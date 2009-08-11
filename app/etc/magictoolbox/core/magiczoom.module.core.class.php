<?php

if(!in_array('MagicZoomModuleCoreClass', get_declared_classes())) {

    require_once(dirname(__FILE__) . '/magictoolbox.params.class.php');

	class MagicZoomModuleCoreClass {
		var $uri;
		var $jsPath;
		var $cssPath;
		var $imgPath;
		var $params;
		
		function MagicZoomModuleCoreClass() {
			$this->params = new MagicToolboxParams();
			$this->_paramDefaults();
		}
		
		function getValue($name) {
			switch($name) {
				case 'name': return 'MagicZoom'; break;
				case 'description': return 'MagicZoom description'; break;
				case 'id': return 'magiczoom'; break;
			}
		}
		
		function headers($jsPath = '', $cssPath = null, $notCheck = false) {
			if($cssPath == null) $cssPath = $jsPath;
			$headers = array();
            $headers[] = '<!-- Magic Zoom Magento module version 3.1.1.11 -->';
			$headers[] = '<script type="text/javascript" src="' . $jsPath . '/mz-packed.js"></script>';
			$headers[] = '<link type="text/css" href="' . $cssPath . '/MagicZoom.css" rel="stylesheet" media="screen" />';
			return implode("\r\n", $headers);
		}
		
        function template($params) {
            extract($params);
            
            if(!isset($alt) || empty($alt)) $alt = '';
            if(!isset($img) || empty($img)) return false;
            if(!isset($thumb) || empty($thumb)) $thumb = $img;
            if(!isset($id) || empty($id)) $id = md5($img);
            if(!isset($title) || empty($title) || $this->params->checkValue('show_caption', 'No')) $title = '';
            else {
                $title = htmlspecialchars($title);
                if(empty($alt)) $alt = $title;
                $title = " title=\"{$title}\"";
            }
            if(!isset($width) || empty($width)) $width = "";
            else $width = " width=\"{$width}\"";
            if(!isset($height) || empty($height)) $height = "";
            else $height = " height=\"{$height}\"";
            if($this->params->checkValue('show_message', 'Yes')) {
                $message = $this->params->getValue('message');
            } else $message = '';

            return "<a class=\"MagicZoom\"{$title} id=\"MagicZoomImage{$id}\" href=\"{$img}\" rel=\"" . $this->getRel() . "\"><img{$width}{$height} src=\"{$thumb}\" alt=\"{$alt}\" /></a>" . $message;
        }
		
        function subTemplate($params) {
            extract($params);
            
            if(!isset($alt) || empty($alt)) $alt = '';
            if(!isset($img) || empty($img)) return false;
            if(!isset($medium) || empty($medium)) $medium = $img;
            if(!isset($thumb) || empty($thumb)) $thumb = $img;
            if(!isset($id) || empty($id)) $id = md5($img);
            if(!isset($title) || empty($title) || $this->params->checkValue('show_caption', 'No')) $title = '';
            else {
                $title = htmlspecialchars($title);
                if(empty($alt)) $alt = $title;
                $title = " title=\"{$title}\"";
            }
            if(!isset($width) || empty($width)) $width = "";
            else $width = " width=\"{$width}\"";
            if(!isset($height) || empty($height)) $height = "";
            else $height = " height=\"{$height}\"";

            return "<a{$title} href=\"{$img}\" rel=\"MagicZoomImage{$id}\" rev=\"{$medium}\"><img{$width}{$height} src=\"{$thumb}\" alt=\"{$alt}\" /></a>";
        }

		function getRel($notCheck = false) {
			$rel = $this->params->getValue('rel');
			if(!$rel || empty($rel)) {
				$rel = Array();
    			$rel[] = "opacity: " . $this->params->getValue('opacity');
    			$rel[] = "zoom-width: " . $this->params->getValue('zoom_width') . 'px';
    			$rel[] = "zoom-height: " . $this->params->getValue('zoom_height') . 'px';
    			$rel[] = "zoom-position: " . $this->params->getValue('zoom_position');
    			$rel[] = "thumb-change: " . $this->params->getValue('thumb_change');
                if($notCheck) {
                    $rel[] = "drag-mode: " . $this->params->getValue('drag_mode');
                    $rel[] = "always-show-zoom: " . $this->params->getValue('always_show_zoom');
                } else {
                    $rel[] = "drag-mode: " . ($this->params->checkValue('drag_mode', 'Yes') ? 'true' : 'false');
                    $rel[] = "always-show-zoom: " . ($this->params->checkValue('always_show_zoom', 'Yes') ? 'true' : 'false');
                }                
    			$rel = implode(';', $rel);
    			$this->params->append('rel', $rel);
			}
			return $rel;
		}
		
		function addonsTemplate($imgPath = '') {
			if ($this->params->getValue("loading_animation") == "Yes"){
				return '<img style="display:none;" class="MagicZoomLoading" src="' . $imgPath . '/' . $this->params->getValue("loading_image") . '" alt="' . $this->params->getValue("loading_text") . '"/>';
            } else return '';
		}
		
		function _paramDefaults() {
			$params = array(
    
        "opacity" => array(
            "id" => "opacity",
            "default" => "50",
            "label" => "Square opacity",
            "type" => "num",
            
        ),
    
        "zoom_width" => array(
            "id" => "zoom_width",
            "default" => "300",
            "label" => "Zoomed area width (in pixels)",
            "type" => "num",
            
        ),
    
        "zoom_height" => array(
            "id" => "zoom_height",
            "default" => "300",
            "label" => "Zoomed area height (in pixels)",
            "type" => "num",
            
        ),
    
        "zoom_position" => array(
            "id" => "zoom_position",
            "default" => "right",
            "label" => "Zoomed area position",
            "type" => "array",
            
            "subType" => "select",
            "values" => array("top","right","bottom","left","inner",),
            
        ),
    
        "thumb_change" => array(
            "id" => "thumb_change",
            "default" => "click",
            "label" => "Thumb change event",
            "type" => "array",
            
            "subType" => "select",
            "values" => array("click","mouseover",),
            
        ),
    
        "show_message" => array(
            "id" => "show_message",
            "default" => "Yes",
            "label" => "Show message under image?",
            "type" => "array",
            
            "subType" => "radio",
            "values" => array("Yes","No",),
            
        ),
    
        "message" => array(
            "id" => "message",
            "default" => "Move your mouse over image",
            "label" => "Message under images",
            "type" => "text",
            
        ),
    
        "show_caption" => array(
            "id" => "show_caption",
            "default" => "Yes",
            "label" => "Show caption in zoomed area?",
            "type" => "array",
            
            "subType" => "radio",
            "values" => array("Yes","No",),
            
        ),
    
        "loading_animation" => array(
            "id" => "loading_animation",
            "default" => "Yes",
            "label" => "Use loading animation?",
            "type" => "array",
            
            "subType" => "radio",
            "values" => array("Yes","No",),
            
        ),
    
        "loading_image" => array(
            "id" => "loading_image",
            "default" => "ajax-loader.gif",
            "label" => "Loading animation image",
            "type" => "text",
            
        ),
    
        "loading_text" => array(
            "id" => "loading_text",
            "default" => "Loading Zoom, please wait",
            "label" => "Loading animation text",
            "type" => "text",
            
        ),
    
        "drag_mode" => array(
            "id" => "drag_mode",
            "default" => "No",
            "label" => "Use drag mode?",
            "type" => "array",
            
            "subType" => "radio",
            "values" => array("Yes","No",),
            
        ),
    
        "always_show_zoom" => array(
            "id" => "always_show_zoom",
            "default" => "No",
            "label" => "Always show zoom?",
            "type" => "array",
            
            "subType" => "radio",
            "values" => array("Yes","No",),
            
        ),
    
        "use_individual_titles" => array(
            "id" => "use_individual_titles",
            "default" => "Yes",
            "label" => "Use individual image titles for additional images?",
            "type" => "array",
            
            "subType" => "radio",
            "values" => array("Yes","No",),
            
        ),
    
        "thumb_size_depends_on" => array(
            "id" => "thumb_size_depends_on",
            "default" => "both",
            "label" => "Size of thumbnail depends on",
            "type" => "array",
            
            "subType" => "select",
            "values" => array("width","height","both",),
            
        ),
    
        "thumb_size" => array(
            "id" => "thumb_size",
            "default" => "250",
            "label" => "Size of thumbnail (in pixels)",
            "type" => "num",
            
        ),
    
        "selector_size" => array(
            "id" => "selector_size",
            "default" => "150",
            "label" => "Size of additional thumbnails (in pixels)",
            "type" => "num",
            
        ),
    
        "use_effect_on_product_page" => array(
            "id" => "use_effect_on_product_page",
            "default" => "Yes",
            "label" => "Use effect on product page",
            "type" => "array",
            
            "subType" => "select",
            "values" => array("Yes","No",),
            
        ),
    
        "use_effect_on_category_page" => array(
            "id" => "use_effect_on_category_page",
            "default" => "No",
            "label" => "Use effect on category page",
            "type" => "array",
            
            "subType" => "select",
            "values" => array("Yes","No",),
            
        ),
    
        "show_selectors_on_category_page" => array(
            "id" => "show_selectors_on_category_page",
            "default" => "No",
            "label" => "Show selectors on category page",
            "type" => "array",
            
            "subType" => "select",
            "values" => array("Yes","No",),
            
        ),
    
        "ignore_magento_css" => array(
            "id" => "ignore_magento_css",
            "default" => "No",
            "label" => "Ignore magento CSS width/height styles for additional images",
            "type" => "array",
            
            "subType" => "radio",
            "values" => array("Yes","No",),
            
        ),
    
        "option_associated_with_images" => array(
            "id" => "option_associated_with_images",
            "default" => "color",
            "label" => "Name of option associated with images (e.g 'Color')",
            "type" => "text",
            
            "description" => "You should named all product images associated with option values. e.g If option values is 'red', 'blue' and 'white' then you should have 3 images with titles 'red', 'blue' and 'white'",
            
        ),
    
        "square_images" => array(
            "id" => "square_images",
            "default" => "No",
            "label" => "Allways create square thumbnails",
            "type" => "array",
            
            "subType" => "radio",
            "values" => array("Yes","No",),
            
        ),
    
);
			$this->params->appendArray($params);
		}
	}

}
?>