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

  use ClicShopping\OM\CLICSHOPPING;
  use ClicShopping\OM\HTTP;

  $comp = array("Comparateur de prix LeGuide.com XML", "Comparateur de prix Twenga XML", "Comparateur de prix Icomparateur XML", "Comparateur de prix C-cher.com XML");

  $products_description = html_entity_decode($products['products_description']);
  $products_description = str_replace('<BR>', '<br />', $products_description);
  $products_description = preg_replace('/\s&nbsp;\s/i', ' ', $products_description);

// les promotions sont elles 1->solde,2->autre promotions
  if ($reduc) $sale = 2;
  $header = 'Content-Type: text/xml';

  $head = '<?xml version="1.0" encoding="UTF-8"?>' . chr(10) . '<catalogue lang="' . $language_code . '" date="' . date('Y-m-d H:i') . '" GMT="+1" version="2.0">' . chr(10);

  $output .= '<product place="' . $product_num . '">' . "\n";
  $output .= '<merchant_category><![CDATA[' . $cat_info[$products['categories_id']]['name'] . ']]></merchant_category>' . chr(10);
  $output .= '<offer_id><![CDATA[' . $products['products_id'] . ']]></offer_id>' . chr(10);
  $output .= '<name><![CDATA[' . $products['products_name'] . ']]></name>' . chr(10);
  $output .= ' <Description><![CDATA[' . substr(strip_tags($products_description), 0, 245) . '...]]></Description>' . chr(10);
  $output .= '<regular_price currency="EUR">' . $regular_price . '</regular_price>' . chr(10);
  $output .= '<product_url><![CDATA[' . CLICSHOPPING::link(null, 'Products&Description&products_id=' . $products['products_id']) . $libre . ']]></product_url>' . chr(10);
  $output .= '<image_url><![CDATA[' . HTTP::getShopUrlDomain() . '/sources/images/' . $products['products_image'] . ']]></image_url>' . chr(10);
  $output .= '<discount_price currency="EUR">' . $discount_price . '</discount_price>' . chr(10);
  $output .= '<sales>' . $sale . '</sales>' . chr(10);//sale peut prendre les valeurs : 0->pas de promotions,1->solde,2->autre promotions
  $output .= '<delivery currency="EUR">FR;-1;</delivery>' . chr(10);
  $output .= '<brand><![CDATA[' . $products['manufacturers_name'] . ']]></brand>' . chr(10);
  $output .= '<model_number><![CDATA[' . $products['products_model'] . ']]></model_number>' . chr(10);
  $output .= '<manufacturer_product_id><![CDATA[]]></manufacturer_product_id>' . chr(10);
  $output .= '<ean13>' . $ean13 . '</ean13>' . chr(10);
  $output .= '<guarantee unit="year"></guarantee>' . chr(10); //unit peut prendre les valeurs : year,month,week,day
  $output .= '<used>0</used>' . chr(10);
  $output .= '<size unit="cm"></size>' . chr(10);
  $output .= '<weight unit="kg">' . $products['products_weight'] . '</weight>' . chr(10);
  $output .= '<color><![CDATA[]]></color>' . chr(10);
  $output .= '<D3E>' . $ecotax_montant . '</D3E>' . chr(10);
  $output .= '</product>';
  $foot = '</catalogue>';
