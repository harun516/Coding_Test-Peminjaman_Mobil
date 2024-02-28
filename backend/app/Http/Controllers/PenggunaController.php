<?php

namespace App\Http\Controllers;

use App\Models\user;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PenggunaController extends Controller
{
    public function index()
    {
        $Pengguna = user::all();

        return response()->json($Pengguna);
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama' => 'required|max:50',
            'password' => 'required|max:20',
            'alamat' => 'required|max:15',
            'telp' => 'required|max:15',
            'no_sim' => 'required|max:15',
        ]);

        $existingPengguna = user::where('id', $request->input('id'))->first();

        if ($existingPengguna) {
            $existingPengguna->update([
                'nama' => $request->input('nama'),
                'password' => Hash::make($request->input('password')),
                'alamat' => $request->input('alamat'),
                'telp' => $request->input('telp'),
                'no_sim' => $request->input('no_sim'),
            ]);
            return response()->json(['success' => true, 'message' => 'Update Pengguna Sukses'], 200);
        } else {

            $latestPengguna = user::latest('kode_pengguna')->first();
            $kodePengguna = $latestPengguna ? 'USR-' . str_pad((int) substr($latestPengguna->kode_pengguna, 4) + 1, 3, '0', STR_PAD_LEFT) : 'USR-001';

            $newPengguna = new user();
            $newPengguna->kode_pengguna = $kodePengguna;
            $newPengguna->nama = $request->input('nama');
            $newPengguna->password = Hash::make($request->password);
            $newPengguna->alamat = $request->input('alamat');
            $newPengguna->telp = $request->input('telp');
            $newPengguna->no_sim = $request->input('no_sim');
            $newPengguna->save();
            return response()->json(['success' => true, 'message' => 'Tambah Pengguna Sukses'], 200);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = user::where('nama', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Login Failed!',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Login Success!',
            'data' => $user,
            'token' => $user->createToken('authToken')->accessToken,
        ]);
    }

    public function logout(Request $request)
    {
        $removeToken = $request->user()->tokens()->delete();

        if($removeToken) {
            return response()->json([
                'success' => true,
                'message' => 'Logout Success!',  
            ]);
        }
    }

}
