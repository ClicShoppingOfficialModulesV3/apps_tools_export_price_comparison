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

  use ClicShopping\OM\CLICSHOPPING;
  use ClicShopping\Sites\Common\HTMLOverrideCommon;

  defined('E_DEPRECATED') ? error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED) : error_reporting(E_ALL & ~E_NOTICE);

  $comp = array("Comparateur de prix Ciao XML");
  $header = 'Content-Type: text/xml';

  $head = '<?xml version="1.0" encoding="UTF-8"?>' . chr(10);
  $head .= '<catalogue lang="' . $language_code . '" date="' . date('Y-m-d H:i') . '" GMT="+1" version="2.0">' . chr(10);

  $output .= '  <Product_ID place="' . $product_id . '">' . chr(10);
  $output .= '  <Product_Name><![CDATA[' . $product_name . ']]></Product_Name>' . chr(10);
  $output .= '  <Description><![CDATA[' . substr(strip_tags(HTMLOverrideCommon::cleanHtml($product_description)), 0, 245) . '...]]></Description>' . chr(10);
  $output .= '  <Prices>' . $regular_price . '</Prices>' . chr(10);
  $output .= '  <Deeplink><![CDATA[' . $product_url . ']]></Deeplink>' . chr(10);
  $output .= '  <ImageURL><![CDATA[' . $product_image . ']]></ImageURL>' . chr(10);
  $output .= '  <Shipping_cost></Shipping_cost>' . chr(10);

  $output .= '</Product_ID>';

  $foot = '</catalogue>';
