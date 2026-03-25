<?php

if (! defined('ABSPATH')) {
    exit;
}

require_once ZALAMO_MARKETPLACE_PATH . 'includes/class-zalamo-marketplace-post-types.php';
require_once ZALAMO_MARKETPLACE_PATH . 'includes/class-zalamo-marketplace-vendors.php';
require_once ZALAMO_MARKETPLACE_PATH . 'includes/class-zalamo-marketplace-shortcodes.php';
require_once ZALAMO_MARKETPLACE_PATH . 'includes/class-zalamo-marketplace-admin.php';

class Zalamo_Marketplace
{
    public function run(): void
    {
        $post_types = new Zalamo_Marketplace_Post_Types();
        $vendors = new Zalamo_Marketplace_Vendors();
        $shortcodes = new Zalamo_Marketplace_Shortcodes();
        $admin = new Zalamo_Marketplace_Admin();

        add_action('init', [$post_types, 'register']);
        add_action('init', [$vendors, 'register_vendor_role']);
        add_action('show_user_profile', [$vendors, 'render_vendor_fields']);
        add_action('edit_user_profile', [$vendors, 'render_vendor_fields']);
        add_action('personal_options_update', [$vendors, 'save_vendor_fields']);
        add_action('edit_user_profile_update', [$vendors, 'save_vendor_fields']);

        add_shortcode('zalamo_storefront', [$shortcodes, 'storefront']);
        add_shortcode('zalamo_vendor_dashboard', [$shortcodes, 'vendor_dashboard']);

        add_action('admin_menu', [$admin, 'register_menu_pages']);
    }
}
