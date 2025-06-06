@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="../css/menu.css">
<x-navbar />
<div class="menu-cart-layout">
    <div class="category-section">
        <div class="category-title">Kategori</div>
        <ul class="category-list">
            <li><button class="category-btn active" onclick="filterCategory('all')">Semua</button></li>
            <li><button class="category-btn" onclick="filterCategory('Drinks')">Drinks</button></li>
            <li><button class="category-btn" onclick="filterCategory('Meals')">Meals</button></li>
            <li><button class="category-btn" onclick="filterCategory('Snack')">Snack</button></li>
        </ul>
        <button id="edit-mode-btn" class="edit-mode-btn">Edit Menu</button>
        <a href="{{ route('menu.create') }}" id="add-menu-btn" class="edit-mode-btn add-menu-btn">+ Add Menu</a>
    </div>
    <div class="menu-list-section">
        <h1 class="menu-list-title">Menu List</h1>
        <div id="menu-list" class="menu-list-cards">
            @foreach($menus as $menu)
                <div class="menu-list-card-wrapper">
                    <x-menu-item 
                        :img="$menu['img']"
                        :name="$menu['name']"
                        :desc="$menu['desc']"
                        :category="isset($menu['category']) ? $menu['category'] : '-'"
                        :price="$menu['price']"
                    />
                </div>
            @endforeach
        </div>
    </div>
    <div class="cart-section">
        <h2 class="cart-title">Cart</h2>
        <div id="cart-items"></div>
        <div id="cart-total" class="cart-total"></div>
        <button id="checkout-btn" class="checkout-btn">Checkout</button>
    </div>
</div>
<!-- Modal for Add/Edit Menu -->
<div id="menu-modal" class="menu-modal-overlay" style="display:none;">
    <div class="menu-modal-card">
        <button class="modal-close-btn" onclick="closeMenuModal()">&times;</button>
        <h2 id="modal-title">Add/Edit Menu</h2>
        <form id="menu-form" enctype="multipart/form-data">
            <input type="hidden" id="modal-menu-id">
            <div class="modal-form-group">
                <label for="modal-menu-name">Name</label>
                <input type="text" id="modal-menu-name" required>
            </div>
            <div class="modal-form-group">
                <label for="modal-menu-price">Price</label>
                <input type="number" id="modal-menu-price" required>
            </div>
            <div class="modal-form-group">
                <label for="modal-menu-desc">Description</label>
                <textarea id="modal-menu-desc" rows="2"></textarea>
            </div>
            <div class="modal-form-group">
                <label for="modal-menu-category">Category</label>
                <select id="modal-menu-category">
                    <option value="Drinks">Drinks</option>
                    <option value="Meals">Meals</option>
                    <option value="Snack">Snack</option>
                </select>
            </div>
            <div class="modal-form-group">
                <label for="modal-menu-img">Image</label>
                <input type="file" id="modal-menu-img" accept="image/*">
            </div>
            <button type="submit" class="modal-save-btn">Save</button>
        </form>
    </div>
</div>
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
<script>
    window.csrfToken = '{{ csrf_token() }}';
</script>
<script src="../js/menu.js"></script>
@endsection