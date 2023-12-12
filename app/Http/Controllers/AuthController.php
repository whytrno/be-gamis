<?php

namespace App\Http\Controllers;

use App\Models\History;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    protected function errorResponse($errors, $status)
    {
        return response()->json(['errors' => $errors], $status);
    }

    // Register user
    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users|max:255',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return $this->failedResponse($validator->errors()->toArray(), 422);
        }

        $data = $validator->validated(); // Get the validated data

        $data['password'] = bcrypt($data['password']);
        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => 'user',
            'password' => $data['password'],
        ]);
        // Buat token untuk user yang baru saja dibuat dan simpan ke database
        return $this->successResponse(null, 'User created successfully', 201);
    }

    public function profile()
    {
        $profile = User::find(Auth::user()->id);
        $xp = 0;
        $histories = History::where('user_id', Auth::user()->id)->get();

        foreach ($histories as $history) {
            $xp += $history->xp;
        }

        $profile->xp = $xp;

        return $this->successResponse($profile);
    }

    public function login(Request $request)
    {
        // autentikasi email dan password harus diisi
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        // Cek apakah email dan password yang dimasukkan sesuai dengan database
        if (Auth::attempt($credentials)) {
            // Mendapatkan objek user yang diotentikasi
            $user = Auth::user();
            // Buat token untuk user yang baru saja diotentikasi dan simpan ke database
            $token = $user->createToken('auth_token')->plainTextToken;
            // Kirim token sebagai response ke client
            return $this->successResponse(['token' => $token, 'role' => $user->role], 'Login successful');
        }
        // Jika email atau password salah, kirim response error
        return $this->failedResponse('Invalid credentials', 401);
    }
    // Logout user
    public function logout(Request $request)
    {
        // Hapus token yang dimiliki user yang sedang login
        $request->user()->currentAccessToken()->delete();
        // Kirim response berhasil logout
        return $this->successResponse('Successfully logged out');
    }

    // Redirect to Google's authentication page
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->stateless()->user();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Google authentication failed', 'message' => $e->getMessage()], 401);
        }

        $authUser = $this->findOrCreateUser($user);

        // Laravel Passport: Generate access token
        $token = $authUser->createToken('Personal Access Token')->accessToken;

        return response()->json(['access_token' => $token]);
    }
    protected function findOrCreateUser($googleUser)
    {
        // Cari atau buat pengguna berdasarkan email
        $authUser = User::where('email', $googleUser->email)->first();

        if ($authUser) {
            return $authUser;
        }

        // Jika pengguna tidak ditemukan, buat pengguna baru
        return User::create([
            'name' => $googleUser->name,
            'email' => $googleUser->email,
            'role' => 'user',
            'password' => bcrypt($googleUser->id), // Gunakan ID Google sebagai password default
        ]);
    }

    public function leaderboards()
    {
        $users = User::where('role', 'user')->take(10)->get();
        $leaderboards = [];
        foreach ($users as $user) {
            $xp = 0;
            $histories = History::where('user_id', $user->id)->get();

            foreach ($histories as $history) {
                $xp += $history->xp;
            }

            $user->xp = $xp;
            array_push($leaderboards, $user);
        }

        usort($leaderboards, function ($a, $b) {
            return $b->xp - $a->xp;
        });

        return $this->successResponse($leaderboards);
    }
}
