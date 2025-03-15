<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MenuController extends Controller
{
    private $apiUrl = 'http://localhost:3000/menu-warkop';

    public function index()
    {
        $response = Http::get($this->apiUrl);
        $menus = $response->json();
        return view('menu.index', compact('menus'));
    }

    public function create()
    {
        return view('menu.create');
    }

    public function store(Request $request)
    {
        $data = $request->only(['name', 'desc', 'img', 'rate', 'price']);
        Http::post($this->apiUrl, $data);
        return redirect()->route('menu.index');
    }

    public function edit($id)
    {
        $response = Http::get("{$this->apiUrl}/{$id}");
        $menu = $response->json();
        return view('menu.edit', compact('menu'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->only(['name', 'desc', 'img', 'rate', 'price']);
        Http::put("{$this->apiUrl}/{$id}", $data);
        return redirect()->route('menu.index');
    }

    public function destroy($id)
    {
        Http::delete("{$this->apiUrl}/{$id}");
        return redirect()->route('menu.index');
    }
}
