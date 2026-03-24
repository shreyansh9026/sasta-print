<section class="admin-page container animate-fade-up">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:2.5rem;">
        <div>
            <h1><i class='bx bx-edit'></i> Edit Product</h1>
            <p style="color:var(--text-secondary);">Modify details and configuration for <strong><?= htmlspecialchars($product['name'] ?? 'New Product') ?></strong>.</p>
        </div>
        <a href="<?= BASE_URL ?>/admin/products" class="btn btn-outline"><i class='bx bx-left-arrow-alt'></i> Back to Products</a>
    </div>

    <form action="<?= BASE_URL ?>/admin/products/edit/<?= $product['id'] ?>" method="POST" class="admin-grid" style="display:grid; grid-template-columns:2fr 1fr; gap:2rem;">
        
        <!-- Left Column: Core Info -->
        <div class="card" style="padding:2rem;">
            <div class="form-group" style="margin-bottom:1.5rem;">
                <label class="form-label">Product Name</label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($product['name']) ?>" required style="font-size:1.2rem; font-weight:600;">
            </div>

            <div class="form-group" style="margin-bottom:1.5rem;">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="5" required style="line-height:1.6;"><?= htmlspecialchars($product['description']) ?></textarea>
            </div>

            <!-- Attributes Management (Sizes/Materials) -->
            <div style="margin-top:3rem; border-top:1px solid var(--border-color); padding-top:2rem;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
                    <h3><i class='bx bx-slider'></i> Configuration Options</h3>
                    <button type="button" onclick="addAttributeRow()" class="btn btn-primary" style="padding:0.5rem 1rem; border-radius:8px;"><i class='bx bx-plus'></i> Add Attribute</button>
                </div>
                
                <div id="attributes-list" style="display:flex; flex-direction:column; gap:1rem;">
                    <?php if(empty($attributes)): ?>
                        <p id="no-attrs" style="text-align:center; color:var(--text-tertiary); padding:2rem; border:1px dashed var(--border-color); border-radius:12px;">No attributes defined yet.</p>
                    <?php endif; ?>

                    <?php foreach($attributes as $idx => $attr): ?>
                    <div class="attr-row card" style="display:grid; grid-template-columns:1fr 2fr 1fr 48px; gap:1rem; align-items:center; background:var(--bg-color); border-radius:12px; padding:1rem; border:1px solid var(--border-color);">
                        <select name="attr_type[]" class="form-control" style="border-radius:8px;">
                            <option value="size" <?= $attr['attribute_type']=='size'?'selected':'' ?>>Size</option>
                            <option value="material" <?= $attr['attribute_type']=='material'?'selected':'' ?>>Material</option>
                        </select>
                        <input type="text" name="attr_value[]" class="form-control" placeholder="e.g. A4 / Premium Matte" value="<?= htmlspecialchars($attr['attribute_value']) ?>" required style="border-radius:8px;">
                        <input type="number" step="0.01" name="attr_price[]" class="form-control" placeholder="+ Price" value="<?= $attr['price_modifier'] ?>" required style="border-radius:8px;">
                        <button type="button" onclick="this.closest('.attr-row').remove()" class="btn btn-outline" style="color:var(--danger); border-radius:8px; padding:0.5rem;"><i class='bx bx-trash'></i></button>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Right Column: Meta & Actions -->
        <div>
            <div class="card" style="padding:2rem;">
                <div class="form-group" style="margin-bottom:1.5rem;">
                    <label class="form-label">Category</label>
                    <select name="category_id" class="form-control" required style="border-radius:8px;">
                        <?php foreach($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= $product['category_id'] == $cat['id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom:1.5rem;">
                    <label class="form-label">Base Price (₹)</label>
                    <input type="number" step="0.01" name="base_price" class="form-control" value="<?= $product['base_price'] ?>" required style="font-size:1.5rem; font-weight:700; color:var(--primary); border-radius:12px;">
                </div>

                <div class="form-group" style="margin-bottom:2rem;">
                    <label class="form-label">Image URL</label>
                    <input type="text" name="image_url" id="image-url-input" class="form-control" value="<?= htmlspecialchars($product['image_url']) ?>" style="border-radius:8px; font-family:monospace; font-size:0.85rem;">
                    <?php if(!empty($product['image_url'])): ?>
                    <div style="margin-top:1rem; border-radius:12px; overflow:hidden; border:1px solid var(--border-color);">
                        <img src="<?= htmlspecialchars($product['image_url']) ?>" style="width:100%; height:auto; display:block;">
                    </div>
                    <?php endif; ?>
                </div>

                <div style="display:flex; flex-direction:column; gap:1rem; margin-top:2rem;">
                    <button type="submit" class="btn btn-primary" style="height:3.5rem; font-weight:700; border-radius:12px;">Save Changes <i class='bx bx-save'></i></button>
                    <a href="<?= BASE_URL ?>/admin/products" class="btn btn-outline" style="border-radius:12px;">Discard Changes</a>
                </div>
            </div>
        </div>
    </form>
</section>

<script>
function addAttributeRow() {
    const list = document.getElementById('attributes-list');
    const noMsg = document.getElementById('no-attrs');
    if(noMsg) noMsg.remove();
    
    const row = document.createElement('div');
    row.className = 'attr-row card';
    row.style = "display:grid; grid-template-columns:1fr 2fr 1fr 48px; gap:1rem; align-items:center; background:var(--bg-color); border-radius:12px; padding:1rem; border:1px solid var(--border-color); margin-bottom:1rem;";
    row.innerHTML = `
        <select name="attr_type[]" class="form-control" style="border-radius:8px;">
            <option value="size">Size</option>
            <option value="material">Material</option>
        </select>
        <input type="text" name="attr_value[]" class="form-control" placeholder="e.g. A2" required style="border-radius:8px;">
        <input type="number" step="0.01" name="attr_price[]" class="form-control" placeholder="+0.00" value="0.00" required style="border-radius:8px;">
        <button type="button" onclick="this.closest('.attr-row').remove()" class="btn btn-outline" style="color:var(--danger); border-radius:8px; padding:0.5rem;"><i class='bx bx-trash'></i></button>
    `;
    list.appendChild(row);
}
</script>
