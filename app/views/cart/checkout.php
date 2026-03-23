<div class="container animate-fade-up" style="padding: 4rem 1rem; max-width:600px;">
    
    <div style="text-align:center; margin-bottom: 2rem;">
        <i class='bx bx-check-shield' style="font-size:3rem; color:var(--success); margin-bottom:1rem;"></i>
        <h2 style="font-family:'Outfit',sans-serif; font-size:2rem; margin:0;">Secure Checkout</h2>
    </div>

    <div class="form-container" style="margin-top:0;">
        <?php 
            $total=0; 
            foreach($cart as $item) $total += ($item['price'] * $item['quantity']); 
        ?>
        <div style="background:var(--bg-color); border-radius:var(--radius-md); padding:1.5rem; border:1px solid var(--border-color); display:flex; justify-content:space-between; align-items:center; margin-bottom:2.5rem;">
            <div style="font-weight:600; color:var(--text-secondary); text-transform:uppercase; letter-spacing:0.05em; font-size:0.875rem;">Total Payment</div>
            <div style="font-family:'Outfit',sans-serif; font-size:1.75rem; font-weight:700; color:var(--primary);">$<?= number_format($total, 2) ?></div>
        </div>

        <form id="checkout-form" onsubmit="event.preventDefault(); placeOrder();">
            <?php if(!isset($_SESSION['user_id'])): ?>
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <div style="position:relative;">
                    <i class='bx bx-envelope' style="position:absolute; left:1rem; top:50%; transform:translateY(-50%); color:var(--text-tertiary); font-size:1.2rem;"></i>
                    <input type="email" id="email" class="form-control" required placeholder="For order receipt & tracking" style="padding-left:2.75rem;">
                </div>
            </div>
            <?php endif; ?>

            <div class="form-group">
                <label class="form-label">Shipping Address</label>
                <div style="position:relative;">
                    <i class='bx bx-map' style="position:absolute; left:1rem; top:1rem; color:var(--text-tertiary); font-size:1.2rem;"></i>
                    <textarea id="address" class="form-control" rows="4" required placeholder="Full street address, City, ZIP, Country" style="padding-left:2.75rem; resize:vertical;"></textarea>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Payment Method</label>
                <div style="position:relative;">
                    <i class='bx bx-credit-card' style="position:absolute; left:1rem; top:50%; transform:translateY(-50%); color:var(--text-tertiary); font-size:1.2rem;"></i>
                    <select class="form-control" disabled style="padding-left:2.75rem; background-color:var(--bg-color); opacity:1;">
                        <option>Cash on Delivery (Demo Payment)</option>
                        <option>Credit Card (Integration Pending)</option>
                    </select>
                </div>
                <div style="display:flex; align-items:flex-start; gap:0.5rem; margin-top:0.75rem; padding:0.75rem; background:rgba(37, 99, 235, 0.05); border-radius:var(--radius-sm); border:1px solid rgba(37, 99, 235, 0.1);">
                    <i class='bx bx-info-circle' style="color:var(--primary); font-size:1.1rem; margin-top:0.125rem;"></i>
                    <p style="color:var(--text-secondary); font-size:0.875rem; margin:0;">For this demo, we'll proceed directly to order placement using simulated COD.</p>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%; font-size:1.1rem; padding:1rem; margin-top:1.5rem;">Confirm & Place Order <i class='bx bx-check-double'></i></button>
            <div style="text-align:center; margin-top:1.5rem; color:var(--text-tertiary); font-size:0.875rem; display:flex; align-items:center; justify-content:center; gap:0.25rem;">
                <i class='bx bxs-lock-alt'></i> 256-bit SSL Encrypted Checkout
            </div>
        </form>
    </div>
</div>

<script>
function placeOrder() {
    const btn = document.querySelector('button[type="submit"]');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i> Processing...';
    btn.disabled = true;

    const data = {
        email: document.getElementById('email') ? document.getElementById('email').value : '',
        address: document.getElementById('address').value
    };

    fetch(BASE_URL + '/api/order', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(data)
    })
    .then(r => r.json())
    .then(data => {
        if(data.success) {
            btn.innerHTML = '<i class="bx bx-check"></i> Success!';
            btn.classList.replace('btn-primary', 'badge-success');
            setTimeout(() => {
                window.location.href = BASE_URL;
            }, 1000);
        } else {
            alert('Failed to place order.');
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    }).catch(err => {
        alert('Server error. Please try again.');
        btn.innerHTML = originalText;
        btn.disabled = false;
    });
}
</script>
