<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Get all 'locations' terms
$terms = get_terms([
    'taxonomy'   => 'locations',
    'hide_empty' => true,
]);

if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) :
    ?>
<section class="locations-slider">
    <div class="marquee-container horizontal-marquee">
            <?php foreach ( $terms as $term ) : ?>
                <span class="location-name"><?php echo esc_html( $term->name ); ?></span>
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

    .horizontal-marquee {
        display: inline-flex;
        align-items: center;
        white-space: nowrap;        
        gap: 46px;                  
        animation: scroll-once 10s linear 1 forwards;
    }
    
    .locations-slider .location-name {
        color: var(--light-grey, #DAD4C6);
        font-family: "Cormorant Garamond", serif;
        font-size: 32px;
        font-style: italic;
        font-weight: 600;
        line-height: 36px; /* 112.5% */
        text-transform: uppercase;
        width: auto;
        text-align: center;
    }
</style>