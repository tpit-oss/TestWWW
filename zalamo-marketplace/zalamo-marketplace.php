<?php
/**
 * Plugin Name: Zalamo Marketplace
 * Plugin URI: https://example.com
 * Description: Lekki plugin marketplace inspirowany modelem Zalando: sprzedawcy, produkty i prosty storefront.
 * Version: 0.1.0
 * Author: Codex
 * License: GPL-2.0+
 * Text Domain: zalamo-marketplace
 */

if (! defined('ABSPATH')) {
    exit;
}

define('ZALAMO_MARKETPLACE_VERSION', '0.1.0');
define('ZALAMO_MARKETPLACE_PATH', plugin_dir_path(__FILE__));
define('ZALAMO_MARKETPLACE_URL', plugin_dir_url(__FILE__));

require_once ZALAMO_MARKETPLACE_PATH . 'includes/class-zalamo-marketplace.php';

function zalamo_marketplace_bootstrap(): void
{
    $plugin = new Zalamo_Marketplace();
    $plugin->run();
}

add_action('plugins_loaded', 'zalamo_marketplace_bootstrap');
