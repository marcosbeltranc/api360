<?php

namespace App\Http\Controllers;

use App\Models\IntegrationFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class IntegrationFileController extends Controller
{
    // LISTAR ARCHIVOS POR INTEGRACIÓN
    public function get(Request $request)
    {
        $files = IntegrationFile::where('integration_id', $request->integration_id)
            ->orderBy('id', 'desc')
            ->get();

        return response()->json($files);
    }

    // SUBIR ARCHIVO
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:20480', // 20MB Max
            'integration_id' => 'required|integer|exists:integrations,id'
        ]);

        $file = $request->file('file');

        $path = $file->store(
            'integrations/' . $request->integration_id,
            'system_files' // Reutiliza el disco configurado
        );
        
        $tags = $request->tags;
        if (is_string($tags)) {
            $tags = json_decode($tags, true);
        }

        $newFile = IntegrationFile::create([
            'integration_id' => $request->integration_id,
            'name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_type' => $file->getClientOriginalExtension(),
            'size' => $file->getSize(),
            'tags' => $tags
        ]);

        return response()->json($newFile);
    }

    // DESCARGAR
    public function download($id)
    {
        $file = IntegrationFile::findOrFail($id);

        return Storage::disk('system_files')
            ->download($file->file_path, $file->name);
    }

    // PREVIEW
    public function preview($id)
    {
        $file = IntegrationFile::findOrFail($id);

        return response()->file(
            Storage::disk('system_files')->path($file->file_path)
        );
    }

    // ACTUALIZAR (Tags / Nombre)
    public function update(Request $request, $id)
    {
        $file = IntegrationFile::findOrFail($id);

        $file->update([
            'name' => $request->name ?? $file->name,
            'tags' => $request->tags ?? $file->tags,
        ]);

        return response()->json($file);
    }

    // ELIMINAR
    public function delete($id)
    {
        $file = IntegrationFile::findOrFail($id);

        Storage::disk('system_files')->delete($file->file_path);
        $file->delete();

        return response()->json([
            'message' => 'Archivo eliminado'
        ]);
    }
}