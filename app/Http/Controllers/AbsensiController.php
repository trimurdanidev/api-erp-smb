<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use PHPUnit\Framework\Constraint\Count;

class AbsensiController extends Controller
{
    public function index(): JsonResponse
    {
        $absensiAll = Absensi::all();

        if (!$absensiAll) {
            return response()->json([
                'message' => 'Data Kosong'
            ], 404);
        }

        return response()->json([
            'message' => "Berhasil",
            'data' => $absensiAll
        ], 200);
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }


            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:master_user,user',
                'date' => 'required|date',
                'time_in' => 'nullable|date_format:H:i:s',
                'longitude_in' => 'nullable|string',
                'latitude_in' => 'nullable|string',
                'images_in' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'time_out' => 'nullable|date_format:H:i:s',
                'longitude_out' => 'nullable|string',
                'latitude_out' => 'nullable|string',
                'images_out' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'absensi_ref' => 'nullable|string',
                'created_by' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            // Cek apakah user sudah melakukan absensi pada tanggal yang sama
            $existingAbsensi = Absensi::where('user_id', $request->user_id)
                ->where('date', $request->date)
                ->count();

            if ($existingAbsensi > 0) {
                return response()->json(['message' => 'Anda sudah melakukan absensi hari ini'], 400);
            }

            $data = $request->all();

            if ($request->hasFile('images_in')) {

                 if ($request->hasFile('images_in')) {
            //     Storage::delete('public/' . $absensi->images_in);
                $data['images_in'] = $request->file('images_in')->store('absensi_images', 'public');
            }
            //     $data['images_in'] = $request->file('images_in');
            //     $fileName = 'IN_' . $request->user_id . '_' . time() . '.' . $data['images_in']->getClientOriginalExtension();
            //     $data['images_in']->move(public_path('absensi_images'), $fileName);
            }

            // if ($request->hasFile('images_out')) {
            //     $data['images_out'] = $request->file('images_out')->store('absensi_images', 'public');
            // }

            $absensi = Absensi::create($data);

            return response()->json([
                'message' => "Berhasil Absensi Masuk",
                'data' => $absensi
            ], 201);

        } catch (\Exception $exception) {
            return response()->json([
                'message' => 'Gagal Menambahkan Data',
                'error' => $exception
            ], 400);
        }
    }

    public function showId($id): JsonResponse
    {
        $absensi = Absensi::find($id);
        if (!$absensi) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }
        return response()->json([
            'message' => "Berhasil",
            'data' => $absensi
        ], 200);
    }

    public function showByDept($deptId): JsonResponse
    {
        $absensiDept = Absensi::select([
            'id',
            'user_id',
            'date',
            'time_in',
            'longitude_in',
            'latitude_in',
            'images_in',
            'time_out',
            'longitude_out',
            'latitude_out',
            'images_out',
            'schedule_code',
            'absensi_ref',
            'created_by',
            'created_at',
            'updated_at',
            'deleted_at',
            'master_user.user',
            'master_user.description',
            'master_user.username',
            'master_department.departmentcode',
            'master_department.description AS department',
        ])
            ->join('master_user', 'master_user.id', '=', 'absensi.user_id')
            ->join('master_department', 'master_user.departmentid', '=', 'master_department.departmentid')
            ->where("master_department.departmentid", "=", $deptId)
            ->get();


        if (!$absensiDept) {
            return response()->json(['message' => 'Data Tidak Ditemukan'], 404);
        }
        return response()->json([
            'message' => "Berhasil",
            'data' => $absensiDept
        ], 200);
    }

    public function showAbsenDayUser($absensiId, $dateAbsen): JsonResponse
    {
        // \DB::enableQueryLog();
        $absensi = Absensi::select([
            'absensi.id',
            'user_id',
            'date',
            'time_in',
            'longitude_in',
            'latitude_in',
            'images_in',
            'time_out',
            'longitude_out',
            'latitude_out',
            'images_out',
            'schedule_code',
            'absensi_ref',
            'absensi.created_by',
            'absensi.created_at',
            'absensi.updated_at',
            'absensi.deleted_at',
            'master_user.user',
            'master_user.description',
            'master_user.username',
            'master_department.departmentcode',
            'master_department.description AS department',
        ])
            ->join('master_user', 'master_user.user', '=', 'absensi.user_id')
            ->join('master_department', 'master_user.departmentid', '=', 'master_department.departmentid')
            ->where("absensi.id", "=", $absensiId)
            ->where("date", "=", $dateAbsen)
            ->first();
        // ->count($absensi);
        // dd(\DB::getQueryLog());


        if (!$absensi) {
            return response()->json(['message' => 'Data Tidak Ditemukan'], 404);
        }

        return response()->json([
            'message' => "Berhasil",
            'data' => $absensi
        ], 200);
    }

    public function showAbsenDay($userid, $dateAbsen): JsonResponse
    {
        // \DB::enableQueryLog();
        $absensi = Absensi::select([
            'absensi.id',
            'user_id',
            'date',
            'time_in',
            'longitude_in',
            'latitude_in',
            'images_in',
            'time_out',
            'longitude_out',
            'latitude_out',
            'images_out',
            'schedule_code',
            'absensi_ref',
            'absensi.created_by',
            'absensi.created_at',
            'absensi.updated_at',
            'absensi.deleted_at',
            'master_user.user',
            'master_user.description',
            'master_user.username',
            'master_department.departmentcode',
            'master_department.description AS department',
        ])
            ->join('master_user', 'master_user.user', '=', 'absensi.user_id')
            ->join('master_department', 'master_user.departmentid', '=', 'master_department.departmentid')
            ->where("absensi.user_id", "=", $userid)
            ->where("date", "=", $dateAbsen)
            ->first();
        // ->count($absensi);
        // dd(\DB::getQueryLog());


        if (!$absensi) {
            return response()->json(['message' => 'Data Tidak Ditemukan'], 404);
        }

        return response()->json([
            'message' => "Berhasil",
            'data' => $absensi
        ], 200);
    }

    public function update(Request $request): JsonResponse
    {
        try {
            $id = $request->id;
            $user = Auth::user();
            if (!$user) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            $absensi = Absensi::find($id);
            if (!$absensi) {
                return response()->json(['message' => 'Data tidak ditemukan'], 404);
            }

            $validator = Validator::make($request->all(), [
                'time_out' => 'nullable|date_format:H:i:s',
                'longitude_out' => 'nullable|string',
                'latitude_out' => 'nullable|string',
                'images_out' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'absensi_ref' => 'nullable|string',
                'created_by' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            // if ($request->hasFile('images_out')) {
            //     Storage::delete('public/' . $absensi->images_out);
            //     $absensi->images_out = $request->file('images_out')->store('absensi_images', 'public');
            // }

            if ($request->hasFile('images_out')) {
                $data['images_out'] = $request->file('images_out');
                $fileName = 'OUT' . $request->user_id . '_' . time() . '.' . $data['images_out']->getClientOriginalExtension();
                $data['images_out']->move(public_path('absensi_images'), $fileName);
            }


            $absensi->update($request->except(['images_out']));

            return response()->json([
                'message' => "Berhasil Absensi Pulang",
                'data' => $absensi
            ], 200);

        } catch (\Exception $exception) {
            return response()->json([
                'message' => 'Gagal Absen',
                'error' => $exception
            ], 400);
        }
    }

    public function destroy(Request $request): JsonResponse
    {
        $id = $request->id;
        $absensi = Absensi::find($id);
        if (!$absensi) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }
        $absensi->delete();
        return response()->json(['message' => 'Data berhasil dihapus'], 200);
    }
}
