<?php

namespace App\Http\Controllers;

use App\Models\ProviderMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProviderController extends Controller
{
    /**
     * Create a new provider
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'provider_type' => 'required|string',
            'api_config' => 'required|array',
            'status' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['status' => 'error', 'errors' => $validator->errors()], 400);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $provider = ProviderMaster::create([
                'name' => $request->name,
                'description' => $request->description,
                'provider_type' => $request->provider_type,
                'api_config' => $request->api_config,
                'status' => $request->has('status'),
                'created_by' => auth()->id(),
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'success',
                    'data' => $provider,
                    'message' => 'Provider created successfully'
                ], 201);
            }

            return redirect()->route('providers.index')->with('success', 'Provider created successfully');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Error creating provider: ' . $e->getMessage());
        }
    }

    /**
     * Display a listing of providers for web interface
     */
    public function index()
    {
        $providers = ProviderMaster::with(['providerMappings.entity'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('providers.index', compact('providers'));
    }

    /**
     * Show the form for creating a new provider
     */
    public function create()
    {
        return view('providers.create');
    }

    /**
     * Get a specific provider
     *
     * @param string $uuid
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $uuid)
    {
        try {
            $provider = ProviderMaster::where('uuid', $uuid)
                ->with(['providerMappings.entity'])
                ->first();

            if (!$provider) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Provider not found'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => $provider
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a provider
     *
     * @param Request $request
     * @param string $uuid
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, string $uuid)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'provider_type' => 'sometimes|required|string',
            'api_config' => 'sometimes|required|array',
            'status' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 400);
        }

        try {
            $provider = ProviderMaster::where('uuid', $uuid)->first();

            if (!$provider) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Provider not found'
                ], 404);
            }

            $provider->update([
                'name' => $request->get('name', $provider->name),
                'description' => $request->get('description', $provider->description),
                'provider_type' => $request->get('provider_type', $provider->provider_type),
                'api_config' => $request->get('api_config', $provider->api_config),
                'status' => $request->get('status', $provider->status),
                'updated_by' => auth()->id(),
            ]);

            return response()->json([
                'status' => 'success',
                'data' => $provider,
                'message' => 'Provider updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a provider
     *
     * @param string $uuid
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $uuid)
    {
        try {
            $provider = ProviderMaster::where('uuid', $uuid)->first();

            if (!$provider) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Provider not found'
                ], 404);
            }

            $provider->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Provider deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test provider configuration
     *
     * @param string $uuid
     * @return \Illuminate\Http\JsonResponse
     */
    public function test(string $uuid)
    {
        try {
            $provider = ProviderMaster::where('uuid', $uuid)->first();

            if (!$provider) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Provider not found'
                ], 404);
            }

            // Test the provider configuration based on type
            if ($provider->provider_type === 'WhatsApp') {
                $whatsAppService = app(\App\Services\WhatsAppService::class);
                $testResult = $whatsAppService->getTemplates();

                if (isset($testResult['error'])) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Provider test failed: ' . $testResult['error']['message']
                    ], 400);
                }

                return response()->json([
                    'status' => 'success',
                    'message' => 'Provider test successful',
                    'data' => $testResult
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Provider type not supported for testing'
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Provider test failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
