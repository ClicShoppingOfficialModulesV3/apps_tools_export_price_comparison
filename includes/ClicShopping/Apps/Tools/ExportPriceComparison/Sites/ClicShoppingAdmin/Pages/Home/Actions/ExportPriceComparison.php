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

  namespace ClicShopping\Apps\Tools\ExportPriceComparison\Sites\ClicShoppingAdmin\Pages\Home\Actions;

  use ClicShopping\OM\Registry;

  class ExportPriceComparison extends \ClicShopping\OM\PagesActionsAbstract
  {
    public function execute()
    {
      $CLICSHOPPING_ExportPriceComparison = Registry::get('ExportPriceComparison');

      $this->page->setFile('export_price_comparison.php');

      $CLICSHOPPING_ExportPriceComparison->loadDefinitions('Sites/ClicShoppingAdmin/main');
    }
  }