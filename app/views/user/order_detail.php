<section class="container" style="padding:3rem 1rem;max-width:800px;">
    <a href="<?= BASE_URL ?>/dashboard" class="btn btn-outline" style="margin-bottom:2rem;"><i class='bx bx-left-arrow-alt'></i> Back to Dashboard</a>
    
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-bottom:2rem;">
        <div class="card" style="padding:1.5rem;">
            <h3><i class='bx bx-info-circle'></i> Order Info</h3>
            <table style="width:100%;border-collapse:collapse;">
                <tr><td style="padding:0.4rem 0;color:var(--text-secondary);">Order #</td><td><strong><?= $order['id'] ?></strong></td></tr>
                <tr><td style="padding:0.4rem 0;color:var(--text-secondary);">Date</td><td><?= date('d M Y, h:i A', strtotime($order['created_at'])) ?></td></tr>
                <tr><td style="padding:0.4rem 0;color:var(--text-secondary);">Status</td><td><span class="badge status-<?= $order['status'] ?>"><?= ucfirst($order['status']) ?></span></td></tr>
                <tr><td style="padding:0.4rem 0;color:var(--text-secondary);">Payment</td><td><span class="badge payment-<?= $order['payment_status'] ?? 'pending' ?>"><?= ucfirst($order['payment_status'] ?? 'pending') ?></span></td></tr>
            </table>
        </div>
        <div class="card" style="padding:1.5rem;">
            <h3><i class='bx bx-map'></i> Shipping Address</h3>
            <p style="color:var(--text-secondary);white-space:pre-wrap;"><?= htmlspecialchars($order['shipping_address'] ?? 'N/A') ?></p>
        </div>
    </div>
    
    <div class="card" style="padding:1.5rem;">
        <h3 style="margin-bottom:1.25rem;"><i class='bx bx-list-ul'></i> Items Ordered</h3>
        <div class="table-wrapper">
        <table class="data-table">
            <thead><tr><th>Product</th><th>Size</th><th>Material</th><th>Qty</th><th>Price</th></tr></thead>
            <tbody>
            <?php foreach($items as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['product_name']) ?></td>
                <td><?= htmlspecialchars($item['size'] ?? '—') ?></td>
                <td><?= htmlspecialchars($item['material'] ?? '—') ?></td>
                <td><?= $item['quantity'] ?></td>
                <td>₹<?= number_format($item['price'], 2) ?></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        </div>
        <div style="text-align:right;padding-top:1rem;border-top:1px solid var(--border-color);margin-top:1rem;">
            <?php if(!empty($order['discount_amount']) && $order['discount_amount'] > 0): ?>
            <div style="color:#10b981;">Discount (<?= $order['coupon_code'] ?>): -₹<?= number_format($order['discount_amount'], 2) ?></div>
            <?php endif; ?>
            <div style="font-size:1.25rem;font-weight:700;color:var(--primary);">Total: ₹<?= number_format($order['total_amount'], 2) ?></div>
        </div>
    </div>
</section>
