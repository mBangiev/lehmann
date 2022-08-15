<?php
/**
 * Sbs_Scroll2Top
 * @author    silbersaiten <info@silbersaiten.de>
 * @copyright 2021 silbersaiten
 * @license   See joined file licence.txt
 * @link      http://www.silbersaiten.de
 * @support   silbersaiten <support@silbersaiten.de>
 * @category  Module
 * @version   1.0.3
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class Sbs_Scroll2Top extends Module
{
    private $ps_version = 1.7;

    public function __construct()
    {
        $this->name = 'sbs_scroll2top';
        $this->tab = 'front_office_features';
        $this->version = '1.0.3';
        $this->author = 'silbersaiten';
        $this->need_instance = 0;
        $this->ps_version = substr(_PS_VERSION_, 0, 3);

        parent::__construct();

        $this->displayName = $this->l('Displays scroll button to top.');
        $this->description = $this->l('Displays scroll button to top on bottom of page.');
    }

    public function install()
    {
        if (!parent::install() || !$this->registerHook('displayFooter') || !$this->registerHook('header'))
            return false;
        return true;
    }


    public function hookDisplayHeader($params)
    {
        $this->hookHeader($params);
    }

    public function hookHeader($params)
    {
        unset($params);
        if (version_compare(_PS_VERSION_, '1.7.0.0', '>=')) {
            $this->context->controller->registerJavascript('modules-sbs_scroll2top', 'modules/' . $this->name . '/views/js/sbs_scroll2top.js', ['position' => 'bottom', 'priority' => 150]);
            $this->context->controller->registerStylesheet('modules-sbs_scroll2top', 'modules/' . $this->name . '/views/css/sbs_scroll2top.css', ['position' => 'bottom', 'priority' => 150]);
        } else {
            $this->context->controller->addCSS(($this->_path) . 'views/css/sbs_scroll2top.css', 'all');
            $this->context->controller->addJS(($this->_path) . 'views/js/sbs_scroll2top.js', 'all');
        }
    }

    public function hookDisplayFooter($params)
    {
        unset($params);
        return $this->display(__FILE__, 'views/templates/hook/sbs_scroll2top_' . $this->ps_version . '.tpl');
    }
}
