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

  class ProductsContentTab1 implements \ClicShopping\OM\Modules\HooksInterface
  {
    protected $app;
    protected $number_of_quantity_discount;

    public function __construct()
    {
      if (!Registry::exists('ExportPriceComparison')) {
        Registry::set('ExportPriceComparison', new ExportPriceComparisonApp());
      }

      $this->app = Registry::get('ExportPriceComparison');
    }

    public function display()
    {

      if (!defined('CLICSHOPPING_APP_EXPORT_PRICE_COMPARISON_PC_STATUS') || CLICSHOPPING_APP_EXPORT_PRICE_COMPARISON_PC_STATUS == 'False') {
        return false;
      }

      $this->app->loadDefinitions('Module/Hooks/ClicShoppingAdmin/Products/page_content_tab_1');

      if (isset($_GET['pID'])) {
        $Qcomparison = $this->app->db->get('products', ['products_price_comparison'],
          ['products_id' => (int)$_GET['pID']]
        );

        $comparison = $Qcomparison->valueInt('products_price_comparison');

        if (!isset($comparison)) $comparison = '1';

        switch ($comparison) {
          case '0':
            $in_comparison = false;
            $out_comparison = true;
            break;
          case '1':
          default:
            $in_comparison = true;
            $out_comparison = false;
        }

        $content = '<div class="row col-md-5" id="tab1ContentRow9">';
        $content .= '<div class="col-md-12">';
        $content .= '<div class="form-group row">';
        $content .= '<label for="' . $this->app->getDef('text_products_price_comparison') . '" class="col-5 col-form-label">' . $this->app->getDef('text_products_price_comparison') . '</label>';
        $content .= '<div class="col-md-6">';
        $content .= '<label class="custom-control custom-checkbox mb-2 mr-sm-2 mb-sm-0">';
        $content .= '<span class="col-md-1">' . HTML::radioField('products_price_comparison', '1', $in_comparison) . '&nbsp;' . $this->app->getDef('text_products_price_comparison_yes') . '</span>';
        $content .= '<span class="col-md-1">' . HTML::radioField('products_price_comparison', '0', $out_comparison) . '&nbsp;' . $this->app->getDef('text_products_price_comparison_no') . '</span>';
        $content .= ' </label>';
        $content .= '</div>';
        $content .= '</div>';
        $content .= '</div>';
        $content .= '</div>';

        $output = <<<EOD
<!-- ######################## -->
<!-- Start Comparison  -->
<!-- ######################## -->
<script>
$('#tab1ContentRow8').append(
    '{$content}'
);
</script>

<!-- ######################## -->
<!--  End Comparison  -->
<!-- ######################## -->
<br />
EOD;

        return $output;
      }
    }
  }
