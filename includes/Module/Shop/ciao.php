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
  use ClicShopping\OM\HTML;
  use ClicShopping\OM\HTTP;
  use ClicShopping\OM\CLICSHOPPING;
  use ClicShopping\Sites\Common\HTMLOverrideCommon;
  
  use ClicShopping\Apps\Tools\ExportPriceComparison\Classes\ClicShoppingAdmin\ExportPriceComparison;
  
  use ClicShopping\Sites\Shop\RewriteUrl as RewriteUrlClass;
  
  global $head, $foot;
  global $cache, $header2, $language_code;
 
  $comp = array("Comparateur de prix Ciao XML");
  $header = 'Content-Type: text/xml';
  
  if (CLICSHOPPING::getSite('Shop')) {
    if (!Registry::exists('RewriteUrl')) {
      Registry::set('RewriteUrl', new RewriteUrlClass());
    }
    
    $CLICSHOPPING_rewriteUrl = Registry::get('RewriteUrl');
    $CLICSHOPPING_Tax = Registry::get('Tax');
    $CLICSHOPPING_Currencies = Registry::get('Currencies');
  
    $products_array = ExportPriceComparison::getProducts();
    
    $head = '<?xml version="1.0" encoding="UTF-8"?>' . chr(10);
    $head .= '<catalogue lang="' . $language_code . '" date="' . date('Y-m-d H:i') . '" GMT="+1" version="2.0">' . chr(10);
    
    if (isset($_GET['tax'])) {
      $tax = HTML::sanitize($_GET['tax']);
    } else {
      $tax = '';
    }
    
    foreach ($products_array as $product) {
      $product_id = $product['products_id'];
      $product_name = $product['products_name'];
      $product_description = $product['products_description'];
      $product_image = HTTP::getShopUrlDomain() . 'sources/images/' . $product['products_image'];
      $product_url = $CLICSHOPPING_rewriteUrl->getProductNameUrl($product_id);
      $specials_new_products_price = ExportPriceComparison::getSpecialNewPrice($product_id);
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

      $output .= '  <Product_ID place="' . $product_id . '">' . chr(10);
      $output .= '  <Product_Name><![CDATA[' . $product_name . ']]></Product_Name>' . chr(10);
      $output .= '  <Description><![CDATA[' . substr(strip_tags(HTMLOverrideCommon::cleanHtml($product_description)), 0, 245) . '...]]></Description>' . chr(10);
      $output .= '  <Prices>' . $regular_price . '</Prices>' . chr(10);
      $output .= '  <Deeplink><![CDATA[' . $product_url . ']]></Deeplink>' . chr(10);
      $output .= '  <ImageURL><![CDATA[' . $product_image . ']]></ImageURL>' . chr(10);
      $output .= '  <Shipping_cost></Shipping_cost>' . chr(10);
      $output .= '</Product_ID>';
    }
  
    $foot = '</catalogue>';
  }