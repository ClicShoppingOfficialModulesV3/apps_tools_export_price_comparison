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
  global $cache, $header2;
  
  if (CLICSHOPPING::getSite('Shop')) {
    if (!Registry::exists('RewriteUrl')) {
      Registry::set('RewriteUrl', new RewriteUrlClass());
    }
    
    $CLICSHOPPING_rewriteUrl = Registry::get('RewriteUrl');
    $CLICSHOPPING_Tax = Registry::get('Tax');
    
    $products_array = ExportPriceComparison::getProducts();
  
    $comp = array("Comparateur de prix Priceminister CSV");
  
    $header = 'Content-type: text/plain; Content-Disposition: "attachment; filename="priceminister.csv ';
  
    $head = '"Reference Produit";	"Votre reference";	"Prix de vente";	"Quantite";	"Qualite";	"Commentaire annonce";	"Commentaire prive annonce";	"Fabricant";' . "\n";
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
      $products_description = html_entity_decode($product_description);
      $product_quantity = $product['products_quantity'];
      
      $product_image = HTTP::getShopUrlDomain() . 'sources/images/' . $product['products_image'];
      $product_url = $CLICSHOPPING_rewriteUrl->getProductNameUrl($product_id);
      $specials_new_products_price = ExportPriceComparison::getSpecialNewPrice($product_id);
    
      $manufacturers_id = $product['manufacturers_id'];
      $manufacturer_name = ExportPriceComparison::getManufacturerName($manufacturers_id);

      $products_price = $product['products_price'];

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

      $output .= '"' . $product_model . '";';
      $output .= '"' . $product_id . '";';
      $output .= $regular_price . ';';
      $output .= $product_quantity . ';';
      $output .= '"n";';
      $output .= '"' . HTMLOverrideCommon::cleanHtml($products_description, 80) . ' : ' . HTMLOverrideCommon::cleanHtml($products_description, 160) . '";';
      $output .= '"";';
      $output .= '"' . $manufacturer_name . '";' . "\n";
    }
    
    $foot = '';
  }
