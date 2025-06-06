document.addEventListener('DOMContentLoaded', function() {
    const checkoutBtn = document.getElementById('checkout-btn');
    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', function() {
            // Collect cart data (implement this according to your cart logic)
            const cartItems = getCartItems(); // You must implement getCartItems()
            if (!cartItems.length) {
                alert('Cart is empty!');
                return;
            }
            fetch('/midtrans/get-snap-token', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.csrfToken
                },
                body: JSON.stringify({
                    cart: cartItems
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.snap_token) {
                    window.snap.pay(data.snap_token, {
                        onSuccess: function(result) {
                            alert('Payment success!');
                            // Optionally clear cart or redirect
                        },
                        onPending: function(result) {
                            alert('Payment pending!');
                        },
                        onError: function(result) {
                            alert('Payment failed!');
                        },
                        onClose: function() {
                            // User closed the popup
                        }
                    });
                } else {
                    alert('Failed to get Snap token');
                }
            })
            .catch(() => alert('Checkout error'));
        });
    }
});

// Dummy getCartItems function, replace with your actual cart logic
function getCartItems() {
    // Example: return [{id: 1, name: 'Coffee', price: 10000, qty: 2}];
    return [];
}