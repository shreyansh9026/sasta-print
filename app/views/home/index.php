<!-- Hero Section -->
<section class="hero animate-fade-up">
    <div class="container text-center">
        <div style="max-width:850px; margin:0 auto;">
            <span style="display:inline-block; background:rgba(255,165,0,0.15); color:var(--accent); padding:0.5rem 1.25rem; border-radius:30px; font-weight:700; font-size:0.875rem; text-transform:uppercase; letter-spacing:0.1em; margin-bottom:1.5rem; border:1px solid rgba(255,165,0,0.3);">Premium Printing, Lower Prices</span>
            <h1 style="font-size:clamp(2.5rem, 6vw, 4.5rem); line-height:1.1; margin-bottom:1.5rem;">Your Vision, <span class="highlight">Perfectly</span> Printed.</h1>
            <p style="font-size:1.25rem; opacity:0.9; margin-bottom:3rem; max-width:650px; margin-inline:auto;">Professional-grade business cards, banners, and stickers customized by you and delivered fast to your doorstep.</p>
            
            <div style="display:flex; gap:1.25rem; justify-content:center; flex-wrap:wrap;">
                <a href="<?= BASE_URL ?>/products" class="btn btn-primary" style="padding:1.15rem 2.5rem; font-size:1.1rem; border-radius:12px; box-shadow:0 10px 15px -3px rgba(30,58,138,0.3);">Browse Our Catalog <i class='bx bx-right-arrow-alt'></i></a>
                <a href="#features" class="btn btn-outline" style="padding:1.15rem 2.5rem; font-size:1.1rem; border-radius:12px; color:white !important; border-color:rgba(255,255,255,0.3);">Why Choose Us?</a>
            </div>
            
            <div style="margin-top:4rem; display:flex; justify-content:center; gap:3rem; flex-wrap:wrap; opacity:0.8;">
                <div style="display:flex; align-items:center; gap:0.5rem; font-weight:600;"><i class='bx bx-check-shield' style="font-size:1.5rem; color:var(--accent);"></i> Quality Guaranteed</div>
                <div style="display:flex; align-items:center; gap:0.5rem; font-weight:600;"><i class='bx bx-time-five' style="font-size:1.5rem; color:var(--accent);"></i> 24h Turnaround</div>
                <div style="display:flex; align-items:center; gap:0.5rem; font-weight:600;"><i class='bx bx-trending-up' style="font-size:1.5rem; color:var(--accent);"></i> 10k+ Happy Clients</div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Categories -->
<section style="padding: 5rem 0; background:var(--bg-color);">
    <div class="container">
        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap:2rem; margin-top:-8rem; position:relative; z-index:10;">
            <div class="glass-card animate-fade-up delay-100" style="padding:2.5rem; text-align:center;">
                <div style="width:70px;height:70px;background:linear-gradient(135deg,#3b82f6,#2563eb);border-radius:20px;display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;color:white;font-size:2rem;box-shadow:0 10px 15px -3px rgba(37,99,235,0.25);">
                    <i class='bx bx-id-card'></i>
                </div>
                <h3>Business Cards</h3>
                <p style="color:var(--text-secondary); margin:1rem 0 1.5rem;">Matte, Glossy, or Silk. Make a lasting first impression.</p>
                <a href="<?= BASE_URL ?>/products?category=business-cards" style="font-weight:700; color:var(--primary); text-transform:uppercase; font-size:0.85rem; letter-spacing:0.1em;">Explore Now <i class='bx bx-right-arrow-alt'></i></a>
            </div>
            
            <div class="glass-card animate-fade-up delay-200" style="padding:2.5rem; text-align:center;">
                <div style="width:70px;height:70px;background:linear-gradient(135deg,#10b981,#059669);border-radius:20px;display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;color:white;font-size:2rem;box-shadow:0 10px 15px -3px rgba(16,185,129,0.25);">
                    <i class='bx bx-landscape'></i>
                </div>
                <h3>Durable Banners</h3>
                <p style="color:var(--text-secondary); margin:1rem 0 1.5rem;">Outdoor banners that withstand the elements. XL Sizes.</p>
                <a href="<?= BASE_URL ?>/products?category=banners" style="font-weight:700; color:var(--primary); text-transform:uppercase; font-size:0.85rem; letter-spacing:0.1em;">Explore Now <i class='bx bx-right-arrow-alt'></i></a>
            </div>
            
            <div class="glass-card animate-fade-up delay-300" style="padding:2.5rem; text-align:center;">
                <div style="width:70px;height:70px;background:linear-gradient(135deg,#f59e0b,#d97706);border-radius:20px;display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;color:white;font-size:2rem;box-shadow:0 10px 15px -3px rgba(245,158,11,0.25);">
                    <i class='bx bx-map-pin'></i>
                </div>
                <h3>Yard Signs</h3>
                <p style="color:var(--text-secondary); margin:1rem 0 1.5rem;">Rigid, high-vis signs for campaigns and businesses.</p>
                <a href="<?= BASE_URL ?>/products?category=yard-signs" style="font-weight:700; color:var(--primary); text-transform:uppercase; font-size:0.85rem; letter-spacing:0.1em;">Explore Now <i class='bx bx-right-arrow-alt'></i></a>
            </div>
        </div>
    </div>
</section>

<!-- Our Products -->
<section style="padding:6rem 0;" id="shop">
    <div class="container text-center">
        <h2 class="section-title">Popular Print <span style="background:var(--gradient-primary); -webkit-background-clip:text; -webkit-text-fill-color:transparent;">Solutions</span></h2>
        <p class="section-subtitle">Realize your vision with our most loved print products.</p>
        
        <div class="grid">
            <?php foreach($products as $product): ?>
                <div class="card">
                    <div class="card-image-wrapper">
                        <img src="<?= htmlspecialchars($product['image_url'] ?: BASE_URL . '/assets/img/placeholder.jpg') ?>" alt="<?= htmlspecialchars($product['name']) ?>" loading="lazy">
                    </div>
                    <div class="card-content">
                        <div class="badge badge-primary" style="align-self:flex-start; margin-bottom:0.75rem;"><?= htmlspecialchars($product['category_name']) ?></div>
                        <h4 class="card-title"><?= htmlspecialchars($product['name']) ?></h4>
                        <p class="card-desc"><?= htmlspecialchars($product['description']) ?></p>
                        <div class="card-footer">
                            <span class="card-price">₹<?= number_format($product['base_price'], 2) ?></span>
                            <a href="<?= BASE_URL ?>/product/<?= htmlspecialchars($product['slug']) ?>" class="btn btn-primary" style="padding:0.5rem 1rem; border-radius:8px;">Design Now</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <a href="<?= BASE_URL ?>/products" class="btn btn-outline" style="border-radius:12px; padding:1rem 3rem;">View All Products</a>
    </div>
</section>

<!-- Design Features -->
<section id="features" style="padding: 10rem 0; background: #0f172a; color: white; position: relative; overflow: hidden;">
    <div style="position:absolute; top:-20%; right:-10%; width:600px; height:600px; background:radial-gradient(circle, rgba(30,58,138,0.4) 0%, transparent 70%); border-radius:50%; pointer-events:none;"></div>
    <div style="position:absolute; bottom:-10%; left:-5%; width:400px; height:400px; background:radial-gradient(circle, rgba(79,70,229,0.3) 0%, transparent 70%); border-radius:50%; pointer-events:none;"></div>

    <div class="container" style="position:relative; z-index:10;">
        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:6rem; align-items:center;">
            <div>
                <h2 style="font-size:3.5rem; line-height:1.1; margin-bottom:2rem; color:white;">Advanced Online <br><span style="color:var(--accent);">Design Studio</span></h2>
                <div style="display:flex; flex-direction:column; gap:2.5rem; margin-top:3rem;">
                    <div style="display:flex; gap:1.5rem;">
                        <div style="width:60px; height:60px; background:rgba(255,255,255,0.05); border-radius:16px; border:1px solid rgba(255,255,255,0.1); display:flex; align-items:center; justify-content:center; font-size:1.75rem; color:var(--accent); flex-shrink:0;">
                            <i class='bx bx-layer'></i>
                        </div>
                        <div>
                            <h4 style="color:white; font-size:1.4rem; margin-bottom:0.5rem;">Layered Customization</h4>
                            <p style="color:#94a3b8; line-height:1.6;">Upload logos, adding text, and arrange elements via our rich browser-based designer tool powered by Canvas.</p>
                        </div>
                    </div>
                    <div style="display:flex; gap:1.5rem;">
                        <div style="width:60px; height:60px; background:rgba(255,255,255,0.05); border-radius:16px; border:1px solid rgba(255,255,255,0.1); display:flex; align-items:center; justify-content:center; font-size:1.75rem; color:var(--accent); flex-shrink:0;">
                            <i class='bx bx-slider-alt'></i>
                        </div>
                        <div>
                            <h4 style="color:white; font-size:1.4rem; margin-bottom:0.5rem;">Real-Time Price Sync</h4>
                            <p style="color:#94a3b8; line-height:1.6;">Our advanced pricing engine calculates exact costs instantly as you switch materials, sizes, and finishes.</p>
                        </div>
                    </div>
                    <div style="display:flex; gap:1.5rem;">
                        <div style="width:60px; height:60px; background:rgba(255,255,255,0.05); border-radius:16px; border:1px solid rgba(255,255,255,0.1); display:flex; align-items:center; justify-content:center; font-size:1.75rem; color:var(--accent); flex-shrink:0;">
                            <i class='bx bx-cloud-upload'></i>
                        </div>
                        <div>
                            <h4 style="color:white; font-size:1.4rem; margin-bottom:0.5rem;">Instant Proofing</h4>
                            <p style="color:#94a3b8; line-height:1.6;">See exactly what you'll get before you order. High-resolution proofs are generated in the cloud.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div style="position:relative; transform: perspective(1000px) rotateY(-15deg); border-radius:20px; box-shadow:0 50px 100px -20px rgba(0,0,0,0.5); border:1px solid rgba(255,255,255,0.1); overflow:hidden; background:#1e293b;">
                <img src="https://images.unsplash.com/photo-1579546929518-9e396f3cc809?auto=format&fit=crop&q=80&w=1200" alt="Design Tool" style="width:100%; height:auto; opacity:0.7;">
                <div style="position:absolute; inset:0; display:flex; align-items:center; justify-content:center; background:linear-gradient(transparent, #0f172a);">
                    <div style="text-align:center;">
                        <div style="width:80px;height:80px;background:var(--primary);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:3rem;margin:0 auto 1.5rem;box-shadow:0 0 20px rgba(30,58,138,0.5);">
                            <i class='bx bx-play'></i>
                        </div>
                        <h4 style="color:white; font-size:1.25rem;">Watch our Designer in action</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
