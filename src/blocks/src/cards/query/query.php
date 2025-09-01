<?php
/**
 * Cards Grid Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// Cards
global $post;
//If is_front_page()
$paged = max( 1, get_query_var( 'paged' ), get_query_var( 'page' ) );

// Define default values
$grid_classes = "";
$cards_count = 0;
$has_description = true;
$has_image = $has_kicker = $has_author = $has_meta = $has_date = false;
$heading_level = "h2";
$thumbnail_size = "dgwltd-medium-crop";

//Post type
$post_type = get_post_type();

//Block type
$block_type = get_field("block_type") ?: "picker";

// Retrieve the card type
$cards_type = get_field("cards_type") ?: "default";

// print_r($cards_type);

//Picker params
$picker = get_field("picker") ?: "";
if ($picker) {
    $cards_count = count($picker) ? count($picker) : "";
}

// Get the mappings
$cardTypeMappings = DGWLTD_PLUGIN_PUBLIC::dgwltd_get_card_type_mappings();

// Initialize flags and classes with default settings
extract($cardTypeMappings['default']);

// print_r($cardTypeMappings[$cards_type]);

// Override with specific card type settings if available
if (isset($cardTypeMappings[$cards_type])) {
    foreach ($cardTypeMappings[$cards_type] as $key => $value) {
        //We need double $ here as this is the php variable
        $$key = $value;
    }
}

// After mapping, append classes for card type and specific grid class
if (!empty($cards_type) && $cards_type !== "default") {
    $grid_classes .= " cards-type--$cards_type";
}

//Custom fields
$featured = get_field( 'featured_card' ) ? : '';
$has_featured = get_field("has_featured") ? : ''; 
if (!empty($has_featured)) {
    $grid_classes .= " has-featured";
}

// Additional logic if needed
switch ($post_type) {
    
    // case 'sector':
    //     // Example of complex logic not covered by the mapping
    //     if ($cards_type == 'something' && someOtherCondition()) {
    //         // Perform some complex operations specific to 'sector' post type
    //         $has_special_feature = true; // Assume this is a variable that controls a unique feature
    //         $grid_classes .= " special-feature-class"; // Append a class for this specific condition
    //     }

    // Default case can also be used for common fallback logic
    default:
        // Some default action if needed
        break;
}


//Query params
$query_group = get_field("query");
// print_r($query_group);

$query_tax = [
    "categories"
];
//Loop through the taxonomies and get the terms
foreach ($query_tax as $tax) {
    ${$tax} = $query_group["query_" . $tax] ?? "";
}

$query_post_type =
    isset($query_group) && isset($query_group["query_post_type"])
        ? $query_group["query_post_type"]
        : "all";

$query_order = isset($query_group["query_order"])
    ? $query_group["query_order"]
    : "desc";
    
$query_order_by = isset($query_group["query_order_by"])
    ? $query_group["query_order_by"]
    : "date";

//Display pagination
$display_pagination = isset($query_group["display_pagination"])
    ? $query_group["display_pagination"]
    : false;

$post_per_page = get_option("posts_per_page") ?: 12;

//If $number_of_posts is set, use it, otherwise use 12
$numberposts = isset($query_group["number_of_posts"])
    ? $query_group["number_of_posts"]
    : $post_per_page;

//If display_pagination is set, use it, otherwise use false
if ($display_pagination) {
    $numberposts = $post_per_page;
}

$post_args = [
    "post_type" => $query_post_type,
    "posts_per_page" => $numberposts,
    "paged" => $paged,
    "post_parent" => 0,
    "post_status" => "publish",
    "orderby" => $query_order_by,
    "order" => $query_order,
];

//If params are set, add them to the query
if ($categories) {
    $post_args["category__in"] = $categories;
}

//Loop through the taxonomies and add them to the query
foreach ($query_tax as $tax) {
    //Ignore categories as we've already added them to the query
    if ($tax == "categories") {
        continue;
    }
    if (${$tax}) {
        $post_args["tax_query"][] = [
            "taxonomy" => $tax,
            "field" => "term_id",
            "terms" => ${$tax},
        ];
    }
}

// print_r($post_args);

//Add them all to the query
$cards_query = new WP_Query($post_args);

//Count the number of posts
if ($cards_query->have_posts()):
    $cards_count = $cards_query->post_count;
    
    // Pre-load metadata to avoid N+1 queries
    $post_ids = wp_list_pluck($cards_query->posts, 'ID');
    update_object_term_cache($post_ids, get_post_types());
    update_postmeta_cache($post_ids);
    
    // Pre-load ACF field groups and cache field data
    if (function_exists('acf_get_field_groups')) {
        $field_groups = acf_get_field_groups();
        foreach ($post_ids as $post_id) {
            foreach ($field_groups as $field_group) {
                $fields = acf_get_fields($field_group);
                if ($fields) {
                    foreach ($fields as $field) {
                        acf_get_value($post_id, $field);
                    }
                }
            }
        }
    }

endif;

// Card index
$card_index = 1;

//Build out the grids class
$grid_classes_arr = [
    "dgwltd-cards__grid",
    "grid-count--" . $cards_count,
    $grid_classes,
];

?>

<?php //If $block_type is picker, display the picker
if ($block_type == "picker"): ?>
	<div class="<?php echo esc_attr(implode(" ", $grid_classes_arr)); ?>">
		<?php 
        if (!empty($picker)) {
        foreach ($picker as $card): ?>
			<?php
            $card = $card->ID;
            require DGWLTD_PLUGIN_BLOCKS . 'build/cards/partials/card.php';
            $card_index++;
            ?>
		<?php endforeach; 
        }
        ?>
	</div>
<?php // End of the loop.
    //Pagination if more than 12 posts

    else: ?>
	<div class="<?php echo esc_attr(implode(" ", $grid_classes_arr)); ?>">
	<?php if ($cards_query->have_posts()):
     while ($cards_query->have_posts()):
         $cards_query->the_post();
         $card_index = $cards_query->current_post + 1;
         $card = get_the_ID();
         require DGWLTD_PLUGIN_BLOCKS . 'build/cards/partials/card.php';
     endwhile;
     wp_reset_postdata();
 else:
      ?>
		<p class="dgwltd-cards__no-results"><?php esc_html_e(
      "No posts found.",
      "dgwltd-plugin"
  ); ?></p>
	<?php
 endif; ?>
	</div>	
	<?php if ($display_pagination):
     $total_pages = $cards_query->max_num_pages;
     $prev_url = get_previous_posts_page_link();
	 $next_url = get_next_posts_page_link();
     require DGWLTD_PLUGIN_BLOCKS . 'build/cards/partials/pagination.php';
 endif; ?>
	<?php wp_reset_postdata(); ?>	
<?php endif; ?>
