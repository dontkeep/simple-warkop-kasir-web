@extends('layouts.app')

@section('content')
<style>
    /* QRIS Dialog Styles */
    .qris-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
        z-index: 1000;
        justify-content: center;
        align-items: center;
    }

    .qris-dialog {
        background: white;
        padding: 25px;
        border-radius: 10px;
        width: 320px;
        max-width: 90%;
        text-align: center;
        position: relative;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .qris-close {
        position: absolute;
        top: 10px;
        right: 10px;
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        color: #777;
    }

    .qris-image-container {
        margin: 15px 0;
        padding: 10px;
        background: #f9f9f9;
        border-radius: 8px;
        display: inline-block;
    }

    .qris-image {
        width: 100%;
        max-width: 250px;
        height: auto;
    }

    .qris-total {
        font-size: 18px;
        margin: 15px 0;
        font-weight: bold;
        color: #2a6496;
    }

    .qris-actions {
        margin-top: 20px;
    }

    .qris-btn {
        padding: 8px 20px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        border: none;
        transition: all 0.3s;
    }

    .qris-close-btn {
        background: #f44336;
        color: white;
    }

    .qris-close-btn:hover {
        background: #d32f2f;
    }
</style>

<link rel="stylesheet" href="../css/menu.css">
<x-navbar />
<div class="menu-cart-layout">
    <div class="category-section">
        <div class="category-title">Kategori</div>
        <ul class="category-list">
            <li><button class="category-btn active" onclick="filterCategory('all')">Semua</button></li>
            <li><button class="category-btn" onclick="filterCategory('Minuman')">Minuman</button></li>
            <li><button class="category-btn" onclick="filterCategory('Makanan')">Makanan</button></li>
            <li><button class="category-btn" onclick="filterCategory('Cemilan')">Cemilan</button></li>
        </ul>
        <button id="edit-mode-btn" class="edit-mode-btn">Ubah Menu</button>
        <a href="{{ route('menu.create') }}" id="add-menu-btn" class="edit-mode-btn add-menu-btn">+ Tambah Menu</a>
    </div>
    <div class="menu-list-section">
        <h1 class="menu-list-title">Daftar Menu</h1>
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
        <h2 class="cart-title">Keranjang</h2>
        <div id="cart-items"></div>
        <div id="cart-total" class="cart-total"></div>
        <button id="checkout-btn" class="checkout-btn">Bayar</button>
    </div>
</div>

<!-- Modal for Add/Edit Menu -->
<div id="menu-modal" class="menu-modal-overlay" style="display:none;">
    <div class="menu-modal-card">
        <button class="modal-close-btn" onclick="closeMenuModal()">&times;</button>
        <h2 id="modal-title">Tambah/Ubah Menu</h2>
        <form id="menu-form" enctype="multipart/form-data">
            <input type="hidden" id="modal-menu-id">
            <div class="modal-form-group">
                <label for="modal-menu-name">Nama</label>
                <input type="text" id="modal-menu-name" required>
            </div>
            <div class="modal-form-group">
                <label for="modal-menu-price">Harga</label>
                <input type="number" id="modal-menu-price" required>
            </div>
            <div class="modal-form-group">
                <label for="modal-menu-desc">Deskripsi</label>
                <textarea id="modal-menu-desc" rows="2"></textarea>
            </div>
            <div class="modal-form-group">
                <label for="modal-menu-category">Kategori</label>
                <select id="modal-menu-category">
                    <option value="Minuman">Minuman</option>
                    <option value="Makanan">Makanan</option>
                    <option value="Cemilan">Cemilan</option>
                </select>
            </div>
            <div class="modal-form-group">
                <label for="modal-menu-img">Image</label>
                <input type="file" id="modal-menu-img" accept="image/*">
            </div>
            <button type="submit" class="modal-save-btn">Simpan</button>
        </form>
    </div>
</div>

<!-- QRIS Payment Modal -->
<!-- QRIS Dialog -->
<div id="qris-dialog" class="qris-overlay">
    <div class="qris-dialog">
        <button class="qris-close" onclick="closeQRISDialog()">&times;</button>
        <h3>Pembayaran QRIS</h3>
        <p>Scan kode QR berikut untuk melakukan pembayaran</p>
        
        <div class="qris-image-container">
            <img src="{{ asset('assets/image/qris.jpg') }}" class="qris-image" alt="QR Code Pembayaran">
        </div>
        
        <div class="qris-total">
            Total: <span id="qris-dialog-total">Rp 0</span>
        </div>
        
        <div class="qris-actions">
            <button class="qris-btn qris-close-btn" onclick="closeQRISDialog()">Tutup</button>
        </div>
    </div>
</div>

<script>
    window.csrfToken = '{{ csrf_token() }}';
    window.assetPath = '{{ asset('') }}';
</script>

<script>
    // Fungsi untuk menampilkan dialog QRIS
    function showQRISDialog(total) {
        document.getElementById('qris-dialog-total').textContent = 'Rp ' + total.toLocaleString('id-ID');
        document.getElementById('qris-dialog').style.display = 'flex';
    }
    
    // Fungsi untuk menutup dialog QRIS
    function closeQRISDialog() {
        document.getElementById('qris-dialog').style.display = 'none';
        cart = [];            
        renderCart(); 
    }
    
    // Event listener untuk tombol checkout
    document.getElementById('checkout-btn').addEventListener('click', function() {
        const total = calculateCartTotal();
        
        if (total <= 0) {
            alert('Keranjang belanja Anda kosong!');
            return;
        }
        
        showQRISDialog(total);
    });
    
    // Fungsi untuk menghitung total (contoh)
    function calculateCartTotal() {
        return cart.reduce((total, item) => total + (item.price * item.qty), 0);
    }
</script>
<script src="../js/menu.js"></script>
@endsection