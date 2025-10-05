<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    private $apiUrl;
    private $phoneNumberId;
    private $accessToken;
    private $businessAccountId;
    private $appSecret;

    public function __construct()
    {
        $this->apiUrl = config('whatsapp.api_url');
        $this->phoneNumberId = config('whatsapp.phone_number_id');
        $this->accessToken = config('whatsapp.access_token');
        $this->businessAccountId = config('whatsapp.business_account_id');
        $this->appSecret = config('whatsapp.app_secret');
    }

    /**
     * Generate app secret proof for API authentication
     *
     * @return string
     */
    private function generateAppSecretProof(): string
    {
        return hash_hmac('sha256', $this->accessToken, $this->appSecret);
    }

    /**
     * Send a WhatsApp message using template
     *
     * @param string $to Recipient's phone number (including country code)
     * @param string $templateName The template name
     * @param string $langCode The language code
     * @param array $parameters Template parameters
     * @return array Response from the API
     */
    public function sendMessage(string $to, string $templateName, string $langCode, array $parameters = []): array
    {
        try {
            $appSecretProof = $this->generateAppSecretProof();
            $endpoint = "{$this->apiUrl}/{$this->phoneNumberId}/messages";

            $payload = [
                'messaging_product' => 'whatsapp',
                'to' => $to,
                'type' => 'template',
                'template' => [
                    'name' => $templateName,
                    'language' => [
                        'code' => $langCode,
                    ],
                ],
                'appsecret_proof' => $appSecretProof,
            ];

            // Add parameters if provided
            if (!empty($parameters)) {
                $payload['template']['components'] = [
                    [
                        'type' => 'body',
                        'parameters' => array_map(function ($param) {
                            return ['type' => 'text', 'text' => $param];
                        }, $parameters)
                    ]
                ];
            }

            $response = Http::withToken($this->accessToken)
                ->timeout(30)
                ->post($endpoint, $payload);

            $responseData = $response->json();

            // Log the request and response for debugging
            Log::info('WhatsApp API Request', [
                'endpoint' => $endpoint,
                'payload' => $payload,
                'response' => $responseData,
                'status_code' => $response->status()
            ]);

            if (!$response->successful()) {
                Log::error('WhatsApp API Error', [
                    'status_code' => $response->status(),
                    'response' => $responseData
                ]);
            }

            return $responseData;

        } catch (\Exception $e) {
            Log::error('WhatsApp Service Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'error' => [
                    'message' => $e->getMessage(),
                    'code' => 'SERVICE_ERROR'
                ]
            ];
        }
    }

    /**
     * Send a text message (non-template)
     *
     * @param string $to Recipient's phone number
     * @param string $message The message content
     * @return array Response from the API
     */
    public function sendTextMessage(string $to, string $message): array
    {
        try {
            $appSecretProof = $this->generateAppSecretProof();
            $endpoint = "{$this->apiUrl}/{$this->phoneNumberId}/messages";

            $payload = [
                'messaging_product' => 'whatsapp',
                'to' => $to,
                'type' => 'text',
                'text' => [
                    'body' => $message
                ],
                'appsecret_proof' => $appSecretProof,
            ];

            $response = Http::withToken($this->accessToken)
                ->timeout(30)
                ->post($endpoint, $payload);

            return $response->json();

        } catch (\Exception $e) {
            Log::error('WhatsApp Text Message Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'error' => [
                    'message' => $e->getMessage(),
                    'code' => 'SERVICE_ERROR'
                ]
            ];
        }
    }

    /**
     * Create a WhatsApp template
     *
     * @param array $templateData Template data
     * @return array Response from the API
     */
    public function createTemplate(array $templateData): array
    {
        try {
            if (!$this->businessAccountId) {
                throw new \Exception('Missing WhatsApp Business Account ID in configuration.');
            }

            $appSecretProof = $this->generateAppSecretProof();
            $endpoint = "{$this->apiUrl}/{$this->businessAccountId}/message_templates";

            $response = Http::withToken($this->accessToken)
                ->timeout(30)
                ->post($endpoint, $templateData + ['appsecret_proof' => $appSecretProof]);

            $responseData = $response->json();

            Log::info('WhatsApp Template Creation', [
                'endpoint' => $endpoint,
                'template_data' => $templateData,
                'response' => $responseData,
                'status_code' => $response->status()
            ]);

            return $responseData;

        } catch (\Exception $e) {
            Log::error('WhatsApp Template Creation Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'error' => [
                    'message' => $e->getMessage(),
                    'code' => 'TEMPLATE_ERROR'
                ]
            ];
        }
    }

    /**
     * Get all WhatsApp templates
     *
     * @return array Response from the API
     */
    public function getTemplates(): array
    {
        try {
            if (!$this->businessAccountId) {
                throw new \Exception('Missing WhatsApp Business Account ID in configuration.');
            }

            $appSecretProof = $this->generateAppSecretProof();
            $endpoint = "{$this->apiUrl}/{$this->businessAccountId}/message_templates";

            $response = Http::withToken($this->accessToken)
                ->timeout(30)
                ->get($endpoint, ['appsecret_proof' => $appSecretProof]);

            $responseData = $response->json();

            Log::info('WhatsApp Templates Fetch', [
                'endpoint' => $endpoint,
                'response' => $responseData,
                'status_code' => $response->status()
            ]);

            return $responseData;

        } catch (\Exception $e) {
            Log::error('WhatsApp Templates Fetch Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'error' => [
                    'message' => $e->getMessage(),
                    'code' => 'TEMPLATE_FETCH_ERROR'
                ]
            ];
        }
    }

    /**
     * Get template by ID
     *
     * @param string $templateId Template ID
     * @return array Response from the API
     */
    public function getTemplate(string $templateId): array
    {
        try {
            if (!$this->businessAccountId) {
                throw new \Exception('Missing WhatsApp Business Account ID in configuration.');
            }

            $appSecretProof = $this->generateAppSecretProof();
            $endpoint = "{$this->apiUrl}/{$this->businessAccountId}/message_templates/{$templateId}";

            $response = Http::withToken($this->accessToken)
                ->timeout(30)
                ->get($endpoint, ['appsecret_proof' => $appSecretProof]);

            return $response->json();

        } catch (\Exception $e) {
            Log::error('WhatsApp Template Fetch Error', [
                'message' => $e->getMessage(),
                'template_id' => $templateId,
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'error' => [
                    'message' => $e->getMessage(),
                    'code' => 'TEMPLATE_FETCH_ERROR'
                ]
            ];
        }
    }

    /**
     * Delete a template
     *
     * @param string $templateId Template ID
     * @return array Response from the API
     */
    public function deleteTemplate(string $templateId): array
    {
        try {
            if (!$this->businessAccountId) {
                throw new \Exception('Missing WhatsApp Business Account ID in configuration.');
            }

            $appSecretProof = $this->generateAppSecretProof();
            $endpoint = "{$this->apiUrl}/{$this->businessAccountId}/message_templates/{$templateId}";

            $response = Http::withToken($this->accessToken)
                ->timeout(30)
                ->delete($endpoint, ['appsecret_proof' => $appSecretProof]);

            return $response->json();

        } catch (\Exception $e) {
            Log::error('WhatsApp Template Delete Error', [
                'message' => $e->getMessage(),
                'template_id' => $templateId,
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'error' => [
                    'message' => $e->getMessage(),
                    'code' => 'TEMPLATE_DELETE_ERROR'
                ]
            ];
        }
    }

    /**
     * Get message status
     *
     * @param string $messageId Message ID
     * @return array Response from the API
     */
    public function getMessageStatus(string $messageId): array
    {
        try {
            $appSecretProof = $this->generateAppSecretProof();
            $endpoint = "{$this->apiUrl}/{$this->phoneNumberId}/messages/{$messageId}";

            $response = Http::withToken($this->accessToken)
                ->timeout(30)
                ->get($endpoint, ['appsecret_proof' => $appSecretProof]);

            return $response->json();

        } catch (\Exception $e) {
            Log::error('WhatsApp Message Status Error', [
                'message' => $e->getMessage(),
                'message_id' => $messageId,
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'error' => [
                    'message' => $e->getMessage(),
                    'code' => 'MESSAGE_STATUS_ERROR'
                ]
            ];
        }
    }
}
