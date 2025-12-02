<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function index()
    {
        return Application::with(['system', 'categories'])->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'system_id' => 'required|exists:systems,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        return Application::create($validated);
    }

    public function show($id)
{
    $application = Application::with([
        'system',
        'shortcuts.category'
    ])->findOrFail($id);

    $shortcutsByCategory = $application->shortcuts
        ->groupBy(fn($shortcut) => $shortcut->category->name ?? 'Sin categoría');

    return view('applications_details', [
        'application' => $application,
        'shortcutsByCategory' => $shortcutsByCategory
    ]);
}



    public function update(Request $request, Application $application)
    {
        $validated = $request->validate([
            'system_id' => 'required|exists:systems,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $application->update($validated);

        return $application;
    }

    public function destroy(Application $application)
    {
        $application->delete();
        return response()->json(['message' => 'Application deleted']);
    }



    public function search(Request $request)
{
    $query = $request->input('q', '');

    if (strlen($query) < 1) {
        return response()->json([]);
    }

    $apps = Application::where('name', 'like', '%' . $query . '%')
        ->orderBy('name')
        ->limit(10)
        ->get(['id', 'name', 'description']);

    return response()->json($apps);
}




public function list(Request $request)
{
    $query = $request->input('q');          // búsqueda opcional
    $systemId = $request->input('system_id'); // filtro opcional

    $apps = Application::query()
        ->when($query, function ($q) use ($query) {
            $q->where('name', 'like', "%{$query}%")
              ->orWhere('description', 'like', "%{$query}%");
        })
        ->when($systemId, function ($q) use ($systemId) {
            $q->where('system_id', $systemId);
        })
        ->orderBy('name')
        ->paginate(12); // 🔥 perfecto para tarjetas

    return response()->json($apps);
}





}
