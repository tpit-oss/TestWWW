<?php

if (! defined('ABSPATH')) {
    exit;
}

class Zalamo_Marketplace_Shortcodes
{
    public function storefront(array $atts = []): string
    {
        $atts = shortcode_atts([
            'per_page' => 12,
            'brand' => '',
        ], $atts, 'zalamo_storefront');

        $query_args = [
            'post_type' => 'zalamo_product',
            'posts_per_page' => absint($atts['per_page']),
            'post_status' => 'publish',
        ];

        if (! empty($atts['brand'])) {
            $query_args['tax_query'] = [
                [
                    'taxonomy' => 'zalamo_brand',
                    'field' => 'slug',
                    'terms' => sanitize_title($atts['brand']),
                ],
            ];
        }

        $query = new WP_Query($query_args);

        ob_start();
        echo '<div class="zalamo-grid">';

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $price = get_post_meta(get_the_ID(), 'zalamo_price', true);
                echo '<article class="zalamo-card">';
                echo '<h3><a href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . '</a></h3>';
                echo '<p>' . esc_html(wp_trim_words(get_the_excerpt(), 18)) . '</p>';
                if (! empty($price)) {
                    echo '<strong>' . esc_html(number_format((float) $price, 2, ',', ' ')) . ' zł</strong>';
                }
                echo '</article>';
            }
            wp_reset_postdata();
        } else {
            echo '<p>' . esc_html__('Brak produktów.', 'zalamo-marketplace') . '</p>';
        }

        echo '</div>';
        return (string) ob_get_clean();
    }

    public function vendor_dashboard(): string
    {
        if (! is_user_logged_in()) {
            return '<p>' . esc_html__('Zaloguj się jako sprzedawca, aby zobaczyć panel.', 'zalamo-marketplace') . '</p>';
        }

        $current_user = wp_get_current_user();

        if (! in_array('zalamo_vendor', (array) $current_user->roles, true) && ! current_user_can('manage_options')) {
            return '<p>' . esc_html__('Nie masz uprawnień sprzedawcy.', 'zalamo-marketplace') . '</p>';
        }

        $query = new WP_Query([
            'post_type' => 'zalamo_product',
            'posts_per_page' => 20,
            'author' => $current_user->ID,
            'post_status' => ['publish', 'draft', 'pending'],
        ]);

        ob_start();
        echo '<h2>' . esc_html__('Twój panel sprzedawcy', 'zalamo-marketplace') . '</h2>';
        echo '<p><a href="' . esc_url(admin_url('post-new.php?post_type=zalamo_product')) . '">' . esc_html__('Dodaj nowy produkt', 'zalamo-marketplace') . '</a></p>';

        if ($query->have_posts()) {
            echo '<ul>';
            while ($query->have_posts()) {
                $query->the_post();
                echo '<li>' . esc_html(get_the_title()) . ' (' . esc_html(get_post_status(get_the_ID())) . ')</li>';
            }
            echo '</ul>';
            wp_reset_postdata();
        } else {
            echo '<p>' . esc_html__('Nie masz jeszcze produktów.', 'zalamo-marketplace') . '</p>';
        }

        return (string) ob_get_clean();
    }
}
