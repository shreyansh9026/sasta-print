<section class="hero animate-fade-up" style="background-image: url('https://images.unsplash.com/photo-1598257006458-087169a1f08d?auto=format&fit=crop&q=80&w=1920');">
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
        <h2 class="section-title">Tackle Your Project with First-Rate Printing</h2>
        <p class="section-subtitle">Custom-Fit Solutions for your business needs. Discover our highest-rated templates and premium print services.</p>
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
        <h2 class="section-title" style="margin-bottom: 3.5rem;">Founded with Excellence in Mind</h2>
        
        <div style="display: flex; flex-wrap: wrap; gap: 4rem; text-align: left; align-items: center;">
            <div style="flex: 1; min-width: 300px;">
                <img src="https://images.unsplash.com/photo-1542744173-8e7e53415bb0?auto=format&fit=crop&q=80&w=1000" alt="Excellence in printing" style="border-radius: var(--radius-lg); box-shadow: var(--shadow-md); width: 100%;">
            </div>
            
            <div style="flex: 1; min-width: 300px;">
                <div style="margin-bottom: 2rem;">
                    <div style="display:flex; align-items:center; gap: 1rem; margin-bottom: 0.5rem;">
                        <div style="width:40px; height:40px; background:var(--primary-light); color:white; border-radius:var(--radius-sm); display:flex; align-items:center; justify-content:center; font-size:1.5rem;"><i class='bx bx-rocket'></i></div>
                        <h3 style="font-family:'Outfit',sans-serif; color:var(--text-primary); margin:0;">Fast Delivery</h3>
                    </div>
                    <p style="color:var(--text-secondary); margin-left: calc(40px + 1rem);">Get your customized prints safely delivered to your doorstep in record time.</p>
                </div>
                
                <div style="margin-bottom: 2rem;">
                    <div style="display:flex; align-items:center; gap: 1rem; margin-bottom: 0.5rem;">
                        <div style="width:40px; height:40px; background:var(--primary); color:white; border-radius:var(--radius-sm); display:flex; align-items:center; justify-content:center; font-size:1.5rem;"><i class='bx bx-diamond'></i></div>
                        <h3 style="font-family:'Outfit',sans-serif; color:var(--text-primary); margin:0;">Premium Quality</h3>
                    </div>
                    <p style="color:var(--text-secondary); margin-left: calc(40px + 1rem);">We exclusively use industry-leading materials for the best, most durable results.</p>
                </div>
                
                <div>
                    <div style="display:flex; align-items:center; gap: 1rem; margin-bottom: 0.5rem;">
                        <div style="width:40px; height:40px; background:var(--accent); color:white; border-radius:var(--radius-sm); display:flex; align-items:center; justify-content:center; font-size:1.5rem;"><i class='bx bx-palette'></i></div>
                        <h3 style="font-family:'Outfit',sans-serif; color:var(--text-primary); margin:0;">Interactive Design</h3>
                    </div>
                    <p style="color:var(--text-secondary); margin-left: calc(40px + 1rem);">Customize your templates instantly using our powerful browser design tools.</p>
                </div>
            </div>
        </div>
    </div>
</section>
