<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Room;
use App\Models\Section;
use Illuminate\Http\Request;

class SchedulesController extends Controller
{

    public function index()
    {
        $schedules = Schedule::with(['teacher', 'subject', 'room', 'section'])->get();
        return view('schedules.index', compact('schedules'));
    }

    public function create()
    {
        $teachers = Teacher::all();
        $subjects = Subject::all();
        $rooms = Room::all();
        $sections = Section::all();

        return view('schedules.create', compact('teachers', 'subjects', 'rooms', 'sections'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'subject_id' => 'required|exists:subjects,id',
            'room_id' => 'required|exists:rooms,id',
            'section_id' => 'required|exists:sections,id',
            'day' => 'required|string|max:50',
            'time_start' => 'required',
            'time_end' => 'required|after:time_start',
        ]);

        Schedule::create($request->all());

        return redirect()->back()->with('success', 'Schedule added successfully!');
    }


    public function show($id)
    {
        $schedule = Schedule::with(['teacher', 'subject', 'room', 'section'])->findOrFail($id);
        return view('schedules.show', compact('schedule'));
    }


    public function edit($id)
    {
        $schedule = Schedule::findOrFail($id);
        $teachers = Teacher::all();
        $subjects = Subject::all();
        $rooms = Room::all();
        $sections = Section::all();

        return view('schedules.edit', compact('schedule', 'teachers', 'subjects', 'rooms', 'sections'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'subject_id' => 'required|exists:subjects,id',
            'room_id' => 'required|exists:rooms,id',
            'section_id' => 'required|exists:sections,id',
            'day' => 'required|string|max:50',
            'time_start' => 'required',
            'time_end' => 'required|after:time_start',
        ]);

        $schedule = Schedule::findOrFail($id);
        $schedule->update($request->all());

        return redirect()->back()->with('success', 'Schedule updated successfully!');
    }

    public function destroy($id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->delete();

        return redirect()->back()->with('success', 'Schedule deleted successfully!');
    }
}
