<?php
// Query area guides custom post type
$area_guides = new WP_Query([
    'post_type'      => 'area-guides',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
]);

?>

<section class="area-guides">
    <div class="container">

        <?php
        if ( $area_guides->have_posts() ) : ?>
        <div class="area-guides-list col-12">
            <?php while ( $area_guides->have_posts() ) : $area_guides->the_post(); ?>
            <article class="area-guide-item">
                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="area-guide-thumb">
                    <?php the_post_thumbnail('medium_large'); ?>
                    </div>
                <?php endif; ?>
                <h3><?php the_title(); ?></h3>
                <?php if ( is_singular('area-guides') ) : ?>
                    <div class="area-guide-date">
                        <?php echo get_the_date(); ?>
                    </div>
                <?php endif; ?>
                <p><?php echo wp_trim_words(get_the_excerpt(), 20); ?><p>
                <div class="property-link-container">
                    <a href="<?php the_permalink(); ?>" class="property-link">Find Out More</a>
                    <svg xmlns="http://www.w3.org/2000/svg" width="21" height="12" viewBox="0 0 21 12" fill="none">
                        <path d="M20.5303 6.53033C20.8232 6.23744 20.8232 5.76256 20.5303 5.46967L15.7574 0.696699C15.4645 0.403806 14.9896 0.403806 14.6967 0.696699C14.4038 0.989593 14.4038 1.46447 14.6967 1.75736L18.9393 6L14.6967 10.2426C14.4038 10.5355 14.4038 11.0104 14.6967 11.3033C14.9896 11.5962 15.4645 11.5962 15.7574 11.3033L20.5303 6.53033ZM0 6V6.75H20V6V5.25H0V6Z" fill="#0E192F"/>
                    </svg>
                </div>
            </article>
            <?php endwhile; ?>
        </div>
        <?php endif; wp_reset_postdata(); ?>

    </div>
</section>