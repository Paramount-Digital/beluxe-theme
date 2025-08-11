<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

	if ( have_rows( 'social_links', 'option' ) ) : ?>

	<section class="social-bar dark-gold">

		<div class="container">
				<span>Stay Connected - Follow Us</span>
				<div class="social-links">
					<?php while ( have_rows( 'social_links', 'option' ) ) : the_row();
						$link = get_sub_field( 'social_link' );
						if ( $link && is_array( $link ) ) {
							$url    = isset( $link['url'] ) ? $link['url'] : '';
							$title  = isset( $link['title'] ) ? $link['title'] : '';
							$target = isset( $link['target'] ) ? $link['target'] : '';
							if ( $url && $title ) {
								$target_attr = $target ? ' target="' . esc_attr( $target ) . '"' : '';
								$rel_attr    = ( '_blank' === $target ) ? ' rel="noopener noreferrer"' : '';
								echo '<a href="' . esc_url( $url ) . '"' . $target_attr . $rel_attr . '>' . esc_html( $title ) . '</a>';
							}
						}
					endwhile; ?>
				</div>

		</div>

	</section>

<?php endif; ?>