<div class="container animate-fade-up" style="padding: 4rem 1rem;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 2rem; flex-wrap:wrap; gap:1rem;">
        <div>
            <h2 style="font-family:'Outfit',sans-serif; font-size:2rem;">Welcome back, <?= htmlspecialchars($_SESSION['user_name']) ?>! 👋</h2>
            <p style="color:var(--text-secondary); margin-top:0.25rem;">Manage your orders and account settings.</p>
        </div>
        <div>
            <a href="<?= BASE_URL ?>/products" class="btn btn-primary"><i class='bx bx-shopping-bag'></i> Start Shopping</a>
        </div>
    </div>
    
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon"><i class='bx bx-box'></i></div>
            <div class="stat-details">
                <h4>Total Orders</h4>
                <p>0</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(16, 185, 129, 0.1); color:var(--success);"><i class='bx bx-check-circle'></i></div>
            <div class="stat-details">
                <h4>Completed</h4>
                <p>0</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(245, 158, 11, 0.1); color:var(--warning);"><i class='bx bx-time'></i></div>
            <div class="stat-details">
                <h4>Pending</h4>
                <p>0</p>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <div style="padding:1.5rem; border-bottom:1px solid var(--border-color); display:flex; justify-content:space-between; align-items:center;">
            <h3 style="font-family:'Outfit',sans-serif;">Recent Orders</h3>
        </div>
        <div style="padding:4rem 2rem; text-align:center; color:var(--text-secondary);">
            <div style="font-size:4rem; color:var(--border-color); margin-bottom:1rem;"><i class='bx bx-ghost'></i></div>
            <h4 style="color:var(--text-primary); margin-bottom:0.5rem; font-family:'Outfit',sans-serif; font-size:1.25rem;">No orders yet</h4>
            <p>You haven't placed any orders. Start exploring our products!</p>
            <a href="<?= BASE_URL ?>/products" class="btn btn-outline" style="margin-top:1.5rem;">Browse Catalog</a>
        </div>
    </div>
</div>
