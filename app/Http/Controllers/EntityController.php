<?php

namespace App\Http\Controllers;

use App\Models\EntityMaster;
use App\Models\EntityProviderMapping;
use App\Models\ProviderMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EntityController extends Controller
{
    /**
     * Create a new entity
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['status' => 'error', 'errors' => $validator->errors()], 400);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $entity = EntityMaster::create([
                'name' => $request->name,
                'description' => $request->description,
                'status' => $request->has('status'),
                'created_by' => auth()->id(),
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'success',
                    'data' => $entity,
                    'message' => 'Entity created successfully'
                ], 201);
            }

            return redirect()->route('entities.index')->with('success', 'Entity created successfully');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Error creating entity: ' . $e->getMessage());
        }
    }

    /**
     * Display a listing of entities for web interface
     */
    public function index()
    {
        $entities = EntityMaster::with(['providerMappings.provider'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('entities.index', compact('entities'));
    }

    /**
     * Show the form for creating a new entity
     */
    public function create()
    {
        return view('entities.create');
    }

    /**
     * Get a specific entity (for AJAX calls)
     *
     * @param string $uuid
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $uuid)
    {
        try {
            $entity = EntityMaster::where('uuid', $uuid)
                ->with(['providerMappings.provider'])
                ->first();

            if (!$entity) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Entity not found'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => $entity
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update an entity
     *
     * @param Request $request
     * @param string $uuid
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function update(Request $request, string $uuid)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['status' => 'error', 'errors' => $validator->errors()], 400);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $entity = EntityMaster::where('uuid', $uuid)->first();

            if (!$entity) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Entity not found'
                    ], 404);
                }
                return redirect()->back()->with('error', 'Entity not found');
            }

            $entity->update([
                'name' => $request->get('name', $entity->name),
                'description' => $request->get('description', $entity->description),
                'status' => $request->has('status'),
                'updated_by' => auth()->id(),
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'success',
                    'data' => $entity,
                    'message' => 'Entity updated successfully'
                ]);
            }

            return redirect()->route('entities.index')->with('success', 'Entity updated successfully');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Error updating entity: ' . $e->getMessage());
        }
    }

    /**
     * Delete an entity
     *
     * @param string $uuid
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function destroy(string $uuid)
    {
        try {
            $entity = EntityMaster::where('uuid', $uuid)->first();

            if (!$entity) {
                if (request()->expectsJson()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Entity not found'
                    ], 404);
                }
                return redirect()->back()->with('error', 'Entity not found');
            }

            $entity->delete();

            if (request()->expectsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Entity deleted successfully'
                ]);
            }

            return redirect()->route('entities.index')->with('success', 'Entity deleted successfully');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Error deleting entity: ' . $e->getMessage());
        }
    }

    /**
     * Add provider mapping to entity
     *
     * @param Request $request
     * @param string $uuid
     * @return \Illuminate\Http\JsonResponse
     */
    public function addProvider(Request $request, string $uuid)
    {
        $validator = Validator::make($request->all(), [
            'provider_id' => 'required|exists:provider_masters,uuid',
            'usage_type' => 'required|string',
            'is_default' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 400);
        }

        try {
            $entity = EntityMaster::where('uuid', $uuid)->first();
            if (!$entity) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Entity not found'
                ], 404);
            }

            $provider = ProviderMaster::where('uuid', $request->provider_id)->first();
            if (!$provider) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Provider not found'
                ], 404);
            }

            // If this is set as default, unset other defaults for this usage type
            if ($request->get('is_default', false)) {
                EntityProviderMapping::where('entity_id', $entity->id)
                    ->where('usage_type', $request->usage_type)
                    ->update(['is_default' => false]);
            }

            $mapping = EntityProviderMapping::create([
                'entity_id' => $entity->id,
                'provider_id' => $provider->id,
                'usage_type' => $request->usage_type,
                'is_default' => $request->get('is_default', false),
                'status' => true,
                'created_by' => auth()->id(),
            ]);

            return response()->json([
                'status' => 'success',
                'data' => $mapping,
                'message' => 'Provider mapping added successfully'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
