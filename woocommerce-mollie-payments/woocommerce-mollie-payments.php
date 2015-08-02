<?php
/**
 * Plugin Name: WooCommerce Mollie Payments
 * Plugin URI: https://github.com/mollie/WooCommerce
 * Description: Mollie payments for WooCommerce
 * Version: 2.0
 * Author: Mollie
 * Author URI: https://www.mollie.com
 * Requires at least: 3.8
 * Tested up to: 3.8
 * Text Domain: woocommerce-mollie-payments
 * Domain Path: /i18n/languages/
 * License: http://www.opensource.org/licenses/bsd-license.php  Berkeley Software Distribution License (BSD-License 2)
 */
require_once 'includes/mollie/wc/autoload.php';

load_plugin_textdomain('woocommerce-mollie-payments', false, 'woocommerce-mollie-payments/i18n/languages');

/**
 * Called when plugin is loaded
 */
function mollie_wc_plugin_init ()
{
    // Register Mollie autoloader
    Mollie_WC_Autoload::register();

    // Setup and start plugin
    Mollie_WC_Plugin::init();
}

/**
 * Called when plugin is activated
 */
function mollie_wc_plugin_activation_hook ()
{
    // WooCommerce plugin not activated
    if (!is_plugin_active('woocommerce/woocommerce.php'))
    {
        $title = sprintf(
            __('Could not activate plugin %s', 'woocommerce-mollie-payments'),
            'WooCommerce Mollie Payments'
        );
        $message = ''
            . '<h1><strong>' . $title . '</strong></h1><br/>'
            . 'WooCommerce plugin not activated. Please activate WooCommerce plugin first.';

        wp_die($message, $title, array('back_link' => true));
        return;
    }

    // Register Mollie autoloader
    Mollie_WC_Autoload::register();

    $status_helper = Mollie_WC_Plugin::getStatusHelper();

    if (!$status_helper->isCompatible())
    {
        $title   = 'Could not activate WooCommerce Mollie Payments plugin';
        $message = '<h1><strong>Could not activate WooCommerce Mollie Payments plugin</strong></h1><br/>'
                 . implode('<br/>', $status_helper->getErrors());

        wp_die($message, $title, array('back_link' => true));
        return;
    }
}

/**
 * Called when admin is initialised
 */
function mollie_wc_plugin_admin_init ()
{
    // WooCommerce plugin not activated
    if (!is_plugin_active('woocommerce/woocommerce.php'))
    {
        // Deactivate myself
        deactivate_plugins(plugin_basename(__FILE__));

        add_action('admin_notices', 'mollie_wc_plugin_deactivated');
    }
}

function mollie_wc_plugin_deactivated ()
{
    echo '<div class="error"><p>' . sprintf(__('%s deactivated because it depends on WooCommerce.', 'woocommerce-mollie-payments'), 'WooCommerce Mollie Payments') . '</p></div>';
}

register_activation_hook(__FILE__, 'mollie_wc_plugin_activation_hook');

add_action('admin_init', 'mollie_wc_plugin_admin_init');
add_action('init', 'mollie_wc_plugin_init');

/*
 * Info link
 * - WooCommerce hooks: http://docs.woothemes.com/wc-apidocs/hook-docs.html
 * - The WordPress / WooCommerce Hook/API Index: http://hookr.io/
 */
