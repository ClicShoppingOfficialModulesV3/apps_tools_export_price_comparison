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
  
    $comp = array("Comparateur de prix Pricerunner TXT");
  
    $header = 'Content-type: text/plain;';
    $header2 = 'Content-Disposition: "inline; filename=pricerunner.txt';
  
    $head = "Prix TTC\tFabricant\tSKU du Fabricant\tSKU\tEAN\tNom du produit\tCategorie\tURL du produit\tCout de livraison\tNiveau du stock\tVente depart\tVente fin\tAutre SKU\tURL Image produit\tDescription\n";
    
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
      
      $product_image = HTTP::getShopUrlDomain() . 'sources/images/' . $product['products_image'];
      $product_url = $CLICSHOPPING_rewriteUrl->getProductNameUrl($product_id);
      $specials_new_products_price = ExportPriceComparison::getSpecialNewPrice($product_id);
      $ean13 = $product['products_ean'];
    
      $categories_id = $product['categories_id'];
      $categorie_name = ExportPriceComparison::getCategoriesName($categories_id);
    
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

      $output .= $regular_price . "\t";
      $output .= $manufacturer_name . "\t";
      $output .= "N/A\t";
      $output .= $product_id . "\t";
      $output .= $ean13 . "\t";
      $output .= HTMLOverrideCommon::cleanHtml($product_name, 80) . "\t";
      $output .= $categorie_name . "\n";
      $output .= $product_url . "\t";
      $output .= "N/A\t";
      $output .= "\t";
      $output .= "\t";
      $output .= "\t";
      $output .= "\t";
      $output .= $product_image . "\t";
      $output .= HTMLOverrideCommon::cleanHtml($products_description, 160) . "\n";
    }
    
    $foot = '';
  }
