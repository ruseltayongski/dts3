<?php

// namespace App\Http\Controllers\Auth;

// use App\Http\Controllers\Controller;
// use App\Providers\RouteServiceProvider;
// use Illuminate\Foundation\Auth\AuthenticatesUsers;
// use App\Helpers\JWTHelper;

// class LoginController extends Controller
// {
//     /*
//     |--------------------------------------------------------------------------
//     | Login Controller
//     |--------------------------------------------------------------------------
//     |
//     | This controller handles authenticating users for the application and
//     | redirecting them to your home screen. The controller uses a trait
//     | to conveniently provide its functionality to your applications.
//     |
//     */

//     use AuthenticatesUsers;

//     /**
//      * Where to redirect users after login.
//      *
//      * @var string
//      */
//     protected $redirectTo = RouteServiceProvider::HOME;

//     /**
//      * Create a new controller instance.
//      *
//      * @return void
//      */
//     public function __construct()
//     {
//         $this->middleware('guest')->except('logout');
//     }
// }

// namespace App\Http\Controllers\Auth;

// use App\Http\Controllers\Controller;
// use App\Providers\RouteServiceProvider;
// use App\Helpers\JWTHelper;
// use Illuminate\Foundation\Auth\AuthenticatesUsers;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Session;

// class LoginController extends Controller
// {
//     /*
//     |--------------------------------------------------------------------------
//     | Login Controller
//     |--------------------------------------------------------------------------
//     |
//     | This controller handles authenticating users for the application and
//     | redirecting them to your home screen. The controller uses a trait
//     | to conveniently provide its functionality to your applications.
//     |
//     */

//     use AuthenticatesUsers;

//     /**
//      * Where to redirect users after login.
//      *
//      * @var string
//      */
//     protected $redirectTo = RouteServiceProvider::HOME;

//     /**
//      * Create a new controller instance.
//      *
//      * @return void
//      */
//     public function __construct()
//     {
//         $this->middleware('guest')->except('logout');
//     }

//     public function login(Request $request)
//     {
//         $this->validateLogin($request);

//         // If the class is using the ThrottlesLogins trait, we can automatically throttle
//         // the login attempts for this application. We'll key this by the username and
//         // the IP address of the client making these requests into this application.
//         if (method_exists($this, 'hasTooManyLoginAttempts') &&
//             $this->hasTooManyLoginAttempts($request)) {
//             $this->fireLockoutEvent($request);
//             return $this->sendLockoutResponse($request);
//         }

//         if ($this->attemptLogin($request)) {
//             if ($request->hasSession()) {
//                 $request->session()->regenerate();
//             }

//             $this->clearLoginAttempts($request);

//             // Generate JWT token immediately after successful authentication
//             $user = $this->guard()->user();
//             $this->generateAndStoreJWT($user);

//             return $this->sendLoginResponse($request);
//         }

//         // If the login attempt was unsuccessful we will increment the number of attempts
//         // to login and redirect the user back to the login form. Of course, when this
//         // user surpasses their maximum number of attempts they will get locked out.
//         $this->incrementLoginAttempts($request);

//         return $this->sendFailedLoginResponse($request);
//     }

//     /**
//      * The user has been authenticated.
//      *
//      * @param  \Illuminate\Http\Request  $request
//      * @param  mixed  $user
//      * @return mixed
//      */
//     protected function authenticated(Request $request, $user)
//     {
//         // Generate JWT token for cross-site authentication
//         $jwtToken = JWTHelper::generateToken($user);
        
//         // Store JWT token in session for later use
//         // session(['jwt_token' => $jwtToken]);
//         Session::put('jwt_token', $jwtToken);

//         // You can also add the token to the user session data
//         session(['user_data' => [
//             'id' => $user->id,
//             'name' => $user->name,
//             'email' => $user->email,
//             'jwt_token' => $jwtToken
//         ]]);

//         // Continue with default behavior (redirect)
//         return redirect()->intended($this->redirectPath());
//     }

//     /**
//      * Log the user out of the application.
//      *
//      * @param  \Illuminate\Http\Request  $request
//      * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
//      */
//     public function logout(Request $request)
//     {
//         // Get JWT token before logout
//         $jwtToken = session('jwt_token');
        
//         // If you want to invalidate the JWT token on logout
//         if ($jwtToken) {
//             try {
//                 JWTHelper::invalidateToken($jwtToken);
//             } catch (\Exception $e) {
//                 // Handle token invalidation error if needed
//                 \Log::warning('JWT token invalidation failed: ' . $e->getMessage());
//             }
//         }

//         // Clear JWT session data
//         session()->forget(['jwt_token', 'user_data']);

//         // Perform standard logout
//         $this->guard()->logout();
//         $request->session()->invalidate();
//         $request->session()->regenerateToken();

//         return $this->loggedOut($request) ?: redirect('/');
//     }
// }

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Helpers\JWTHelper;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\SystemController;
use App\User;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            if ($request->hasSession()) {
                $request->session()->regenerate();
            }

            $this->clearLoginAttempts($request);

            // Get the authenticated user
            $user = Auth::user();
            
            // Generate and store JWT token
            $this->handlePostLoginJWT($user);

            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Handle JWT token generation after successful login
     *
     * @param  mixed  $user
     * @return void
     */
    protected function handlePostLoginJWT($user)
    {
        try {
            // Generate JWT token for cross-site authentication
            $jwtToken = JWTHelper::generateToken($user);
            
            // Store JWT token in session
            Session::put('jwt_token', $jwtToken);
            
            // Store user data with JWT token
            session(['user_data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'jwt_token' => $jwtToken
            ]]);

            //Log::info('JWT token generated successfully for user: ' . $user->id);
            
        } catch (\Exception $e) {
            Log::error('JWT token generation failed: ' . $e->getMessage());
            // Don't fail the login if JWT generation fails
            // You might want to handle this differently based on your requirements
        }
    }

    /**
     * The user has been authenticated. (Fallback method)
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        // This should now be handled in handlePostLoginJWT, but keeping as fallback
        if (!session('jwt_token')) {
            $this->handlePostLoginJWT($user);
        }
        Log::info('hahahehehe');
        //Log::info(session('jwt_token'));
        return redirect()->intended($this->redirectPath());
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        if ($response = $this->authenticated($request, $this->guard()->user())) {
            return $response;
        }

        return $request->wantsJson()
                    ? new \Illuminate\Http\JsonResponse([], 204)
                    : redirect()->intended($this->redirectPath());
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $user = Auth::user();
        if (isset($user)) {
            $id = $user->id;
            SystemController::logDefault('Logged Out');
            // Auth::logout();
            User::where('id', $id)->update(['status' => 0]);
        }
        // Session::flush();

        
        // Get JWT token before logout
        $jwtToken = session('jwt_token');
        
        // Invalidate JWT token if it exists
        if ($jwtToken) {
            try {
                JWTHelper::invalidateToken($jwtToken);
                Log::info('JWT token invalidated successfully');
            } catch (\Exception $e) {
                Log::warning('JWT token invalidation failed: ' . $e->getMessage());
            }
        }

        // Clear JWT session data
        session()->forget(['jwt_token', 'user_data']);

        // Perform standard logout
        $this->guard()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return $this->loggedOut($request) ?: redirect('/');
    }
}
