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
                <a href="<?php the_permalink(); ?>">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <div class="area-guide-thumb">
                        <?php the_post_thumbnail('medium_large'); ?>
                        </div>
                    <?php endif; ?>
                        <h3><?php the_title(); ?></h3>
                </a>
                <p><?php echo wp_trim_words(get_the_excerpt(), 20); ?><p>
                <div class="property-link-container">
                    <a href="<?php the_permalink(); ?>" class="property-link">Find Out More</a>
                </div>
            </article>
            <?php endwhile; ?>
        </div>
        <?php endif; wp_reset_postdata(); ?>

    </div>
</section>