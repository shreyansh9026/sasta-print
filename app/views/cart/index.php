<div class="container animate-fade-up" style="padding: 4rem 1rem; max-width: 900px;">
    <div style="display:flex; align-items:center; gap:1rem; margin-bottom:2rem;">
        <i class='bx bx-shopping-bag' style="font-size:2.5rem; color:var(--primary);"></i>
        <h2 style="font-family:'Outfit',sans-serif; font-size:2rem; margin:0;">Your Shopping Cart</h2>
    </div>

    <?php if(empty($cart)): ?>
        <div style="background:var(--surface); padding:4rem 2rem; border-radius:var(--radius-lg); text-align:center; box-shadow:var(--shadow-sm); border:1px solid var(--border-color);">
            <div style="width:80px; height:80px; background:rgba(79, 70, 229, 0.1); color:var(--primary); font-size:3rem; display:flex; align-items:center; justify-content:center; border-radius:var(--radius-full); margin:0 auto 1.5rem;">
                <i class='bx bx-cart-alt'></i>
            </div>
            <h3 style="font-family:'Outfit',sans-serif; font-size:1.5rem; margin-bottom:0.5rem;">Your cart is empty</h3>
            <p style="color:var(--text-secondary); margin-bottom:2rem;">Looks like you haven't added any printing products yet.</p>
            <a href="<?= BASE_URL ?>/products" class="btn btn-primary" style="font-size:1.1rem; padding:0.75rem 2rem;">Start Shopping <i class='bx bx-right-arrow-alt'></i></a>
        </div>
    <?php else: ?>
        <div style="background:var(--surface); border-radius:var(--radius-lg); box-shadow:var(--shadow-md); border:1px solid var(--border-color); overflow:hidden;">
            <div style="padding:1.5rem 2rem; border-bottom:1px solid var(--border-color); background:var(--bg-color);">
                <p style="font-weight:600; color:var(--text-secondary); text-transform:uppercase; letter-spacing:0.05em; font-size:0.875rem; margin:0;">Review your items</p>
            </div>
            <div style="padding:2rem;">
            <?php 
            $total = 0; 
            foreach($cart as $index => $item): 
                $itemTotal = $item['price'] * $item['quantity'];
                $total += $itemTotal;
            ?>
                <div style="display:flex; justify-content:space-between; align-items:flex-start; padding-bottom:1.5rem; border-bottom:1px solid var(--border-color); margin-bottom:1.5rem;">
                    <div style="display:flex; gap:1.5rem;">
                        <?php if(!empty($item['image'])): ?>
                            <div style="width:100px; height:100px; border-radius:var(--radius-md); overflow:hidden; border:1px solid var(--border-color); background:#f8fafc;">
                                <img src="<?= htmlspecialchars($item['image']) ?>" alt="" style="width:100%; height:100%; object-fit:cover;">
                            </div>
                        <?php else: ?>
                            <div style="width:100px; height:100px; border-radius:var(--radius-md); background:var(--bg-color); border:1px solid var(--border-color); display:flex; align-items:center; justify-content:center; color:var(--text-tertiary);">
                                <i class='bx bx-image' style="font-size:2rem;"></i>
                            </div>
                        <?php endif; ?>
                        <div>
                            <h4 style="font-family:'Outfit',sans-serif; font-size:1.25rem; font-weight:600; margin-bottom:0.25rem; color:var(--text-primary);"><?= htmlspecialchars($item['name']) ?></h4>
                            <div style="font-size:0.875rem; color:var(--text-secondary); line-height:1.8;">
                                <div style="display:flex; gap:0.5rem; align-items:center;">
                                    <span style="font-weight:500; color:var(--text-primary);">Size:</span> <?= htmlspecialchars($item['size']) ?>
                                </div>
                                <div style="display:flex; gap:0.5rem; align-items:center;">
                                    <span style="font-weight:500; color:var(--text-primary);">Material:</span> <?= htmlspecialchars($item['material']) ?>
                                </div>
                                <div style="display:flex; gap:0.5rem; align-items:center;">
                                    <span style="font-weight:500; color:var(--text-primary);">Qty:</span> <span class="badge badge-primary"><?= $item['quantity'] ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div style="text-align:right;">
                        <div style="font-weight:700; font-family:'Outfit',sans-serif; font-size:1.25rem; color:var(--text-primary);">
                            $<?= number_format($itemTotal, 2) ?>
                        </div>
                        <div style="font-size:0.75rem; color:var(--text-secondary); margin-top:0.25rem;">$<?= number_format($item['price'], 2) ?> each</div>
                    </div>
                </div>
            <?php endforeach; ?>
            
            <div style="display:flex; justify-content:space-between; align-items:center; margin-top:2rem; padding-top:1rem;">
                <a href="<?= BASE_URL ?>/products" style="color:var(--text-secondary); font-weight:500; display:flex; align-items:center; gap:0.25rem; transition:color 0.2s;"><i class='bx bx-left-arrow-alt'></i> Continue Shopping</a>
                <div style="text-align:right;">
                    <p style="font-size:0.875rem; color:var(--text-secondary); margin-bottom:0.25rem; text-transform:uppercase; letter-spacing:0.05em;">Subtotal</p>
                    <h3 style="font-family:'Outfit',sans-serif; font-size:2rem; color:var(--text-primary); margin-bottom:1.5rem;">$<?= number_format($total, 2) ?></h3>
                    <a href="<?= BASE_URL ?>/checkout" class="btn btn-primary" style="font-size:1.1rem; padding:0.875rem 2.5rem; width:100%; justify-content:center;">Proceed to Checkout <i class='bx bx-check-shield'></i></a>
                </div>
            </div>
            </div>
        </div>
    <?php endif; ?>
</div>
