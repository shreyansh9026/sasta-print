<section class="admin-page container">
    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1.5rem;margin-bottom:2.5rem;">
        <div>
            <h2 style="margin:0;"><i class='bx bx-group'></i> Registered Users</h2>
            <p style="color:var(--text-secondary);margin:0.25rem 0 0;">Manage accounts and roles across the platform.</p>
        </div>
        
        <div style="display:flex;gap:1rem;align-items:center;">
             <button class="btn btn-outline" style="border-radius:20px;font-size:0.875rem;">
                <i class='bx bx-export'></i> Export CSV
            </button>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card" style="padding:1.5rem;box-shadow:var(--shadow-md);">
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:60px;">ID</th>
                        <th>User Name</th>
                        <th>Email Address</th>
                        <th>Account Role</th>
                        <th>Registration Date</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($users)): ?>
                    <tr><td colspan="6" style="text-align:center;padding:3.5rem;color:var(--text-tertiary);">No users found. <i class='bx bx-sad'></i></td></tr>
                    <?php endif; ?>
                    
                    <?php foreach($users as $user): ?>
                    <tr>
                        <td><span style="font-family:monospace;color:var(--text-tertiary);">#<?= $user['id'] ?></span></td>
                        <td>
                            <div style="display:flex;align-items:center;gap:0.75rem;">
                                <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--primary-light));color:white;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.8rem;">
                                    <?= strtoupper(substr($user['name'], 0, 1)) ?>
                                </div>
                                <strong style="color:var(--text-primary);"><?= htmlspecialchars($user['name']) ?></strong>
                            </div>
                        </td>
                        <td><a href="mailto:<?= htmlspecialchars($user['email']) ?>" style="color:var(--primary);font-weight:500;text-decoration:none;"><?= htmlspecialchars($user['email']) ?> <i class='bx bx-link-external' style="font-size:0.8rem;"></i></a></td>
                        <td>
                            <span class="badge <?= ($user['role'] === 'admin') ? 'badge-primary' : 'badge-success' ?>" style="font-size:0.75rem;padding:0.25rem 0.6rem;">
                                <?= ucfirst($user['role']) ?>
                            </span>
                        </td>
                        <td><?= date('d M Y, h:i A', strtotime($user['created_at'])) ?></td>
                        <td style="text-align:right;">
                            <div style="display:flex;gap:0.5rem;justify-content:flex-end;">
                                <button class="btn btn-outline" style="padding:0.4rem;font-size:1.1rem;width:34px;height:34px;" title="Reset Password"><i class='bx bx-key'></i></button>
                                <button class="btn btn-outline" style="padding:0.4rem;font-size:1.1rem;width:34px;height:34px;" title="Review User History"><i class='bx bx-history'></i></button>
                                <?php if($user['id'] != $_SESSION['user_id']): ?>
                                <button class="btn btn-outline" style="padding:0.4rem;font-size:1.1rem;width:34px;height:34px;border-color:var(--danger);color:var(--danger) !important;" title="Suspend Account"><i class='bx bx-block'></i></button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
