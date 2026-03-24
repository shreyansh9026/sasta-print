<section class="admin-page container">
    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1.5rem;margin-bottom:2rem;">
        <div>
            <a href="<?= BASE_URL ?>/admin/orders" class="btn btn-outline" style="margin-bottom:1.5rem;padding:0.4rem 1.25rem;font-size:0.875rem;"><i class='bx bx-left-arrow-alt'></i> Back to Orders</a>
            <h2 style="margin:0;"><i class='bx bx-package'></i> Order Details #<?= $order['id'] ?></h2>
            <p style="color:var(--text-secondary);margin:0.25rem 0 0;">Received on <?= date('d M Y, h:i A', strtotime($order['created_at'])) ?></p>
        </div>
        
        <div style="display:flex;gap:1rem;align-items:center;">
            <div style="display:flex;flex-direction:column;align-items:flex-end;">
                <label style="font-size:0.8rem;font-weight:600;color:var(--text-tertiary);margin-bottom:0.25rem;text-transform:uppercase;letter-spacing:0.05em;">Update Status</label>
                <select id="status-update-select" data-order-id="<?= $order['id'] ?>" class="form-control" style="width:200px;font-weight:600;border:2px solid var(--primary);color:var(--primary);">
                    <?php foreach(['pending','processing','shipped','delivered','cancelled'] as $s): ?>
                    <option value="<?= $s ?>" <?= ($order['status'] === $s) ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <a href="<?= BASE_URL ?>/admin/invoice/<?= $order['id'] ?>" class="btn btn-primary" style="align-self:flex-end;">
                <i class='bx bx-download'></i> Print Invoice
            </a>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:3fr 1.5fr;gap:2rem;margin-bottom:2.5rem;">
        
        <!-- Order Items -->
        <div class="card" style="padding:1.5rem;">
            <h3 style="margin-bottom:1.25rem;"><i class='bx bx-cart-alt'></i> Order Items</h3>
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Size</th>
                            <th>Material</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th style="text-align:right;">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($items as $item): ?>
                        <tr>
                            <td>
                                <div style="display:flex;align-items:center;gap:1rem;">
                                    <div style="width:48px;height:48px;border-radius:4px;overflow:hidden;background:var(--bg-color);flex-shrink:0;">
                                        <img src="<?= htmlspecialchars($item['image_url'] ?: BASE_URL.'/assets/img/placeholder.jpg') ?>" style="width:100%;height:100%;object-fit:cover;">
                                    </div>
                                    <span style="font-weight:600;color:var(--text-primary);"><?= htmlspecialchars($item['product_name']) ?></span>
                                </div>
                                <?php if($item['design_data']): ?>
                                <div style="margin-top:0.5rem;"><span class="badge badge-primary">Custom Design Included</span></div>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($item['size'] ?: '—') ?></td>
                            <td><?= htmlspecialchars($item['material'] ?: '—') ?></td>
                            <td>₹<?= number_format($item['price'], 2) ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td style="text-align:right;"><strong>₹<?= number_format($item['price'] * $item['quantity'], 2) ?></strong></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Summary Table -->
            <div style="margin-top:2rem;border-top:1px solid var(--border-color);padding-top:1.5rem;display:flex;flex-direction:column;align-items:flex-end;gap:0.75rem;">
                <?php 
                    $subtotal = array_reduce($items, fn($c, $i) => $c + ($i['price'] * $i['quantity']), 0);
                    $discount = (float)($order['discount_amount'] ?? 0);
                ?>
                <div style="display:flex;justify-content:space-between;width:240px;color:var(--text-secondary);">
                    <span>Subtotal</span><span>₹<?= number_format($subtotal, 2) ?></span>
                </div>
                <?php if($discount > 0): ?>
                <div style="display:flex;justify-content:space-between;width:240px;color:var(--success);">
                    <span>Discount (<?= htmlspecialchars($order['coupon_code']) ?>)</span><span>-₹<?= number_format($discount, 2) ?></span>
                </div>
                <?php endif; ?>
                <div style="display:flex;justify-content:space-between;width:240px;font-size:1.5rem;font-weight:800;color:var(--primary);margin-top:0.5rem;border-top:2px solid var(--primary);padding-top:0.75rem;">
                    <span>Total</span><span>₹<?= number_format($order['total_amount'], 2) ?></span>
                </div>
            </div>
        </div>

        <!-- Sidebar Details -->
        <div style="display:flex;flex-direction:column;gap:1.5rem;">
            <!-- Customer Info -->
            <div class="card" style="padding:1.5rem;">
                <h3 style="margin-bottom:1rem;"><i class='bx bx-user-pin'></i> Customer Info</h3>
                <div style="display:flex;flex-direction:column;gap:0.75rem;">
                    <div>
                        <div style="font-size:0.75rem;font-weight:700;color:var(--text-tertiary);text-transform:uppercase;margin-bottom:0.15rem;">Name</div>
                        <div style="font-weight:600;"><?= htmlspecialchars($order['user_name'] ?? 'Guest Customer') ?></div>
                    </div>
                    <div>
                        <div style="font-size:0.75rem;font-weight:700;color:var(--text-tertiary);text-transform:uppercase;margin-bottom:0.15rem;">Email Address</div>
                        <a href="mailto:<?= htmlspecialchars($order['user_email'] ?? $order['guest_email']) ?>" style="color:var(--primary);font-weight:500;"><?= htmlspecialchars($order['user_email'] ?? $order['guest_email']) ?></a>
                    </div>
                    <div>
                        <div style="font-size:0.75rem;font-weight:700;color:var(--text-tertiary);text-transform:uppercase;margin-bottom:0.15rem;">Order Link (User)</div>
                        <div style="background:var(--bg-color);padding:0.4rem 0.6rem;border-radius:4px;font-size:0.8rem;border:1px dashed var(--border-color);overflow-x:auto;">
                            <?= BASE_URL ?>/order/track/<?= $order['id'] ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shipping Details -->
            <div class="card" style="padding:1.5rem;">
                <h3 style="margin-bottom:1rem;"><i class='bx bx-map-pin'></i> Shipping Details</h3>
                <div style="background:var(--bg-color);padding:1rem;border-radius:6px;font-size:0.95rem;line-height:1.6;white-space:pre-wrap;border:1px solid var(--border-color);"><?= htmlspecialchars($order['shipping_address'] ?: 'No address specified.') ?></div>
            </div>

            <!-- Payment Details -->
            <div class="card" style="padding:1.5rem;">
                <h3 style="margin-bottom:1rem;"><i class='bx bx-credit-card'></i> Payment Status</h3>
                <div style="display:flex;justify-content:space-between;align-items:center;">
                    <span class="badge payment-<?= $order['payment_status'] ?? 'pending' ?>" style="font-size:1rem;padding:0.4rem 1rem;"><?= ucfirst($order['payment_status'] ?? 'pending') ?></span>
                    <?php if($order['payment_id']): ?>
                    <span style="font-size:0.75rem;color:var(--text-tertiary);font-family:monospace;">ID: <?= $order['payment_id'] ?></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.getElementById('status-update-select').addEventListener('change', async function() {
    const orderId = this.dataset.orderId;
    const status  = this.value;
    const select  = this;
    
    // UI feedback
    const originalBorder = select.style.borderColor;
    select.disabled = true;
    select.style.borderColor = '#94a3b8';

    try {
        const resp = await fetch(BASE_URL + '/api/v1/admin/order-status', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ order_id: orderId, status: status })
        });
        const data = await resp.json();
        
        if (data.success) {
            select.style.borderColor = '#10b981';
            setTimeout(() => select.style.borderColor = originalBorder, 1500);
        } else {
            alert('Update failed: ' + (data.error || 'Unknown error'));
            select.style.borderColor = '#ef4444';
        }
    } catch (e) {
        alert('Server error. Status might not have been updated.');
        select.style.borderColor = '#ef4444';
    } finally {
        select.disabled = false;
    }
});
</script>
