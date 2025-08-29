<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


if (!function_exists('beluxe_register_newdev_rules')) {
    function beluxe_register_newdev_rules() {
        $taxonomy = 'property-type'; 
        $term_slug = 'new-developments';  

        // new-developments/
        add_rewrite_rule(
            '^' . preg_quote($term_slug, '#') . '/?$',
            'index.php?' . $taxonomy . '=' . $term_slug,
            'top'
        );

        // new-developments/page/2/
        add_rewrite_rule(
            '^' . preg_quote($term_slug, '#') . '/page/([0-9]{1,})/?$',
            'index.php?' . $taxonomy . '=' . $term_slug . '&paged=$matches[1]',
            'top'
        );

    }
}

add_action('init', 'beluxe_register_newdev_rules');

// Output the top-level URL for that single term everywhere.
add_filter('term_link', function ($url, $term, $taxonomy) {
    $target_tax  = 'property-type';    
    $target_slug = 'new-developments'; 

    if ($taxonomy === $target_tax && is_object($term) && $term->slug === $target_slug) {
        return home_url('/' . $target_slug . '/');
    }
    return $url;
}, 10, 3);

// Canonical redirect old /property-type/new-developments/ to top-level,
add_filter('redirect_canonical', function ($redirect, $request) {
    $target_tax  = 'property-type';    
    $target_slug = 'new-developments';  

    if (is_tax($target_tax, $target_slug)) {
        $base  = home_url('/' . $target_slug . '/');
        $feed  = get_query_var('feed');
        $paged = max(1, (int) get_query_var('paged'));

        if (!empty($feed)) {
            return trailingslashit($base . $feed);
        }
        if ($paged > 1) {
            return trailingslashit($base . 'page/' . $paged);
        }
        return $base;
    }
    return $redirect;
}, 10, 2);

// Flush rewrite rules on theme switch
add_action('after_switch_theme', function () {
    beluxe_register_newdev_rules();
    flush_rewrite_rules();
});

// Warn in admin if a page/post slug conflicts
add_action('admin_notices', function () {
    $target_slug = 'new-developments'; // <-- change if needed

    if (get_page_by_path($target_slug, OBJECT, ['page', 'post'])) {
        echo '<div class="notice notice-error"><p><strong>URL conflict:</strong> A Page or Post with the slug <code>' . esc_html($target_slug) . '</code> exists. Remove/rename it to use the top-level archive URL.</p></div>';
    }
});
