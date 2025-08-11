<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>
<section class="reviews-slider">

	<div class="container">

		<header class="section-header">
			<div class="section-header-content col-lg-6 col-12">
				<?php
				$header_content = get_field('review_header_content', 'option');
				if (empty($header_content)) {
					$header_content = esc_html__('What Our Clients Say', 'beluxe-theme');
				}
				echo wp_kses_post($header_content);
				?>
			</div>
			<?php
			$all_reviews = get_field('all_reviews_link', 'option');
			if (empty($all_reviews)) {
				$all_reviews = 'https://www.google.com/search?q=BeLuxe+Properties&sca_esv=07fba3af1fe20c74&biw=1704&bih=1022&sxsrf=AE3TifPJPTuEWFdZiixMg18pOBvThWDfNA%3A1754478265359&ei=uTaTaL7QFbOphbIPpZS08Ag&ved=0ahUKEwj-kJfuhPaOAxWzVEEAHSUKDY4Q4dUDCBA&uact=5&oq=BeLuxe+Properties&gs_lp=Egxnd3Mtd2l6LXNlcnAiEUJlTHV4ZSBQcm9wZXJ0aWVzMgQQIxgnMgoQIxiABBgnGIoFMgsQABiABBiGAxiKBTIIEAAYgAQYogQyBRAAGO8FMggQABiiBBiJBTIFEAAY7wVI8gJQAFirAXAAeAGQAQCYAYoBoAHnAaoBAzEuMbgBA8gBAPgBAZgCAqAC-gHCAgUQABiABJgDAJIHAzEuMaAH2A2yBwMxLjG4B_oBwgcFMi0xLjHIBw8&sclient=gws-wiz-serp#lrd=0x27b79118ead90b3:0xbc2d41105aa078f6,1,,,,';
			}
			?>

			<a href="<?php echo esc_url($all_reviews); ?>" class="cta-button">
				<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 28 28" fill="none">
					<path d="M26.32 14.2916C26.32 13.3816 26.2383 12.5066 26.0867 11.6666H14V16.6366H20.9067C20.6033 18.235 19.6933 19.5883 18.3283 20.4983V23.73H22.4933C24.92 21.49 26.32 18.2 26.32 14.2916Z" fill="#4285F4"/>
					<path d="M13.9996 26.8335C17.4646 26.8335 20.3696 25.6902 22.493 23.7302L18.328 20.4985C17.1846 21.2685 15.7263 21.7352 13.9996 21.7352C10.663 21.7352 7.82797 19.4835 6.81297 16.4502H2.54297V19.7635C4.65464 23.9519 8.98297 26.8335 13.9996 26.8335Z" fill="#34A853"/>
					<path d="M6.81464 16.4385C6.55797 15.6685 6.4063 14.8519 6.4063 14.0002C6.4063 13.1485 6.55797 12.3319 6.81464 11.5619V8.24854H2.54464C1.66964 9.9752 1.16797 11.9235 1.16797 14.0002C1.16797 16.0769 1.66964 18.0252 2.54464 19.7519L5.86964 17.1619L6.81464 16.4385Z" fill="#FBBC05"/>
					<path d="M13.9996 6.2765C15.8896 6.2765 17.5696 6.92984 18.9113 8.18984L22.5863 4.51484C20.358 2.43817 17.4646 1.1665 13.9996 1.1665C8.98297 1.1665 4.65464 4.04817 2.54297 8.24817L6.81297 11.5615C7.82797 8.52817 10.663 6.2765 13.9996 6.2765Z" fill="#EA4335"/>
				</svg>
				<?php esc_html_e('View our reviews on Google', 'beluxe-theme'); ?>
			</a>

		</header>

	</div>

</section>