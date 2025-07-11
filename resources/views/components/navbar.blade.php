<nav class="navbar" style="background: #23223a; padding: 0; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
    <div class="navbar-content" style="display: flex; align-items: center; justify-content: space-between; max-width: 1200px; margin: 0 auto; height: 64px;">
        <div style="display: flex; align-items: baseline; gap: 12px;">
            <span class="navbar-title" style="font-size: 2rem; font-weight: bold; color: #fff; letter-spacing: 1px;">Warkop Cashier</span>
            <span class="navbar-subtitle" style="font-size: 1.1rem; color: #b0b3c6; font-weight: 400;">Ryan</span>
        </div>
        <div style="display: flex; align-items: center; gap: 16px;">
            <a href="https://merchant.qris.interactive.co.id/v2/m/kontenr.php?idir=pages/summary.php" target="_blank" class="navbar-midtrans-btn" title="Go to QRIS Dashboard" style="padding: 10px 24px; background: linear-gradient(90deg,#2d9cdb,#1b6ca8); color: #fff; border-radius: 6px; text-decoration: none; font-weight: bold; font-size: 1.1rem; box-shadow: 0 2px 8px rgba(45,156,219,0.12); transition: background 0.2s; border: none; outline: none;">
                QRIS Dashboard
            </a>
            <button id="logout-btn" style="padding: 10px 24px; background: #e74c3c; color: #fff; border-radius: 6px; border: none; font-weight: bold; font-size: 1.1rem; cursor: pointer; transition: background 0.2s;">Logout</button>
        </div>
    </div>
</nav>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var logoutBtn = document.getElementById('logout-btn');
    if (logoutBtn) {
        logoutBtn.onclick = async function() {
            try {
                const res = await fetch('https://api.warkopkasir.web.id/user');
                const user = await res.json();
                await fetch('https://api.warkopkasir.web.id/user', {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ ...user, isLoggedIn: false })
                });
                window.location.href = '/login';
            } catch (err) {
                alert('Logout failed.');
            }
        };
    }
});
</script>
