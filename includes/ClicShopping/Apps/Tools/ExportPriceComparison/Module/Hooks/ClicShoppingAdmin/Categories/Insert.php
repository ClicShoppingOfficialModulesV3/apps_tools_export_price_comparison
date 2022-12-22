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

  namespace ClicShopping\Apps\Tools\ExportPriceComparison\Module\Hooks\ClicShoppingAdmin\Categories;

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
        $Qcategories = $this->app->db->prepare('select categories_id 
                                              from :table_categories                                   
                                              order by categories_id desc
                                              limit 1 
                                             ');
        $Qcategories->execute();

        $id = $Qcategories->valueInt('categories_id');

        if (isset($_POST['google_taxonomy_id'])) {
          $google_taxonomy_id = HTML::sanitize($_POST['google_taxonomy_id']);

          $sql_data_array = ['google_taxonomy_id' => (int)$google_taxonomy_id];

          $this->app->db->save('categories', $sql_data_array, ['categories_id' => (int)$id]);
        }
      }
    }
  }