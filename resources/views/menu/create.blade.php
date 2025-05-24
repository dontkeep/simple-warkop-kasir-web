@extends('layouts.app')

@section('content')
    <h1>Add New Menu</h1>
    <form action="{{ route('menu.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" required>
        <label for="desc">Description:</label>
        <input type="text" name="desc" id="desc" required>
        <label for="img_file">Image Upload:</label>
        <input type="file" name="img_file" id="img_file" accept="image/*">
        <span style="font-size:0.9em; color:#888;">or enter image URL below</span>
        <input type="text" name="img" id="img" placeholder="Image URL">
        <label for="rate">Rate:</label>
        <input type="number" name="rate" id="rate" required>
        <label for="price">Price:</label>
        <input type="number" name="price" id="price" required>
        <button type="submit">Add Menu</button>
    </form>
@endsection