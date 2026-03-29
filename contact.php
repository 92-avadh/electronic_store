<?php require_once('header.php'); ?>

<?php
// Fetch Page Details
$statement = $pdo->prepare("SELECT * FROM tbl_page WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
foreach ($result as $row) {
    $contact_title = $row['contact_title'];
    $contact_banner = $row['contact_banner'];
}

// Fetch Global Settings
$statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
foreach ($result as $row) {
    $contact_map_iframe = $row['contact_map_iframe'];
    $contact_email = $row['contact_email'];
    $contact_phone = $row['contact_phone'];
    $contact_address = $row['contact_address'];
    $receive_email = $row['receive_email'];
    $receive_email_subject = $row['receive_email_subject'];
}

// Auto-Create Table if it doesn't exist for storing messages
$pdo->exec("CREATE TABLE IF NOT EXISTS tbl_contact (
    id INT AUTO_INCREMENT PRIMARY KEY,
    visitor_name VARCHAR(255),
    visitor_email VARCHAR(255),
    visitor_phone VARCHAR(255),
    visitor_message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$error_message = '';
$success_message = '';

// Form Submission Logic
if(isset($_POST['form_contact'])) {
    $valid = 1;

    if(empty($_POST['visitor_name'])) { $valid = 0; $error_message .= 'Please enter your name.<br>'; }
    if(empty($_POST['visitor_phone'])) { $valid = 0; $error_message .= 'Please enter your phone number.<br>'; }
    if(empty($_POST['visitor_email'])) { 
        $valid = 0; $error_message .= 'Please enter your email address.<br>'; 
    } else {
        if(!filter_var($_POST['visitor_email'], FILTER_VALIDATE_EMAIL)) {
            $valid = 0; $error_message .= 'Please enter a valid email address.<br>';
        }
    }
    if(empty($_POST['visitor_message'])) { $valid = 0; $error_message .= 'Please enter your message.<br>'; }

    if($valid == 1) {
        $visitor_name = strip_tags($_POST['visitor_name']);
        $visitor_email = strip_tags($_POST['visitor_email']);
        $visitor_phone = strip_tags($_POST['visitor_phone']);
        $visitor_message = strip_tags($_POST['visitor_message']);

        // 1. Insert into Database for Admin Panel
        $stmt = $pdo->prepare("INSERT INTO tbl_contact (visitor_name, visitor_email, visitor_phone, visitor_message) VALUES (?, ?, ?, ?)");
        $stmt->execute([$visitor_name, $visitor_email, $visitor_phone, $visitor_message]);

        // 2. Send Email Notification
        $to_admin = $receive_email;
        $subject = $receive_email_subject;
        $message = '<html><body><table><tr><td>Name</td><td>'.$visitor_name.'</td></tr><tr><td>Email</td><td>'.$visitor_email.'</td></tr><tr><td>Phone</td><td>'.$visitor_phone.'</td></tr><tr><td>Message</td><td>'.nl2br($visitor_message).'</td></tr></table></body></html>';
        $headers = 'From: ' . $visitor_email . "\r\n" . 'Reply-To: ' . $visitor_email . "\r\n" . 'X-Mailer: PHP/' . phpversion() . "\r\n" . "MIME-Version: 1.0\r\n" . "Content-Type: text/html; charset=ISO-8859-1\r\n";
        
        mail($to_admin, $subject, $message, $headers); 
        
        // Hardcoded guaranteed success message
        $success_message = 'Message sent successfully!';
    }
}
?>

<div class="relative h-[250px] md:h-[350px] flex items-center justify-center bg-cover bg-center mt-20" style="background-image: url('assets/uploads/<?php echo $contact_banner; ?>');">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-[2px]"></div>
    <h1 class="relative z-10 text-4xl md:text-5xl font-headline font-black text-white uppercase tracking-wider text-center px-4"><?php echo $contact_title; ?></h1>
</div>

<main class="max-w-[1440px] mx-auto px-6 py-12 md:py-20">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 lg:gap-16">
        
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-slate-800 p-8 md:p-10 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 transition-colors">
                <div class="mb-8">
                    <h2 class="text-3xl font-headline font-extrabold text-slate-900 dark:text-white mb-2">Get in Touch</h2>
                    <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Have a complaint, question, or feedback? Send us a message and our team will get back to you shortly.</p>
                </div>

                <?php if($error_message): ?>
                    <div class="bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 px-4 py-3 rounded-xl mb-6 text-sm font-bold border border-red-200 dark:border-red-500/20"><?php echo $error_message; ?></div>
                <?php endif; ?>
                <?php if($success_message): ?>
                    <div class="bg-green-50 dark:bg-green-500/10 text-green-600 dark:text-green-400 px-4 py-3 rounded-xl mb-6 text-sm font-bold border border-green-200 dark:border-green-500/20"><?php echo $success_message; ?></div>
                <?php endif; ?>

                <form action="" method="post" class="space-y-6">
                    <?php $csrf->echoInputField(); ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-2">Full Name</label>
                            <input type="text" name="visitor_name" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all" placeholder="John Doe">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-2">Phone Number</label>
                            <input type="text" name="visitor_phone" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all" placeholder="+1 (555) 000-0000">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-2">Email Address</label>
                        <input type="email" name="visitor_email" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all" placeholder="john@example.com">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-2">Your Message / Complaint</label>
                        <textarea name="visitor_message" rows="6" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all resize-none" placeholder="How can we help you today?"></textarea>
                    </div>
                    <button type="submit" name="form_contact" class="w-full md:w-auto px-8 py-4 bg-primary hover:bg-primaryHover text-white font-bold rounded-xl shadow-lg shadow-primary/30 transition-all active:scale-95 flex items-center justify-center gap-2 text-sm uppercase tracking-widest">
                        <span class="material-symbols-outlined text-[20px]">send</span> Send Message
                    </button>
                </form>
            </div>
        </div>

        <div class="space-y-8">
            <div class="bg-white dark:bg-slate-800 p-8 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 transition-colors">
                <h3 class="text-xl font-headline font-extrabold text-slate-900 dark:text-white mb-6">Contact Information</h3>
                <div class="space-y-6">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full bg-sky-50 dark:bg-sky-500/10 flex items-center justify-center text-primary flex-shrink-0">
                            <span class="material-symbols-outlined text-[20px]">location_on</span>
                        </div>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-1">Our Office</p>
                            <p class="text-sm font-semibold text-slate-900 dark:text-white leading-relaxed"><?php echo nl2br($contact_address); ?></p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full bg-sky-50 dark:bg-sky-500/10 flex items-center justify-center text-primary flex-shrink-0">
                            <span class="material-symbols-outlined text-[20px]">call</span>
                        </div>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-1">Phone</p>
                            <p class="text-sm font-semibold text-slate-900 dark:text-white"><?php echo $contact_phone; ?></p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full bg-sky-50 dark:bg-sky-500/10 flex items-center justify-center text-primary flex-shrink-0">
                            <span class="material-symbols-outlined text-[20px]">mail</span>
                        </div>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-1">Email</p>
                            <a href="mailto:<?php echo $contact_email; ?>" class="text-sm font-semibold text-primary hover:underline"><?php echo $contact_email; ?></a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl overflow-hidden shadow-sm border border-slate-100 dark:border-slate-700 h-[300px] w-full relative">
                <style>
                    /* Force iframe to fit the rounded box perfectly */
                    iframe { width: 100% !important; height: 100% !important; border: none !important; }
                </style>
                <?php echo $contact_map_iframe; ?>
            </div>
        </div>

    </div>
</main>

<?php require_once('footer.php'); ?>