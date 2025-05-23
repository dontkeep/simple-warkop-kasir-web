@extends('layouts.app')

@section('content')
<style>
    body {
        background: #e5e7eb;
    }
    .navbar {
        width: 100vw;
        background: #22223b;
        color: #fff;
        padding: 18px 0 16px 0;
        margin-bottom: 32px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.07);
        position: sticky;
        top: 0;
        left: 0;
        z-index: 100;
    }
    .navbar-content {
        max-width: 1400px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 32px;
    }
    .navbar-title {
        font-size: 1.5rem;
        font-weight: 700;
        letter-spacing: 1px;
    }
    .menu-cart-layout {
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: flex-start;
        gap: 40px;
        max-width: 1400px;
        margin: 0 auto;
    }
    .menu-list-section {
        flex: 2;
    }
    .cart-section {
        flex: 1;
        background: #fafbfc;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        padding: 24px 20px;
        min-width: 320px;
        max-width: 380px;
        margin-top: 24px;
        height: fit-content;
        position: sticky;
        top: 90px;
        z-index: 10;
    }
    .cart-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid #eee;
    }
    .cart-item:last-child {
        border-bottom: none;
    }
    .cart-remove-btn {
        background: #ff4d4f;
        color: #fff;
        border: none;
        border-radius: 50%;
        width: 28px;
        height: 28px;
        font-size: 1.2rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
<x-navbar />
<div class="menu-cart-layout">
    <div class="menu-list-section">
        <h1 style="text-align:center; margin-bottom:32px;">Menu List (Demo)</h1>
        <div id="menu-list" style="display: flex; flex-wrap: wrap; justify-content: center; gap: 20px; max-width: 1280px; margin: 0 auto;">
            <div style="flex: 1 1 320px; max-width: 340px; min-width: 220px; cursor:pointer;" onclick="addToCart(0)">
                <x-menu-item 
                    img="https://via.placeholder.com/120x120?text=Coffee"
                    name="Kopi Susu Gula Aren"
                    desc="Kopi susu segar dengan gula aren asli."
                    category="Minuman"
                    :price="18000"
                />
            </div>
            <div style="flex: 1 1 320px; max-width: 340px; min-width: 220px; cursor:pointer;" onclick="addToCart(1)">
                <x-menu-item 
                    img="https://via.placeholder.com/120x120?text=Nasi"
                    name="Nasi Goreng Spesial"
                    desc="Nasi goreng dengan telur, ayam, dan kerupuk."
                    category="Makanan"
                    :price="25000"
                />
            </div>
            <div style="flex: 1 1 320px; max-width: 340px; min-width: 220px; cursor:pointer;" onclick="addToCart(2)">
                <x-menu-item 
                    img="https://via.placeholder.com/120x120?text=Snack"
                    name="Pisang Goreng"
                    desc="Pisang goreng hangat dengan taburan keju."
                    category="Snack"
                    :price="12000"
                />
            </div>
            <div style="flex: 1 1 320px; max-width: 340px; min-width: 220px; cursor:pointer;" onclick="addToCart(3)">
                <x-menu-item 
                    img="https://via.placeholder.com/120x120?text=Tea"
                    name="Teh Tarik"
                    desc="Teh tarik manis dan creamy, khas Malaysia."
                    category="Minuman"
                    :price="15000"
                />
            </div>
            <div style="flex: 1 1 320px; max-width: 340px; min-width: 220px; cursor:pointer;" onclick="addToCart(4)">
                <x-menu-item 
                    img="https://via.placeholder.com/120x120?text=Soup"
                    name="Soto Ayam"
                    desc="Soto ayam hangat dengan suwiran ayam dan telur."
                    category="Makanan"
                    :price="22000"
                />
            </div>
        </div>
    </div>
    <div class="cart-section">
        <h2 style="text-align:center; margin-bottom:18px;">Cart</h2>
        <div id="cart-items"></div>
        <div id="cart-total" style="margin-top:18px; font-weight:600; text-align:right;"></div>
    </div>
</div>
<script>
    // Dummy menu data
    const menuData = [
        { id: 0, name: 'Kopi Susu Gula Aren', price: 18000 },
        { id: 1, name: 'Nasi Goreng Spesial', price: 25000 },
        { id: 2, name: 'Pisang Goreng', price: 12000 },
        { id: 3, name: 'Teh Tarik', price: 15000 },
        { id: 4, name: 'Soto Ayam', price: 22000 },
    ];
    let cart = [];
    function addToCart(idx) {
        const item = menuData[idx];
        const found = cart.find(i => i.id === item.id);
        if (found) {
            found.qty++;
        } else {
            cart.push({ ...item, qty: 1 });
        }
        renderCart();
    }
    function removeFromCart(idx) {
        if (cart[idx].qty > 1) {
            cart[idx].qty--;
        } else {
            cart.splice(idx, 1);
        }
        renderCart();
    }
    function renderCart() {
        const cartItems = document.getElementById('cart-items');
        cartItems.innerHTML = '';
        let total = 0;
        cart.forEach((item, idx) => {
            total += item.price * item.qty;
            cartItems.innerHTML += `
                <div class="cart-item">
                    <span>${item.name} <span style='color:#888;'>x${item.qty}</span></span>
                    <button class="cart-remove-btn" onclick="removeFromCart(${idx})">-</button>
                </div>
            `;
        });
        document.getElementById('cart-total').innerHTML = total > 0 ? `Total: Rp${total.toLocaleString('id-ID')}` : '';
    }
</script>
@endsection