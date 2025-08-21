
<?php

if (!defined( 'ABSPATH' ) ) {
	exit;
}

$properties = new WP_Query([
  'post_type'      => 'property',
  'posts_per_page' => 8,
  'post_status'    => 'publish',
  'orderby'        => 'menu_order',
  'order'          => 'ASC',
]);

$content = get_sub_field('element_content');
?>

<section class="property-listings">
  <div class="container">
    <div class="header-wrapper">
      <?php echo $content;?>
    </div>
    <?php if ( $properties->have_posts() ) : ?>
      <div class="col-12 property-grid">
        <?php while ( $properties->have_posts() ) : $properties->the_post(); ?>
          <div class="property-card">
            <?php if ( has_post_thumbnail() ) : ?>
              <div class="property-listing-image">
                <?php the_post_thumbnail('medium'); ?>
              </div>
            <?php endif; ?>
            <div class="property-content">
              <?php
              $location_terms = get_the_terms(get_the_ID(), 'locations');
              $first_location = $location_terms ? $location_terms[0]->name : '';
              ?>
              <p class="property-location"><?php echo esc_html($first_location); ?></p>
              <a href="<?php the_permalink(); ?>"><h3 class="property-title"><?php the_title(); ?></h3></a>
              <div class="property-listing-details">
                <div class="property-listing-detail">
                  <img src="/wp-content/uploads/2025/08/bed_OFF-8.png" class="listing-icon" alt="Bedrooms Icon">
                  <p><?php echo get_post_meta(get_the_ID(), 'bedrooms', true); ?></p>
                </div>
                <div class="property-listing-detail">
                  <img src="/wp-content/uploads/2025/08/BATHROOMS_OFF-8.png" class="listing-icon" alt="Bathrooms Icon">
                  <p><?php echo get_post_meta(get_the_ID(), 'bathrooms', true); ?></p>
                </div>
                <div class="property-listing-detail">
                  <img src="/wp-content/uploads/2025/08/HOUSE_OFF-8.png" class="listing-icon" alt="Build Size Icon">
                  <p><?php echo get_post_meta(get_the_ID(), 'build_size', true); ?>m²</p>
                </div>
                <div class="property-listing-detail">
                  <img src="/wp-content/uploads/2025/08/PLOT_OFF-8.png" class="listing-icon" alt="Plot Size Icon">
                  <p><?php echo get_post_meta(get_the_ID(), 'plot_size', true); ?>m²</p>
                </div>
              </div>
              <p><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>
              <div class="property-price-sku">
                <p class="listing-card-price">€<?php echo number_format(get_field('for_sale_price', get_the_ID())); ?></p>
                <p class="listing-card-ref"><?php echo get_field('reference_number', get_the_ID()); ?></p>
              </div>
              <div class="property-link-container">
                <a href="<?php the_permalink(); ?>" class="property-link">View Property</a>
                <svg xmlns="http://www.w3.org/2000/svg" width="21" height="12" viewBox="0 0 21 12" fill="none">
                  <path d="M20.5303 6.53033C20.8232 6.23744 20.8232 5.76256 20.5303 5.46967L15.7574 0.696699C15.4645 0.403806 14.9896 0.403806 14.6967 0.696699C14.4038 0.989593 14.4038 1.46447 14.6967 1.75736L18.9393 6L14.6967 10.2426C14.4038 10.5355 14.4038 11.0104 14.6967 11.3033C14.9896 11.5962 15.4645 11.5962 15.7574 11.3033L20.5303 6.53033ZM0 6V6.75H20V6V5.25H0V6Z" fill="#0E192F"/>
                </svg>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
            </div>
    <?php else : ?>
      <div class="col-12">
        <p>No properties found. View all of our properties <a href="<?php echo esc_url( home_url( '/properties/' ) ); ?>">here</a>.</p>
      </div>
    <?php endif; ?>
  </div>
</section>
<?php wp_reset_postdata(); ?>

<style>
    .header-wrapper {
        text-align: center;
        display: flex;
        flex-direction: column;
        margin: auto auto 40px auto;
    }

    .header-wrapper p {
        margin: 0;
    }
    </style>