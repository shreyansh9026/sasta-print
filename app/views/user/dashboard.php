<section class="container" style="padding:3rem 1rem;">
    <!-- Success / Error flash -->
    <?php if(!empty($success)): ?>
    <div class="alert alert-success animate-fade-up"><i class='bx bx-check-circle'></i> <?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;margin-bottom:2rem;">
        <div>
            <h1 style="margin:0;">👋 Welcome, <?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?></h1>
            <p style="color:var(--text-secondary);margin:0.25rem 0 0;">Manage your orders and account from here.</p>
        </div>
        <a href="<?= BASE_URL ?>/logout" class="btn btn-outline"><i class='bx bx-log-out'></i> Logout</a>
    </div>

    <!-- Summary Cards -->
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:1rem;margin-bottom:2rem;">
        <div class="stat-card">
            <div class="stat-icon" style="background:linear-gradient(135deg,#6c63ff,#8b5cf6)"><i class='bx bx-package'></i></div>
            <div class="stat-info">
                <div class="stat-label">Total Orders</div>
                <div class="stat-value"><?= count($orders) ?></div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:linear-gradient(135deg,#10b981,#059669)"><i class='bx bx-check-circle'></i></div>
            <div class="stat-info">
                <div class="stat-label">Delivered</div>
                <div class="stat-value"><?= count(array_filter($orders, fn($o) => $o['status'] === 'delivered')) ?></div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:linear-gradient(135deg,#f59e0b,#d97706)"><i class='bx bx-time'></i></div>
            <div class="stat-info">
                <div class="stat-label">Pending</div>
                <div class="stat-value"><?= count(array_filter($orders, fn($o) => $o['status'] === 'pending')) ?></div>
            </div>
        </div>
    </div>

    <!-- Order History -->
    <div class="card" style="padding:1.5rem;">
        <h2 style="margin-bottom:1.25rem;"><i class='bx bx-list-ul'></i> Your Orders</h2>
        <?php if(empty($orders)): ?>
        <div style="text-align:center;padding:3rem;color:var(--text-secondary);">
            <i class='bx bx-shopping-bag' style="font-size:4rem;opacity:0.3;"></i>
            <p>No orders yet. <a href="<?= BASE_URL ?>/products" class="link">Start shopping!</a></p>
        </div>
        <?php else: ?>
        <div class="table-wrapper">
        <table class="data-table">
            <thead><tr><th>Order #</th><th>Items</th><th>Total</th><th>Status</th><th>Payment</th><th>Date</th><th>Detail</th></tr></thead>
            <tbody>
            <?php foreach($orders as $order): ?>
            <tr>
                <td><strong>#<?= $order['id'] ?></strong></td>
                <td><?= $order['item_count'] ?> item(s)</td>
                <td>₹<?= number_format($order['total_amount'], 2) ?></td>
                <td><span class="badge status-<?= $order['status'] ?>"><?= ucfirst($order['status']) ?></span></td>
                <td><span class="badge payment-<?= $order['payment_status'] ?? 'pending' ?>"><?= ucfirst($order['payment_status'] ?? 'pending') ?></span></td>
                <td><?= date('d M Y', strtotime($order['created_at'])) ?></td>
                <td><a href="<?= BASE_URL ?>/dashboard/order/<?= $order['id'] ?>" class="btn btn-outline" style="padding:0.25rem 0.75rem;font-size:0.8rem;"><i class='bx bx-show'></i> View</a></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        </div>
        <?php endif; ?>
    </div>

    <div style="margin-top:2rem;text-align:center;">
        <a href="<?= BASE_URL ?>/products" class="btn btn-primary"><i class='bx bx-printer'></i> Order More Prints</a>
    </div>
</section>
