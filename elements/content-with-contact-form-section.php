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
  'background-' . sanitize_html_class( $fields['background'] ?: 'black' ),
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
    echo do_shortcode('[contact-info]');
    echo '</div>';
  } else {
    echo '<div class="col-12 col-lg-6 section-intro">';
    echo '<h2>Our team are here to answer all of your questions</h2>
          <p>If you have any questions about any of the properties listed on our website and would like to find out more, our team will be happy to help! Fill out the form and one of our team will be in touch ASAP, or contact us directly by phone or email.</p>
          <h4>Contact details</h4>';
          echo do_shortcode('[contact-info]');
          echo '</div>';
  }

    // Contact form shortcode
  $form_shortcode = $fields['form'] ? $fields['form'] : '[contact-form-7 id="1ac0f2a" title="Contact form 1"]';
  echo '<div class="col-12 col-lg-6 contact-form">' . do_shortcode( $form_shortcode ) . '</div>';

  echo '</div>

  </section>';