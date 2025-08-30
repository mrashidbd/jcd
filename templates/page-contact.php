<?php
/**
 * Template Name: Contact
 * Page template for contact information and form
 */

get_header(); ?>

    <!-- Page Header -->
    <section class="page-header py-20 relative overflow-hidden bg-no-repeat bg-center bg-cover" style="background-image: linear-gradient(135deg, rgba(0, 213, 190, 0.8), rgba(255, 240, 133, 0.9)), url('/ducsu/wp-content/themes/jcd-ducsu/assets/images/central-bg.jpg');">
        <div class="container mx-auto px-4 text-center text-slate-700">
            <h1 class="text-4xl md:text-6xl font-bold mb-4">যোগাযোগ</h1>
            <p class="text-lg md:text-xl max-w-2xl mx-auto">আমাদের সাথে যোগাযোগ করুন এবং আপনার মতামত, পরামর্শ ও সহযোগিতা জানান</p>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-section py-16">
        <div class="container mx-auto px-4 ">
                <!-- Contact Form -->
                <div class="contact-form mx-0 md:mx-96">
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
                                    class="w-full bg-gradient-to-r cursor-pointer from-purple-200 via-blue-400 to-green-300 hover:from-primary-blue hover:to-primary-green text-white font-bold py-4 px-8 rounded-lg text-lg transition-all duration-300 transform hover:scale-105 flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                                বার্তা পাঠান
                            </button>
                        </form>
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