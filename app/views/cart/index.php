<div class="container animate-fade-up" style="padding: 4rem 1rem; max-width: 1000px;">
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:2.5rem; flex-wrap:wrap; gap:1rem;">
        <div style="display:flex; align-items:center; gap:0.75rem;">
            <div style="background:var(--primary); color:white; width:48px; height:48px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.5rem;">
                <i class='bx bx-shopping-bag'></i>
            </div>
            <div>
                <h2 style="font-family:'Outfit',sans-serif; font-size:1.75rem; margin:0; line-height:1;">Shopping Cart</h2>
                <p style="color:var(--text-secondary);margin-top:0.25rem;"><?= count($cart) ?> items in your bag</p>
            </div>
        </div>
        <a href="<?= BASE_URL ?>/products" class="btn btn-outline" style="border-radius:20px; font-size:0.875rem;">
            <i class='bx bx-plus'></i> Add More Items
        </a>
    </div>

    <?php if(empty($cart)): ?>
        <div style="background:var(--surface); padding:5rem 2rem; border-radius:16px; text-align:center; box-shadow:var(--shadow-md); border:1px solid var(--border-color);">
            <div style="width:100px; height:100px; background:rgba(79, 70, 229, 0.08); color:var(--primary); font-size:4rem; display:flex; align-items:center; justify-content:center; border-radius:50%; margin:0 auto 2rem;">
                <i class='bx bx-cart-alt'></i>
            </div>
            <h3 style="font-family:'Outfit',sans-serif; font-size:1.75rem; margin-bottom:0.75rem;">Your cart is empty</h3>
            <p style="color:var(--text-secondary); margin-bottom:2.5rem; max-width:400px; margin-inline:auto;">Ready to bring your designs to life? Explore our range of high-quality print products today.</p>
            <a href="<?= BASE_URL ?>/products" class="btn btn-primary" style="font-size:1.1rem; padding:1rem 2.5rem; border-radius:12px;">Start Shopping <i class='bx bx-right-arrow-alt'></i></a>
        </div>
    <?php else: ?>
        <div style="display:grid; grid-template-columns: 2fr 1fr; gap:2.5rem; align-items:start;">
            
            <!-- Items Column -->
            <div style="display:flex; flex-direction:column; gap:1.25rem;">
                <?php 
                $subtotal = 0; 
                foreach($cart as $index => $item): 
                    $itemTotal = $item['price'] * $item['quantity'];
                    $subtotal += $itemTotal;
                ?>
                    <div id="cart-item-<?= $index ?>" class="card" style="padding:1.25rem; position:relative; overflow:visible; flex-direction:row; gap:1.5rem; align-items:center;">
                        <div style="width:110px; height:110px; border-radius:12px; overflow:hidden; background:var(--bg-color); border:1px solid var(--border-color); flex-shrink:0;">
                            <img src="<?= htmlspecialchars($item['image'] ?: BASE_URL.'/assets/img/placeholder.jpg') ?>" style="width:100%; height:100%; object-fit:cover;">
                        </div>
                        
                        <div style="flex:1;">
                            <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                                <h4 style="font-size:1.2rem; font-weight:700; color:var(--text-primary); margin:0;"><?= htmlspecialchars($item['name']) ?></h4>
                                <button onclick="removeFromCart(<?= $index ?>)" style="background:none; border:none; color:var(--text-tertiary); cursor:pointer; font-size:1.25rem; transition:color 0.2s;" onmouseover="this.style.color='var(--danger)'" onmouseout="this.style.color='var(--text-tertiary)'">
                                    <i class='bx bx-trash'></i>
                                </button>
                            </div>
                            
                            <div style="display:flex; flex-wrap:wrap; gap:1rem; margin-top:0.75rem; font-size:0.875rem; color:var(--text-secondary);">
                                <?php if($item['size']): ?>
                                <span style="background:var(--bg-color); padding:0.25rem 0.6rem; border-radius:6px; border:1px solid var(--border-color);">Size: <strong><?= htmlspecialchars($item['size']) ?></strong></span>
                                <?php endif; ?>
                                <?php if($item['material']): ?>
                                <span style="background:var(--bg-color); padding:0.25rem 0.6rem; border-radius:6px; border:1px solid var(--border-color);">Material: <strong><?= htmlspecialchars($item['material']) ?></strong></span>
                                <?php endif; ?>
                            </div>
                            
                            <div style="display:flex; justify-content:space-between; align-items:center; margin-top:1.25rem;">
                                <div style="display:flex; align-items:center; gap:0.75rem; font-weight:600; font-size:0.95rem;">
                                    <span style="color:var(--text-tertiary);">Qty:</span>
                                    <span style="background:var(--primary); color:white; padding:0.15rem 0.75rem; border-radius:6px;"><?= $item['quantity'] ?></span>
                                </div>
                                <div style="text-align:right;">
                                    <div style="font-size:1.25rem; font-weight:800; color:var(--primary);">₹<?= number_format($itemTotal, 2) ?></div>
                                    <div style="font-size:0.75rem; color:var(--text-tertiary);">₹<?= number_format($item['price'], 2) ?> / unit</div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Summary Sidebar -->
            <div style="position:sticky; top:120px;">
                <div class="card" style="padding:2rem;">
                    <h3 style="margin-bottom:1.5rem; font-family:'Outfit',sans-serif; font-size:1.25rem;">Order Summary</h3>
                    
                    <div style="display:flex; flex-direction:column; gap:1rem; margin-bottom:1.5rem; font-size:0.95rem;">
                        <div style="display:flex; justify-content:space-between; color:var(--text-secondary);">
                            <span>Subtotal</span>
                            <span style="font-weight:600; color:var(--text-primary);">₹<?= number_format($subtotal, 2) ?></span>
                        </div>
                        <div style="display:flex; justify-content:space-between; color:var(--text-secondary);">
                            <span>Shipping</span>
                            <span style="font-weight:600; color:var(--success);">Calculated at next step</span>
                        </div>
                        <div style="display:flex; justify-content:space-between; padding-top:1rem; border-top:1px solid var(--border-color); margin-top:0.5rem;">
                            <span style="font-weight:700; font-size:1.1rem;">Total Amount</span>
                            <span style="font-weight:800; font-size:1.5rem; color:var(--primary);">₹<?= number_format($subtotal, 2) ?></span>
                        </div>
                    </div>
                </div>

                <div style="margin-top:1.5rem; display:flex; flex-direction:column; gap:1rem;">
                    <a href="<?= BASE_URL ?>/checkout" class="btn btn-primary" style="width:100%; border-radius:12px; padding:1.15rem; font-size:1.1rem; box-shadow:0 10px 15px -3px rgba(30, 58, 138, 0.25);">
                        Secure Checkout <i class='bx bx-check-shield' style="font-size:1.25rem;"></i>
                    </a>
                    <div style="display:flex; align-items:center; justify-content:center; gap:0.5rem; color:var(--text-tertiary); font-size:0.8rem;">
                        <i class='bx bx-lock-alt' style="font-size:1rem;"></i> 256-bit SSL Secure Payment
                    </div>
                </div>
            </div>

        </div>
    <?php endif; ?>
</div>

<script>
async function removeFromCart(index) {
    try {
        const resp = await fetch(BASE_URL + '/api/v1/cart/remove', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ index: index })
        });
        const data = await resp.json();
        if (data.success) {
            const item = document.getElementById(`cart-item-${index}`);
            item.style.transform = 'scale(0.95)';
            item.style.opacity = '0';
            setTimeout(() => location.reload(), 300);
        }
    } catch (e) {
        alert('Could not remove item. Please try again.');
    }
}
</script>
