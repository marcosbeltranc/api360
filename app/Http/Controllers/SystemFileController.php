<?php

namespace App\Http\Controllers;

use App\Models\SystemFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SystemFileController extends Controller
{
    // LISTAR
    public function get(Request $request)
    {
        $files = SystemFile::where('system_id', $request->system_id)
            ->orderBy('id', 'desc')
            ->get();

        return response()->json($files);
    }

    // SUBIR
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:20480', // 20MB
            'system_id' => 'required|integer'
        ]);

        $file = $request->file('file');

        $path = $file->store(
            'systems/' . $request->system_id,
            'system_files'
        );
        
        $tags = $request->tags;

        if (is_string($tags)) {
            $tags = json_decode($tags, true);
        }

        $newFile = SystemFile::create([
            'system_id' => $request->system_id,
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
        $file = SystemFile::findOrFail($id);

        return Storage::disk('system_files')
            ->download($file->file_path, $file->name);
    }

    // PREVIEW (PDF)
    public function preview($id)
    {
        $file = SystemFile::findOrFail($id);

        return response()->file(
            Storage::disk('system_files')->path($file->file_path)
        );
    }

    // ACTUALIZAR (tags / nombre)
    public function update(Request $request, $id)
    {
        $file = SystemFile::findOrFail($id);

        $file->update([
            'name' => $request->name ?? $file->name,
            'tags' => $request->tags ?? $file->tags,
        ]);

        return response()->json($file);
    }

    // ELIMINAR
    public function delete($id)
    {
        $file = SystemFile::findOrFail($id);

        Storage::disk('system_files')->delete($file->file_path);

        $file->delete();

        return response()->json([
            'message' => 'Archivo eliminado'
        ]);
    }
}