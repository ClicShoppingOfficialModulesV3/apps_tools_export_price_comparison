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
  use ClicShopping\OM\CLICSHOPPING;
  use ClicShopping\OM\HTTP;
  
  use ClicShopping\Apps\Tools\ExportPriceComparison\Classes\ClicShoppingAdmin\ExportPriceComparison;
  
  use ClicShopping\Sites\Shop\RewriteUrl as RewriteUrlClass;
  use ClicShopping\Apps\Catalog\Products\Classes\Shop\ProductsCommon as ProductsCommonClass;
  
  global $head, $foot;
  global $cache, $header2, $language_code;
  
  $products_array = ExportPriceComparison::getProducts();
  
  $comp = array("Exportation de la table des produits XML");

  
  if (CLICSHOPPING::getSite('Shop')) {
    
    if (!Registry::exists('RewriteUrl')) {
      Registry::set('RewriteUrl', new RewriteUrlClass());
    }
  
    if (!Registry::exists('ProductsCommon')) {
      Registry::set('ProductsCommon', new ProductsCommonClass());
    }
    
    $CLICSHOPPING_rewriteUrl = Registry::get('RewriteUrl');
    $CLICSHOPPING_Tax = Registry::get('Tax');
    $CLICSHOPPING_Currencies = Registry::get('Currencies');
    $CLICSHOPPING_ProductsCommon = Registry::get('ProductsCommon');
    
    $header = 'Content-Type: text/xml';

  $head = '<?xml version="1.0" encoding="UTF-8"?>' . chr(10);
  $head .= '<catalogue lang="' . $language_code . '" date="' . date('Y-m-d H:i') . '" GMT="+1" version="2.0">' . chr(10);
  
  if (isset($_GET['tax'])) {
    $tax = HTML::sanitize($_GET['tax']);
  } else {
    $tax = '';
  }
  
  $product_num = 0;
  $ecotax_montant = '';
  
  foreach ($products_array as $product) {
    $product_id = $product['products_id'];
    $product_name = $product['products_name'];
    $product_model = $product['products_model'];
    $product_description = $product['products_description'];
    $product_image = HTTP::getShopUrlDomain() . 'sources/images/' . $product['products_image'];
    $product_url = $CLICSHOPPING_rewriteUrl->getProductNameUrl($product_id);
    $specials_new_products_price = ExportPriceComparison::getSpecialNewPrice($product_id);
    $ean13 = $product['products_ean'];
    $product_weight = $product['products_weight'];
    $product_quantity = $product['products_quantity'];
  
    $manufacturers_id = $product['manufacturers_id'];
    $manufacturer_name = ExportPriceComparison::getManufacturerName($manufacturers_id);
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

    $product_num = $product_num + 1;
  
    $output .= '<product place="' . $product_num . '">' . "\n";
    $output .= '<products_id><![CDATA[' . $product_id . ']]></products_id>' . chr(10);
    $output .= '<products_model><![CDATA[' . $product_model . ']]></products_model>' . chr(10);
    $output .= '<product_url><![CDATA[' . $product_url . ']]></product_url>' . chr(10);
    $output .= '<ImageURL><![CDATA[' . $product_image . ']]></ImageURL>' . chr(10);
    $output .= '<regular_price currency="EUR">' . $regular_price . '</regular_price>' . chr(10);

    if (!empty($specials_new_products_price)) {
      $output .= '<discount_price currency="EUR">' . $discount_price . '</discount_price>' . chr(10);
    }

    $output .= '<products_weight>' . $product_weight . '</products_weight>' . chr(10);
    $output .= '<ean13>' . $ean13 . '</ean13>' . chr(10);
    $output .= '<products_stock>' . $product_quantity . '</products_stock>' . chr(10);
    $output .= '<products_name><![CDATA[' . $product_name . ']]></products_name>' . chr(10);
    $output .= '<products_description><![CDATA[' . $product_description . ']]></products_description>' . chr(10);
    $output .= '<manufacturers_name>' . $manufacturer_name . '</manufacturers_name>' . chr(10);
    
    $output .= '</product>';
  }
  
  $foot = '</catalogue>';
  
  }