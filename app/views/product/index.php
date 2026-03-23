<div class="container animate-fade-up" style="padding: 4rem 1rem;">
    <div style="text-align:center; margin-bottom: 3.5rem;">
        <span class="badge badge-primary" style="margin-bottom:1rem;"><i class='bx bx-category'></i> Catalog</span>
        <h1 class="section-title">All Products</h1>
        <p class="section-subtitle">Explore our full range of premium printing services, from business cards to high-quality banners.</p>
    </div>
    
    <div class="grid">
        <?php foreach($products as $p): ?>
        <div class="card">
            <?php if(!empty($p['image_url'])): ?>
            <div class="card-image-wrapper">
                <img src="<?= htmlspecialchars($p['image_url']) ?>" alt="<?= htmlspecialchars($p['name']) ?>" loading="lazy">
            </div>
            <?php else: ?>
            <div class="card-image-wrapper" style="background:var(--gradient-primary); display:flex; align-items:center; justify-content:center;">
                <i class='bx bxs-image' style="font-size:4rem; color:rgba(255,255,255,0.5);"></i>
            </div>
            <?php endif; ?>
            <div class="card-content">
                <div style="font-size:0.875rem; color:var(--text-tertiary); margin-bottom:0.5rem; text-transform:uppercase; letter-spacing:0.05em; font-weight:600;"><i class='bx bx-category'></i> <?= htmlspecialchars($p['category_name']) ?></div>
                <h3 class="card-title"><?= htmlspecialchars($p['name']) ?></h3>
                <p class="card-desc">Fully customizable top-tier <?= strtolower(htmlspecialchars($p['category_name'])) ?> designed for your brand.</p>
                <div class="card-footer">
                    <div class="card-price"><span style="font-size:0.875rem; color:var(--text-secondary); font-weight:400;">from</span> $<?= number_format($p['base_price'], 2) ?></div>
                    <a href="<?= BASE_URL ?>/product/<?= $p['slug'] ?>" class="btn btn-primary" style="padding:0.5rem 1rem;">Configure <i class='bx bx-slider-alt'></i></a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    
    <?php if(empty($products)): ?>
    <div style="text-align:center; padding: 4rem 0; color:var(--text-tertiary);">
        <i class='bx bx-search' style="font-size:4rem; margin-bottom:1rem; opacity:0.5;"></i>
        <h3 style="font-family:'Outfit',sans-serif; color:var(--text-primary);">No products found</h3>
        <p>Check back later for new additions to our catalog.</p>
    </div>
    <?php endif; ?>
</div>
