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

  $comp = array("Comparateur de prix Google Merchant/Achat TXT");

  $header = 'Content-type: text/plain;';
  $header2 = 'Content-Disposition: "inline; filename=google_merchant_txt.txt';

  $head = "id\tmpn\ttitle\tprice\tlink\timage link\tdescription\tbrand\tfeatured_product\tcondition\tavailability\tproduct_categories\tgoogle_product_category\tgtin\n";

  $output .= "id" . $product_id . "\t";
  $output .= $product_model . "\t";

  $output .= HTMLOverrideCommon::cleanHtml($product_name, 80) . "\t";

  $output .= $price . "\t";
  $output .= $product_url . "\t";
  $output .= $product_image . "\t";

  $output .= HTMLOverrideCommon::cleanHtml($product_description, 10000) . "\t";
//  $output .= $product_description. "\t";
  $output .= $manufacturer_name . "\t";
  $output .= $featured_product . "\t";
  $output .= "new\t";
  $output .= "in stock\t";

  $output .= HTMLOverrideCommon::cleanHtml($categorie_name, 80) . "\t";

  if ($google_taxonomy_id != 0) {
    $output .= '<g:google_​​product_​​category>' . $google_taxonomy_id  . "\t";
  }

//  $output .= $categorie_name ."\t";
  $output .= $ean . "\n";
  $foot = '';