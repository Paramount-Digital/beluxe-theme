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


function call_us_mob_button() {
	echo '<a href="tel:" class="phone-mob-link"><svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
<rect x="0.75" y="0.75" width="34.5" height="34.5" rx="17.25" stroke="white" stroke-width="1.5"/>
<path d="M8.80621 13.4166C8.80408 12.1887 9.24935 11.0016 10.0582 10.0777C10.8655 9.15566 11.9802 8.55755 13.1949 8.39605C13.3877 8.37255 13.583 8.41283 13.7515 8.50933C13.9202 8.60594 14.0531 8.75481 14.1304 8.93316L14.1343 8.94097L16.2476 13.6597V13.6675L16.3101 13.8101C16.3692 13.9465 16.3938 14.0956 16.3814 14.2437C16.369 14.3914 16.3185 14.5334 16.2379 14.6578C16.2352 14.6614 16.2313 14.6667 16.2252 14.6744L14.0865 17.2115L13.7701 17.5865L13.9869 18.0259C14.8343 19.7479 16.5997 21.4965 18.3384 22.3433L18.7789 22.5572L19.1529 22.2398L21.6597 20.105L21.6607 20.106C21.6632 20.1039 21.6658 20.102 21.6685 20.1002L21.6714 20.0982C21.796 20.0151 21.9393 19.9645 22.0884 19.9507C22.2363 19.9371 22.3853 19.9594 22.522 20.0171L22.5269 20.0201L27.3882 22.1978V22.1988L27.398 22.2027C27.5766 22.2797 27.7259 22.412 27.8228 22.5806C27.9192 22.7484 27.9587 22.9432 27.9361 23.1353C27.775 24.3511 27.1773 25.4671 26.2545 26.275C25.3305 27.0838 24.1435 27.5281 22.9156 27.5259H22.9146C15.1363 27.5259 8.8064 21.1968 8.80621 13.4185V13.4166Z" stroke="#BFA570" stroke-width="1.5"/>
</svg></a>';
}
add_action('generate_inside_mobile_header', 'call_us_mob_button', 30);
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
		'content-with-contact-form-section' => 'Content with contact form section template part not found',
		'reviews-slider' => 'Reviews slider template part not found',
		'social-bar'     => 'Social bar template part not found',
	];

	$form_page = is_page(196);

	foreach ( $elements as $slug => $not_found ) {
		// Skip contact form section on contact page
		if ( $form_page && 'content-with-contact-form-section' === $slug ) {
			continue;
		}

		if ( locate_template( "elements/{$slug}.php", false, false ) ) {
			get_template_part( "elements/{$slug}" );
		} else {
			echo "<!-- {$not_found} -->";
		}
	}
}
add_action( 'generate_after_main_content', 'footer_additional_sections', 25 );
