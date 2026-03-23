<div class="container animate-fade-up" style="padding: 2rem 1rem;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
        <div>
            <span class="badge badge-primary" style="margin-bottom:0.5rem;"><i class='bx bx-palette'></i> Design Studio</span>
            <h2 style="font-family:'Outfit',sans-serif; font-size:2rem; margin:0;"><?= htmlspecialchars($product['name']) ?></h2>
        </div>
        <div>
            <button onclick="addToCart()" class="btn btn-primary" style="padding:0.75rem 2rem;">Save & Add to Cart <i class='bx bx-check'></i></button>
        </div>
    </div>
    
    <div style="display:flex; gap:2rem; flex-wrap:wrap; align-items:flex-start;">
        <div style="flex: 1; min-width:300px;">
            <div style="background:var(--surface); padding:1.5rem; border-radius:var(--radius-lg); box-shadow:var(--shadow-md); border:1px solid var(--border-color);">
                <div style="margin-bottom:1.5rem; display:flex; gap:0.75rem; flex-wrap:wrap; padding-bottom:1.5rem; border-bottom:1px solid var(--border-color);">
                    <button onclick="addText()" class="btn btn-outline" style="padding:0.5rem 1rem; font-size:0.875rem;"><i class='bx bx-text'></i> Add Text</button>
                    <button onclick="addRect()" class="btn btn-outline" style="padding:0.5rem 1rem; font-size:0.875rem;"><i class='bx bx-shape-square'></i> Add Shape</button>
                    <input type="file" id="imageLoader" style="display:none;" onchange="handleImage(event)" accept="image/*">
                    <button onclick="document.getElementById('imageLoader').click()" class="btn btn-outline" style="padding:0.5rem 1rem; font-size:0.875rem;"><i class='bx bx-cloud-upload'></i> Upload Image</button>
                    <div style="flex:1;"></div>
                    <button onclick="deleteObject()" class="btn" style="background:rgba(239, 68, 68, 0.1); color:var(--danger); padding:0.5rem 1rem; font-size:0.875rem;"><i class='bx bx-trash'></i> Delete</button>
                </div>
                
                <div style="background:var(--bg-color); border-radius:var(--radius-md); padding:2rem; display:flex; justify-content:center; align-items:center; min-height:400px; overflow:auto; border:1px inset var(--border-color);">
                    <div style="box-shadow:var(--shadow-lg); background:white;">
                        <canvas id="designCanvas" width="600" height="400"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <div style="width: 320px; background:var(--surface); padding:1.5rem; border-radius:var(--radius-lg); box-shadow:var(--shadow-sm); border:1px solid var(--border-color); position:sticky; top:100px;">
            <h3 style="font-family:'Outfit',sans-serif; margin-bottom:1.5rem; border-bottom:1px solid var(--border-color); padding-bottom:1rem; display:flex; align-items:center; gap:0.5rem;"><i class='bx bx-receipt'></i> Order Summary</h3>
            <div id="summary-details" style="margin-bottom:2rem;"></div>
            <button onclick="addToCart()" class="btn btn-primary" style="width:100%; font-size:1.1rem; padding:1rem;"><i class='bx bx-cart-add'></i> Add to Cart</button>
            <p style="text-align:center; font-size:0.75rem; color:var(--text-tertiary); margin-top:1rem;"><i class='bx bx-check-shield'></i> High-Resolution Print Ready</p>
        </div>
    </div>
</div>

<script>
let canvas;
document.addEventListener('DOMContentLoaded', function() {
    canvas = new fabric.Canvas('designCanvas');
    canvas.backgroundColor = '#ffffff';
    canvas.renderAll();

    const custom = JSON.parse(sessionStorage.getItem('customization') || '{}');
    let html = `
        <div style="display:flex; justify-content:space-between; margin-bottom:0.75rem; font-size:0.95rem;">
            <span style="color:var(--text-secondary);">Size:</span>
            <span style="font-weight:600;">${custom.size || 'Standard'}</span>
        </div>
        <div style="display:flex; justify-content:space-between; margin-bottom:0.75rem; font-size:0.95rem;">
            <span style="color:var(--text-secondary);">Material:</span>
            <span style="font-weight:600;">${custom.material || 'Standard'}</span>
        </div>
        <div style="display:flex; justify-content:space-between; margin-bottom:0.75rem; font-size:0.95rem;">
            <span style="color:var(--text-secondary);">Quantity:</span>
            <span class="badge badge-primary">${custom.qty || 1}</span>
        </div>
        <div style="margin-top:1.5rem; padding-top:1.5rem; border-top:1px dashed var(--border-color);">
            <div style="color:var(--text-secondary); text-transform:uppercase; font-size:0.75rem; font-weight:600; letter-spacing:0.05em; margin-bottom:0.5rem;">Estimated Total</div>
            <div style="font-size:2.5rem; color:var(--primary); font-family:'Outfit',sans-serif; font-weight:800; line-height:1;">$${custom.price || '0.00'}</div>
        </div>
    `;
    document.getElementById('summary-details').innerHTML = html;
});

function addText() {
    const text = new fabric.IText('Double Click to Edit', {
        left: 50, top: 50, fontFamily: 'Inter', fill: '#0f172a', fontWeight: '600'
    });
    canvas.add(text);
    canvas.setActiveObject(text);
}

function addRect() {
    const rect = new fabric.Rect({
        left: 100, top: 100, fill: '#4f46e5', width: 100, height: 100, rx: 8, ry: 8
    });
    canvas.add(rect);
    canvas.setActiveObject(rect);
}

function handleImage(e) {
    const reader = new FileReader();
    reader.onload = function(event){
        const imgObj = new Image();
        imgObj.src = event.target.result;
        imgObj.onload = function () {
            const image = new fabric.Image(imgObj);
            image.scaleToWidth(250);
            canvas.add(image);
            canvas.centerObject(image);
            canvas.setActiveObject(image);
            canvas.setCoords();
            document.getElementById('imageLoader').value = '';
        }
    }
    if(e.target.files[0]) {
        reader.readAsDataURL(e.target.files[0]);
    }
}

function deleteObject() {
    const activeObject = canvas.getActiveObject();
    if(activeObject) {
        canvas.remove(activeObject);
    }
}

function addToCart() {
    const custom = JSON.parse(sessionStorage.getItem('customization') || '{}');
    const designData = canvas.toJSON();
    
    // To generate a low-res image preview for cart
    const previewImage = canvas.toDataURL({format: 'png', quality: 0.8});

    const payload = {
        product_id: <?= $product['id'] ?>,
        name: <?= json_encode($product['name']) ?>,
        size: custom.size,
        material: custom.material,
        quantity: custom.qty,
        price: custom.price,
        image: previewImage,
        design_data: designData
    };
    
    const btns = document.querySelectorAll('button[onclick="addToCart()"]');
    btns.forEach(b => {
        b.disabled = true;
        b.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i> Saving...';
    });

    fetch(BASE_URL + '/api/cart/add', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(payload)
    })
    .then(r => r.json())
    .then(data => {
        if(data.success) {
            document.getElementById('cart-count').innerText = data.cart_count;
            window.location.href = BASE_URL + '/cart';
        } else {
            alert('Failed to add to cart.');
            btns.forEach(b => {
                b.disabled = false;
                b.innerHTML = '<i class="bx bx-cart-add"></i> Add to Cart';
            });
        }
    }).catch(err => {
        alert('Server error. Please try again.');
        btns.forEach(b => {
            b.disabled = false;
            b.innerHTML = '<i class="bx bx-cart-add"></i> Add to Cart';
        });
    });
}

// Add simple keyboard support for deletion
window.addEventListener('keydown', function(e) {
    if(e.key === 'Delete' || e.key === 'Backspace') {
        const activeObject = canvas.getActiveObject();
        if(activeObject && !activeObject.isEditing) {
            canvas.remove(activeObject);
            e.preventDefault();
        }
    }
});
</script>
