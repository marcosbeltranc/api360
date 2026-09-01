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

        Mail::to('cesar.lopez@mepiel.com.mx')
            ->cc([
                'mario.almendarez@mepiel.com.mx',
                'jorge.guillen@mepiel.com.mx',
                'isai.zuniga@mepiel.com.mx',
            ])
            ->send(new ServerAccessRequestMail($accessRequest));

        return response()->json([
            'message' => 'Solicitud enviada correctamente',
            'data' => $accessRequest
        ]);
    }
}
