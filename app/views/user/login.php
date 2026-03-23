<div class="container animate-fade-up" style="padding: 4rem 1rem;">
    <div class="form-container">
        <div style="text-align:center; margin-bottom: 2rem;">
            <div style="width:56px; height:56px; background:rgba(79, 70, 229, 0.1); color:var(--primary); font-size:1.75rem; display:flex; align-items:center; justify-content:center; border-radius:var(--radius-full); margin:0 auto 1rem;">
                <i class='bx bx-user'></i>
            </div>
            <h2 style="font-family:'Outfit',sans-serif; font-size:1.75rem;">Welcome Back</h2>
            <p style="color:var(--text-secondary); margin-top:0.5rem;">Please log in to your account</p>
        </div>
        <?php if(isset($error)): ?>
            <div style="background:rgba(239, 68, 68, 0.1); color:var(--danger); padding:1rem; border-radius:var(--radius-md); margin-bottom:1.5rem; display:flex; align-items:center; gap:0.5rem; font-weight:500;">
                <i class='bx bx-error-circle'></i> <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        <form action="<?= BASE_URL ?>/login" method="POST">
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="you@example.com" required>
            </div>
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%; font-size:1rem; padding:0.875rem;">Log In <i class='bx bx-log-in-circle'></i></button>
        </form>
        <p style="text-align:center; margin-top:2rem; color:var(--text-secondary);">
            Don't have an account? <a href="<?= BASE_URL ?>/register" style="color:var(--primary); font-weight:600;">Sign Up</a>
        </p>
    </div>
</div>
