<?php

namespace RabindraPantha\WordPress\WooCommerce\WooCommerce;

class Products {
    public static function get_products() {
        // Fetch WooCommerce products and return them in an array.
        $args = [
            'limit' => -1,
            'return' => 'ids',
        ];

        $products = wc_get_products($args);

        $product_data = [];

        foreach ($products as $product_id) {
            $product = wc_get_product($product_id);

            $product_data[] = [
                'ID' => $product->get_id(),
                'name' => $product->get_name(),
                'price' => $product->get_price(),
                'stock' => $product->get_stock_quantity(),
            ];
        }

        return $product_data;
    }

    public static function update_product($product_id, $data) {
        // Update WooCommerce product data here.
        $product = wc_get_product($product_id);

        if (isset($data['name'])) {
            $product->set_name($data['name']);
        }

        if (isset($data['price'])) {
            $product->set_price($data['price']);
        }

        if (isset($data['stock'])) {
            $product->set_stock_quantity($data['stock']);
        }

        $product->save();
    }
}
