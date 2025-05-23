<div class="menu-item-card" style="max-width: 340px; background: #fff; border-radius: 16px; box-shadow: 0 4px 16px rgba(0,0,0,0.08); overflow: hidden; margin-bottom: 12px; display: flex; flex-direction: column; align-items: center;">
    <div style="width: 100%; display: flex; justify-content: center; background: #f3f3f3;">
        <img src="{{ 'https://placehold.co/600x400' }}" alt="{{ $name }}" style="width: 100%; max-width: 180px; height: 120px; object-fit: cover; margin-top: 16px; border-radius: 10px;">
    </div>
    <div style="padding: 14px 16px; width: 100%; display: flex; flex-direction: column; align-items: center;">
        <h2 style="margin: 0 0 6px 0; font-size: 1.1rem; font-weight: 700; color: #222; text-align: center;">{{ $name }}</h2>
        <p style="margin: 0 0 6px 0; color: #666; font-size: 0.95rem; text-align: center;">{{ $desc }}</p>
        <div style="margin-bottom: 6px;">
            <span style="display: inline-block; background: #f0f0f0; color: #888; font-size: 0.9rem; border-radius: 6px; padding: 2px 10px;">{{ $category ?? '-' }}</span>
        </div>
        <div style="margin-top: auto;">
            <span style="font-size: 1rem; font-weight: 600; color: #1a8917;">Rp{{ number_format($price, 0, ',', '.') }}</span>
        </div>
    </div>
</div>
