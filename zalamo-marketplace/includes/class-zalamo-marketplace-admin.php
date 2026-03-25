<?php

if (! defined('ABSPATH')) {
    exit;
}

class Zalamo_Marketplace_Admin
{
    public function register_menu_pages(): void
    {
        add_menu_page(
            __('Zalamo Marketplace', 'zalamo-marketplace'),
            __('Zalamo Marketplace', 'zalamo-marketplace'),
            'read',
            'zalamo-marketplace-photographer',
            [$this, 'render_photographer_page'],
            'dashicons-camera',
            26
        );

        add_submenu_page(
            'zalamo-marketplace-photographer',
            __('Panel fotografa', 'zalamo-marketplace'),
            __('Panel fotografa', 'zalamo-marketplace'),
            'read',
            'zalamo-marketplace-photographer',
            [$this, 'render_photographer_page']
        );

        add_submenu_page(
            'zalamo-marketplace-photographer',
            __('Panel klienta', 'zalamo-marketplace'),
            __('Panel klienta', 'zalamo-marketplace'),
            'read',
            'zalamo-marketplace-client',
            [$this, 'render_client_page']
        );
    }

    public function render_photographer_page(): void
    {
        $this->render_tabs('zalamo-marketplace-photographer');
        echo '<h1>' . esc_html__('Panel fotografa', 'zalamo-marketplace') . '</h1>';
        echo '<p>' . esc_html__('Tutaj możesz zarządzać sesjami, portfolio i publikacją zdjęć produktów.', 'zalamo-marketplace') . '</p>';
        echo '</div>';
    }

    public function render_client_page(): void
    {
        $this->render_tabs('zalamo-marketplace-client');
        echo '<h1>' . esc_html__('Panel klienta', 'zalamo-marketplace') . '</h1>';
        echo '<p>' . esc_html__('Tutaj klient może śledzić zamówienia sesji, akceptować zdjęcia i pobierać materiały.', 'zalamo-marketplace') . '</p>';
        echo '</div>';
    }

    private function render_tabs(string $current_slug): void
    {
        $tabs = [
            'zalamo-marketplace-photographer' => __('Panel fotografa', 'zalamo-marketplace'),
            'zalamo-marketplace-client' => __('Panel klienta', 'zalamo-marketplace'),
        ];

        echo '<div class="wrap">';
        echo '<nav class="nav-tab-wrapper">';

        foreach ($tabs as $slug => $label) {
            $class = $slug === $current_slug ? ' nav-tab-active' : '';
            $url = admin_url('admin.php?page=' . $slug);
            echo '<a class="nav-tab' . esc_attr($class) . '" href="' . esc_url($url) . '">' . esc_html($label) . '</a>';
        }

        echo '</nav>';
    }
}
