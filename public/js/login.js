// login.js
window.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('login-form');
    const errorDiv = document.getElementById('login-error');
    form.onsubmit = async function(e) {
        e.preventDefault();
        errorDiv.style.display = 'none';
        const email = document.getElementById('login-email').value;
        const password = document.getElementById('login-password').value;
        try {
            const res = await fetch('https://api.warkopkasir.web.id/user');
            const user = await res.json();
            if (user.email === email && user.password === password) {
                // Update isLoggedIn to true
                await fetch('https://api.warkopkasir.web.id/user', {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ ...user, isLoggedIn: true })
                });
                window.location.href = '/';
            } else {
                errorDiv.textContent = 'Email or password is incorrect!';
                errorDiv.style.display = 'block';
            }
        } catch (err) {
            errorDiv.textContent = 'Login failed. Please try again.';
            errorDiv.style.display = 'block';
        }
    };
});
