<section class="admin-page container animate-fade-up">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:2rem;">
        <div>
            <h1><i class='bx bx-category'></i> Manage Categories</h1>
            <p style="color:var(--text-secondary);">Organize your printing products into clear groups.</p>
        </div>
        <a href="<?= BASE_URL ?>/admin/products" class="btn btn-outline"><i class='bx bx-left-arrow-alt'></i> Back to Products</a>
    </div>

    <div class="admin-grid" style="display:grid; grid-template-columns:1fr 2fr; gap:2rem;">
        <!-- Add Category Form -->
        <div class="card" style="padding:1.5rem; height:fit-content;">
            <h3 style="margin-bottom:1.5rem;">Add Category</h3>
            <form action="<?= BASE_URL ?>/admin/categories" method="POST">
                <input type="hidden" name="add" value="1">
                <div class="form-group">
                    <label class="form-label">Category Name</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g. Business Stationary" required>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%; margin-top:1rem;">Create Category</button>
            </form>
        </div>

        <!-- Categories List -->
        <div class="card" style="padding:1.5rem;">
            <h3 style="margin-bottom:1.5rem;">Existing Categories</h3>
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Category Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($categories as $cat): ?>
                        <tr>
                            <td>#<?= $cat['id'] ?></td>
                            <td><strong><?= htmlspecialchars($cat['name']) ?></strong></td>
                            <td>
                                <div style="display:flex; gap:0.5rem;">
                                    <button class="btn btn-outline" style="padding:0.25rem 0.5rem;" onclick="editCategory(<?= $cat['id'] ?>, '<?= addslashes($cat['name']) ?>')"><i class='bx bx-edit'></i></button>
                                    <form action="<?= BASE_URL ?>/admin/categories" method="POST" onsubmit="return confirm('Note: Category can only be deleted if it has no products. Continue?')" style="display:inline;">
                                        <input type="hidden" name="delete" value="1">
                                        <input type="hidden" name="id" value="<?= $cat['id'] ?>">
                                        <button type="submit" class="btn btn-outline" style="padding:0.25rem 0.5rem; color:var(--danger);"><i class='bx bx-trash'></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<!-- Edit Modal -->
<div id="edit-cat-modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:2000; align-items:center; justify-content:center;">
    <div class="card" style="padding:2rem; width:100%; max-width:400px; position:relative;">
        <h3 style="margin-bottom:1.5rem;">Edit Category</h3>
        <form action="<?= BASE_URL ?>/admin/categories" method="POST">
            <input type="hidden" name="edit" value="1">
            <input type="hidden" name="id" id="edit-id">
            <div class="form-group">
                <label class="form-label">Category Name</label>
                <input type="text" name="name" id="edit-name" class="form-control" required>
            </div>
            <div style="display:flex; gap:1rem; margin-top:1.5rem;">
                <button type="button" onclick="document.getElementById('edit-cat-modal').style.display='none'" class="btn btn-outline" style="flex:1;">Cancel</button>
                <button type="submit" class="btn btn-primary" style="flex:1;">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
function editCategory(id, name) {
    document.getElementById('edit-id').value = id;
    document.getElementById('edit-name').value = name;
    document.getElementById('edit-cat-modal').style.display = 'flex';
}
</script>
