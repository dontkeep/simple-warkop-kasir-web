@extends('layouts.app')

@section('content')
    <h1>Edit Menu</h1>
    <form action="{{ route('menu.update', $menu['id']) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" value="{{ $menu['name'] }}" required>
        <label for="desc">Description:</label>
        <input type="text" name="desc" id="desc" value="{{ $menu['desc'] }}" required>
        <label for="img_file">Image Upload:</label>
        <input type="file" name="img_file" id="img_file" accept="image/*">
        <span style="font-size:0.9em; color:#888;">or enter image URL below</span>
        <input type="text" name="img" id="img" value="{{ $menu['img'] }}" placeholder="Image URL">
        <label for="rate">Rate:</label>
        <input type="number" name="rate" id="rate" value="{{ $menu['rate'] }}" required>
        <label for="price">Price:</label>
        <input type="number" name="price" id="price" value="{{ $menu['price'] }}" required>
        <button type="submit">Update Menu</button>
    </form>
@endsection