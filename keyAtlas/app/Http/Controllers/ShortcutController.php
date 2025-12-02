<?php

namespace App\Http\Controllers;

use App\Models\Shortcut;
use Illuminate\Http\Request;

class ShortcutController extends Controller
{
    public function index()
    {
        return Shortcut::with(['system', 'application', 'category', 'aliases'])->get();
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'system_id' => 'required|exists:systems,id',
        'application_id' => 'required|exists:applications,id',
        'category_id' => 'required|exists:categories,id',
        'keys' => 'required|string|max:255',
        'description' => 'required|string|max:255',
    ]);

    Shortcut::create($validated);

    return response()->json(['success' => true]);
}


    public function show(Shortcut $shortcut)
    {
        return $shortcut->load(['system', 'application', 'category', 'aliases']);
    }

    public function update(Request $request, Shortcut $shortcut)
    {
        $validated = $request->validate([
            'system_id' => 'required|exists:systems,id',
            'application_id' => 'required|exists:applications,id',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string|max:255',
            'keys' => 'required|string|max:255',
        ]);

        $shortcut->update($validated);

        return $shortcut;
    }

    public function destroy(Shortcut $shortcut)
    {
        $shortcut->delete();
        return response()->json(['message' => 'Shortcut deleted']);
    }
}
