<?php

namespace App\Http\Controllers;

use App\Models\System;
use Illuminate\Http\Request;

class SystemController extends Controller
{
    public function index()
    {
        return System::with('applications')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255'
        ]);

        return System::create($validated);
    }

    public function show(System $system)
    {
        return $system->load('applications');
    }

    public function update(Request $request, System $system)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $system->update($validated);

        return $system;
    }

    public function destroy(System $system)
    {
        $system->delete();
        return response()->json(['message' => 'System deleted']);
    }
}
