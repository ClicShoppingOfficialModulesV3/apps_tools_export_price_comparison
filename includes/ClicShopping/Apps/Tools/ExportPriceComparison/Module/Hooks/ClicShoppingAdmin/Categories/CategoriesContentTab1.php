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
  use ClicShopping\OM\CLICSHOPPING;

  use  ClicShopping\Apps\Tools\ExportPriceComparison\ExportPriceComparison as ExportPriceComparisonApp;

  class CategoriesContentTab1 implements \ClicShopping\OM\Modules\HooksInterface
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


    private function getCategoryTaxonomy()
    {
      if (isset($_GET['cID'])) {
        $cID = HTML::sanitize($_GET['cID']);

        $Qcategories = $this->app->db->get('categories', ['google_taxonomy_id'],
            ['categories_id' => $cID]
        );

        $result = $Qcategories->valueInt('google_taxonomy_id');

        if (empty($result)) $result = 0;

        return $result;
      }
    }

    public function display()
    {
      if (!defined('CLICSHOPPING_APP_EXPORT_PRICE_COMPARISON_PC_STATUS') || CLICSHOPPING_APP_EXPORT_PRICE_COMPARISON_PC_STATUS == 'False') {
        return false;
      }

      $this->app->loadDefinitions('Module/Hooks/ClicShoppingAdmin/Categories/page_content_tab_1');

      if (isset($_GET['cPath'])) {
        $google_taxonomy_id = $this->getCategoryTaxonomy();

        $content = '<div class="row" id="tab1ContentGoogleTaxonomy">';
        $content .= '<div class="col-md-12">';
        $content .= '<div class="form-group row">';
        $content .=  $this->app->getDef('text_categories_google_taxonomy_id');
        $content .= '<div class="col-md-6">';
        $content .= HTML::inputField('google_taxonomy_id', $google_taxonomy_id, 'min="0"', 'number') . ' ';
        $content .= '<a href="http://www.google.com/basepages/producttype/taxonomy-with-ids.en-US.txt" target="_blank"><i class="fas fa-external-link-square-alt"></i></a>';
        $content .= '</div>';
        $content .= '</div>';
        $content .= '</div>';
        $content .= '</div>';

      $output = <<<EOD
<!-- ######################## -->
<!-- Start Comparison  -->
<!-- ######################## -->
<script>
$('#categoriesName').append(
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
