<?php

if (! defined('ABSPATH')) {
    exit;
}

class Zalamo_Marketplace_Post_Types
{
    public function register(): void
    {
        $this->register_product_type();
        $this->register_brand_taxonomy();
    }

    private function register_product_type(): void
    {
        register_post_type('zalamo_product', [
            'labels' => [
                'name' => __('Produkty Marketplace', 'zalamo-marketplace'),
                'singular_name' => __('Produkt Marketplace', 'zalamo-marketplace'),
            ],
            'public' => true,
            'has_archive' => true,
            'menu_icon' => 'dashicons-store',
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
            'rewrite' => ['slug' => 'produkty'],
            'show_in_rest' => true,
        ]);
    }

    private function register_brand_taxonomy(): void
    {
        register_taxonomy('zalamo_brand', ['zalamo_product'], [
            'labels' => [
                'name' => __('Marki', 'zalamo-marketplace'),
                'singular_name' => __('Marka', 'zalamo-marketplace'),
            ],
            'public' => true,
            'hierarchical' => false,
            'show_in_rest' => true,
            'rewrite' => ['slug' => 'marka'],
        ]);
    }
}
