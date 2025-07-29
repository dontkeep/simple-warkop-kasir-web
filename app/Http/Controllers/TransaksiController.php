<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Http\Requests\StoreTransaksiRequest;
use App\Http\Requests\UpdateTransaksiRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transaksis = Transaksi::with('details')->get();
        return response()->json($transaksis);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Log::info('TransaksiController@store request', $request->all());
        try {
            $transaction_id = 'TRX-' . strtoupper(uniqid());
            $transaksi = Transaksi::create([
                'transaction_id' => $transaction_id,
                'jumlah_item' => $request->jumlah, // total items
                'total_harga' => $request->total_harga,
            ]);

            $menus = $request->menus;
            if (is_array($menus)) {
                foreach ($menus as $menu) {
                    \App\Models\Detail_Transaksi::create([
                        'transaksi_id' => $transaksi->id,
                        'menu_id' => $menu['menu_id'],
                        'nama_menu' => $menu['nama_menu'],
                        'jumlah' => $menu['jumlah'],
                        'harga_satuan' => $menu['harga_satuan'],
                        'total_harga' => $menu['total_harga'],
                    ]);
                }
            }
            return response()->json(['transaksi' => $transaksi, 'message' => 'Transaksi created successfully']);
        } catch (\Exception $e) {
            Log::error('TransaksiController@store error', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaksi $transaksi)
    {
        $details = \App\Models\Detail_Transaksi::where('transaksi_id', $transaksi->id)->get();
        return response()->json(['transaksi' => $transaksi, 'details' => $details]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaksi $transaksi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaksi $transaksi)
    {
        $transaksi->update($request->only(['menu_id', 'jumlah', 'total_harga']));
        // Optionally update detail transaksi
        // ...
        return response()->json(['transaksi' => $transaksi, 'message' => 'Transaksi updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaksi $transaksi)
    {
        $transaksi->delete();
        return response()->json(['message' => 'Transaksi deleted successfully']);
    }
}
