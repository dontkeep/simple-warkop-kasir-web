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
        $data = $request->only(['name', 'desc', 'rate', 'price', 'category']);
        // Handle image upload
        if ($request->hasFile('img_file') && $request->file('img_file')->isValid()) {
            $image = $request->file('img_file');
            $filename = uniqid('menu_') . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('assets/image'), $filename);
            $data['img'] = $filename; // Store only the filename
        } else if ($request->filled('img')) {
            $data['img'] = $request->input('img');
        } else {
            $data['img'] = '';
        }
        \Illuminate\Support\Facades\Http::post($this->apiUrl, $data);
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
        $data = $request->only(['name', 'desc', 'rate', 'price', 'category']);
        // Handle image upload
        if ($request->hasFile('img_file') && $request->file('img_file')->isValid()) {
            $image = $request->file('img_file');
            $filename = uniqid('menu_') . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('assets/image'), $filename);
            $data['img'] = $filename; // Store only the filename
        } else if ($request->filled('img')) {
            $data['img'] = $request->input('img');
        } else {
            $data['img'] = '';
        }
        \Illuminate\Support\Facades\Http::put("{$this->apiUrl}/{$id}", $data);
        return redirect()->route('menu.index');
    }

    public function destroy($id)
    {
        Http::delete("{$this->apiUrl}/{$id}");
        return redirect()->route('menu.index');
    }

    public function uploadImage(Request $request)
    {
        if ($request->hasFile('img_file') && $request->file('img_file')->isValid()) {
            $image = $request->file('img_file');
            $filename = uniqid('menu_') . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('assets/image'), $filename);
            return response()->json(['filename' => $filename]);
        }
        return response()->json(['error' => 'No valid image uploaded'], 400);
    }

    public function deleteImage(Request $request)
    {
        $filename = $request->input('filename');
        if ($filename) {
            $path = public_path('assets/image/' . $filename);
            if (file_exists($path)) {
                unlink($path);
                return response()->json(['success' => true]);
            } else {
                return response()->json(['error' => 'File not found'], 404);
            }
        }
        return response()->json(['error' => 'No filename provided'], 400);
    }
}
