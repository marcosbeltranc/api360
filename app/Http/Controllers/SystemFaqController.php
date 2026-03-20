<?php

namespace App\Http\Controllers;

use App\Models\SystemFaq;
use Illuminate\Http\Request;

class SystemFaqController extends Controller
{
    public function get(Request $request)
    {
        $query = SystemFaq::with('system')
            ->where('is_active', true);

        // filtro por sistema
        if ($request->system_id) {
            $query->where('system_id', $request->system_id);
        }

        // búsqueda por texto
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('question', 'like', "%{$request->search}%")
                  ->orWhere('answer', 'like', "%{$request->search}%");
            });
        }

        // filtro por tag
        if ($request->tag) {
            $query->whereJsonContains('tags', $request->tag);
        }

        $faqs = $query->orderBy('order')->get();

        return response()->json([
            'count' => $faqs->count(),
            'data'  => $faqs
        ]);
    }

    public function getById(Request $request, $id)
    {
        $faq = SystemFaq::with('system')->findOrFail($id);

        return response()->json($faq);
    }

    public function create(Request $request)
    {
        $request->validate([
            'system_id' => 'required|exists:system,id',
            'question'  => 'required|string',
            'answer'    => 'required|string',
        ]);

        $faq = SystemFaq::create($request->all());

        return response()->json([
            'message' => 'FAQ registrada correctamente',
            'data' => $faq->load('system')
        ]);
    }

    public function update(Request $request, $id)
    {
        $faq = SystemFaq::findOrFail($id);

        $faq->update($request->all());

        return response()->json([
            'message' => 'Cambios guardados',
            'data' => $faq->load('system')
        ]);
    }

    public function delete($id)
    {
        $faq = SystemFaq::findOrFail($id);
        $faq->delete();

        return response()->json([
            'message' => 'FAQ eliminada de la lista activa'
        ]);
    }

    public function grouped()
    {
        $faqs = SystemFaq::with('system')
            ->where('is_active', true)
            ->get()
            ->groupBy('system.name');

        return response()->json($faqs);
    }
}