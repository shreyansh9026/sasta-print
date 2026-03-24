<section class="container" style="padding:3rem 1rem;">
    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;margin-bottom:2rem;">
        <h2><i class='bx bx-purchase-tag'></i> Manage Coupons</h2>
        <button onclick="document.getElementById('add-coupon-modal').style.display='flex'" class="btn btn-primary"><i class='bx bx-plus'></i> Add Coupon</button>
    </div>

    <?php if(!empty($success)): ?>
    <div class="alert alert-success"><i class='bx bx-check-circle'></i> <?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <div class="card" style="padding:1.5rem;">
        <div class="table-wrapper">
        <table class="data-table">
            <thead><tr><th>Code</th><th>Type</th><th>Value</th><th>Min Order</th><th>Used/Limit</th><th>Expiry</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
            <?php foreach($coupons as $coupon): ?>
            <tr>
                <td><code style="background:var(--surface);padding:0.25rem 0.5rem;border-radius:4px;font-weight:700;"><?= htmlspecialchars($coupon['code']) ?></code></td>
                <td><?= ucfirst($coupon['type']) ?></td>
                <td><?= $coupon['type']==='percent' ? $coupon['value'].'%' : '₹'.$coupon['value'] ?></td>
                <td>₹<?= number_format($coupon['min_order_amount'], 2) ?></td>
                <td><?= $coupon['used_count'] ?> / <?= $coupon['usage_limit'] ?: '∞' ?></td>
                <td><?= $coupon['expiry_date'] ?? 'Never' ?></td>
                <td><span class="badge <?= $coupon['is_active'] ? 'badge-success' : 'badge-danger' ?>"><?= $coupon['is_active'] ? 'Active' : 'Inactive' ?></span></td>
                <td>
                    <button class="btn btn-outline" style="padding:0.25rem 0.75rem;font-size:0.8rem;" onclick="deleteCoupon(<?= $coupon['id'] ?>)"><i class='bx bx-trash'></i></button>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if(empty($coupons)): ?>
            <tr><td colspan="8" style="text-align:center;color:var(--text-secondary);padding:2rem;">No coupons yet.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
        </div>
    </div>
</section>

<!-- Add Coupon Modal -->
<div id="add-coupon-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;">
    <div class="card" style="padding:2rem;max-width:480px;width:100%;margin:1rem;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;">
            <h3 style="margin:0;">Add New Coupon</h3>
            <button onclick="document.getElementById('add-coupon-modal').style.display='none'" style="background:none;border:none;cursor:pointer;font-size:1.5rem;color:var(--text-secondary);">&times;</button>
        </div>
        <form action="<?= BASE_URL ?>/admin/coupons/add" method="POST">
            <?= CsrfMiddleware::field() ?>
            <div class="form-group">
                <label class="form-label">Coupon Code</label>
                <input type="text" name="code" class="form-control" placeholder="SUMMER20" required style="text-transform:uppercase;">
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div class="form-group">
                    <label class="form-label">Type</label>
                    <select name="type" class="form-control">
                        <option value="percent">Percent (%)</option>
                        <option value="flat">Flat (₹)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Value</label>
                    <input type="number" name="value" class="form-control" placeholder="10" min="0" step="0.01" required>
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div class="form-group">
                    <label class="form-label">Min Order (₹)</label>
                    <input type="number" name="min_order_amount" class="form-control" placeholder="0" min="0" step="0.01">
                </div>
                <div class="form-group">
                    <label class="form-label">Usage Limit (0=∞)</label>
                    <input type="number" name="usage_limit" class="form-control" placeholder="0" min="0">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Expiry Date</label>
                <input type="date" name="expiry_date" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;">Create Coupon</button>
        </form>
    </div>
</div>
<script>
function deleteCoupon(id) {
    if(!confirm('Delete this coupon?')) return;
    fetch(`${BASE_URL}/admin/coupons/delete/${id}`, {method:'POST'})
        .then(r => r.json())
        .then(d => { if(d.success) location.reload() });
}
</script>
