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
        <button id="transaksi-history-btn" class="edit-mode-btn" style="margin-top:10px;width:100%;background:#2d9cdb;color:#fff;">Transaksi History</button>
    </div>
    <div class="menu-list-section">
        <h1 class="menu-list-title" id="menu-list-title">Daftar Menu</h1>
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
            <button class="qris-btn qris-save-btn" id="qris-save-btn" style="background:#2d9cdb;color:#fff;margin-left:10px;">Simpan Transaksi</button>
        </div>
    </div>
</div>

<script>
    // Save original menu HTML for restore
    let originalMenuHTML = '';
    let originalMenuTitle = '';

    async function renderTransaksiHistory() {
        const menuList = document.getElementById('menu-list');
        const title = document.getElementById('menu-list-title');
        const historyBtn = document.getElementById('transaksi-history-btn');
        const cartSection = document.querySelector('.cart-section');
        // Save original menu if not already saved
        if (!originalMenuHTML) {
            originalMenuHTML = menuList.innerHTML;
            originalMenuTitle = title.textContent;
        }
        title.textContent = 'Transaksi History';
        menuList.innerHTML = '<div style="text-align:center;padding:20px;">Loading...</div>';
        historyBtn.textContent = 'Kembali ke Menu';
        historyBtn.classList.add('back-menu-btn');
        if (cartSection) cartSection.style.display = 'none';
        try {
            const res = await fetch('/transaksi');
            let data;
            try {
                data = await res.json();
            } catch (jsonErr) {
                menuList.innerHTML = '<div style="text-align:center;padding:20px;">Gagal memuat data (bukan JSON).</div>';
                return;
            }
            if (Array.isArray(data)) {
                if (data.length === 0) {
                    menuList.innerHTML = '<div style="text-align:center;padding:20px;">Belum ada transaksi.</div>';
                } else {
                    // Table header
                    let tableHTML = `<table style="width:100%;border-collapse:collapse;">
                        <thead>
                            <tr style="background:#f2f2f2;">
                                <th style="border:1px solid #ddd;padding:8px;">ID</th>
                                <th style="border:1px solid #ddd;padding:8px;">Total Item</th>
                                <th style="border:1px solid #ddd;padding:8px;">Total Harga</th>
                                <th style="border:1px solid #ddd;padding:8px;">Tanggal</th>
                                <th style="border:1px solid #ddd;padding:8px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>`;
                    tableHTML += data.map((trx, idx) => `
                        <tr>
                            <td style="border:1px solid #ddd;padding:8px;">${trx.transaction_id}</td>
                            <td style="border:1px solid #ddd;padding:8px;">${trx.jumlah_item}</td>
                            <td style="border:1px solid #ddd;padding:8px;">Rp ${trx.total_harga.toLocaleString('id-ID')}</td>
                            <td style="border:1px solid #ddd;padding:8px;">${new Date(trx.created_at).toLocaleString('id-ID')}</td>
                            <td style="border:1px solid #ddd;padding:8px;">
                                <button class="detail-menu-btn" data-idx="${idx}" style="padding:4px 12px;border-radius:4px;background:#2d9cdb;color:#fff;border:none;cursor:pointer;">Detail Menu</button>
                            </td>
                        </tr>
                    `).join('');
                    tableHTML += '</tbody></table>';
                    menuList.innerHTML = tableHTML;

                    // Add modal for detail menu
                    if (!document.getElementById('detail-menu-modal')) {
                        const modalDiv = document.createElement('div');
                        modalDiv.id = 'detail-menu-modal';
                        modalDiv.style.display = 'none';
                        modalDiv.style.position = 'fixed';
                        modalDiv.style.top = '0';
                        modalDiv.style.left = '0';
                        modalDiv.style.width = '100vw';
                        modalDiv.style.height = '100vh';
                        modalDiv.style.background = 'rgba(0,0,0,0.5)';
                        modalDiv.style.zIndex = '2000';
                        modalDiv.innerHTML = `<div id="detail-menu-modal-content" style="background:#fff;padding:24px;border-radius:10px;max-width:400px;margin:80px auto;position:relative;box-shadow:0 4px 16px rgba(0,0,0,0.2);">
                            <button id="close-detail-menu-modal" style="position:absolute;top:10px;right:10px;font-size:22px;background:none;border:none;cursor:pointer;">&times;</button>
                            <h3>Detail Menu</h3>
                            <div id="detail-menu-modal-body"></div>
                        </div>`;
                        document.body.appendChild(modalDiv);
                        document.getElementById('close-detail-menu-modal').onclick = function() {
                            modalDiv.style.display = 'none';
                        };
                    }

                    // Add event listeners for detail buttons
                    document.querySelectorAll('.detail-menu-btn').forEach(btn => {
                        btn.onclick = function() {
                            const idx = parseInt(btn.getAttribute('data-idx'));
                            const trx = data[idx];
                            const modalDiv = document.getElementById('detail-menu-modal');
                            const modalBody = document.getElementById('detail-menu-modal-body');
                            modalBody.innerHTML = `<ul style="padding-left:20px;">
                                ${(trx.details || []).map(d => `<li><b>${d.nama_menu}</b> x${d.jumlah} (Rp ${d.total_harga.toLocaleString('id-ID')})<br><span style='font-size:12px;color:#888;'>Harga Satuan: Rp ${d.harga_satuan.toLocaleString('id-ID')}</span></li>`).join('')}
                            </ul>`;
                            modalDiv.style.display = 'block';
                        };
                    });
                }
            } else {
                menuList.innerHTML = '<div style="text-align:center;padding:20px;">Gagal memuat data.</div>';
            }
        } catch (err) {
            menuList.innerHTML = '<div style="text-align:center;padding:20px;">Error: ' + err.message + '</div>';
        }
    }

    function restoreMenu() {
        const menuList = document.getElementById('menu-list');
        const title = document.getElementById('menu-list-title');
        const historyBtn = document.getElementById('transaksi-history-btn');
        const cartSection = document.querySelector('.cart-section');
        title.textContent = originalMenuTitle || 'Daftar Menu';
        menuList.innerHTML = originalMenuHTML;
        historyBtn.textContent = 'Transaksi History';
        historyBtn.classList.remove('back-menu-btn');
        if (cartSection) cartSection.style.display = '';
    }

    document.addEventListener('DOMContentLoaded', function () {
        const historyBtn = document.getElementById('transaksi-history-btn');
        let showingHistory = false;
        if (historyBtn) {
            historyBtn.addEventListener('click', function () {
                if (!showingHistory) {
                    renderTransaksiHistory();
                } else {
                    restoreMenu();
                }
                showingHistory = !showingHistory;
            });
        }
    });
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
    // Event listener untuk tombol simpan transaksi

document.addEventListener('DOMContentLoaded', function () {
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : null;

    if (!csrfToken) {
        console.error('CSRF token not found in <meta> tag!');
        return;
    }

    const saveBtn = document.getElementById('qris-save-btn');
    if (saveBtn) {
        saveBtn.addEventListener('click', async function () {
            if (!cart.length) {
                alert('Keranjang kosong!');
                return;
            }

            const total_harga = calculateCartTotal();
            const transaksiData = {
                menu_id: cart[0]?.menu_id || 1,
                jumlah: cart.reduce((sum, item) => sum + item.qty, 0),
                total_harga: total_harga,
                menus: cart.map(item => ({
                    menu_id: item.menu_id || 1,
                    nama_menu: item.name,
                    jumlah: item.qty,
                    harga_satuan: item.price,
                    total_harga: item.price * item.qty
                }))
            };

            try {
                const res = await fetch('/transaksi', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(transaksiData)
                });

                if (res.ok) {
                    alert('Transaksi berhasil disimpan!');
                    closeQRISDialog();
                } else {
                    alert('Gagal menyimpan transaksi!');
                }
            } catch (err) {
                alert('Error: ' + err.message);
            }
        });
    }
});

</script>
<script src="../js/menu.js"></script>
@endsection