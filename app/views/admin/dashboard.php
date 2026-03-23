<div class="container animate-fade-up" style="padding: 4rem 1rem;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 2rem; flex-wrap:wrap; gap:1rem;">
        <div>
            <h2 style="font-family:'Outfit',sans-serif; font-size:2rem;"><i class='bx bxs-dashboard' style="color:var(--primary);"></i> Admin Dashboard</h2>
            <p style="color:var(--text-secondary); margin-top:0.25rem;">Manage your store, orders, and products overview.</p>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(236, 72, 153, 0.1); color:var(--accent);"><i class='bx bx-cart-alt'></i></div>
            <div class="stat-details">
                <h4>Total Revenue</h4>
                <p>$<?= number_format(array_sum(array_column($orders, 'total_amount')), 2) ?></p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class='bx bx-package'></i></div>
            <div class="stat-details">
                <h4>Total Orders</h4>
                <p><?= count($orders) ?></p>
            </div>
        </div>
    </div>
    
    <div class="table-responsive">
        <div style="padding:1.5rem; border-bottom:1px solid var(--border-color); display:flex; justify-content:space-between; align-items:center;">
            <h3 style="font-family:'Outfit',sans-serif;">Recent Orders</h3>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($orders as $o): ?>
                <tr>
                    <td style="font-weight:600;">#<?= $o['id'] ?></td>
                    <td style="color:var(--text-secondary);"><?= date('M d, Y', strtotime($o['created_at'])) ?></td>
                    <td style="font-weight:600;">$<?= number_format($o['total_amount'], 2) ?></td>
                    <td>
                        <?php 
                            $statusClass = 'badge-primary';
                            if($o['status'] === 'completed') $statusClass = 'badge-success';
                            if($o['status'] === 'pending') $statusClass = 'badge-warning';
                            if($o['status'] === 'cancelled') $statusClass = 'badge-danger';
                        ?>
                        <span class="badge <?= $statusClass ?>">
                            <?= ucfirst($o['status']) ?>
                        </span>
                    </td>
                    <td><button class="btn btn-outline" style="padding:0.25rem 0.5rem; font-size:0.75rem;">View</button></td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($orders)): ?>
                    <tr><td colspan="5" style="padding:3rem 1rem; text-align:center; color:var(--text-secondary);">
                        <i class='bx bx-inbox' style="font-size:3rem; margin-bottom:1rem; opacity:0.5;"></i>
                        <p>No orders found.</p>
                    </td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
