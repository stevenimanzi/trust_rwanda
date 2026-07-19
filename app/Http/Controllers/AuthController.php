<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect($this->getRedirectPath(Auth::user()));
        }
        return view('auth.login');
    }

    /**
     * Handle login verification.
     */
    public function login(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string',
            'password' => 'required|string',
        ]);

        $identifier = trim($request->input('identifier'));
        $password = $request->input('password');

        // Check email or phone against users password hashes
        $user = User::where('email', $identifier)
                    ->orWhere('phone', $identifier)
                    ->first();

        if ($user && Hash::check($password, $user->password)) {
            Auth::login($user, $request->filled('remember'));
            $request->session()->regenerate();

            return redirect()->intended($this->getRedirectPath($user));
        }

        return back()->withErrors([
            'identifier' => 'Invalid credentials. Please try again.',
        ])->withInput($request->only('identifier'));
    }

    /**
     * Show buyer registration form.
     */
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect('/');
        }
        return view('auth.register');
    }

    /**
     * Handle buyer registration.
     */
    public function register(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:120',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20|unique:users',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[^\w]/',
            ],
        ], [
            'password.regex' => 'Password must be at least 8 characters and include uppercase, numbers, and special characters.',
        ]);

        $user = User::create([
            'full_name' => $request->input('full_name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'password' => Hash::make($request->input('password')),
            'role' => 'customer',
            'is_verified' => 1,
        ]);

        // Send welcome email (using fallback log mailer configured in .env)
        try {
            $siteName = config('app.name', 'Trust Rwanda');
            Mail::html("
                <h2>Hello, {$user->full_name}!</h2>
                <p>We're thrilled to have you join our marketplace.</p>
                <p>Your account is now active. You can now track orders, save items to your wishlist, and enjoy exclusive deals.</p>
            ", function ($message) use ($user, $siteName) {
                $message->to($user->email)
                        ->subject("Welcome to {$siteName}, {$user->full_name}! 🚀");
            });
        } catch (\Exception $e) {
            // Ignore email errors to prevent blocking registration
        }

        Auth::login($user);
        return redirect('/');
    }

    /**
     * Show vendor (seller) registration form.
     */
    public function showVendorRegister()
    {
        if (Auth::check()) {
            return redirect($this->getRedirectPath(Auth::user()));
        }
        return view('auth.vendor_register');
    }

    /**
     * Handle vendor registration.
     */
    public function vendorRegister(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:120',
            'shop_name' => 'required|string|max:120',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20|unique:users',
            'address' => 'required|string|max:220',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[^\w]/',
            ],
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ], [
            'password.regex' => 'Password must be at least 8 characters and include uppercase, numbers, and special characters.',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'full_name' => $request->input('full_name'),
                'shop_name' => $request->input('shop_name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'address' => $request->input('address'),
                'latitude' => $request->input('latitude') ?: -1.9441,
                'longitude' => $request->input('longitude') ?: 30.0619,
                'password' => Hash::make($request->input('password')),
                'role' => 'vendor',
                'is_verified' => 0,
            ]);

            DB::table('subscriptions')->insert([
                'user_id' => $user->id,
                'plan_name' => 'None',
                'status' => 'expired',
                'start_date' => now()->subDay(),
                'end_date' => now()->subDay(),
            ]);

            DB::commit();

            Auth::login($user);
            return redirect('/vendor/dashboard');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Registration failed: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Show property owner registration form.
     */
    public function showPropertyOwnerRegister()
    {
        if (Auth::check()) {
            return redirect($this->getRedirectPath(Auth::user()));
        }
        return view('auth.property_owner_register');
    }

    /**
     * Handle property owner registration.
     */
    public function propertyOwnerRegister(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:120',
            'agency_name' => 'required|string|max:120',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20|unique:users',
            'address' => 'required|string|max:220',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[^\w]/',
            ],
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ], [
            'password.regex' => 'Password must be at least 8 characters and include uppercase, numbers, and special characters.',
        ]);

        try {
            $user = User::create([
                'full_name' => $request->input('full_name'),
                'shop_name' => $request->input('agency_name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'address' => $request->input('address'),
                'latitude' => $request->input('latitude') ?: -1.9441,
                'longitude' => $request->input('longitude') ?: 30.0619,
                'password' => Hash::make($request->input('password')),
                'role' => 'real_estate_owner',
                'is_verified' => 0,
            ]);

            Auth::login($user);
            return redirect('/property_owner/dashboard');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Registration failed: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Show forgot password form.
     */
    public function showForgotPassword()
    {
        return view('auth.forgot_password');
    }

    /**
     * Handle forgot password token generation and email dispatch.
     */
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string',
        ]);

        $identifier = trim($request->input('identifier'));
        $user = User::where('email', $identifier)
                    ->orWhere('phone', $identifier)
                    ->first();

        if ($user) {
            $token = Str::random(64);
            $user->reset_token = $token;
            $user->token_expiry = now()->addHour();
            $user->save();

            $resetLink = url("/reset-password?token=" . $token);

            try {
                Mail::html("
                    <h2>Password Reset Request</h2>
                    <p>Click the link below to reset your password. This link is valid for 1 hour.</p>
                    <p><a href='{$resetLink}' style='padding: 10px 20px; background-color: #4F46E5; color: white; text-decoration: none; border-radius: 5px; display: inline-block;'>Reset Password</a></p>
                    <p>If you did not request this, please ignore this email.</p>
                ", function ($message) use ($user) {
                    $message->to($user->email)
                            ->subject("Reset Password Link - Trust Rwanda");
                });

                return back()->with('status', "We sent a secret link to " . htmlspecialchars($user->email) . ". Please check your inbox to reset your password.");
            } catch (\Exception $e) {
                return back()->withErrors(['error' => 'Failed to send email. Please contact support.']);
            }
        }

        // For security, show the same message even if the identifier doesn't exist
        return back()->with('status', "If an account exists for " . htmlspecialchars($identifier) . ", a reset link has been sent to the associated email.");
    }

    /**
     * Show reset password form.
     */
    public function showResetPassword(Request $request)
    {
        $token = $request->query('token');
        if (empty($token)) {
            return view('auth.reset_password')->withErrors(['error' => 'Access denied. No security token found in the request.']);
        }

        $user = User::where('reset_token', $token)
                    ->where('token_expiry', '>', now())
                    ->first();

        if (!$user) {
            return view('auth.reset_password')->withErrors(['error' => 'This reset link is invalid or has expired. Please request a new link.']);
        }

        return view('auth.reset_password', ['token' => $token]);
    }

    /**
     * Handle resetting of password.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $token = $request->input('token');
        $user = User::where('reset_token', $token)
                    ->where('token_expiry', '>', now())
                    ->first();

        if (!$user) {
            return back()->withErrors(['error' => 'This reset link is invalid or has expired. Please request a new link.']);
        }

        $user->password = Hash::make($request->input('password'));
        $user->reset_token = null;
        $user->token_expiry = null;
        $user->save();

        return redirect()->route('login')->with('status', 'Success! Your password has been updated. You can now log in securely.');
    }

    /**
     * Log the user out.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    /**
     * Get redirect path based on user role.
     */
    private function getRedirectPath($user)
    {
        return match ($user->role) {
            'admin' => '/admin/dashboard',
            'vendor' => '/vendor/dashboard',
            'real_estate_owner' => '/property_owner/dashboard',
            default => '/',
        };
    }
}
