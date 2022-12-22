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

  $comp = array("Exportation de la table des produits XML");

  $header = 'Content-Type: text/xml';

  $head = '<?xml version="1.0" encoding="UTF-8"?>' . chr(10);
  $head .= '<catalogue lang="' . $language_code . '" date="' . date('Y-m-d H:i') . '" GMT="+1" version="2.0">' . chr(10);

  $output .= '<product place="' . $product_num . '">' . "\n";
  $output .= '<products_id><![CDATA[' . $products_id . ']]></products_id>' . chr(10);
  $output .= '<products_model><![CDATA[' . $product_model . ']]></products_model>' . chr(10);
  $output .= '<product_url><![CDATA[' . $rpoduct_url . ']]></product_url>' . chr(10);
  $output .= '<ImageURL><![CDATA[' . $product_image . ']]></ImageURL>' . chr(10);
  $output .= '<regular_price currency="EUR">' . $regular_price . '</regular_price>' . chr(10);
  $output .= '<discount_price currency="EUR">' . $discount_price . '</discount_price>' . chr(10);
  $output .= '<products_weight>' . $product_weight . '</products_weight>' . chr(10);
  $output .= '<ean13>' . $ean13 . '</ean13>' . chr(10);
  $output .= '<products_stock>' . $product_quantity . '</products_stock>' . chr(10);
  $output .= '<products_name><![CDATA[' . $product_name . ']]></products_name>' . chr(10);
  $output .= '<products_description><![CDATA[' . $product_description . ']]></products_description>' . chr(10);
  $output .= '<manufacturers_name>' . $manufacturer_name . '</manufacturers_name>' . chr(10);
  $output .= '</product>';
  $foot = '</catalogue>';