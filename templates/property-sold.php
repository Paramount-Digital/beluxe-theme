<?php
/**
 * Template: Property No Longer Available (410 Gone)
 *
 * Served by beluxe_handle_sold_property() for property URLs that return 410.
 *
 * @package Beluxe_Theme
 */

if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'wp_head', function () {
    echo '<meta http-equiv="refresh" content="5;url=' . esc_url( home_url( '/' ) ) . '">' . "\n";
} );

get_header(); ?>

<section class="property-sold-page">
    <div class="property-sold-page__inner">

        <div class="property-sold-page__icon" aria-hidden="true">
            <svg width="56" height="56" viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="28" cy="28" r="27" stroke="#BFA570" stroke-width="2"/>
                <path d="M18 28h20M28 18v20" stroke="#BFA570" stroke-width="2" stroke-linecap="round" transform="rotate(45 28 28)"/>
            </svg>
        </div>

        <h1 class="property-sold-page__heading">This Property Is No Longer Available</h1>

        <p class="property-sold-page__body">
            This property has been sold or removed from our listings. You'll be redirected to our
            homepage in a few seconds, or browse our current properties below.
        </p>

        <p class="property-sold-page__countdown">
            Redirecting in <span id="countdown">5</span> seconds&hellip;
        </p>

        <div class="property-sold-page__actions">
            <a href="<?php echo esc_url( get_post_type_archive_link( 'property' ) ); ?>" class="property-sold-page__btn property-sold-page__btn--primary">
                Browse Properties
            </a>
            <a href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>" class="property-sold-page__btn property-sold-page__btn--secondary">
                Contact Us
            </a>
        </div>

    </div>
</section>

<script>
(function () {
    var seconds = 5;
    var el = document.getElementById('countdown');
    var timer = setInterval(function () {
        seconds--;
        if (el) el.textContent = seconds;
        if (seconds <= 0) {
            clearInterval(timer);
            window.location.href = '<?php echo esc_url( home_url( '/' ) ); ?>';
        }
    }, 1000);
})();
</script>

<?php get_footer(); ?>
