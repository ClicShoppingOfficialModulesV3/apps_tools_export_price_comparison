<?php
  /**
   *
   * @copyright 2008 - https://www.clicshopping.org
   * @Brand : ClicShopping(Tm) at Inpi all right Reserved
   * @Licence GPL 2 & MIT
   * @licence MIT - Portion of osCommerce 2.4
   * @Info : https://www.clicshopping.org/forum/trademark/
   *
   */

  defined('E_DEPRECATED') ? error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED) : error_reporting(E_ALL & ~E_NOTICE);

  use ClicShopping\Sites\Common\HTMLOverrideCommon;

  $comp = array("Comparateur de prix Facebook CSV");

  $header = 'Content-type: text/plain; Content-Disposition: "attachment; filename="facebook.csv ';

  $head = '"id", "title", "ios_url", "ios_app_store_id", "ios_app_name", "android_url", "android_package", "android_app_name", "windows_phone_url", "windows_phone_app_id", "windows_phone_app_name", "description", "google_product_category", "product_type", "link", "image_link", "condition", "availability", "price", "sale_price", "sale_price_effective_date", "gtin", "brand", "mpn", "item_group_id", "gender", "age_group", "color", "size", "shipping", "custom_label_0";' . "\n";

  $output .= '"' . $product_model . '",';
  $output .= '"' . $product_name . '",';
  $output .= '"",';
  $output .= '"",';
  $output .= '"",';
  $output .= '"",';
  $output .= '"",';
  $output .= '"",';
  $output .= '"",';
  $output .= '"",';
  $output .= '"",';
  $output .= '"' . HTMLOverrideCommon::cleanHtml($product_description) . '",';
  $output .= '"' . $categorie_name . '",';
  $output .= '"",';
  $output .= '"' . $product_url . '",';
  $output .= '"' . $product_image . '",';
  $output .= '"new",';
  $output .= '"in stock",';
  $output .= '"' . $regular_price . ' ' . DEFAULT_CURRENCY . '",';
  $output .= '"",';
  $output .= '"",';
  $output .= '"",';
  if (empty($manufacturer_name)) {
    $output .= '"-",';
  } else {
    $output .= '"' . $manufacturer_name . '",';
  }
  $output .= '"",';
  $output .= '"",';
  $output .= '"",';
  $output .= '"",';
  $output .= '"",';
  $output .= '"",';
  $output .= '"",';
  $output .= '"";' . "\n";

  $foot = '';
