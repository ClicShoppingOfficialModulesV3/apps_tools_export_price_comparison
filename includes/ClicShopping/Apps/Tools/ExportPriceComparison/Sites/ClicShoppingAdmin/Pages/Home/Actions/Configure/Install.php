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

  namespace ClicShopping\Apps\Tools\ExportPriceComparison\Sites\ClicShoppingAdmin\Pages\Home\Actions\Configure;

  use ClicShopping\OM\Registry;
  use ClicShopping\OM\Cache;

  class Install extends \ClicShopping\OM\PagesActionsAbstract
  {

    public function execute()
    {

      $CLICSHOPPING_MessageStack = Registry::get('MessageStack');
      $CLICSHOPPING_ExportPriceComparison = Registry::get('ExportPriceComparison');

      $current_module = $this->page->data['current_module'];

      $CLICSHOPPING_ExportPriceComparison->loadDefinitions('Sites/ClicShoppingAdmin/install');

      $m = Registry::get('ExportPriceComparisonAdminConfig' . $current_module);
      $m->install();

      static::installDbMenuAdministration();
      static::installDb();

      $CLICSHOPPING_MessageStack->add($CLICSHOPPING_ExportPriceComparison->getDef('alert_module_install_success'), 'success', 'ExportPriceComparison');

      $CLICSHOPPING_ExportPriceComparison->redirect('Configure&module=' . $current_module);
    }

    public static function installDb() {
      $CLICSHOPPING_Db = Registry::get('Db');

      $Qcheck = $CLICSHOPPING_Db->query("show columns from :table_categories like 'google_taxonomy_id'");

      if ($Qcheck->fetch() === false) {
        $sql = <<<EOD
ALTER TABLE :table_categories ADD google_taxonomy_id INT(255) NOT NULL DEFAULT '0' AFTER `customers_group_id`;
EOD;

        $CLICSHOPPING_Db->exec($sql);
      }
    }

    private static function installDbMenuAdministration()
    {
      $CLICSHOPPING_Db = Registry::get('Db');
      $CLICSHOPPING_ExportPriceComparison = Registry::get('ExportPriceComparison');
      $CLICSHOPPING_Language = Registry::get('Language');

      $Qcheck = $CLICSHOPPING_Db->get('administrator_menu', 'app_code', ['app_code' => 'app_tools_export_price_comparison']);

      if ($Qcheck->fetch() === false) {

        $sql_data_array = ['sort_order' => 1,
          'link' => 'index.php?A&Tools\ExportPriceComparison&ExportPriceComparison',
          'image' => 'comparison_export.gif',
          'b2b_menu' => 0,
          'access' => 0,
          'app_code' => 'app_tools_export_price_comparison'
        ];

        $insert_sql_data = ['parent_id' => 175];

        $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

        $CLICSHOPPING_Db->save('administrator_menu', $sql_data_array);

        $id = $CLICSHOPPING_Db->lastInsertId();

        $languages = $CLICSHOPPING_Language->getLanguages();

        for ($i = 0, $n = count($languages); $i < $n; $i++) {

          $language_id = $languages[$i]['id'];

          $sql_data_array = ['label' => $CLICSHOPPING_ExportPriceComparison->getDef('title_menu')];

          $insert_sql_data = ['id' => (int)$id,
            'language_id' => (int)$language_id
          ];

          $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

          $CLICSHOPPING_Db->save('administrator_menu_description', $sql_data_array);
        }

        Cache::clear('menu-administrator');
      }
    }
  }
