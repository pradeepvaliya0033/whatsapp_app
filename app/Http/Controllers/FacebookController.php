<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FacebookService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class FacebookController extends Controller
{
    protected $facebookService;

    public function __construct(FacebookService $facebookService)
    {
        $this->facebookService = $facebookService;
    }

    /**
     * Redirect to Facebook OAuth
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToFacebook()
    {
        try {
            $state = bin2hex(random_bytes(16));
            session(['facebook_state' => $state]);

            $authUrl = $this->facebookService->getAuthUrl([
                'pages_manage_metadata',
                'pages_read_engagement',
                'pages_show_list',
                'pages_manage_posts',
                'pages_read_user_content'
            ], $state);

            return redirect($authUrl);
        } catch (\Exception $e) {
            Log::error('Facebook Redirect Error', [
                'message' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return redirect()->back()->with('error', 'Failed to initiate Facebook connection. Please try again.');
        }
    }

    /**
     * Handle Facebook OAuth callback
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleFacebookCallback(Request $request)
    {
        try {
            // Validate state parameter
            $state = $request->get('state');
            if (!$state || $state !== session('facebook_state')) {
                return redirect()->route('facebook.settings')->with('error', 'Invalid state parameter. Please try again.');
            }

            // Check for error from Facebook
            if ($request->has('error')) {
                $error = $request->get('error_description', $request->get('error'));
                return redirect()->route('facebook.settings')->with('error', 'Facebook authentication failed: ' . $error);
            }

            $code = $request->get('code');
            if (!$code) {
                return redirect()->route('facebook.settings')->with('error', 'No authorization code received from Facebook.');
            }

            // Exchange code for access token
            $tokenResponse = $this->facebookService->getAccessToken($code);
            if (isset($tokenResponse['error'])) {
                return redirect()->route('facebook.settings')->with('error', 'Failed to get access token: ' . $tokenResponse['error']['message']);
            }

            $accessToken = $tokenResponse['access_token'];

            // Get long-lived token
            $longLivedResponse = $this->facebookService->getLongLivedToken($accessToken);
            if (isset($longLivedResponse['error'])) {
                Log::warning('Failed to get long-lived token, using short-lived token', [
                    'user_id' => auth()->id(),
                    'error' => $longLivedResponse['error']
                ]);
                $longLivedToken = $accessToken;
                $expiresIn = $tokenResponse['expires_in'] ?? 3600;
            } else {
                $longLivedToken = $longLivedResponse['access_token'];
                $expiresIn = $longLivedResponse['expires_in'] ?? 5184000; // 60 days
            }

            // Get user profile
            $profileResponse = $this->facebookService->getUserProfile($longLivedToken);
            if (isset($profileResponse['error'])) {
                return redirect()->route('facebook.settings')->with('error', 'Failed to get Facebook profile: ' . $profileResponse['error']['message']);
            }

            // Get user's pages
            $pagesResponse = $this->facebookService->getUserPages($longLivedToken);
            if (isset($pagesResponse['error'])) {
                Log::warning('Failed to get user pages', [
                    'user_id' => auth()->id(),
                    'error' => $pagesResponse['error']
                ]);
                $pages = [];
            } else {
                $pages = $pagesResponse['data'] ?? [];
            }

            // Save to user
            $user = Auth::user();
            $user->update([
                'facebook_id' => $profileResponse['id'],
                'facebook_name' => $profileResponse['name'],
                'facebook_email' => $profileResponse['email'] ?? null,
                'facebook_picture' => $profileResponse['picture']['data']['url'] ?? null,
                'facebook_access_token' => encrypt($longLivedToken),
                'facebook_token_expires_at' => now()->addSeconds($expiresIn),
                'facebook_pages' => json_encode($pages),
                'facebook_connected_at' => now(),
            ]);

            // Clear state from session
            session()->forget('facebook_state');

            return redirect()->route('facebook.settings')->with('success', 'Successfully connected to Facebook! You can now manage your pages.');

        } catch (\Exception $e) {
            Log::error('Facebook Callback Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id(),
                'request' => $request->all()
            ]);

            return redirect()->route('facebook.settings')->with('error', 'An error occurred during Facebook authentication. Please try again.');
        }
    }

    /**
     * Show Facebook settings page
     *
     * @return \Illuminate\View\View
     */
    public function settings()
    {
        $user = Auth::user();
        $pages = [];
        $isConnected = false;

        if ($user->facebook_access_token) {
            $isConnected = true;
            $pages = json_decode($user->facebook_pages, true) ?? [];
        }

        return view('facebook.settings', compact('user', 'pages', 'isConnected'));
    }

    /**
     * Disconnect Facebook account
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function disconnect()
    {
        try {
            $user = Auth::user();

            $user->update([
                'facebook_id' => null,
                'facebook_name' => null,
                'facebook_email' => null,
                'facebook_picture' => null,
                'facebook_access_token' => null,
                'facebook_token_expires_at' => null,
                'facebook_pages' => null,
                'facebook_connected_at' => null,
            ]);

            return redirect()->route('facebook.settings')->with('success', 'Facebook account disconnected successfully.');

        } catch (\Exception $e) {
            Log::error('Facebook Disconnect Error', [
                'message' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return redirect()->route('facebook.settings')->with('error', 'Failed to disconnect Facebook account. Please try again.');
        }
    }

    /**
     * Refresh Facebook token
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function refreshToken()
    {
        try {
            $user = Auth::user();

            if (!$user->facebook_access_token) {
                return redirect()->route('facebook.settings')->with('error', 'No Facebook token found. Please reconnect your account.');
            }

            $accessToken = decrypt($user->facebook_access_token);
            $refreshResponse = $this->facebookService->refreshToken($accessToken);

            if (isset($refreshResponse['error'])) {
                return redirect()->route('facebook.settings')->with('error', 'Failed to refresh token: ' . $refreshResponse['error']['message']);
            }

            $newToken = $refreshResponse['token'] ?? $refreshResponse['access_token'];
            $expiresIn = $refreshResponse['expires_in'] ?? 5184000;

            $user->update([
                'facebook_access_token' => encrypt($newToken),
                'facebook_token_expires_at' => now()->addSeconds($expiresIn),
            ]);

            return redirect()->route('facebook.settings')->with('success', 'Facebook token refreshed successfully.');

        } catch (\Exception $e) {
            Log::error('Facebook Token Refresh Error', [
                'message' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return redirect()->route('facebook.settings')->with('error', 'Failed to refresh Facebook token. Please try again.');
        }
    }

    /**
     * Update selected Facebook page
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateSelectedPage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'selected_page_id' => 'required|string'
        ]);

        if ($validator->fails()) {
            return redirect()->route('facebook.settings')->with('error', 'Please select a valid page.');
        }

        try {
            $user = Auth::user();
            $pages = json_decode($user->facebook_pages, true) ?? [];

            $selectedPage = collect($pages)->firstWhere('id', $request->selected_page_id);

            if (!$selectedPage) {
                return redirect()->route('facebook.settings')->with('error', 'Selected page not found.');
            }

            $user->update([
                'facebook_selected_page_id' => $request->selected_page_id,
                'facebook_selected_page_name' => $selectedPage['name'],
                'facebook_selected_page_token' => encrypt($selectedPage['access_token']),
            ]);

            return redirect()->route('facebook.settings')->with('success', 'Selected page updated successfully.');

        } catch (\Exception $e) {
            Log::error('Facebook Page Selection Error', [
                'message' => $e->getMessage(),
                'user_id' => auth()->id(),
                'page_id' => $request->selected_page_id
            ]);

            return redirect()->route('facebook.settings')->with('error', 'Failed to update selected page. Please try again.');
        }
    }

    /**
     * Test Facebook connection
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function testConnection()
    {
        try {
            $user = Auth::user();

            if (!$user->facebook_access_token) {
                return response()->json([
                    'success' => false,
                    'message' => 'No Facebook token found. Please connect your account first.'
                ]);
            }

            $accessToken = decrypt($user->facebook_access_token);
            $validation = $this->facebookService->validateToken($accessToken);

            if (!$validation['valid']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Facebook token is invalid or expired. Please reconnect your account.',
                    'error' => $validation['error']
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Facebook connection is working properly.',
                'data' => $validation['data']
            ]);

        } catch (\Exception $e) {
            Log::error('Facebook Connection Test Error', [
                'message' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to test Facebook connection. Please try again.'
            ]);
        }
    }
}
