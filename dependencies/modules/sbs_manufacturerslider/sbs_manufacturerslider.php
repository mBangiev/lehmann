<?php
/**
 * Sbs_ManufacturersSlider
 *
 * @author    silbersaiten <info@silbersaiten.de>
 * @copyright 2022 silbersaiten
 * @license   See joined file licence.txt
 * @link      https://www.silbersaiten.de
 * @support   silbersaiten <support@silbersaiten.de>
 * @category  Module
 * @version   1.0.9
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;

class Sbs_ManufacturerSlider extends Module implements WidgetInterface
{
    protected $_template_file;
    private $hook_module = array(
        'actionObjectManufacturerUpdateAfter',
        'actionObjectManufacturerAddAfter',
        'actionObjectManufacturerDeleteAfter',
        'displayHeader',
        'displayHome',
    );

    public $tabs = array(
        array(
            'name' => 'Manufacturer slider', // One name for all langs
            'class_name' => 'AdminManufacturerSliderConfig',
            'visible' => true,
            'parent_class_name' => 'SBS_THEME',
        )
    );

    public function __construct()
    {
        $this->name = 'sbs_manufacturerslider';
        $this->tab = 'front_office_features';
        $this->version = '1.0.9';
        $this->author = 'silbersaiten';
        $this->need_instance = 0;
        $this->bootstrap = true;
        parent::__construct();
        $this->displayName = $this->l('Manufacturer slider');
        $this->description = $this->l('Display Manufacturer logo slider on homepage.');
        $this->ps_versions_compliancy = array('min' => '1.7.0.0', 'max' => _PS_VERSION_);
        $this->_template_file = 'module:sbs_manufacturerslider/views/templates/hook/sbs_manufacturerslider.tpl';
    }

    public function install()
    {
        /** Before install */
        $this->createSbsMenu();
        Configuration::updateValue('SBS_MANUFACTURER_SLIDER_SHOW_NAMES', false);
        Configuration::updateValue('SBS_MANUFACTURER_SLIDER_AUTOPLAY', false);
        Configuration::updateValue('SBS_MANUFACTURER_SLIDER_AUTOPLAY_SPEED', 5000);
        Configuration::updateValue('SBS_MANUFACTURER_SLIDER_MARGIN', 1);
        Configuration::updateValue('SBS_MANUFACTURER_SLIDER_LOOP', true);
        Configuration::updateValue('SBS_MANUFACTURER_SLIDER_ITEMS_DESKTOP', 4);
        Configuration::updateValue('SBS_MANUFACTURER_SLIDER_ITEMS_MOBILE', 1);
        Configuration::updateValue('SBS_MANUFACTURER_SLIDER_NAV', true);
        Configuration::updateValue('SBS_MANUFACTURER_SLIDER_DOTS', true);
        Configuration::updateValue('SBS_MANUFACTURER_SLIDER_RANDOM_COUNT', 10);
        Configuration::updateValue('SBS_MANUFACTURER_SLIDER_DISABLE_OWL_LIBRARY', false);

        return parent::install() &&
            $this->installTab() &&
            $this->registerHook($this->hook_module);
    }

    public function uninstall()
    {
        return parent::uninstall() &&
            Configuration::deleteByName('SBS_MANUFACTURER_SLIDER_SHOW_NAMES') &&
            Configuration::deleteByName('SBS_MANUFACTURER_SLIDER_AUTOPLAY') &&
            Configuration::deleteByName('SBS_MANUFACTURER_SLIDER_AUTOPLAY_SPEED') &&
            Configuration::deleteByName('SBS_MANUFACTURER_SLIDER_MARGIN') &&
            Configuration::deleteByName('SBS_MANUFACTURER_SLIDER_LOOP') &&
            Configuration::deleteByName('SBS_MANUFACTURER_SLIDER_ITEMS_DESKTOP') &&
            Configuration::deleteByName('SBS_MANUFACTURER_SLIDER_ITEMS_MOBILE') &&
            Configuration::deleteByName('SBS_MANUFACTURER_SLIDER_NAV') &&
            Configuration::deleteByName('SBS_MANUFACTURER_SLIDER_DOTS') &&
            Configuration::deleteByName('SBS_MANUFACTURER_SLIDER_RANDOM_COUNT') &&
            Configuration::deleteByName('SBS_MANUFACTURER_SLIDER_DISABLE_OWL_LIBRARY') &&
            $this->uninstallTab() &&
            $this->uninstallSbsCommonTab();
    }

    private function installTab()
    {
        $tabId = (int)Tab::getIdFromClassName('AdminManufacturerSliderConfig');
        if (!$tabId) {
            $tabId = null;
        }

        $tab = new Tab($tabId);
        $tab->active = 1;
        $tab->class_name = 'AdminManufacturerSliderConfig';
        $tab->name = array();
        foreach (Language::getLanguages() as $lang) {
            $tab->name[$lang['id_lang']] = $this->l('Manufacturer slider');
        }

        if ($id_parent = $this->getSbsMenuId()) {
            $tab->id_parent = $id_parent;
        } else {
            $this->createSbsMenu();
            $tab->id_parent = $this->getSbsMenuId();
        }

        $tab->module = $this->name;
        return $tab->add();
    }

    private function uninstallTab()
    {
        $tabId = (int)Tab::getIdFromClassName('AdminManufacturerSliderConfig');
        if (!$tabId) {
            return true;
        }
        $tab = new Tab($tabId);
        return $tab->delete();
    }

    private function getSbsMenuId()
    {
        $config_id = $this->getIdCONFIGURATIONTab();
        return (int)Db::getInstance()->getValue('SELECT `id_tab` FROM `' . _DB_PREFIX_ . 'tab` WHERE `id_parent`=' . $config_id . ' AND `icon`="important_devices"');
    }

    private function createSbsMenu()
    {
        if (!$this->getSbsMenuId()) {
            $id_parent = $this->getIdCONFIGURATIONTab();

            $tab = new Tab();
            $tab->active = 1;
            $tab->class_name = 'SBSTHEME'; // The name matters for roles, NOT CamelCase `SBSTHEME` = `SBS theme`
            $tab->module = $this->name;
            $tab->icon = 'important_devices';
            $tab->name = array();
            foreach (Language::getLanguages() as $lang) {
                $tab->name[$lang['id_lang']] = 'SBS theme'; // The name matters for roles, NOT CamelCase
            }
            $tab->id_parent = $id_parent;
            $tab->position = $this->getTabNextPosition($id_parent);
            if (version_compare(_PS_VERSION_, '1.7.7.0', '>=')) {
                $tab->enabled = 1;
            }
            return $tab->add();
        }
    }

    private function getTabNextPosition($id_parent)
    {
        return (int)Db::getInstance()->getValue('SELECT COUNT(*) FROM `' . _DB_PREFIX_ . 'tab` WHERE `id_parent`=' . $id_parent);
    }

    private function getIdCONFIGURATIONTab()
    {
        return (int)Db::getInstance()->getValue('SELECT `id_tab` FROM `' . _DB_PREFIX_ . 'tab` WHERE `id_parent`=0 AND `position`=3');
    }

    private function uninstallSbsCommonTab()
    {
        $sbs_menu_id = $this->getSbsMenuId();
        $menu_exist = (int)Db::getInstance()->getValue('SELECT COUNT(*) FROM `' . _DB_PREFIX_ . 'tab` WHERE `id_parent`=' . $sbs_menu_id . ' OR id_tab=' . $sbs_menu_id);
        if ($menu_exist === 1) {
            $tab = new Tab($sbs_menu_id);
            return $tab->delete();
        } else {
            return true;
        }
    }

    public function clearTplCache($template, $cache_id = null, $compile_id = null)
    {
        return $this->_clearCache($template, $cache_id, $compile_id);
    }

    public function hookActionObjectManufacturerUpdateAfter()
    {
        $this->_clearCache('*');
    }

    public function hookActionObjectManufacturerAddAfter()
    {
        $this->_clearCache('*');
    }

    public function hookActionObjectManufacturerDeleteAfter()
    {
        $this->_clearCache('*');
    }

    protected function getCacheId($hookName = null)
    {
        return parent::getCacheId() . '|' . $hookName;
    }

    public function getContent()
    {
        Tools::redirectAdmin($this->context->link->getAdminLink('AdminManufacturerSliderConfig'));
    }

    public function getWidgetVariables($hookName, array $configuration)
    {
        $brands = Manufacturer::getManufacturers(false, (int)Context::getContext()->language->id);
        $brand_list = Configuration::get('SBS_MANUFACTURER_IDS');

        if ($brand_list) {
            $brand_list = explode(',', $brand_list);
        } else {
            $brand_list = [];
        }

        foreach ($brands as $key => &$brand) {
            if (!in_array($brand['id_manufacturer'], $brand_list)) {
                unset($brands[$key]);
            }
        }
        unset($brand);

        $random_count = (int)Configuration::get('SBS_MANUFACTURER_SLIDER_RANDOM_COUNT');
        $random_count = $random_count >= count($brands) ? count($brands) : $random_count;

        if ($random_count == 0) {
            $random_keys = $brands;
        } elseif ($random_count == 1) {
            $random_keys = array(array_rand($brands, $random_count));
        } else {
            $random_keys = array_rand($brands, $random_count);
        }

        foreach ($brands as $key => &$brand) {
            if ($random_count && !in_array($key, $random_keys)) {
                unset($brands[$key]);
                continue;
            }
            $brand['image'] = $this->context->link->getManufacturerImageLink($brand['id_manufacturer']);
            $brand['link'] = $this->context->link->getManufacturerLink($brand);
            $file = _PS_MANU_IMG_DIR_ . $brand['id_manufacturer'] . '-' . ImageType::getFormattedName('medium') . '.jpg';
            $icon = _THEME_MANU_DIR_ . $brand['id_manufacturer'] . '-' . ImageType::getFormattedName('medium') . '.jpg';

            if (file_exists($file)) {
                $brand['image'] = $icon;
            }
        }
        unset($brand);

        return array(
            'brands' => $brands,
            'page_link' => $this->context->link->getPageLink('manufacturer'),
            'show_names' => Configuration::get('SBS_MANUFACTURER_SLIDER_SHOW_NAMES'),
            'display_link_brand' => Configuration::get('PS_DISPLAY_SUPPLIERS'),
        );
    }

    public function renderWidget($hookName, array $configuration)
    {
        $cacheId = $this->getCacheId('sbs_manufacturerslider');
        $random_count = (int)Configuration::get('SBS_MANUFACTURER_SLIDER_RANDOM_COUNT');
        if ($random_count) {
            $this->smarty->assign($this->getWidgetVariables($hookName, $configuration));
        } else {
            $isCached = $this->isCached($this->_template_file, $cacheId);
            if (!$isCached) {
                $this->smarty->assign($this->getWidgetVariables($hookName, $configuration));
            }
        }
        return $this->fetch($this->_template_file, $cacheId);
    }

    public function hookDisplayHeader()
    {
        if ($this->context->isMobile()) {
            $item_count = (int)Configuration::get('SBS_MANUFACTURER_SLIDER_ITEMS_MOBILE');
        } else {
            $item_count = (int)Configuration::get('SBS_MANUFACTURER_SLIDER_ITEMS_DESKTOP');
        }

        Media::addJsDef(array(
            'SBS_MANUFACTURER_SLIDER_AUTOPLAY' => (bool)Configuration::get('SBS_MANUFACTURER_SLIDER_AUTOPLAY'),
            'SBS_MANUFACTURER_SLIDER_AUTOPLAY_SPEED' => (int)Configuration::get('SBS_MANUFACTURER_SLIDER_AUTOPLAY_SPEED'),
            'SBS_MANUFACTURER_SLIDER_MARGIN' => (int)Configuration::get('SBS_MANUFACTURER_SLIDER_MARGIN'),
            'SBS_MANUFACTURER_SLIDER_LOOP' => (bool)Configuration::get('SBS_MANUFACTURER_SLIDER_LOOP'),
            'SBS_MANUFACTURER_SLIDER_ITEMS' => $item_count,
            'SBS_MANUFACTURER_SLIDER_NAV' => (bool)Configuration::get('SBS_MANUFACTURER_SLIDER_NAV'),
            'SBS_MANUFACTURER_SLIDER_DOTS' => (bool)Configuration::get('SBS_MANUFACTURER_SLIDER_DOTS'),
        ));

        $this->context->controller->registerJavascript('modules-sbs_manufacturerslider_script', 'modules/' . $this->name . '/views/js/sbs_manufacturerslider.js', ['position' => 'bottom', 'priority' => 150]);
        $this->context->controller->registerStylesheet('modules-sbs_manufacturerslider_style', 'modules/' . $this->name . '/views/css/sbs_manufacturerslider.css', ['position' => 'bottom', 'priority' => 150]);
        if (!(bool)Configuration::get('SBS_MANUFACTURER_SLIDER_DISABLE_OWL_LIBRARY')) {
            $this->loadOwlPack();
        }
    }

    private function loadOwlPack()
    {
        $this->context->controller->registerJavascript(
            'module_' . $this->name . '_owl_slider-pack',
            'modules/' . $this->name . '/views/lib/owl/owl.carousel.min.js',
            ['position' => 'bottom', 'priority' => 150]
        );
        $this->context->controller->registerStylesheet(
            'module_' . $this->name . '_owl_animate_functions',
            'modules/' . $this->name . '/views/lib/owl/animate.css',
            ['media' => 'all', 'priority' => 150]
        );
        $this->context->controller->registerStylesheet(
            'module_' . $this->name . '_owl_slider_default',
            'modules/' . $this->name . '/views/lib/owl/owl.theme.default.min.css',
            ['media' => 'all', 'priority' => 150]
        );
        $this->context->controller->registerStylesheet(
            'module_' . $this->name . '_owl_slider',
            'modules/' . $this->name . '/views/lib/owl/owl.carousel.min.css',
            ['media' => 'all', 'priority' => 150]
        );
        $this->context->controller->registerStylesheet(
            'module_' . $this->name . '_owl_slider_style',
            'modules/' . $this->name . '/views/lib/owl/owl_slider.css',
            ['media' => 'all', 'priority' => 150]
        );
    }
}
