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

  $comp = array("Comparateur de prix Kelkoo XML");

  $header = 'Content-Type: text/xml';

  $head = '<?xml version="1.0" encoding="UTF-8"?>' . chr(10);
  $head .= '<catalogue lang="' . $language_code . '" date="' . date('Y-m-d H:i') . '" GMT="+1" version="2.0">' . chr(10);

  $output .= '<product place="' . $product_num . '">' . "\n";
  $output .= '<merchant_category><![CDATA[' . $categorie_name . ']]></merchant_category>' . chr(10);
  $output .= '<offer_id><![CDATA[' . $product_id . ']]></offer_id>' . chr(10);

  $output .= '<name><![CDATA[' . $product_name . ']]></name>' . chr(10);
  $output .= '<description><![CDATA[' . substr(strip_tags($product_description), 0, 245) . '...]]></description>' . chr(10);
  $output .= '<regular_price currency="EUR">' . $regular_price . '</regular_price>' . chr(10);
  $output .= '<product_url><![CDATA[' . $product_url . ']]></product_url>' . chr(10);
  $output .= '<image_url><![CDATA[' . $product_image . ']]></image_url>' . chr(10);
  $output .= '<discount_price currency="EUR">' . $discount_price . '</discount_price>' . chr(10);
  $output .= '<price_discounted_from><![CDATA[' . substr($specials_date_added, 0, 16) . ']]></price_discounted_from>' . chr(10);
  $output .= '<price_discounted_until><![CDATA[' . substr($specials_expires_date, 0, 16) . ']]></price_discounted_until>' . chr(10);
  $output .= '<delivery currency="EUR">FR;-1;</delivery>' . chr(10);;
  $output .= '<brand><![CDATA[ ' . $manufacturer_name . ']]></brand>' . chr(10);
  $output .= '<model_number><![CDATA[' . $product_model . ']]></model_number>' . chr(10);
  $output .= '<ean13>' . $ean13 . '</ean13>' . chr(10);
  $output .= '<used_condition><![CDATA[]]></used_condition>' . chr(10);//ne doit pas dépasser 25 caractères et doit être dans la langue du catalogue
  $output .= '<update_date><![CDATA[' . substr($product_last_modified, 0, 16) . ']]></update_date>' . chr(10);
  $output .= '<offer_valid_from><![CDATA[' . substr($product_date_added, 0, 16) . ']]></offer_valid_from>' . chr(10);
  $output .= '<offer_valid_until><![CDATA[' . substr($products_date_available, 0, 16) . ']]></offer_valid_until>' . chr(10);
  $output .= '<weight unit="kg">' . $product_weight . '</weight>' . chr(10);
  $output .= '<D3E>' . $ecotax_montant . '</D3E>' . chr(10);

  $output .= '</product>';

  $foot = '</catalogue>';
