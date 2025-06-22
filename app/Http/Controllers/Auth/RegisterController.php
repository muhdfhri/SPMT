<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:15'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\da-zA-Z]).{8,}$/u'
            ],
            'terms' => ['required', 'accepted'],
        ], [
            'name.required' => 'Nama lengkap harus diisi',
            'name.max' => 'Nama lengkap maksimal 255 karakter',
            'phone.required' => 'Nomor HP harus diisi',
            'phone.max' => 'Nomor HP maksimal 15 karakter',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.regex' => 'Password harus mengandung minimal 8 karakter dengan kombinasi huruf besar, huruf kecil, angka, dan simbol',
            'password.confirmed' => 'Konfirmasi password tidak sesuai',
            'terms.required' => 'Anda harus menyetujui syarat dan ketentuan',
            'terms.accepted' => 'Anda harus menyetujui syarat dan ketentuan',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        try {
            // Log the incoming data for debugging
            Log::info('Registration data:', $data);

            // Ensure phone is present and not empty
            if (empty($data['phone'])) {
                throw new \Exception('Phone number is required');
            }

            // Create user with phone number
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'password' => Hash::make($data['password']),
                'role' => 'mahasiswa',
            ]);

            // Log the created user for verification
            Log::info('User created:', $user->toArray());

            return $user;
        } catch (\Exception $e) {
            Log::error('Error creating user: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        try {
            // Log the request data for debugging
            Log::info('Registration request data:', $request->all());

            // Validate the request data
            $this->validator($request->all())->validate();

            // Create and register the user
            event(new Registered($user = $this->create($request->all())));

            // Return JSON response for AJAX requests
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Registrasi berhasil. Silakan login untuk mengakses dashboard.'
                ]);
            }

            // Redirect for regular requests
            return $this->registered($request, $user)
                ?: redirect($this->redirectPath());
        } catch (ValidationException $e) {
            Log::error('Validation error:', $e->errors());
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            Log::error('Registration error: ' . $e->getMessage());
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat registrasi: ' . $e->getMessage()
                ], 500);
            }
            throw $e;
        }
    }

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Registrasi berhasil. Silakan login untuk mengakses dashboard.'
            ]);
        }

        return redirect()->route('login')
            ->with('success', 'Registrasi berhasil. Silakan login untuk mengakses dashboard.');
    }

    /**
     * Get the post registration redirect path.
     *
     * @return string
     */
    public function redirectPath()
    {
        return route('login');
    }

    /**
     * Get the failed validation response for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $errors
     * @return \Illuminate\Http\Response
     */
    protected function sendFailedValidationResponse(Request $request, $errors)
    {
        if ($request->wantsJson()) {
            return response()->json([
                'success' => false,
                'errors' => $errors
            ], 422);
        }

        return parent::sendFailedValidationResponse($request, $errors);
    }
}