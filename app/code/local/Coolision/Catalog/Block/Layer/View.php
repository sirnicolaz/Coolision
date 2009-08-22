<?php

class Coolision_Catalog_Block_Layer_View extends Mage_Catalog_Block_Layer_View
{
    /* Funzione per recuperare la lista dei nomi dei filtri disponibili */
    public function getFiltersName()
    {
      /* Recupera i filtri disponibili */
      $filters = $this->getFilters();

      /* Per ogni filtro recupera la requestVar e la salva in un array */
      $filtersNames = array();
      if (!empty($filters))
      {
	foreach ($filters as $filter)
	{
	  $filtersNames[] = $filter->getAttributeCode();
	}
      }
      return $filtersNames;
    }
}