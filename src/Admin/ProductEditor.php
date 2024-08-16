<?php

namespace RabindraPantha\WordPress\WooCommerce\Admin;

use RabindraPantha\WordPress\WooCommerce\WooCommerce\Products;

class ProductEditor {
    public static function init() {
        add_action('admin_menu', [self::class, 'register_menu_page']);
        add_action('admin_enqueue_scripts', [self::class, 'enqueue_scripts']);

        // Register AJAX actions
        add_action('wp_ajax_get_products', [self::class, 'handle_get_products']);
        add_action('wp_ajax_update_product', [self::class, 'handle_update_product']);
    }

    public static function register_menu_page() {
        add_menu_page(
            __('Product Editor', 'woocommerce-product-editor'),
            __('Product Editor', 'woocommerce-product-editor'),
            'manage_woocommerce',
            'woocommerce-product-editor',
            [self::class, 'render_editor_page'],
            'dashicons-edit',
            58
        );
    }

    public static function enqueue_scripts($hook_suffix) {
        if ($hook_suffix === 'toplevel_page_woocommerce-product-editor') {
            // Load Handsontable CSS and JS from a stable version
            wp_enqueue_style('handsontable-css', 'https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.css');
            wp_enqueue_script('handsontable-js', 'https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.js', [], null, true);

            // Load custom CSS and JS
            wp_enqueue_style('woocommerce-product-editor-css', plugin_dir_url(__FILE__) . '../../assets/css/editor.css');
            wp_enqueue_script('woocommerce-product-editor-js', plugin_dir_url(__FILE__) . '../../assets/js/editor.js', ['jquery', 'handsontable-js'], null, true);

            // Localize script
            wp_localize_script('woocommerce-product-editor-js', 'WooCommerceProductEditor', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('woocommerce_product_editor_nonce'),
            ]);
        }
    }

    public static function render_editor_page() {
        echo '<div class="wrap"><h1>' . esc_html__('WooCommerce Product Editor', 'woocommerce-product-editor') . '</h1>';
        echo '<div id="product-editor-table"></div></div>';
    }

    // AJAX handler to get products
    public static function handle_get_products() {
        check_ajax_referer('woocommerce_product_editor_nonce', 'nonce');

        $products = Products::get_products();
        wp_send_json_success($products);
    }

    // AJAX handler to update a product
    public static function handle_update_product() {
        check_ajax_referer('woocommerce_product_editor_nonce', 'nonce');

        $product_id = absint($_POST['product_id']);
        $field = sanitize_text_field($_POST['field']);
        $value = sanitize_text_field($_POST['value']);

        Products::update_product($product_id, [$field => $value]);

        wp_send_json_success();
    }
}
