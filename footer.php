<!-- FOOTER SECTION -->
<footer class="premium-footer">
    <div class="footer-wave">
        <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
            <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="shape-fill"></path>
        </svg>
    </div>

    <div class="footer-container">
        <div class="footer-grid">
            
            <!-- Column 1: Brand -->
            <div class="footer-col" data-aos="fade-up">
                <a href="#" class="footer-logo">LUXE<span>STORE</span></a>
                <p class="footer-desc">
                    Defining the future of luxury e-commerce. We provide curated premium collections for the modern lifestyle with a focus on quality and global delivery.
                </p>
                <div class="social-links">
                    <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#"><i class="fa-brands fa-x-twitter"></i></a>
                    <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
                </div>
            </div>

            <!-- Column 2: Quick Links -->
            <div class="footer-col" data-aos="fade-up" data-aos-delay="100">
                <h3>Quick Explore</h3>
                <ul>
                    <li><a href="shop.php">Home Page</a></li>
                    <li><a href="all-products.php">Full Catalog</a></li>
                    <li><a href="journey.php">Our Journey</a></li>
                    <li><a href="profile.php">My Account</a></li>
                    <li><a href="cart.php">Shopping Bag</a></li>
                </ul>
            </div>

            <!-- Column 3: Categories -->
            <div class="footer-col" data-aos="fade-up" data-aos-delay="200">
                <h3>Collections</h3>
                <ul>
                    <li><a href="all-products.php#section-Shoes">Premium Footwear</a></li>
                    <li><a href="all-products.php#section-Fashion">Clothing & Apparel</a></li>
                    <li><a href="all-products.php#section-Sunglasses">Designer Shades</a></li>
                    <li><a href="all-products.php#section-Accessories">Luxury Bags</a></li>
                </ul>
            </div>

            <!-- Column 4: Newsletter -->
            <div class="footer-col" data-aos="fade-up" data-aos-delay="300">
                <h3>Stay in the Loop</h3>
                <p style="font-size: 0.85rem; opacity: 0.6; margin-bottom: 20px;">Subscribe to get early access to new arrivals and exclusive flash sales.</p>
                <form class="newsletter-form" onsubmit="event.preventDefault(); Swal.fire('Subscribed!', 'Welcome to the LuxeStore elite club.', 'success')">
                    <input type="email" placeholder="Your Email Address" required>
                    <button type="submit"><i class="fa-solid fa-paper-plane"></i></button>
                </form>
                <div class="payment-partners">
                    <i class="fa-brands fa-cc-visa"></i>
                    <i class="fa-brands fa-cc-mastercard"></i>
                    <i class="fa-brands fa-cc-paypal"></i>
                    <i class="fa-brands fa-cc-apple-pay"></i>
                </div>
            </div>

        </div>

        <div class="footer-bottom">
            <p>&copy; 2025 LuxeStore Premium Experience. All Rights Reserved.</p>
            <div class="bottom-links">
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
                <a href="#">Refund Policy</a>
            </div>
        </div>
    </div>
</footer>

<style>
/* --- FOOTER STYLING --- */
.premium-footer {
    background: #070b14;
    position: relative;
    z-index: 2;
    padding-top: 100px;
    color: white;
    overflow: hidden;
}

/* Wave Divider */
.footer-wave {
    position: absolute;
    top: 0; left: 0; width: 100%; overflow: hidden; line-height: 0;
}
.footer-wave svg {
    position: relative; display: block; width: calc(100% + 1.3px); height: 80px;
}
.footer-wave .shape-fill { fill: #0f172a; } /* Matches previous section's dark bg */

.footer-container { padding: 50px 8% 30px; }

.footer-grid {
    display: grid;
    grid-template-columns: 1.5fr 1fr 1fr 1.5fr;
    gap: 50px;
    border-bottom: 1px solid rgba(255,255,255,0.05);
    padding-bottom: 60px;
}

.footer-col h3 {
    font-size: 1.2rem;
    margin-bottom: 25px;
    position: relative;
    display: inline-block;
}
.footer-col h3::after {
    content: ''; position: absolute; left: 0; bottom: -8px;
    width: 30px; height: 2px; background: var(--primary);
}

.footer-logo { font-size: 24px; font-weight: 700; text-decoration: none; color: white; display: block; margin-bottom: 20px; }
.footer-logo span { color: var(--primary); }

.footer-desc { font-size: 0.9rem; opacity: 0.6; line-height: 1.8; margin-bottom: 25px; }

.social-links { display: flex; gap: 15px; }
.social-links a {
    width: 40px; height: 40px; background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.1); border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    color: white; text-decoration: none; transition: 0.3s;
}
.social-links a:hover { background: var(--primary); transform: translateY(-5px); box-shadow: 0 5px 15px rgba(99, 102, 241, 0.4); }

.footer-col ul { list-style: none; padding: 0; }
.footer-col ul li { margin-bottom: 12px; }
.footer-col ul li a { color: #94a3b8; text-decoration: none; transition: 0.3s; font-size: 0.9rem; }
.footer-col ul li a:hover { color: var(--primary); padding-left: 8px; }

/* Newsletter */
.newsletter-form { display: flex; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 5px; }
.newsletter-form input { background: transparent; border: none; padding: 10px 15px; color: white; flex: 1; outline: none; }
.newsletter-form button { background: var(--primary); border: none; color: white; width: 45px; height: 40px; border-radius: 8px; cursor: pointer; transition: 0.3s; }
.newsletter-form button:hover { background: #4f46e5; transform: scale(1.05); }

.payment-partners { margin-top: 30px; display: flex; gap: 20px; font-size: 1.8rem; opacity: 0.2; }

/* Bottom Bar */
.footer-bottom {
    display: flex; justify-content: space-between; align-items: center;
    padding-top: 30px; font-size: 0.8rem; opacity: 0.5;
}
.bottom-links a { color: white; text-decoration: none; margin-left: 20px; }
.bottom-links a:hover { text-decoration: underline; }

/* Responsive Footer */
@media (max-width: 1000px) {
    .footer-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 600px) {
    .footer-grid { grid-template-columns: 1fr; gap: 40px; }
    .footer-bottom { flex-direction: column; text-align: center; gap: 20px; }
}
</style>