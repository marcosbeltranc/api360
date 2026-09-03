<?php

namespace App\Http\Controllers;

use App\Models\Integration;
use App\Models\OptionList;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class IntegrationController extends Controller
{
    private const RELATIONS = [
        'sourceSystem',
        'destinationSystem',
        'server',             // <- Carga la info del servidor (nombre, IP, etc.)
        'responsible',
        'integrationType',
        'criticality',
        'status',
        'authenticationMethod',
        'triggerType',
        'files'
    ];

    public function get()
    {
        $integrations = Integration::with(self::RELATIONS)->latest()->get();

        return response()->json([
            'count' => $integrations->count(),
            'data'  => $integrations,
        ]);
    }

    public function getById(int $id)
    {
        return response()->json(Integration::with(self::RELATIONS)->findOrFail($id));
    }

    public function create(Request $request)
    {
        $this->ensureAdministrator($request);
        $integration = Integration::create($this->validatedData($request));

        return response()->json([
            'message' => 'Integración registrada correctamente',
            'data'    => $integration->load(self::RELATIONS),
        ], 201);
    }

    public function update(Request $request, int $id)
    {
        $this->ensureAdministrator($request);
        $integration = Integration::findOrFail($id);
        $integration->update($this->validatedData($request, $integration));

        return response()->json([
            'message' => 'Cambios guardados',
            'data'    => $integration->load(self::RELATIONS),
        ]);
    }

    public function deactivate(Request $request, int $id)
    {
        $this->ensureAdministrator($request);
        $request->validate([
            'status' => ['required', Rule::in(['paused', 'maintenance', 'deprecated'])],
        ]);

        $integration = Integration::findOrFail($id);
        $integration->update(['status' => $request->status]);

        return response()->json([
            'message' => 'Estado de la integración actualizado',
            'data'    => $integration->load(self::RELATIONS),
        ]);
    }

    private function validatedData(Request $request, ?Integration $integration = null): array
    {
        $activeSystem = Rule::exists('system', 'id')->where(fn ($query) => $query->whereNull('deleted_at'));

        $scheduledTriggerId = OptionList::where('slug', 'scheduled')->value('id');

        return $request->validate([
            'name'                     => ['required', 'string', 'max:255', Rule::unique('integrations', 'name')->ignore($integration)],
            'description'              => ['required', 'string'],
            'integration_type_id'      => ['required', 'integer', Rule::exists('option_lists', 'id')],
            'criticality_id'           => ['required', 'integer', Rule::exists('option_lists', 'id')],
            'status_id'                => ['required', 'integer', Rule::exists('option_lists', 'id')],
            'source_system_id'         => ['required', 'integer', $activeSystem, 'different:destination_system_id'],
            'destination_system_id'    => ['required', 'integer', $activeSystem, 'different:source_system_id'],
            'responsible_id'           => ['nullable', 'integer', Rule::exists('users', 'id')->where(fn ($query) => $query->whereNull('deleted_at'))],
            'external_support_contact' => ['nullable', 'string'],
            'endpoint_url'             => ['nullable', 'string', 'max:2048'],
            'test_endpoint_url'        => ['nullable', 'string', 'max:2048'],
            'server_device_id'         => ['nullable', 'integer', Rule::exists('server_devices', 'id')],
            'authentication_method_id' => ['required', 'integer', Rule::exists('option_lists', 'id')],
            'trigger_type_id'          => ['required', 'integer', Rule::exists('option_lists', 'id')],
            'frequency_detail'         => ['nullable', 'string', 'max:255', "required_if:trigger_type_id,{$scheduledTriggerId}"],
            'repository_url'           => ['nullable', 'url', 'max:2048'],
            'technical_notes'          => ['nullable', 'array'],
            'technical_notes.*'        => ['string'],
            'logs_location'            => ['nullable', 'string'],
            'alerts_channel'           => ['nullable', 'string'],
        ]);
    }

    private function ensureAdministrator(Request $request): void
    {
        abort_unless((int) $request->user()->level === 0, 403, 'Solo administradores pueden gestionar integraciones');
    }
}