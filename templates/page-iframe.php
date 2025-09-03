<?php
/*
Template Name: External Content iFrame
*/

get_header(); ?>

<?php
if ( have_posts() ):
    while ( have_posts() ):
        the_post();
        ?>
        <!-- Page Header -->
        <section class="page-header py-20 relative overflow-hidden bg-no-repeat bg-center bg-cover"
                 style="background-image: linear-gradient(135deg, rgba(0, 213, 190, 0.8), rgba(255, 240, 133, 0.9)), url('<?php echo home_url();?>/wp-content/themes/jcd-ducsu/assets/images/central-bg.jpg');">
            <div class="container mx-auto px-4 text-center relative z-10">
                <div class="max-w-4xl text-slate-700 mx-auto">
                    <?php the_title('<h1 class="text-4xl md:text-6xl font-bold mb-6">', '</h1>'); ?>
                </div>
            </div>
        </section>

        <!-- Main Page with iFrame -->
        <section class="faq-content py-16 bg-slate-100">
            <div class="container mx-auto">
                <div class="max-w-5xl mx-auto bg-white px-8 py-4 rounded-lg shadow-sm">

                    <!-- WordPress Page Content (if any) -->
                    <?php if (trim(get_the_content())): ?>
                        <div class="wordpress-content mb-8 text-xl">
                            <?php the_content(); ?>
                        </div>
                    <?php endif; ?>

                    <!-- External Content iFrame -->
                    <div class="iframe-container">
                        <div class="absolute bottom-0 left-0 w-full py-8 bg-gray-100 text-center">
                            <p>সৌজন্যে: ঢাকা বিশ্ববিদ্যালয় আইসিটি সেল</p>
                        </div>
                        <div id="loading-message" class="text-center py-8">
                            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                            <p class="mt-2 text-gray-600">Loading voter information form...</p>
                        </div>

                        <iframe
                                id="external-iframe"
                                src="https://ducsu.du.ac.bd/voter.php"
                                frameborder="0"
                                scrolling="auto"
                                onload="handleIframeLoad()"
                                onerror="handleIframeError()">
                            <div class="fallback-content text-center py-8">
                                <p class="text-gray-600 mb-4">Your browser does not support embedded content.</p>
                                <a href="https://ducsu.du.ac.bd/voter.php"
                                   target="_blank"
                                   class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition duration-300">
                                    Open in New Window
                                </a>
                            </div>
                        </iframe>
                    </div>

                </div>
            </div>
        </section>

    <?php endwhile; ?>
<?php endif; ?>

    <style>
        .iframe-container {
            position: relative;
            width: 100%;
            min-height: 500px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        #external-iframe {
            width: 100%;
            height: 600px;
            min-height: 600px;
            border: none;
            display: none; /* Hidden until loaded */
            background: white;
        }

        #external-iframe.loaded {
            display: block;
        }

        .iframe-error {
            background-color: #fee2e2;
            border: 1px solid #fecaca;
            color: #dc2626;
            padding: 1rem;
            border-radius: 0.5rem;
            text-align: center;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .iframe-container {
                min-height: 500px;
            }

            #external-iframe {
                height: 700px;
            }
        }

        @media (max-width: 480px) {
            #external-iframe {
                height: 600px;
            }
        }

        /* Loading animation */
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .animate-spin {
            animation: spin 1s linear infinite;
        }
    </style>

    <script>
        function handleIframeLoad() {
            const iframe = document.getElementById('external-iframe');
            const loadingMessage = document.getElementById('loading-message');

            // Hide loading message and show iframe
            loadingMessage.style.display = 'none';
            iframe.classList.add('loaded');

            // Set a reasonable default height since cross-origin height detection won't work
            iframe.style.height = '800px';

            console.log('Iframe loaded successfully');
        }

        function handleIframeError() {
            const loadingMessage = document.getElementById('loading-message');
            const iframeContainer = document.querySelector('.iframe-container');

            loadingMessage.style.display = 'none';

            iframeContainer.innerHTML = `
        <div class="iframe-error">
            <h3 class="text-lg font-semibold mb-2">Unable to Load Content</h3>
            <p class="mb-4">The external content could not be loaded. This might be due to network issues or the external site being temporarily unavailable.</p>
            <a href="https://ducsu.du.ac.bd/voter.php"
               target="_blank"
               class="inline-block bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 transition duration-300">
                Visit Original Page
            </a>
        </div>
    `;
        }

        // Optional: Refresh iframe content
        function refreshIframe() {
            const iframe = document.getElementById('external-iframe');
            const loadingMessage = document.getElementById('loading-message');

            loadingMessage.style.display = 'block';
            iframe.classList.remove('loaded');
            iframe.src = iframe.src; // Reload iframe
        }
    </script>

<?php get_footer(); ?>