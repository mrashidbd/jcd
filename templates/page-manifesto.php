<?php
/**
 * Template Name: Manifesto
 * Page template for displaying manifesto items
 */

get_header(); ?>

    <!-- Page Header -->
    <section class="page-header py-20 relative overflow-hidden bg-no-repeat bg-center bg-cover" style="background-image: linear-gradient(135deg, rgba(0, 213, 190, 0.8), rgba(255, 240, 133, 0.9)), url('/ducsu/wp-content/themes/jcd-ducsu/assets/images/central-bg.jpg');">
        <div class="container mx-auto px-4 text-center relative z-10">
            <div class="max-w-4xl mx-auto">
                <h1 class="text-4xl md:text-6xl font-bold mb-6 text-slate-700">আমাদের ইশতিহার</h1>
                <p class="text-lg md:text-xl leading-relaxed text-slate-800">"প্রতিশ্রুতি নয়, পরিবর্তনে প্রতিজ্ঞাবদ্ধ"</p>
            </div>
        </div>
    </section>

    <!-- Manifesto Items -->
    <section class="manifesto-content py-16 bg-slate-100">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">

                <div class="pre-content text-lg text-justify pb-4">
                    <p>
                        শত বছরের ঐতিহ্যবাহী ঢাকা বিশ্ববিদ্যালয় কেবল বাংলাদেশের গৌরবময় প্রতিষ্ঠানই নয় বরং সাতচল্লিশের দেশভাগ, বায়ান্নর ভাষা আন্দোলন, একাত্তরের মহান স্বাধীনতা যুদ্ধ, নম্বইয়ের স্বৈরাচার বিরোধী আন্দোলন, এবং চব্বিশের ছাত্র-জনতার গণঅভ্যুন্থানসহ বাংলাদেশের প্রতিটি অধ্যায়ে অনস্বীকার্য অবদান রেখেছে। আবেগ, অনুভূতি ও সংগ্রামের প্রতীক হিসেবে ঐতিহাসিক এই বিশ্ববিদ্যালয় পরিণত হয়েছে স্বাধীনতা ও গণমানুষের অধিকার প্রতিষ্ঠার অন্যতম ধারক ও বাহক হিসেবে। আগামী ৯ সেপ্টেম্বর অনুষ্ঠিতব্য বহুল প্রতীক্ষিত ডাকসু নির্বাচন আমাদের, ঢাকা বিশ্ববিদ্যালয়ের শিক্ষার্থীদের, ক্যাম্পাসে গণতান্ত্রিক অধিকার চর্চার এক গুরুত্বপূর্ণ সুযোগ এনে দিয়েছে।
                    </p>
                    <p>
                        সেই অভিযাত্রায় বাংলাদেশ জাতীয়তাবাদী ছাত্রদল আসন্ন ডাকসু নির্বাচনের জন্য প্যানেল ঘোষণা করেছে। ফ্যাসিবাদবিরোধী আন্দোলনে নজিরবিহীন দমন-নিপীড়নের শিকার হওয়া এবং সর্বদা অগ্রণী নেতৃত্ব প্রদানকারী সংগঠন হিসেবে, আমরা ১০টি মূল অঙ্গীকার সম্বলিত নির্বাচনী ইশতেহার ঘোষণা করছি, যা ঢাকা বিশ্ববিদ্যালয়ের শিক্ষা ব্যবস্থাকে আরও যুগোপযোগী ও আধুনিক করে তুলবে এবং গড়ে তুলবে একটি নিরাপদ, শিক্ষার্থীবান্ধব ক্যাম্পাস। আমরা বিশ্বাস করি, এই ১০টি প্রধান পরিকল্পনায় শিক্ষার্থীদের আশা-আকাঙ্ক্ষার প্রতিফলন ঘটবে এবং আমরা সবাই মিলে তা বাস্তবায়নে কাজ করব, ইনশাআল্লাহ।
                    </p>
                </div>

                <h3 class="my-4 text-center text-3xl">
                    আমাদের ইশতিহার - প্রতিশ্রুতি নয়, পরিবর্তনে প্রতিজ্ঞাবদ্ধ
                </h3>

                <div id="manifesto-accordion" class="space-y-4">
                    <?php
                    $manifesto_items = ducsu_get_manifesto_items();

                    if ($manifesto_items->have_posts()) :
                        $item_count = 0;
                        while ($manifesto_items->have_posts()) : $manifesto_items->the_post();
                            $item_count++;
                            $accordion_id = 'accordion-item-' . $item_count;
                            ?>
                            <div class="accordion-item bg-white rounded-lg shadow-sm overflow-hidden">
                                <div class="accordion-header">
                                    <button type="button"
                                            class="accordion-button flex cursor-pointer items-center justify-between w-full p-6 text-left bg-green-50 hover:bg-green-100 transition-colors duration-300 active:bg-gray-200 focus:outline-none"
                                            data-target="#<?php echo $accordion_id; ?>"
                                            aria-expanded="false"
                                            aria-controls="<?php echo $accordion_id; ?>">
                                        <div class="flex items-center space-x-4">
                                            <div class="bg-primary-green text-white w-10 h-10 rounded-full flex items-center justify-center font-bold">
                                                <?php echo convertEngToBn(str_pad($item_count, 2, '0', STR_PAD_LEFT)); ?>
                                            </div>
                                            <h3 class="text-lg md:text-xl font-semibold text-gray-800">
                                                <?php the_title(); ?>
                                            </h3>
                                        </div>
                                        <svg class="accordion-icon w-6 h-6 text-gray-500 transition-transform duration-300"
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>
                                </div>

                                <div id="<?php echo $accordion_id; ?>"
                                     class="accordion-content hidden"
                                     aria-labelledby="accordion-button-<?php echo $item_count; ?>">
                                    <div class="p-6 border-t border-gray-100">
                                        <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                                            <?php the_content(); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                        endwhile;
                    else :
                        ?>
                        <div class="text-center py-12">
                            <div class="bg-white rounded-lg shadow-md p-8 max-w-md mx-auto">
                                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <h3 class="text-lg font-medium text-gray-800 mb-2">ইশতিহার আপডেট হচ্ছে</h3>
                                <p class="text-gray-600">আমাদের বিস্তারিত ইশতিহার শীঘ্রই প্রকাশিত হবে।</p>
                            </div>
                        </div>
                    <?php
                    endif;
                    wp_reset_postdata();
                    ?>
                </div>

                <div class="post-content text-lg text-justify mt-16">
                    <p>
                        বাংলাদেশের স্বাধীনতা, সার্বভৌমত্ব ও গণতন্ত্র সুরক্ষার মাধ্যমে জনগণের মানবাধিকার, বাকস্বাধীনতা ও আইনের অনুশাসন প্রতিষ্ঠায় সরকারের জবাবদিহিতা ও দায়বদ্ধতা নিশ্চিত করা অত্যাবশ্যক। গণমানুষের সেই আকাঙ্ক্ষা বাস্তবায়নে ছাত্রসমাজের প্রতিনিধি হিসেবে আমরা নিরলস ভাবে কাজ করব।
                    </p>
                    <p>
                        ঢাকা বিশ্ববিদ্যালয় কেবল একটি শিক্ষা প্রতিষ্ঠান নয়। এটি বাংলাদেশের ইতিহাস, সংস্কৃতি, সংগ্রাম ও অর্জনের অবিচ্ছেদ্য অংশ। সেই গৌরবময় ধারাবাহিকতায় আমাদের প্রতিশ্রুতি একটি আধুনিক, নিরাপদ, গণতান্ত্রিক ও শিক্ষার্থীবান্ধব ক্যাম্পাস বিনির্মাণ। আমাদের এই নির্বাচনী ইশতেহার সম্মিলিতভাবে এমন একটি ক্যাম্পাস গড়ে তোলার প্রত্যয়, যেখানে সকলের শিক্ষা, স্বাস্থ্য, নিরাপত্তা, মর্যাদা, গবেষণা, ক্যারিয়ার উন্নয়ন, সাংস্কৃতিক বিকাশ এবং গণতান্ত্রিক অধিকার প্রতিষ্ঠার পূর্ণ নিশ্চয়তা থাকবে।
                    </p>
                    <p>
                        প্রতিশ্রুতি নয়, বাংলাদেশ জাতীয়তাবাদী ছাত্রদল পরিবর্তনের প্রতিজ্ঞাবদ্ধ। আমরা বিশ্বাস করি, আপনাদের সহযোগিতা, অংশগ্রহণ ও সমর্থনই ঢাকা বিশ্ববিদ্যালয়কে দেশের গণতান্ত্রিক মূল্যবোধ ও প্রগতির এক অটুট সূতিকাগার হিসেবে প্রতিষ্ঠিত করবে। আসুন, একসাথে সেই দায়িত্ব কাঁধে তুলে নিয়ে একটি আদর্শ ক্যাম্পাস গড়ে তুলি, সমৃদ্ধ ভবিষ্যৎ রচনা করি। আপনার ভোট, আপনার অধিকার। আসুন, আমরা সবাই সেই অধিকার প্রয়োগ করে অর্থবহ ও টেকসই পরিবর্তন নিশ্চিত করি।
                    </p>
                </div>

            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="cta-section py-16 bg-gradient-to-r from-teal-400 to-yellow-200">
        <div class="container mx-auto px-4 text-center text-white">
            <div class="max-w-3xl mx-auto text-slate-700">
                <h2 class="text-3xl md:text-4xl font-bold mb-6">আপনার মতামত আমাদের কাছে গুরুত্বপূর্ণ</h2>
                <p class="text-lg md:text-xl mb-8 opacity-90">
                    আমাদের ইশতিহার সম্পর্কে আপনার কোনো প্রশ্ন বা পরামর্শ থাকলে আমাদের সাথে যোগাযোগ করুন।
                    আমরা সবসময় আপনাদের মতামতের জন্য প্রস্তুত।
                </p>
                <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-6">
                    <a href="<?php echo home_url(); ?>/contact"
                       class="inline-block font-bold py-3 px-8 rounded-md text-slate-800 bg-gray-200 hover:bg-gray-400 transition-all duration-300 transform">
                        যোগাযোগ করুন
                    </a>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Accordion functionality
            const accordionButtons = document.querySelectorAll('.accordion-button');

            accordionButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const target = document.querySelector(this.getAttribute('data-target'));
                    const icon = this.querySelector('.accordion-icon');
                    const isExpanded = this.getAttribute('aria-expanded') === 'true';

                    // Close all accordion items
                    accordionButtons.forEach(btn => {
                        const content = document.querySelector(btn.getAttribute('data-target'));
                        const btnIcon = btn.querySelector('.accordion-icon');

                        btn.setAttribute('aria-expanded', 'false');
                        content.classList.add('hidden');
                        btnIcon.style.transform = 'rotate(0deg)';
                    });

                    // If the clicked item was not expanded, expand it
                    if (!isExpanded) {
                        this.setAttribute('aria-expanded', 'true');
                        target.classList.remove('hidden');
                        icon.style.transform = 'rotate(180deg)';
                    }
                });
            });

            // Auto-expand first item on load
            // if (accordionButtons.length > 0) {
            //     accordionButtons[0].click();
            // }
        });
    </script>

<?php get_footer(); ?>