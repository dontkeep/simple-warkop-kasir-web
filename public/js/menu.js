// No dummy menuData, expect menu items to be rendered by Blade or fetched from backend
let cart = [];
let editMode = false;
let currentCategory = 'all';

function addToCart(idx) {
    const menuCards = document.querySelectorAll('.menu-item-card');
    const card = menuCards[idx];
    const name = card.querySelector('.menu-item-title').textContent;
    const price = parseInt(card.querySelector('.menu-item-price').textContent.replace(/[^\d]/g, ''));
    const found = cart.find(i => i.name === name);
    if (found) {
        found.qty++;
    } else {
        cart.push({ name, price, qty: 1 });
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
function filterCategory(category) {
    currentCategory = category;
    document.querySelectorAll('.category-btn').forEach(btn => btn.classList.remove('active'));
    document.querySelectorAll('.category-btn').forEach(btn => {
        if ((category === 'all' && btn.textContent === 'Semua') || btn.textContent === category) {
            btn.classList.add('active');
        }
    });
    const menuItems = document.querySelectorAll('.menu-list-card-wrapper');
    menuItems.forEach((div) => {
        const cat = div.querySelector('.menu-item-category')?.textContent || '';
        if(category === 'all' || cat === category) {
            div.style.display = '';
        } else {
            div.style.display = 'none';
        }
    });
}

function setEditModeUI() {
    // Show/hide edit/delete buttons on menu items
    document.querySelectorAll('.menu-item-card').forEach((card, idx) => {
        let editBtn = card.querySelector('.edit-btn');
        let deleteBtn = card.querySelector('.delete-btn');
        if (editMode) {
            if (!editBtn) {
                editBtn = document.createElement('button');
                editBtn.className = 'edit-btn';
                editBtn.style.position = 'absolute';
                editBtn.style.left = '8px';
                editBtn.style.top = '8px';
                editBtn.style.zIndex = '2';
                editBtn.innerHTML = '✎';
                editBtn.onclick = function(e) {
                    e.stopPropagation();
                    handleEdit(idx);
                };
                card.appendChild(editBtn);
            }
            if (!deleteBtn) {
                deleteBtn = document.createElement('button');
                deleteBtn.className = 'delete-btn';
                deleteBtn.style.position = 'absolute';
                deleteBtn.style.right = '8px';
                deleteBtn.style.top = '8px';
                deleteBtn.style.zIndex = '2';
                deleteBtn.innerHTML = '&times;';
                deleteBtn.onclick = function(e) {
                    e.stopPropagation();
                    handleDelete(idx);
                };
                card.appendChild(deleteBtn);
            }
            card.onclick = null;
        } else {
            if (editBtn) editBtn.remove();
            if (deleteBtn) deleteBtn.remove();
            card.onclick = function() { addToCart(idx); };
        }
    });
    // Show/hide add menu button
    const addBtn = document.getElementById('add-menu-btn');
    if (addBtn) addBtn.style.display = editMode ? '' : 'none';
    // Change edit mode button text
    const editBtn = document.getElementById('edit-mode-btn');
    if (editBtn) editBtn.textContent = editMode ? 'Apply' : 'Edit Menu';
}

function setupEditModeButton() {
    let editBtn = document.getElementById('edit-mode-btn');
    if (!editBtn) {
        editBtn = document.createElement('button');
        editBtn.id = 'edit-mode-btn';
        editBtn.className = 'edit-mode-btn';
        editBtn.textContent = 'Edit Menu';
        const catSection = document.querySelector('.category-section');
        catSection.insertBefore(editBtn, catSection.firstChild);
    }
    editBtn.onclick = function() {
        editMode = !editMode;
        setEditModeUI();
    };
}

function setupAddMenuButton() {
    const addBtn = document.getElementById('add-menu-btn');
    if (addBtn) {
        addBtn.onclick = function(e) {
            e.preventDefault();
            openMenuModal('add');
        };
    }
}

function openMenuModal(mode, idx = null) {
    const modal = document.getElementById('menu-modal');
    const title = document.getElementById('modal-title');
    const form = document.getElementById('menu-form');
    if (mode === 'add') {
        form.reset();
        document.getElementById('modal-menu-id').value = '';
        title.textContent = 'Add Menu';
    } else if (mode === 'edit') {
        title.textContent = 'Edit Menu';
        // Populate fields with current menu item
        const card = document.querySelectorAll('.menu-item-card')[idx];
        document.getElementById('modal-menu-id').value = card.getAttribute('data-id') || '';
        // Instead of fetching, get values from the card's DOM
        document.getElementById('modal-menu-name').value = card.querySelector('.menu-item-title').textContent;
        document.getElementById('modal-menu-price').value = card.querySelector('.menu-item-price').textContent.replace(/[^\d]/g, '');
        document.getElementById('modal-menu-desc').value = card.querySelector('.menu-item-desc').textContent;
        document.getElementById('modal-menu-category').value = card.querySelector('.menu-item-category').textContent;
        document.getElementById('modal-menu-img').value = '';
    }
    modal.style.display = 'flex';
}
function closeMenuModal() {
    document.getElementById('menu-modal').style.display = 'none';
}

// Edit Menu button opens modal for add (optional: could be for bulk edit)
const editBtn = document.getElementById('edit-mode-btn');
if (editBtn) {
    editBtn.onclick = function(e) {
        e.preventDefault();
        // Toggle edit mode as before, or open modal for add
        // editMode = !editMode; setEditModeUI();
        openMenuModal('add');
    };
}

// Edit icon on each card opens modal for edit
function handleEdit(idx) {
    openMenuModal('edit', idx);
}

// Handle delete menu item
async function handleDelete(idx) {
    const menuCards = document.querySelectorAll('.menu-item-card');
    const card = menuCards[idx];
    const name = card.querySelector('.menu-item-title').textContent;
    const img = card.querySelector('.menu-item-image').getAttribute('src');
    // Extract filename from /assets/image/filename.jpg
    let imgFilename = '';
    if (img && img.startsWith('/assets/image/')) {
        imgFilename = img.split('/assets/image/')[1];
    }
    try {
        const res = await fetch('http://localhost:3000/menu-warkop');
        const menuData = await res.json();
        const item = menuData.find(i => i.name === name);
        if (!item) return alert('Item not found');
        if (!confirm('Delete this menu item?')) return;
        // 1. Delete image from Laravel if exists
        if (imgFilename) {
            await fetch('/menu/delete-image', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ filename: imgFilename })
            });
        }
        // 2. Delete menu item from json-server
        const delRes = await fetch(`http://localhost:3000/menu-warkop/${item.id}`, { method: 'DELETE' });
        if (!delRes.ok) throw new Error('Failed to delete');
        await fetchAndRenderMenu();
    } catch (err) {
        alert('Delete error: ' + err.message);
    }
}

// Modal form submission (Add/Edit Menu)
document.getElementById('menu-form').onsubmit = async function(e) {
    e.preventDefault();
    const id = document.getElementById('modal-menu-id').value;
    console.log('modal-menu-id value:', id); // Debug log
    const name = document.getElementById('modal-menu-name').value;
    const price = document.getElementById('modal-menu-price').value;
    const desc = document.getElementById('modal-menu-desc').value;
    const category = document.getElementById('modal-menu-category').value;
    const rate = 5; // Default rate
    const imgInput = document.getElementById('modal-menu-img');
    let imgFilename = '';
    let method = 'POST';
    let url = 'http://localhost:3000/menu-warkop';
    let isEdit = false;
    if (id !== '') {
        // Edit mode: use the json-server id directly
        isEdit = true;
        url = `http://localhost:3000/menu-warkop/${id}`;
        method = 'PUT';
        // Fetch the current item to get the old image filename if needed
        const res = await fetch(url);
        const item = await res.json();
        imgFilename = item.img || '';
    }
    // 1. If image selected, upload to Laravel to get filename
    if (imgInput.files && imgInput.files[0]) {
        const imgForm = new FormData();
        imgForm.append('img_file', imgInput.files[0]);
        try {
            const uploadRes = await fetch('/menu/upload-image', {
                method: 'POST',
                body: imgForm
            });
            if (!uploadRes.ok) throw new Error('Image upload failed');
            const uploadData = await uploadRes.json();
            imgFilename = uploadData.filename;
        } catch (err) {
            alert('Image upload error: ' + err.message);
            return;
        }
    } else if (!isEdit) {
        imgFilename = '';
    }
    // 2. Post menu data to json-server
    const menuData = {
        name,
        price,
        desc,
        category,
        rate,
        img: imgFilename
    };
    try {
        const response = await fetch(url, {
            method,
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(menuData)
        });
        if (!response.ok) throw new Error(isEdit ? 'Failed to update menu item' : 'Failed to add menu item');
        closeMenuModal();
        await fetchAndRenderMenu();
    } catch (err) {
        alert('Error: ' + err.message);
    }
};

// Fetch and render menu items dynamically from json-server API
async function fetchAndRenderMenu() {
    try {
        const res = await fetch('http://localhost:3000/menu-warkop');
        if (!res.ok) throw new Error('Failed to fetch menu');
        const menuData = await res.json();
        const menuList = document.getElementById('menu-list');
        menuList.innerHTML = '';
        menuData.forEach((item, idx) => {
            menuList.innerHTML += `
            <div class="menu-list-card-wrapper">
                <div class="menu-item-card" data-id="${item.id}" onclick="addToCart(${idx})">
                    <img class="menu-item-image" src="/assets/image/${item.img}" alt="${item.name}">
                    <div class="menu-item-title">${item.name}</div>
                    <div class="menu-item-price">Rp${item.price}</div>
                    <div class="menu-item-desc">${item.desc}</div>
                    <div class="menu-item-category">${item.category}</div>
                </div>
            </div>
            `;
        });
        setEditModeUI();
        filterCategory(currentCategory);
    } catch (err) {
        alert('Error loading menu: ' + err.message);
    }
}

// Remove all server-rendered menu items on page load and always render from JS
window.addEventListener('DOMContentLoaded', () => {
    const menuList = document.getElementById('menu-list');
    if (menuList) menuList.innerHTML = '';
    fetchAndRenderMenu();
});

// Initial setup
setupEditModeButton();
setupAddMenuButton();
setEditModeUI();
filterCategory(currentCategory);
renderCart();

document.addEventListener('DOMContentLoaded', function() {
    const checkoutBtn = document.getElementById('checkout-btn');
    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', function() {
            // Use the actual cart array for checkout
            if (!cart.length) {
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
                    cart: cart
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.snap_token) {
                    window.snap.pay(data.snap_token, {
                        onSuccess: function(result) {
                            alert('Payment success!');
                            cart = [];
                            renderCart();
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
