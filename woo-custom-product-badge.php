<?php

/**
 * Plugin Name: Woo Custom Product Badge
 * Description: This is a simple plugin to add custom badges to WooCommerce products.
 * Author: Sagnika Goswami
 * Version: 1.0.0
 * Requires at least: 6.8.1
 * Requires PHP: 7.4
 */


if (!defined('ABSPATH')) {
  exit;
}

// Register Product Badge Meta Box
add_action("add_meta_boxes", "wcpb_register_product_badge_meta_box");

function wcpb_register_product_badge_meta_box()
{
  add_meta_box("wcpb_product_badge_meta_box", "Woo Custom Product Badge", "wpcb_create_product_badge_meta_box", "product", "side");
}

// Creating the Meta Box Dropdown
function wpcb_create_product_badge_meta_box($post)
{
  $selected_badge = get_post_meta($post->ID, "wcpb_product_badge", true);
  if (!$selected_badge) {
    $selected_badge = "none";
  }
 
  ?>
  <label for="badge_dropdown">Select your Product Badge</label>
  <select name="badge_dropdown" id="badge_dropdown">
    <option value="none" <?php if ($selected_badge == "none") {
      echo "selected";
    } ?>>None</option>
    <option value="best seller" <?php if ($selected_badge == "best seller") {
      echo "selected";
    } ?>>Best Seller</option>
    <option value="hot deal" <?php if ($selected_badge == "hot deal") {
      echo "selected";
    } ?>>Hot Deal</option>
    <option value="new arrival" <?php if ($selected_badge == "new arrival") {
      echo "selected";
    } ?>>New Arrival</option>
  </select>
  <?php
}

// Saving the Meta Box Data
add_action("save_post", "wcpb_save_product_badge_meta_data");

function wcpb_save_product_badge_meta_data($post_id)
{
  update_post_meta($post_id, "wcpb_product_badge", $_POST['badge_dropdown']);
}

// Viewing the Product Badge 
add_action("woocommerce_single_product_summary", "wcpb_show_product_badge");

function wcpb_show_product_badge()
{
  global $post;
  $selected_badge = get_post_meta($post->ID, "wcpb_product_badge", true);

  if ($selected_badge && $selected_badge != "none") {
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

// Adding shortcode
  add_action("add_shortcode", "wcpb_filter_product_badge");

  function wcpb_filter_product_badge(){
    
  }


?>