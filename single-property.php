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
?>

<section class="single-property-listing">

    <div class="container">

        <div class="property-details col-12">
            <?php if ($yearBuilt) : ?>
            <div class="property-detail">
                <img src="/wp-content/uploads/2025/08/calendar_OFF-1.png"><p class="detail">Year Built</p><hr class="detail-sep"/><p class="value"><?php echo esc_html($yearBuilt); ?></p>
            </div>
            <?php endif; ?>
            <?php if ($bedrooms) : ?>
            <div class="property-detail">
                <img src="/wp-content/uploads/2025/08/bed_OFF-8.png"><p class="detail">Bedrooms</p><hr class="detail-sep"/><p class="value"><?php echo esc_html($bedrooms); ?></p>
            </div>
            <?php endif; ?>
            <?php if ($bathrooms) : ?>
            <div class="property-detail">
                <img src="/wp-content/uploads/2025/08/BATHROOMS_OFF-8.png"><p class="detail">Bathrooms</p><hr class="detail-sep"/><p class="value"><?php echo esc_html($bathrooms); ?></p>
            </div>
            <?php endif; ?>
            <?php if ($buildSize) : ?>
            <div class="property-detail">
                <img src="/wp-content/uploads/2025/08/HOUSE_OFF-8.png"><p class="detail">Built Size</p><hr class="detail-sep"/><p class="value"><?php echo esc_html($buildSize); ?>M²</p>
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

    </div>

    <div class="container">

        <div class="col-12 col-lg-6 property-left">

            <h2 class="property-content-title">Luxury Villas in <?php echo esc_html($first_location); ?></h2>
            <p class="property-content-p"><?php echo the_content(); ?></p>

        </div>

        <div class="col-12 col-lg-6 property-right">

            <h2 class="property-content-title pointers-title">Luxury Villas in <?php echo esc_html($first_location); ?></h2>

            <?php if ( have_rows('key_features') ) : ?>
                <ul class="key-features">
                    <?php while ( have_rows('key_features') ) : the_row(); ?>
                        <li><?php the_sub_field('feature'); ?></li>
                    <?php endwhile; ?>
                </ul>
            <?php endif; ?>

        </div>

    </div>

    <div class="container">

        <?php $gallery = get_field('property_gallery'); ?>
        <?php if ($gallery && is_array($gallery)) : ?>
        <div class="property-gallery-swiper swiper">
            <div class="swiper-wrapper">
                <?php foreach ($gallery as $image) : ?>
                    <div class="swiper-slide">
                        <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" style="width:100%;height:auto;" />
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <!-- Thumbnails -->
        <div class="property-gallery-thumbs swiper">
            <div class="swiper-wrapper">
                <?php foreach ($gallery as $image) : ?>
                    <div class="swiper-slide">
                        <img src="<?php echo esc_url($image['sizes']['thumbnail']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
                    </div>
                <?php endforeach; ?>
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
        slidesPerView: 6,
        freeMode: true,
        watchSlidesProgress: true,
    });

    const propertyGallerySwiper = new Swiper('.property-gallery-swiper', {
        // Optional parameters
        direction: 'horizontal',
        loop: true,
        autoplay: {
            delay: 5000,
        },
        thumbs: {
            swiper: thumbsSwiper,
        },

    });
</script>