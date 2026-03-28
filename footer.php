<?php
$statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
    $footer_copyright = $row['footer_copyright'];
}
?>

<footer class="bg-surfaceDark dark:bg-black text-white w-full pt-24 pb-10 border-t-0 dark:border-t dark:border-slate-800 transition-colors duration-300">
    <div class="grid grid-cols-1 md:grid-cols-12 gap-12 px-6 md:px-12 max-w-[1440px] mx-auto" data-aos="fade-up">
        
        <div class="md:col-span-5 pr-0 md:pr-12">
            <span class="text-2xl font-headline font-black tracking-tight text-white uppercase mb-6 block">
                <?php echo isset($logo) && $logo != '' ? '<img src="assets/uploads/'.$logo.'" class="h-10 filter brightness-0 invert" alt="logo">' : '<span class="text-primary dark:text-indigo-400">E</span>-STORE'; ?>
            </span>
            <p class="text-slate-400 font-body text-base leading-relaxed mb-8 max-w-md">
                Elevating the human experience through precision-selected technology. We curate only the finest electronics for the modern minimalist.
            </p>
            <div class="flex space-x-4">
                <a href="#" class="h-10 w-10 rounded-full bg-slate-800 flex items-center justify-center text-white hover:bg-primary dark:hover:bg-indigo-600 transition-colors"><i class="fa fa-facebook"></i></a>
                <a href="#" class="h-10 w-10 rounded-full bg-slate-800 flex items-center justify-center text-white hover:bg-primary dark:hover:bg-indigo-600 transition-colors"><i class="fa fa-twitter"></i></a>
                <a href="#" class="h-10 w-10 rounded-full bg-slate-800 flex items-center justify-center text-white hover:bg-primary dark:hover:bg-indigo-600 transition-colors"><i class="fa fa-instagram"></i></a>
            </div>
        </div>

        <div class="md:col-span-2">
            <h5 class="font-headline font-bold text-white mb-6 uppercase tracking-widest text-xs">Shop</h5>
            <ul class="space-y-4 font-body text-sm font-medium text-slate-400">
                <li><a class="hover:text-primary dark:hover:text-white transition-colors" href="product-category.php">All Products</a></li>
                <li><a class="hover:text-primary dark:hover:text-white transition-colors" href="#">New Arrivals</a></li>
                <li><a class="hover:text-primary dark:hover:text-white transition-colors" href="#">Featured</a></li>
            </ul>
        </div>

        <div class="md:col-span-2">
            <h5 class="font-headline font-bold text-white mb-6 uppercase tracking-widest text-xs">Support</h5>
            <ul class="space-y-4 font-body text-sm font-medium text-slate-400">
                <li><a class="hover:text-primary dark:hover:text-white transition-colors" href="contact.php">Contact Us</a></li>
                <li><a class="hover:text-primary dark:hover:text-white transition-colors" href="faq.php">FAQ</a></li>
                <li><a class="hover:text-primary dark:hover:text-white transition-colors" href="#">Shipping & Returns</a></li>
            </ul>
        </div>

        <div class="md:col-span-3">
            <h5 class="font-headline font-bold text-white mb-6 uppercase tracking-widest text-xs">Stay Updated</h5>
            <p class="text-slate-400 text-sm mb-4">Join our newsletter for exclusive releases and tech insights.</p>
            <form action="" method="post" class="relative">
                <input type="email" placeholder="Email address..." class="w-full bg-slate-800 dark:bg-slate-900 border border-transparent dark:border-slate-800 rounded-full py-3 px-5 text-sm text-white focus:ring-2 focus:ring-primary dark:focus:ring-indigo-500 outline-none transition-all">
                <button type="submit" class="absolute right-1 top-1 bottom-1 bg-primary hover:bg-primaryHover dark:bg-indigo-600 dark:hover:bg-indigo-500 text-white rounded-full px-4 text-xs font-bold tracking-wider uppercase transition-colors">
                    Join
                </button>
            </form>
        </div>
    </div>
    
    <div class="mt-20 pt-8 border-t border-slate-800 px-6 md:px-12 max-w-[1440px] mx-auto flex flex-col md:flex-row justify-between items-center gap-4">
        <p class="font-headline text-[11px] tracking-[0.2em] uppercase text-slate-500 font-bold">
            <?php echo strip_tags($footer_copyright); ?>
        </p>
        <div class="flex gap-4 text-slate-500 text-sm">
            <a href="#" class="hover:text-white transition-colors">Privacy</a>
            <a href="#" class="hover:text-white transition-colors">Terms</a>
        </div>
    </div>
</footer>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init({ once: true, offset: 50, duration: 800, easing: 'ease-out-cubic' });
</script>

<script>
    var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
    var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

    // Change the icons inside the button based on previous settings
    if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        themeToggleLightIcon.classList.remove('hidden');
    } else {
        themeToggleDarkIcon.classList.remove('hidden');
    }

    var themeToggleBtn = document.getElementById('theme-toggle');

    themeToggleBtn.addEventListener('click', function() {
        themeToggleDarkIcon.classList.toggle('hidden');
        themeToggleLightIcon.classList.toggle('hidden');

        if (localStorage.getItem('color-theme')) {
            if (localStorage.getItem('color-theme') === 'light') {
                document.documentElement.classList.add('dark');
                localStorage.setItem('color-theme', 'dark');
            } else {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('color-theme', 'light');
            }
        } else {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('color-theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('color-theme', 'dark');
            }
        }
    });
</script>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
foreach ($result as $row) { $stripe_public_key = $row['stripe_public_key']; }
?>
<script src="assets/js/jquery-2.2.4.min.js"></script>
<script src="https://js.stripe.com/v2/"></script>
<script src="assets/js/jquery.magnific-popup.min.js"></script>
<script src="assets/js/rating.js"></script>
<script src="assets/js/custom.js"></script>
<script>
	function confirmDelete() { return confirm("Are you sure you want to delete this?"); }
	$(document).ready(function () {
		advFieldsStatus = $('#advFieldsStatus').val();
		$('#paypal_form').hide(); $('#stripe_form').hide(); $('#bank_form').hide();

        $('#advFieldsStatus').on('change',function() {
            advFieldsStatus = $('#advFieldsStatus').val();
            if ( advFieldsStatus == '' ) {
            	$('#paypal_form').hide(); $('#stripe_form').hide(); $('#bank_form').hide();
            } else if ( advFieldsStatus == 'PayPal' ) {
               	$('#paypal_form').show(); $('#stripe_form').hide(); $('#bank_form').hide();
            } else if ( advFieldsStatus == 'Stripe' ) {
               	$('#paypal_form').hide(); $('#stripe_form').show(); $('#bank_form').hide();
            } else if ( advFieldsStatus == 'Bank Deposit' ) {
            	$('#paypal_form').hide(); $('#stripe_form').hide(); $('#bank_form').show();
            }
        });
	});

	$(document).on('submit', '#stripe_form', function () {
        $('#submit-button').prop("disabled", true);
        $("#msg-container").hide();
        Stripe.card.createToken({
            number: $('.card-number').val(), cvc: $('.card-cvc').val(),
            exp_month: $('.card-expiry-month').val(), exp_year: $('.card-expiry-year').val()
        }, stripeResponseHandler);
        return false;
    });
    Stripe.setPublishableKey('<?php echo $stripe_public_key; ?>');
    function stripeResponseHandler(status, response) {
        if (response.error) {
            $('#submit-button').prop("disabled", false);
            $("#msg-container").html('<div style="color: red;border: 1px solid;margin: 10px 0px;padding: 5px;"><strong>Error:</strong> ' + response.error.message + '</div>').show();
        } else {
            var form$ = $("#stripe_form");
            var token = response['id'];
            form$.append("<input type='hidden' name='stripeToken' value='" + token + "' />");
            form$.get(0).submit();
        }
    }
</script>
</body>
</html>