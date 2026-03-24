<section class="admin-page container">
    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1.1rem;margin-bottom:2rem;">
        <div>
            <h2 style="margin:0;"><i class='bx bx-list-ul'></i> Manage Orders</h2>
            <p style="color:var(--text-secondary);margin:0.25rem 0 0;">Viewing <?= number_format($total) ?> orders in total.</p>
        </div>
        
        <div style="display:flex;gap:0.75rem;align-items:center;">
            <label style="font-size:0.9rem;font-weight:600;color:var(--text-secondary);">Filter by Status:</label>
            <select onchange="window.location.href='?status=' + this.value" class="form-control" style="width:auto;padding:0.5rem 2rem 0.5rem 1rem;">
                <option value="">All Statuses</option>
                <?php foreach(['pending','processing','shipped','delivered','cancelled'] as $s): ?>
                <option value="<?= $s ?>" <?= ($status === $s) ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="card" style="padding:1.5rem;">
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Date</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($orders)): ?>
                    <tr><td colspan="7" style="text-align:center;padding:3rem;color:var(--text-secondary);">No orders found.</td></tr>
                    <?php endif; ?>
                    
                    <?php foreach($orders as $order): ?>
                    <tr>
                        <td><strong>#<?= $order['id'] ?></strong></td>
                        <td>
                            <div style="display:flex;flex-direction:column;">
                                <span style="font-weight:600;color:var(--text-primary);"><?= htmlspecialchars($order['user_name'] ?? 'Guest') ?></span>
                                <span style="font-size:0.75rem;color:var(--text-tertiary);"><?= htmlspecialchars($order['user_email'] ?? $order['guest_email']) ?></span>
                            </div>
                        </td>
                        <td><strong>₹<?= number_format($order['total_amount'], 2) ?></strong></td>
                        <td><span class="badge status-<?= $order['status'] ?>"><?= ucfirst($order['status']) ?></span></td>
                        <td><span class="badge payment-<?= $order['payment_status'] ?? 'pending' ?>"><?= ucfirst($order['payment_status'] ?? 'pending') ?></span></td>
                        <td><?= date('d M Y, h:i A', strtotime($order['created_at'])) ?></td>
                        <td style="text-align:right;">
                            <div style="display:flex;gap:0.5rem;justify-content:flex-end;">
                                <a href="<?= BASE_URL ?>/admin/orders/<?= $order['id'] ?>" class="btn btn-outline" style="padding:0.4rem;font-size:1.1rem;width:36px;height:36px;" title="View Detail"><i class='bx bx-show-alt'></i></a>
                                <a href="<?= BASE_URL ?>/admin/invoice/<?= $order['id'] ?>" class="btn btn-outline" style="padding:0.4rem;font-size:1.1rem;width:36px;height:36px;border-color:var(--success);color:var(--success) !important;" title="Download Invoice"><i class='bx bx-download'></i></a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if($pages > 1): ?>
        <div style="display:flex;justify-content:center;gap:0.5rem;margin-top:2rem;">
            <?php for($i=1; $i<=$pages; $i++): ?>
            <a href="?page=<?= $i ?><?= $status ? '&status='.$status : '' ?>" 
               class="btn <?= ($page == $i) ? 'btn-primary' : 'btn-outline' ?>" 
               style="min-width:40px;height:40px;padding:0;">
               <?= $i ?>
            </a>
            <?php endfor; ?>
        </div>
        <?php endif; ?>
    </div>
</section>
