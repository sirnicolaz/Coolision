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
 * @package    Mage_Catalog
 * @copyright  Copyright (c) 2009 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


/**
 * Catalog view layer model
 *
 * @category    Mage
 * @package     Mage_Catalog
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Catalog_Model_Layer extends Varien_Object
{
    /**
     * Product collections array
     *
     * @var array
     */
    protected $_productCollections = array();

    /**
     * Key which can be used for load/save aggregation data
     *
     * @var string
     */
    protected $_stateKey = null;

    /**
     * Get data aggregation object
     *
     * @return Mage_CatalogIndex_Model_Aggregation
     */
    public function getAggregator()
    {
        return Mage::getSingleton('catalogindex/aggregation');
    }

    /**
     * Get layer state key
     *
     * @return string
     */
	/*Sta merda minaccia di non servire a nulla. Test eseguito: mettere un attributo settato di default, ovvero color=4, nella stringa _statekey. Il comportamento nell'atto di applicare un qualsiasi filtri di tipo colore non � cambiato.*/
    public function getStateKey()
    {
        if ($this->_stateKey === null) {
            $this->_stateKey = 'STORE_'.Mage::app()->getStore()->getId()
                . '_CAT_'.$this->getCurrentCategory()->getId()
                . '_CUSTGROUP_' . Mage::getSingleton('customer/session')->getCustomerGroupId();
        }
        return $this->_stateKey;
    }

    /**
     * Get default tags for current layer state
     *
     * @param   array $additionalTags
     * @return  array
     */
    public function getStateTags(array $additionalTags = array())
    {
        $additionalTags = array_merge($additionalTags, array(
            Mage_Catalog_Model_Category::CACHE_TAG.$this->getCurrentCategory()->getId()
        ));
        return $additionalTags;
    }

    /**
     * Retrieve current layer product collection
     *
     * @return Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection
     */
	 
    public function getProductCollection()
    {
        if (isset($this->_productCollections[$this->getCurrentCategory()->getId()])) {
            $collection = $this->_productCollections[$this->getCurrentCategory()->getId()];
        }
        else {
            $collection = $this->getCurrentCategory()->getProductCollection(); //NEW: Viene prelevata l'intera categoria di prodotti senza filtri
            $this->prepareProductCollection($collection);
            $this->_productCollections[$this->getCurrentCategory()->getId()] = $collection; //NEW: l'array in cui viene inserita la collezione parrebbe essere una sorta di cache, da cui si attinge nel ramo condizionale if (vedi sopra)
        }

        return $collection;
    }

    /**
     * Initialize product collection
     *
     * @param Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection $collection
     * @return Mage_Catalog_Model_Layer
     */
    public function prepareProductCollection($collection)
    {
        $attributes = Mage::getSingleton('catalog/config')
            ->getProductAttributes(); //NEW: Torna un array con tutti i codici degli attributi (verde, giacca...)
        $collection->addAttributeToSelect($attributes) //NEW: probabilmente qui vengono filtrati i prodotti della collezione
            ->addMinimalPrice()
            ->addFinalPrice()
            ->addTaxPercents()
            //->addStoreFilter()
            ;

        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($collection);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($collection);
        $collection->addUrlRewrite($this->getCurrentCategory()->getId());

        return $this;
    }

    /**
     * Apply layer
     * Method is colling after apply all filters, can be used
     * for prepare some index data before getting information
     * about existing intexes
     *
     * @return Mage_Catalog_Model_Layer
     */
    public function apply()
    {
        $stateSuffix = '';
        foreach ($this->getState()->getFilters() as $filterItem) {
            $stateSuffix.= '_'.$filterItem->getFilter()->getRequestVar()
                . '_' . $filterItem->getValueString();
        }
        if (!empty($stateSuffix)) {
            $this->_stateKey = $this->getStateKey().$stateSuffix;
        }
        return $this;
    }

    /**
     * Retrieve current category model
     * If no category found in registry, the root will be taken
     *
     * @return Mage_Catalog_Model_Category
     */
    public function getCurrentCategory()
    {
        $category = $this->getData('current_category');
        if (is_null($category)) {
            if ($category = Mage::registry('current_category')) {
                $this->setData('current_category', $category);
            }
            else {
                $category = Mage::getModel('catalog/category')->load($this->getCurrentStore()->getRootCategoryId());
                $this->setData('current_category', $category);
            }
        }
        return $category;
    }

    /**
     * Change current category object
     *
     * @param mixed $category
     * @return Mage_Catalog_Model_Layer
     */
    public function setCurrentCategory($category)
    {
        if (is_numeric($category)) {
            $category = Mage::getModel('catalog/category')->load($category);
        }
        if (!$category instanceof Mage_Catalog_Model_Category) {
            Mage::throwException(Mage::helper('catalog')->__('Category must be instance of Mage_Catalog_Model_Category'));
        }
        if (!$category->getId()) {
            Mage::throwException(Mage::helper('catalog')->__('Invalid category'));
        }

        if ($category->getId() != $this->getCurrentCategory()->getId()) {
            $this->setData('current_category', $category);
        }

        return $this;
    }

    /**
     * Retrieve current store model
     *
     * @return Mage_Core_Model_Store
     */
    public function getCurrentStore()
    {
        return Mage::app()->getStore();
    }

    /**
     * Get collection of all filterable attributes for layer products set
     *
     * @return Mage_Eav_Model_Mysql4_Entity_Attribute_Collection
     */
    public function getFilterableAttributes()
    {
        $entity = Mage::getSingleton('eav/config')
            ->getEntityType('catalog_product');

        $setIds = $this->_getSetIds();
        if (!$setIds) {
            return array();
        }

        $collection = Mage::getModel('eav/entity_attribute')
            ->getCollection()
            ->setItemObjectClass('catalog/resource_eav_attribute');

        /* @var $collection Mage_Eav_Model_Mysql4_Entity_Attribute_Collection */
        $collection->getSelect()->distinct(true);
        $collection
            ->setEntityTypeFilter($entity->getId())
            ->setAttributeSetFilter($setIds)
            ->setOrder('position', 'ASC');
        $collection = $this->_prepareAttributeCollection($collection);
        $collection->load();

        return $collection;
    }

    /**
     * Prepare attribute for use in layered navigation
     *
     * @param   Mage_Eav_Model_Entity_Attribute $attribute
     * @return  Mage_Eav_Model_Entity_Attribute
     */
    protected function _prepareAttribute($attribute)
    {
        Mage::getResourceSingleton('catalog/product')->getAttribute($attribute);
        return $attribute;
    }

    /**
     * Add filters to attribute collection
     *
     * @param   Mage_Eav_Model_Mysql4_Entity_Attribute_Collection $collection
     * @return  Mage_Eav_Model_Mysql4_Entity_Attribute_Collection
     */
    protected function _prepareAttributeCollection($collection)
    {
        $collection->addIsFilterableFilter();
        return $collection;
    }

    /**
     * Retrieve layer state object
     *
     * @return Mage_Catalog_Model_Layer_State
     */
    public function getState()
    {
        $state = $this->getData('state');
        if (is_null($state)) {
            Varien_Profiler::start(__METHOD__);
            $state = Mage::getModel('catalog/layer_state');
            $this->setData('state', $state);
            Varien_Profiler::stop(__METHOD__);
        }
        return $state;
    }

    /**
     * Get attribute sets idendifiers of current product set
     *
     * @return array
     */
    protected function _getSetIds()
    {
        $key = $this->getStateKey().'_SET_IDS';
        $setIds = $this->getAggregator()->getCacheData($key);

        if ($setIds === null) {
            $setIds = $this->getProductCollection()->getSetIds();
            $this->getAggregator()->saveCacheData($setIds, $key, $this->getStateTags());
        }
        return $setIds;
    }
}
