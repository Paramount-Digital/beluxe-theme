<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Get all 'locations' terms
$terms = get_terms([
    'taxonomy'   => 'locations',
    'hide_empty' => false,
]);

if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) :
    ?>
<section class="locations-slider">
    <div class="marquee-container horizontal-marquee">
            <?php foreach ( $terms as $term ) : ?>
                <span class="location-name horizontal-marquee-inner"><?php echo esc_html( $term->name ); ?></span>
            <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<style>
    .location-name {     
        flex: 0 0 auto;
        margin: 0;
        padding: 0 36px;
        position: relative;
    }
</style>