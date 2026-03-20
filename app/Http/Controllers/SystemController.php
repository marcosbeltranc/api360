<?php

namespace App\Http\Controllers;

use App\Models\System;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SystemController extends Controller
{
    public function get(Request $request)
    {
        $systems = System::with([
            'server',
            'status',
            'priority',
            'area', 
            'areas',
            'responsible',
            'faqs',
            'files'
        ])
        ->orderBy('id', 'desc')
        ->get();

        return response()->json([
            'count' => $systems->count(),
            'data'  => $systems
        ]);
    }

    public function getById(Request $request, $id)
    {
        $system = System::with([
            'server',
            'status',
            'priority',
            'area',
            'areas',
            'responsible',
            'faqs',
            'files'
        ])->findOrFail($id);

        return response()->json($system);
    }

    // Crear sistema
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'server_device_id' => 'required|integer',
        ]);

        $system = System::create($request->except('areas', 'faqs', 'files'));

        if ($request->has('areas')) {
            $system->areas()->sync($request->areas);
        }

        if ($request->has('faqs')) {
            foreach ($request->faqs as $faq) {
                $system->faqs()->create($faq);
            }
        }

        return response()->json([
            'message' => 'Sistema registrado correctamente',
            'data' => $system->load([
                'server',
                'status',
                'priority',
                'area',
                'areas',
                'responsible',
                'faqs',
                'files'
            ])
        ]);
    }

    // Actualizar sistema
    public function update(Request $request, $id)
    {
        $system = System::findOrFail($id);

        $data = $request->except('areas', 'faqs', 'files');
        $data['last_update'] = Carbon::now();

        $system->update($data);

        if ($request->has('areas')) {
            $system->areas()->sync($request->areas);
        }

        if ($request->has('faqs')) {
            $existingIds = $system->faqs()->pluck('id')->toArray();
            $incomingIds = collect($request->faqs)
                ->pluck('id')
                ->filter()
                ->toArray();
            $toDelete = array_diff($existingIds, $incomingIds);
            if (!empty($toDelete)) {
                $system->faqs()->whereIn('id', $toDelete)->delete();
            }
            foreach ($request->faqs as $faqData) {
                if (isset($faqData['id'])) {
                    $system->faqs()
                        ->where('id', $faqData['id'])
                        ->update([
                            'question' => $faqData['question'],
                            'answer'   => $faqData['answer'],
                            'tags'     => $faqData['tags'] ?? [],
                        ]);
                } else {
                    $system->faqs()->create($faqData);
                }
            }
        }

        return response()->json([
            'message' => 'Cambios guardados',
            'data' => $system->load([
                'server',
                'status',
                'priority',
                'area',
                'areas',
                'responsible',
                'faqs',
                'files'
            ])
        ]);
    }

    // Soft delete
    public function delete($id)
    {
        $system = System::findOrFail($id);
        $system->delete();

        return response()->json([
            'message' => 'Sistema eliminado de la lista activa'
        ]);
    }
}