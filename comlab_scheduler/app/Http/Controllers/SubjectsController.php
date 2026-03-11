<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectsController extends Controller
{
    // Display all subjects
    public function index()
    {
        $subjects = Subject::all();
        return view('subjects.index', compact('subjects'));
    }

    // Show form for creating a new subject
    public function create()
    {
        return view('subjects.create');
    }

    // Store a new subject in the database
    public function store(Request $request)
    {
        $request->validate([
            'subject_code' => 'required|string|max:255',
            'subject_name' => 'required|string|max:255',
        ]);

        Subject::create($request->only('subject_code', 'subject_name'));
        return redirect()->back()->with('success', 'Subject added successfully!');
    }

    // Show a single subject
    public function show($id)
    {
        $subject = Subject::findOrFail($id);
        return view('subjects.show', compact('subject'));
    }

    // Show form to edit a subject
    public function edit($id)
    {
        $subject = Subject::findOrFail($id);
        return view('subjects.edit', compact('subject'));
    }

    // Update a subject in the database
    public function update(Request $request, $id)
    {
        $request->validate([
            'subject_code' => 'required|string|max:255',
            'subject_name' => 'required|string|max:255',
        ]);

        $subject = Subject::findOrFail($id);
        $subject->update($request->only('subject_code', 'subject_name'));

        return redirect()->back()->with('success', 'Subject updated successfully!');
    }

    // Delete a subject
    public function destroy($id)
    {
        $subject = Subject::findOrFail($id);
        $subject->delete();

        return redirect()->back()->with('success', 'Subject deleted successfully!');
    }
}
