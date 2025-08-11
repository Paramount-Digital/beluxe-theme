<?php if ( ! defined( 'ABSPATH' ) ) exit;

// Gather all sub-fields once
$fields = [
  'anchor'            => get_sub_field('element_anchor'),
  'background'        => get_sub_field('element_background_colour'),
  'disable_read_more' => get_sub_field('disable_read_more'),
  'content'           => get_sub_field('element_content'),
  'form'             => get_sub_field('contact_form_shortcode') ?: [],
];


// Build section classes 
$classes = array_filter([
  'content-form-section',
  'background-' . sanitize_html_class( $fields['background'] ),
]);

// Start section
printf(
  '<section%s class="%s">',
  $fields['anchor']
    ? ' id="' . esc_attr( sanitize_title( $fields['anchor'] ) ) . '"'
    : '',
  esc_attr( implode( ' ', $classes ) )
);

echo '<div class="container">';

  if ( $fields['content'] ) {
    echo '<div class="col-12 col-lg-6 section-intro">';
    $html = $fields['disable_read_more']
          ? wp_kses_post( $fields['content'] )
          : '<div class="read-more" data-read-smore-inline>'
            . wp_kses_post( $fields['content'] )
            . '</div>';
    echo $html;
    base_get_content_buttons();
    echo '</div>';
  }

    // Contact form shortcode
   echo '<div class="col-12 col-lg-6 contact-form">' . do_shortcode( $fields['form'] ) . '</div>';

  echo '</div>

  </section>';