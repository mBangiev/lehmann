{**
 * Sbs_ManufacturersSlider
 *
 * @author    silbersaiten <info@silbersaiten.de>
 * @copyright 2022 silbersaiten
 * @license   See joined file licence.txt
 * @link      https://www.silbersaiten.de
 * @support   silbersaiten <support@silbersaiten.de>
 * @category  Module
 * @version   1.0.8
*}

<div class="panel">
    <div class="panel-body">
        <div class="row">
            {foreach $manufacturer_list as $manufacturer}
                <div class="row">
                    <label>
                        <input
                                type="checkbox"
                                name="SBS_MANUFACTURER_IDS[]"
                                value="{$manufacturer.id_manufacturer}"
                                {if in_array($manufacturer.id_manufacturer, $selected_manufacturer)}
                                    checked="checked"
                                {/if}
                        >
                        {$manufacturer.name}
                    </label>
                </div>
            {/foreach}
        </div>
    </div>
</div>
