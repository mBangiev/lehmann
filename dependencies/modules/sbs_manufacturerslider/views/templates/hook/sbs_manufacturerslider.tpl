{**
 * Sbs_ManufacturersSlider
 *
 * @author    silbersaiten <info@silbersaiten.de>
 * @copyright 2020 silbersaiten
 * @license   See joined file licence.txt
 * @link      http://www.silbersaiten.de
 * @support   silbersaiten <support@silbersaiten.de>
 * @category  Module
 * @version   1.0.3
*}

<section class="manufacturers sbs_manufacturer_slider">
    <div class="container">
        <h2 class="h2 products-section-title sbs_manufacturer_slider_title">
            {if $display_link_brand}<a href="{$page_link}" title="{l s='Brands' mod='sbs_manufacturerslider'}">{/if}
                {l s='Brands' mod='sbs_manufacturerslider'}
                {if $display_link_brand}</a>{/if}
        </h2>
        <div class="products">
            {if $brands}
                <div id="sbs_manufacturer_carousel" class="product_list owl-carousel owl-theme">
                    {foreach $brands as $brand}
                        <div class="brand-item">
                            <div class="brand-image">
                                <a href="{$link->getManufacturerLink($brand['id_manufacturer'], $brand['link_rewrite'])}"
                                   title="{$brand.name}">
                                    <img src="{$brand.image}"
                                         alt="{$brand.name}"/>
                                </a>
                            </div>
                            {if $show_names}
                                <p class="h3 product-title" itemprop="name">
                                    <a class="product-name" itemprop="url"
                                       href="{$link->getManufacturerLink($brand['id_manufacturer'], $brand['link_rewrite'])}"
                                       title="{$brand.name}">{$brand.name}</a>
                                </p>
                            {/if}
                        </div>
                    {/foreach}
                </div>
            {else}
                <p>{l s='No brand' mod='sbs_manufacturerslider'}</p>
            {/if}
        </div>
    </div>
</section>
