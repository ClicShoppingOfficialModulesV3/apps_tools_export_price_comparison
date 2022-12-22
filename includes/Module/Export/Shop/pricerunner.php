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

  $comp = array("Comparateur de prix Pricerunner TXT");

  $header = 'Content-type: text/plain;';
  $header2 = 'Content-Disposition: "inline; filename=pricerunner.txt';

  $products_description = $products['products_description'];
  $products_description = html_entity_decode($products_description);

  $head = "Prix TTC\tFabricant\tSKU du Fabricant\tSKU\tEAN\tNom du produit\tCat�gorie\tURL du produit\tCo�t de livraison\tNiveau du stock\tVente d�part\tVente fin\tAutre SKU\tURL Image produit\tDescription\n";

  $output .= $regular_price . "\t";
  $output .= $manufacturer_name . "\t";
  $output .= "N/A\t";
  $output .= $products_id . "\t";
  $output .= $ean13 . "\t";
  $output .= HTMLOverrideCommon::cleanHtml($product_name, 80) . "\t";
  $output .= $categorie_name . "\n";
  $output .= $product_url . "\t";
  $output .= "N/A\t";
  $output .= "\t";
  $output .= "\t";
  $output .= "\t";
  $output .= "\t";
  $output .= $product_image . "\t";
  $output .= HTMLOverrideCommon::cleanHtml($product_description, 160) . "\n";

  $foot = '';

