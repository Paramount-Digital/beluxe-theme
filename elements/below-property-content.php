<?php
// Get the current taxonomy term object
$term = get_queried_object();

// Make sure we have a term
if ( $term && isset($term->term_id) ) {
    // Fetch the WYSIWYG field for this term
    $content = get_field( 'below_properties_content', $term );

    // Only output if content exists
    if ( $content ) : ?>
        <section class="below-property-content">
            <div class="container">
                <div class="col-12">
                    <?php echo $content; ?>
                </div>
            </div>
        </section>
    <?php endif;
}
?>
