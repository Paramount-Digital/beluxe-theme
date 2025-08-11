<?php if ( ! defined( 'ABSPATH' ) ) exit;

// Gather all sub-fields once
$fields = [
  'anchor'            => get_sub_field('element_anchor'),
  'background'        => get_sub_field('element_background_colour'),
  'disable_read_more' => get_sub_field('disable_read_more'),
  'content'           => get_sub_field('element_content'),
  'media'             => get_sub_field('element_media') ?: [],
];


// Build section classes
$classes = array_filter([
  'content-media-section',
  'background-' . sanitize_html_class( $fields['background'] ),
  $prev ? 'previous-' . sanitize_html_class( $prev ) : '',
  $fields['media']['media_arrangement'] ?? '',
  $fields['media']['scale_image_block']  ?? '',
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

  // Media markup imavges & video oembeds
  get_template_part( 'elements/partials/content-media', null, $fields['media'] );

  echo '</div>

  </section>';
