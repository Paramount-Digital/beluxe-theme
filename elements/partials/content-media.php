<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
/**
 * Template part for rendering videos/images for content with media section - may expand further tbh
 */
$media_type    = $args['media_type']          ?? '';
$image_media   = (array) ( $args['image_media']  ?? [] );
$oembed_media  = $args['oembed_media']       ?? '';

// IMAGE MEDIA
if ( $media_type === 'image-media' && ! empty( $image_media ) ) :
    $item = is_array( $image_media ) ? reset( $image_media ) : $image_media;
    $id   = is_array( $item ) ? ( $item['ID'] ?? 0 ) : (int) $item;
    if ( $id ) :
        $url     = wp_get_attachment_image_url( $id, 'large' );
        $alt     = get_post_meta( $id, '_wp_attachment_image_alt', true ) ?: wp_get_attachment_caption( $id );
        $caption = wp_get_attachment_caption( $id ); ?>

    <div class="content-media col-12 col-lg-6">
        <figure>
            <img
                src="<?php echo esc_url( $url ); ?>"
                alt="<?php echo esc_attr( $alt ); ?>"
                loading="lazy"
                sizes="(max-width: 768px) 100vw, 50vw"
                class="img-fluid"
            >
            <?php if ( $caption ) : ?>
                <figcaption><?php echo esc_html( $caption ); ?></figcaption>
            <?php endif; ?>
        </figure>
    </div>
<?php
    endif;
endif;

// VIDEO EMBED
if ( $media_type === 'oEmbed-media' && $oembed_media ) :
    $url    = esc_url_raw( $oembed_media );
    $embed  = wp_oembed_get( $url );
    if ( $embed ) :
        // Add lazy-loading, title, and sandbox attributes
        $embed = preg_replace(
            '/<iframe /',
            '<iframe loading="lazy" title="Embedded video" sandbox="allow-scripts allow-same-origin" ',
            $embed
        ); ?>
        <div class="content-videos col-12 col-lg-6" role="region" aria-label="Video embed">
            <?php echo wp_kses_post( $embed ); ?>
        </div>
    <?php endif;
endif;