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
  
  if (CLICSHOPPING::getSite('Shop')) {
    if (!Registry::exists('RewriteUrl')) {
      Registry::set('RewriteUrl', new RewriteUrlClass());
    }
    
    $CLICSHOPPING_rewriteUrl = Registry::get('RewriteUrl');
    $CLICSHOPPING_Tax = Registry::get('Tax');
    $CLICSHOPPING_Currencies = Registry::get('Currencies');
    
    $products_array = ExportPriceComparison::getProducts();
    
    $comp = array("Comparateur de prix Kelkoo XML");
  
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
      $products_date_available = $product['products_date_available'];
      $product_last_modified = $product['products_last_modified'];
      $product_date_added = $product['products_date_added'];
    
      $categories_id = $product['categories_id'];
      $categorie_name = ExportPriceComparison::getCategoriesName($categories_id);
    
      $manufacturers_id = $product['manufacturers_id'];
      $manufacturer_name = ExportPriceComparison::getManufacturerName($manufacturers_id);
    
      //$specials_date_added = ExportPriceComparison::$specials_date_added;
      //$specials_expires_date == ExportPriceComparison::$specials_expires_date;
      $specials_date_added = '';
      $specials_expires_date = '';
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
      $output .= '<merchant_category><![CDATA[' . $categorie_name . ']]></merchant_category>' . chr(10);
      $output .= '<offer_id><![CDATA[' . $product_id . ']]></offer_id>' . chr(10);
    
      $output .= '<name><![CDATA[' . $product_name . ']]></name>' . chr(10);
      $output .= '<description><![CDATA[' . substr(strip_tags($product_description), 0, 245) . '...]]></description>' . chr(10);
      $output .= '<regular_price currency="EUR">' . $regular_price . '</regular_price>' . chr(10);
      $output .= '<product_url><![CDATA[' . $product_url . ']]></product_url>' . chr(10);
      $output .= '<image_url><![CDATA[' . $product_image . ']]></image_url>' . chr(10);

      if (!empty($specials_new_products_price)) {
        $output .= '<discount_price currency="EUR">' . $discount_price . '</discount_price>' . chr(10);
      }

      $output .= '<price_discounted_from><![CDATA[' . substr($specials_date_added, 0, 16) . ']]></price_discounted_from>' . chr(10);
      $output .= '<price_discounted_until><![CDATA[' . substr($specials_expires_date, 0, 16) . ']]></price_discounted_until>' . chr(10);
      $output .= '<delivery currency="EUR">FR;-1;</delivery>' . chr(10);
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
    }
    
    $foot = '</catalogue>';
  }