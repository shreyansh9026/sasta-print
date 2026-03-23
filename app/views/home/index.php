<section class="hero animate-fade-up">
    <div class="container" style="position: relative; z-index: 2;">
        <h1>Bring Your <span class="highlight">Ideas to Life</span></h1>
        <p>Premium quality printing for business cards, banners, and promotional materials. Fast, reliable, and brilliantly simple.</p>
        <div style="display:flex; gap:1rem; justify-content:center; margin-top:2rem;">
            <a href="<?= BASE_URL ?>/products" class="btn btn-primary" style="font-size: 1.125rem;">Shop Collection <i class='bx bx-right-arrow-alt'></i></a>
            <a href="#features" class="btn btn-outline" style="font-size: 1.125rem;">Learn More</a>
        </div>
    </div>
</section>

<section class="products-section container animate-fade-up delay-100">
    <div style="text-align:center; margin-bottom: 3.5rem;">
        <span class="badge badge-primary" style="margin-bottom:1rem;"><i class='bx bxs-star'></i> Trending Now</span>
        <h2 class="section-title">Popular Products</h2>
        <p class="section-subtitle">Discover our highest-rated templates and custom print solutions.</p>
    </div>
    
    <div class="grid">
        <?php foreach($products as $p): ?>
        <div class="card">
            <?php if(!empty($p['image_url'])): ?>
            <div class="card-image-wrapper">
                <img src="<?= htmlspecialchars($p['image_url']) ?>" alt="<?= htmlspecialchars($p['name']) ?>">
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
                    <a href="<?= BASE_URL ?>/product/<?= $p['slug'] ?>" class="btn btn-primary" style="padding:0.5rem 1rem;">Order <i class='bx bx-cart-add'></i></a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <div style="text-align:center; margin-top:3rem;">
        <a href="<?= BASE_URL ?>/products" class="btn btn-outline">View All Products <i class='bx bx-right-arrow-alt'></i></a>
    </div>
</section>

<section id="features" style="background: var(--surface); padding: 6rem 0; margin-top:4rem; border-top:1px solid var(--border-color);" class="animate-fade-up delay-200">
    <div class="container" style="text-align:center;">
        <span class="badge badge-success" style="margin-bottom:1rem;"><i class='bx bx-check-shield'></i> Satisfaction Guaranteed</span>
        <h2 class="section-title" style="margin-bottom: 3.5rem;">Why Choose <?= APP_NAME ?>?</h2>
        <div class="grid" style="gap:3rem;">
            <div style="padding:2rem; border-radius:var(--radius-lg); background:var(--bg-color); transition:var(--transition-spring);">
                <div style="width:64px; height:64px; background:var(--gradient-primary); border-radius:var(--radius-full); display:flex; align-items:center; justify-content:center; margin:0 auto 1.5rem; color:white; font-size:2rem; box-shadow:var(--shadow-glow);">
                    <i class='bx bx-rocket'></i>
                </div>
                <h3 style="margin-bottom:1rem; font-family:'Outfit',sans-serif; color:var(--text-primary);">Fast Delivery</h3>
                <p style="color:var(--text-secondary);">Get your customized prints safely delivered to your doorstep in record time.</p>
            </div>
            <div style="padding:2rem; border-radius:var(--radius-lg); background:var(--bg-color); transition:var(--transition-spring);">
                <div style="width:64px; height:64px; background:var(--gradient-glow); border-radius:var(--radius-full); display:flex; align-items:center; justify-content:center; margin:0 auto 1.5rem; color:white; font-size:2rem;">
                    <i class='bx bx-diamond'></i>
                </div>
                <h3 style="margin-bottom:1rem; font-family:'Outfit',sans-serif; color:var(--text-primary);">Premium Quality</h3>
                <p style="color:var(--text-secondary);">We exclusively use industry-leading materials for the best, most durable results.</p>
            </div>
            <div style="padding:2rem; border-radius:var(--radius-lg); background:var(--bg-color); transition:var(--transition-spring);">
                <div style="width:64px; height:64px; background:linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius:var(--radius-full); display:flex; align-items:center; justify-content:center; margin:0 auto 1.5rem; color:white; font-size:2rem;">
                    <i class='bx bx-palette'></i>
                </div>
                <h3 style="margin-bottom:1rem; font-family:'Outfit',sans-serif; color:var(--text-primary);">Interactive Design</h3>
                <p style="color:var(--text-secondary);">Customize your templates instantly using our powerful browser design tools.</p>
            </div>
        </div>
    </div>
</section>
