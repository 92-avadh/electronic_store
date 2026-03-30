<?php require_once('header.php'); ?>

<main class="pt-20 bg-white dark:bg-slate-900">

<!-- TEAM SECTION -->
<section class="py-24 bg-[#faf8ff] dark:bg-slate-900">
    <div class="max-w-[1400px] mx-auto px-6 md:px-12">
        
        <div class="text-center max-w-2xl mx-auto mb-16">
            <h2 class="text-4xl font-extrabold text-slate-900 dark:text-white">Meet the Team</h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">

            <!-- YOU -->
            <div class="rounded-2xl overflow-hidden bg-white dark:bg-slate-800 shadow-lg">
                <div class="h-64">
                    <img src="assets/uploads/team1.jpg" class="w-full h-full object-cover">
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white">Vivek Ghoghari</h3>
                    <p class="text-sm text-primary">Lead Developer</p>
                </div>
            </div>

            <!-- MEMBER 2 -->
            <div class="rounded-2xl overflow-hidden bg-white dark:bg-slate-800 shadow-lg">
                <div class="h-64">
                    <img src="assets/uploads/team2.jpg" class="w-full h-full object-cover">
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white">Yug Ghantala</h3>
                    <p class="text-sm text-teal-500">backend Specialist</p>
                </div>
            </div>

            <!-- MEMBER 3 -->
            <div class="rounded-2xl overflow-hidden bg-white dark:bg-slate-800 shadow-lg">
                <div class="h-64">
                    <img src="assets/uploads/team3.jpg" class="w-full h-full object-cover">
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white">Saurav Hadvani</h3>
                    <p class="text-sm text-purple-500">UI/UX designer</p>
                </div>
            </div>

            <!-- MEMBER 4 -->
            <div class="rounded-2xl overflow-hidden bg-white dark:bg-slate-800 shadow-lg">
                <div class="h-64">
                    <img src="assets/uploads/team4.jpg" class="w-full h-full object-cover">
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white">Mohit Zinjala</h3>
                    <p class="text-sm text-orange-500">Frontend Developer</p>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ABOUT IMAGE SECTION -->
<section class="py-20 bg-white dark:bg-slate-800">
    <div class="max-w-[1200px] mx-auto px-6 md:px-12 flex flex-col lg:flex-row items-center gap-16">
        
        <div class="w-full lg:w-1/2">
            <img src="assets/uploads/about-banner.jpg" class="rounded-3xl shadow-xl w-full">
        </div>

        <div class="w-full lg:w-1/2">
            <h2 class="text-3xl font-bold text-slate-900 dark:text-white mb-4">
                Driven by innovation
            </h2>
            <p class="text-slate-500 dark:text-slate-400">
                Electronic store started as a small retailer and grew into a trusted digital platform.
            </p>
        </div>

    </div>
</section>

</main>

<?php require_once('footer.php'); ?>