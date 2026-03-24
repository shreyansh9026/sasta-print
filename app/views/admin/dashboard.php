<section class="admin-page container">
    <!-- Flash Messages -->
    <?php if(!empty($success)): ?>
    <div class="alert alert-success animate-fade-up"><i class='bx bx-check-circle'></i> <?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <!-- Stats Cards -->
    <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:1.5rem; margin-bottom:2rem;">
        <div class="stat-card">
            <div class="stat-icon" style="background:linear-gradient(135deg,#6c63ff,#8b5cf6)"><i class='bx bx-package'></i></div>
            <div class="stat-info">
                <div class="stat-label">Total Orders</div>
                <div class="stat-value"><?= number_format($stats['total_orders']) ?></div>
                <div class="stat-sub" style="color:#f59e0b;"><?= $stats['pending_orders'] ?> pending</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:linear-gradient(135deg,#10b981,#059669)"><i class='bx bx-rupee'></i></div>
            <div class="stat-info">
                <div class="stat-label">Total Revenue</div>
                <div class="stat-value">₹<?= number_format($stats['revenue']['total'], 2) ?></div>
                <div class="stat-sub">₹<?= number_format($stats['revenue']['month'], 2) ?> this month</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:linear-gradient(135deg,#3b82f6,#2563eb)"><i class='bx bx-group'></i></div>
            <div class="stat-info">
                <div class="stat-label">Total Users</div>
                <div class="stat-value"><?= number_format($stats['total_users']) ?></div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:linear-gradient(135deg,#f59e0b,#d97706)"><i class='bx bx-store'></i></div>
            <div class="stat-info">
                <div class="stat-label">Products</div>
                <div class="stat-value"><?= $stats['total_products'] ?></div>
            </div>
        </div>
    </div>

    <!-- Revenue Chart -->
    <div class="admin-grid" style="display:grid;grid-template-columns:2fr 1fr;gap:1.5rem;margin-bottom:2rem;">
        <div class="card" style="padding:1.5rem;">
            <h3 style="margin-bottom:1rem;"><i class='bx bx-line-chart'></i> Revenue (Last 30 Days)</h3>
            <canvas id="revenueChart" height="120"></canvas>
        </div>
        <div class="card" style="padding:1.5rem;">
            <h3 style="margin-bottom:1rem;"><i class='bx bx-pie-chart-alt-2'></i> Orders by Status</h3>
            <canvas id="statusChart" height="120"></canvas>
        </div>
    </div>

    <!-- Quick Links -->
    <div style="display:flex;gap:1rem;flex-wrap:wrap;margin-bottom:2rem;">
        <a href="<?= BASE_URL ?>/admin/orders" class="btn btn-primary"><i class='bx bx-list-ul'></i> Manage Orders</a>
        <a href="<?= BASE_URL ?>/admin/products" class="btn btn-outline"><i class='bx bx-package'></i> Products</a>
        <a href="<?= BASE_URL ?>/admin/coupons" class="btn btn-outline"><i class='bx bx-purchase-tag'></i> Coupons</a>
        <a href="<?= BASE_URL ?>/admin/users" class="btn btn-outline"><i class='bx bx-group'></i> Users</a>
    </div>

    <!-- Recent Orders -->
    <div class="card" style="padding:1.5rem;margin-bottom:2rem;">
        <h3 style="margin-bottom:1.25rem;"><i class='bx bx-time'></i> Recent Orders</h3>
        <div class="table-wrapper">
        <table class="data-table">
            <thead><tr><th>Order #</th><th>Customer</th><th>Amount</th><th>Status</th><th>Payment</th><th>Date</th><th>Actions</th></tr></thead>
            <tbody>
            <?php foreach($recent_orders as $order): ?>
            <tr>
                <td><strong>#<?= $order['id'] ?></strong></td>
                <td><?= htmlspecialchars($order['user_name'] ?? $order['guest_email'] ?? 'Guest') ?></td>
                <td>₹<?= number_format($order['total_amount'], 2) ?></td>
                <td><span class="badge status-<?= $order['status'] ?>"><?= ucfirst($order['status']) ?></span></td>
                <td><span class="badge payment-<?= $order['payment_status'] ?? 'pending' ?>"><?= ucfirst($order['payment_status'] ?? 'pending') ?></span></td>
                <td><?= date('d M Y', strtotime($order['created_at'])) ?></td>
                <td>
                    <div style="display:flex;gap:0.5rem;">
                        <a href="<?= BASE_URL ?>/admin/orders/<?= $order['id'] ?>" class="btn btn-outline" style="padding:0.25rem 0.75rem;font-size:0.8rem;"><i class='bx bx-show'></i></a>
                        <select class="status-select" data-order="<?= $order['id'] ?>" style="padding:0.25rem;border-radius:6px;border:1px solid var(--border-color);background:var(--surface);color:var(--text-primary);font-size:0.8rem;">
                            <?php foreach(['pending','processing','shipped','delivered','cancelled'] as $s): ?>
                            <option value="<?= $s ?>" <?= $order['status']===$s?'selected':'' ?>><?= ucfirst($s) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <a href="<?= BASE_URL ?>/admin/invoice/<?= $order['id'] ?>" class="btn btn-outline" style="padding:0.25rem 0.75rem;font-size:0.8rem;" title="Download Invoice"><i class='bx bx-download'></i></a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        </div>
        <div style="margin-top:1rem;"><a href="<?= BASE_URL ?>/admin/orders" class="btn btn-outline">View All Orders <i class='bx bx-right-arrow-alt'></i></a></div>
    </div>

    <!-- Pending Reviews -->
    <?php if(!empty($pending_reviews)): ?>
    <div class="card" style="padding:1.5rem;">
        <h3 style="margin-bottom:1.25rem;"><i class='bx bx-star'></i> Pending Reviews (<?= count($pending_reviews) ?>)</h3>
        <?php foreach($pending_reviews as $review): ?>
        <div class="review-item" style="border:1px solid var(--border-color);border-radius:8px;padding:1rem;margin-bottom:0.75rem;display:flex;justify-content:space-between;align-items:center;">
            <div>
                <strong><?= htmlspecialchars($review['user_name']) ?></strong> on <em><?= htmlspecialchars($review['product_name']) ?></em>
                <div style="color:#f59e0b;margin:0.25rem 0;"><?= str_repeat('★', $review['rating']) . str_repeat('☆', 5-$review['rating']) ?></div>
                <p style="color:var(--text-secondary);margin:0;"><?= htmlspecialchars($review['comment']) ?></p>
            </div>
            <button class="btn btn-primary approve-review" data-id="<?= $review['id'] ?>" style="white-space:nowrap;"><i class='bx bx-check'></i> Approve</button>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
// Load analytics and render charts
fetch(BASE_URL + '/api/v1/admin/analytics')
    .then(r => r.json())
    .then(data => {
        // Revenue Chart
        const daily = data.revenue.daily || [];
        new Chart(document.getElementById('revenueChart'), {
            type: 'line',
            data: {
                labels: daily.map(d => d.date),
                datasets: [{
                    label: 'Revenue (₹)',
                    data: daily.map(d => d.revenue),
                    borderColor: '#6c63ff',
                    backgroundColor: 'rgba(108,99,255,0.1)',
                    fill: true, tension: 0.4, pointRadius: 3
                }]
            },
            options: { plugins:{legend:{display:false}}, scales:{ y:{beginAtZero:true}, x:{ticks:{maxTicksLimit:8}} } }
        });

        // Status Pie Chart
        const statuses = data.orders_by_status || [];
        const colors = { pending:'#f59e0b', processing:'#3b82f6', shipped:'#8b5cf6', delivered:'#10b981', cancelled:'#ef4444' };
        new Chart(document.getElementById('statusChart'), {
            type: 'doughnut',
            data: {
                labels: statuses.map(s => s.status),
                datasets: [{ data: statuses.map(s => s.count), backgroundColor: statuses.map(s => colors[s.status] || '#94a3b8') }]
            },
            options: { plugins:{ legend:{ position:'bottom', labels:{color:'#94a3b8'} } }, cutout:'65%' }
        });
    });

// Order status update
document.querySelectorAll('.status-select').forEach(sel => {
    sel.addEventListener('change', async function() {
        const resp = await fetch(BASE_URL + '/api/v1/admin/order-status', {
            method:'POST',
            headers:{'Content-Type':'application/json'},
            body: JSON.stringify({ order_id: this.dataset.order, status: this.value })
        });
        const data = await resp.json();
        if(!data.success) alert('Update failed');
    });
});

// Approve review
document.querySelectorAll('.approve-review').forEach(btn => {
    btn.addEventListener('click', async function() {
        const resp = await fetch(BASE_URL + '/api/v1/admin/review/approve/' + this.dataset.id, {method:'POST'});
        if(resp.ok) this.closest('.review-item').style.opacity='0.4';
    });
});
</script>
