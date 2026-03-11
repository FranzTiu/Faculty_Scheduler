<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomsController extends Controller
{

    public function index()
    {
        $rooms = Room::all();
        return view('rooms.index', compact('rooms'));
    }


    public function create()
    {
        return view('rooms.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'room_name' => 'required|string|max:255',
            'campus' => 'required|string|max:255',
        ]);

        Room::create($request->only('room_name', 'campus'));

        return redirect()->back()->with('success', 'Room added successfully!');
    }


    public function show($id)
    {
        $room = Room::findOrFail($id);
        return view('rooms.show', compact('room'));
    }


    public function edit($id)
    {
        $room = Room::findOrFail($id);
        return view('rooms.edit', compact('room'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'room_name' => 'required|string|max:255',
            'campus' => 'required|string|max:255',
        ]);

        $room = Room::findOrFail($id);
        $room->update($request->only('room_name', 'campus'));

        return redirect()->back()->with('success', 'Room updated successfully!');
    }


    public function destroy($id)
    {
        $room = Room::findOrFail($id);
        $room->delete();

        return redirect()->back()->with('success', 'Room deleted successfully!');
    }
}
