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

  $comp = array("Comparateur de prix Priceminister CSV");

  $products_description = $products['products_description'];
  $products_description = html_entity_decode($products_description);

  $header = 'Content-type: text/plain; Content-Disposition: "attachment; filename="priceminister.csv ';

  $head = '"Reference Produit";	"Votre reference";	"Prix de vente";	"Quantite";	"Qualite";	"Commentaire annonce";	"Commentaire prive annonce";	"Fabricant";' . "\n";

  $output .= '"' . $products['products_model'] . '";';
  $output .= '"' . $products['products_id'] . '";';
  $output .= $regular_price . ';';
  $output .= $products['products_quantity'] . ';';
  $output .= '"n";';
  $output .= '"' . HTMLOverrideCommon::cleanHtml($products_description, 80) . ' : ' . HTMLOverrideCommon::cleanHtml($products['products_description'], 160) . '";';
  $output .= '"";';
  $output .= '"' . $products['manufacturers_name'] . '";' . "\n";

  $foot = '';

