<div class="menu-item-card">
    <div class="menu-item-image-wrapper">
        <img src="{{ $img ? '/assets/image/' . $img : 'https://placehold.co/600x400' }}" alt="{{ $name }}" class="menu-item-image">
    </div>
    <div class="menu-item-content">
        <h2 class="menu-item-title">{{ $name }}</h2>
        <p class="menu-item-desc">{{ $desc }}</p>
        <div class="menu-item-category-wrapper">
            <span class="menu-item-category">{{ $category ?? '-' }}</span>
        </div>
        <div class="menu-item-price-wrapper">
            <span class="menu-item-price">Rp{{ number_format($price, 0, ',', '.') }}</span>
        </div>
    </div>
</div>
