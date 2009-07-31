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
 * @package    Mage_Core
 * @copyright  Copyright (c) 2008 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * URL
 *
 * Properties:
 *
 * - request
 *
 * - relative_url: true, false
 * - type: 'link', 'skin', 'js', 'media'
 * - store: instanceof Mage_Core_Model_Store
 * - secure: true, false
 *
 * - scheme: 'http', 'https'
 * - user: 'user'
 * - password: 'password'
 * - host: 'localhost'
 * - port: 80, 443
 * - base_path: '/dev/magento/'
 * - base_script: 'index.php'
 *
 * - storeview_path: 'storeview/'
 * - route_path: 'module/controller/action/param1/value1/param2/value2'
 * - route_name: 'module'
 * - controller_name: 'controller'
 * - action_name: 'action'
 * - route_params: array('param1'=>'value1', 'param2'=>'value2')
 *
 * - query: (?)'param1=value1&param2=value2'
 * - query_array: array('param1'=>'value1', 'param2'=>'value2')
 * - fragment: (#)'fragment-anchor'
 *
 * URL structure:
 *
 * https://user:password@host:443/base_path/[base_script][storeview_path]route_name/controller_name/action_name/param1/value1?query_param=query_value#fragment
 *       \__________A___________/\____________________________________B_____________________________________/
 * \__________________C___________________/              \__________________D_________________/ \_____E_____/
 * \_____________F______________/                        \___________________________G______________________/
 * \___________________________________________________H____________________________________________________/
 *
 * - A: authority
 * - B: path
 * - C: absolute_base_url
 * - D: action_path
 * - E: route_params
 * - F: host_url
 * - G: route_path
 * - H: route_url
 *
 * @category   Mage
 * @package    Mage_Core
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Coolision_Core_Model_Url extends Mage_Core_Model_Url
{

    public function setQueryParams(array $data, $useCurrent = false, $isRem = false)
    {
	
        $this->unsetData('query');
        if ($useCurrent) {
		
            $params = $this->_getData('query_params');
			if($isRem){
				
				foreach ($data as $param => $value) {
					if(isset($params[$param])){
						$tmpArr=explode("-",$params[$param]);
						if(is_array($tmpArr)){
							$params[$param]="";
							foreach($tmpArr as $el){
								if($el!==$value)
									$params[$param]=$params[$param]."-".$el;
							}
							$params[$param]=substr($params[$param],1);
						}
						else
							unset($params[$param]);
					}
				}
				
			}
			
			else{
	            foreach ($data as $param => $value) {
											
					if($param!="order" && $param!="dir" && $param!="p" && $param!="mode" && $param!="limit" && isset($params[$param])){
						$compArr=explode("-",$params[$param]);
						$justAdded=false;

						//Verify if value has already been selected
						if(is_array($compArr)){
							foreach($compArr as $el){
								if($el===$value)
									$justAdded=true;
							}
						}
						else
							if($compArr===$value)
								$justAdded=true;
								
						if(!$justAdded)
							$params[$param] = $params[$param]."-".$value;
							
						}
						
					else
						$params[$param] = $value;
				}
			}
            $this->setData('query_params', $params);
            return $this;
        }

        if ($this->_getData('query_params')==$data) {
            return $this;
        }
        return $this->setData('query_params', $data);
    }

    /**
     * Build url by requested path and parameters
     *
     * @param   string $routePath
     * @param   array $routeParams
     * @return  string
     */
    public function getUrl($routePath=null, $routeParams=null)
    {
        $escapeQuery = false;

        /**
         * All system params should be unseted before we call getRouteUrl
         * this method has condition for ading default controller anr actions names
         * in case when we have params
         */
        if (isset($routeParams['_fragment'])) {
            $this->setFragment($routeParams['_fragment']);
            unset($routeParams['_fragment']);
        }

        if (isset($routeParams['_escape'])) {
            $escapeQuery = $routeParams['_escape'];
            unset($routeParams['_escape']);
        }

        $query = null;
        if (isset($routeParams['_query'])) {
            $query = $routeParams['_query'];
            unset($routeParams['_query']);
        }

        $noSid = null;
        if (isset($routeParams['_nosid'])) {
            $noSid = (bool)$routeParams['_nosid'];
            unset($routeParams['_nosid']);
        }
        $url = $this->getRouteUrl($routePath, $routeParams);
        /**
         * Apply query params, need call after getRouteUrl for rewrite _current values
         */
        if ($query !== null) {
            if (is_string($query)) {
                $this->setQuery($query);
            } elseif (is_array($query)) {
				if(isset($routeParams['_isRem']))
					$this->setQueryParams($query, !empty($routeParams['_current']), $routeParams['_isRem']);
				else 
					$this->setQueryParams($query, !empty($routeParams['_current']), false);
            }
            if ($query === false) {
                $this->setQueryParams(array());
            }
        }
		
        if ($noSid !== true) {
            $this->_prepareSessionUrl($url);
        }

        if ($query = $this->getQuery($escapeQuery)) {
            $url .= '?'.$query;
        }

        if ($this->getFragment()) {
            $url .= '#'.$this->getFragment();
        }

        return $this->escape($url);
    }

}