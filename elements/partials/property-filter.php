<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>

<?php
// Preserve selections
$selected_location = $_GET['locations'] ?? '';
$selected_type     = $_GET['property_type'] ?? '';
$selected_bedrooms = $_GET['bedrooms'] ?? '';
$selected_min      = $_GET['price_min'] ?? '';
$selected_max      = $_GET['price_max'] ?? '';
$selected_ref      = $_GET['reference_number'] ?? '';

// Helper: Get unique values from an ACF field, sorted
function get_unique_acf_values_sorted($field_name, $numeric = false) {
    global $wpdb;
    $results = $wpdb->get_col($wpdb->prepare("
        SELECT DISTINCT meta_value 
        FROM $wpdb->postmeta 
        WHERE meta_key = %s 
        AND meta_value != ''
    ", $field_name));
    $results = array_unique(array_filter($results));
    $numeric ? sort($results, SORT_NUMERIC) : sort($results, SORT_STRING);
    return $results;
}
?>

<div class="col-12 property-filter">

    <div id="filter-modal" class="filter-modal">
    <div class="filter-modal-content">
        <form class="property-filter-form-mobile" method="GET" action="<?php echo esc_url(get_post_type_archive_link('property')); ?>">

        <!-- Location (taxonomy) -->
        <select name="locations">
            <option value="">All Locations</option>
            <?php
            $locations = get_terms([
                'taxonomy'   => 'locations',
                'hide_empty' => true,
                'childless'  => true, // only terms without children
            ]);
            foreach ($locations as $loc) {
                echo '<option value="' . esc_attr($loc->slug) . '" ' . selected($selected_location, $loc->slug, false) . '>' . esc_html($loc->name) . '</option>';
            }
            ?>
        </select>

        <!-- Property Type (ACF text) -->
        <select name="property_type">
            <option value="">All Types of Property</option>
            <?php foreach (get_unique_acf_values_sorted('property_type') as $type): ?>
                <option value="<?php echo esc_attr($type); ?>" <?php selected($selected_type, $type); ?>>
                    <?php echo esc_html($type); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <div class="price-selects">
            <!-- Min Price -->
            <select name="price_min" class="min-price">
                <option value="">Min Price</option>
                <?php foreach (get_unique_acf_values_sorted('for_sale_price', true) as $price): ?>
                    <option value="<?php echo esc_attr($price); ?>" <?php selected($selected_min, $price); ?>>
                        <?php echo number_format($price); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <!-- Max Price -->
            <select name="price_max" class="max-price">
                <option value="">Max Price</option>
                <?php foreach (get_unique_acf_values_sorted('for_sale_price', true) as $price): ?>
                    <option value="<?php echo esc_attr($price); ?>" <?php selected($selected_max, $price); ?>>
                        <?php echo number_format($price); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Bedrooms -->
        <select name="bedrooms">
            <option value="">All Bedrooms</option>
            <?php foreach (get_unique_acf_values_sorted('bedrooms', true) as $beds): ?>
                <option value="<?php echo esc_attr($beds); ?>" <?php selected($selected_bedrooms, $beds); ?>>
                    <?php echo esc_html($beds); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- Reference Number -->
        <input type="text" name="reference_number" placeholder="Reference Number" value="<?php echo esc_attr($selected_ref); ?>">

        <button type="submit">Search Properties</button>
        <button type="button" id="reset-filter">Reset</button>
                <script>
                document.getElementById('reset-filter').onclick = function() {
                    const form = this.closest('form');
                    form.reset();
                };
                </script>
    </form>
    </div>
    </div>

    <form class="property-filter-form" method="GET" action="<?php echo esc_url(get_post_type_archive_link('property')); ?>">

        <!-- Location (taxonomy) -->
        <select name="locations">
            <option value="">All Locations</option>
            <?php
            $locations = get_terms(['taxonomy' => 'locations', 'hide_empty' => false]);
            foreach ($locations as $loc) {
                echo '<option value="' . esc_attr($loc->slug) . '" ' . selected($selected_location, $loc->slug, false) . '>' . esc_html($loc->name) . '</option>';
            }
            ?>
        </select>

        <!-- Property Type (ACF text) -->
        <select name="property_type">
            <option value="">All Types of Property</option>
            <?php foreach (get_unique_acf_values_sorted('property_type') as $type): ?>
                <option value="<?php echo esc_attr($type); ?>" <?php selected($selected_type, $type); ?>>
                    <?php echo esc_html($type); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- Min Price -->
        <select name="price_min">
            <option value="">Min Price</option>
            <?php foreach (get_unique_acf_values_sorted('for_sale_price', true) as $price): ?>
                <option value="<?php echo esc_attr($price); ?>" <?php selected($selected_min, $price); ?>>
                    <?php echo number_format($price); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="button" id="reset-filter1">Reset Filters</button>
        <script>
        document.getElementById('reset-filter1').onclick = function() {
            const form = this.closest('form');
            form.reset();
        };
        </script>

        <!-- Bedrooms -->
        <select name="bedrooms">
            <option value="">All Bedrooms</option>
            <?php foreach (get_unique_acf_values_sorted('bedrooms', true) as $beds): ?>
                <option value="<?php echo esc_attr($beds); ?>" <?php selected($selected_bedrooms, $beds); ?>>
                    <?php echo esc_html($beds); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- Reference Number -->
        <input type="text" name="reference_number" placeholder="Reference Number" value="<?php echo esc_attr($selected_ref); ?>">

        <!-- Max Price -->
        <select name="price_max">
            <option value="">Max Price</option>
            <?php foreach (get_unique_acf_values_sorted('for_sale_price', true) as $price): ?>
                <option value="<?php echo esc_attr($price); ?>" <?php selected($selected_max, $price); ?>>
                    <?php echo number_format($price); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Search Properties</button>
    </form>
</div>

