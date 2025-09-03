<!-- Footer -->
<footer class="site-footer bg-gray-900 text-white">
    <!-- Main Footer -->
    <div class="footer-main py-16">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                <!-- Brand Section -->
                <div class="footer-brand">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="bg-gradient-to-br from-primary-green to-primary-blue rounded-full w-12 h-12 flex items-center justify-center">
                            <img src="<?php echo get_site_icon_url(); ?>" alt="Chatradal Logo">
                        </div>
                        <div>
                            <h3 class="text-xl font-bold">বাংলাদেশ জাতীয়তাবাদী ছাত্রদল</h3>
                            <p class="text-sm text-gray-300">ডাকসু নির্বাচন - ২০২৫</p>
                        </div>
                    </div>
                    <p class="text-gray-300 mb-6 sm:pr-0 md:pr-16 leading-relaxed">বাংলাদেশ জাতীয়তাবাদী ছাত্রদল সমর্থিত ঢাকা বিশ্ববিদ্যালয় কেন্দ্রীয় ছাত্র সংসদ (ডাকসু) ও হল সংসদ প্যানেল, ২০২৫।
                        <br>"প্রতিশ্রুতি নয়, পরিবর্তনে প্রতিজ্ঞাবদ্ধ"
                    </p>

                    <!-- Social Media Links -->
                    <div class="flex space-x-4">
                        <a href="https://www.facebook.com/bangladesh.jcd" target="_blank"
                           class="bg-blue-600 hover:bg-blue-700 text-white w-10 h-10 rounded-full flex items-center justify-center transition-colors duration-300">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        <a href="mailto:info@jcdducsu.org"
                           class="bg-gray-600 hover:bg-gray-700 text-white w-10 h-10 rounded-full flex items-center justify-center transition-colors duration-300">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="footer-links">
                    <h4 class="text-lg font-bold mb-6 text-white">দ্রুত লিঙ্ক</h4>
                    <ul class="space-y-3">
                        <li><a href="<?php echo home_url(); ?>" class="text-gray-300 hover:text-primary-green transition-colors duration-300 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                                হোম
                            </a></li>
                        <li><a href="<?php echo home_url(); ?>/central-panel" class="text-gray-300 hover:text-primary-green transition-colors duration-300 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                                কেন্দ্রীয় প্যানেল
                            </a></li>
                        <li><a href="<?php echo home_url(); ?>/hall-panels" class="text-gray-300 hover:text-primary-green transition-colors duration-300 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                                হল প্যানেল
                            </a></li>
                        <li><a href="<?php echo home_url(); ?>/manifesto" class="text-gray-300 hover:text-primary-green transition-colors duration-300 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                                ইশতেহার
                            </a></li>
                        <li><a href="<?php echo home_url(); ?>/contact" class="text-gray-300 hover:text-primary-green transition-colors duration-300 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                                যোগাযোগ
                            </a></li>
                    </ul>
                </div>

                <!-- Important Links -->
                <div class="footer-info">
                    <h4 class="text-lg font-bold mb-6 text-white">গুরুত্বপূর্ণ তথ্য</h4>
                    <ul class="space-y-3">
                        <li><a href="//ducsu.du.ac.bd/voter.php" target="_blank" class="text-gray-300 hover:text-primary-green transition-colors duration-300 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                                ভোটার অনুসন্ধান
                            </a></li>
                        <li><a href="#" class="text-gray-300 hover:text-primary-green transition-colors duration-300 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                                নির্বাচনী সূচি
                            </a></li>
                        <li><a href="#" class="text-gray-300 hover:text-primary-green transition-colors duration-300 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                                ভোট প্রদান নির্দেশিকা
                            </a></li>
                        <li><a href="#" class="text-gray-300 hover:text-primary-green transition-colors duration-300 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                                সংবাদ ও আপডেট
                            </a></li>
                        <li><a href="<?php echo home_url(); ?>/privacy-policy" class="text-gray-300 hover:text-primary-green transition-colors duration-300 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                                গোপনীয়তা নীতি
                            </a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Footer -->
    <div class="footer-bottom bg-gray-950 border-t border-gray-800 py-6">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                <div class="text-gray-400 text-sm max-w-4xl mx-auto text-center">
                    <p>&copy; <?php echo date('Y'); ?> বাংলাদেশ জাতীয়তাবাদী ছাত্রদল, ঢাকা বিশ্ববিদ্যালয়। সর্বস্বত্ব সংরক্ষিত।</p>
                </div>
            </div>

            <!-- Developer Credit -->
            <div class="text-center mt-4 pt-4 border-t border-gray-800">
                <p class="text-gray-500 text-sm">
                    Developed with ❤️ by <a href="//bnpbd.org/" target="_blank" class="text-green-600 hover:text-yellow-400 transition-colors">তথ্য ও প্রযুক্তি দপ্তর, বিএনপি।</a>
                </p>
            </div>
        </div>
    </div>
</footer>

<!-- Scroll to Top Button -->
<button id="scroll-to-top"
        class="fixed bottom-8 right-8 bg-primary-green hover:bg-green-900 cursor-pointer text-white w-12 h-12 rounded-full shadow-lg flex items-center group justify-center transition-all duration-300 opacity-0 translate-y-4 pointer-events-none z-30">
    <svg class="w-6 h-6 group-hover:-translate-y-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
    </svg>
</button>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Scroll to top functionality
        const scrollToTopBtn = document.getElementById('scroll-to-top');

        function toggleScrollToTop() {
            const scrollY = window.scrollY;
            if (scrollY > 300) {
                scrollToTopBtn.classList.remove('opacity-0', 'translate-y-4', 'pointer-events-none');
                scrollToTopBtn.classList.add('opacity-100', 'translate-y-0', 'pointer-events-auto');
            } else {
                scrollToTopBtn.classList.add('opacity-0', 'translate-y-4', 'pointer-events-none');
                scrollToTopBtn.classList.remove('opacity-100', 'translate-y-0', 'pointer-events-auto');
            }
        }

        window.addEventListener('scroll', toggleScrollToTop);

        if (scrollToTopBtn) {
            scrollToTopBtn.addEventListener('click', function() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        }
    });
</script>

<?php wp_footer(); ?>
</body>
</html>