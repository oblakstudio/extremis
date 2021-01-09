<?php declare(strict_types = 1);

/**
 * Add <body> classes
 */
add_filter('body_class', function (array $classes) : array
{

    /** Add page slug if it doesn't exist */
    if (is_single() || is_page() && !is_front_page()) {
        if (!in_array(basename(get_permalink()), $classes)) {
            $classes[] = basename(get_permalink());
        }
    }

    return array_filter($classes);
});
