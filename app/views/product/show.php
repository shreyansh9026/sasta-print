<div class="container animate-fade-up" style="padding: 4rem 1rem; max-width: 1200px;">
    <!-- Breadcrumb -->
    <nav style="margin-bottom:2rem; font-size:0.875rem;">
        <a href="<?= BASE_URL ?>" style="color:var(--text-tertiary);">Home</a>
        <span style="margin:0 0.5rem; color:var(--text-tertiary);">/</span>
        <a href="<?= BASE_URL ?>/products" style="color:var(--text-tertiary);">Products</a>
        <span style="margin:0 0.5rem; color:var(--text-tertiary);">/</span>
        <span style="color:var(--primary); font-weight:600;"><?= htmlspecialchars($product['name']) ?></span>
    </nav>

    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap:4rem; align-items:start; margin-bottom:5rem;">
        
        <!-- Left: Image & Rating -->
        <div>
            <div style="border-radius:24px; overflow:hidden; box-shadow:var(--shadow-lg); border:1px solid var(--border-color); background:white;">
                <img src="<?= htmlspecialchars($product['image_url'] ?: BASE_URL . '/assets/img/placeholder.jpg') ?>" style="width:100%; height:auto; display:block; aspect-ratio:1/1; object-fit:cover;">
            </div>
            
            <!-- Overall Rating Card -->
            <?php 
                $avg = (new Review())->getAvgRating($product['id']);
                $count = count($reviews);
            ?>
            <div class="card" style="margin-top:2rem; padding:1.5rem; display:flex; align-items:center; gap:1.5rem;">
                <div style="text-align:center;">
                    <div style="font-size:2.5rem; font-weight:800; color:var(--primary); line-height:1;"><?= $avg ?></div>
                    <div style="font-size:0.8rem; color:var(--text-tertiary); margin-top:0.25rem;">out of 5</div>
                </div>
                <div style="flex:1;">
                    <div style="color:#f59e0b; font-size:1.25rem; margin-bottom:0.25rem;">
                        <?php for($i=1;$i<=5;$i++): ?>
                            <i class='bx <?= ($i <= floor($avg)) ? "bxs-star" : (($i - $avg < 1) ? "bxs-star-half" : "bx-star") ?>'></i>
                        <?php endfor; ?>
                    </div>
                    <div style="font-size:0.9rem; color:var(--text-secondary);">Based on <?= $count ?> verified reviews</div>
                </div>
            </div>
        </div>

        <!-- Right: Details & Order -->
        <div>
            <div style="margin-bottom:2.5rem;">
                <span class="badge badge-primary" style="margin-bottom:1rem; font-size:0.8rem; padding:0.4rem 0.8rem;"><i class='bx bx-purchase-tag-alt'></i> <?= htmlspecialchars($product['category_name']) ?></span>
                <h1 style="font-family:'Outfit',sans-serif; font-size:3.5rem; margin-bottom:1rem; letter-spacing:-0.03em; line-height:1.1; color:var(--text-primary);"><?= htmlspecialchars($product['name']) ?></h1>
                <p style="color:var(--text-secondary); font-size:1.15rem; line-height:1.7;"><?= htmlspecialchars($product['description']) ?></p>
            </div>
            
            <div style="background:var(--surface); padding:2.5rem; border-radius:24px; box-shadow:var(--shadow-md); border:1px solid var(--border-color);">
                <h3 style="font-family:'Outfit',sans-serif; font-size:1.4rem; font-weight:700; margin-bottom:1.75rem; display:flex; align-items:center; gap:0.75rem;"><i class='bx bx-customize' style="color:var(--primary);"></i> Configure Your Print</h3>
                
                <form id="product-config-form" onsubmit="event.preventDefault(); proceedToDesign();">
                    <input type="hidden" id="pid" value="<?= $product['id'] ?>">
                    
                    <?php 
                    $sizes = []; $materials = [];
                    foreach($attributes as $attr) {
                        if($attr['attribute_type'] == 'size') $sizes[] = $attr;
                        if($attr['attribute_type'] == 'material') $materials[] = $attr;
                    }
                    ?>

                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem;">
                        <?php if(count($sizes) > 0): ?>
                        <div class="form-group">
                            <label class="form-label" style="font-weight:700;">Select Size</label>
                            <select id="psize" class="form-control" onchange="updatePrice()" style="height:3.5rem; border-radius:12px;">
                                <?php foreach($sizes as $s): ?>
                                    <option value="<?= htmlspecialchars($s['attribute_value']) ?>"><?= htmlspecialchars($s['attribute_value']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php endif; ?>

                        <?php if(count($materials) > 0): ?>
                        <div class="form-group">
                            <label class="form-label" style="font-weight:700;">Material</label>
                            <select id="pmaterial" class="form-control" onchange="updatePrice()" style="height:3.5rem; border-radius:12px;">
                                <?php foreach($materials as $m): ?>
                                    <option value="<?= htmlspecialchars($m['attribute_value']) ?>"><?= htmlspecialchars($m['attribute_value']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group" style="margin-top:0.5rem;">
                        <label class="form-label" style="font-weight:700;">Print Quantity</label>
                        <div style="display:flex; align-items:center; gap:1rem;">
                            <input type="number" id="pqty" class="form-control" value="1" min="1" onchange="updatePrice()" style="height:3.5rem; border-radius:12px; width:120px; text-align:center; font-weight:700; font-size:1.2rem;">
                            <span style="color:var(--text-tertiary); font-size:0.9rem;">(Bulk discounts may apply)</span>
                        </div>
                    </div>

                    <div style="padding-top:2rem; margin-top:2.5rem; border-top:2px solid var(--border-color);">
                        <div style="display:flex; justify-content:space-between; align-items:center;">
                            <div>
                                <span style="font-size:0.875rem; color:var(--text-tertiary); font-weight:700; text-transform:uppercase; letter-spacing:0.1em; display:block; margin-bottom:0.25rem;">Total (Incl. Taxes)</span>
                                <div style="font-size:2.75rem; font-family:'Outfit',sans-serif; font-weight:900; color:var(--primary); line-height:1;">
                                    ₹<span id="live-price"><?= number_format($product['base_price'], 2) ?></span>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary" style="font-size:1.15rem; padding:1.25rem 2.5rem; border-radius:16px; box-shadow:0 12px 20px -5px rgba(30,58,138,0.3);">
                                Design & Order <i class='bx bx-right-arrow-alt'></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            
            <div style="margin-top:2rem; display:flex; gap:1.5rem; font-size:0.9rem; color:var(--text-secondary);">
                <div style="display:flex; align-items:center; gap:0.5rem;"><i class='bx bx-check-circle' style="color:var(--success); font-size:1.2rem;"></i> High DPI Print</div>
                <div style="display:flex; align-items:center; gap:0.5rem;"><i class='bx bx-check-circle' style="color:var(--success); font-size:1.2rem;"></i> Premium Stock</div>
                <div style="display:flex; align-items:center; gap:0.5rem;"><i class='bx bx-check-circle' style="color:var(--success); font-size:1.2rem;"></i> Recyclable</div>
            </div>
        </div>
    </div>

    <!-- Review Section -->
    <div style="max-width:800px; margin-top:8rem;">
        <div style="display:flex; justify-content:space-between; align-items:flex-end; border-bottom:2px solid var(--border-color); padding-bottom:1.5rem; margin-bottom:3rem;">
            <div>
                <h2 style="font-family:'Outfit',sans-serif; font-size:2rem; margin:0;">Customer Reviews</h2>
                <p style="color:var(--text-secondary); margin-top:0.25rem;">What our clients say about this product.</p>
            </div>
            <?php if(isset($_SESSION['user_id'])): ?>
            <button onclick="document.getElementById('review-modal').style.display='flex'" class="btn btn-outline" style="border-radius:20px;">Write a Review</button>
            <?php endif; ?>
        </div>

        <div style="display:flex; flex-direction:column; gap:2.5rem;">
            <?php if(empty($reviews)): ?>
                <div style="text-align:center; padding:4rem; background:var(--bg-color); border-radius:16px; border:2px dashed var(--border-color);">
                    <i class='bx bx-message-alt-detail' style="font-size:3rem; color:var(--text-tertiary); margin-bottom:1rem;"></i>
                    <p style="color:var(--text-secondary); margin:0;">No reviews yet. Be the first to share your experience!</p>
                </div>
            <?php endif; ?>

            <?php foreach($reviews as $rev): ?>
            <div class="animate-fade-up" style="display:flex; gap:1.5rem;">
                <div style="width:48px; height:48px; border-radius:50%; background:var(--primary); color:white; display:flex; align-items:center; justify-content:center; font-weight:700; flex-shrink:0;">
                    <?= strtoupper(substr($rev['user_name'], 0, 1)) ?>
                </div>
                <div style="flex:1;">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.5rem;">
                        <strong style="color:var(--text-primary);"><?= htmlspecialchars($rev['user_name']) ?></strong>
                        <span style="font-size:0.8rem; color:var(--text-tertiary);"><?= date('d M Y', strtotime($rev['created_at'])) ?></span>
                    </div>
                    <div style="color:#f59e0b; font-size:0.875rem; margin-bottom:0.75rem;">
                        <?php for($i=1;$i<=5;$i++) echo "<i class='bx ".($i<=$rev['rating']?'bxs-star':'bx-star')."'></i>"; ?>
                    </div>
                    <p style="color:var(--text-secondary); line-height:1.6; font-size:1.05rem;"><?= nl2br(htmlspecialchars($rev['comment'])) ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Write Review Modal -->
<div id="review-modal" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.6); z-index:2000; backdrop-filter:blur(10px); align-items:center; justify-content:center;">
    <div class="card animate-fade-up" style="padding:2.5rem; max-width:500px; width:100%; margin:1rem; box-shadow:var(--shadow-xl);">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:2rem;">
            <h3 style="margin:0;"><i class='bx bx-edit'></i> Write Product Review</h3>
            <button onclick="document.getElementById('review-modal').style.display='none'" style="background:none; border:none; cursor:pointer; font-size:2rem; color:var(--text-tertiary); transition:color 0.2s;" onmouseover="this.style.color='var(--primary)'">&times;</button>
        </div>
        
        <form id="review-form" onsubmit="event.preventDefault(); submitReview();">
            <div class="form-group">
                <label class="form-label" style="font-weight:700;">Rate your experience</label>
                <div style="display:flex; gap:0.75rem; font-size:2rem; color:#f59e0b; cursor:pointer;" id="star-selector">
                    <i class='bx bx-star' data-val="1"></i>
                    <i class='bx bx-star' data-val="2"></i>
                    <i class='bx bx-star' data-val="3"></i>
                    <i class='bx bx-star' data-val="4"></i>
                    <i class='bx bx-star' data-val="5"></i>
                </div>
                <input type="hidden" id="rev-rating" value="0">
            </div>
            
            <div class="form-group" style="margin-top:1.5rem;">
                <label class="form-label" style="font-weight:700;">Your detailed feedback</label>
                <textarea id="rev-comment" class="form-control" rows="4" placeholder="How was the print quality? Was the delivery on time?" required style="border-radius:12px;"></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary" style="width:100%; height:3.5rem; border-radius:12px; margin-top:1rem; font-weight:700;">Submit Review <i class='bx bx-paper-plane'></i></button>
        </form>
    </div>
</div>

<script>
// Price Calculation
async function updatePrice() {
    const psize = document.getElementById('psize');
    const pmaterial = document.getElementById('pmaterial');
    const pqty = document.getElementById('pqty');
    
    const data = {
        product_id: document.getElementById('pid').value,
        size: psize ? psize.value : '',
        material: pmaterial ? pmaterial.value : '',
        quantity: pqty ? pqty.value : 1
    };
    
    try {
        const resp = await fetch(BASE_URL + '/api/v1/pricing', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(data)
        });
        const result = await resp.json();
        if(result.total) {
            document.getElementById('live-price').innerText = result.total;
        }
    } catch(e) { console.error('Pricing error', e); }
}

// Design Flow
function proceedToDesign() {
    const pid = document.getElementById('pid').value;
    const size = document.getElementById('psize') ? document.getElementById('psize').value : '';
    const material = document.getElementById('pmaterial') ? document.getElementById('pmaterial').value : '';
    const qty = document.getElementById('pqty').value;
    const price = document.getElementById('live-price').innerText;
    
    sessionStorage.setItem('customization', JSON.stringify({size, material, qty, price}));
    window.location.href = BASE_URL + '/design/' + pid;
}

// Review Interaction
if(document.getElementById('star-selector')) {
    const stars = document.getElementById('star-selector').children;
    for(let i=0; i<stars.length; i++) {
        stars[i].onclick = function() {
            const val = parseInt(this.dataset.val);
            document.getElementById('rev-rating').value = val;
            for(let j=0; j<stars.length; j++) {
                stars[j].className = j < val ? 'bx bxs-star' : 'bx bx-star';
            }
        };
    }
}

async function submitReview() {
    const rating = document.getElementById('rev-rating').value;
    const comment = document.getElementById('rev-comment').value;
    const pid = document.getElementById('pid').value;
    
    if(rating == 0) return alert('Please select a star rating.');
    
    try {
        const resp = await fetch(BASE_URL + '/api/v1/review/add', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ product_id: pid, rating, comment })
        });
        const data = await resp.json();
        if(data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert(data.error);
        }
    } catch(e) { alert('Failed to submit review.'); }
}

document.addEventListener('DOMContentLoaded', updatePrice);
</script>
