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
 * @version   1.0.8
 */

class AdminManufacturerSliderConfigController extends ModuleAdminController
{
    public function __construct()
    {
        $this->context = Context::getContext();
        $this->bootstrap = true;
        parent::__construct();
    }

    public function initContent()
    {
        $this->initToolbar();
        $this->initPageHeaderToolbar();
        $this->content .= $this->renderForm();
        $this->context->smarty->assign(array(
            'content' => $this->content,
            'show_page_header_toolbar' => $this->show_page_header_toolbar,
            'page_header_toolbar_title' => $this->page_header_toolbar_title,
            'page_header_toolbar_btn' => $this->page_header_toolbar_btn
        ));
    }

    public function displayManufacturerForm()
    {
        $list = Configuration::get('SBS_MANUFACTURER_IDS');

        if ($list) {
            $list = explode(',', $list);
        } else {
            $list = [];
        }

        $this->context->smarty->assign(array(
            'manufacturer_list' => Manufacturer::getManufacturers(false, (int)Context::getContext()->language->id),
            'selected_manufacturer' => $list
        ));

        return $this->context->smarty->fetch('module:sbs_manufacturerslider/views/templates/admin/manufacturers.tpl');
    }

    public function postProcess()
    {
        if (Tools::isSubmit('submitSbsManufacturerSliderConfig')) {
            $errors = array();
            $show_names = Tools::getValue('SBS_MANUFACTURER_SLIDER_SHOW_NAMES');
            if (!Validate::isBool($show_names)) {
                $errors[] = $this->l('The show names of products is invalid');
            }

            $autoplay = Tools::getValue('SBS_MANUFACTURER_SLIDER_AUTOPLAY');
            if (!Validate::isBool($autoplay)) {
                $errors[] = $this->l('Invalid value for the "autoplay" flag.');
            }

            $autoplay_speed = Tools::getValue('SBS_MANUFACTURER_SLIDER_AUTOPLAY_SPEED');
            if (!Validate::isInt($autoplay_speed) || $autoplay_speed <= 0) {
                $errors[] = $this->l('Autoplay speed is invalid');
            }

            $margin = Tools::getValue('SBS_MANUFACTURER_SLIDER_MARGIN');
            if (!Validate::isInt($margin) || $margin <= 0) {
                $errors[] = $this->l('Margin is invalid');
            }

            $loop = Tools::getValue('SBS_MANUFACTURER_SLIDER_LOOP');
            if (!Validate::isBool($loop)) {
                $errors[] = $this->l('Invalid value for the loop flag');
            }

            $items_desktop = Tools::getValue('SBS_MANUFACTURER_SLIDER_ITEMS_DESKTOP');
            if (!Validate::isInt($items_desktop) || $items_desktop <= 0) {
                $errors[] = $this->l('Desktop items is invalid');
            }

            $items_mobile = Tools::getValue('SBS_MANUFACTURER_SLIDER_ITEMS_MOBILE');
            if (!Validate::isInt($items_mobile) || $items_mobile <= 0) {
                $errors[] = $this->l('Mobile items is invalid');
            }

            $brands = Manufacturer::getManufacturers(false, (int)Context::getContext()->language->id);
            $random_count = (int)Tools::getValue('SBS_MANUFACTURER_SLIDER_RANDOM_COUNT');
            $random_count = ($random_count > count($brands)) ? count($brands) : $random_count;

            if (!Validate::isInt($random_count)) {
                $errors[] = $this->l('Random count is invalid');
            }

            $slider_nav = Tools::getValue('SBS_MANUFACTURER_SLIDER_NAV');
            if (!Validate::isBool($slider_nav)) {
                $errors[] = $this->l('Invalid value for the navigation flag');
            }

            $slider_dots = Tools::getValue('SBS_MANUFACTURER_SLIDER_DOTS');
            if (!Validate::isBool($slider_dots)) {
                $errors[] = $this->l('Invalid value for the navigation dots flag');
            }

            $slider_wol_lib = Tools::getValue('SBS_MANUFACTURER_SLIDER_DISABLE_OWL_LIBRARY');
            if (!Validate::isBool($slider_wol_lib)) {
                $errors[] = $this->l('Invalid value for the loading owl library');
            }

            $list_of_manufacturer = Tools::getValue('SBS_MANUFACTURER_IDS');
            if ($list_of_manufacturer) {
                $list_of_manufacturer = implode(',', $list_of_manufacturer);
            }

            if (isset($errors) && count($errors)) {
                $this->errors[] = (implode('<br />', $errors));
            } else {
                Configuration::updateValue('SBS_MANUFACTURER_SLIDER_SHOW_NAMES', (bool)$show_names);
                Configuration::updateValue('SBS_MANUFACTURER_SLIDER_AUTOPLAY', (bool)$autoplay);
                Configuration::updateValue('SBS_MANUFACTURER_SLIDER_AUTOPLAY_SPEED', (int)$autoplay_speed);
                Configuration::updateValue('SBS_MANUFACTURER_SLIDER_MARGIN', (int)$margin);
                Configuration::updateValue('SBS_MANUFACTURER_SLIDER_LOOP', (bool)$loop);
                Configuration::updateValue('SBS_MANUFACTURER_SLIDER_ITEMS_DESKTOP', (int)$items_desktop);
                Configuration::updateValue('SBS_MANUFACTURER_SLIDER_ITEMS_MOBILE', (int)$items_mobile);
                Configuration::updateValue('SBS_MANUFACTURER_SLIDER_NAV', (bool)$slider_nav);
                Configuration::updateValue('SBS_MANUFACTURER_SLIDER_DOTS', (bool)$slider_dots);
                Configuration::updateValue('SBS_MANUFACTURER_SLIDER_RANDOM_COUNT', (int)$random_count);
                Configuration::updateValue('SBS_MANUFACTURER_SLIDER_DISABLE_OWL_LIBRARY', (bool)$slider_wol_lib);
                Configuration::updateValue('SBS_MANUFACTURER_IDS', $list_of_manufacturer);
                $this->module->clearTplCache('*');
                $this->confirmations[] = $this->l('The settings have been updated!');
            }
        }
        parent::postProcess();
    }

    public function renderForm()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs'
                ),
                'input' => array(
                    array(
                        'type' => 'free',
                        'label' => $this->l('Manufacturer'),
                        'name' => 'SBS_MANUFACTURER_IDS',
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Show Manufacture Name'),
                        'name' => 'SBS_MANUFACTURER_SLIDER_SHOW_NAMES',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Yes')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('No')
                            )
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Autoplay for products slider'),
                        'name' => 'SBS_MANUFACTURER_SLIDER_AUTOPLAY',
                        'class' => 'fixed-width-xs',
                        'values' => array(
                            array(
                                'id' => 'autoplay_on',
                                'value' => 1,
                                'label' => $this->l('Yes'),
                            ),
                            array(
                                'id' => 'autoplay_off',
                                'value' => 0,
                                'label' => $this->l('No'),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Speed for changing slides, in ms (for example, 5000)'),
                        'name' => 'SBS_MANUFACTURER_SLIDER_AUTOPLAY_SPEED',
                        'class' => 'fixed-width-xs',
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Space on the right of the every product slide'),
                        'name' => 'SBS_MANUFACTURER_SLIDER_MARGIN',
                        'class' => 'fixed-width-xs',
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Infinite scrolling'),
                        'name' => 'SBS_MANUFACTURER_SLIDER_LOOP',
                        'class' => 'fixed-width-xs',
                        'values' => array(
                            array(
                                'id' => 'loop_on',
                                'value' => 1,
                                'label' => $this->l('Yes'),
                            ),
                            array(
                                'id' => 'loop_off',
                                'value' => 0,
                                'label' => $this->l('No'),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Items displayed in every time - for desktop'),
                        'name' => 'SBS_MANUFACTURER_SLIDER_ITEMS_DESKTOP',
                        'class' => 'fixed-width-xs',
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Items displayed in every time - for mobile'),
                        'name' => 'SBS_MANUFACTURER_SLIDER_ITEMS_MOBILE',
                        'class' => 'fixed-width-xs',
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Enable navigation'),
                        'name' => 'SBS_MANUFACTURER_SLIDER_NAV',
                        'class' => 'fixed-width-xs',
                        'values' => array(
                            array(
                                'id' => 'nav_on',
                                'value' => 1,
                                'label' => $this->l('Yes'),
                            ),
                            array(
                                'id' => 'nav_off',
                                'value' => 0,
                                'label' => $this->l('No'),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Enable dots navigation'),
                        'name' => 'SBS_MANUFACTURER_SLIDER_DOTS',
                        'class' => 'fixed-width-xs',
                        'values' => array(
                            array(
                                'id' => 'dot_on',
                                'value' => 1,
                                'label' => $this->l('Yes'),
                            ),
                            array(
                                'id' => 'dot_off',
                                'value' => 0,
                                'label' => $this->l('No'),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('How many random brands to choose from the base?'),
                        'desc' => $this->l('If nothing is specified or if you specify 0, all will be displayed.'),
                        'name' => 'SBS_MANUFACTURER_SLIDER_RANDOM_COUNT',
                        'class' => 'fixed-width-xs',
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Disable owl library from this module'),
                        'name' => 'SBS_MANUFACTURER_SLIDER_DISABLE_OWL_LIBRARY',
                        'class' => 'fixed-width-xs',
                        'values' => array(
                            array(
                                'id' => 'owl_lib_on',
                                'value' => 1,
                                'label' => $this->l('Yes'),
                            ),
                            array(
                                'id' => 'owl_lib_off',
                                'value' => 0,
                                'label' => $this->l('No'),
                            ),
                        ),
                        'hint' => $this->l('Necessary to avoid conflicts when loading this library with more than just this module.')
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                    'name' => 'submitSbsManufacturerSliderConfig',
                ),
            ),
        );
        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->allow_employee_form_lang =
            Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ?
                Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') :
                0;
        $helper->identifier = $this->identifier;
        $helper->currentIndex = $this->context->link->getAdminLink('AdminManufacturerSliderConfig', false);
        $helper->token = Tools::getAdminTokenLite('AdminManufacturerSliderConfig');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
        );
        return $helper->generateForm(array($fields_form));
    }

    public function getConfigFieldsValues()
    {
        return array(
            'SBS_MANUFACTURER_SLIDER_SHOW_NAMES' => (bool)Tools::getValue('SBS_MANUFACTURER_SLIDER_SHOW_NAMES', (bool)Configuration::get('SBS_MANUFACTURER_SLIDER_SHOW_NAMES')),
            'SBS_MANUFACTURER_SLIDER_AUTOPLAY' => Tools::getValue('SBS_MANUFACTURER_SLIDER_AUTOPLAY', (bool)Configuration::get('SBS_MANUFACTURER_SLIDER_AUTOPLAY')),
            'SBS_MANUFACTURER_SLIDER_AUTOPLAY_SPEED' => Tools::getValue('SBS_MANUFACTURER_SLIDER_AUTOPLAY_SPEED', (int)Configuration::get('SBS_MANUFACTURER_SLIDER_AUTOPLAY_SPEED')),
            'SBS_MANUFACTURER_SLIDER_MARGIN' => Tools::getValue('SBS_MANUFACTURER_SLIDER_MARGIN', (int)Configuration::get('SBS_MANUFACTURER_SLIDER_MARGIN')),
            'SBS_MANUFACTURER_SLIDER_LOOP' => Tools::getValue('SBS_MANUFACTURER_SLIDER_LOOP', (bool)Configuration::get('SBS_MANUFACTURER_SLIDER_LOOP')),
            'SBS_MANUFACTURER_SLIDER_ITEMS_DESKTOP' => Tools::getValue('SBS_MANUFACTURER_SLIDER_ITEMS_DESKTOP', (int)Configuration::get('SBS_MANUFACTURER_SLIDER_ITEMS_DESKTOP')),
            'SBS_MANUFACTURER_SLIDER_ITEMS_MOBILE' => Tools::getValue('SBS_MANUFACTURER_SLIDER_ITEMS_MOBILE', (int)Configuration::get('SBS_MANUFACTURER_SLIDER_ITEMS_MOBILE')),
            'SBS_MANUFACTURER_SLIDER_NAV' => Tools::getValue('SBS_MANUFACTURER_SLIDER_NAV', (bool)Configuration::get('SBS_MANUFACTURER_SLIDER_NAV')),
            'SBS_MANUFACTURER_SLIDER_DOTS' => Tools::getValue('SBS_MANUFACTURER_SLIDER_DOTS', (bool)Configuration::get('SBS_MANUFACTURER_SLIDER_DOTS')),
            'SBS_MANUFACTURER_SLIDER_RANDOM_COUNT' => Tools::getValue('SBS_MANUFACTURER_SLIDER_RANDOM_COUNT', (int)Configuration::get('SBS_MANUFACTURER_SLIDER_RANDOM_COUNT')),
            'SBS_MANUFACTURER_SLIDER_DISABLE_OWL_LIBRARY' => Tools::getValue('SBS_MANUFACTURER_SLIDER_DISABLE_OWL_LIBRARY', (bool)Configuration::get('SBS_MANUFACTURER_SLIDER_DISABLE_OWL_LIBRARY')),
            'SBS_MANUFACTURER_IDS' => $this->displayManufacturerForm(),
        );
    }
}
