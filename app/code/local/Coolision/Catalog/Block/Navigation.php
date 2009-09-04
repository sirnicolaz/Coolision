<?php
/**
 * Catalog navigation
 *
 * @category   Mage
 * @package    Mage_Catalog
 * @author      Coolision Team 
 */
class Coolision_Catalog_Block_Navigation extends Mage_Catalog_Block_Navigation
{
    /**
     * Overridden to draw specific menu + submenu table for this web site
     *
     * @param Mage_Catalog_Model_Category $category
     * @param int $level
     * @param boolean $last
     * @return string
     */
    public function drawItem($category, $level=0, $last=false)
    {
        $html = '';
        if (!$category->getIsActive()) {
            return $html;
        }
        if (Mage::helper('catalog/category_flat')->isEnabled()) {
            $children = $category->getChildrenNodes();
            $childrenCount = count($children);
        } else {
            $children = $category->getChildren();
            $childrenCount = $children->count();
        }
        $hasChildren = $children && $childrenCount;
        $html.= '<li';
        
		//if ($hasChildren) {  //We always need a submenu, defined in top.phtml
        $html.= ' onmouseover="toggleMenu(this,1)" onmouseout="toggleMenu(this,0)"';
        //}

        $html.= ' class="level'.$level;
        $html.= ' nav-'.str_replace('/', '-', Mage::helper('catalog/category')->getCategoryUrlPath($category->getRequestPath()));
        if ($this->isCategoryActive($category)) {
            $html.= ' active';
        }
        if ($last) {
            $html .= ' last';
        }
        if ($hasChildren) {
            $cnt = 0;
            foreach ($children as $child) {
                if ($child->getIsActive()) {
                    $cnt++;
                }
            }
            $html .= ' parent';
        }
        $html.= '">'."\n";
        $html.= '<a href="'.$this->getCategoryUrl($category).'"><span>'.$this->htmlEscape($category->getName()).'</span></a>'."\n";

        if ($hasChildren){

            $j = 0;
            $htmlChildren = '';
            foreach ($children as $child) {
                if ($child->getIsActive()) {
                    $htmlChildren.= $this->drawItem($child, $level+1, ++$j >= $cnt);
                }
            }

            if (!empty($htmlChildren)) {
                $html.= '<ul class="level' . $level . '">'."\n"
                        .$htmlChildren
                        .'</ul>';
            }

        }
		
		$html=$this->initSubMenu($html,$level,$category);//Submenu initialization for this menu item
		
        $html.= '</li>'."\n";
        return $html;
    }

	/**
	*  Initialize single submenu
	* @param string $html 
	* @param int $level 
	* @param Mage_Catalog_Model_Category $category
	* @return string
	*/
	
	function initSubMenu($html, $level, $category){
		/* recupero la model della categoria corrente (cioè relativa a questa voce) */
		$categoryModel = Mage::getModel('catalog/category')->load($category->getData('entity_id'));

		/* recupero la model dell'attributo tipologia_capo */
		$productModel = Mage::getModel('catalog/product');
		$attributes = Mage::getResourceModel('eav/entity_attribute_collection')
			      ->setEntityTypeFilter($productModel->getResource()->getTypeId())
			      ->addFieldToFilter('attribute_code', 'tipologia_capo')
			      ->load(false);
		$attributeModel = $attributes->getFirstItem()->setEntity($productModel->getResource());

		/* recupero le opzioni disponibili per quel filtro */
		$options = $attributeModel->getFrontend()->getSelectOptions();

		/* recupero il count per ogni opzione disponibile */
		$optionsCount = Mage::getSingleton('catalogindex/attribute')->getCount($attributeModel, $categoryModel->getProductCollection()->getSelect());

		/* costruisco la lista di opzioni disponibili */
		$data = array();
		foreach ($options as $option) { /* per ogni opzione */
		  if (!empty($optionsCount[$option['value']])) { /* se l'opzione ha valori possibili */
		    $data[] = array(
                                'label' => $option['label'],
                                'value' => $option['value'],
                                'count' => $optionsCount[$option['value']],
                            );
                  }
                } /* data è un array che contiene tutte le opzioni per il filtro tipologia_capo */


// 		$atributeLabel = $categoryModel->getAttribute('topologia_capo')->getFrontend()->getLabel();
		$maxRowNum = 4; //Maximum number of row per submenu
		$htmlChildrenSub = '';
		$subElements = count($data); //Elements number
		if ($subElements != 0) /* SISTEMARE */
		{
		  //Measures calculation
		  $columnNum=(int)( $subElements/$maxRowNum );
		  if($subElements%$maxRowNum){
			  $columnNum=$columnNum+1;
		  }
		  $houndred=100;
		  $percWidth=$houndred / $columnNum;

	    
		  /* ciclo che genera il sub menu (per ora senza senzo) */
		  foreach($data as $d){
			  $htmlChildrenSub=$htmlChildrenSub.'<li style="display:inline !important; width:' . $percWidth . '%;" class="level';
			  $htmlChildrenSub.= $level+1 . ' nav-' . str_replace('/', '-', Mage::helper('catalog/category')->getCategoryUrlPath($category->getRequestPath())) . ' sub-menu-top">' . "\n";
			  $htmlChildrenSub.= '<a href="'. $categoryModel->getUrl().'?tipologia_capo='.$d['value'] .'"><span>' . $d['label'] . '</span></a>' . "\n"; //Menu item content - Per Andrea: inserisci qua il contenuto della singola voce di men�. Il resto dovrebbe essere ok senza bisogno di intervento.  Puoi anche rasare via la stringa e rimpiazzarla con quello che vuoi, basta che lasci intatto quella che c'è sopra e sotto.
			  $htmlChildrenSub.= '</li>' . "\n";
			  
			  //foreach($category->getData() as $label => $value)
			  //{
			  //  $htmlChildrenSub.= '<p>'.$label.'='.$value.'</p><br/>';
			  //}
		  }
		  
		  $width=($columnNum+1)*8;
		  //Adding submenu item to ul list
		  $html.='<ul class="level'. $level . '" style="width:'. $width . 'em !important;">'."\n"
				  .$htmlChildrenSub
				  .'</ul>'."\n";
		}
		return $html;
	}
}
?>