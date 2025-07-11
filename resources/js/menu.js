function getCartItems() {
    // Example: return [{id: 1, name: 'Coffee', price: 10000, qty: 2}];
    return [];
}

// Fungsi untuk menampilkan dialog QRIS
function showQRISDialog(total) {
    // Format angka ke Rupiah
    const formattedTotal = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR'
    }).format(total).replace(/\s/g, '');
    
    document.getElementById('qris-dialog-total').textContent = formattedTotal;
    document.getElementById('qris-dialog').style.display = 'flex';
}

// Fungsi untuk menutup dialog QRIS
function closeQRISDialog() {
    document.getElementById('qris-dialog').style.display = 'none';
}

// Inisialisasi saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    // Event listener untuk tombol checkout
    const checkoutBtn = document.getElementById('checkout-btn');
    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', function() {
            const total = calculateCartTotal();
            
            if (total <= 0) {
                alert('Keranjang belanja Anda kosong!');
                return;
            }
            
            showQRISDialog(total);
        });
    }
    
    // Tutup dialog saat klik di luar area dialog
    const qrisDialog = document.getElementById('qris-dialog');
    if (qrisDialog) {
        qrisDialog.addEventListener('click', function(e) {
            if (e.target === qrisDialog) {
                closeQRISDialog();
            }
        });
    }
});

// Contoh fungsi untuk menghitung total keranjang
function calculateCartTotal() {
    // Ganti dengan logika perhitungan sebenarnya dari keranjang Anda
    // Contoh sederhana:
    const cartItems = [
        { price: 25000, quantity: 2 },
        { price: 15000, quantity: 1 }
    ];
    
    return cartItems.reduce((total, item) => {
        return total + (item.price * item.quantity);
    }, 0);
}