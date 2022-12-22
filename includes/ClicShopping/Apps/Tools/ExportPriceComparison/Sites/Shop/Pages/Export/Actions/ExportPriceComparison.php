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


  namespace ClicShopping\Apps\Tools\ExportPriceComparison\Sites\Shop\Pages\Export\Actions;

  use ClicShopping\OM\HTML;
  use ClicShopping\OM\CLICSHOPPING;
  use ClicShopping\OM\Registry;
  use ClicShopping\OM\HTTP;
  use ClicShopping\Sites\Shop\Tax;

  defined('E_DEPRECATED') ? error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED) : error_reporting(E_ALL & ~E_NOTICE);

  class ExportPriceComparison extends \ClicShopping\OM\PagesActionsAbstract
  {

    protected $rewriteUrl;

    public function execute()
    {
      global $head, $foot, $language_code;

      $CLICSHOPPING_Db = Registry::get('Db');
      $CLICSHOPPING_Language = Registry::get('Language');
      $CLICSHOPPING_Tax = Registry::get('Tax');
      $CLICSHOPPING_Currencies = Registry::get('Currencies');

      $this->rewriteUrl = Registry::get('RewriteUrl');

      set_time_limit(0);

      $output = '';

      $verif = true;

      if (isset($_GET['tax'])) $tax = HTML::sanitize($_GET['tax']);
      if (isset($_GET['ean'])) $ean = HTML::sanitize($_GET['ean']);

      $pass = HTML::sanitize(EXPORT_CODE);

      $language_code = (isset($_GET['language']) && !is_null($_GET['language'])) ? HTML::sanitize($_GET['language']) : DEFAULT_LANGUAGE;

      if (isset($_GET['p'])) $p = HTML::sanitize($_GET['p']);
      if (isset($_GET['format'])) $format = basename(HTML::sanitize($_GET['format']));
      if (isset($_GET['cache'])) $cache = HTML::sanitize($_GET['cache']);
      if (isset($_GET['fichier'])) $fichier = HTML::sanitize($_GET['fichier']);
      if (isset($_GET['libre'])) $libre = HTML::sanitize($_GET['libre']);

      if (isset($_GET['rep']) && $_GET['rep'] == 1) {
        $rep = 'export/secure/';
      } else {
        $rep = 'export/';
      }

//On verifie le code avant de lancer les requetes
      if (($verif === true && $p == $pass) || $verif === false) {


        $QincludedCategories = $CLICSHOPPING_Db->prepare('select c.categories_id,
                                                                  c.parent_id,
                                                                  c.google_taxonomy_id,
                                                                  cd.categories_name
                                                           from :table_categories c,
                                                                :table_categories_description cd
                                                           where c.categories_id = cd.categories_id
                                                           and cd.language_id = :language_id
                                                          ');
        $QincludedCategories->bindInt(':language_id', (int)$CLICSHOPPING_Language->getId());

        $QincludedCategories->execute();

        $inc_cat = [];

        // Identification du nom de la categorie, et l'id de la categorie parent
        while ($QincludedCategories->fetch()) {
          $inc_cat[] = array('id' => $QincludedCategories->valueInt('categories_id'),
            'parent' => $QincludedCategories->valueInt('parent_id'),
            'name' => $QincludedCategories->value('categories_name'),
            'google_taxonomy_id' => $QincludedCategories->value('google_taxonomy_id')
          );
        }

        $cat_info = [];

        for ($i = 0; $i < count($inc_cat); $i++)
          $cat_info[$inc_cat[$i]['id']] = array('parent' => $inc_cat[$i]['parent'],
            'name' => $inc_cat[$i]['name'],
            'path' => $inc_cat[$i]['id'],
            'link' => ''
          );
        for ($i = 0; $i < count($inc_cat); $i++) {
          $cat_id = $inc_cat[$i]['id'];

          while ($cat_info[$cat_id]['parent'] != 0) {
            $cat_info[$inc_cat[$i]['id']]['path'] = $cat_info[$cat_id]['parent'] . '_' . $cat_info[$inc_cat[$i]['id']]['path'];
            $cat_id = $cat_info[$cat_id]['parent'];
          }

          $link_array = preg_split('#_#', $cat_info[$inc_cat[$i]['id']] ['path']);

          for ($j = 0; $j < count($link_array); $j++) {
            $cat_info[$inc_cat[$i]['id']]['link'] .= '&nbsp;' . HTML::link(CLICSHOPPING::link(null, 'cPath=' . $cat_info[$link_array[$j]]['path']), '<nobr>' . $cat_info[$link_array[$j]]['name'] . '</nobr>') . '&nbsp;&raquo;&nbsp;';
          }
        }

// Requete identifiant les produits disponibles dans le catalogue
        $Qproducts = $CLICSHOPPING_Db->prepare('select p.*,
                                                       pd.*,
                                                       pc.categories_id,
                                                       pr.date_added as review_date,
                                                       pr.customers_name,
                                                       pr.reviews_rating,
                                                       pt.reviews_text,
                                                       pt.languages_id as lngr,
                                                       c.google_taxonomy_id
                                                  from (:table_products p,
                                                        :table_products_description pd,
                                                        :table_products_to_categories p2c,
                                                        :table_categories c,
                                                        :table_products_to_categories pc)
                                                          left join :table_reviews as pr on (p.products_id = pr.products_id)
                                                          left join :table_reviews_description as pt ON (pr.reviews_id = pt.reviews_id)
                                                  where p.products_id = pd.products_id
                                                  and p.products_id = pc.products_id
                                                  and p.products_status = 1
                                                  and p.products_archive = 0
                                                  and p.products_quantity > 0
                                                  and p.products_price_comparison = 1
                                                  and p.products_view = 1  
                                                  and pd.language_id = :language_id
                                                  and p.products_id = p2c.products_id
                                                  and p2c.categories_id = c.categories_id
                                                  and c.status = 1
                                                  order by pc.categories_id,
                                                          pd.products_name
                                                ');

        $Qproducts->bindInt(':language_id', $CLICSHOPPING_Language->getId());
        $Qproducts->execute();

        $product_num = 0;

        while ($Qproducts->fetch()) {
          $product_id = $Qproducts->valueInt('products_id');
          $product_model = $Qproducts->value('products_model');
          $product_name = $Qproducts->value('products_name');
          $product_description = $Qproducts->value('products_description');
          $product_quantty = $Qproducts->valueInt('products_quantity');

          $product_image = HTTP::getShopUrlDomain() . 'sources/images/' . $Qproducts->value('products_image');
          $product_url = $this->rewriteUrl->getProductNameUrl($product_id) . $libre;

          $categorie_name = $cat_info[$i]['name'];
          $google_taxonomy_id = $Qproducts->valueInt('google_taxonomy_id');

          if ($Qproducts->valueInt('manufacturers_id') > 0) {
            $Qmanufacturers = $CLICSHOPPING_Db->prepare('select manufacturers_name
                                                         from :table_manufacturers
                                                         where manufacturers_id = :manufacturers_id
                                                         and manufacturers_status = 1
                                                        ');
            $Qmanufacturers->bindInt(':manufacturers_id', $Qproducts->valueInt('manufacturers_id'));

            $Qmanufacturers->execute();

            $manufacturer_name = $Qmanufacturers->value('manufacturers_name');
          }

          $Qspecials = $CLICSHOPPING_Db->prepare('select specials_new_products_price ,
                                                          expires_date,
                                                          specials_date_added
                                                   from :table_specials
                                                   where products_id = :products_id
                                                   and status = 1
                                                   limit 1
                                                  ');
          $Qspecials->bindInt(':products_id', $Qproducts->valueInt('products_id'));

          $Qspecials->execute();

          $specials_date_added = $Qspecials->value('specials_date_added');
          $specials_expires_date = $Qspecials->value('expires_date');

          $product_num++;

//calcul des prix
// la variable $reduc permet de tester s'il y a une promo
          if ($tax == 'true') {
            $price = Tax::addTax($Qproducts->value('products_price'), $CLICSHOPPING_Tax->getTaxRate($Qproducts->valueInt('products_tax_class_id')));
            $featured_product = 'n';
          } else {
            $price = $Qproducts->value('products_price');
            $featured_product = 'n';
          }

          if (!empty($Qspecials->value('specials_new_products_price'))) {
            $discount_price = '';
            $regular_price = $price;
            $reduc = false;
          } else {
            if ($tax == 'true') {
              $discount_price = Tax::addTax($Qspecials->value('specials_new_products_price'), $CLICSHOPPING_Tax->getTaxRate($Qproducts->valueInt('products_tax_class_id')));
            } else {
              $discount_price = $Qspecials->value('specials_new_products_price');
            }
            $regular_price = $price;
            $regular_price_currencies = $CLICSHOPPING_Currencies->format($price);
            $featured_product = 'y';
            $reduc = true;
          }

// Test barcod mod
          if ($ean == 'true') {
            $ean13 = $Qproducts->value('products_ean');
          }

          include('includes/Module/Export/Shop/' . $format);
        }

        $content = $head . $output . $foot;

        if ($cache != 'true') {
          Header($header);
          if ($header2) Header($header2);
          $display_output = $content;
        } else {
          $fp = fopen(CLICSHOPPING::getConfig('dir_root', 'Shop') . 'ext/' . $rep . $fichier, 'w');
          fputs($fp, $content);
          fclose($fp);

          $display_output = '
            <div class="contentext">
             <div style="text-align: center; padding-top:200px;"><p>Opération réalisée avec succès - Veuillez fermer cette page <br /></p></div>
             <div style="text-align: center; padding-top:10px;"> Success Operation - Please close this page</div>
             <div style="text-align: center;  padding-top:10px"><img src="images/logo_clicshopping_1.png"></td></div>
           </div>
          ';
        }
      }

      print_r($display_output);
      exit;
    }
  }