<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category   Mage
 * @package    Mage_CatalogIndex
 * @copyright  Copyright (c) 2008 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


/**
 * Attribute index resource model
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Coolision_CatalogIndex_Model_Mysql4_Attribute extends Mage_CatalogIndex_Model_Mysql4_Attribute
{

	public function applyFilterToCollection($collection, $attribute, $value)
	{
		$alias = 'attr_index_'.$attribute->getId();
// 		echo "applyfiltertocollection";
		//Build query string
		$query=$alias.'.value = ?';
		$val=$value[0];
		foreach($value as $v)
		{
			if($v!==$val)
				$query = $query.' OR '.$alias.'.value = '.$v;
		}
		
        $collection->getSelect()->join(
            array($alias => $this->getMainTable()),
            $alias.'.entity_id=e.entity_id',
            array()
        )
        ->where($alias.'.store_id = ?', $this->getStoreId())
        ->where($alias.'.attribute_id = ?', $attribute->getId())
        ->where($query, $val);
        return $this;
	}
}