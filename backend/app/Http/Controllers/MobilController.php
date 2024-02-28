<?php

namespace App\Http\Controllers;

use App\Models\mobil;
use Illuminate\Http\Request;

class MobilController extends Controller
{
    public function index()
    {
        $mobile = mobil::all();

        return response()->json($mobile);
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'merk' => 'required|max:50',
            'model' => 'required|max:15',
            'no_pol' => 'required|max:15',
            'tarif' => 'required|max:15',
            'tersedia' => 'required|max:15',
        ]);

        $existingMobil = mobil::where('id', $request->input('id'))->first();

        if ($existingMobil) {
            $existingMobil->update([
                'merk' => $request->input('merk'),
                'model' => $request->input('model'),
                'no_pol' => $request->input('no_pol'),
                'tarif' => $request->input('tarif'),
                'tersedia' => $request->input('tersedia'),
            ]);
            return response()->json(['success' => true, 'message' => 'Update Mobil Sukses'], 200);
        } else {

            $latestMobil = mobil::latest('kode_mobil')->first();
            $kodeMobil = $latestMobil ? 'MBL-' . str_pad((int) substr($latestMobil->kode_mobil, 4) + 1, 3, '0', STR_PAD_LEFT) : 'MBL-001';

            $newMobil = new mobil();
            $newMobil->kode_mobil = $kodeMobil;
            $newMobil->merk = $request->input('merk');
            $newMobil->model = $request->input('model');
            $newMobil->no_pol = $request->input('no_pol');
            $newMobil->tarif = $request->input('tarif');
            $newMobil->tersedia = $request->input('tersedia');
            $newMobil->save();
            return response()->json(['success' => true, 'message' => 'Tambah Mobil Sukses'], 200);
        }
    }
}
