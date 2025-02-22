<?php

namespace App\Http\Controllers;

use Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\MasterUser;
use App\Models\User;
use App\Http\Controllers\Controller;
use PhpParser\Node\Stmt\TryCatch;

class MasterUserController extends Controller
{
    public function index(): JsonResponse
    {
        $master_user = MasterUser::all();

        if (!$master_user) {
            return response()->json([
                'message' => 'Data Kosong'
            ], 404);
        }

        return response()->json([
            'message' => "Berhasil",
            'data' => $master_user
        ], 200);
    }

    public function showId($id): JsonResponse
    {
        $user = MasterUser::find($id);
        if (!$user) {
            return response()->json(['message' => 'User Tidak Ditemukan'], 404);
        }
        return response()->json($user, 200);
    }

    public function showUser($user): JsonResponse
    {
        $masteruser = MasterUser::where('user', '=', $user)->first();
        if (!$masteruser) {
            return response()->json(['message' => 'User Tidak Ditemukan'], 404);
        }
        return response()->json([
            'message' => "Berhasil",
            'data' => $masteruser
        ], 200);
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'user' => 'required|string|max:30|unique:master_user',
                'password' => 'required|string|min:6',
                'description' => 'nullable|string|max:255',
                'username' => 'nullable|string|max:50',
                'phone' => 'nullable|string|max:20',
                'nik' => 'nullable|string|max:50',
                'departmentid' => 'nullable|integer',
                'unitid' => 'nullable|integer',
                'avatar' => 'nullable|string',
                'created_by' => 'nullable|string|max:30'
            ]);

            // $setValidator = validator::make(null,[$validatedData]);

            // if ($setValidator->fails()) {
            //     return response()->json(['errors' => $validatedData->errors()], 422);
            // }

            $validatedData['password'] = Hash::make($validatedData['password']);
            $user = MasterUser::create($validatedData);
            return response()->json([
                'message' => "Berhasil Menambahkan Data",
                'data' => $user
            ], 201);


        } catch (\Exception $ex) {
            return response()->json([
                'message' => 'Gagal Menambahkan Data',
                'error' => $ex
            ], 400);
        }

    }

    public function update(Request $request, $id): JsonResponse
    {
        $user = MasterUser::find($id);
        if (!$user) {
            return response()->json(['message' => 'User Tidak Ditemukan'], 404);
        }

        $validatedData = $request->validate([
            'description' => 'nullable|string|max:255',
            'username' => 'nullable|string|max:50',
            'phone' => 'nullable|string|max:20',
            'nik' => 'nullable|string|max:50',
            'departmentid' => 'nullable|integer',
            'unitid' => 'nullable|integer',
            'avatar' => 'nullable|string'
        ]);

        if ($request->has('password')) {
            $validatedData['password'] = Hash::make($request->password);
        }

        try {
            $user->update($validatedData);
            return response()->json([
                'message' => "Berhasil Mengedit Data",
                'data' => $user
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => 'Gagal Mengedit Data',
                'error' => $exception
            ], 400);
        }
    }

    public function destroy($id): JsonResponse
    {
        $user = MasterUser::find($id);
        if (!$user) {
            return response()->json(['message' => 'User Tidak Ditemukan'], 404);
        }

        $user->delete();
        return response()->json(['message' => 'User Berhasil Dihapus'], 200);
    }

    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'user' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        // $credentials = ['user' => $request->user, 'password' => $request->password];
        // if (!$token = Auth::attempt($credentials)) {
        //     return response()->json(['error' => 'Unauthorized'], 401);
        // }


        $user = MasterUser::where('user', $request->user)->first();

        if (!$user) {
            return response()->json(['error' => 'User Belum Terdaftar'], 404);
        }


        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Password Salah'], 401);
        }

        $token = Auth::guard('api')->login($user);
        return response()->json([
            'status' => 'success',
            'message' => 'Login Berhasil',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 60,
            'master_user' => [
                'user' => $user->user,
                'description' => $user->description,
                'username' => $user->username,
                'phone' => $user->phone,
                'nik' => $user->nik,
                'departmentid' => $user->departmentid,
                'unitid' => $user->unitid
            ]
        ]);
    }

    public function logout(): JsonResponse
    {
        Auth::guard('api')->logout();
        return response()->json(['message' => 'Logout Berhasil']);
    }
}
