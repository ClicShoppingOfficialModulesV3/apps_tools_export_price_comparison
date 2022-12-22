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

  namespace ClicShopping\Apps\Tools\ExportPriceComparison\Module\Hooks\ClicShoppingAdmin\Products;

  use ClicShopping\OM\HTML;
  use ClicShopping\OM\Registry;

  use  ClicShopping\Apps\Tools\ExportPriceComparison\ExportPriceComparison as ExportPriceComparisonApp;


  class Insert implements \ClicShopping\OM\Modules\HooksInterface
  {
    protected $app;

    public function __construct()
    {
      if (!Registry::exists('ExportPriceComparison')) {
        Registry::set('ExportPriceComparison', new ExportPriceComparisonApp());
      }

      $this->app = Registry::get('ExportPriceComparison');
    }

    public function execute()
    {
      if (!defined('CLICSHOPPING_APP_EXPORT_PRICE_COMPARISON_PC_STATUS') || CLICSHOPPING_APP_EXPORT_PRICE_COMPARISON_PC_STATUS == 'False') {
        return false;
      }

      if (isset($_GET['Insert'])) {
        $Qproducts = $this->app->db->prepare('select products_id 
                                              from :table_products                                            
                                              order by products_id desc
                                              limit 1 
                                             ');
        $Qproducts->execute();

        $id = $Qproducts->valueInt('products_id');

        if (isset($_POST['products_price_comparison'])) {
          $sql_data_array = ['products_price_comparison' => (int)HTML::sanitize($_POST['products_price_comparison'])];

          $this->app->db->save('products', $sql_data_array, ['products_id' => (int)$id]);
        }
      }
    }
  }