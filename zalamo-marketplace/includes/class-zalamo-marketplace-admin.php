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
            'zalamo-marketplace-client',
            [$this, 'render_client_page'],
            'dashicons-camera',
            26
        );

        add_submenu_page(
            'zalamo-marketplace-client',
            __('Panel klienta', 'zalamo-marketplace'),
            __('Panel klienta', 'zalamo-marketplace'),
            'read',
            'zalamo-marketplace-client',
            [$this, 'render_client_page']
        );

        add_submenu_page(
            'zalamo-marketplace-client',
            __('Panel fotografa', 'zalamo-marketplace'),
            __('Panel fotografa', 'zalamo-marketplace'),
            'manage_options',
            'zalamo-marketplace-photographer',
            [$this, 'render_photographer_page']
        );
    }

    public function render_photographer_page(): void
    {
        if (! current_user_can('manage_options')) {
            wp_die(esc_html__('Nie masz uprawnień do panelu fotografa.', 'zalamo-marketplace'));
        }

        $this->render_tabs('zalamo-marketplace-photographer');
        echo '<h1>' . esc_html__('Panel fotografa', 'zalamo-marketplace') . '</h1>';
        echo '<p>' . esc_html__('Twórz sesje zdjęciowe i dodawaj galerie dla klientów.', 'zalamo-marketplace') . '</p>';
        echo '<p><a class="button button-primary" href="' . esc_url(admin_url('post-new.php?post_type=zalamo_session')) . '">' . esc_html__('Utwórz nową sesję', 'zalamo-marketplace') . '</a></p>';
        echo '</div>';
    }

    public function render_client_page(): void
    {
        $this->render_tabs('zalamo-marketplace-client');
        echo '<h1>' . esc_html__('Panel klienta', 'zalamo-marketplace') . '</h1>';
        echo '<p>' . esc_html__('Tutaj zobaczysz przypisane do Ciebie sesje i zdjęcia.', 'zalamo-marketplace') . '</p>';

        $query = new WP_Query([
            'post_type' => 'zalamo_session',
            'posts_per_page' => 20,
            'post_status' => ['publish', 'draft'],
            'meta_key' => 'zalamo_client_user_id',
            'meta_value' => get_current_user_id(),
        ]);

        if ($query->have_posts()) {
            echo '<ul>';
            while ($query->have_posts()) {
                $query->the_post();
                echo '<li><a href="' . esc_url(get_edit_post_link(get_the_ID())) . '">' . esc_html(get_the_title()) . '</a></li>';
            }
            echo '</ul>';
            wp_reset_postdata();
        } else {
            echo '<p>' . esc_html__('Brak przypisanych sesji.', 'zalamo-marketplace') . '</p>';
        }

        echo '</div>';
    }

    private function render_tabs(string $current_slug): void
    {
        $tabs = [
            'zalamo-marketplace-client' => [
                'label' => __('Panel klienta', 'zalamo-marketplace'),
                'cap' => 'read',
            ],
            'zalamo-marketplace-photographer' => [
                'label' => __('Panel fotografa', 'zalamo-marketplace'),
                'cap' => 'manage_options',
            ],
        ];

        echo '<div class="wrap">';
        echo '<nav class="nav-tab-wrapper">';

        foreach ($tabs as $slug => $tab) {
            if (! current_user_can($tab['cap'])) {
                continue;
            }

            $class = $slug === $current_slug ? ' nav-tab-active' : '';
            $url = admin_url('admin.php?page=' . $slug);
            echo '<a class="nav-tab' . esc_attr($class) . '" href="' . esc_url($url) . '">' . esc_html($tab['label']) . '</a>';
        }

        echo '</nav>';
    }
}
