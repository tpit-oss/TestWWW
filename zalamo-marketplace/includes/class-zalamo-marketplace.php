<?php

if (! defined('ABSPATH')) {
    exit;
}

require_once ZALAMO_MARKETPLACE_PATH . 'includes/class-zalamo-marketplace-post-types.php';
require_once ZALAMO_MARKETPLACE_PATH . 'includes/class-zalamo-marketplace-vendors.php';
require_once ZALAMO_MARKETPLACE_PATH . 'includes/class-zalamo-marketplace-shortcodes.php';
require_once ZALAMO_MARKETPLACE_PATH . 'includes/class-zalamo-marketplace-admin.php';
require_once ZALAMO_MARKETPLACE_PATH . 'includes/class-zalamo-marketplace-sessions.php';

class Zalamo_Marketplace
{
    public function run(): void
    {
        $post_types = new Zalamo_Marketplace_Post_Types();
        $vendors = new Zalamo_Marketplace_Vendors();
        $shortcodes = new Zalamo_Marketplace_Shortcodes();
        $admin = new Zalamo_Marketplace_Admin();
        $sessions = new Zalamo_Marketplace_Sessions();

        add_action('init', [$post_types, 'register']);
        add_action('init', [$vendors, 'register_vendor_role']);
        add_action('show_user_profile', [$vendors, 'render_vendor_fields']);
        add_action('edit_user_profile', [$vendors, 'render_vendor_fields']);
        add_action('personal_options_update', [$vendors, 'save_vendor_fields']);
        add_action('edit_user_profile_update', [$vendors, 'save_vendor_fields']);

        add_shortcode('zalamo_storefront', [$shortcodes, 'storefront']);
        add_shortcode('zalamo_vendor_dashboard', [$shortcodes, 'vendor_dashboard']);

        add_action('admin_menu', [$admin, 'register_menu_pages']);

        add_action('init', [$sessions, 'register_post_type']);
        add_action('add_meta_boxes', [$sessions, 'register_meta_boxes']);
        add_action('save_post_zalamo_session', [$sessions, 'save_session_meta']);
        add_action('admin_enqueue_scripts', [$sessions, 'enqueue_admin_assets']);
    }
}
