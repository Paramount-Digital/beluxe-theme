<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$element_content = get_sub_field( 'element_content' );
// Build section classes
$section_classes = ['cards-section', 'background-' . sanitize_html_class( $element_background_colour )];

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

        <?php
        // Button rendering
        $args = ['class' => 'col-12'];
        base_get_content_buttons( $args );
        ?>

        <?php if ( have_rows( 'cards' ) ): ?>
            <div class="card-grid col-12 col-lg-11 col-xl-12"
                 data-columns="<?php echo esc_attr( $desktop_grid_style ?: '3' ); ?>">

                <?php while ( have_rows( 'cards' ) ): the_row();
                    $card_image   = get_sub_field( 'card_image' );
                    $card_content = get_sub_field( 'card_content' );

                    // Skip empty cards
                    if ( empty( $card_image ) && empty( $card_content ) ) {
                        continue;
                    }
                ?>
                <div class="card-grid-item">
                    <?php if ( $card_image ): ?>
                        <figure>
                            <?php
                            echo wp_get_attachment_image(
                                $card_image,
                                'full',
                                false,
                                [
                                    'class'   => 'card-image',
                                    'loading' => 'lazy',
                                    'alt'     => get_post_meta( $card_image, '_wp_attachment_image_alt', true ),
                                ]
                            );
                            ?>
                        </figure>
                    <?php endif; ?>
                    <?php echo wp_kses_post( $card_content ); ?>
                </div>
                <?php endwhile; ?>

            </div>
        <?php endif; ?>

    </div>
</section>