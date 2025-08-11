<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Get all 'locations' terms
$terms = get_terms([
    'taxonomy'   => 'locations',
    'hide_empty' => false,
]);

if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) : ?>
<section class="locations-slider">
    <div class="container">
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <?php foreach ( $terms as $term ) : ?>
                    <div class="swiper-slide location-name">
                        <?php echo esc_html( $term->name ); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>