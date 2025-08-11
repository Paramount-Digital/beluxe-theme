<?php
if ( ! defined( 'ABSPATH' ) ) exit;

$slides = [];
if ( have_rows( 'content_image_slides' ) ) {
	while ( have_rows( 'content_image_slides' ) ) { the_row();
		$image   = get_sub_field( 'image' );
		$content = get_sub_field( 'content' );
		$slides[] = [ 'image' => $image, 'content' => $content ];
	}
}

if ( ! empty( $slides ) ) : ?>
<section class="content-image-slider">
	
</section>
<?php endif; ?>