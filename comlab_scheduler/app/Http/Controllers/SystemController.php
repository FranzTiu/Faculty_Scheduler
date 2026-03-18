<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Room;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Schedule;
use App\Models\Semester;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SystemController extends Controller
{
    private function resolveSemester(Request $request): Semester
    {
        $requested = $request->query('semester_id');
        if ($requested) {
            $sem = Semester::find($requested);
            if ($sem) {
                $request->session()->put('semester_id', $sem->id);
                return $sem;
            }
        }

        $sessionId = $request->session()->get('semester_id');
        if ($sessionId) {
            $sem = Semester::find($sessionId);
            if ($sem) {
                return $sem;
            }
        }

        $active = Semester::query()->where('is_active', true)->orderByDesc('created_at')->first();
        if ($active) {
            $request->session()->put('semester_id', $active->id);
            return $active;
        }

        $latest = Semester::query()->orderByDesc('created_at')->first();
        if ($latest) {
            $request->session()->put('semester_id', $latest->id);
            return $latest;
        }

        // Absolute fallback: create one (prevents "no semester selected" state).
        $created = Semester::create([
            'term' => '1st',
            'school_year' => '2025-2026',
            'curriculum_mode' => 'custom',
            'is_active' => true,
        ]);
        $request->session()->put('semester_id', $created->id);
        return $created;
    }


    // Auth logic
    public function checkAuth()
    {
        if (Auth::check()) {
            return response()->json(["authenticated" => true, "username" => Auth::user()->username]);
        }
        return response()->json(["authenticated" => false]);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return response()->json(["success" => true, "user" => ["username" => Auth::user()->username]]);
        }

        return response()->json(["success" => false, "message" => "Invalid username or password"]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user) {
            return response()->json(["success" => false, "message" => "Username not found"]);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(["success" => true, "message" => "Password has been reset successfully"]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(["success" => true]);
        }

        return redirect()->route('login');
    }

    // Semesters
    public function getSemesters(Request $request)
    {
        $current = $this->resolveSemester($request);
        $semesters = Semester::query()->orderByDesc('created_at')->get()->map(function ($s) use ($current) {
            return [
                'id' => $s->id,
                'term' => $s->term,
                'school_year' => $s->school_year,
                'label' => trim(($s->term ?? '') . ' Semester (' . ($s->school_year ?? '') . ')'),
                'curriculum_mode' => $s->curriculum_mode,
                'is_active' => (bool) $s->is_active,
                'is_selected' => $s->id === $current->id,
            ];
        });

        return response()->json([
            'current' => [
                'id' => $current->id,
                'term' => $current->term,
                'school_year' => $current->school_year,
                'label' => trim(($current->term ?? '') . ' Semester (' . ($current->school_year ?? '') . ')'),
                'curriculum_mode' => $current->curriculum_mode,
                'is_active' => (bool) $current->is_active,
            ],
            'items' => $semesters,
        ]);
    }

    public function createSemester(Request $request)
    {
        $request->validate([
            'term' => ['required', 'string', Rule::in(['1st', '2nd', 'Summer'])],
            'school_year' => ['required', 'string', 'max:32'],
            'use_default_curriculum' => ['required', 'boolean'],
        ]);

        $hasActive = Semester::query()->where('is_active', true)->exists();
        $semester = Semester::create([
            'term' => $request->term,
            'school_year' => trim($request->school_year),
            'curriculum_mode' => $request->boolean('use_default_curriculum') ? 'default' : 'custom',
            'is_active' => $hasActive ? false : true,
        ]);

        if ($semester->curriculum_mode === 'default') {
            $this->seedDefaultCurriculum($semester);
        }

        // Always select the newly created semester after creation.
        $request->session()->put('semester_id', $semester->id);

        return response()->json([
            'success' => true,
            'semester' => [
                'id' => $semester->id,
                'term' => $semester->term,
                'school_year' => $semester->school_year,
                'label' => trim(($semester->term ?? '') . ' Semester (' . ($semester->school_year ?? '') . ')'),
                'curriculum_mode' => $semester->curriculum_mode,
                'is_active' => (bool) $semester->is_active,
            ],
        ]);
    }

    public function activateSemester(Request $request, $id)
    {
        $semester = Semester::findOrFail($id);
        DB::transaction(function () use ($semester) {
            Semester::query()->where('is_active', true)->update(['is_active' => false]);
            $semester->update(['is_active' => true]);
        });

        $request->session()->put('semester_id', $semester->id);
        return response()->json(['success' => true]);
    }

    public function deleteSemester(Request $request, $id)
    {
        $semester = Semester::findOrFail($id);
        
        $count = Semester::count();
        if ($count <= 1) {
            return response()->json(['success' => false, 'message' => 'Cannot delete the only semester in the system.']);
        }

        DB::transaction(function () use ($semester) {
            // Cascade delete the associated relationships to keep DB clean
            \App\Models\Schedule::where('semester_id', $semester->id)->delete();
            \App\Models\Subject::where('semester_id', $semester->id)->delete();
            \App\Models\Room::where('semester_id', $semester->id)->delete();
            
            $wasActive = $semester->is_active;
            $semester->delete();

            // Handle fallback scenario if we deleted the currently active semester
            if ($wasActive) {
                $latest = Semester::orderByDesc('created_at')->first();
                if ($latest) {
                    $latest->update(['is_active' => true]);
                }
            }
        });

        // Current session reset
        if ($request->session()->get('semester_id') == $id) {
            $request->session()->forget('semester_id');
        }

        return response()->json(['success' => true]);
    }

    private function seedDefaultCurriculum(Semester $semester): void
    {
        $term = $semester->term;

        $rooms = \App\Support\DefaultCurriculum::rooms();
        foreach ($rooms as $r) {
            Room::firstOrCreate(
                ['semester_id' => $semester->id, 'room_name' => $r['room_name']],
                ['campus' => $r['campus']]
            );
        }

        $subjects = \App\Support\DefaultCurriculum::subjectsForTerm($term);
        foreach ($subjects as $s) {
            Subject::firstOrCreate(
                ['semester_id' => $semester->id, 'subject_code' => $s['subject_code']],
                ['subject_name' => $s['subject_name'], 'year_level' => $s['year_level']]
            );
        }
    }

    // Rooms logic
    public function getRooms()
    {
        $semester = $this->resolveSemester(request());
        return response()->json(Room::where('semester_id', $semester->id)->orderBy('room_name', 'asc')->get()->map(function ($r) {
            return [
                'id' => $r->id,
                'name' => $r->room_name,
                'campus' => $r->campus
            ];
        }));
    }

    public function addRoom(Request $request)
    {
        $semester = $this->resolveSemester($request);

        try {
            $request->validate([
                'name'     => 'required|string|max:255',
                'location' => 'required|string|max:255',
            ]);

            $room = Room::create([
                'semester_id' => $semester->id,
                'room_name' => trim($request->name),
                'campus'    => trim($request->location),
            ]);

            return response()->json(["success" => true, "id" => $room->id, "name" => $room->room_name, "campus" => $room->campus]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(["success" => false, "errors" => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "error" => $e->getMessage()], 500);
        }
    }

    public function updateRoom(Request $request, $id)
    {
        $semester = $this->resolveSemester($request);

        try {
            $request->validate([
                'name'     => 'required|string|max:255',
                'location' => 'required|string|max:255',
            ]);

            $room = Room::where('semester_id', $semester->id)->find($id);
            if (!$room) {
                return response()->json(["success" => false, "error" => "Room not found."], 404);
            }

            $room->update([
                'room_name' => trim($request->name),
                'campus'    => trim($request->location),
            ]);

            return response()->json(["success" => true, "name" => $room->room_name, "campus" => $room->campus]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(["success" => false, "errors" => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "error" => $e->getMessage()], 500);
        }
    }

    public function deleteRoom($id)
    {
        $semester = $this->resolveSemester(request());

        $room = Room::where('semester_id', $semester->id)->find($id);
        if (!$room) {
            return response()->json(["success" => false, "error" => "Room not found."], 404);
        }
        $room->delete();
        return response()->json(["success" => true]);
    }

    // Subjects logic
    public function getSubjects()
    {
        $semester = $this->resolveSemester(request());
        return response()->json(Subject::where('semester_id', $semester->id)->orderBy('subject_code', 'asc')->get()->map(function ($s) {
            return [
                'id' => $s->id,
                'code' => $s->subject_code,
                'name' => $s->subject_name,
                'year_level' => $s->year_level,
                'units' => 3,
            ];
        }));
    }
    public function addSubject(Request $request)
    {
        $semester = $this->resolveSemester($request);

        $request->validate([
            'code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('subjects', 'subject_code')->where(fn ($q) => $q->where('semester_id', $semester->id)),
            ],
            'name' => 'required|string|max:255',
            'year_level' => 'nullable|integer|min:1|max:4',
        ]);

        try {
            $subject = Subject::create([
                'semester_id' => $semester->id,
                'subject_code' => trim($request->code),
                'subject_name' => trim($request->name),
                'year_level' => $request->year_level ? intval($request->year_level) : null,
            ]);

            return response()->json(["success" => true, "id" => $subject->id]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(["success" => false, "errors" => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "error" => $e->getMessage()], 500);
        }
    }

    public function updateSubject(Request $request, $id)
    {
        $semester = $this->resolveSemester($request);

        $request->validate([
            'code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('subjects', 'subject_code')
                    ->where(fn ($q) => $q->where('semester_id', $semester->id))
                    ->ignore($id),
            ],
            'name' => 'required|string|max:255',
            'year_level' => 'nullable|integer|min:1|max:4',
        ]);

        try {
            $subject = Subject::where('semester_id', $semester->id)->find($id);
            if (!$subject) {
                return response()->json(["success" => false, "error" => "Subject not found."], 404);
            }

            $subject->update([
                'subject_code' => trim($request->code),
                'subject_name' => trim($request->name),
                'year_level' => $request->year_level ? intval($request->year_level) : null,
            ]);

            return response()->json(["success" => true]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(["success" => false, "errors" => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "error" => $e->getMessage()], 500);
        }
    }

    public function deleteSubject($id)
    {
        $semester = $this->resolveSemester(request());

        Subject::where('semester_id', $semester->id)->where('id', $id)->delete();
        return response()->json(["success" => true]);
    }

    // Teachers (Faculty) logic
    public function getFaculty()
    {
        return response()->json(Teacher::orderBy('name', 'asc')->get()->map(function ($t) {
            return [
                'id' => $t->id,
                'name' => $t->name,
                'employment_status' => $t->employment_status
            ];
        }));
    }

    public function addFaculty(Request $request)
    {
        $teacher = Teacher::create([
            'name' => $request->name,
            'employment_status' => $request->employment_status ?? 'Full-time'
        ]);
        return response()->json(["success" => true, "id" => $teacher->id]);
    }

    public function updateFaculty(Request $request, $id)
    {
        $teacher = Teacher::find($id);
        if ($teacher) {
            $updates = [];
            if ($request->has('name'))
                $updates['name'] = $request->name;
            if ($request->has('employment_status') && $request->employment_status !== '') {
                $updates['employment_status'] = $request->employment_status;
            }
            $teacher->update($updates);
            return response()->json(["success" => true]);
        }
        return response()->json(["success" => false]);
    }

    public function deleteFaculty($id)
    {
        Teacher::destroy($id);
        return response()->json(["success" => true]);
    }

    // Schedules logic
    public function getSchedules()
    {
        $semester = $this->resolveSemester(request());
        $schedules = Schedule::with(['teacher', 'subject', 'room', 'section'])
            ->where('semester_id', $semester->id)
            ->orderBy('day')->orderBy('time_start')
            ->get();
        return response()->json($schedules->map(function ($s) {
            return [
                'id' => $s->id,
                'faculty_id' => $s->teacher_id,
                'subject_id' => $s->subject_id,
                'room_id' => $s->room_id,
                'day' => $s->day,
                'section' => $s->section ? $s->section->section : null,
                'start_time' => $s->time_start,
                'end_time' => $s->time_end,
                'faculty_name' => $s->teacher ? $s->teacher->name : null,
                'subject_code' => $s->subject ? $s->subject->subject_code : null,
                'subject_name' => $s->subject ? $s->subject->subject_name : null,
                'room_name' => $s->room ? $s->room->room_name : null
            ];
        }));
    }

    public function addSchedule(Request $request)
    {
        $semester = $this->resolveSemester($request);

        $faculty_id = $request->faculty_id ? intval($request->faculty_id) : 0;
        $subject_id = $request->subject_id ? intval($request->subject_id) : 0;
        $room_id = $request->room_id ? intval($request->room_id) : 0;

        if ($room_id === 0 || $faculty_id === 0 || $subject_id === 0) {
            return response()->json(["success" => false, "error" => "Please select a valid Faculty, Subject and Room. If they aren't in the list, please create them first using their respective management pages."]);
        }

        $subject = Subject::where('semester_id', $semester->id)->find($subject_id);
        $room = Room::where('semester_id', $semester->id)->find($room_id);
        if (!$subject || !$room) {
            return response()->json(["success" => false, "error" => "Selected Subject/Room does not belong to the currently selected semester."]);
        }


        // The legacy DB maps 'section' as a string, but the new migration uses 'section_id'.
        // If the 'sections' table is there, we should link it. Or just insert section string if we modify migration
        // Let's assume section_id is a foreign key in schedules, we need to find or create Section.
        $section_id = null;
        if (!empty($request->section)) {
            $sectionModel = \App\Models\Section::firstOrCreate(['section' => $request->section]);
            $section_id = $sectionModel->id;
        }

        $schedule = Schedule::create([
            'semester_id' => $semester->id,
            'teacher_id' => $faculty_id,
            'subject_id' => $subject_id,
            'room_id' => $room_id,
            'section_id' => $section_id,
            'day' => $request->day,
            'time_start' => $request->start_time,
            'time_end' => $request->end_time
        ]);

        return response()->json(["success" => true, "id" => $schedule->id]);
    }

    public function updateSchedule(Request $request, $id)
    {
        $semester = $this->resolveSemester($request);
        // Legacy handling: Update all rows belonging to teacher + original subject
        $schedule = Schedule::where('semester_id', $semester->id)->find($id);
        if (!$schedule) {
            return response()->json(["success" => false, "error" => "Schedule not found"]);
        }

        $teacher_id = $schedule->teacher_id;
        $original_subject_id = $schedule->subject_id;

        $updates = [];
        if ($request->has('section')) {
            $sectionModel = \App\Models\Section::firstOrCreate(['section' => $request->section]);
            $updates['section_id'] = $sectionModel->id;
        }

        if (!empty($request->subject_code)) {
            $subject = Subject::firstOrCreate(
                ['semester_id' => $semester->id, 'subject_code' => $request->subject_code],
                ['subject_name' => $request->subject_name ?: $request->subject_code]
            );
            if (!empty($request->subject_name)) {
                $subject->update(['subject_name' => $request->subject_name]);
            }
            $updates['subject_id'] = $subject->id;
        }
        else if (!empty($request->subject_name)) {
            $subject = Subject::find($original_subject_id);
            if ($subject) {
                $subject->update(['subject_name' => $request->subject_name]);
            }
        }

        if (empty($updates)) {
            return response()->json(["success" => true, "message" => "No explicit updates"]);
        }

        Schedule::where('semester_id', $semester->id)->where('teacher_id', $teacher_id)->where('subject_id', $original_subject_id)->update($updates);
        return response()->json(["success" => true]);
    }

    public function deleteSchedule($id)
    {
        $semester = $this->resolveSemester(request());
        Schedule::where('semester_id', $semester->id)->where('id', $id)->delete();
        return response()->json(["success" => true]);
    }

    // Custom APIs for viewing
    public function getTeacherSchedule()
    {
        $semester = $this->resolveSemester(request());
        $schedules = Schedule::with(['teacher', 'subject', 'room', 'section'])->where('semester_id', $semester->id)->get();
        $grouped = [];
        foreach ($schedules as $s) {
            if (!$s->teacher)
                continue;
            $name = $s->teacher->name;
            if (!isset($grouped[$name])) {
                $grouped[$name] = [];
            }
            $grouped[$name][] = [
                'id' => $s->id,
                'subject_code' => $s->subject ? $s->subject->subject_code : null,
                'subject_name' => $s->subject ? $s->subject->subject_name : null,
                'section' => $s->section ? $s->section->section : null,
                'room_name' => $s->room ? $s->room->room_name : null,
                'day' => $s->day,
                'start_time' => $s->time_start,
                'end_time' => $s->time_end
            ];
        }
        return response()->json($grouped);
    }

    public function getLabSchedule()
    {
        $semester = $this->resolveSemester(request());
        $schedules = Schedule::with(['teacher', 'subject', 'room', 'section'])
            ->where('semester_id', $semester->id)
            ->orderBy('day')->orderBy('time_start')->get();

        $labs = [];
        $others = [];
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        foreach ($schedules as $s) {
            if (!$s->room)
                continue;
            $r_name = strtoupper($s->room->room_name);
            $isLab = str_starts_with($r_name, 'COMLAB') || str_starts_with($r_name, 'COMPLAB');

            $item = [
                'room_name' => $s->room->room_name,
                'campus' => $s->room->campus ?? null,
                'day' => $s->day,
                'start_time' => $s->time_start,
                'end_time' => $s->time_end,
                'subject_code' => $s->subject ? $s->subject->subject_code : null,
                'faculty_name' => $s->teacher ? $s->teacher->name : null,
                'section' => $s->section ? $s->section->section : null
            ];

            if ($isLab) {
                if (!isset($labs[$r_name]))
                    $labs[$r_name] = [];
                if (!isset($labs[$r_name][$s->day]))
                    $labs[$r_name][$s->day] = [];
                $labs[$r_name][$s->day][] = $item;
            }
            else {
                if (!isset($others[$r_name]))
                    $others[$r_name] = [];
                if (!isset($others[$r_name][$s->day]))
                    $others[$r_name][$s->day] = [];
                $others[$r_name][$s->day][] = $item;
            }
        }

        uksort($labs, function ($a, $b) {
            $numA = intval(preg_replace('/[^0-9]/', '', $a));
            $numB = intval(preg_replace('/[^0-9]/', '', $b));
            return $numA <=> $numB;
        });

        ksort($others);

        return response()->json([
            'labs' => $labs,
            'others' => $others
        ]);
    }
}
