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

  $comp = array("Comparateur de prix InfoPrix.ca CSV");

  $header = 'Content-type: text/plain; Content-Disposition: attachment; filename="infoprix.csv"';

  $head = '"Ref"| "Produit"| "Prix"| "Quantite"| "Description"| Url|' . "\n";

  $output .= '"' . $product_model . '"|';
  $output .= '"' . $product_name . '"|';
  $output .= $regular_price . '|';
  $output .= $products_quantty . '|';
  $output .= HTMLOverrideCommon::cleanHtml($products_description, 160) . '"|';
  $output .= htmlspecialchars($product_url) . "|" . "\n";
  $foot = '';

