<?php
class Coolision_Catalog_Block_Layer_Filter_Price extends Mage_Catalog_Block_Layer_Filter_Price
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('catalog/layer/price.phtml');
    }
}
?>