<?php

class Coolision_News_Block_Adminhtml_News_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
    $form = new Varien_Data_Form();
    $this->setForm($form);
    $fieldset = $form->addFieldset('news_form', array('legend'=>Mage::helper('news')->__('News information')));
    $fieldset->addField('title', 'text', array(
					  'label'     => Mage::helper('news')->__('Title'),
					  'class'     => 'required-entry',
					  'required'  => true,
					  'name'      => 'title',
    ));

    $fieldset->addField('status', 'select', array(
					      'label'     => Mage::helper('news')->__('Status'),
					      'name'      => 'status',
					      'values'    => array(
							    array(
							    'value'     => 1,
							    'label'     => Mage::helper('news')->__('Active'),
							    ),
							    array(
							    'value'     => 0,
							    'label'     => Mage::helper('news')->__('Inactive'),
					      ),
					      ),
    ));

    $fieldset->addField('content', 'editor', array(
					      'name'      => 'content',
					      'label'     => Mage::helper('news')->__('Content'),
					      'title'     => Mage::helper('news')->__('Content'),
					      'style'     => 'width:98%; height:400px;',
					      'wysiwyg'   => false,
					      'required'  => true,
    ));

    if ( Mage::getSingleton('adminhtml/session')->getNewsData() )
    {
      $form->setValues(Mage::getSingleton('adminhtml/session')->getNewsData());
      Mage::getSingleton('adminhtml/session')->setNewsData(null);
    } elseif ( Mage::registry('news_data') ) {
      $form->setValues(Mage::registry('news_data')->getData());
    }
    return parent::_prepareForm();
  }
}