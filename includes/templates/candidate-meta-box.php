<?php
/**
 * Candidate Meta Box Template
 */

if (!defined('ABSPATH')) {
    exit;
}

$fields = [
    '_candidate_name_bangla' => 'Name (Bangla)',
    '_candidate_position' => 'Position (Bangla)',
    '_candidate_ballot_number' => 'Ballot Number (Bangla)',
    '_candidate_department' => 'Department',
    '_candidate_hall' => 'Hall',
    '_candidate_session' => 'Session',
    '_candidate_father_name' => "Father's Name (Bangla)",
    '_candidate_father_profession' => "Father's Profession (Bangla)",
    '_candidate_mother_name' => "Mother's Name (Bangla)",
    '_candidate_mother_profession' => "Mother's Profession (Bangla)",
    '_candidate_permanent_address' => 'Permanent Address (Bangla)',
    '_candidate_special_achievements' => 'Special Achievements (Bangla)',
    '_candidate_political_journey' => 'Political Journey (Bangla)',
    '_candidate_vision' => 'Vision (Bangla)',
    '_candidate_facebook_url' => 'Facebook URL',
    '_candidate_twitter_url' => 'Twitter URL',
    '_candidate_email' => 'Candidate Email'
];

// SSC fields
$ssc_fields = [
    '_candidate_ssc_school' => 'SSC School Name',
    '_candidate_ssc_gpa' => 'SSC GPA',
    '_candidate_ssc_year' => 'SSC Passing Year'
];

// HSC fields
$hsc_fields = [
    '_candidate_hsc_college' => 'HSC College Name',
    '_candidate_hsc_gpa' => 'HSC GPA',
    '_candidate_hsc_year' => 'HSC Passing Year'
];

// Graduation fields
$graduation_fields = [
    '_candidate_graduation_university' => 'University Name',
    '_candidate_graduation_cgpa' => 'CGPA',
    '_candidate_graduation_year' => 'Graduation Year',
    '_candidate_graduation_subject' => 'Subject'
];

echo '<div class="candidate-meta-tabs">';
echo '<ul class="subsubsub">';
echo '<li><a href="#basic-info" class="current">Basic Info</a> |</li>';
echo '<li><a href="#education">Education</a> |</li>';
echo '<li><a href="#other-info">Other Info</a></li>';
echo '</ul>';

echo '<div id="basic-info" class="tab-content">';
echo '<table class="form-table">';
foreach ($fields as $key => $label) {
    $value = get_post_meta($post->ID, $key, true);
    $type = (strpos($key, 'url') !== false || strpos($key, 'email') !== false) ?
        (strpos($key, 'email') !== false ? 'email' : 'url') : 'text';

    if (in_array($key, ['_candidate_special_achievements', '_candidate_political_journey', '_candidate_vision', '_candidate_permanent_address'])) {
        echo '<tr><th><label for="' . esc_attr($key) . '">' . esc_html($label) . '</label></th>';
        echo '<td><textarea id="' . esc_attr($key) . '" name="' . esc_attr($key) . '" class="widefat" rows="4">' . esc_textarea($value) . '</textarea></td></tr>';
    } else {
        echo '<tr><th><label for="' . esc_attr($key) . '">' . esc_html($label) . '</label></th>';
        echo '<td><input type="' . $type . '" id="' . esc_attr($key) . '" name="' . esc_attr($key) . '" value="' . esc_attr($value) . '" class="widefat" /></td></tr>';
    }
}
echo '</table>';
echo '</div>';

echo '<div id="education" class="tab-content" style="display:none;">';
echo '<h3>Educational Information</h3>';

// SSC Section
echo '<h4 style="color: #0073aa; margin-top: 20px;">SSC Information</h4>';
echo '<table class="form-table">';
foreach ($ssc_fields as $key => $label) {
    $value = get_post_meta($post->ID, $key, true);
    echo '<tr><th><label for="' . esc_attr($key) . '">' . esc_html($label) . '</label></th>';
    echo '<td><input type="text" id="' . esc_attr($key) . '" name="' . esc_attr($key) . '" value="' . esc_attr($value) . '" class="widefat" /></td></tr>';
}
echo '</table>';

// HSC Section
echo '<h4 style="color: #0073aa; margin-top: 20px;">HSC Information</h4>';
echo '<table class="form-table">';
foreach ($hsc_fields as $key => $label) {
    $value = get_post_meta($post->ID, $key, true);
    echo '<tr><th><label for="' . esc_attr($key) . '">' . esc_html($label) . '</label></th>';
    echo '<td><input type="text" id="' . esc_attr($key) . '" name="' . esc_attr($key) . '" value="' . esc_attr($value) . '" class="widefat" /></td></tr>';
}
echo '</table>';

// Graduation Section
echo '<h4 style="color: #0073aa; margin-top: 20px;">Graduation Information</h4>';
echo '<table class="form-table">';
foreach ($graduation_fields as $key => $label) {
    $value = get_post_meta($post->ID, $key, true);
    echo '<tr><th><label for="' . esc_attr($key) . '">' . esc_html($label) . '</label></th>';
    echo '<td><input type="text" id="' . esc_attr($key) . '" name="' . esc_attr($key) . '" value="' . esc_attr($value) . '" class="widefat" /></td></tr>';
}
echo '</table>';
echo '</div>';

echo '<div id="other-info" class="tab-content" style="display:none;">';
// Image Gallery Section
echo '<h3>Image Gallery (Max 5 images)</h3>';
echo '<div id="candidate-gallery" style="margin-bottom: 15px;">';
$gallery_images = get_post_meta($post->ID, '_candidate_gallery', true);
if ($gallery_images) {
    $images = explode(',', $gallery_images);
    foreach ($images as $image_id) {
        if ($image_id && trim($image_id)) {
            $image_url = wp_get_attachment_image_src($image_id, 'thumbnail');
            if ($image_url) {
                echo '<div class="gallery-image" data-id="' . esc_attr(trim($image_id)) . '" style="display: inline-block; margin: 5px; position: relative; border: 1px solid #ddd; padding: 5px;">';
                echo '<img src="' . esc_url($image_url[0]) . '" width="100" height="100" style="display: block;" />';
                echo '<button type="button" class="remove-image" style="position: absolute; top: 0; right: 0; background: #dc3232; color: white; border: none; border-radius: 50%; width: 20px; height: 20px; font-size: 12px; cursor: pointer;">×</button>';
                echo '</div>';
            }
        }
    }
}
echo '</div>';
echo '<input type="hidden" id="candidate_gallery" name="candidate_gallery" value="' . esc_attr($gallery_images) . '" />';
echo '<button type="button" id="add-gallery-image" class="button">Add Images to Gallery</button>';
echo '<p class="description">You can select up to 5 images for the gallery.</p>';
echo '</div>';

echo '</div>'; // Close tabs container

// Add JavaScript for tabs and gallery management
?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            // Tab functionality
            $(".subsubsub a").click(function(e) {
                e.preventDefault();
                $(".subsubsub a").removeClass("current");
                $(this).addClass("current");

                var target = $(this).attr("href");
                $(".tab-content").hide();
                $(target).show();
            });

            // Gallery functionality
            var galleryFrame;

            $("#add-gallery-image").click(function(e) {
                e.preventDefault();

                // Check current image count
                var currentIds = $("#candidate_gallery").val();
                var currentCount = currentIds ? currentIds.split(',').filter(function(id) {
                    return id.trim() !== '';
                }).length : 0;

                if (currentCount >= 5) {
                    alert('Maximum 5 images allowed in gallery.');
                    return;
                }

                if (galleryFrame) {
                    galleryFrame.open();
                    return;
                }

                galleryFrame = wp.media({
                    title: 'Select Gallery Images',
                    button: {
                        text: 'Add to Gallery'
                    },
                    multiple: true,
                    library: {
                        type: 'image'
                    }
                });

                galleryFrame.on('select', function() {
                    var selection = galleryFrame.state().get('selection');
                    var gallery_ids = $("#candidate_gallery").val();
                    var ids_array = gallery_ids ? gallery_ids.split(',').filter(function(id) {
                        return id.trim() !== '';
                    }) : [];

                    var added = 0;
                    selection.each(function(attachment) {
                        var attachment_id = attachment.id;

                        // Check if image already exists
                        if (ids_array.indexOf(attachment_id.toString()) === -1 && ids_array.length + added < 5) {
                            ids_array.push(attachment_id);
                            added++;

                            var thumbnail_url = attachment.attributes.sizes && attachment.attributes.sizes.thumbnail ?
                                attachment.attributes.sizes.thumbnail.url :
                                attachment.attributes.url;

                            $("#candidate-gallery").append(
                                '<div class="gallery-image" data-id="' + attachment_id + '" style="display: inline-block; margin: 5px; position: relative; border: 1px solid #ddd; padding: 5px;">' +
                                '<img src="' + thumbnail_url + '" width="100" height="100" style="display: block;" />' +
                                '<button type="button" class="remove-image" style="position: absolute; top: 0; right: 0; background: #dc3232; color: white; border: none; border-radius: 50%; width: 20px; height: 20px; font-size: 12px; cursor: pointer;">×</button>' +
                                '</div>'
                            );
                        }
                    });

                    $("#candidate_gallery").val(ids_array.join(','));

                    if (added === 0) {
                        alert('No new images were added. Either they already exist in the gallery or the maximum limit (5) has been reached.');
                    }
                });

                galleryFrame.open();
            });

            // Remove image from gallery
            $(document).on("click", ".remove-image", function(e) {
                e.preventDefault();

                var image_div = $(this).closest('.gallery-image');
                var image_id = image_div.data("id");
                var gallery_ids = $("#candidate_gallery").val();

                if (gallery_ids) {
                    var ids_array = gallery_ids.split(",");
                    var index = ids_array.indexOf(image_id.toString());

                    if (index > -1) {
                        ids_array.splice(index, 1);
                    }

                    $("#candidate_gallery").val(ids_array.filter(function(id) {
                        return id.trim() !== '';
                    }).join(","));
                }

                image_div.remove();
            });
        });
    </script>
<?php