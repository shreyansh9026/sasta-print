<section class="admin-page container">
    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1.5rem;margin-bottom:2rem;">
        <div>
            <h2 style="margin:0;"><i class='bx bx-package'></i> Manage Products</h2>
            <p style="color:var(--text-secondary);margin:0.25rem 0 0;">View and modify your print catalog.</p>
        </div>
        
        <div style="display:flex;gap:1rem;align-items:center;">
             <button onclick="document.getElementById('add-product-modal').style.display='flex'" class="btn btn-primary">
                <i class='bx bx-plus-circle'></i> Add New Product
            </button>
        </div>
    </div>

    <?php if(!empty($success)): ?>
    <div class="alert alert-success animate-fade-up"><i class='bx bx-check-circle'></i> <?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if(!empty($error)): ?>
    <div class="alert alert-error animate-fade-up"><i class='bx bx-error-circle'></i> <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- Products Table -->
    <div class="card" style="padding:1.5rem;">
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:70px;">Image</th>
                        <th>Product Name</th>
                        <th>Category</th>
                        <th>Base Price</th>
                        <th>Date Published</th>
                        <th>Status</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($products)): ?>
                    <tr><td colspan="7" style="text-align:center;padding:3rem;color:var(--text-secondary);">No products yet. <a href="javascript:void(0)" onclick="document.getElementById('add-product-modal').style.display='flex'">Add your first product!</a></td></tr>
                    <?php endif; ?>
                    
                    <?php foreach($products as $product): ?>
                    <tr id="product-row-<?= $product['id'] ?>">
                        <td>
                            <div style="width:48px;height:48px;border-radius:4px;overflow:hidden;background:var(--surface-hover);border:1px solid var(--border-color);">
                                <img src="<?= htmlspecialchars($product['image_url'] ?: BASE_URL.'/assets/img/placeholder.jpg') ?>" style="width:100%;height:100%;object-fit:cover;">
                            </div>
                        </td>
                        <td>
                            <div style="display:flex;flex-direction:column;">
                                <strong style="color:var(--text-primary);"><?= htmlspecialchars($product['name']) ?></strong>
                                <span style="font-size:0.75rem;color:var(--text-tertiary);"><?= htmlspecialchars($product['slug']) ?></span>
                            </div>
                        </td>
                        <td><span class="badge badge-primary" style="font-weight:600;"><?= htmlspecialchars($product['category_name']) ?></span></td>
                        <td><strong>₹<?= number_format($product['base_price'], 2) ?></strong></td>
                        <td><?= date('d M Y', strtotime($product['created_at'])) ?></td>
                        <td><span class="badge badge-success">Active</span></td>
                        <td style="text-align:right;">
                            <div style="display:flex;gap:0.5rem;justify-content:flex-end;">
                                <a href="<?= BASE_URL ?>/admin/products/edit/<?= $product['id'] ?>" class="btn btn-outline" style="padding:0.4rem;font-size:1.1rem;width:36px;height:36px;display:flex;align-items:center;justify-content:center;" title="Edit Product"><i class='bx bx-edit-alt'></i></a>
                                <button onclick="deleteProduct(<?= $product['id'] ?>)" class="btn btn-outline" style="padding:0.4rem;font-size:1.1rem;width:36px;height:36px;border-color:var(--danger);color:var(--danger) !important;display:flex;align-items:center;justify-content:center;" title="Delete Product"><i class='bx bx-trash'></i></button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- Add Product Modal -->
<div id="add-product-modal" style="display:none;position:fixed;inset:0;background:rgba(15,23,42,0.6);z-index:1000;backdrop-filter:blur(10px);align-items:center;justify-content:center;">
    <div class="card animate-fade-up" style="padding:2rem;max-width:600px;width:100%;margin:1rem;box-shadow:var(--shadow-lg);">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.75rem;">
            <h3 style="margin:0;"><i class='bx bx-plus-circle'></i> Add New Product</h3>
            <button onclick="document.getElementById('add-product-modal').style.display='none'" style="background:none;border:none;cursor:pointer;font-size:2rem;color:var(--text-tertiary);">&times;</button>
        </div>
        
        <form action="<?= BASE_URL ?>/admin/products/add" method="POST">
            <?= CsrfMiddleware::field() ?>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;">
                <div class="form-group">
                    <label class="form-label">Product Name</label>
                    <input type="text" name="name" class="form-control" placeholder="Business Cards High-Def" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Category</label>
                    <select name="category_id" class="form-control" required>
                        <?php 
                        $categories = (new Product())->getCategories();
                        foreach($categories as $c): ?>
                        <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Explain product features and quality..."></textarea>
            </div>
            
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;">
                <div class="form-group">
                    <label class="form-label">Base Price (₹)</label>
                    <input type="number" name="base_price" class="form-control" placeholder="9.99" step="0.01" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Main Image URL</label>
                    <input type="url" name="image_url" class="form-control" placeholder="https://example.com/image.jpg">
                </div>
            </div>
            
            <div style="display:flex;gap:1rem;margin-top:1.5rem;">
                <button type="button" onclick="document.getElementById('add-product-modal').style.display='none'" class="btn btn-outline" style="flex:1;">Cancel</button>
                <button type="submit" class="btn btn-primary" style="flex:2;">Save Product <i class='bx bx-check-double'></i></button>
            </div>
        </form>
    </div>
</div>

<script>
async function deleteProduct(id) {
    if (!confirm('Are you sure you want to delete this product? This action cannot be undone.')) return;
    
    try {
        const resp = await fetch(`${BASE_URL}/admin/products/delete/${id}`, {
            method: 'POST',
            headers: {'Content-Type': 'application/json'}
        });
        const data = await resp.json();
        
        if (data.success) {
            const row = document.getElementById(`product-row-${id}`);
            row.style.opacity = '0';
            row.style.transform = 'translateX(20px)';
            setTimeout(() => row.remove(), 400);
        } else {
            alert('Failed to delete: ' + (data.error || 'Check server logs'));
        }
    } catch (e) {
        alert('Server error while deleting product.');
    }
}
</script>
