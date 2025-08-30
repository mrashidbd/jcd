<?php
/**
 * Custom Taxonomies Registration
 */

if (!defined('ABSPATH')) {
    exit;
}

class DUCSU_Taxonomies {

    public function __construct() {
        add_action('init', [$this, 'register_taxonomies']);
        add_action('halls_edit_form_fields', [$this, 'add_hall_image_field']);
        add_action('edited_halls', [$this, 'save_hall_image']);
    }

    /**
     * Register all custom taxonomies
     */
    public function register_taxonomies() {
        $this->register_halls_taxonomy();
        // Add more taxonomies here if needed
    }

    /**
     * Register Halls taxonomy
     */
    private function register_halls_taxonomy() {
        register_taxonomy('halls', 'hall_candidate', [
            'labels' => [
                'name' => __('Halls', 'ducsu-jcd'),
                'singular_name' => __('Hall', 'ducsu-jcd'),
            ],
            'hierarchical' => true,
            'public' => true,
            'show_admin_column' => true,
            'show_in_rest' => true,
            'rewrite' => ['slug' => 'halls']
        ]);

        // Pre-populate with default halls
        $this->insert_default_halls();
    }

    /**
     * Pre-populate Halls taxonomy with default halls
     */
    private function insert_default_halls() {
        $halls = [
            'মুক্তিযোদ্ধা জিয়াউর রহমান হল',
            'বিজয় একাত্তর হল',
            'কবি জসিমউদদীন হল',
            'মাস্টারদা সূর্যসেন হল',
            'সলিমুল্লাহ মুসলিম হল',
            'শহীদ সার্জেন্ট জহুরুল হক হল',
            'শেখ মুজিবুর রহমান হল',
            'হাজী মুহম্মদ মুহসীন হল',
            'স্যার এ এফ রহমান হল',
            'রোকেয়া হল',
            'শামসুন্নাহার হল',
            'ফজিলাতুন্নেছা মুজিব হল',
            'বাংলাদেশ কুয়েত মৈত্রী হল',
            'কবি সুফিয়া কামাল হল',
            'জগন্নাথ হল',
            'ফজলুল হক মুসলিম হল',
            'ড. মুহম্মদ শহীদুল্লাহ হল',
            'অমর একুশে হল'
        ];

        foreach ($halls as $hall) {
            if (!term_exists($hall, 'halls')) {
                wp_insert_term($hall, 'halls');
            }
        }
    }

    /**
     * Add image field to halls taxonomy
     */
    public function add_hall_image_field($term) {
        $image_id = get_term_meta($term->term_id, 'hall_image', true);
        ?>
        <tr class="form-field">
            <th scope="row">
                <label for="hall_image">Hall Image</label>
            </th>
            <td>
                <input type="hidden" id="hall_image" name="hall_image" value="<?php echo esc_attr($image_id); ?>"/>
                <div id="hall_image_preview">
                    <?php if ($image_id) : ?>
                        <?php echo wp_get_attachment_image($image_id, 'medium'); ?>
                    <?php endif; ?>
                </div>
                <button type="button" id="upload_hall_image" class="button">Upload Image</button>
                <button type="button" id="remove_hall_image" class="button"
                        style="<?php echo $image_id ? '' : 'display:none;'; ?>">Remove Image</button>
            </td>
        </tr>

        <script>
            jQuery(document).ready(function($) {
                $('#upload_hall_image').click(function() {
                    var frame = wp.media.frames.hall_image = wp.media({
                        title: 'Select Hall Image',
                        button: { text: 'Use Image' },
                        library: { type: 'image' },
                        multiple: false
                    });

                    frame.on('select', function() {
                        var attachment = frame.state().get('selection').first().toJSON();
                        $('#hall_image').val(attachment.id);
                        $('#hall_image_preview').html('<img src="' + attachment.sizes.medium.url + '" style="max-width: 300px;" />');
                        $('#remove_hall_image').show();
                    });

                    frame.open();
                });

                $('#remove_hall_image').click(function() {
                    $('#hall_image').val('');
                    $('#hall_image_preview').html('');
                    $(this).hide();
                });
            });
        </script>
        <?php
    }

    /**
     * Save hall image field
     */
    public function save_hall_image($term_id) {
        if (isset($_POST['hall_image'])) {
            update_term_meta($term_id, 'hall_image', absint($_POST['hall_image']));
        }
    }
}