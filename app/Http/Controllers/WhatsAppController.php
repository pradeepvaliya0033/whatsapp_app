<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Contact;

class WhatsAppController extends Controller
{
    protected $whatsAppService;

    public function __construct(WhatsAppService $whatsAppService)
    {
        $this->whatsAppService = $whatsAppService;
    }

    /**
     * Send a WhatsApp message.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendMessage(Request $request)
    {
        // Normalize inputs: allow 'to' and 'parameters' as comma/newline separated strings or arrays
        $rawTo = $request->input('to');
        if (is_string($rawTo)) {
            $parts = preg_split('/[\s,]+|\r?\n/', $rawTo, -1, PREG_SPLIT_NO_EMPTY);
            $normalizedTo = array_values(array_filter(array_map('trim', $parts)));
            $request->merge(['to' => $normalizedTo]);
        }
        $rawParams = $request->input('parameters');
        if (is_string($rawParams)) {
            $paramParts = preg_split('/[\s,]+|\r?\n/', $rawParams, -1, PREG_SPLIT_NO_EMPTY);
            $normalizedParams = array_values(array_filter(array_map('trim', $paramParts)));
            $request->merge(['parameters' => $normalizedParams]);
        }

        $validator = Validator::make($request->all(), [
            'to' => 'required|array',
            'to.*' => 'regex:/^\+\d{10,15}$/',
            'template_name' => 'required|string',
            'lang_code' => 'required|string',
            'parameters' => 'nullable|array', // Support for dynamic parameters
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 400);
        }

        try {
            $responses = [];
            foreach ($request->to as $phone_number) {
                $response = $this->whatsAppService->sendMessage(
                    $phone_number,
                    $request->template_name,
                    $request->lang_code,
                    $request->parameters ?? []
                );
                $responses[] = $response;
            }

            return response()->json([
                'status' => 'success',
                'responses' => $responses
            ]);

        } catch (\Exception $exception) {
            Log::error('WhatsApp Send Message Error', [
                'message' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
                'request' => $request->all()
            ]);

            return response()->json(['error' => $exception->getMessage()], 400);
        }
    }

    /**
     * Send a text message (non-template)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendTextMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'to' => 'required|regex:/^\+\d{10,15}$/',
            'message' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 400);
        }

        try {
            $response = $this->whatsAppService->sendTextMessage($request->to, $request->message);

            return response()->json([
                'status' => 'success',
                'response' => $response
            ]);

        } catch (\Exception $exception) {
            Log::error('WhatsApp Send Text Message Error', [
                'message' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
                'request' => $request->all()
            ]);

            return response()->json(['error' => $exception->getMessage()], 400);
        }
    }

    /**
     * Helper to fetch current user's contacts for UI pickers.
     */
    public function getContacts()
    {
        $contacts = Contact::where('user_id', Auth::id())
            ->orderBy('name')
            ->get(['uuid', 'name', 'phone', 'tags']);

        return response()->json([
            'status' => 'success',
            'data' => $contacts
        ]);
    }

    /**
     * Create a WhatsApp template
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createTemplate(Request $request)
    {
        $validCategories = ['TRANSACTIONAL', 'MARKETING', 'UTILITY'];
        $validHeaderTypes = ['text', 'image', 'video', 'document'];
        $validButtonTypes = ['quick_reply', 'url'];

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'category' => ['required', 'string', 'in:' . implode(',', $validCategories)],
            'lang_code' => 'required|string',

            // Header Validation
            'header' => 'nullable|array',
            'header.type' => ['nullable', 'string', 'in:' . implode(',', $validHeaderTypes)],
            'header.text' => 'nullable|string|required_if:header.type,text',
            'header.format' => 'nullable|string|required_if:header.type,text|in:TEXT',

            // Body Validation
            'body' => 'required|string',

            // Footer Validation
            'footer' => 'nullable|string',

            // Buttons Validation
            'buttons' => 'nullable|array',
            'buttons.*.type' => ['required_with:buttons', 'string', 'in:' . implode(',', $validButtonTypes)],
            'buttons.*.text' => 'required_with:buttons|string',
            'buttons.*.url' => 'nullable|string|required_if:buttons.*.type,url|url',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()->toArray(),
            ], 400);
        }

        try {
            $components = [];

            // Header Component
            if ($request->filled('header')) {
                $header = [
                    'type' => $request->header['type'],
                    'format' => $request->header['format'] ?? 'TEXT', // Default format
                    'text' => $request->header['text'],
                ];
                $components[] = ['type' => 'header'] + $header;
            }

            // Body Component
            $components[] = [
                'type' => 'body',
                'text' => $request->body,
            ];

            // Footer Component
            if ($request->filled('footer')) {
                $components[] = [
                    'type' => 'footer',
                    'text' => $request->footer,
                ];
            }

            // Buttons Component
            if ($request->filled('buttons')) {
                $buttons = [];
                foreach ($request->buttons as $button) {
                    $buttonData = [
                        'type' => $button['type'],
                        'text' => $button['text'],
                    ];

                    // Handling URL buttons
                    if ($button['type'] === 'url') {
                        $buttonData['url'] = $button['url'];
                    }

                    $buttons[] = $buttonData;
                }

                $components[] = [
                    'type' => 'buttons',
                    'buttons' => $buttons,
                ];
            }

            $templateData = [
                'name' => $request->name,
                'category' => $request->category,
                'language' => $request->lang_code,
                'components' => $components,
            ];

            $response = $this->whatsAppService->createTemplate($templateData);

            return response()->json([
                'status' => 'success',
                'data' => $response,
            ]);
        } catch (\Exception $exception) {
            Log::error('WhatsApp Create Template Error', [
                'message' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
                'request' => $request->all()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    /**
     * Get all WhatsApp templates
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTemplateStatuses()
    {
        try {
            $templates = $this->whatsAppService->getTemplates();

            // Check for errors in the response
            if (isset($templates['error'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => $templates['error']['message'] ?? 'Failed to fetch templates',
                ], 400);
            }

            return response()->json([
                'status' => 'success',
                'data' => $templates['data'] ?? [],
            ]);
        } catch (\Exception $exception) {
            Log::error('WhatsApp Get Templates Error', [
                'message' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    /**
     * Get a specific template by ID
     *
     * @param string $templateId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTemplate(string $templateId)
    {
        try {
            $template = $this->whatsAppService->getTemplate($templateId);

            if (isset($template['error'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => $template['error']['message'] ?? 'Failed to fetch template',
                ], 400);
            }

            return response()->json([
                'status' => 'success',
                'data' => $template,
            ]);
        } catch (\Exception $exception) {
            Log::error('WhatsApp Get Template Error', [
                'message' => $exception->getMessage(),
                'template_id' => $templateId,
                'trace' => $exception->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a template
     *
     * @param string $templateId
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteTemplate(string $templateId)
    {
        try {
            $response = $this->whatsAppService->deleteTemplate($templateId);

            if (isset($response['error'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => $response['error']['message'] ?? 'Failed to delete template',
                ], 400);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Template deleted successfully',
                'data' => $response,
            ]);
        } catch (\Exception $exception) {
            Log::error('WhatsApp Delete Template Error', [
                'message' => $exception->getMessage(),
                'template_id' => $templateId,
                'trace' => $exception->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    /**
     * Get message status
     *
     * @param string $messageId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMessageStatus(string $messageId)
    {
        try {
            $response = $this->whatsAppService->getMessageStatus($messageId);

            if (isset($response['error'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => $response['error']['message'] ?? 'Failed to fetch message status',
                ], 400);
            }

            return response()->json([
                'status' => 'success',
                'data' => $response,
            ]);
        } catch (\Exception $exception) {
            Log::error('WhatsApp Get Message Status Error', [
                'message' => $exception->getMessage(),
                'message_id' => $messageId,
                'trace' => $exception->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

}
