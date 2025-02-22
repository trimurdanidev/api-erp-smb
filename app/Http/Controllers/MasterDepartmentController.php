<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterDepartment;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class MasterDepartmentController extends Controller
{
    public function index(): JsonResponse
    {
        $master_user = MasterDepartment::all();
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
        $department = MasterDepartment::find($id);
        if (!$department) {
            return response()->json(['message' => 'Department Tidak Ditemukan'], 404);
        }
        return response()->json($department, 200);
    }

    public function store(Request $request): JsonResponse
    {
        $validatedData = validator::make($request->all(), [
            'departmentid' => 'required|string|max:2|unique:master_department',
            'departmentcode' => 'required|string|max:11',
            'description' => 'required|string|max:30',
            'created_by' => 'nullable|string|max:30'
        ]);

        if ($validatedData->fails()) {
            return response()->json(['errors' => $validatedData->errors()], 422);
        }

        try {
            $department = MasterDepartment::create($request->all());
            return response()->json([
                'message' => "Berhasil Menambahkan Data",
                'data' => $department
            ], 201);

        } catch (\Exception $exception) {
            return response()->json([
                'message' => 'Gagal Menambahkan Data',
                'error' => $exception
            ], 400);
        }

    }

    public function update(Request $request, $id): JsonResponse
    {
        $department = MasterDepartment::find($id);
        if (!$department) {
            return response()->json(['message' => 'Department Tidak Ditemukan'], 404);
        }

        $validatedData = $request->validate([
            'departmentcode' => 'required|string|max:11',
            'description' => 'nullable|string|max:30'
        ]);

        try {
            $department->update($validatedData);
            return response()->json($department, 200);

        } catch (\Exception $exception) {
            return response()->json([
                'message' => 'Gagal Mengedit Data',
                'error' => $exception
            ], 400);
        }
    }

    public function destroy($id): JsonResponse
    {
        $department = MasterDepartment::find($id);
        if (!$department) {
            return response()->json(['message' => 'Department Tidak Ditemukan'], 404);
        }

        $department->delete();
        return response()->json(['message' => 'Department Berhasil Dihapus'], 200);
    }
}
