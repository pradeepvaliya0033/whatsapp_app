<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class FacebookService
{
    private $appId;
    private $appSecret;
    private $redirectUri;
    private $apiVersion;

    public function __construct()
    {
        $this->appId = config('facebook.app_id');
        $this->appSecret = config('facebook.app_secret');
        $this->redirectUri = config('facebook.redirect_uri');
        $this->apiVersion = config('facebook.api_version', 'v18.0');
    }

    /**
     * Generate Facebook OAuth URL for authentication
     *
     * @param array $scopes
     * @param string $state
     * @return string
     */
    public function getAuthUrl(array $scopes = ['pages_manage_metadata', 'pages_read_engagement', 'pages_show_list'], string $state = null): string
    {
        $params = [
            'client_id' => $this->appId,
            'redirect_uri' => $this->redirectUri,
            'scope' => implode(',', $scopes),
            'response_type' => 'code',
            'state' => $state ?: bin2hex(random_bytes(16))
        ];

        return 'https://www.facebook.com/' . $this->apiVersion . '/dialog/oauth?' . http_build_query($params);
    }

    /**
     * Exchange authorization code for access token
     *
     * @param string $code
     * @return array
     */
    public function getAccessToken(string $code): array
    {
        try {
            $response = Http::get('https://graph.facebook.com/' . $this->apiVersion . '/oauth/access_token', [
                'client_id' => $this->appId,
                'client_secret' => $this->appSecret,
                'redirect_uri' => $this->redirectUri,
                'code' => $code
            ]);

            if (!$response->successful()) {
                throw new \Exception('Failed to get access token: ' . $response->body());
            }

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Facebook Access Token Error', [
                'message' => $e->getMessage(),
                'code' => $code
            ]);

            return [
                'error' => [
                    'message' => $e->getMessage(),
                    'code' => 'ACCESS_TOKEN_ERROR'
                ]
            ];
        }
    }

    /**
     * Get long-lived access token
     *
     * @param string $shortLivedToken
     * @return array
     */
    public function getLongLivedToken(string $shortLivedToken): array
    {
        try {
            $response = Http::get('https://graph.facebook.com/' . $this->apiVersion . '/oauth/access_token', [
                'grant_type' => 'fb_exchange_token',
                'client_id' => $this->appId,
                'client_secret' => $this->appSecret,
                'fb_exchange_token' => $shortLivedToken
            ]);

            if (!$response->successful()) {
                throw new \Exception('Failed to get long-lived token: ' . $response->body());
            }

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Facebook Long-lived Token Error', [
                'message' => $e->getMessage(),
                'token' => substr($shortLivedToken, 0, 20) . '...'
            ]);

            return [
                'error' => [
                    'message' => $e->getMessage(),
                    'code' => 'LONG_LIVED_TOKEN_ERROR'
                ]
            ];
        }
    }

    /**
     * Get user's Facebook profile information
     *
     * @param string $accessToken
     * @return array
     */
    public function getUserProfile(string $accessToken): array
    {
        try {
            $response = Http::withToken($accessToken)
                ->get('https://graph.facebook.com/' . $this->apiVersion . '/me', [
                    'fields' => 'id,name,email,picture'
                ]);

            if (!$response->successful()) {
                throw new \Exception('Failed to get user profile: ' . $response->body());
            }

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Facebook User Profile Error', [
                'message' => $e->getMessage(),
                'token' => substr($accessToken, 0, 20) . '...'
            ]);

            return [
                'error' => [
                    'message' => $e->getMessage(),
                    'code' => 'USER_PROFILE_ERROR'
                ]
            ];
        }
    }

    /**
     * Get user's Facebook pages
     *
     * @param string $accessToken
     * @return array
     */
    public function getUserPages(string $accessToken): array
    {
        try {
            $response = Http::withToken($accessToken)
                ->get('https://graph.facebook.com/' . $this->apiVersion . '/me/accounts', [
                    'fields' => 'id,name,category,access_token,picture'
                ]);

            if (!$response->successful()) {
                throw new \Exception('Failed to get user pages: ' . $response->body());
            }

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Facebook User Pages Error', [
                'message' => $e->getMessage(),
                'token' => substr($accessToken, 0, 20) . '...'
            ]);

            return [
                'error' => [
                    'message' => $e->getMessage(),
                    'code' => 'USER_PAGES_ERROR'
                ]
            ];
        }
    }

    /**
     * Get page access token for a specific page
     *
     * @param string $pageId
     * @param string $userAccessToken
     * @return array
     */
    public function getPageAccessToken(string $pageId, string $userAccessToken): array
    {
        try {
            $response = Http::withToken($userAccessToken)
                ->get('https://graph.facebook.com/' . $this->apiVersion . '/' . $pageId, [
                    'fields' => 'access_token'
                ]);

            if (!$response->successful()) {
                throw new \Exception('Failed to get page access token: ' . $response->body());
            }

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Facebook Page Access Token Error', [
                'message' => $e->getMessage(),
                'page_id' => $pageId,
                'token' => substr($userAccessToken, 0, 20) . '...'
            ]);

            return [
                'error' => [
                    'message' => $e->getMessage(),
                    'code' => 'PAGE_ACCESS_TOKEN_ERROR'
                ]
            ];
        }
    }

    /**
     * Validate access token
     *
     * @param string $accessToken
     * @return array
     */
    public function validateToken(string $accessToken): array
    {
        try {
            $response = Http::get('https://graph.facebook.com/' . $this->apiVersion . '/me', [
                'access_token' => $accessToken,
                'fields' => 'id,name'
            ]);

            if (!$response->successful()) {
                return [
                    'valid' => false,
                    'error' => $response->json()
                ];
            }

            return [
                'valid' => true,
                'data' => $response->json()
            ];
        } catch (\Exception $e) {
            Log::error('Facebook Token Validation Error', [
                'message' => $e->getMessage(),
                'token' => substr($accessToken, 0, 20) . '...'
            ]);

            return [
                'valid' => false,
                'error' => [
                    'message' => $e->getMessage(),
                    'code' => 'TOKEN_VALIDATION_ERROR'
                ]
            ];
        }
    }

    /**
     * Refresh access token if needed
     *
     * @param string $accessToken
     * @return array
     */
    public function refreshToken(string $accessToken): array
    {
        // First validate the token
        $validation = $this->validateToken($accessToken);
        
        if ($validation['valid']) {
            return [
                'success' => true,
                'token' => $accessToken,
                'message' => 'Token is still valid'
            ];
        }

        // If token is invalid, try to get a new long-lived token
        return $this->getLongLivedToken($accessToken);
    }

    /**
     * Post to Facebook page
     *
     * @param string $pageId
     * @param string $pageAccessToken
     * @param string $message
     * @param array $attachments
     * @return array
     */
    public function postToPage(string $pageId, string $pageAccessToken, string $message, array $attachments = []): array
    {
        try {
            $data = [
                'message' => $message,
                'access_token' => $pageAccessToken
            ];

            // Add attachments if provided
            if (!empty($attachments)) {
                $data = array_merge($data, $attachments);
            }

            $response = Http::post('https://graph.facebook.com/' . $this->apiVersion . '/' . $pageId . '/feed', $data);

            if (!$response->successful()) {
                throw new \Exception('Failed to post to page: ' . $response->body());
            }

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Facebook Post Error', [
                'message' => $e->getMessage(),
                'page_id' => $pageId,
                'token' => substr($pageAccessToken, 0, 20) . '...'
            ]);

            return [
                'error' => [
                    'message' => $e->getMessage(),
                    'code' => 'POST_ERROR'
                ]
            ];
        }
    }

    /**
     * Get page insights
     *
     * @param string $pageId
     * @param string $pageAccessToken
     * @param string $metric
     * @param string $period
     * @return array
     */
    public function getPageInsights(string $pageId, string $pageAccessToken, string $metric = 'page_impressions', string $period = 'day'): array
    {
        try {
            $response = Http::withToken($pageAccessToken)
                ->get('https://graph.facebook.com/' . $this->apiVersion . '/' . $pageId . '/insights', [
                    'metric' => $metric,
                    'period' => $period
                ]);

            if (!$response->successful()) {
                throw new \Exception('Failed to get page insights: ' . $response->body());
            }

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Facebook Page Insights Error', [
                'message' => $e->getMessage(),
                'page_id' => $pageId,
                'metric' => $metric,
                'token' => substr($pageAccessToken, 0, 20) . '...'
            ]);

            return [
                'error' => [
                    'message' => $e->getMessage(),
                    'code' => 'INSIGHTS_ERROR'
                ]
            ];
        }
    }
}
