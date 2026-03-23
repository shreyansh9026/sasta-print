<div class="container animate-fade-up" style="padding: 4rem 1rem;">
    <div style="display:flex; gap:4rem; flex-wrap:wrap;">
        <div style="flex:1; min-width:320px;">
            <?php if(!empty($product['image_url'])): ?>
            <div style="border-radius:var(--radius-lg); overflow:hidden; box-shadow:var(--shadow-lg); border:1px solid var(--border-color); position:relative;">
                <img src="<?= htmlspecialchars($product['image_url']) ?>" style="width:100%; height:auto; display:block;">
            </div>
            <?php else: ?>
            <div style="border-radius:var(--radius-lg); background:var(--bg-color); border:1px solid var(--border-color); aspect-ratio:4/3; display:flex; align-items:center; justify-content:center; color:var(--text-tertiary);">
                <i class='bx bx-image' style="font-size:6rem;"></i>
            </div>
            <?php endif; ?>
        </div>

        <div style="flex:1; min-width:320px;">
            <div style="margin-bottom:2.5rem;">
                <span class="badge badge-primary" style="margin-bottom:1rem;"><i class='bx bx-category'></i> <?= htmlspecialchars($product['category_name']) ?></span>
                <h1 style="font-family:'Outfit',sans-serif; font-size:3rem; margin-bottom:1rem; letter-spacing:-0.03em; line-height:1.1; color:var(--text-primary);"><?= htmlspecialchars($product['name']) ?></h1>
                <p style="color:var(--text-secondary); font-size:1.1rem; line-height:1.7;"><?= htmlspecialchars($product['description']) ?></p>
            </div>
            
            <div style="background:var(--surface); padding:2rem; border-radius:var(--radius-lg); box-shadow:var(--shadow-sm); border:1px solid var(--border-color);">
                <h3 style="font-family:'Outfit',sans-serif; font-size:1.25rem; font-weight:600; margin-bottom:1.5rem; display:flex; align-items:center; gap:0.5rem;"><i class='bx bx-slider-alt'></i> Configuration</h3>
                <form id="product-form" onsubmit="event.preventDefault(); proceedToDesign();">
                    <input type="hidden" id="pid" value="<?= $product['id'] ?>">
                    
                    <?php 
                    $sizes = []; $materials = [];
                    foreach($attributes as $attr) {
                        if($attr['attribute_type'] == 'size') $sizes[] = $attr;
                        if($attr['attribute_type'] == 'material') $materials[] = $attr;
                    }
                    ?>

                    <?php if(count($sizes) > 0): ?>
                    <div class="form-group">
                        <label class="form-label">Size</label>
                        <select id="psize" class="form-control" onchange="updatePrice()">
                            <?php foreach($sizes as $s): ?>
                                <option value="<?= htmlspecialchars($s['attribute_value']) ?>"><?= htmlspecialchars($s['attribute_value']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php endif; ?>

                    <?php if(count($materials) > 0): ?>
                    <div class="form-group">
                        <label class="form-label">Material</label>
                        <select id="pmaterial" class="form-control" onchange="updatePrice()">
                            <?php foreach($materials as $m): ?>
                                <option value="<?= htmlspecialchars($m['attribute_value']) ?>"><?= htmlspecialchars($m['attribute_value']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php endif; ?>

                    <div class="form-group">
                        <label class="form-label">Quantity</label>
                        <input type="number" id="pqty" class="form-control" value="1" min="1" onchange="updatePrice()">
                    </div>

                    <div style="padding-top:1.5rem; margin-top:1.5rem; border-top:1px solid var(--border-color);">
                        <div style="display:flex; justify-content:space-between; align-items:flex-end;">
                            <div>
                                <span style="font-size:0.875rem; color:var(--text-secondary); font-weight:600; text-transform:uppercase; letter-spacing:0.05em;">Estimated Total</span>
                                <div style="font-size:2.5rem; font-family:'Outfit',sans-serif; font-weight:800; color:var(--primary); line-height:1;">
                                    $<span id="live-price"><?= number_format($product['base_price'], 2) ?></span>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary" style="font-size:1.1rem; padding:1rem 2rem;">Start Designing <i class='bx bx-palette'></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function updatePrice() {
    const data = {
        product_id: document.getElementById('pid').value,
        size: document.getElementById('psize') ? document.getElementById('psize').value : '',
        material: document.getElementById('pmaterial') ? document.getElementById('pmaterial').value : '',
        quantity: document.getElementById('pqty').value
    };
    
    fetch(BASE_URL + '/api/pricing', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(data)
    })
    .then(r => r.json())
    .then(data => {
        if(data.price) {
            document.getElementById('live-price').innerText = data.price;
        }
    });
}
function proceedToDesign() {
    const pid = document.getElementById('pid').value;
    const size = document.getElementById('psize') ? document.getElementById('psize').value : '';
    const material = document.getElementById('pmaterial') ? document.getElementById('pmaterial').value : '';
    const qty = document.getElementById('pqty').value;
    const price = document.getElementById('live-price').innerText;
    
    // Store customization choices in session storage for the next page
    sessionStorage.setItem('customization', JSON.stringify({size, material, qty, price}));
    
    const btn = document.querySelector('button[type="submit"]');
    btn.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i> Preparing Editor';
    
    setTimeout(() => {
        window.location.href = BASE_URL + '/design/' + pid;
    }, 400);
}
document.addEventListener('DOMContentLoaded', updatePrice);
</script>
