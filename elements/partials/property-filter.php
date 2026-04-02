<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}


// Preserve selections
$selected_location = $_GET['locations'] ?? '';
$selected_type     = $_GET['property-type'] ?? '';
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

// Max price helper (only published 'property' posts)
function get_max_for_sale_price() {
    global $wpdb;
    $max = $wpdb->get_var( $wpdb->prepare("
        SELECT MAX(CAST(pm.meta_value AS UNSIGNED))
        FROM $wpdb->postmeta pm
        INNER JOIN $wpdb->posts p ON p.ID = pm.post_id
        WHERE pm.meta_key = %s
          AND p.post_type = %s
          AND p.post_status = 'publish'
    ", 'for_sale_price', 'property') );
    $max = (int) $max;
    // Safety floor and ceiling to prevent pathological ranges
    $HARD_CAP = 100000000; // 100,000,000
    if ($max <= 0) {
        $max = 200000; // sensible floor
    } elseif ($max > $HARD_CAP) {
        $max = $HARD_CAP;
    }
    return $max;
}

// Build stepped price scale:
// - 200,000 to < 1,000,000 step 100,000
// - 1,000,000 to < 3,000,000 step 500,000
// - 3,000,000 to < 10,000,000 step 1,000,000
// - 10,000,000+ step 10,000,000
function build_price_scale($max_price) {
    $min_start = 200000;
    $max_price = max($min_start, (int) $max_price);

    $values = [];

    // Segment 1: 200k .. <1m by 100k
    for ($v = $min_start; $v < min($max_price, 1000000); $v += 100000) {
        $values[] = $v;
    }

    // Segment 2: 1m .. <3m by 500k
    if ($max_price >= 1000000) {
        for ($v = 1000000; $v < min($max_price, 3000000); $v += 500000) {
            $values[] = $v;
        }
    }

    // Segment 3: 3m .. <10m by 1m
    if ($max_price >= 3000000) {
        for ($v = 3000000; $v < min($max_price, 10000000); $v += 1000000) {
            $values[] = $v;
        }
    }

    // Segment 4: 10m .. <= max by 10m
    if ($max_price >= 10000000) {
        for ($v = 10000000; $v <= $max_price; $v += 10000000) {
            $values[] = $v;
        }
    }

    // Add one step above the max to ensure Max dropdown can cover all listings
    if (!empty($values)) {
        $last = end($values);
    } else {
        $last = $min_start;
        $values[] = $last;
    }

    $step = ($last < 1000000) ? 100000
        : (($last < 3000000) ? 500000
        : (($last < 10000000) ? 1000000 : 10000000));
    $values[] = $last + $step;

    $values = array_values(array_unique($values));
    sort($values, SORT_NUMERIC);

    return $values;
}

// Build the options once
$max_site_price = get_max_for_sale_price();
$price_options  = build_price_scale($max_site_price);

$active_features = isset($_GET['features']) && is_array($_GET['features'])
  ? array_map('sanitize_text_field', array_slice($_GET['features'], 0, 20))
  : [];
?>

<div class="col-12 property-filter">

    <div id="filter-modal" class="filter-modal">
    <div class="filter-modal-content">
        <form class="property-filter-form-mobile" method="GET" action="<?php echo esc_url(get_post_type_archive_link('property')); ?>">

        <!-- Row 1: Location | Type | Min Price | Max Price -->
        <select name="locations">
            <option value="">All Locations</option>
            <?php
            $locations = get_terms([
                'taxonomy'   => 'locations',
                'hide_empty' => true,
                'childless'  => true,
            ]);
            foreach ($locations as $loc) {
                echo '<option value="' . esc_attr($loc->slug) . '" ' . selected($selected_location, $loc->slug, false) . '>' . esc_html($loc->name) . '</option>';
            }
            ?>
        </select>

        <select name="property-type">
            <option value="">All Types of Property</option>
            <?php
            $property_types = get_terms([
                'taxonomy' => 'property-type',
                'hide_empty' => true
            ]);
            foreach ($property_types as $type) {
                echo '<option value="' . esc_attr($type->slug) . '" ' . selected($selected_type, $type->slug, false) . '>' . esc_html($type->name) . '</option>';
            }
            ?>
        </select>

        <div class="price-selects">
            <select name="price_min" class="min-price">
                <option value="">Min Price</option>
                <?php foreach ($price_options as $price): ?>
                    <option value="<?php echo esc_attr($price); ?>" <?php selected((string)$selected_min, (string)$price); ?>>
                        <?php echo number_format($price); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <select name="price_max" class="max-price">
                <option value="">Max Price</option>
                <?php foreach ($price_options as $price): ?>
                    <option value="<?php echo esc_attr($price); ?>" <?php selected((string)$selected_max, (string)$price); ?>>
                        <?php echo number_format($price); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Row 2: Bedrooms | Features | Reference Number -->
        <select name="bedrooms">
            <option value="">All Bedrooms</option>
            <?php foreach (get_unique_acf_values_sorted('bedrooms', true) as $beds): ?>
                <option value="<?php echo esc_attr($beds); ?>" <?php selected($selected_bedrooms, $beds); ?>>
                    <?php echo esc_html($beds); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <div class="property-filter-col features-filter-col">
          <div class="features-dropdown" id="features-dropdown-mobile">
            <button type="button" class="features-dropdown__toggle">
              <span class="features-dropdown__label">Features<?php
                if ( ! empty( $active_features ) ) {
                  echo ' (' . count( $active_features ) . ')';
                }
              ?></span>
              <svg class="features-dropdown__chevron" width="10" height="6" viewBox="0 0 10 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M1 1L5 5L9 1" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </button>
            <div class="features-dropdown__panel">
              <?php foreach ( get_feature_options() as $value => $label ) :
                $checked = in_array( $value, $active_features, true ) ? 'checked' : '';
              ?>
                <label class="features-dropdown__option">
                  <input type="checkbox" name="features[]" value="<?php echo esc_attr( $value ); ?>" <?php echo $checked; ?>>
                  <span class="features-dropdown__option-text"><?php echo esc_html( $label ); ?></span>
                  <span class="features-dropdown__tick"><?php echo $checked ? '&#10003;' : ''; ?></span>
                </label>
              <?php endforeach; ?>
            </div>
          </div>
        </div>

        <input type="text" name="reference_number" placeholder="Reference Number" value="<?php echo esc_attr($selected_ref); ?>">

        <div class="property-filter-inline-actions">
          <a href="<?php echo esc_url(get_post_type_archive_link('property')); ?>" class="reset-filters-link">Reset Filters</a>
          <button type="submit">Search Properties</button>
        </div>
    </form>
    </div>
    </div>

    <form class="property-filter-form" method="GET" action="<?php echo esc_url(get_post_type_archive_link('property')); ?>">

        <!-- Row 1: Location | Type | Min Price | Max Price -->
        <select name="locations">
            <option value="">All Locations</option>
            <?php
            $locations = get_terms(['taxonomy' => 'locations', 'hide_empty' => false]);
            foreach ($locations as $loc) {
                echo '<option value="' . esc_attr($loc->slug) . '" ' . selected($selected_location, $loc->slug, false) . '>' . esc_html($loc->name) . '</option>';
            }
            ?>
        </select>

        <select name="property-type">
            <option value="">All Types of Property</option>
            <?php
            $property_types = get_terms([
                'taxonomy' => 'property-type',
                'hide_empty' => false
            ]);
            foreach ($property_types as $type) {
                echo '<option value="' . esc_attr($type->slug) . '" ' . selected($selected_type, $type->slug, false) . '>' . esc_html($type->name) . '</option>';
            }
            ?>
        </select>

        <select name="price_min" class="min-price">
            <option value="">Min Price</option>
            <?php foreach ($price_options as $price): ?>
                <option value="<?php echo esc_attr($price); ?>" <?php selected((string)$selected_min, (string)$price); ?>>
                    €<?php echo number_format($price); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <select name="price_max" class="max-price">
            <option value="">Max Price</option>
            <?php foreach ($price_options as $price): ?>
                <option value="<?php echo esc_attr($price); ?>" <?php selected((string)$selected_max, (string)$price); ?>>
                    €<?php echo number_format($price); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- Row 2: Bedrooms | Features | Reference Number -->
        <select name="bedrooms">
            <option value="">All Bedrooms</option>
            <?php foreach (get_unique_acf_values_sorted('bedrooms', true) as $beds): ?>
                <option value="<?php echo esc_attr($beds); ?>" <?php selected($selected_bedrooms, $beds); ?>>
                    <?php echo esc_html($beds); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <div class="property-filter-col features-filter-col">
          <div class="features-dropdown" id="features-dropdown-desktop">
            <button type="button" class="features-dropdown__toggle">
              <span class="features-dropdown__label">Features<?php
                if ( ! empty( $active_features ) ) {
                  echo ' (' . count( $active_features ) . ')';
                }
              ?></span>
              <svg class="features-dropdown__chevron" width="10" height="6" viewBox="0 0 10 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M1 1L5 5L9 1" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </button>
            <div class="features-dropdown__panel">
              <?php foreach ( get_feature_options() as $value => $label ) :
                $checked = in_array( $value, $active_features, true ) ? 'checked' : '';
              ?>
                <label class="features-dropdown__option">
                  <input type="checkbox" name="features[]" value="<?php echo esc_attr( $value ); ?>" <?php echo $checked; ?>>
                  <span class="features-dropdown__option-text"><?php echo esc_html( $label ); ?></span>
                  <span class="features-dropdown__tick"><?php echo $checked ? '&#10003;' : ''; ?></span>
                </label>
              <?php endforeach; ?>
            </div>
          </div>
        </div>

        <input type="text" name="reference_number" placeholder="Reference Number" value="<?php echo esc_attr($selected_ref); ?>">

        <div class="property-filter-inline-actions">
          <a href="<?php echo esc_url(get_post_type_archive_link('property')); ?>" class="reset-filters-link">Reset Filters</a>
          <button type="submit">Search Properties</button>
        </div>
    </form>
</div>