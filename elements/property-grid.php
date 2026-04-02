
<?php

if (!defined( 'ABSPATH' ) ) {
	exit;
}

$content = get_sub_field('element_content');
$current_sort = isset($_GET['sortby']) ? sanitize_text_field($_GET['sortby']) : 'recent';
$selected_features = isset($_GET['features']) && is_array($_GET['features'])
  ? array_map('sanitize_text_field', array_slice($_GET['features'], 0, 20))
  : [];
?>

<section class="property-listings">
  <div class="container">
    <div class="header-wrapper">
      <?php echo $content; ?>
    </div>

    <?php $property_grid = get_sub_field('property_selection'); ?>

    <?php
    if ( $property_grid ) :
      // Sort the ACF relationship array based on sortby param
      if ( $current_sort === 'price_asc' ) {
        usort( $property_grid, function( $a, $b ) {
          return (float) get_field( 'for_sale_price', $a->ID ) - (float) get_field( 'for_sale_price', $b->ID );
        });
      } elseif ( $current_sort === 'price_desc' ) {
        usort( $property_grid, function( $a, $b ) {
          return (float) get_field( 'for_sale_price', $b->ID ) - (float) get_field( 'for_sale_price', $a->ID );
        });
      } else {
        // 'recent' — sort by post date, newest first
        usort( $property_grid, function( $a, $b ) {
          return strtotime( $b->post_date ) - strtotime( $a->post_date );
        });
      }

      // Filter by selected features
      if ( ! empty( $selected_features ) ) {
        $property_grid = array_values( array_filter(
          $property_grid,
          function( $p ) use ( $selected_features ) {
            return property_matches_features_global( $p->ID, $selected_features );
          }
        ));
      }
    ?>

      <span class="property-count"><?php echo count( $property_grid ); ?> Properties</span>

      <div class="property-grid col-12">
        <?php foreach ( $property_grid as $properties ) :
          $pid = $properties->ID;

          // Core property data
          $permalink = get_permalink( $pid );
          $title     = get_the_title( $pid );
          $excerpt   = get_the_excerpt( $pid );
          $thumb     = get_property_featured_image( $pid, 'medium' );

          // Location
          $location_terms = get_the_terms( $pid, 'locations' );
          $first_location = ( $location_terms && ! is_wp_error( $location_terms ) ) ? $location_terms[0]->name : '';

          // Custom fields
          $bedrooms   = get_post_meta( $pid, 'bedrooms', true );
          $bathrooms  = get_post_meta( $pid, 'bathrooms', true );
          $build_size = get_post_meta( $pid, 'build_size', true );
          $plot_size  = get_post_meta( $pid, 'plot_size', true );

          $price      = get_field( 'for_sale_price', $pid );
          $ref_number = get_field( 'reference_number', $pid );
        ?>
          <div class="property-card">
            <?php if ( $thumb ) : ?>
              <div class="property-listing-image">
                <?php echo $thumb; ?>
              </div>
            <?php endif; ?>

            <div class="property-content">
              <?php if ( $first_location ) : ?>
                <p class="property-location"><?php echo esc_html( $first_location ); ?></p>
              <?php endif; ?>

              <a href="<?php echo esc_url( $permalink ); ?>">
                <h3 class="property-title"><?php echo esc_html( $title ); ?></h3>
              </a>

              <?php if ( $bedrooms || $bathrooms || $build_size || $plot_size ) : ?>
                <div class="property-listing-details">
                  <?php if ( $bedrooms ) : ?>
                    <div class="property-listing-detail">
                      <img src="/wp-content/uploads/2025/08/bed_OFF-8.png" class="listing-icon" alt="Bedrooms Icon">
                      <p><?php echo esc_html( $bedrooms ); ?></p>
                    </div>
                  <?php endif; ?>
                  <?php if ( $bathrooms ) : ?>
                    <div class="property-listing-detail">
                      <img src="/wp-content/uploads/2025/08/BATHROOMS_OFF-8.png" class="listing-icon" alt="Bathrooms Icon">
                      <p><?php echo esc_html( $bathrooms ); ?></p>
                    </div>
                  <?php endif; ?>
                  <?php if ( $build_size ) : ?>
                    <div class="property-listing-detail">
                      <img src="/wp-content/uploads/2025/08/HOUSE_OFF-8.png" class="listing-icon" alt="Build Size Icon">
                      <p><?php echo esc_html( $build_size ); ?>m²</p>
                    </div>
                  <?php endif; ?>
                  <?php if ( $plot_size ) : ?>
                    <div class="property-listing-detail">
                      <img src="/wp-content/uploads/2025/08/PLOT_OFF-8.png" class="listing-icon" alt="Plot Size Icon">
                      <p><?php echo esc_html( $plot_size ); ?>m²</p>
                    </div>
                  <?php endif; ?>
                </div>
              <?php endif; ?>

              <?php if ( $excerpt ) : ?>
                <p><?php echo esc_html( $excerpt ); ?></p>
              <?php endif; ?>

              <div class="property-bottom">
                <div class="property-price-sku">
                  <?php if ( $price ) : ?>
                    <p class="listing-card-price">€<?php echo number_format( (float) $price ); ?></p>
                  <?php endif; ?>
                  <?php if ( $ref_number ) : ?>
                    <p class="listing-card-ref"><?php echo esc_html( $ref_number ); ?></p>
                  <?php endif; ?>
                </div>
                <div class="property-link-container">
                  <a href="<?php echo esc_url( $permalink ); ?>" class="property-link">View Property</a>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

    <?php else : ?>
      <?php
      // Fallback: Query all properties for PHP filtering
      $fallback_args = [
        'post_type'      => 'property',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
      ];

      // Apply sort to fallback query
      if ( $current_sort === 'price_asc' ) {
        $fallback_args['orderby']  = 'meta_value_num';
        $fallback_args['meta_key'] = 'for_sale_price';
        $fallback_args['order']    = 'ASC';
      } elseif ( $current_sort === 'price_desc' ) {
        $fallback_args['orderby']  = 'meta_value_num';
        $fallback_args['meta_key'] = 'for_sale_price';
        $fallback_args['order']    = 'DESC';
      }

      $fallback_query = new WP_Query( $fallback_args );
      $fallback_posts = $fallback_query->posts;

      // Filter by selected features
      if ( ! empty( $selected_features ) ) {
        $fallback_posts = array_values( array_filter(
          $fallback_posts,
          function( $p ) use ( $selected_features ) {
            return property_matches_features_global( $p->ID, $selected_features );
          }
        ));
      }
      ?>

      <span class="property-count"><?php echo count( $fallback_posts ); ?> Properties</span>

      <?php if ( ! empty( $fallback_posts ) ) : ?>
        <div class="property-grid col-12">
          <?php foreach ( $fallback_posts as $property_post ) :
            $pid = $property_post->ID;
            $permalink = get_permalink( $pid );
            $title     = get_the_title( $pid );
            $excerpt   = get_the_excerpt( $property_post );
            $thumb     = get_property_featured_image( $pid, 'medium' );

            $location_terms = get_the_terms( $pid, 'locations' );
            $first_location = ( $location_terms && ! is_wp_error( $location_terms ) ) ? $location_terms[0]->name : '';

            $bedrooms   = get_post_meta( $pid, 'bedrooms', true );
            $bathrooms  = get_post_meta( $pid, 'bathrooms', true );
            $build_size = get_post_meta( $pid, 'build_size', true );
            $plot_size  = get_post_meta( $pid, 'plot_size', true );

            $price      = get_field( 'for_sale_price', $pid );
            $ref_number = get_field( 'reference_number', $pid );
          ?>
            <div class="property-card">
              <?php if ( $thumb ) : ?>
                <div class="property-listing-image">
                  <?php echo $thumb; ?>
                </div>
              <?php endif; ?>

              <div class="property-content">
                <?php if ( $first_location ) : ?>
                  <p class="property-location"><?php echo esc_html( $first_location ); ?></p>
                <?php endif; ?>

                <a href="<?php echo esc_url( $permalink ); ?>">
                  <h3 class="property-title"><?php echo esc_html( $title ); ?></h3>
                </a>

                <?php if ( $bedrooms || $bathrooms || $build_size || $plot_size ) : ?>
                  <div class="property-listing-details">
                    <?php if ( $bedrooms ) : ?>
                      <div class="property-listing-detail">
                        <img src="/wp-content/uploads/2025/08/bed_OFF-8.png" class="listing-icon" alt="Bedrooms Icon">
                        <p><?php echo esc_html( $bedrooms ); ?></p>
                      </div>
                    <?php endif; ?>
                    <?php if ( $bathrooms ) : ?>
                      <div class="property-listing-detail">
                        <img src="/wp-content/uploads/2025/08/BATHROOMS_OFF-8.png" class="listing-icon" alt="Bathrooms Icon">
                        <p><?php echo esc_html( $bathrooms ); ?></p>
                      </div>
                    <?php endif; ?>
                    <?php if ( $build_size ) : ?>
                      <div class="property-listing-detail">
                        <img src="/wp-content/uploads/2025/08/HOUSE_OFF-8.png" class="listing-icon" alt="Build Size Icon">
                        <p><?php echo esc_html( $build_size ); ?>m²</p>
                      </div>
                    <?php endif; ?>
                    <?php if ( $plot_size ) : ?>
                      <div class="property-listing-detail">
                        <img src="/wp-content/uploads/2025/08/PLOT_OFF-8.png" class="listing-icon" alt="Plot Size Icon">
                        <p><?php echo esc_html( $plot_size ); ?>m²</p>
                      </div>
                    <?php endif; ?>
                  </div>
                <?php endif; ?>

                <?php if ( $excerpt ) : ?>
                  <p><?php echo esc_html( $excerpt ); ?></p>
                <?php endif; ?>

                <div class="property-bottom">
                  <div class="property-price-sku">
                    <?php if ( $price ) : ?>
                      <p class="listing-card-price">€<?php echo number_format( (float) $price ); ?></p>
                    <?php endif; ?>
                    <?php if ( $ref_number ) : ?>
                      <p class="listing-card-ref"><?php echo esc_html( $ref_number ); ?></p>
                    <?php endif; ?>
                  </div>
                  <div class="property-link-container">
                    <a href="<?php echo esc_url( $permalink ); ?>" class="property-link">View Property</a>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
        <?php wp_reset_postdata(); ?>
      <?php else : ?>
        <div class="col-12">
          <p>No properties match your filters. Please adjust and try again.</p>
        </div>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</section>
