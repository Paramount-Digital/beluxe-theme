<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $wp_query;
$current_sort = isset($_GET['sortby']) ? sanitize_text_field($_GET['sortby']) : 'recent';
?>

<section class="property-listings">
	<div class="container">

		<?php if ( have_posts() ) : ?>

			<div class="col-12 property-listing-header">
				<span class="property-count"><?php echo esc_html( $wp_query->found_posts ); ?> Properties</span>

				<div class="property-sort-wrap">
					<label class="property-sort-label" for="property-sort">Sort By</label>
					<form class="property-sort-form" method="GET" action="<?php echo esc_url( strtok( $_SERVER['REQUEST_URI'], '?' ) ); ?>">
						<?php
						foreach ( $_GET as $key => $value ) {
							if ( $key === 'sortby' ) {
								continue;
							}
							echo '<input type="hidden" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '">';
						}
						?>
						<select name="sortby" id="property-sort">
							<option value="recent" <?php selected( $current_sort, 'recent' ); ?>>Recently Added</option>
							<option value="price_asc" <?php selected( $current_sort, 'price_asc' ); ?>>Price: Low to High</option>
							<option value="price_desc" <?php selected( $current_sort, 'price_desc' ); ?>>Price: High to Low</option>
						</select>
						<noscript><input type="submit" value="Sort" /></noscript>
					</form>
				</div>
			</div>

			<div class="col-12 property-grid">
				<?php while ( have_posts() ) : the_post(); ?>
					<div class="property-card">
						<?php 
						$featured_image = get_property_featured_image(get_the_ID(), 'full');
						if ( $featured_image ) : 
						?>
							<div class="property-listing-image">
								<?php echo $featured_image; ?>
							</div>
						<?php endif; ?>

						<div class="property-content">
							<?php
							// Get first location term for THIS post (robust against WP_Error/empty)
							$first_location = '';
							$location_terms = get_the_terms(get_the_ID(), 'locations');
							if ( ! is_wp_error($location_terms) && ! empty($location_terms) && is_array($location_terms) ) {
								$first = reset($location_terms);
								if ($first && isset($first->name)) {
									$first_location = $first->name;
								}
							}
							?>
							<p class="property-location"><?php echo esc_html($first_location); ?></p>

							<a href="<?php the_permalink(); ?>"><h3 class="property-title"><?php the_title(); ?></h3></a>

							<?php
							$bedrooms   = get_post_meta(get_the_ID(), 'bedrooms', true);
							$bathrooms  = get_post_meta(get_the_ID(), 'bathrooms', true);
							$build_size = get_post_meta(get_the_ID(), 'build_size', true);
							$plot_size  = get_post_meta(get_the_ID(), 'plot_size', true);

							if ($bedrooms || $bathrooms || $build_size || $plot_size) :
							?>
								<div class="property-listing-details">

									<?php if ($bedrooms) : ?>
										<div class="property-listing-detail">
											<img src="/wp-content/uploads/2025/08/bed_OFF-8.png" class="listing-icon" alt="Bedrooms Icon">
											<p><?php echo $bedrooms; ?></p>
										</div>
									<?php endif; ?>

									<?php if ($bathrooms) : ?>
										<div class="property-listing-detail">
											<img src="/wp-content/uploads/2025/08/BATHROOMS_OFF-8.png" class="listing-icon" alt="Bathrooms Icon">
											<p><?php echo $bathrooms; ?></p>
										</div>
									<?php endif; ?>

									<?php if ($build_size) : ?>
										<div class="property-listing-detail">
											<img src="/wp-content/uploads/2025/08/HOUSE_OFF-8.png" class="listing-icon" alt="Build Size Icon">
											<p><?php echo $build_size; ?>m²</p>
										</div>
									<?php endif; ?>

									<?php if ($plot_size) : ?>
										<div class="property-listing-detail">
											<img src="/wp-content/uploads/2025/08/PLOT_OFF-8.png" class="listing-icon" alt="Plot Size Icon">
											<p><?php echo $plot_size; ?>m²</p>
										</div>
									<?php endif; ?>

								</div>
							<?php endif; ?>


							<p><?php echo get_the_excerpt(); ?></p>

							<div class="property-price-sku">
								<?php $price_raw = get_field('for_sale_price', get_the_ID()); $price = is_numeric($price_raw) ? (int) $price_raw : 0; ?>
								<p class="listing-card-price">€<?php echo number_format($price); ?></p>
								<p class="listing-card-ref"><?php echo esc_html((string) get_field('reference_number', get_the_ID())); ?></p>
							</div>

							<div class="property-link-container">
								<a href="<?php the_permalink(); ?>" class="property-link">View Property</a>
							</div>
						</div>
					</div>
				<?php endwhile; ?>
			</div>

			<?php
			// Pagination
			the_posts_pagination( array(
				'mid_size'  => 2,
				'prev_text' => __('<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
<path d="M12.9422 15.8079C13.0003 15.866 13.0463 15.9349 13.0777 16.0108C13.1092 16.0867 13.1254 16.168 13.1254 16.2501C13.1254 16.3322 13.1092 16.4135 13.0777 16.4894C13.0463 16.5653 13.0003 16.6342 12.9422 16.6923C12.8841 16.7504 12.8152 16.7964 12.7393 16.8278C12.6634 16.8593 12.5821 16.8755 12.5 16.8755C12.4179 16.8755 12.3366 16.8593 12.2607 16.8278C12.1848 16.7964 12.1159 16.7504 12.0578 16.6923L5.80782 10.4423C5.74971 10.3842 5.70361 10.3153 5.67215 10.2394C5.6407 10.1636 5.62451 10.0822 5.62451 10.0001C5.62451 9.91797 5.6407 9.83664 5.67215 9.76077C5.70361 9.68489 5.74971 9.61596 5.80782 9.55792L12.0578 3.30792C12.1751 3.19064 12.3342 3.12476 12.5 3.12476C12.6659 3.12476 12.8249 3.19064 12.9422 3.30792C13.0595 3.42519 13.1254 3.58425 13.1254 3.7501C13.1254 3.91596 13.0595 4.07502 12.9422 4.19229L7.1336 10.0001L12.9422 15.8079Z" fill="#2C2C2C"/>
</svg>', 'textdomain'),
				'next_text' => __('<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
<path d="M14.1923 10.4423L7.94229 16.6923C7.88422 16.7504 7.81528 16.7964 7.73941 16.8278C7.66354 16.8593 7.58223 16.8755 7.5001 16.8755C7.41798 16.8755 7.33666 16.8593 7.26079 16.8278C7.18492 16.7964 7.11598 16.7504 7.05792 16.6923C6.99985 16.6342 6.95378 16.5653 6.92236 16.4894C6.89093 16.4135 6.87476 16.3322 6.87476 16.2501C6.87476 16.168 6.89093 16.0867 6.92236 16.0108C6.95378 15.9349 6.99985 15.866 7.05792 15.8079L12.8665 10.0001L7.05792 4.19229C6.94064 4.07502 6.87476 3.91596 6.87476 3.7501C6.87476 3.58425 6.94064 3.42519 7.05792 3.30792C7.17519 3.19064 7.33425 3.12476 7.5001 3.12476C7.66596 3.12476 7.82502 3.19064 7.94229 3.30792L14.1923 9.55792C14.2504 9.61596 14.2965 9.68489 14.328 9.76077C14.3594 9.83664 14.3756 9.91797 14.3756 10.0001C14.3756 10.0822 14.3594 10.1636 14.328 10.2394C14.2965 10.3153 14.2504 10.3842 14.1923 10.4423Z" fill="#2C2C2C"/>
</svg>', 'textdomain'),
			) );
			?>

		<?php else : ?>
            <div class="col-12">
			    <p>No properties found. View all of our properties <a href="<?php echo esc_url( home_url( '/properties/' ) ); ?>">here</a>.</p>
            </div>
		<?php endif; ?>

	</div>
</section>