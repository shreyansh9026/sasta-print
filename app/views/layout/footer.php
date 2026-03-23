    </main>
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <h3><i class='bx bxs-printer'></i> <?= APP_NAME ?></h3>
                    <p style="opacity:0.8; line-height:1.6; max-width:280px;">
                        High-quality professional print solutions for your business. Fast, reliable, and beautifully crafted.
                    </p>
                    <div style="display:flex; gap:1rem; margin-top:1.5rem; font-size:1.5rem;">
                        <a href="#" style="color:#94a3b8; transition:color 0.2s;"><i class='bx bxl-facebook-circle'></i></a>
                        <a href="#" style="color:#94a3b8; transition:color 0.2s;"><i class='bx bxl-twitter'></i></a>
                        <a href="#" style="color:#94a3b8; transition:color 0.2s;"><i class='bx bxl-instagram-alt'></i></a>
                        <a href="#" style="color:#94a3b8; transition:color 0.2s;"><i class='bx bxl-linkedin-square'></i></a>
                    </div>
                </div>
                <div>
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="<?= BASE_URL ?>/products"><i class='bx bx-chevron-right'></i> All Products</a></li>
                        <li><a href="<?= BASE_URL ?>/login"><i class='bx bx-chevron-right'></i> My Account</a></li>
                        <li><a href="<?= BASE_URL ?>/cart"><i class='bx bx-chevron-right'></i> Shopping Cart</a></li>
                        <li><a href="#"><i class='bx bx-chevron-right'></i> Track Order</a></li>
                    </ul>
                </div>
                <div>
                    <h4>Contact Us</h4>
                    <ul style="color:#94a3b8;">
                        <li style="display:flex; align-items:center; gap:0.5rem;"><i class='bx bx-envelope'></i> support@<?= strtolower(str_replace(' ', '', APP_NAME)) ?>.com</li>
                        <li style="display:flex; align-items:center; gap:0.5rem;"><i class='bx bx-phone'></i> +1 (800) 123-4567</li>
                        <li style="display:flex; align-items:center; gap:0.5rem;"><i class='bx bx-map'></i> 123 Print Street, NY</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                &copy; <?= date('Y') ?> <?= APP_NAME ?>. All rights reserved. Designed with precision.
            </div>
        </div>
    </footer>
    <script>const BASE_URL = '<?= BASE_URL ?>';</script>
    <script src="<?= BASE_URL ?>/assets/js/app.js"></script>
</body>
</html>
