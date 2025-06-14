<div class="login-container" style="min-height:100vh;display:flex;align-items:center;justify-content:center;background:#f5f6fa;">
    <form id="login-form" style="background:#fff;padding:32px 40px;border-radius:10px;box-shadow:0 2px 16px rgba(0,0,0,0.08);min-width:320px;">
        <h2 style="text-align:center;margin-bottom:24px;font-weight:bold;">Login</h2>
        <div style="margin-bottom:16px;">
            <label for="email" style="display:block;margin-bottom:6px;">Email</label>
            <input type="email" id="login-email" class="form-control" style="width:100%;padding:8px 12px;border-radius:4px;border:1px solid #ccc;" required>
        </div>
        <div style="margin-bottom:24px;">
            <label for="password" style="display:block;margin-bottom:6px;">Password</label>
            <input type="password" id="login-password" class="form-control" style="width:100%;padding:8px 12px;border-radius:4px;border:1px solid #ccc;" required>
        </div>
        <button type="submit" style="width:100%;padding:10px 0;background:#2d9cdb;color:#fff;font-weight:bold;border:none;border-radius:4px;font-size:1.1rem;">Login</button>
        <div id="login-error" style="color:#e74c3c;text-align:center;margin-top:16px;display:none;"></div>
    </form>
</div>
<script src="/js/login.js"></script>
