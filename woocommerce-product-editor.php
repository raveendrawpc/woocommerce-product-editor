<?php

/**
 * Plugin Name: WooCommerce Product Editor
 * Description: Edit WooCommerce products in a spreadsheet-like environment.
 * Version: 1.0.0
 * Author: Rabindra Pantha
 * Text Domain: woocommerce-product-editor
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Autoload dependencies.
require_once __DIR__ . '/vendor/autoload.php';

// Register the Admin Product Editor
use RabindraPantha\WordPress\WooCommerce\Admin\ProductEditor;

ProductEditor::init();
