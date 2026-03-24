<div class="container animate-fade-up" style="padding:5rem 1rem;text-align:center;max-width:500px;">
    <div style="width:80px;height:80px;background:linear-gradient(135deg,#10b981,#059669);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;font-size:2.5rem;color:white;">
        <i class='bx bx-check'></i>
    </div>
    <h1 style="font-size:2rem;margin-bottom:0.5rem;">Order Placed! 🎉</h1>
    <p style="color:var(--text-secondary);margin-bottom:2rem;">Your order <strong>#<?= htmlspecialchars($order['id'] ?? '') ?></strong> has been received and is being processed.</p>
    <?php if(!empty($order['user_id'])): ?>
    <p style="color:var(--text-secondary);margin-bottom:2rem;">You'll receive a confirmation email shortly. Track your order from your dashboard.</p>
    <?php else: ?>
    <p style="color:var(--text-secondary);margin-bottom:2rem;">A confirmation email will be sent to you. <a href="<?= BASE_URL ?>/register" style="color:var(--primary);">Create an account</a> to track your orders.</p>
    <?php endif; ?>
    <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
        <a href="<?= BASE_URL ?>/dashboard" class="btn btn-primary"><i class='bx bx-bar-chart-alt-2'></i> My Orders</a>
        <a href="<?= BASE_URL ?>/products" class="btn btn-outline"><i class='bx bx-store'></i> Continue Shopping</a>
    </div>
</div>
