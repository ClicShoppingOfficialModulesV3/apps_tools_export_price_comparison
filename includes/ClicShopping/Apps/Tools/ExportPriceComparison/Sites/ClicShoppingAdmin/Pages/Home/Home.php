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

  namespace ClicShopping\Apps\Tools\ExportPriceComparison\Sites\ClicShoppingAdmin\Pages\Home;

  use ClicShopping\OM\Registry;

  use ClicShopping\Apps\Tools\ExportPriceComparison\ExportPriceComparison;

  class Home extends \ClicShopping\OM\PagesAbstract
  {
    public mixed $app;

    protected function init()
    {
      $CLICSHOPPING_ExportPriceComparison = new ExportPriceComparison();
      Registry::set('ExportPriceComparison', $CLICSHOPPING_ExportPriceComparison);

      $this->app = Registry::get('ExportPriceComparison');

      $this->app->loadDefinitions('Sites/ClicShoppingAdmin/main');
    }
  }
