<?php

    require_once(BP . DS . 'app' . DS . 'etc' . DS . 'magictoolbox' . DS . 'core' . DS . 'magiczoom.module.core.class.php');

    require_once(BP . DS . 'app' . DS . 'etc' . DS . 'magictoolbox' . DS . 'core' . DS . 'magiczoom.module.core.class.php');
    $tool = new MagicZoomModuleCoreClass();
        
    // allow to use different ini files for different themes
    // get ini file from current theme folder by default
    $url = preg_replace('/^.*?skin(\/frontend\/default\/default\/)$/is', '$1', $this->getSkinUrl(''));
    $iniFile = BP . DS . 'app' . DS . 'design' . str_replace('/', DS, $url) . 'magiczoom.settings.ini';
    if(!file_exists($iniFile)) {
        // if we can't found ini file for current theme we should get default ini file
        $iniFile = BP . DS . 'app' . DS . 'etc' . DS . 'magictoolbox' . DS . 'magiczoom.settings.ini';
    }
    // load INI
    $tool->params->loadINI($iniFile);
    
    /* load locale */

    $mz_lt = $this->__('MagicZoom_LoadingText');
    if($mz_lt != 'MagicZoom_LoadingText') {
        $tool->params->set('loading_text', $mz_lt);
    }

    $mz_m = $this->__('MagicZoom_Message');
    if($mz_m != 'MagicZoom_Message') {
        $tool->params->set('message', $mz_m);
    }
    

    
    $GLOBALS["magictoolbox"]["magiczoom"] = & $tool;
    
    echo $tool->headers($this->getSkinUrl('js'), $this->getSkinUrl('css'));
    echo '<script type="text/javascript" src="' . $this->getSkinUrl('js') . '/magictoolbox_utils.js"></script>';
    
    $f = 'function(){MagicToolbox_findOption(\'' . $tool->params->getValue('option_associated_with_images') . '\');}';
    echo '<script type="text/javascript">

                MagicZoom_addEventListener(window, \'load\', ' . $f . ');


            </script>';
    echo $tool->addonsTemplate($this->getSkinUrl('images'));
    
    require_once(BP . DS . 'app' . DS . 'etc' . DS . 'magictoolbox' . DS . 'core' . DS . 'addons.php');

?>