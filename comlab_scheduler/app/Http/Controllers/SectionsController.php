<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;

class SectionsController extends Controller
{

    public function index()
    {
        $sections = Section::all();
        return view('sections.index', compact('sections'));
    }


    public function create()
    {
        return view('sections.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'section' => 'required|string|max:255',
        ]);

        Section::create($request->only('section'));
        return redirect()->back()->with('success', 'Section added successfully!');
    }

    public function show($id)
    {
        $section = Section::findOrFail($id);
        return view('sections.show', compact('section'));
    }

    public function edit($id)
    {
        $section = Section::findOrFail($id);
        return view('sections.edit', compact('section'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'section' => 'required|string|max:255',
        ]);

        $section = Section::findOrFail($id);
        $section->update($request->only('section'));

        return redirect()->back()->with('success', 'Section updated successfully!');
    }

    public function destroy($id)
    {
        $section = Section::findOrFail($id);
        $section->delete();

        return redirect()->back()->with('success', 'Section deleted successfully!');
    }
}
