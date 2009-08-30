<?php
class Coolision_News_Model_News extends Mage_Core_Model_Abstract
{
  public function _construct()
  {
    parent::_construct();
    $this->_init('news/news');
  }
}