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

  use ClicShopping\OM\HTTP;
  use ClicShopping\Sites\Common\HTMLOverrideCommon;

  $comp = ['Exportation Google Merchent XML'];

  $header = 'Content-Type: text/xml';

  $head = '<?xml version="1.0" encoding="UTF-8"?>' . chr(10);
  $head .= '<feed xmlns="http://www.w3.org/2005/Atom" xmlns:g="http://base.google.com/ns/1.0">' . chr(10);
  $head .= '<title>' . STORE_NAME . '</title>' . chr(10);
  $head .= '<link>' . HTTP::typeUrlDomain() . '</link>' . chr(10);
  $head .= '<entry>' . chr(10);

//  $output .= '<product place="'.$product_num.'">'."\n";
  $output .= '<g:id>' . $product_model . '</g:id>' . chr(10);
  $output .= '<g:title>' . $product_name . '</g:title>' . chr(10);
  $output .= '<g:description>' . HTMLOverrideCommon::cleanHtml($product_description) . '</g:description>' . chr(10);
  $output .= '<g:link>' . htmlspecialchars($product_url) . '</g:link>' . chr(10);
  $output .= '<g:image_link>' . $product_image . '</g:image_link>' . chr(10);
  $output .= '<g:brand>' . $manufacturer_name . '</g:brand>' . chr(10);
  $output .= '<g:availability>in stock</g:availability>' . chr(10);
  $output .= '<g:price>' . HTMLOverrideCommon::cleanHtml($regular_price_currencies) . '</g:price>' . chr(10);

  if ($google_taxonomy_id != 0) {
    $output .= '<g:google_product_category>' . $google_taxonomy_id . '</g:google_product_category>' . chr(10);
  }

  $foot = '</entry>' . chr(10);
  $foot .= '</feed>';