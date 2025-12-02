<?php

namespace App\Http\Controllers;

use App\Models\ShortcutAlias;
use Illuminate\Http\Request;

class ShortcutAliasController extends Controller
{
    public function index()
    {
        return ShortcutAlias::with('shortcut')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'alias' => 'required|string|max:255',
            'shortcut_id' => 'required|exists:shortcuts,id'
        ]);

        return ShortcutAlias::create($validated);
    }

    public function show(ShortcutAlias $shortcutAlias)
    {
        return $shortcutAlias->load('shortcut');
    }

    public function update(Request $request, ShortcutAlias $shortcutAlias)
    {
        $validated = $request->validate([
            'alias' => 'required|string|max:255',
            'shortcut_id' => 'required|exists:shortcuts,id'
        ]);

        $shortcutAlias->update($validated);

        return $shortcutAlias;
    }

    public function destroy(ShortcutAlias $shortcutAlias)
    {
        $shortcutAlias->delete();
        return response()->json(['message' => 'Alias deleted']);
    }
}
