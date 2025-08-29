<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// Single content block for use in a flexible content layout
?>
<section class="single-content-block">
    <div class="container">
        <div class="content-block-inner">
            <?php 
            $content = get_sub_field('element_content');
            if ($content) {
                echo wp_kses_post($content);
            }
            ?>
        </div>
    </div>
</section>
