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
  
  use ClicShopping\OM\Registry;
  use ClicShopping\OM\HTTP;
  use ClicShopping\OM\HTML;
  use ClicShopping\OM\CLICSHOPPING;
  use ClicShopping\Sites\Common\HTMLOverrideCommon;
  
  use ClicShopping\Apps\Tools\ExportPriceComparison\Classes\ClicShoppingAdmin\ExportPriceComparison;
  
  use ClicShopping\Sites\Shop\RewriteUrl as RewriteUrlClass;
  
  global $head, $foot;
  global $cache, $header2, $language_code;

  $products_array = ExportPriceComparison::getProducts();
  
  $comp = ['Exportation Google Merchent XML'];
  
  if (CLICSHOPPING::getSite('Shop')) {
    if (!Registry::exists('RewriteUrl')) {
      Registry::set('RewriteUrl', new RewriteUrlClass());
    }
  
    $CLICSHOPPING_rewriteUrl = Registry::get('RewriteUrl');
    $CLICSHOPPING_Tax = Registry::get('Tax');
    $CLICSHOPPING_Currencies = Registry::get('Currencies');
  
    $header = 'Content-Type: text/rss';
  
    $head = '<?xml version="1.0" encoding="UTF-8"?>' . chr(10);
    $head .= '<rss xmlns:g="http://base.google.com/ns/1.0" version="2.0">' . chr(10);
    $head .= '<channel>' . chr(10);
    $head .= '<title>' . HTMLOverrideCommon::cleanHtml(STORE_NAME) . '</title>' . chr(10);
    //  $head .= '<description>' . HTMLOverrideCommon::cleanHtml(STORE_NAME) . '</description>' . chr(10);
    $head .= '<link>' . HTTP::typeUrlDomain() . '</link>' . chr(10);
  
    if (isset($_GET['tax'])) {
      $tax = HTML::sanitize($_GET['tax']);
    } else {
      $tax = '';
    }

    foreach ($products_array as $product) {
      $product_id = $product['products_id'];
      $product_name = $product['products_name'];
      $product_model = $product['products_model'];
      $product_description = $product['products_description'];
      $product_image = HTTP::getShopUrlDomain() . 'sources/images/' . $product['products_image'];
      $product_url = $CLICSHOPPING_rewriteUrl->getProductNameUrl($product_id);
      $specials_new_products_price = ExportPriceComparison::getSpecialNewPrice($product_id);
      $ean = $product['products_ean'];
    
      $manufacturers_id = $product['manufacturers_id'];
      $manufacturer_name = ExportPriceComparison::getManufacturerName($manufacturers_id);
    
      $google_taxonomy_id = $product['google_taxonomy_id'];
      $products_price = $product['products_price'];
      $products_tax_class_id = $product['products_tax_class_id'];

      if ($tax == 'true') {
        $price = $CLICSHOPPING_Tax->addTax($products_price, $CLICSHOPPING_Tax->getTaxRate($products_tax_class_id));
        $featured_product = 'n';

        if (!empty($specials_new_products_price)) {
          $discount_price = $CLICSHOPPING_Tax->addTax($specials_new_products_price, $CLICSHOPPING_Tax->getTaxRate($products_tax_class_id));
          $price = $discount_price;
        }
      } else {
        $price = $products_price;

        if (!empty($specials_new_products_price)) {
          $discount_price = $specials_new_products_price;
          $price = $discount_price;
        }
      }

      $regular_price = $price;
      $regular_price_currencies = $CLICSHOPPING_Currencies->format($price);

      $output .= '<item>' . chr(10);
      $output .= '<g:id>' . HTMLOverrideCommon::cleanHtml($product_model) . '</g:id>' . chr(10);
      $output .= '<g:title>' . substr(strip_tags(HTMLOverrideCommon::cleanHtml($product_name)), 0, 145) . '...</g:title>' . chr(10);
      $output .= '<g:description>' . substr(strip_tags(HTMLOverrideCommon::cleanHtml($product_description)), 0, 4999) . '...</g:description>' . chr(10);
      $output .= '<g:link>' . htmlspecialchars($product_url, ENT_QUOTES | ENT_HTML5) . '</g:link>' . chr(10);
      $output .= '<g:image_link>' . $product_image . '</g:image_link>' . chr(10);
      $output .= '<g:brand>' . substr(strip_tags(HTMLOverrideCommon::cleanHtml($manufacturer_name)), 0, 65) . '...</g:brand>' . chr(10);
    
      $output .= '<g:availability>in stock</g:availability>' . chr(10);
      $output .= '<g:price>' . HTMLOverrideCommon::cleanHtml($regular_price_currencies) . '</g:price>' . chr(10);
      $output .= '<g:shipping_weight>1 kg</g:shipping_weight>' . "\n";
/*
  <g:shipping>
<g:country>US</g:country>
<g:service>Standard</g:service>
<g:price>14.95 USD</g:price>
</g:shipping>
*/

      $output .= '<g:gtin>' . $ean . '</g:gtin>' . "\n";
      $output .= '<g:condition>new</g:condition>' . "\n";
    
      if ($google_taxonomy_id != 0) {
        $output .= '<g:google_product_category>' . $google_taxonomy_id . '</g:google_product_category>' . chr(10);
        $output .= '<g:product_type>' . products_type . '</g:product_type>' . chr(10);
      }
    
      $output .= '</item>' . chr(10);
    }
  
    $foot = '</channel>' . chr(10);
    $foot .= '</rss>';
  }