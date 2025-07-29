<?php

namespace App\Http\Controllers;

use App\Models\Detail_Transaksi;
use Illuminate\Http\Request;

class DetailTransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $details = Detail_Transaksi::all();
        return response()->json($details);
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
        $detail = Detail_Transaksi::create($request->only([
            'transaksi_id', 'menu_id', 'nama_menu', 'jumlah', 'harga_satuan', 'total_harga'
        ]));
        return response()->json(['detail_transaksi' => $detail, 'message' => 'Detail transaksi created successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Detail_Transaksi $detail_Transaksi)
    {
        return response()->json($detail_Transaksi);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Detail_Transaksi $detail_Transaksi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Detail_Transaksi $detail_Transaksi)
    {
        $detail_Transaksi->update($request->only([
            'menu_id', 'nama_menu', 'jumlah', 'harga_satuan', 'total_harga'
        ]));
        return response()->json(['detail_transaksi' => $detail_Transaksi, 'message' => 'Detail transaksi updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Detail_Transaksi $detail_Transaksi)
    {
        $detail_Transaksi->delete();
        return response()->json(['message' => 'Detail transaksi deleted successfully']);
    }
}
