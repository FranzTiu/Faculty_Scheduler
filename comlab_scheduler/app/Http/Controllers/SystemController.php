<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Room;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Schedule;
use Illuminate\Support\Facades\DB;

class SystemController extends Controller
{
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

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return response()->json(["success" => true, "user" => ["username" => Auth::user()->username]]);
        }

        return response()->json(["success" => false, "message" => "Invalid username or password"]);
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

    // Rooms logic
    public function getRooms()
    {
        return response()->json(Room::orderBy('room_name', 'asc')->get()->map(function ($r) {
            return [
                'id' => $r->id,
                'name' => $r->room_name,
                'campus' => $r->campus
            ];
        }));
    }

    public function addRoom(Request $request)
    {
        $room = Room::create([
            'room_name' => $request->name,
            'campus' => $request->location ?? 'Main Campus'
        ]);
        return response()->json(["success" => true, "id" => $room->id]);
    }

    public function updateRoom(Request $request, $id)
    {
        $room = Room::find($id);
        if ($room) {
            $room->update([
                'room_name' => $request->name,
                'campus' => $request->location ?? $room->campus
            ]);
            return response()->json(["success" => true]);
        }
        return response()->json(["success" => false]);
    }

    public function deleteRoom($id)
    {
        Room::destroy($id);
        return response()->json(["success" => true]);
    }

    // Subjects logic
    public function getSubjects()
    {
        return response()->json(Subject::orderBy('subject_code', 'asc')->get()->map(function ($s) {
            return [
                'id' => $s->id,
                'code' => $s->subject_code,
                'name' => $s->subject_name,
                'units' => 3
            ];
        }));
    }

    public function addSubject(Request $request)
    {
        DB::beginTransaction();
        try {
            $subject = Subject::create([
                'subject_code' => $request->code,
                'subject_name' => $request->name
            ]);

            if ($request->has('room_id') && $request->room_id) {
                // Not standard pattern but adapting legacy logic
                Schedule::create([
                    'subject_id' => $subject->id,
                    'room_id' => current(explode(',', $request->room_id)) // fallback
                ]);
            }

            DB::commit();
            return response()->json(["success" => true, "id" => $subject->id]);
        }
        catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["success" => false, "error" => $e->getMessage()]);
        }
    }

    public function updateSubject(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $subject = Subject::find($id);
            if ($subject) {
                $subject->update([
                    'subject_code' => $request->code,
                    'subject_name' => $request->name
                ]);

                // Legacy: clean up standalone
                Schedule::where('subject_id', $id)->whereNull('teacher_id')->delete();

                if ($request->has('room_id') && $request->room_id) {
                    Schedule::create([
                        'subject_id' => $id,
                        'room_id' => current(explode(',', $request->room_id))
                    ]);
                }
            }
            DB::commit();
            return response()->json(["success" => true]);
        }
        catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["success" => false, "error" => $e->getMessage()]);
        }
    }

    public function deleteSubject($id)
    {
        Subject::destroy($id);
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
        $schedules = Schedule::with(['teacher', 'subject', 'room', 'section'])->orderBy('day')->orderBy('time_start')->get();
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
        $faculty_id = $request->faculty_id ? intval($request->faculty_id) : 0;
        $subject_id = $request->subject_id ? intval($request->subject_id) : 0;
        $room_id = $request->room_id ? intval($request->room_id) : 0;

        if ($room_id === 0 || $faculty_id === 0 || $subject_id === 0) {
            return response()->json(["success" => false, "error" => "Please select a valid Faculty, Subject and Room. If they aren't in the list, please create them first using their respective management pages."]);
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
        // Legacy handling: Update all rows belonging to teacher + original subject
        $schedule = Schedule::find($id);
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
            $subject = Subject::firstOrCreate(['subject_code' => $request->subject_code]);
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

        Schedule::where('teacher_id', $teacher_id)->where('subject_id', $original_subject_id)->update($updates);
        return response()->json(["success" => true]);
    }

    public function deleteSchedule($id)
    {
        Schedule::destroy($id);
        return response()->json(["success" => true]);
    }

    // Custom APIs for viewing
    public function getTeacherSchedule()
    {
        $schedules = Schedule::with(['teacher', 'subject', 'room', 'section'])->get();
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
        $schedules = Schedule::with(['teacher', 'subject', 'room', 'section'])
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
