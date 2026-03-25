<?php

if (! defined('ABSPATH')) {
    exit;
}

class Zalamo_Marketplace_Sessions
{
    public function register_post_type(): void
    {
        register_post_type('zalamo_session', [
            'labels' => [
                'name' => __('Sesje zdjęciowe', 'zalamo-marketplace'),
                'singular_name' => __('Sesja zdjęciowa', 'zalamo-marketplace'),
            ],
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_icon' => 'dashicons-format-gallery',
            'supports' => ['title', 'editor'],
            'capability_type' => 'post',
            'show_in_rest' => true,
        ]);
    }

    public function register_meta_boxes(): void
    {
        add_meta_box(
            'zalamo_session_details',
            __('Szczegóły sesji', 'zalamo-marketplace'),
            [$this, 'render_session_meta_box'],
            'zalamo_session',
            'normal',
            'high'
        );
    }

    public function render_session_meta_box(WP_Post $post): void
    {
        wp_nonce_field('zalamo_save_session_meta', 'zalamo_session_meta_nonce');

        $client_user_id = (int) get_post_meta($post->ID, 'zalamo_client_user_id', true);
        $session_date = (string) get_post_meta($post->ID, 'zalamo_session_date', true);
        $gallery_ids = (string) get_post_meta($post->ID, 'zalamo_gallery_ids', true);

        $users = get_users([
            'orderby' => 'display_name',
            'order' => 'ASC',
        ]);

        echo '<p><label for="zalamo_client_user_id"><strong>' . esc_html__('Klient', 'zalamo-marketplace') . '</strong></label></p>';
        echo '<select name="zalamo_client_user_id" id="zalamo_client_user_id">';
        echo '<option value="0">' . esc_html__('— wybierz klienta —', 'zalamo-marketplace') . '</option>';
        foreach ($users as $user) {
            echo '<option value="' . esc_attr((string) $user->ID) . '" ' . selected($client_user_id, $user->ID, false) . '>' . esc_html($user->display_name . ' (' . $user->user_email . ')') . '</option>';
        }
        echo '</select>';

        echo '<p><label for="zalamo_session_date"><strong>' . esc_html__('Data sesji', 'zalamo-marketplace') . '</strong></label></p>';
        echo '<input type="date" name="zalamo_session_date" id="zalamo_session_date" value="' . esc_attr($session_date) . '" />';

        echo '<p><label for="zalamo_gallery_ids"><strong>' . esc_html__('Zdjęcia (ID z biblioteki mediów)', 'zalamo-marketplace') . '</strong></label></p>';
        echo '<input type="text" class="regular-text" name="zalamo_gallery_ids" id="zalamo_gallery_ids" value="' . esc_attr($gallery_ids) . '" />';
        echo '<button type="button" class="button" id="zalamo_pick_images">' . esc_html__('Dodaj zdjęcia', 'zalamo-marketplace') . '</button>';
        echo '<p class="description">' . esc_html__('Pole przechowuje listę ID obrazów rozdzieloną przecinkami.', 'zalamo-marketplace') . '</p>';

        if (! empty($gallery_ids)) {
            $ids = array_filter(array_map('absint', explode(',', $gallery_ids)));
            if (! empty($ids)) {
                echo '<div style="display:flex;gap:8px;flex-wrap:wrap;">';
                foreach ($ids as $id) {
                    $thumb = wp_get_attachment_image($id, 'thumbnail');
                    if ($thumb) {
                        echo '<div>' . $thumb . '</div>';
                    }
                }
                echo '</div>';
            }
        }
    }

    public function save_session_meta(int $post_id): void
    {
        if (! isset($_POST['zalamo_session_meta_nonce']) || ! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['zalamo_session_meta_nonce'])), 'zalamo_save_session_meta')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (! current_user_can('edit_post', $post_id)) {
            return;
        }

        if (isset($_POST['zalamo_client_user_id'])) {
            update_post_meta($post_id, 'zalamo_client_user_id', absint($_POST['zalamo_client_user_id']));
        }

        if (isset($_POST['zalamo_session_date'])) {
            update_post_meta($post_id, 'zalamo_session_date', sanitize_text_field(wp_unslash($_POST['zalamo_session_date'])));
        }

        if (isset($_POST['zalamo_gallery_ids'])) {
            $raw = sanitize_text_field(wp_unslash($_POST['zalamo_gallery_ids']));
            $ids = array_filter(array_map('absint', explode(',', $raw)));
            update_post_meta($post_id, 'zalamo_gallery_ids', implode(',', $ids));
        }
    }

    public function enqueue_admin_assets(string $hook): void
    {
        if ('post.php' !== $hook && 'post-new.php' !== $hook) {
            return;
        }

        $screen = get_current_screen();
        if (! $screen || 'zalamo_session' !== $screen->post_type) {
            return;
        }

        wp_enqueue_media();
        wp_add_inline_script('jquery-core', "jQuery(function($){
            var frame;
            $('#zalamo_pick_images').on('click', function(e){
                e.preventDefault();
                if (frame) { frame.open(); return; }
                frame = wp.media({ title: 'Wybierz zdjęcia', button: { text: 'Użyj zdjęć' }, multiple: true });
                frame.on('select', function(){
                    var ids = frame.state().get('selection').map(function(attachment){ return attachment.id; }).join(',');
                    $('#zalamo_gallery_ids').val(ids);
                });
                frame.open();
            });
        });");
    }
}
