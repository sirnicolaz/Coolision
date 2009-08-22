<?php

abstract class Coolision_Catalog_Block_Layer_Filter_Abstract extends Mage_Catalog_Block_Layer_Filter_Abstract
{
  /* Funzione per prendere l'attribute code del filtro */   
  public function getAttributeCode()
  {
      return $this->_filter->getRequestVar();
  }
}