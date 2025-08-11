<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Settings & content
$element_anchor             = get_sub_field('element_anchor');
$element_background_colour  = get_sub_field('element_background_colour');
$element_content            = get_sub_field('element_content');
$logo_selection             = get_sub_field('logo_media');

// Build section classes
$section_classes = ['logo-media-section', 'background-' . sanitize_html_class( $element_background_colour )];

?>
<section<?php if ( $element_anchor ): ?> id="<?php echo esc_attr( sanitize_title( $element_anchor ) ); ?>"<?php endif; ?> class="<?php echo esc_attr( implode( ' ', $section_classes ) ); ?>">

    <div class="container">

        <?php if ( $element_content ): ?>
            <div class="section-introduction col-12">
                <div class="read-more-content" data-read-smore-inline="true">
                    <?php echo wp_kses_post( $element_content ); ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if ( ! empty( $logo_selection ) ): ?>
            <div class="logo-slider swiper col-12" data-component="logo-slider">
                <div class="swiper-wrapper">
                    <?php foreach ( $logo_selection as $image_id ): ?>
                        <div class="logo-item swiper-slide">
                            <?php
                            echo wp_get_attachment_image(
                                $image_id,
                                'full',
                                false,
                                [
                                    'class'       => 'logo-image',
                                    'loading'     => 'lazy',
                                    'alt'         => get_post_meta( $image_id, '_wp_attachment_image_alt', true ),
                                ]
                            );
                            ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

    </div>
</section>