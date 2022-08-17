{**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 *}
{extends file='page.tpl'}

{block name='header'}
{/block}

{block name="breadcrumb"}{/block}

{block name='page_title'}
  <h1 class="forofour">404</h1>
  {$page.title}
{/block}

{capture assign="errorContent"}
  <h4>{l s='No products available yet' d='Shop.Theme.Catalog'}</h4>
  <p>{l s='Stay tuned! More products will be shown here as they are added.' d='Shop.Theme.Catalog'}</p>

  <hr />
  <a class="_blank" href="{$urls.base_url}" target="_blank">
    {l s='%copyright% %year% %shop_name%' sprintf=['%shop_name%' => {$shop.name}, '%year%' => 'Y'|date, '%copyright%' => '©'] d='Shop.Theme.Global'}
  </a>
{/capture}


{block name='page_content_container'}
  {include file='errors/not-found.tpl' errorContent=$errorContent}
{/block}

{block name='footer'}
{/block}
