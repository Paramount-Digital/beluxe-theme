<?php
/**
 * The template for displaying all single properties
 *
 * @package WordPress
 * @subpackage Beluxe_Theme
 * @since Beluxe 1.0
 */

get_header(); ?>

<?php
    // Get custom fields
    $yearBuilt = get_field('year_built');
    $bedrooms = get_field('bedrooms');
    $bathrooms = get_field('bathrooms');
    $buildSize = get_field('build_size');
    $terraceSize = get_field('terrace_size');
    $plotSize = get_field('plot_size');
    $location_terms = get_the_terms(get_the_ID(), 'locations');
    $first_location = $location_terms[0]->name;

    // Range fields for new developments
    $price_min     = get_field('price_min');
    $price_max     = get_field('price_max');
    $bedrooms_min  = get_field('bedrooms_min');
    $bedrooms_max  = get_field('bedrooms_max');
    $bathrooms_min = get_field('bathrooms_min');
    $bathrooms_max = get_field('bathrooms_max');
    $build_size_min = get_field('build_size_min');
    $build_size_max = get_field('build_size_max');
    $is_development = ! empty( $price_min );
?>

<section class="single-property-listing">

    <div class="container">

        <?php
        $beds_display  = $is_development ? beluxe_format_stat_range( $bedrooms_min, $bedrooms_max ) : esc_html( $bedrooms );
        $baths_display = $is_development ? beluxe_format_stat_range( $bathrooms_min, $bathrooms_max ) : esc_html( $bathrooms );
        $size_display  = $is_development ? beluxe_format_stat_range( $build_size_min, $build_size_max, 'M²' ) : ( $buildSize ? esc_html( $buildSize ) . 'M²' : '' );
        ?>

        <?php if ( $yearBuilt || $beds_display || $baths_display || $size_display || $terraceSize || $plotSize ) : ?>

        <div class="property-details col-12">
            <?php if ($yearBuilt) : ?>
            <div class="property-detail">
                <img src="/wp-content/uploads/2025/08/calendar_OFF-1.png"><p class="detail">Year Built</p><hr class="detail-sep"/><p class="value"><?php echo esc_html($yearBuilt); ?></p>
            </div>
            <?php endif; ?>
            <?php if ($beds_display) : ?>
            <div class="property-detail">
                <img src="/wp-content/uploads/2025/08/bed_OFF-8.png"><p class="detail">Bedrooms</p><hr class="detail-sep"/><p class="value"><?php echo wp_kses_post( $beds_display ); ?></p>
            </div>
            <?php endif; ?>
            <?php if ($baths_display) : ?>
            <div class="property-detail">
                <img src="/wp-content/uploads/2025/08/BATHROOMS_OFF-8.png"><p class="detail">Bathrooms</p><hr class="detail-sep"/><p class="value"><?php echo wp_kses_post( $baths_display ); ?></p>
            </div>
            <?php endif; ?>
            <?php if ($size_display) : ?>
            <div class="property-detail">
                <img src="/wp-content/uploads/2025/08/HOUSE_OFF-8.png"><p class="detail">Built Size</p><hr class="detail-sep"/><p class="value"><?php echo wp_kses_post( $size_display ); ?></p>
            </div>
            <?php endif; ?>
            <?php if ($terraceSize) : ?>
            <div class="property-detail">
                <img src="/wp-content/uploads/2025/08/TERRACE_OFF-1.png"><p class="detail">Terrace Size</p><hr class="detail-sep"/><p class="value"><?php echo esc_html($terraceSize); ?>M²</p>
            </div>
            <?php endif; ?>
            <?php if ($plotSize) : ?>
            <div class="property-detail">
                <img src="/wp-content/uploads/2025/08/PLOT_OFF-8.png"><p class="detail">Plot Size</p><hr class="detail-sep"/><p class="value"><?php echo esc_html($plotSize); ?>M²</p>
            </div>
            <?php endif; ?>
        </div>

        <?php endif; ?>

    </div>

    <div class="container">

        <div class="col-12 col-lg-6 property-left">

<!--             <h2 class="property-content-title">Luxury Villas in </?ph/p echo esc_html($first_location); ?/></h2> -->
            <p class="property-content-p"><?php echo the_content(); ?></p>

        </div>

        <div class="col-12 col-lg-6 property-right">

            <h2 class="property-content-title pointers-title">Features and Details</h2>

            <?php if ( have_rows('key_features') ) : ?>
                <?php 
                    $feature_count = 0; 
                    $list_id = 'key-features-' . get_the_ID(); 
                ?>
                <ul class="key-features" id="<?php echo esc_attr( $list_id ); ?>" data-expanded="false">
                    <?php while ( have_rows('key_features') ) : the_row(); ?>
                        <?php 
                            $feature_count++; 
                            $hidden_class = ( $feature_count > 14 ) ? ' is-hidden' : ''; 
                        ?>
                        <li class="<?php echo esc_attr( $hidden_class ); ?>"><?php the_sub_field('feature'); ?></li>
                    <?php endwhile; ?>
                </ul>
                <?php if ( $feature_count > 10 ) : ?>
                    <button class="key-features-toggle button" type="button" aria-expanded="false" aria-controls="<?php echo esc_attr( $list_id ); ?>" data-more="Show more" data-less="Show less">Show more</button>
                <?php endif; ?>
            <?php endif; ?>

        </div>

    </div>

    <div class="container">

        <?php 
        // Check for external gallery first (comma-separated URLs), fallback to property_gallery
        $external_gallery_raw = get_field('external_property_gallery');
        $gallery = get_field('property_gallery');
        
        // Parse external gallery URLs if it's a string
        $external_gallery_urls = [];
        if (!empty($external_gallery_raw) && is_string($external_gallery_raw)) {
            $urls = preg_split('/[,\r\n]+/', $external_gallery_raw, -1, PREG_SPLIT_NO_EMPTY);
            $external_gallery_urls = array_map('trim', array_filter($urls));
        }
        
        $use_external = !empty($external_gallery_urls);
        ?>
        
        <?php if ($use_external || ($gallery && is_array($gallery))) : ?>
            <div class="property-gallery-swiper swiper">
                <div class="swiper-wrapper">
                    <?php if ($use_external) : ?>
                        <?php foreach ($external_gallery_urls as $url) : ?>
                            <div class="swiper-slide">
                                <img src="<?php echo esc_url($url); ?>" alt="Property Image"/>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <?php foreach ($gallery as $image) : ?>
                            <div class="swiper-slide">
                                <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>"/>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="property-gallery-thumbs swiper">
                <div class="swiper-wrapper">
                    <?php if ($use_external) : ?>
                        <?php foreach ($external_gallery_urls as $url) : ?>
                            <div class="swiper-slide">
                                <img src="<?php echo esc_url($url); ?>" alt="Property Thumbnail" />
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <?php foreach ($gallery as $image) : ?>
                            <div class="swiper-slide">
                                <img src="<?php echo esc_url($image['sizes']['thumbnail']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        

    </div>

</section>

<?php footer_additional_sections(); 
get_footer();?>
    
<script>
  const thumbsSwiper = new Swiper('.property-gallery-thumbs', {
    spaceBetween: 10,
  });

  const propertyGallerySwiper = new Swiper('.property-gallery-swiper', {
    direction: 'horizontal',
    loop: false,              // loop only the main swiper
    autoplay: {
      delay: 5000,
    },
    thumbs: {
      swiper: thumbsSwiper,
    },
  });
</script>