<?php
/**
 * Admin-specific functions and customizations
 */

if (!defined('ABSPATH')) {
    exit;
}

class DUCSU_Admin_Functions {

    public function __construct() {
        add_action('admin_menu', [$this, 'add_newsletter_admin_menu']);
        add_action('admin_init', [$this, 'handle_newsletter_actions']);
        add_filter('manage_central_candidate_posts_columns', [$this, 'add_candidate_columns']);
        add_filter('manage_hall_candidate_posts_columns', [$this, 'add_candidate_columns']);
        add_action('manage_central_candidate_posts_custom_column', [$this, 'fill_candidate_columns'], 10, 2);
        add_action('manage_hall_candidate_posts_custom_column', [$this, 'fill_candidate_columns'], 10, 2);
        add_action('admin_head', [$this, 'add_admin_styles']);
    }

    /**
     * Add newsletter subscribers menu
     */
    public function add_newsletter_admin_menu() {
        add_submenu_page(
            'edit.php?post_type=central_candidate',
            'Newsletter Subscribers',
            'Newsletter Subscribers',
            'manage_options',
            'newsletter-subscribers',
            [$this, 'newsletter_subscribers_page']
        );
    }

    /**
     * Newsletter subscribers admin page
     */
    public function newsletter_subscribers_page() {
        $subscribers = get_option('ducsu_newsletter_subscribers', []);
        ?>
        <div class="wrap">
            <h1>Newsletter Subscribers</h1>
            <p>Total subscribers: <strong><?php echo count($subscribers); ?></strong></p>

            <?php if (!empty($subscribers)) : ?>
                <div class="tablenav top">
                    <div class="alignleft actions">
                        <a href="?page=newsletter-subscribers&action=export&nonce=<?php echo wp_create_nonce('export_subscribers'); ?>"
                           class="button">Export as CSV</a>
                    </div>
                </div>

                <table class="wp-list-table widefat fixed striped">
                    <thead>
                    <tr>
                        <th>Email</th>
                        <th>Subscribed Date</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($subscribers as $index => $email) : ?>
                        <tr>
                            <td><?php echo esc_html($email); ?></td>
                            <td><?php echo date('Y-m-d H:i:s'); ?></td>
                            <td>
                                <a href="?page=newsletter-subscribers&action=remove&index=<?php echo $index; ?>&nonce=<?php echo wp_create_nonce('remove_subscriber'); ?>"
                                   onclick="return confirm('Are you sure you want to remove this subscriber?')"
                                   class="button button-small button-link-delete">Remove</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else : ?>
                <div class="notice notice-info">
                    <p>No subscribers yet.</p>
                </div>
            <?php endif; ?>

            <div class="postbox" style="margin-top: 20px;">
                <h3 class="hndle">Newsletter Statistics</h3>
                <div class="inside">
                    <p><strong>Total Subscribers:</strong> <?php echo count($subscribers); ?></p>
                    <p><strong>Last 30 days:</strong> <?php echo $this->get_recent_subscribers_count(30); ?></p>
                    <p><strong>Last 7 days:</strong> <?php echo $this->get_recent_subscribers_count(7); ?></p>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Handle newsletter admin actions
     */
    public function handle_newsletter_actions() {
        if (!isset($_GET['page']) || $_GET['page'] !== 'newsletter-subscribers') {
            return;
        }

        if (!isset($_GET['action']) || !isset($_GET['nonce'])) {
            return;
        }

        $action = $_GET['action'];
        $nonce = $_GET['nonce'];

        if ($action === 'remove' && wp_verify_nonce($nonce, 'remove_subscriber')) {
            $index = intval($_GET['index']);
            $subscribers = get_option('ducsu_newsletter_subscribers', []);

            if (isset($subscribers[$index])) {
                unset($subscribers[$index]);
                update_option('ducsu_newsletter_subscribers', array_values($subscribers));
                add_action('admin_notices', function() {
                    echo '<div class="notice notice-success is-dismissible"><p>Subscriber removed successfully.</p></div>';
                });
            }
        }

        if ($action === 'export' && wp_verify_nonce($nonce, 'export_subscribers')) {
            $this->export_subscribers_csv();
        }
    }

    /**
     * Export subscribers as CSV
     */
    private function export_subscribers_csv() {
        $subscribers = get_option('ducsu_newsletter_subscribers', []);

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="newsletter-subscribers-' . date('Y-m-d') . '.csv"');

        $output = fopen('php://output', 'w');

        // Add UTF-8 BOM for proper Excel display
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        fputcsv($output, ['Email', 'Subscribed Date']);

        foreach ($subscribers as $email) {
            fputcsv($output, [$email, date('Y-m-d H:i:s')]);
        }

        fclose($output);
        exit;
    }

    /**
     * Get recent subscribers count
     */
    private function get_recent_subscribers_count($days) {
        // This is a simple implementation
        // In a real scenario, you'd store subscription dates
        return 0;
    }

    /**
     * Add custom columns to candidate post types
     */
    public function add_candidate_columns($columns) {
        $new_columns = [];

        foreach ($columns as $key => $value) {
            $new_columns[$key] = $value;

            if ($key === 'title') {
                $new_columns['candidate_image'] = 'Photo';
                $new_columns['ballot_number'] = 'Ballot #';
                $new_columns['position'] = 'Position';
                $new_columns['department'] = 'Department';
            }
        }

        return $new_columns;
    }

    /**
     * Fill custom columns content
     */
    public function fill_candidate_columns($column, $post_id) {
        switch ($column) {
            case 'candidate_image':
                if (has_post_thumbnail($post_id)) {
                    echo get_the_post_thumbnail($post_id, [50, 50]);
                } else {
                    echo '<div style="width:50px;height:50px;background:#ddd;display:flex;align-items:center;justify-content:center;">📷</div>';
                }
                break;

            case 'ballot_number':
                $ballot = get_post_meta($post_id, '_candidate_ballot_number', true);
                echo $ballot ? esc_html($ballot) : '—';
                break;

            case 'position':
                $position = get_post_meta($post_id, '_candidate_position', true);
                echo $position ? esc_html($position) : '—';
                break;

            case 'department':
                $department = get_post_meta($post_id, '_candidate_department', true);
                echo $department ? esc_html($department) : '—';
                break;
        }
    }

    /**
     * Add admin styles
     */
    public function add_admin_styles() {
        $screen = get_current_screen();

        if ($screen && in_array($screen->post_type, ['central_candidate', 'hall_candidate'])) {
            ?>
            <style>
                .candidate-meta-tabs .subsubsub {
                    margin-bottom: 15px;
                }
                .tab-content {
                    border: 1px solid #ccd0d4;
                    padding: 20px;
                    background: #fff;
                    border-radius: 4px;
                }
                .gallery-image {
                    display: inline-block;
                    margin: 10px;
                    position: relative;
                    border: 1px solid #ddd;
                    border-radius: 4px;
                    overflow: hidden;
                }
                .remove-image {
                    position: absolute;
                    top: -5px;
                    right: -5px;
                    background: #dc3232;
                    color: white;
                    border: none;
                    border-radius: 50%;
                    width: 20px;
                    height: 20px;
                    font-size: 12px;
                    cursor: pointer;
                    line-height: 1;
                }
                .remove-image:hover {
                    background: #a00;
                }
                .column-candidate_image {
                    width: 60px;
                }
                .column-ballot_number {
                    width: 80px;
                }
                .column-position {
                    width: 150px;
                }
                .column-department {
                    width: 120px;
                }
            </style>
            <?php
        }
    }

    /**
     * Add dashboard widgets
     */
    public function add_dashboard_widgets() {
        wp_add_dashboard_widget(
            'ducsu_stats_widget',
            'DUCSU Election Statistics',
            [$this, 'dashboard_stats_widget']
        );
    }

    /**
     * Dashboard stats widget
     */
    public function dashboard_stats_widget() {
        $central_count = wp_count_posts('central_candidate')->publish;
        $hall_count = wp_count_posts('hall_candidate')->publish;
        $manifesto_count = wp_count_posts('manifesto')->publish;
        $subscriber_count = ducsu_get_newsletter_subscriber_count();

        ?>
        <div class="activity-block">
            <h3>Election Overview</h3>
            <ul>
                <li><strong>Central Candidates:</strong> <?php echo $central_count; ?></li>
                <li><strong>Hall Candidates:</strong> <?php echo $hall_count; ?></li>
                <li><strong>Manifesto Items:</strong> <?php echo $manifesto_count; ?></li>
                <li><strong>Newsletter Subscribers:</strong> <?php echo $subscriber_count; ?></li>
            </ul>
        </div>

        <div class="activity-block" style="margin-top: 20px;">
            <h3>Quick Actions</h3>
            <p>
                <a href="<?php echo admin_url('post-new.php?post_type=central_candidate'); ?>" class="button">Add Central Candidate</a>
                <a href="<?php echo admin_url('post-new.php?post_type=hall_candidate'); ?>" class="button">Add Hall Candidate</a>
            </p>
            <p>
                <a href="<?php echo admin_url('edit.php?post_type=central_candidate&page=newsletter-subscribers'); ?>" class="button">View Subscribers</a>
            </p>
        </div>
        <?php
    }

    /**
     * Customize admin footer text
     */
    public function custom_admin_footer_text() {
        return 'DUCSU JCD Election Management System | Version ' . DUCSU_THEME_VERSION;
    }

    /**
     * Add custom admin notices
     */
    public function add_admin_notices() {
        $screen = get_current_screen();

        if ($screen && $screen->post_type === 'central_candidate' && $screen->base === 'edit') {
            $featured_count = $this->get_featured_candidates_count();
            if ($featured_count === 0) {
                ?>
                <div class="notice notice-warning">
                    <p><strong>Notice:</strong> No candidates are marked as featured for the homepage.
                        <a href="<?php echo admin_url('edit.php?post_type=central_candidate'); ?>">Mark some candidates as featured</a> to display them on the homepage.</p>
                </div>
                <?php
            }
        }
    }

    /**
     * Get featured candidates count
     */
    private function get_featured_candidates_count() {
        $args = [
            'post_type' => 'central_candidate',
            'posts_per_page' => -1,
            'meta_query' => [
                [
                    'key' => '_candidate_featured',
                    'value' => '1',
                    'compare' => '='
                ]
            ]
        ];

        $query = new WP_Query($args);
        return $query->found_posts;
    }

    /**
     * Add help tabs
     */
    public function add_help_tabs() {
        $screen = get_current_screen();

        if ($screen && in_array($screen->post_type, ['central_candidate', 'hall_candidate'])) {
            $screen->add_help_tab([
                'id' => 'candidate_help',
                'title' => 'Candidate Management',
                'content' => '
                    <h3>Managing Candidates</h3>
                    <p>Use the tabs in the Candidate Details meta box to organize information:</p>
                    <ul>
                        <li><strong>Basic Info:</strong> Name, position, contact details</li>
                        <li><strong>Education:</strong> SSC, HSC, and graduation information</li>
                        <li><strong>Other Info:</strong> Photo gallery and additional details</li>
                    </ul>
                    <p>Make sure to set a featured image for each candidate and fill out at least the basic information.</p>
                '
            ]);
        }
    }
}