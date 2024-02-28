<?php

namespace App\Http\Controllers;

use App\Models\checkout;
use App\Models\mobil;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class CheckoutController extends Controller
{

    public function tersedia()
    {
        $tersedia = mobil::where('tersedia', 'Ya')->get();

        return response()->json($tersedia);
    }

    public function index()
    {
        $peminjaman = checkout::with('mobil')->get();

        $data = [];
        foreach ($peminjaman as $peminjaman) {
            $data[] = [
                'kode_transaksi_out' => $peminjaman->kode_transaksi_out,
                'merk' => $peminjaman->mobil->merk,
                'model' => $peminjaman->mobil->model,
                'no_pol' => $peminjaman->mobil->no_pol,
                'tanggal_mulai' => $peminjaman->tanggal_mulai,
                'tanggal_akhir' => $peminjaman->tanggal_akhir,
                'jumlah_hari' => $peminjaman->jumlah_hari,
            ];
        }

        return response()->json($data);
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'kode_mobil' => 'required|max:20',
            'tanggal_mulai' => 'required|max:50',
            'tanggal_akhir' => 'required|max:50',
        ]);

        $existingPeminjaman = checkout::where('id', $request->input('id'))->first();

        if ($existingPeminjaman) {
            // Hitung jumlah hari di sini
            $startDate = Carbon::parse($request->input('tanggal_mulai'));
            $endDate = Carbon::parse($request->input('tanggal_akhir'));
            $jumlahHari = $endDate->diffInDays($startDate);

            $existingPeminjaman->update([
                'tanggal_mulai' => $request->input('tanggal_mulai'),
                'tanggal_akhir' => $request->input('tanggal_akhir'),
                'jumlah_hari' => $jumlahHari,
            ]);

            return response()->json(['success' => true, 'message' => 'Update Transaksi Peminjaman Sukses'], 200);
        } else {

            $latest = checkout::latest('kode_transaksi_out')->first();
            $chekOut = $latest ? 'TOUT-' . str_pad((int) substr($latest->kode_transaksi_out, 4) + 1, 3, '0', STR_PAD_LEFT) : 'TOUT-001';

            // Hitung jumlah hari di sini
            $startDate = Carbon::parse($request->input('tanggal_mulai'));
            $endDate = Carbon::parse($request->input('tanggal_akhir'));
            $jumlahHari = $endDate->diffInDays($startDate);

            $newPeminjaman = new checkout();
            $newPeminjaman->kode_transaksi_out = $chekOut;
            $newPeminjaman->kode_mobil = $request->input('kode_mobil');
            $newPeminjaman->tanggal_mulai = $request->input('tanggal_mulai');
            $newPeminjaman->tanggal_akhir = $request->input('tanggal_akhir');
            $newPeminjaman->jumlah_hari = $jumlahHari;
            $newPeminjaman->save();

            mobil::where('kode_mobil', $request->input('kode_mobil'))->update(['tersedia' => 'Tidak']);

            return response()->json(['success' => true, 'message' => 'Tambah Transaksi Peminjaman Sukses'], 200);
        }
    }

}
