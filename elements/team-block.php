<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$content = get_sub_field('element_content');

?>

<section class="team-block">
    <div class="container">
        <div class="col-12 col-lg-5 content">
            <?php echo $content; ?>
        </div>
        <div class="col-12 col-lg-7">
            <?php if( have_rows('team_members_repeater', 'option') ): ?>
                <div class="team-members-grid">
                    <?php while( have_rows('team_members_repeater', 'option') ): the_row(); 
                        $image = get_sub_field('image');
                        $name = get_sub_field('name');
                        $title = get_sub_field('job_title');
                        $phone = get_sub_field('telephone');
                        $email = get_sub_field('email');
                    ?>
                        <div class="team-member">
                            <?php if( $image ): ?>
                                <div class="team-member__image">
                                    <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" class="img-fluid rounded">
                                </div>
                            <?php endif; ?>
                            <h4 class="team-member__name"><?php echo esc_html($name); ?></h4>
                            <?php if( $title ): ?>
                                <p class="team-member__title"><?php echo esc_html($title); ?></p>
                            <?php endif; ?>
                            <?php if( $phone ): ?>
                                <p class="team-member__phone">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none"><path d="M4.99998 9.99854C4.99736 8.5332 5.52801 7.11675 6.49315 6.01416C7.45622 4.91407 8.78629 4.20047 10.2353 4.00732L10.2363 4.0083C10.4504 3.98217 10.6673 4.02517 10.8545 4.13232C11.0414 4.2394 11.1886 4.40448 11.2744 4.60205V4.60303L11.2803 4.61377L13.832 10.311V10.3218L13.915 10.5112C13.9807 10.6628 14.008 10.8291 13.9941 10.9937C13.9802 11.1581 13.925 11.3163 13.8349 11.4546L11.2353 14.5366L10.8144 15.0366L11.1025 15.6226C12.1382 17.7271 14.2916 19.8594 16.416 20.894L17.0039 21.1802L17.5019 20.7573L20.539 18.1714L20.54 18.1724L20.541 18.1714L20.5449 18.1685C20.6832 18.0762 20.8423 18.0197 21.0078 18.0044C21.1709 17.9893 21.3353 18.0144 21.4863 18.0776L21.4931 18.0815L27.3828 20.7202L27.3887 20.7231L27.3955 20.7261C27.5937 20.8115 27.7586 20.9589 27.8662 21.146C27.9727 21.3314 28.0165 21.546 27.9922 21.7583C27.8003 23.2097 27.0879 24.5419 25.9863 25.5063C24.8837 26.4716 23.4673 27.0031 22.0019 27.0005H22C12.6274 27.0005 5.00012 19.3731 4.99998 10.0005V9.99854Z" stroke="#BFA570" stroke-width="2"/></svg>
                                    <a href="tel:<?php echo preg_replace('/\s+/', '', $phone); ?>"><?php echo esc_html($phone); ?></a>
                                </p>
                            <?php endif; ?>
                            <?php if( $email ): ?>
                                <p class="team-member__email">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none"><path d="M28.555 11.1677L16.555 3.16766C16.3907 3.05802 16.1975 2.99951 16 2.99951C15.8025 2.99951 15.6093 3.05802 15.445 3.16766L3.445 11.1677C3.30801 11.2591 3.19572 11.3829 3.11809 11.5281C3.04046 11.6733 2.99989 11.8355 3 12.0002V25.0002C3 25.5306 3.21071 26.0393 3.58579 26.4144C3.96086 26.7894 4.46957 27.0002 5 27.0002H27C27.5304 27.0002 28.0391 26.7894 28.4142 26.4144C28.7893 26.0393 29 25.5306 29 25.0002V12.0002C29.0001 11.8355 28.9595 11.6733 28.8819 11.5281C28.8043 11.3829 28.692 11.2591 28.555 11.1677ZM12.09 19.0002L5 24.0002V13.9414L12.09 19.0002ZM14.1362 20.0002H17.8638L24.9425 25.0002H7.0575L14.1362 20.0002ZM19.91 19.0002L27 13.9414V24.0002L19.91 19.0002Z" fill="#BFA570"/></svg>
                                    <a href="mailto:<?php echo antispambot($email); ?>"><?php echo esc_html($email); ?></a>
                                </p>
                            <?php endif; ?>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>