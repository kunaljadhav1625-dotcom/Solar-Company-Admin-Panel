<?php
// includes/footer.php - Common Footer
?>

    </main> <!-- Close main content container -->

    <!-- Footer Section -->
    <footer class="footer" style="background: #1a202c; color: white; padding: 3rem 0 1.5rem; margin-top: 4rem;">
        <div class="container">
            <div class="footer-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; margin-bottom: 2rem;">
                
                <!-- Company Information -->
                <div class="footer-section">
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                        <div style="background: #f7971e; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <span style="font-size: 1.5rem;">☀️</span>
                        </div>
                        <h3 style="color: #f7971e; margin: 0;">Technosky Solar Syatem</h3>
                    </div>
                    <p style="line-height: 1.6; margin-bottom: 1rem;">Leading provider of solar energy solutions, committed to creating a sustainable future through innovative renewable energy technologies.</p>
                    
                    <div class="social-links" style="display: flex; gap: 1rem;">
                        <a href="https://facebook.com/solartechpro" style="color: white; text-decoration: none; transition: color 0.3s;">
                            <i class="fab fa-facebook-f" style="font-size: 1.2rem;"></i>
                        </a>
                        <a href="https://instagram.com/solartechpro" style="color: white; text-decoration: none; transition: color 0.3s;">
                            <i class="fab fa-instagram" style="font-size: 1.2rem;"></i>
                        </a>
                        <a href="https://twitter.com/solartechpro" style="color: white; text-decoration: none; transition: color 0.3s;">
                            <i class="fab fa-twitter" style="font-size: 1.2rem;"></i>
                        </a>
                        <a href="https://linkedin.com/company/solartechpro" style="color: white; text-decoration: none; transition: color 0.3s;">
                            <i class="fab fa-linkedin-in" style="font-size: 1.2rem;"></i>
                        </a>
                        <a href="https://youtube.com/solartechpro" style="color: white; text-decoration: none; transition: color 0.3s;">
                            <i class="fab fa-youtube" style="font-size: 1.2rem;"></i>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="footer-section">
                    <h3 style="color: #f7971e; margin-bottom: 1rem; border-bottom: 2px solid #f7971e; padding-bottom: 0.5rem;">Quick Links</h3>
                    <ul style="list-style: none; padding: 0;">
                        <li style="margin-bottom: 0.5rem;">
                            <a href="<?php echo $current_page === 'admin-dashboard.php' ? '#' : './admin-dashboard.php'; ?>" style="color: #cbd5e0; text-decoration: none; transition: color 0.3s; display: flex; align-items: center; gap: 0.5rem;">
                                <i class="fas fa-chevron-right" style="color: #f7971e; font-size: 0.8rem;"></i>
                                Dashboard
                            </a>
                        </li>
                        <li style="margin-bottom: 0.5rem;">
                            <a href="<?php echo strpos($_SERVER['PHP_SELF'], '/public/') !== false ? './products.php' : './public/products.php'; ?>" style="color: #cbd5e0; text-decoration: none; transition: color 0.3s; display: flex; align-items: center; gap: 0.5rem;">
                                <i class="fas fa-chevron-right" style="color: #f7971e; font-size: 0.8rem;"></i>
                                Products Management
                            </a>
                        </li>
                        <li style="margin-bottom: 0.5rem;">
                            <a href="<?php echo strpos($_SERVER['PHP_SELF'], '/public/') !== false ? './projects.php' : './public/projects.php'; ?>" style="color: #cbd5e0; text-decoration: none; transition: color 0.3s; display: flex; align-items: center; gap: 0.5rem;">
                                <i class="fas fa-chevron-right" style="color: #f7971e; font-size: 0.8rem;"></i>
                                Projects
                            </a>
                        </li>
                        <li style="margin-bottom: 0.5rem;">
                            <a href="<?php echo strpos($_SERVER['PHP_SELF'], '/public/') !== false ? './generate_bill.php' : './public/generate_bill.php'; ?>" style="color: #cbd5e0; text-decoration: none; transition: color 0.3s; display: flex; align-items: center; gap: 0.5rem;">
                                <i class="fas fa-chevron-right" style="color: #f7971e; font-size: 0.8rem;"></i>
                                Generate Bill
                            </a>
                        </li>
                        <li style="margin-bottom: 0.5rem;">
                            <a href="<?php echo strpos($_SERVER['PHP_SELF'], '/public/') !== false ? './contact.php' : './public/contact.php'; ?>" style="color: #cbd5e0; text-decoration: none; transition: color 0.3s; display: flex; align-items: center; gap: 0.5rem;">
                                <i class="fas fa-chevron-right" style="color: #f7971e; font-size: 0.8rem;"></i>
                                Contact Us
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Contact Information -->
                <div class="footer-section">
                    <h3 style="color: #f7971e; margin-bottom: 1rem; border-bottom: 2px solid #f7971e; padding-bottom: 0.5rem;">Contact Info</h3>
                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        <div style="display: flex; align-items: start; gap: 0.8rem;">
                            <i class="fas fa-map-marker-alt" style="color: #f7971e; margin-top: 0.2rem;"></i>
                            <div>
                                <strong>Address:</strong><br>
                                Adinatha Complex, Lonara Galli,<br>
                                Ravivarpeth gala no 1,<br>
                                Satara, Maharashtra<br>
                                PIN: 415002
                            </div>
                        </div>
                        <div style="display: flex; align-items: start; gap: 0.8rem;">
                            <i class="fas fa-phone" style="color: #f7971e; margin-top: 0.2rem;"></i>
                            <div>
                                <strong>Phone:</strong><br>
                                +91-8149079292
                            </div>
                        </div>
                        <div style="display: flex; align-items: start; gap: 0.8rem;">
                            <i class="fas fa-envelope" style="color: #f7971e; margin-top: 0.2rem;"></i>
                            <div>
                                <strong>Email:</strong><br>
                                technoskysolar@gmail.com<br>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Business Hours -->
                <div class="footer-section">
                    <h3 style="color: #f7971e; margin-bottom: 1rem; border-bottom: 2px solid #f7971e; padding-bottom: 0.5rem;">Business Hours</h3>
                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                        <div style="display: flex; justify-content: space-between; padding: 0.3rem 0; border-bottom: 1px solid #2d3748;">
                            <span>Monday - Friday:</span>
                            <span style="color: #f7971e;">9:00 AM - 6:00 PM</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 0.3rem 0; border-bottom: 1px solid #2d3748;">
                            <span>Saturday:</span>
                            <span style="color: #f7971e;">10:00 AM - 4:00 PM</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding: 0.3rem 0;">
                            <span>Sunday:</span>
                            <span style="color: #f7971e;">Closed</span>
                        </div>
                    </div>
                    
                    <div style="margin-top: 1.5rem; padding: 1rem; background: rgba(247, 151, 30, 0.1); border-radius: 5px;">
                        <p style="margin: 0; font-size: 0.9rem; color: #f7971e;">
                            <i class="fas fa-clock"></i> 24/7 Emergency Support Available
                        </p>
                    </div>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="footer-bottom" style="text-align: center; padding-top: 2rem; margin-top: 2rem; border-top: 1px solid #2d3748;">
                <p style="margin: 0; opacity: 0.8; font-size: 0.9rem;">
                    &copy; 2024 Technosky Solar System. All rights reserved. | 
                    <a href="#" style="color: #f7971e; text-decoration: none;">Privacy Policy</a> | 
                    <a href="#" style="color: #f7971e; text-decoration: none;">Terms of Service</a> |
                    <a href="#" style="color: #f7971e; text-decoration: none;">Sitemap</a>
                </p>
                <p style="margin: 0.5rem 0 0 0; opacity: 0.6; font-size: 0.8rem;">
                    <i class="fas fa-heart" style="color: #e74c3c;"></i> Powered by Clean Energy ☀️ - Building a Sustainable Future
                </p>
            </div>
        </div>
    </footer>

    <!-- JavaScript for Navigation -->
    <script>
        // Mobile menu toggle function
        function toggleMobileMenu() {
            const nav = document.getElementById('mainNav');
            nav.classList.toggle('mobile-active');
        }

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const nav = document.getElementById('mainNav');
            const toggleBtn = document.querySelector('.mobile-menu-toggle');
            
            if (!nav.contains(event.target) && !toggleBtn.contains(event.target)) {
                nav.classList.remove('mobile-active');
            }
        });

        // Active page highlighting
        document.addEventListener('DOMContentLoaded', function() {
            const currentPage = '<?php echo $current_page; ?>';
            const navLinks = document.querySelectorAll('.nav-link');
            
            navLinks.forEach(link => {
                if (link.getAttribute('href').includes(currentPage)) {
                    link.classList.add('active');
                }
            });
            
            // Add loading animation to navigation
            const navItems = document.querySelectorAll('.nav-item');
            navItems.forEach((item, index) => {
                item.style.animationDelay = (index * 0.1) + 's';
                item.style.animation = 'fadeInUp 0.5s ease both';
            });
        });

        // Add CSS animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(10px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            
            .nav-link {
                position: relative;
                overflow: hidden;
            }
            
            .nav-link::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(247, 151, 30, 0.1), transparent);
                transition: left 0.5s;
            }
            
            .nav-link:hover::before {
                left: 100%;
            }
        `;
        document.head.appendChild(style);

        // Notification bell animation
        const notificationBell = document.querySelector('.notification-bell');
        notificationBell.addEventListener('click', function() {
            this.style.transform = 'scale(1.1)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 300);
        });

        // Auto-hide alerts
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.opacity = '0';
                setTimeout(() => {
                    alert.style.display = 'none';
                }, 300);
            }, 5000);
        });
    </script>