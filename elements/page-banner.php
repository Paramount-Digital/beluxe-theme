<?php if ( ! defined( 'ABSPATH' ) ) exit;

$page_id = ( is_home() || is_category() || is_search() )
         ? get_option( 'page_for_posts' )
         : get_the_ID();

// Gather fields
$fields = [
  'content' => get_field( 'banner_content', $page_id ),
  'buttons' => get_field( 'buttons_hero',   $page_id ),
  'media'   => get_field( 'banner_media',   $page_id ) ?: [],
  'sizing'  => get_field( 'banner_sizing',  $page_id ),
];

// Context overrides
if ( is_post_type_archive() ) {
  $fields['content'] = '<h1>' . post_type_archive_title( '', false ) . '</h1>';
} elseif ( is_singular( 'post' ) ) {
  $fields['content'] = '<h1>' . get_the_title() . '</h1>';
  $fields['media']['background_image'] = get_post_thumbnail_id();
  $fields['sizing'] = 'small-banner';
} elseif ( is_category() ) {
  $fields['content'] = sprintf(
    '<h1>%s</h1>%s%s',
    single_cat_title( '', false ),
    category_description(),
    do_shortcode( '[searchform post_types="post"]' )
  );
} elseif ( is_search() ) {
  $q = get_search_query();
  $fields['content'] = sprintf(
    '<h1>Search Results for: <span>%s</span></h1>%s',
    esc_html( $q ),
    do_shortcode( '[searchform post_types="post"]' )
  );
  if ( ! have_posts() ) {
    $fields['content'] = preg_replace( '/<h1(.*?)>/', '<h2$1>', $fields['content'] );
    $fields['content'] = str_replace( '</h1>', '</h2>', $fields['content'] );
  }
} elseif ( is_404() ) {
  $fields['content'] = '<h1>Oops! That page can’t be found.</h1>'
                     . '<p>It looks like nothing was found here. Maybe try searching?</p>';
}

// Fallback to title
if ( empty( $fields['content'] ) ) {
  $fields['content'] = '<h1>' . get_the_title() . '</h1>';
}

// Build classes
$classes = array_filter([
  'page-banner',
  'single-page-banner',
  $fields['sizing'] ? 'page-' . sanitize_html_class( $fields['sizing'] ) : '',
  'swiper',
]);
?>
<header role="banner" aria-label="Page Hero" class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
  <div class="swiper-wrapper">
    <div class="banner-slide single-slide swiper-slide">
      <div class="container">
        <div class="banner-content col-12 col-lg-8 song-2">
          <?php
          echo wp_kses_post( $fields['content'] );
          base_get_content_buttons( [ 'prefix' => 'banner_' ] );
          if ( function_exists( 'yoast_breadcrumb' ) ) {
            echo '<nav class="banner-breadcrumbs screen-reader-text">'
               . do_shortcode( '[wpseo_breadcrumb]' )
               . '</nav>';
          }
          ?>
        </div>
      </div>

      <?php
      //Background media
      $bg_id     = $fields['media']['background_image']        ?? 0;
      $mobile_id = $fields['media']['mobile_background_image'] ?? 0;

      if ( $bg_id ) :
        list( $url, $w, $h ) = wp_get_attachment_image_src( $bg_id, 'full' );
      ?>
        <div class="banner-background" data-bg-id="<?php echo intval( $bg_id ); ?>" data-mobile-id="<?php echo intval( $mobile_id ); ?>">
          <?php echo '<svg class="draw-svg" xmlns="http://www.w3.org/2000/svg" width="438" height="588" viewBox="0 0 438 588" fill="none">
                          <path class="draw-path" d="M381.148 1.00504C397.614 -0.252263 414.152 -0.3297 430.629 0.773599C433.11 0.931454 435.553 1.12782 438 1.34782V14.1789C427.127 12.8128 415.512 12.5118 403.449 13.3058C401.631 13.4265 399.854 13.5778 398.148 13.7287C344.682 22.1676 336.516 50.0493 232.871 296.659C170.386 445.904 142.98 520.336 142.979 553.026C142.979 578.171 165.25 578.172 193.487 578.172H256.846C344.125 578.172 399.412 569.121 438 550.431V578.759C436.554 581.832 435.276 584.913 434.187 588H0.646484L4.84082 580.686H23.6699C89.596 580.686 97.3107 561.575 206.903 296.629C269.388 146.62 296.814 76.1811 296.814 43.4914C296.924 38.9247 296.406 34.3641 295.273 29.9328C228.151 53.9701 170.888 98.8223 132.196 157.663C93.505 216.504 75.5049 286.112 80.9365 355.893C85.6244 392.152 97.4872 427.168 115.861 458.981L110.839 471.101C88.9109 436.341 75.0453 397.29 70.2227 356.707C63.4582 271.961 90.2588 187.915 145.067 121.994C199.876 56.0736 278.49 13.3331 364.61 2.63395H364.692C370.157 2.01034 375.612 1.43755 381.148 1.00504Z" fill="white"/>
                          <path class="draw-path" d="M438 449.471C408.477 464.055 370.426 474.073 322.71 477.166C288.846 478.747 254.914 478.269 221.109 475.737L224.51 467.178C224.67 467.188 273.85 470.425 294.75 469.381C363.193 465.973 409.144 442.341 438 410.995V449.471Z" fill="white"/>
                          <path class="draw-path" d="M438 216.075C434.396 217.798 430.739 219.469 427.033 221.081C430.826 222.088 434.481 223.149 438 224.263V233.798C431.136 230.29 423.711 227.817 415.971 226.513C388.858 236.632 360.36 242.754 331.411 244.678C324.571 245.144 317.706 245.144 310.866 244.678L313.753 235.625C319.344 235.772 324.94 235.645 330.518 235.243C372.393 232.524 408.854 219.17 438 200.138V216.075Z" fill="white"/>
                      </svg>'; ?>
          <div class="banner-overlay" aria-hidden="true"></div>
          <picture>
            <?php if ( $mobile_id ) : ?>
              <source media="(max-width:767px)"
                      srcset="<?php echo esc_url( wp_get_attachment_image_url( $mobile_id, 'full' ) ); ?>">
            <?php endif; ?>
            <img
              aria-hidden="true"
              class="banner-background-media"
              src="<?php echo esc_url( $url ); ?>"
              loading="eager"
              fetchpriority="high"
              sizes="100vw"
              style="aspect-ratio: <?php echo esc_attr( $w ) . '/' . esc_attr( $h ); ?>"
              alt=""
            >
          </picture>
        </div>
      <?php endif; ?>

    </div><!-- .swiper-slide -->
  </div><!-- .swiper-wrapper -->
</header>
