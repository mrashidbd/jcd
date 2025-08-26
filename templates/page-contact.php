<?php
/**
 * Template Name: Contact
 * Page template for contact information and form
 */

get_header(); ?>

    <!-- Page Header -->
    <section class="page-header bg-gradient-to-r from-primary-blue to-primary-green py-20">
        <div class="container mx-auto px-4 text-center text-white">
            <h1 class="text-4xl md:text-6xl font-bold mb-4">যোগাযোগ</h1>
            <p class="text-lg md:text-xl max-w-2xl mx-auto">আমাদের সাথে যোগাযোগ করুন এবং আপনার মতামত, পরামর্শ ও সহযোগিতা জানান</p>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-section py-16">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 max-w-7xl mx-auto">

                <!-- Contact Information -->
                <div class="contact-info">
                    <h2 class="text-3xl font-bold text-gray-800 mb-8">যোগাযোগের তথ্য</h2>

                    <div class="space-y-8">
                        <!-- Office Address -->
                        <div class="contact-item flex items-start">
                            <div class="bg-primary-blue text-white rounded-full w-12 h-12 flex items-center justify-center mr-6 mt-1 flex-shrink-0">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-800 mb-2">অফিস ঠিকানা</h3>
                                <p class="text-gray-600 leading-relaxed">
                                    বাংলাদেশ জাতীয়তাবাদী ছাত্রদল<br>
                                    ঢাকা বিশ্ববিদ্যালয় শাখা<br>
                                    ঢাকা বিশ্ববিদ্যালয়<br>
                                    ঢাকা ১০০০, বাংলাদেশ
                                </p>
                            </div>
                        </div>

                        <!-- Phone Numbers -->
                        <div class="contact-item flex items-start">
                            <div class="bg-primary-green text-white rounded-full w-12 h-12 flex items-center justify-center mr-6 mt-1 flex-shrink-0">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-800 mb-2">ফোন নম্বর</h3>
                                <div class="space-y-1">
                                    <p class="text-gray-600">প্রধান অফিস: <a href="tel:+8801712345678" class="text-primary-green hover:text-primary-red transition-colors">+৮৮০ ১৭১২ ৩৪৫৬৭৮</a></p>
                                    <p class="text-gray-600">জরুরি যোগাযোগ: <a href="tel:+8801887654321" class="text-primary-green hover:text-primary-red transition-colors">+৮৮০ ১৮৮৭ ৬৫৪৩২১</a></p>
                                </div>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="contact-item flex items-start">
                            <div class="bg-primary-red text-white rounded-full w-12 h-12 flex items-center justify-center mr-6 mt-1 flex-shrink-0">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-800 mb-2">ইমেইল</h3>
                                <div class="space-y-1">
                                    <p class="text-gray-600">সাধারণ যোগাযোগ: <a href="mailto:info@jcdducsu.org" class="text-primary-green hover:text-primary-red transition-colors">info@jcdducsu.org</a></p>
                                    <p class="text-gray-600">প্রেস ও মিডিয়া: <a href="mailto:press@jcdducsu.org" class="text-primary-green hover:text-primary-red transition-colors">press@jcdducsu.org</a></p>
                                </div>
                            </div>
                        </div>

                        <!-- Social Media -->
                        <div class="contact-item flex items-start">
                            <div class="bg-gradient-to-br from-primary-green to-primary-blue text-white rounded-full w-12 h-12 flex items-center justify-center mr-6 mt-1 flex-shrink-0">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-800 mb-2">সামাজিক যোগাযোগ</h3>
                                <div class="flex flex-wrap gap-4">
                                    <a href="https://facebook.com/jcdducsu" target="_blank"
                                       class="flex items-center space-x-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                        </svg>
                                        <span>Facebook</span>
                                    </a>

                                    <a href="https://twitter.com/jcdducsu" target="_blank"
                                       class="flex items-center space-x-2 bg-blue-400 text-white px-4 py-2 rounded-lg hover:bg-blue-500 transition-colors">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                        </svg>
                                        <span>Twitter</span>
                                    </a>

                                    <a href="https://youtube.com/@jcdducsu" target="_blank"
                                       class="flex items-center space-x-2 bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                        </svg>
                                        <span>YouTube</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Office Hours -->
                        <div class="contact-item flex items-start">
                            <div class="bg-gray-700 text-white rounded-full w-12 h-12 flex items-center justify-center mr-6 mt-1 flex-shrink-0">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.414-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-800 mb-2">অফিসের সময়</h3>
                                <div class="space-y-1 text-gray-600">
                                    <p>সোমবার - শুক্রবার: সকাল ৯:০০ - বিকাল ৫:০০</p>
                                    <p>শনিবার: সকাল ১০:০০ - দুপুর ২:০০</p>
                                    <p>রবিবার: বন্ধ</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Map Section -->
                    <div class="mt-12">
                        <h3 class="text-2xl font-bold text-gray-800 mb-6">আমাদের অবস্থান</h3>
                        <div class="bg-gray-200 rounded-lg h-64 flex items-center justify-center">
                            <div class="text-center text-gray-600">
                                <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <p class="text-lg font-medium">ঢাকা বিশ্ববিদ্যালয়</p>
                                <p class="text-sm">ইন্টারেক্টিভ ম্যাপ শীঘ্রই আসছে</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="contact-form">
                    <div class="bg-white rounded-2xl shadow-xl p-8">
                        <h2 class="text-3xl font-bold text-gray-800 mb-2">বার্তা পাঠান</h2>
                        <p class="text-gray-600 mb-8">আপনার মতামত, পরামর্শ বা যেকোনো প্রশ্ন আমাদের জানান। আমরা শীঘ্রই আপনার সাথে যোগাযোগ করব।</p>

                        <form id="contact-form" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="contact-name" class="block text-sm font-semibold text-gray-700 mb-2">পূর্ণ নাম *</label>
                                    <input type="text" id="contact-name" name="name" required
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent transition-all duration-300"
                                           placeholder="আপনার পূর্ণ নাম লিখুন">
                                </div>

                                <div>
                                    <label for="contact-email" class="block text-sm font-semibold text-gray-700 mb-2">ইমেইল ঠিকানা *</label>
                                    <input type="email" id="contact-email" name="email" required
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent transition-all duration-300"
                                           placeholder="example@email.com">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="contact-phone" class="block text-sm font-semibold text-gray-700 mb-2">ফোন নম্বর</label>
                                    <input type="tel" id="contact-phone" name="phone"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent transition-all duration-300"
                                           placeholder="+৮৮০ ১৭xx xxx xxx">
                                </div>

                                <div>
                                    <label for="contact-subject" class="block text-sm font-semibold text-gray-700 mb-2">বিষয়</label>
                                    <select id="contact-subject" name="subject"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent transition-all duration-300">
                                        <option value="">বিষয় নির্বাচন করুন</option>
                                        <option value="general">সাধারণ তথ্য</option>
                                        <option value="complaint">অভিযোগ</option>
                                        <option value="suggestion">পরামর্শ</option>
                                        <option value="support">সহযোগিতা</option>
                                        <option value="media">মিডিয়া অনুসন্ধান</option>
                                        <option value="other">অন্যান্য</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label for="contact-student-id" class="block text-sm font-semibold text-gray-700 mb-2">শিক্ষার্থী আইডি (ঐচ্ছিক)</label>
                                <input type="text" id="contact-student-id" name="student_id"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent transition-all duration-300"
                                       placeholder="যদি আপনি ঢাকা বিশ্ববিদ্যালয়ের শিক্ষার্থী হন">
                            </div>

                            <div>
                                <label for="contact-message" class="block text-sm font-semibold text-gray-700 mb-2">আপনার বার্তা *</label>
                                <textarea id="contact-message" name="message" rows="6" required
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent transition-all duration-300 resize-none"
                                          placeholder="আপনার বার্তা, মতামত বা প্রশ্ন বিস্তারিত লিখুন..."></textarea>
                            </div>

                            <!-- Privacy Notice -->
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div class="text-sm text-blue-700">
                                        <p class="font-medium mb-1">গোপনীয়তা নীতি</p>
                                        <p>আপনার ব্যক্তিগত তথ্য সুরক্ষিত থাকবে এবং কেবল যোগাযোগের প্রয়োজনেই ব্যবহৃত হবে। আমরা তৃতীয় পক্ষের সাথে আপনার তথ্য শেয়ার করি না।</p>
                                    </div>
                                </div>
                            </div>

                            <button type="submit"
                                    class="w-full bg-gradient-to-r from-primary-green to-primary-blue hover:from-primary-blue hover:to-primary-green text-white font-bold py-4 px-8 rounded-lg text-lg transition-all duration-300 transform hover:scale-105 flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                                বার্তা পাঠান
                            </button>
                        </form>
                    </div>

                    <!-- Quick Links -->
                    <div class="mt-8 bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl p-8">
                        <h3 class="text-2xl font-bold text-gray-800 mb-6">দ্রুত লিঙ্ক</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <a href="/central-panel"
                               class="flex items-center space-x-3 bg-white rounded-lg p-4 hover:bg-primary-green hover:text-white transition-all duration-300 group">
                                <div class="bg-primary-blue group-hover:bg-white group-hover:text-primary-blue text-white rounded-full w-10 h-10 flex items-center justify-center transition-all duration-300">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"></path>
                                    </svg>
                                </div>
                                <span class="font-medium">কেন্দ্রীয় প্যানেল</span>
                            </a>

                            <a href="/hall-panels"
                               class="flex items-center space-x-3 bg-white rounded-lg p-4 hover:bg-primary-green hover:text-white transition-all duration-300 group">
                                <div class="bg-primary-green group-hover:bg-white group-hover:text-primary-green text-white rounded-full w-10 h-10 flex items-center justify-center transition-all duration-300">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.84L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.84l-7-3z"></path>
                                    </svg>
                                </div>
                                <span class="font-medium">হল প্যানেল</span>
                            </a>

                            <a href="/manifesto"
                               class="flex items-center space-x-3 bg-white rounded-lg p-4 hover:bg-primary-green hover:text-white transition-all duration-300 group">
                                <div class="bg-primary-red group-hover:bg-white group-hover:text-primary-red text-white rounded-full w-10 h-10 flex items-center justify-center transition-all duration-300">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <span class="font-medium">ইশতেহার</span>
                            </a>

                            <a href="/#"
                               class="flex items-center space-x-3 bg-white rounded-lg p-4 hover:bg-primary-green hover:text-white transition-all duration-300 group">
                                <div class="bg-gray-600 group-hover:bg-white group-hover:text-gray-600 text-white rounded-full w-10 h-10 flex items-center justify-center transition-all duration-300">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <span class="font-medium">হোম পেজে ফিরুন</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-800 mb-4">প্রায়শই জিজ্ঞাসিত প্রশ্ন</h2>
                    <p class="text-lg text-gray-600">সাধারণ কিছু প্রশ্ন ও তার উত্তর</p>
                </div>

                <div class="space-y-4">
                    <div class="faq-item bg-white rounded-lg shadow-lg overflow-hidden">
                        <button class="faq-header w-full text-left p-6 bg-white hover:bg-gray-50 flex justify-between items-center transition-colors duration-300"
                                data-target="faq-content-1">
                            <h3 class="text-lg font-semibold text-gray-800">নির্বাচনে ভোট দেওয়ার যোগ্যতা কী?</h3>
                            <svg class="faq-icon w-5 h-5 text-gray-400 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div id="faq-content-1" class="faq-content overflow-hidden transition-all duration-300 max-h-0">
                            <div class="p-6 pt-0 border-t border-gray-100">
                                <p class="text-gray-700">ঢাকা বিশ্ববিদ্যালয়ের যেকোনো নিয়মিত শিক্ষার্থী যিনি বর্তমানে অধ্যয়নরত এবং যার শিক্ষার্থী আইডি কার্ড আছে, তিনি ডাকসু নির্বাচনে ভোট দিতে পারবেন।</p>
                            </div>
                        </div>
                    </div>

                    <div class="faq-item bg-white rounded-lg shadow-lg overflow-hidden">
                        <button class="faq-header w-full text-left p-6 bg-white hover:bg-gray-50 flex justify-between items-center transition-colors duration-300"
                                data-target="faq-content-2">
                            <h3 class="text-lg font-semibold text-gray-800">নির্বাচনের তারিখ কবে?</h3>
                            <svg class="faq-icon w-5 h-5 text-gray-400 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div id="faq-content-2" class="faq-content overflow-hidden transition-all duration-300 max-h-0">
                            <div class="p-6 pt-0 border-t border-gray-100">
                                <p class="text-gray-700">নির্বাচনের তারিখ শীঘ্রই ঘোষণা করা হবে। সর্বশেষ তথ্যের জন্য আমাদের ওয়েবসাইট ও সামাজিক যোগাযোগ মাধ্যম ফলো করুন।</p>
                            </div>
                        </div>
                    </div>

                    <div class="faq-item bg-white rounded-lg shadow-lg overflow-hidden">
                        <button class="faq-header w-full text-left p-6 bg-white hover:bg-gray-50 flex justify-between items-center transition-colors duration-300"
                                data-target="faq-content-3">
                            <h3 class="text-lg font-semibold text-gray-800">কীভাবে প্রার্থীদের সাথে যোগাযোগ করব?</h3>
                            <svg class="faq-icon w-5 h-5 text-gray-400 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div id="faq-content-3" class="faq-content overflow-hidden transition-all duration-300 max-h-0">
                            <div class="p-6 pt-0 border-t border-gray-100">
                                <p class="text-gray-700">প্রতিটি প্রার্থীর প্রোফাইলে যোগাযোগ ফর্ম রয়েছে। আপনি সেখান থেকে সরাসরি তাদের সাথে যোগাযোগ করতে পারেন। এছাড়াও তাদের সামাজিক যোগাযোগ মাধ্যমের লিঙ্ক পাবেন।</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Contact Form functionality
            const contactForm = document.getElementById('contact-form');

            if (contactForm) {
                contactForm.addEventListener('submit', async function(e) {
                    e.preventDefault();

                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;

                    // Disable submit button
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = `
                <svg class="animate-spin w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                পাঠানো হচ্ছে...
            `;

                    try {
                        const formData = new FormData(this);
                        formData.append('action', 'ducsu_general_contact');
                        formData.append('nonce', ducsu_ajax.nonce);

                        const response = await fetch(ducsu_ajax.ajax_url, {
                            method: 'POST',
                            body: formData
                        });

                        const data = await response.json();

                        if (data.success) {
                            showNotification(data.data, 'success');
                            this.reset();
                        } else {
                            showNotification(data.data, 'error');
                        }
                    } catch (error) {
                        console.error('Contact form error:', error);
                        showNotification('বার্তা পাঠাতে সমস্যা হয়েছে। পরে চেষ্টা করুন।', 'error');
                    } finally {
                        // Re-enable submit button
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }
                });
            }

            // FAQ Accordion functionality
            const faqHeaders = document.querySelectorAll('.faq-header');

            faqHeaders.forEach(header => {
                header.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    const content = document.getElementById(targetId);
                    const icon = this.querySelector('.faq-icon');
                    const isOpen = !content.classList.contains('max-h-0');

                    // Close all FAQs
                    faqHeaders.forEach(otherHeader => {
                        const otherTargetId = otherHeader.getAttribute('data-target');
                        const otherContent = document.getElementById(otherTargetId);
                        const otherIcon = otherHeader.querySelector('.faq-icon');

                        otherContent.classList.add('max-h-0');
                        otherContent.classList.remove('max-h-screen');
                        otherIcon.classList.remove('rotate-180');
                    });

                    // Toggle current FAQ
                    if (!isOpen) {
                        content.classList.remove('max-h-0');
                        content.classList.add('max-h-screen');
                        icon.classList.add('rotate-180');
                    }
                });
            });

            // Notification function
            function showNotification(message, type = 'info') {
                const notification = document.createElement('div');
                notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full ${
                    type === 'error' ? 'bg-red-500 text-white' :
                        type === 'success' ? 'bg-green-500 text-white' :
                            'bg-blue-500 text-white'
                }`;

                notification.innerHTML = `
            <div class="flex items-center space-x-3">
                ${type === 'error' ?
                    '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>' :
                    '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>'
                }
                <span>${message}</span>
                <button class="ml-4 hover:opacity-75">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        `;

                document.body.appendChild(notification);

                // Show notification
                setTimeout(() => {
                    notification.classList.remove('translate-x-full');
                }, 100);

                // Auto hide after 5 seconds
                setTimeout(() => {
                    notification.classList.add('translate-x-full');
                    setTimeout(() => {
                        if (notification.parentNode) {
                            notification.parentNode.removeChild(notification);
                        }
                    }, 300);
                }, 5000);

                // Close button functionality
                notification.querySelector('button').addEventListener('click', () => {
                    notification.classList.add('translate-x-full');
                    setTimeout(() => {
                        if (notification.parentNode) {
                            notification.parentNode.removeChild(notification);
                        }
                    }, 300);
                });
            }
        });
    </script>

<?php get_footer(); ?>