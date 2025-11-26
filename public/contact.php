<?php
// public/contact.php - Contact Page
require_once '../includes/auth.php';
require_once '../includes/db.php';
requireLogin();

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    
    // Here you can add code to save the message to database or send email
    // For now, we'll just show a success message
    
    $success = "Thank you for your message! We'll get back to you soon.";
}
?>

<?php include '../includes/headers.php'; ?>

<div class="container">
    <h1>Contact Us</h1>
    
    <?php if ($success): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <?php if ($error): ?>
    <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-top: 2rem;">
        <!-- Contact Information -->
        <div class="card">
            <h2 style="color: #1e3c72; margin-bottom: 1rem;">Get in Touch</h2>
            
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                <div style="display: flex; align-items: start; gap: 1rem;">
                    <div style="background: #1e3c72; color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div>
                        <h3 style="margin: 0; color: #2a5298;">Address</h3>
                        <p style="margin: 0.5rem 0;">Adinatha Complex,Lonara Galli,<br>Ravivarpeth gala no 1,<br>pin :- 415002
                    </div>
                </div>
                
                <div style="display: flex; align-items: start; gap: 1rem;">
                    <div style="background: #f7971e; color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div>
                        <h3 style="margin: 0; color: #2a5298;">Phone</h3>
                        <p style="margin: 0.5rem 0;">+91-8149079292<br>+91-9130515276</p>
                    </div>
                </div>
                
                <div style="display: flex; align-items: start; gap: 1rem;">
                    <div style="background: #28a745; color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div>
                        <h3 style="margin: 0; color: #2a5298;">Email</h3>
                        <p style="margin: 0.5rem 0;">technoskysolar@gmail.com<br>
                    </div>
                </div>
                
                <div style="display: flex; align-items: start; gap: 1rem;">
                    <div style="background: #6f42c1; color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div>
                        <h3 style="margin: 0; color: #2a5298;">Business Hours</h3>
                        <p style="margin: 0.5rem 0;">
                            Monday - Friday: 9:00 AM - 6:00 PM<br>
                            Saturday: 10:00 AM - 4:00 PM<br>
                            Sunday: Closed
                        </p>
                    </div>
                </div>
            </div>
            
            <div style="margin-top: 2rem;">
                <h3 style="color: #2a5298;">Follow Us</h3>
                <div style="display: flex; gap: 1rem;">
                    <a href="#" style="color: #1e3c72; font-size: 1.5rem;"><i class="fab fa-facebook"></i></a>
                    <a href="#" style="color: #1e3c72; font-size: 1.5rem;"><i class="fab fa-twitter"></i></a>
                    <a href="#" style="color: #1e3c72; font-size: 1.5rem;"><i class="fab fa-instagram"></i></a>
                    <a href="#" style="color: #1e3c72; font-size: 1.5rem;"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="card">
            <h2 style="color: #1e3c72; margin-bottom: 1rem;">Send us a Message</h2>
            
            <form method="POST">
                <div class="form-group">
                    <label class="form-label">Your Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Subject</label>
                    <input type="text" name="subject" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Message</label>
                    <textarea name="message" class="form-control" rows="5" required></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i> Send Message
                </button>
            </form>
        </div>
    </div>

    <!-- Map Section -->
    <div class="card" style="margin-top: 2rem;">
        <h2 style="color: #1e3c72; margin-bottom: 1rem;">Find Us</h2>
        <div style="background: #f8f9fa; padding: 2rem; text-align: center; border-radius: 10px;">
            <div style="background: linear-gradient(45deg, #1e3c72, #2a5298); color: white; padding: 3rem; border-radius: 10px;">
                <i class="fas fa-map-marked-alt" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                <h3>Technosky Solar Syatem Headquarters</h3>
                <p>Adinatha Complex,Lonara Galli,<br>Ravivarpeth gala no 1,<br>pin :- 415002</p>
                <p>📍 Located in the heart of Satara</p>
            </div>
            <div style="margin-top: 1rem;">
                <p><strong>Landmarks:</strong>Adinatha Complex, Ravivarpeth gala no 1</p>
                <p><strong>Parking:</strong> Available for customers</p>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>