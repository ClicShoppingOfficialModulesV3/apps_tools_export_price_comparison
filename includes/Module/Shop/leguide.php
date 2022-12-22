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
    
    $comp = array("Comparateur de prix LeGuide.com XML", "Comparateur de prix Twenga XML", "Comparateur de prix Icomparateur XML", "Comparateur de prix C-cher.com XML");
  
    $header = 'Content-Type: text/xml';
  
    $head = '<?xml version="1.0" encoding="UTF-8"?>' . chr(10) . '<catalogue lang="' . $language_code . '" date="' . date('Y-m-d H:i') . '" GMT="+1" version="2.0">' . chr(10);
    
    if (isset($_GET['tax'])) {
      $tax = HTML::sanitize($_GET['tax']);
    } else {
      $tax = '';
    }
    
    $product_num = 0;
    $ecotax_montant =0;
    
    foreach ($products_array as $product) {
      $product_id = $product['products_id'];
      $product_name = $product['products_name'];
      $product_model = $product['products_model'];
      $products_description = $product['products_description'];
    
      $products_description = html_entity_decode($products_description);
      $products_description = str_replace('<BR>', '<br />', $products_description);
      $products_description = preg_replace('/\s&nbsp;\s/i', ' ', $products_description);
      
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
      $sale = 0;

      if ($tax == 'true') {
        $price = $CLICSHOPPING_Tax->addTax($products_price, $CLICSHOPPING_Tax->getTaxRate($products_tax_class_id));
        $featured_product = 'n';

        if (!empty($specials_new_products_price)) {
          $discount_price = $CLICSHOPPING_Tax->addTax($specials_new_products_price, $CLICSHOPPING_Tax->getTaxRate($products_tax_class_id));
          $price = $discount_price;
          $sale = 1;
        }
      } else {
        $price = $products_price;

        if (!empty($specials_new_products_price)) {
          $discount_price = $specials_new_products_price;
          $price = $discount_price;
          $sale = 1;
        }
      }

      $regular_price = $price;

      $product_num = $product_num + 1;
      
      $output .= '<product place="' . $product_num . '">' . "\n";
      $output .= '<merchant_category><![CDATA[' . $categorie_name . ']]></merchant_category>' . chr(10);
      $output .= '<offer_id><![CDATA[' . $product_id . ']]></offer_id>' . chr(10);
      $output .= '<name><![CDATA[' . $categorie_name . ']]></name>' . chr(10);
      $output .= ' <Description><![CDATA[' . substr(strip_tags($products_description), 0, 245) . '...]]></Description>' . chr(10);
      $output .= '<regular_price currency="EUR">' . $regular_price . '</regular_price>' . chr(10);
      $output .= '<product_url><![CDATA[' . CLICSHOPPING::link(null, 'Products&Description&products_id=' . $product_id) . ']]></product_url>' . chr(10);
      $output .= '<image_url><![CDATA[' . HTTP::getShopUrlDomain() . '/sources/images/' . $product_image . ']]></image_url>' . chr(10);

      if (!empty($specials_new_products_price)) {
        $output .= '<discount_price currency="EUR">' . $discount_price . '</discount_price>' . chr(10);
      }


      $output .= '<sales>' . $sale . '</sales>' . chr(10);//sale peut prendre les valeurs : 0->pas de promotions,1->solde,2->autre promotions
      $output .= '<delivery currency="EUR">FR;-1;</delivery>' . chr(10);
      $output .= '<brand><![CDATA[' . $manufacturer_name . ']]></brand>' . chr(10);
      $output .= '<model_number><![CDATA[' . $product_model . ']]></model_number>' . chr(10);
      $output .= '<manufacturer_product_id><![CDATA[]]></manufacturer_product_id>' . chr(10);
      $output .= '<ean13>' . $ean13 . '</ean13>' . chr(10);
      $output .= '<guarantee unit="year"></guarantee>' . chr(10); //unit peut prendre les valeurs : year,month,week,day
      $output .= '<used>0</used>' . chr(10);
      $output .= '<size unit="cm"></size>' . chr(10);
      $output .= '<weight unit="kg">' . $product_weight . '</weight>' . chr(10);
      $output .= '<color><![CDATA[]]></color>' . chr(10);
      $output .= '<D3E>' . $ecotax_montant . '</D3E>' . chr(10);
      $output .= '</product>';
    }
  
    $foot = '</catalogue>';
  }