<?php

namespace App\Http\Controllers;
use App\Models\ServerAccessRequest;
use App\Models\ServerDevice;
use App\Mail\ServerAccessRequestMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class ServerAccessRequestController extends Controller
{
    public function store(Request $request, $serverId)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
            'start_at' => 'required|date',
            'end_at' => 'nullable|date|after:start_at',
        ]);

        $user = auth()->user();
        $server = ServerDevice::findOrFail($serverId);

        $accessRequest = ServerAccessRequest::create([
            'user_id' => $user->id,
            'server_id' => $server->id,
            'reason' => $request->reason,
            'start_at' => $request->start_at,
            'end_at' => $request->end_at,
            'status' => 'pending',
        ]);

        Mail::to('mabc181@hotmail.com')
            ->cc([
                'alfredo.mondragonc+cc1@mepiel.com.mx',
                'alfredo.mondragonc+cc2@mepiel.com.mx',
                'alfredo.mondragonc+cc3@mepiel.com.mx',
            ])
            ->send(new ServerAccessRequestMail($accessRequest));

        return response()->json([
            'message' => 'Solicitud enviada correctamente',
            'data' => $accessRequest
        ]);
    }
}
