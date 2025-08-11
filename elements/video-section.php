<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


$video_oembed = get_sub_field('video_oembed');

//settings
$element_anchor = get_sub_field('element_anchor');
$element_background_colour = get_sub_field('element_background_colour');

?>
<section <?php echo ((!empty($element_anchor)) ? 'id="' . sanitize_title($element_anchor) . '"' : null); ?> class="video-section <?php esc_attr_e('background-' . $element_background_colour); ?> <?php echo ((!empty($previous_colour)) ? esc_attr('previous-' . $previous_colour) : null); ?>">

	<div class="container">
		
		<?php
		if(!empty($video_oembed)) :

			echo '<div class="video-wrap col-12 col-xl-10">' . $video_oembed . '</div>';
				
		endif;
		
		?>
		
	</div>

</section>