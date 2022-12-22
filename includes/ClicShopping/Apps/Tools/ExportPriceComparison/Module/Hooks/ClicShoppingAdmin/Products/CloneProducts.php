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

  class CloneProducts implements \ClicShopping\OM\Modules\HooksInterface
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

      if (isset($_GET['Update']) && isset($_POST['clone_categories_id_to']) && isset($_GET['pID'])) {

        $Qproducts = $this->app->db->prepare('select *
                                              from :table_products
                                              where products_id = :products_id
                                             ');
        $Qproducts->bindInt(':products_id', $_GET['pID']);

        $Qproducts->execute();

        $sql_array = ['products_price_comparison' => (int)$Qproducts->valueInt('products_price_comparison')];
        $insert_array = ['products_id' => HTML::sanitize($_POST['clone_products_id'])];

        $this->app->db->save('products', $sql_array, $insert_array);
      }
    }
  }