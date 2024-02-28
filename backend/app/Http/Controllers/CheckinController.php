<?php

namespace App\Http\Controllers;

use App\Models\checkin;
use App\Models\checkout;
use App\Models\mobil;
use Illuminate\Http\Request;

class CheckinController extends Controller
{

    public function tersedia()
    {
        $tersedia = checkout::with('mobil')->whereNull('status')->get();

        $data = [];
        foreach ($tersedia as $peminjaman) {
            $data[] = [
                'kode_transaksi_out' => $peminjaman->kode_transaksi_out,
                'merk' => $peminjaman->mobil->merk,
                'model' => $peminjaman->mobil->model,
                'no_pol' => $peminjaman->mobil->no_pol,
            ];
        }

        return response()->json($data);
    }

    public function index()
    {
        $checkins = checkin::with('checkout.mobil')->get();

        $data = [];
        foreach ($checkins as $checkin) {
            $data[] = [
                'kode_transaksi_in' => $checkin->kode_transaksi_in,
                'merk' => $checkin->checkout->mobil->merk,
                'model' => $checkin->checkout->mobil->model,
                'no_pol' => $checkin->checkout->mobil->no_pol,
                'tanggal_mulai' => $checkin->checkout->tanggal_mulai,
                'tanggal_akhir' => $checkin->checkout->tanggal_akhir,
                'jumlah_hari' => $checkin->checkout->jumlah_hari,
            ];
        }

        return response()->json($data);
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'kode_transaksi_out' => 'required|max:20',
        ]);

        $kodeMobil = checkout::where('kode_transaksi_out', $request->input('kode_transaksi_out'))->value('kode_mobil');

        $existingPeminjaman = checkin::where('id', $request->input('id'))->first();

        if ($existingPeminjaman) {
            $existingPeminjaman->update([
                'kode_transaksi_out' => $request->input('kode_transaksi_out'),
            ]);
            return response()->json(['success' => true, 'message' => 'Update Transaksi Pengembalian Sukses'], 200);
        } else {
            $latest = checkin::latest('kode_transaksi_in')->first();
            $chekIn = $latest ? 'TIN-' . str_pad((int) substr($latest->kode_transaksi_out, 4) + 1, 3, '0', STR_PAD_LEFT) : 'TIN-001';

            mobil::where('kode_mobil', $kodeMobil)->update(['tersedia' => 'Ya']);
            checkout::where('kode_transaksi_out', $request->input('kode_transaksi_out'))->update(['status' => 'Ya']);

            $newPeminjaman = new checkin();
            $newPeminjaman->kode_transaksi_in = $chekIn;
            $newPeminjaman->kode_transaksi_out = $request->input('kode_transaksi_out');
            $newPeminjaman->save();
            return response()->json(['success' => true, 'message' => 'Tambah Transaksi Pengembalian Sukses'], 200);
        }
    }
}
