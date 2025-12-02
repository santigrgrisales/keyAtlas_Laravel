<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shortcut;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query      = $request->input('query', '');
        $systemId   = $request->input('system_id');
        $appId      = $request->input('application_id');
        $categoryIds = $request->category_ids ?? [];
        $limit      = $request->input('limit', 15);

        // Si NO hay ningún filtro ni búsqueda → retornar vacío
        if (!$query && !$systemId && !$appId && !$categoryIds) {
            return response()->json([]);
        }

        $results = Shortcut::query()

            // FILTRO POR TEXTO
            ->when($query, function ($q) use ($query) {
                $q->where(function ($sub) use ($query) {
                    $sub->where('description', 'like', "%$query%")
                        ->orWhere('keys', 'like', "%$query%");
                });
            })

            // FILTRO POR SISTEMA
            ->when($systemId, function ($q) use ($systemId) {
                $q->whereHas('application', function ($app) use ($systemId) {
                $app->where('system_id', $systemId);
                });
            })


            // FILTRO POR APLICACIÓN
            ->when($appId, function ($q) use ($appId) {
                $q->where('application_id', $appId);
            })

            // FILTRO POR VARIAS CATEGORÍAS
            ->when($categoryIds, function ($q) use ($categoryIds) {
                $q->whereIn('category_id', $categoryIds);
           })


            ->limit($limit)
            ->with(['application', 'category', 'system']) // información para mostrar
            ->get();

        return response()->json($results);
    }
}
