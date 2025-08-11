<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

//remove titles by default
add_action( 'wp', 'remove_all_titles' );
function remove_all_titles() {
		
	if(get_post_type() == 'page') {
		add_filter( 'generate_show_title', '__return_false' );
	}
	
}

//remove #more-x anchor that’s added to your read more URLs
add_filter( 'generate_more_jump', '__return_false' );

//add aria labels to widgets
add_filter('dynamic_sidebar_params', 'add_aside_to_widget'); 
function add_aside_to_widget($params) {

	$add_aria = 'aria-label="' . $params[0]['widget_id'] . ' widget" ';	//adding aria label of id for multiple footer widgets
	$add_aria = $add_aria . 'class="';
	$params[0]['before_widget'] = str_replace('class="', $add_aria, $params[0]['before_widget']);

    return $params;
}

//remove author username from preview links - external sites
add_filter( 'oembed_response_data', 'remove_author_preview' );
function remove_author_preview( $data ) {
    unset($data['author_url']);
    unset($data['author_name']);
    return $data;
}

//remove sidebar for specific post types
add_filter( 'generate_sidebar_layout', 'change_sidebar_layout' );
function change_sidebar_layout( $layout ) {
	
    $post_types = array();
    if ( in_array( get_post_type(), $post_types ) || is_home() ) {
        return 'no-sidebar';
	}

    return $layout;
}

//close menu svg
add_filter( 'generate_svg_icon', function( $output, $icon ) {
	
    if ( 'pro-close' === $icon ) {
        $output = '<svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" width="40" height="41" viewBox="0 0 40 41" fill="none"><path d="M30 10.9124L10 30.9124" stroke="#093DF4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M10 10.9124L30 30.9124" stroke="#093DF4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
    }
	
	if('arrow' === $icon) {
		$output = '<svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="7.44141" cy="7.44141" r="7.44141" fill="#D3D3D3"/><path d="M4.06836 5.69824L7.17584 8.77544L10.3068 5.74401" stroke="#323232" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>';
	}
	
	if('menu-bars' === $icon) {
		$output = '<svg width="30" height="23" viewBox="0 0 30 23" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="30" height="3" rx="1.5" fill="#1D262D"/><rect y="10" width="30" height="3" rx="1.5" fill="#1D262D"/><rect y="20" width="30" height="3" rx="1.5" fill="#1D262D"/></svg>';
	}
	
    return $output;
}, 10, 2 );

// Register the above_navigation menu location
add_action( 'after_setup_theme', function() {
	register_nav_menu( 'above_navigation', __( 'Above Navigation Menu', 'beluxe-theme' ) );
} );

// Add navigation on top of main navigation
function above_navigation_links() {
	wp_nav_menu( array(
		'theme_location' => 'above_navigation',
		'container'      => false,
		'menu_class'     => 'above-navigation-menu',
		'fallback_cb'    => false,
	) );
}
add_action('generate_after_logo', 'above_navigation_links', 12);

// add a navigation button after the main menu
function add_call_to_action_button() {
    
    //get the button
    $button = get_field('call_to_action_button', 'option');

    if(!empty($button)) {
        
        echo '<a class="cta-button content-button" href="' . esc_url($button['url']) . '" target="' . esc_attr($button['target']) . '">
                  <span class="button-title">' . esc_html($button['title']) . '</span>
             </a>';
        
    }
}
add_action('generate_menu_bar_items', 'add_call_to_action_button', 40);

//global content buttons
function get_content_buttons($args = null) {
	
	//buttons classes
	if(!empty($args['class'])) $class = $args['class'];
		
	$buttons = 'buttons';
	$buttons_option = null;

	//button prefix for use in certain clone items
	if(!empty($args['prefix'])) $buttons = $args['prefix'] . $buttons;

	if(have_rows($buttons, $buttons_option) || !empty($resource_file)) :
				
		$content_buttons_output = '<div class="content-buttons ' . ((isset($class)) ? esc_attr($class) : null) . '">';

		while(have_rows($buttons, $buttons_option)) : the_row();

			$link = get_sub_field('links');
			$button_colour = get_sub_field('button_colour');

			if(!empty($link)) : 

			$content_buttons_output .= '<a class="content-button ' . esc_attr($button_colour) . '" href="' . esc_url($link['url']) . '" target="' . esc_attr($link['target']) . '">'
				. '<span class="button-title">' . $link['title'] . '</span>' . (($button_colour === 'link-style') ? ' <svg width="6" height="11" viewBox="0 0 6 11" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M0.75 9.875L5.125 5.5L0.75 1.125" stroke="#377CFD" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>' : null)
				. (($link['target'] == '_blank') ? '<span class="screen-reader-text"> (opens in new tab)</span>' : null)
				. '</a>';
			endif;

		endwhile;

		$content_buttons_output .= '</div>';

	endif;
	
	echo $content_buttons_output ?? null;
	
}

// Adding excerpt for page - used for content cards
add_post_type_support( 'page', 'excerpt' );


//adding a title wrapping to blog items on archives
add_action('generate_before_content', 'category_title_wrap_start', 15);
add_action('generate_after_content', 'category_title_wrap_ending', 25);


//blog archive content wrapping 
function category_title_wrap_start() {
	echo ((get_post_type() == 'post' && !is_singular('post')) ? '<div class="archive-title-details">' : null);
}
function category_title_wrap_ending() {
	echo ((get_post_type() == 'post' && !is_singular('post')) ? '</div>' : null);
}

/**
 * FOOTER SECTIONS
 * Adds additional sections (reviews slider and social bar) to the footer of the website.
 */
function footer_additional_sections() {
	$elements = [
		'reviews-slider' => 'Reviews slider template part not found',
		'social-bar'     => 'Social bar template part not found',
	];

	foreach ( $elements as $slug => $not_found ) {
		if ( locate_template( "elements/{$slug}.php", false, false ) ) {
			get_template_part( "elements/{$slug}" );
		} else {
			echo "<!-- {$not_found} -->";
		}
	}
}
add_action( 'generate_after_main_content', 'footer_additional_sections', 25 );
