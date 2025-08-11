<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Build section classes
$section_classes = [
    'accordion-section',
    'background-' . sanitize_html_class( $element_background_colour ),
];

?>
<section<?php if ( $element_anchor ): ?> id="<?php echo esc_attr( sanitize_title( $element_anchor ) ); ?>"<?php endif; ?> class="<?php echo esc_attr( implode( ' ', $section_classes ) ); ?>">

    <div class="container">

        <?php if ( $element_content ): ?>
            <div class="section-introduction col-12">
                <div class="read-more-content" data-read-smore-inline="true">
                    <?php echo wp_kses_post( $element_content ); ?>
                </div>
                <?php base_get_content_buttons( [ 'class' => 'col-12' ] ); ?>
            </div>
        <?php endif; ?>

        <?php if ( ! empty( $dropdowns ) ): ?>
            <div class="accordion-dropdowns col-12 col-xl-10">
                <?php
                $index = 0;
                $faqs_list = [];
                foreach ( $dropdowns as $dropdown ):
                    $raw_title   = $dropdown['accordion_title']   ?? '';
                    $raw_content = $dropdown['accordion_content'] ?? '';
                    $title       = sanitize_text_field( $raw_title );
                    $content     = wp_kses_post( $raw_content );
                    if ( ! $title || ! $content ) {
                        continue;
                    }
                    $index++;
                    $item_id     = 'accordion-item-' . $index;
                    $summary_id  = $item_id . '-summary';
                    $content_id  = $item_id . '-content';
                    $faqs_list[] = [
                        '@type'          => 'Question',
                        'name'           => $title,
                        'acceptedAnswer' => [
                            '@type' => 'Answer',
                            'text'  => wp_strip_all_tags( $content ),
                        ],
                    ];
                ?>
                <details class="accordion" id="<?php echo esc_attr( $item_id ); ?>">
                    <summary
                        id="<?php echo esc_attr( $summary_id ); ?>"
                        aria-controls="<?php echo esc_attr( $content_id ); ?>">
                        <?php echo esc_html( $title ); ?>
                    </summary>
                    <div
                        id="<?php echo esc_attr( $content_id ); ?>"
                        class="accordion-details"
                        role="region"
                        aria-labelledby="<?php echo esc_attr( $summary_id ); ?>">
                        <?php echo $content; ?>
                    </div>
                </details>
                <?php endforeach; ?>
            </div>
            <?php if ( ! empty( $faqs_list ) ): ?>
                <script type="application/ld+json">
                <?php echo wp_json_encode(
                    [
                        '@context'   => 'https://schema.org',
                        '@type'      => 'FAQPage',
                        'mainEntity' => $faqs_list,
                    ],
                    JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
                ); ?>
                </script>
            <?php endif; ?>
        <?php endif; ?>

    </div>
</section>