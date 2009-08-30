<?php

class Coolision_News_Block_Adminhtml_News_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
    parent::__construct();
    $this->setId('newsGrid');
    // This is the primary key of the database
    $this->setDefaultSort('news_id');
    $this->setDefaultDir('ASC');
    $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
    $collection = Mage::getModel('news/news')->getCollection();
    $this->setCollection($collection);
    return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
    $this->addColumn('news_id', array(
			'header'    => Mage::helper('news')->__('ID'),
			'align'     => 'right',
			'width'     => '50px',
			'index'     => 'news_id',
    ));

    $this->addColumn('title', array(
			'header'    => Mage::helper('news')->__('Title'),
			'align'     => 'left',
			'index'     => 'title',
    ));

    /*
    $this->addColumn('content', array(
    'header'    => Mage::helper('<module>')->__('Item Content'),
    'width'     => '150px',
    'index'     => 'content',
    ));
    */

    $this->addColumn('created_time', array(
			'header'    => Mage::helper('news')->__('Creation Time'),
			'align'     => 'left',
			'width'     => '120px',
			'type'      => 'date',
			'default'   => '--',
			'index'     => 'created_time',
    ));

//     $this->addColumn('update_time', array(
//     'header'    => Mage::helper('<module>')->__('Update Time'),
//     'align'     => 'left',
//     'width'     => '120px',
//     'type'      => 'date',
//     'default'   => '--',
//     'index'     => 'update_time',
//     ));   

    $this->addColumn('status', array(
			'header'    => Mage::helper('news')->__('Status'),
			'align'     => 'left',
			'width'     => '80px',
			'index'     => 'status',
			'type'      => 'options',
			'options'   => array(
					1 => 'Active',
					0 => 'Inactive',
			),
    ));
    return parent::_prepareColumns();
  }

  public function getRowUrl($row)
  {
    return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }
}