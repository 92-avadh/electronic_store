<?php require_once('header.php'); ?>

<?php
// Fetch About Us content if you manage it dynamically from the admin panel
// $statement = $pdo->prepare("SELECT * FROM tbl_page WHERE id=1");
// $statement->execute();
// $result = $statement->fetch(PDO::FETCH_ASSOC);
// $about_title = $result['about_title'];
// $about_content = $result['about_content'];
?>

<main class="pt-20 bg-white dark:bg-slate-900 transition-colors duration-300">
    
    <section class="relative pt-20 pb-20 md:pt-32 md:pb-32 overflow-hidden">
        <div class="absolute inset-0 bg-[#0052CC] dark:bg-slate-950 z-0"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-transparent to-black/50 z-0"></div>
        
        <div class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-1/3 opacity-10 pointer-events-none z-0">
            <span class="material-symbols-outlined text-[400px] md:text-[600px] text-white">memory</span>
        </div>

        <div class="relative z-10 max-w-[1200px] mx-auto px-6 md:px-12 text-center md:text-left">
            <span class="inline-block py-1 px-3 rounded-full bg-white/20 text-white border border-white/30 backdrop-blur-md text-[10px] font-black uppercase tracking-[0.2em] mb-6">Our Mission</span>
            <h1 class="text-5xl md:text-7xl font-extrabold font-headline text-white tracking-tight mb-6 leading-tight max-w-4xl">
                Engineering <br/><span class="text-[#9cf0ff]">tomorrow's</span> workspace.
            </h1>
            <p class="text-lg md:text-xl text-blue-100 dark:text-slate-300 max-w-2xl leading-relaxed mb-10 font-medium">
                Silicon Slate was founded on a simple principle: professionals deserve hardware that matches their ambition. We curate and deliver precision engineering for the modern creator.
            </p>
            <div class="flex flex-col sm:flex-row items-center md:justify-start justify-center gap-4">
                <a href="product.php" class="w-full sm:w-auto px-8 py-4 bg-white text-[#0052CC] dark:text-slate-900 rounded-xl font-bold uppercase tracking-widest text-sm hover:scale-105 transition-transform shadow-xl text-center">Explore Collection</a>
                <a href="contact.php" class="w-full sm:w-auto px-8 py-4 bg-transparent text-white border border-white/30 rounded-xl font-bold uppercase tracking-widest text-sm hover:bg-white/10 transition-colors text-center">Contact Support</a>
            </div>
        </div>
    </section>

    <section class="py-20 md:py-32 bg-white dark:bg-slate-800 transition-colors duration-200">
        <div class="max-w-[1200px] mx-auto px-6 md:px-12">
            
            <div class="text-center max-w-3xl mx-auto mb-16 md:mb-24">
                <h2 class="text-3xl md:text-4xl font-extrabold font-headline text-slate-900 dark:text-white tracking-tight mb-4">The Silicon Slate Standard</h2>
                <p class="text-slate-500 dark:text-slate-400 font-medium text-lg">We don't just sell electronics. We provide the tools that empower your best work. Every item in our catalog meets our strict three-pillar standard.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-12">
                <div class="bg-slate-50 dark:bg-slate-900 p-8 md:p-10 rounded-3xl border border-slate-100 dark:border-slate-700 hover:shadow-xl hover:border-[#0052CC]/30 dark:hover:border-[#4da3ff]/30 transition-all duration-300 group">
                    <div class="w-16 h-16 rounded-2xl bg-[#0052CC]/10 dark:bg-[#0052CC]/20 text-[#0052CC] dark:text-[#4da3ff] flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-3xl" style="font-variation-settings: 'FILL' 1;">architecture</span>
                    </div>
                    <h3 class="text-xl font-bold font-headline text-slate-900 dark:text-white mb-3">Precision Curation</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed">We sift through thousands of components to offer only the absolute best. If it's in our store, it has passed rigorous quality assurance testing.</p>
                </div>

                <div class="bg-slate-50 dark:bg-slate-900 p-8 md:p-10 rounded-3xl border border-slate-100 dark:border-slate-700 hover:shadow-xl hover:border-[#0052CC]/30 dark:hover:border-[#4da3ff]/30 transition-all duration-300 group">
                    <div class="w-16 h-16 rounded-2xl bg-[#0052CC]/10 dark:bg-[#0052CC]/20 text-[#0052CC] dark:text-[#4da3ff] flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-3xl" style="font-variation-settings: 'FILL' 1;">speed</span>
                    </div>
                    <h3 class="text-xl font-bold font-headline text-slate-900 dark:text-white mb-3">Peak Performance</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed">Your time is valuable. We focus on high-yield, high-efficiency hardware that eliminates bottlenecks and accelerates your daily workflow.</p>
                </div>

                <div class="bg-slate-50 dark:bg-slate-900 p-8 md:p-10 rounded-3xl border border-slate-100 dark:border-slate-700 hover:shadow-xl hover:border-[#0052CC]/30 dark:hover:border-[#4da3ff]/30 transition-all duration-300 group">
                    <div class="w-16 h-16 rounded-2xl bg-[#0052CC]/10 dark:bg-[#0052CC]/20 text-[#0052CC] dark:text-[#4da3ff] flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-3xl" style="font-variation-settings: 'FILL' 1;">support_agent</span>
                    </div>
                    <h3 class="text-xl font-bold font-headline text-slate-900 dark:text-white mb-3">Elite Support</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed">Buying premium hardware deserves premium backing. Our Slate Care+ team provides priority technical assistance whenever you need it.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-24 bg-[#faf8ff] dark:bg-slate-900 border-t border-slate-100 dark:border-slate-800 transition-colors duration-300">
        <div class="max-w-[1400px] mx-auto px-6 md:px-12">
            
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span class="inline-block py-1 px-3 rounded-full bg-[#0052CC]/10 dark:bg-[#0052CC]/20 text-[#0052CC] dark:text-[#4da3ff] text-[10px] font-black uppercase tracking-[0.2em] mb-4">The Architects</span>
                <h2 class="text-4xl md:text-5xl font-extrabold font-headline text-slate-900 dark:text-white tracking-tight mb-4">Meet the Developers</h2>
                <p class="text-slate-500 dark:text-slate-400 font-medium text-sm md:text-base">The visionary minds engineering the future of the Silicon Slate ecosystem. Built with passion, precision, and thousands of lines of code.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">

                <div class="group relative rounded-[2rem] overflow-hidden bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 shadow-sm hover:shadow-2xl hover:-translate-y-3 transition-all duration-500">
                    <div class="absolute inset-0 bg-gradient-to-b from-[#0052CC]/0 to-[#0052CC]/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>
                    <div class="relative h-72 overflow-hidden bg-slate-100 dark:bg-slate-900">
                        <img src="https://images.unsplash.com/photo-1568602471122-7832951cc4c5?q=80&w=600&auto=format&fit=crop" alt="Avadh Dhameliya" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-out">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/20 to-transparent"></div>
                        <div class="absolute bottom-4 left-6 flex gap-2">
                            <span class="px-2.5 py-1 bg-white/20 backdrop-blur-md text-white text-[9px] font-black uppercase tracking-widest rounded-md border border-white/30">System Admin</span>
                        </div>
                    </div>
                    <div class="p-6 relative z-10">
                        <h3 class="font-headline text-xl font-black text-slate-900 dark:text-white mb-1 group-hover:text-[#0052CC] dark:group-hover:text-[#4da3ff] transition-colors">Avadh Dhameliya</h3>
                        <p class="text-xs font-bold text-[#0052CC] dark:text-[#4da3ff] uppercase tracking-widest mb-4">Lead Architect & Founder</p>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mb-6 line-clamp-2">Mastermind behind the Silicon Slate database architecture and premium dark-mode UI/UX.</p>
                        <div class="flex items-center gap-3 pt-4 border-t border-slate-100 dark:border-slate-700">
                            <a href="#" class="w-9 h-9 rounded-full bg-slate-50 dark:bg-slate-900 flex items-center justify-center text-slate-400 hover:bg-[#0052CC] hover:text-white dark:hover:bg-[#4da3ff] dark:hover:text-slate-900 transition-colors border border-slate-200 dark:border-slate-700 hover:border-transparent">
                                <span class="material-symbols-outlined text-[18px]">language</span>
                            </a>
                            <a href="#" class="w-9 h-9 rounded-full bg-slate-50 dark:bg-slate-900 flex items-center justify-center text-slate-400 hover:bg-[#0052CC] hover:text-white dark:hover:bg-[#4da3ff] dark:hover:text-slate-900 transition-colors border border-slate-200 dark:border-slate-700 hover:border-transparent">
                                <span class="material-symbols-outlined text-[18px]">terminal</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="group relative rounded-[2rem] overflow-hidden bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 shadow-sm hover:shadow-2xl hover:-translate-y-3 transition-all duration-500">
                    <div class="absolute inset-0 bg-gradient-to-b from-teal-500/0 to-teal-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>
                    <div class="relative h-72 overflow-hidden bg-slate-100 dark:bg-slate-900">
                        <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=600&auto=format&fit=crop" alt="Frontend Dev" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-out">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/20 to-transparent"></div>
                        <div class="absolute bottom-4 left-6 flex gap-2">
                            <span class="px-2.5 py-1 bg-white/20 backdrop-blur-md text-white text-[9px] font-black uppercase tracking-widest rounded-md border border-white/30">Tailwind CSS</span>
                        </div>
                    </div>
                    <div class="p-6 relative z-10">
                        <h3 class="font-headline text-xl font-black text-slate-900 dark:text-white mb-1 group-hover:text-teal-600 dark:group-hover:text-teal-400 transition-colors">Marcus Chen</h3>
                        <p class="text-xs font-bold text-teal-600 dark:text-teal-400 uppercase tracking-widest mb-4">Frontend Visionary</p>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mb-6 line-clamp-2">Obsessed with pixel-perfect designs, fluid animations, and lightning-fast user interfaces.</p>
                        <div class="flex items-center gap-3 pt-4 border-t border-slate-100 dark:border-slate-700">
                            <a href="#" class="w-9 h-9 rounded-full bg-slate-50 dark:bg-slate-900 flex items-center justify-center text-slate-400 hover:bg-teal-600 hover:text-white dark:hover:bg-teal-400 dark:hover:text-slate-900 transition-colors border border-slate-200 dark:border-slate-700 hover:border-transparent">
                                <span class="material-symbols-outlined text-[18px]">design_services</span>
                            </a>
                            <a href="#" class="w-9 h-9 rounded-full bg-slate-50 dark:bg-slate-900 flex items-center justify-center text-slate-400 hover:bg-teal-600 hover:text-white dark:hover:bg-teal-400 dark:hover:text-slate-900 transition-colors border border-slate-200 dark:border-slate-700 hover:border-transparent">
                                <span class="material-symbols-outlined text-[18px]">terminal</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="group relative rounded-[2rem] overflow-hidden bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 shadow-sm hover:shadow-2xl hover:-translate-y-3 transition-all duration-500">
                    <div class="absolute inset-0 bg-gradient-to-b from-purple-500/0 to-purple-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>
                    <div class="relative h-72 overflow-hidden bg-slate-100 dark:bg-slate-900">
                        <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=600&auto=format&fit=crop" alt="Backend Dev" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-out">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/20 to-transparent"></div>
                        <div class="absolute bottom-4 left-6 flex gap-2">
                            <span class="px-2.5 py-1 bg-white/20 backdrop-blur-md text-white text-[9px] font-black uppercase tracking-widest rounded-md border border-white/30">PHP / MySQL</span>
                        </div>
                    </div>
                    <div class="p-6 relative z-10">
                        <h3 class="font-headline text-xl font-black text-slate-900 dark:text-white mb-1 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">Elena Rodriguez</h3>
                        <p class="text-xs font-bold text-purple-600 dark:text-purple-400 uppercase tracking-widest mb-4">Backend Ninja</p>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mb-6 line-clamp-2">Ensuring secure transactions, zero downtime, and rock-solid server performance.</p>
                        <div class="flex items-center gap-3 pt-4 border-t border-slate-100 dark:border-slate-700">
                            <a href="#" class="w-9 h-9 rounded-full bg-slate-50 dark:bg-slate-900 flex items-center justify-center text-slate-400 hover:bg-purple-600 hover:text-white dark:hover:bg-purple-400 dark:hover:text-slate-900 transition-colors border border-slate-200 dark:border-slate-700 hover:border-transparent">
                                <span class="material-symbols-outlined text-[18px]">database</span>
                            </a>
                            <a href="#" class="w-9 h-9 rounded-full bg-slate-50 dark:bg-slate-900 flex items-center justify-center text-slate-400 hover:bg-purple-600 hover:text-white dark:hover:bg-purple-400 dark:hover:text-slate-900 transition-colors border border-slate-200 dark:border-slate-700 hover:border-transparent">
                                <span class="material-symbols-outlined text-[18px]">terminal</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="group relative rounded-[2rem] overflow-hidden bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 shadow-sm hover:shadow-2xl hover:-translate-y-3 transition-all duration-500">
                    <div class="absolute inset-0 bg-gradient-to-b from-orange-500/0 to-orange-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>
                    <div class="relative h-72 overflow-hidden bg-slate-100 dark:bg-slate-900">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=600&auto=format&fit=crop" alt="Security Expert" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-out grayscale group-hover:grayscale-0">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/20 to-transparent"></div>
                        <div class="absolute bottom-4 left-6 flex gap-2">
                            <span class="px-2.5 py-1 bg-white/20 backdrop-blur-md text-white text-[9px] font-black uppercase tracking-widest rounded-md border border-white/30">Cyber Sec</span>
                        </div>
                    </div>
                    <div class="p-6 relative z-10">
                        <h3 class="font-headline text-xl font-black text-slate-900 dark:text-white mb-1 group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors">Sarah Jenkins</h3>
                        <p class="text-xs font-bold text-orange-600 dark:text-orange-400 uppercase tracking-widest mb-4">Security Analyst</p>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mb-6 line-clamp-2">Defending customer data with enterprise-grade encryption and bulletproof firewall rules.</p>
                        <div class="flex items-center gap-3 pt-4 border-t border-slate-100 dark:border-slate-700">
                            <a href="#" class="w-9 h-9 rounded-full bg-slate-50 dark:bg-slate-900 flex items-center justify-center text-slate-400 hover:bg-orange-600 hover:text-white dark:hover:bg-orange-400 dark:hover:text-slate-900 transition-colors border border-slate-200 dark:border-slate-700 hover:border-transparent">
                                <span class="material-symbols-outlined text-[18px]">security</span>
                            </a>
                            <a href="#" class="w-9 h-9 rounded-full bg-slate-50 dark:bg-slate-900 flex items-center justify-center text-slate-400 hover:bg-orange-600 hover:text-white dark:hover:bg-orange-400 dark:hover:text-slate-900 transition-colors border border-slate-200 dark:border-slate-700 hover:border-transparent">
                                <span class="material-symbols-outlined text-[18px]">terminal</span>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="py-20 md:py-32 bg-white dark:bg-slate-800 border-t border-slate-100 dark:border-slate-700 transition-colors duration-200">
        <div class="max-w-[1200px] mx-auto px-6 md:px-12 flex flex-col lg:flex-row items-center gap-16">
            
            <div class="w-full lg:w-1/2 relative">
                <div class="aspect-square bg-slate-200 dark:bg-slate-800 rounded-[3rem] overflow-hidden relative z-10 border border-slate-200 dark:border-slate-700 shadow-2xl flex items-center justify-center">
                    <span class="material-symbols-outlined text-[120px] text-slate-300 dark:text-slate-600">developer_board</span>
                    <img src="assets/uploads/about-banner.jpg" onerror="this.style.display='none'" class="absolute inset-0 w-full h-full object-cover mix-blend-multiply dark:mix-blend-normal">
                </div>
                <div class="absolute -bottom-6 -right-6 w-48 h-48 bg-[#0052CC] rounded-full blur-3xl opacity-20 z-0 pointer-events-none"></div>
            </div>

            <div class="w-full lg:w-1/2 space-y-8">
                <span class="text-[10px] font-black uppercase tracking-[0.2em] text-[#0052CC] dark:text-[#4da3ff]">The Origin Story</span>
                <h2 class="text-3xl md:text-5xl font-extrabold font-headline text-slate-900 dark:text-white tracking-tight leading-tight">Built by creators, <br>for creators.</h2>
                <div class="space-y-4 text-slate-500 dark:text-slate-400 leading-relaxed">
                    <p>
                        Silicon Slate began when a group of developers, designers, and engineers grew frustrated with the cluttered, confusing landscape of consumer electronics. Navigating between budget gadgets and overpriced hype made finding truly reliable workstations nearly impossible.
                    </p>
                    <p>
                        We built the platform we wanted to shop on. A meticulously organized inventory where every single item is verified for build quality, thermal performance, and longevity.
                    </p>
                    <p class="font-bold text-slate-900 dark:text-slate-300">
                        Welcome to the new standard of tech retail.
                    </p>
                </div>
                
                <div class="pt-6 border-t border-slate-200 dark:border-slate-700 grid grid-cols-3 gap-6">
                    <div>
                        <p class="text-3xl font-black font-headline text-[#0052CC] dark:text-[#4da3ff]">50K+</p>
                        <p class="text-xs font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400 mt-1">Orders Shipped</p>
                    </div>
                    <div>
                        <p class="text-3xl font-black font-headline text-[#0052CC] dark:text-[#4da3ff]">99%</p>
                        <p class="text-xs font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400 mt-1">Satisfaction</p>
                    </div>
                    <div>
                        <p class="text-3xl font-black font-headline text-[#0052CC] dark:text-[#4da3ff]">24/7</p>
                        <p class="text-xs font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400 mt-1">Support Access</p>
                    </div>
                </div>
            </div>

        </div>
    </section>

</main>

<?php require_once('footer.php'); ?>