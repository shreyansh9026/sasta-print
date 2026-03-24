<div class="container animate-fade-up" style="padding: 4rem 1rem; max-width:640px;">
    
    <div style="text-align:center; margin-bottom: 2rem;">
        <i class='bx bx-check-shield' style="font-size:3rem; color:var(--success); margin-bottom:1rem;"></i>
        <h2 style="font-family:'Outfit',sans-serif; font-size:2rem; margin:0;">Secure Checkout</h2>
        <p style="color:var(--text-secondary);margin:0.5rem 0 0;">Your order is protected by 256-bit SSL encryption.</p>
    </div>

    <div class="form-container" style="margin-top:0;">
        <?php 
            $subtotal = 0; 
            foreach($cart as $item) $subtotal += ($item['price'] * $item['quantity']); 
        ?>

        <!-- Order Summary -->
        <div style="background:var(--bg-color); border-radius:var(--radius-md); padding:1.25rem; border:1px solid var(--border-color); margin-bottom:1.5rem;">
            <?php foreach($cart as $item): ?>
            <div style="display:flex;justify-content:space-between;align-items:center;padding:0.5rem 0;border-bottom:1px solid var(--border-color);">
                <div>
                    <div style="font-weight:600;"><?= htmlspecialchars($item['name']) ?></div>
                    <div style="font-size:0.8rem;color:var(--text-secondary);">Qty: <?= $item['quantity'] ?> <?= $item['size']? '| '.$item['size'] : '' ?> <?= $item['material']? '| '.$item['material'] : '' ?></div>
                </div>
                <div style="font-weight:600;">₹<?= number_format($item['price'] * $item['quantity'], 2) ?></div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Coupon -->
        <div class="form-group" style="margin-bottom:1rem;">
            <label class="form-label">🏷️ Coupon Code</label>
            <div style="display:flex;gap:0.5rem;">
                <input type="text" id="coupon-input" class="form-control" placeholder="Enter coupon code" style="flex:1;text-transform:uppercase;">
                <button type="button" id="apply-coupon" class="btn btn-outline" onclick="applyCoupon()">Apply</button>
            </div>
            <div id="coupon-msg" style="font-size:0.85rem;margin-top:0.5rem;"></div>
        </div>

        <!-- Price Summary -->
        <div style="background:var(--bg-color); border-radius:var(--radius-md); padding:1.25rem; border:1px solid var(--border-color); margin-bottom:2rem;">
            <div style="display:flex;justify-content:space-between;margin-bottom:0.5rem;">
                <span>Subtotal</span><span>₹<?= number_format($subtotal, 2) ?></span>
            </div>
            <div id="discount-row" style="display:none;justify-content:space-between;margin-bottom:0.5rem;color:#10b981;">
                <span>Discount (<span id="discount-label"></span>)</span><span id="discount-val">-₹0.00</span>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:1.25rem;font-weight:700;color:var(--primary);border-top:1px solid var(--border-color);padding-top:0.75rem;margin-top:0.5rem;">
                <span>Total</span><span id="final-total">₹<?= number_format($subtotal, 2) ?></span>
            </div>
        </div>

        <form id="checkout-form" onsubmit="event.preventDefault(); placeOrder();">
            <?php if(!isset($_SESSION['user_id'])): ?>
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <div style="position:relative;">
                    <i class='bx bx-envelope' style="position:absolute;left:1rem;top:50%;transform:translateY(-50%);color:var(--text-tertiary);font-size:1.2rem;"></i>
                    <input type="email" id="guest-email" class="form-control" required placeholder="For order receipt & tracking" style="padding-left:2.75rem;">
                </div>
            </div>
            <?php endif; ?>

            <div class="form-group">
                <label class="form-label">Shipping Address</label>
                <div style="position:relative;">
                    <i class='bx bx-map' style="position:absolute;left:1rem;top:1rem;color:var(--text-tertiary);font-size:1.2rem;"></i>
                    <textarea id="shipping-address" class="form-control" rows="4" required placeholder="Full address, City, PIN, State" style="padding-left:2.75rem;resize:vertical;"></textarea>
                </div>
            </div>

            <!-- Razorpay Pay Button -->
            <button type="submit" id="pay-btn" class="btn btn-primary" style="width:100%;font-size:1.1rem;padding:1rem;margin-top:1rem;">
                <i class='bx bx-rupee'></i> Pay ₹<span id="btn-amount"><?= number_format($subtotal, 2) ?></span>
            </button>
            <div style="text-align:center;margin-top:1rem;color:var(--text-tertiary);font-size:0.875rem;">
                <i class='bx bxs-lock-alt'></i> Powered by Razorpay · 100% Secure
            </div>
        </form>
    </div>
</div>

<!-- Razorpay SDK -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
let subtotal = <?= $subtotal ?>;
let discount = 0;
let couponCode = '';

function applyCoupon() {
    const code = document.getElementById('coupon-input').value.trim().toUpperCase();
    if (!code) return;
    fetch(BASE_URL + '/api/v1/coupon/validate', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ code, amount: subtotal })
    })
    .then(r => r.json())
    .then(data => {
        const msg = document.getElementById('coupon-msg');
        if (data.valid) {
            discount   = parseFloat(data.discount);
            couponCode = code;
            const total = Math.max(0, subtotal - discount);
            document.getElementById('discount-row').style.display = 'flex';
            document.getElementById('discount-label').textContent = code;
            document.getElementById('discount-val').textContent   = '-₹' + discount.toFixed(2);
            document.getElementById('final-total').textContent    = '₹' + total.toFixed(2);
            document.getElementById('btn-amount').textContent     = total.toFixed(2);
            msg.innerHTML = '<span style="color:#10b981;"><i class="bx bx-check"></i> Coupon applied! You save ₹' + discount.toFixed(2) + '</span>';
        } else {
            msg.innerHTML = '<span style="color:#ef4444;"><i class="bx bx-x"></i> ' + (data.error || 'Invalid coupon') + '</span>';
        }
    });
}

async function placeOrder() {
    const btn = document.getElementById('pay-btn');
    btn.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i> Creating order...';
    btn.disabled = true;

    const payload = {
        address:     document.getElementById('shipping-address').value,
        coupon_code: couponCode,
        email:       document.getElementById('guest-email')?.value || ''
    };

    try {
        const resp = await fetch(BASE_URL + '/api/v1/order', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(payload)
        });
        const data = await resp.json();

        if (!data.success) {
            alert(data.error || 'Order failed. Please try again.');
            btn.innerHTML = '<i class="bx bx-rupee"></i> Pay ₹' + document.getElementById('btn-amount').textContent;
            btn.disabled = false;
            return;
        }

        const rp = data.razorpay_order;

        // Demo mode (no key)
        if (!rp || rp.demo || !data.razorpay_key || data.razorpay_key === 'demo') {
            window.location.href = BASE_URL + '/order/success/' + data.order_id;
            return;
        }

        // Razorpay checkout
        const rzp = new Razorpay({
            key:          data.razorpay_key,
            amount:       rp.amount,
            currency:     rp.currency,
            order_id:     rp.id,
            name:         'SastaPrint',
            description:  'Order #' + data.order_id,
            handler: async function(response) {
                btn.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i> Verifying payment...';
                const verify = await fetch(BASE_URL + '/api/v1/payment/verify', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({
                        order_id:             data.order_id,
                        razorpay_order_id:    response.razorpay_order_id,
                        razorpay_payment_id:  response.razorpay_payment_id,
                        razorpay_signature:   response.razorpay_signature
                    })
                });
                const vData = await verify.json();
                if (vData.success) {
                    window.location.href = BASE_URL + '/order/success/' + data.order_id;
                } else {
                    alert('Payment verification failed. Contact support with order #' + data.order_id);
                }
            },
            modal: { ondismiss: function() {
                btn.innerHTML = '<i class="bx bx-rupee"></i> Pay ₹' + document.getElementById('btn-amount').textContent;
                btn.disabled = false;
            }},
            theme: { color: '#6c63ff' }
        });
        rzp.open();

    } catch(err) {
        alert('Server error. Please try again.');
        btn.innerHTML = '<i class="bx bx-rupee"></i> Pay ₹' + document.getElementById('btn-amount').textContent;
        btn.disabled = false;
    }
}
</script>
