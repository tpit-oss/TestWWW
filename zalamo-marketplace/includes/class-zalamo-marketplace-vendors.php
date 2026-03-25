<?php

if (! defined('ABSPATH')) {
    exit;
}

class Zalamo_Marketplace_Vendors
{
    public function register_vendor_role(): void
    {
        if (null === get_role('zalamo_vendor')) {
            add_role(
                'zalamo_vendor',
                __('Sprzedawca Marketplace', 'zalamo-marketplace'),
                [
                    'read' => true,
                    'upload_files' => true,
                    'edit_posts' => true,
                    'publish_posts' => true,
                ]
            );
        }
    }

    public function render_vendor_fields(WP_User $user): void
    {
        if (! in_array('zalamo_vendor', (array) $user->roles, true) && ! current_user_can('manage_options')) {
            return;
        }

        $shop_name = get_user_meta($user->ID, 'zalamo_shop_name', true);
        $shop_description = get_user_meta($user->ID, 'zalamo_shop_description', true);
        ?>
        <h2><?php esc_html_e('Dane sklepu sprzedawcy', 'zalamo-marketplace'); ?></h2>
        <table class="form-table" role="presentation">
            <tr>
                <th><label for="zalamo_shop_name"><?php esc_html_e('Nazwa sklepu', 'zalamo-marketplace'); ?></label></th>
                <td>
                    <input type="text" name="zalamo_shop_name" id="zalamo_shop_name" value="<?php echo esc_attr($shop_name); ?>" class="regular-text" />
                </td>
            </tr>
            <tr>
                <th><label for="zalamo_shop_description"><?php esc_html_e('Opis sklepu', 'zalamo-marketplace'); ?></label></th>
                <td>
                    <textarea name="zalamo_shop_description" id="zalamo_shop_description" rows="5" class="regular-text"><?php echo esc_textarea($shop_description); ?></textarea>
                </td>
            </tr>
        </table>
        <?php
    }

    public function save_vendor_fields(int $user_id): void
    {
        if (! current_user_can('edit_user', $user_id)) {
            return;
        }

        if (isset($_POST['zalamo_shop_name'])) {
            update_user_meta($user_id, 'zalamo_shop_name', sanitize_text_field(wp_unslash($_POST['zalamo_shop_name'])));
        }

        if (isset($_POST['zalamo_shop_description'])) {
            update_user_meta($user_id, 'zalamo_shop_description', sanitize_textarea_field(wp_unslash($_POST['zalamo_shop_description'])));
        }
    }
}
