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
 * @package    Mage_Wishlist
 * @copyright  Copyright (c) 2008 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


/**
 * Links block
 *
 * @category    Mage
 * @package     Mage_Wishlist
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Wishlist_Block_Links extends Mage_Core_Block_Template
{
    /**
     * Add link on wishlist page in parent block
     *
     * @return Mage_Wishlist_Block_Links
     */
    public function addWishlistLink()
    {
        $parentBlock = $this->getParentBlock();
        if ($parentBlock && $this->helper('wishlist')->isAllow()) {
            $count = $this->helper('wishlist')->getItemCount();
            if( $count > 1 ) {
                $text = $this->__('wishlist (%d items)', $count);
            } elseif( $count == 1 ) {
                $text = $this->__('wishlist (%d item)', $count);
            } else {
                $text = $this->__('wishlist');
            }
            $parentBlock->addLink($text, 'wishlist', $text, true, array(), 30, null, 'class="top-link-wishlist"');
        }
        return $this;
    }
}
