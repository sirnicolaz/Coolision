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
	
		$maxRowNum=8; //Maximum number of row per submenu
		$htmlChildrenSub='';
		$subElements=10; //Elements number
		
		//Measures calculation
		$columnNum=(int)($subElements/$maxRowNum);
		if($subElements%$maxRowNum){
			$columnNum=$columnNum+1;
		}
		$houndred=100;
		$percWidth=$houndred / $columnNum;
		
		//Generate submenu item
		for($i=0; $i<$subElements; $i++){
			$htmlChildrenSub=$htmlChildrenSub.'<li style="display:inline !important; width:' . $percWidth . '%;" class="level';

			$htmlChildrenSub.= $level+1 . ' nav-' . str_replace('/', '-', Mage::helper('catalog/category')->getCategoryUrlPath($category->getRequestPath())) . ' sub-menu-top">' . "\n";
			$htmlChildrenSub.= '<a href="http://www.merda.it"><span>Test menu ' . $i . '</span></a>' . "\n";; //Menu item content - Per Andrea: inserisci qua il contenuto della singola voce di men�. Il resto dovrebbe essere ok senza bisogno di intervento.  Puoi anche rasare via la stringa e rimpiazzarla con quello che vuoi, basta che lasci intatto quella che c'� sopra e sotto.
			$htmlChildrenSub.= '</li>' . "\n";
		}
		
		$width=($columnNum+1)*8;
		//Adding submenu item to ul list
		$html.='<ul class="level'. $level . '" style="width:'. $width . 'em !important;">'."\n"
				.$htmlChildrenSub
				.'</ul>'."\n";
		return $html;
	}
}
?>