<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:250',
            'email' => 'required|string|email|max:250|unique:users',
            'phone_number' => 'required|string|max:250|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'user' => $user,
            'message' => 'AKUN BERHASIL DIBUAT'
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message'=>'AKUN TIDAK DITEMUKAN'], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
            'message' => 'LOGIN BERHASIL'
        ], 200);
    }

    public function logout(Request $request)
    {
        if ($request->user()) {
            $request->user()->tokens()->delete();
            return response()->json([
                'message' => 'LOGOUT BERHASIL',
            ]);
        }
        return response()->json([
            'message' => 'TIDAK ADA AKUN YANG TERAUTENTIFIKASI',
        ], 401);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:250',
            'password' => 'required|string|min:8',
        ]);

        $user= User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }
        $user->name = $request->name;
        $user->password = Hash::make($request->password);
        $user->save();
        return response()->json([
            'message' => 'Update berhasil dilakukan',
            'user' => $user
        ], 200);
    }

    public function index()
    {
        $this->authorizeAdmin();
        $user = User::all();
        return response()->json($user);
    }

    public function show(string $id)
    {
        $this->authorizeAdmin();
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    public function destroy(string $id)
    {
        $this->authorizeAdmin();
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['message' => 'Akun berhasil dihapus']);

    }

    public function updateRole(Request $request, string $id)
    {
        $this->authorizeAdmin();
        $request->validate([
            'role' => 'required|string|in:user,admin',
        ]);

        $user = User::findOrFail($id);

        if ($user->role === $request->role) {
            return response()->json(['message' => 'ROLE SUDAH SESUAI, TIDAK ADA PERUBAHAN']);
        }

        $user->role = $request->role;
        $user->save();

        return response()->json([
            'message' => 'ROLE BERHASIL DIUBAH',
            'user' => $user
        ]);
    }

    private function authorizeAdmin(){
        if (Auth::user()->role !== 'admin'){
            abort(403, 'AKSES DITOLAK. HANYA ADMIN YANG BOLEH MENGAKSES');
        }
    }
}
