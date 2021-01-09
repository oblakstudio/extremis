<?php

namespace Extremis;

use Extremis\Container;

/**
 * Helper function for prettying up errors
 * @param string $message
 * @param string $subtitle
 * @param string $title
 */
$extremis_error = function ($message, $subtitle = '', $title = '') {
    $title = $title ?: __('Extremis &rsaquo; Error', 'extremis');
    $footer = '<a href="https://roots.io/sage/docs/">roots.io/sage/docs/</a>';
    $message = "<h1>{$title}<br><small>{$subtitle}</small></h1><p>{$message}</p><p>{$footer}</p>";
    wp_die($message, $title);
};

/**
 * Ensure compatible version of PHP is used
 */
if (version_compare('7.4', phpversion(), '>=')) {
    $extremis_error(__('You must be using PHP 7.1 or greater.', 'extremis'), __('Invalid PHP version', 'extremis'));
}

/**
 * Ensure compatible version of WordPress is used
 */
if (version_compare('5.3', get_bloginfo('version'), '>=')) {
    $extremis_error(__('You must be using WordPress 5.3.0 or greater.', 'extremis'), __('Invalid WordPress version', 'extremis'));
}

/**
 * Ensure dependencies are loaded
 */
if (!class_exists('Extremis\\Container')) :
    if (!file_exists($composer = __DIR__.'/vendor/autoload.php')) :
        $extremis_error(
            __('You must run <code>composer install</code> from the Sage directory.', 'extremis'),
            __('Autoloader not found.', 'extremis')
        );
    endif;
    require_once $composer;
endif;

/**
 * Extremis required files
 *
 * The mapped array determines the code library included in your theme.
 * Add or remove files to the array as needed.
 */
array_map(function ($file) use ($extremis_error) {
    $file = "/framework/Utils/{$file}.php";
    if (!locate_template($file, true, true)) {
        $extremis_error(sprintf(__('Error locating <code>%s</code> for inclusion.', 'extremis'), $file), 'File not found');
    }
}, ['helpers', 'filters', 'setup']);

Container::getInstance()
    ->bindIf('config', function () {
        return new Config([
            'modules' => require_once locate_template('/config/modules.php'),
            'assets'  => require_once locate_template('/config/assets.php'),
        ]);
    }, true);
