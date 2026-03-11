<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;

class TeachersController extends Controller
{
    // Display all teachers
    public function index()
    {
        $teachers = Teacher::all();
        return view('teachers.index', compact('teachers'));
    }

    // Show form for creating a new teacher
    public function create()
    {
        return view('teachers.create');
    }

    // Store a new teacher in the database
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'employment_status' => 'required|string|max:255',
        ]);

        Teacher::create($request->only('name', 'employment_status'));
        return redirect()->back()->with('success', 'Teacher added successfully!');
    }

    // Show a single teacher
    public function show($id)
    {
        $teacher = Teacher::findOrFail($id);
        return view('teachers.show', compact('teacher'));
    }

    // Show form to edit a teacher
    public function edit($id)
    {
        $teacher = Teacher::findOrFail($id);
        return view('teachers.edit', compact('teacher'));
    }

    // Update a teacher in the database
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'employment_status' => 'required|string|max:255',
        ]);

        $teacher = Teacher::findOrFail($id);
        $teacher->update($request->only('name', 'employment_status'));

        return redirect()->back()->with('success', 'Teacher updated successfully!');
    }

    // Delete a teacher
    public function destroy($id)
    {
        $teacher = Teacher::findOrFail($id);
        $teacher->delete();

        return redirect()->back()->with('success', 'Teacher deleted successfully!');
    }
}
