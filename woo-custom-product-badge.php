<?php

/**
 * Plugin Name: Woo Custom Product Badge
 * Description: This is simple plugin for custom product badge.
 * Author: Sagnika Goswami
 * Version: 1.0.0
 * Requires at least: 6.8.1
 * Requires PHP: 8.2
 */

 if(!defined('ABSPATH')){
    exit;
 }

 // Register the PLugin Badge Meta Box
 add_action('add_meta_boxes', "wcpb_register_product_badge_meta_box");

 function wcpb_register_meta_box(){
    add_meta_box("wcpb_custom_product_badge", "Woo Custom Product Badge", "wcpb_create_product_badge_meta_box", "product", "side");
 }

 // Create the Product Badge Dropdown/ Create the Meta Box for Product Badge
 function wcpb_create_product_badge_meta_box($post){
   $selected_badge = get_post_meta($post->ID, "wcpb_selected_badge", true);
   ?>
   <label for="product-badge-dropdown">Select your Product Badge</label>
   <select name="product-badge-dropdown" id="product-badge-dropdown">
      <option value="none" <?php if($selected_badge == "none" || !($selected_badge)){echo "selected";} ?> >None</option>
      <option value="hot deal" <?php if($selected_badge == "hot deal"){echo "selected";} ?> >Hot Deal</option>
      <option value="best seller" <?php if($selected_badge == "best seller"){echo "selected";} ?> >Best Seller</option>
      <option value="new arrival" <?php if($selected_badge == "new arrival"){echo "selected";} ?> >New Arrival</option>
   </select>
   <?php
 }

 // Saving the Product Badge Meta Box
 add_action("save_post", "wcpb_save_product_badge_meta_box");

 function wcpb_save_product_badge_meta_box($post_id){
   update_post_meta($post_id, "wcpb_selected_badge", sanitize_text_field($_POST['product-badge-dropdown']));
 }

 // Show the Product Badge Over the Product Title
 add_action("woocommerce_single_product_summary", "wcpb_show_product_badge");

 function wcpb_show_product_badge(){
   global $post, $wpdb;

   $post_id = $post->ID;
   $table = $wpdb->prefix . "postmeta";
   $meta_key = "wcpb_selected_badge";

   $selected_badge = $wpdb->get_var(
      $wpdb->prepare("SELECT meta_value FROM {$table} WHERE post_id = %d AND meta_key = %s",
      $post_id,
      $meta_key
      )
   );

   $selected_badge_from_shortcode = get_query_var('custom_badge_products');

   if ($selected_badge != "none") {
    $product_badge_final = '';
    if ($selected_badge == "best seller") {
      $product_badge_final = "Best Seller";
    } else if ($selected_badge == "hot deal") {
      $product_badge_final = "Hot Deal";
    } else if ($selected_badge == "new arrival") {
      $product_badge_final = "New Arrival";
    }
    echo "<div>{$product_badge_final}</div>";
  }

  
}

// Addiing the Shortcode

add_shortcode('custom_badge_products', "wcpb_custom_badge_products");

function wcpb_custom_badge_products($attributes){
   $attributes = shortcode_atts(array(
      "badge" => "none"   
   ), $attributes, "custom_badge_products");

   return $attributes['badge'];
}
?>

