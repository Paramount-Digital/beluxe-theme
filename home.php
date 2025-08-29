<?php
/**
 * The template for displaying the blog index (posts page).
 * Template Hierarchy: home.php
 */

get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        <div class="container">

            <?php if ( have_posts() ) : ?>

                <div class="blog-grid col-12">
                    <?php
                    while ( have_posts() ) :
                        the_post();
                        ?>
                        
                        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                            <?php if ( has_post_thumbnail() ) : ?>
                                <a href="<?php the_permalink(); ?>" class="post-thumbnail">
                                    <?php the_post_thumbnail( 'medium_large' ); ?>
                                </a>
                            <?php endif; ?>

                            <div class="entry-content">
                                <h3 class="entry-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h2>
                                <div class="entry-meta">
                                    <span class="date"><?php echo get_the_date(); ?></span>
                                    <span class="author"><?php the_author(); ?></span>
                                </div>
                                <div class="excerpt">
                                    <p><?php echo wp_trim_words(get_the_excerpt(), 20); ?><p>
                                </div>
								<div class="property-link-container">
									<a href="<?php the_permalink(); ?>" class="property-link">Find Out More</a>
									<svg xmlns="http://www.w3.org/2000/svg" width="21" height="12" viewBox="0 0 21 12" fill="none">
										<path d="M20.5303 6.53033C20.8232 6.23744 20.8232 5.76256 20.5303 5.46967L15.7574 0.696699C15.4645 0.403806 14.9896 0.403806 14.6967 0.696699C14.4038 0.989593 14.4038 1.46447 14.6967 1.75736L18.9393 6L14.6967 10.2426C14.4038 10.5355 14.4038 11.0104 14.6967 11.3033C14.9896 11.5962 15.4645 11.5962 15.7574 11.3033L20.5303 6.53033ZM0 6V6.75H20V6V5.25H0V6Z" fill="#0E192F"/>
									</svg>
								</div>
                            </div>
                        </article>

                    <?php endwhile; ?>
                </div><!-- .blog-grid -->

                <div class="pagination">
                    <?php the_posts_pagination(); ?>
                </div>

            <?php else : ?>

                <p>No posts found.</p>

            <?php endif; ?>

        </div><!-- .container -->
    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();
