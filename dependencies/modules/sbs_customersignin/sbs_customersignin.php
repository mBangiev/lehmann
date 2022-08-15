<?php
/**
 * Sbs_CustomerSignIn
 *
 * @category  Module
 * @author    silbersaiten <info@silbersaiten.de>
 * @support   silbersaiten <support@silbersaiten.de>
 * @copyright 2021 silbersaiten
 * @version   1.0.4
 * @link      http://www.silbersaiten.de
 * @license   See joined file licence.txt
 */

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;

if (!defined('_PS_VERSION_')) {
    exit;
}

class Sbs_CustomerSignIn extends Module implements WidgetInterface
{
    private $templateFile;

    public function __construct()
    {
        $this->name = 'sbs_customersignin';
        $this->author = 'silbersaiten';
        $this->version = '1.0.4';
        $this->need_instance = 0;

        parent::__construct();

        $this->displayName = $this->l('Sbs customer Sign in link');
        $this->description = $this->l('Adds a block that displays information about the customer.');
        $this->ps_versions_compliancy = array('min' => '1.7.1.0', 'max' => _PS_VERSION_);

        $this->templateFile = 'module:sbs_customersignin/sbs_customersignin.tpl';
    }

    public function install()
    {
        return parent::install() &&
            $this->registerHook('displayNav2');
    }

    public function getWidgetVariables($hookName, array $configuration)
    {
        $logged = $this->context->customer->isLogged();

        if ($logged) {
            $customerName = $this->getTranslator()->trans(
                '%firstname% %lastname%',
                array(
                    '%firstname%' => $this->context->customer->firstname,
                    '%lastname%' => $this->context->customer->lastname,
                ),
                'Modules.Customersignin.Admin'
            );
        } else {
            $customerName = '';
        }

        $link = $this->context->link;

        return array(
            'logged' => $logged,
            'customerName' => $customerName,
            'logout_url' => $link->getPageLink('index', true, null, 'mylogout'),
            'my_account_url' => $link->getPageLink('my-account', true),
            'my_account_urls' => $this->getMyAccountLinks(),
        );
    }

    public function renderWidget($hookName, array $configuration)
    {
        $this->smarty->assign($this->getWidgetVariables($hookName, $configuration));
        return $this->fetch($this->templateFile);
    }

    private function getMyAccountLinks()
    {
        $link = $this->context->link;

        $my_account_urls = array(
            2 => array(
                'title' => $this->l('Orders'),
                'url' => $link->getPageLink('history', true),
            ),
            3 => array(
                'title' => $this->l('Credit slips'),
                'url' => $link->getPageLink('order-slip', true),
            ),
            4 => array(
                'title' => $this->l('Addresses'),
                'url' => $link->getPageLink('addresses', true),
            ),
            0 => array(
                'title' => $this->l('Personal info'),
                'url' => $link->getPageLink('identity', true),
            ),
        );

        if ((int)Configuration::get('PS_ORDER_RETURN')) {
            $my_account_urls[1] = array(
                'title' => $this->l('Merchandise returns'),
                'url' => $link->getPageLink('order-follow', true),
            );
        }

        if (CartRule::isFeatureActive()) {
            $my_account_urls[5] = array(
                'title' => $this->l('Vouchers'),
                'url' => $link->getPageLink('discount', true),
            );
        }

        ksort($my_account_urls);

        return $my_account_urls;
    }
}
