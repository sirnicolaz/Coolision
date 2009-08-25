<?php

abstract class Coolision_Catalog_Block_Layer_Filter_Abstract extends Mage_Catalog_Block_Layer_Filter_Abstract
{
  /* NON CARICA! PROBABILMENTE PERCHÈ NON È COSÌ CHE SI REDIFINISCE UNA CLASSE ASTRATTA */
  /* Funzione per prendere l'attribute code del filtro */   
  public function getAttributeCode()
  {
      jewhfowefew
      return $this->_filter->getRequestVar();
  }
}