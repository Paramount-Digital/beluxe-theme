<?php
if ( ! defined( 'ABSPATH' ) ) exit;

$slides = [];
if ( have_rows( 'property_slider' ) ) {
   while ( have_rows( 'property_slider' ) ) {
       the_row();
       // Get related property (may return object, ID or array)
       $prop = get_sub_field( 'property' );
       if ( is_array( $prop ) ) {
           $prop = reset( $prop );
       }
       if ( ! ( $prop instanceof WP_Post ) ) {
           $prop = get_post( $prop );
       }
       if ( ! $prop ) {
           continue;
       }
       $description = get_sub_field( 'description' );
       $thumb_id    = get_post_thumbnail_id( $prop->ID );
       $price       = get_field( 'for_sale_price', $prop->ID );
       $link        = get_permalink( $prop->ID );
       $slides[]    = [
           'thumb_id'    => $thumb_id,
           'description' => $description,
           'price'       => $price,
           'link'        => $link,
       ];
   }
}

if ( ! empty( $slides ) ) : ?>
<section class="featured-property-slider">
            <div class="property-slider swiper-container">
                <div class="swiper-wrapper">
                    <?php foreach ( $slides as $slide ) : ?>
                        <div class="swiper-slide property-slide">
                            <?php if ( ! empty( $slide['thumb_id'] ) ) : ?>
                                <?php echo wp_get_attachment_image( $slide['thumb_id'], 'full' ); ?>
                            <?php endif; ?>
                            <div class="container">
                                <div class="property-slider-content dark-gold">
                                    <?php echo wp_kses_post( $slide['description'] ); ?>
                                    <div class="property-slider-content-footer background-black">               
                                        <?php if ( isset( $slide['price'] ) && $slide['price'] !== '' ) :
                                            // Normalize to digits (allow dot for safety), then format with thousands separators
                                            $numeric_price = preg_replace( '/[^\d\.]/', '', (string) $slide['price'] );
                                            if ( $numeric_price !== '' ) :
                                                $formatted_price = '€' . number_format( (float) $numeric_price, 0, '.', ',' );
                                        ?>
                                            <span class="price"><?php echo esc_html( $formatted_price ); ?></span>
                                        <?php endif; endif; ?>
                                        <?php if ( $slide['link'] ) : ?>
                                            <a class="view-link" href="<?php echo esc_url( $slide['link'] ); ?>">View Property</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                             </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                    <div class="property-slider-controls">
                        <div class="property-slider-prev">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22" fill="none">
                                <path d="M13.75 17.875L6.875 11L13.75 4.125" stroke="#fff" stroke-width="1.83333" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div class="property-slider-pagination"></div>
                        <div class="property-slider-next">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22" fill="none">
                                <path d="M8.25 4.125L15.125 11L8.25 17.875" stroke="#fff" stroke-width="1.83333" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>
            </div>
</section>
<?php endif; ?>