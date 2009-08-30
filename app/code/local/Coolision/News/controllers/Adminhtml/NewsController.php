<?php

class Coolision_News_Adminhtml_NewsController extends Mage_Adminhtml_Controller_action
{
  protected function _initAction()
  {
    $this->loadLayout()
    ->_setActiveMenu('news/items')
    ->_addBreadcrumb(Mage::helper('adminhtml')->__('News Manager'), Mage::helper('adminhtml')->__('News Manager'));
    return $this;
  }
   
  public function indexAction() {
    $this->_initAction();       
//     $this->_addContent($this->getLayout()->createBlock('news/adminhtml_news'));
    $this->renderLayout();
  }

  public function editAction()
  {
    $newsId     = $this->getRequest()->getParam('id');
    $newsModel  = Mage::getModel('news/news')->load($newsId);
    if ($newsModel->getId() || $newsId == 0) {
      Mage::register('news_data', $newsModel);
      $this->loadLayout();
      $this->_setActiveMenu('news/items');
      $this->_addBreadcrumb(Mage::helper('adminhtml')->__('News Manager'), Mage::helper('adminhtml')->__('News Manager'));
      $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));
      $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
      $this->_addContent($this->getLayout()->createBlock('news/adminhtml_news_edit'))
	      ->_addLeft($this->getLayout()->createBlock('news/adminhtml_news_edit_tabs'));
      $this->renderLayout();
    } else {
      Mage::getSingleton('adminhtml/session')->addError(Mage::helper('news')->__('News does not exist'));
      $this->_redirect('*/*/');
    }
  }

  public function newAction()
  {
    $this->_forward('edit');
  }

  public function saveAction()
  {
    if ( $this->getRequest()->getPost() ) {
    try {
      $postData = $this->getRequest()->getPost();
      $newsModel = Mage::getModel('news/news');
      $newsModel->setId($this->getRequest()->getParam('id'))
	->setTitle($postData['title'])
	->setContent($postData['content'])
	->setStatus($postData['status'])
	->setCreatedTime(Mage::getSingleton('core/date')->gmtDate())
	->save();
      Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('News was successfully saved'));
      Mage::getSingleton('adminhtml/session')->setNewsData(false);
      $this->_redirect('*/*/');
      return;
    } catch (Exception $e) {
      Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
      Mage::getSingleton('adminhtml/session')->setNewsData($this->getRequest()->getPost());
      $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
      return;
    }
    }
    $this->_redirect('*/*/');
  }

  public function deleteAction()
  {
    if( $this->getRequest()->getParam('id') > 0 ) {
    try {
      $newsModel = Mage::getModel('news/news');
      $newsModel->setId($this->getRequest()->getParam('id'))
	->delete();
      Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('News was successfully deleted'));
      $this->_redirect('*/*/');
    } catch (Exception $e) {
      Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
      $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
    }
    }
    $this->_redirect('*/*/');
  }
}