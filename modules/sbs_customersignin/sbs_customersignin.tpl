{**
    * Sbs_CustomerSignIn
    *
    * @category  Module
    * @author    silbersaiten <info@silbersaiten.de>
    * @support   silbersaiten <support@silbersaiten.de>
    * @copyright 2021 silbersaiten
    * @version   1.0.3
    * @link      https://www.silbersaiten.de
    * @license   See joined file licence.txt
    *}

    <div id="_desktop_user_info">
        <div class="user-info dropdown js-dropdown user-info-selector">
            {if !$logged}
                <a  href="{$my_account_url}" class="non_login_user_info">
                    <i class="material-icons">&#xE7FF;</i>
                
                    <span class="iconLabel hidden-sm-down">{l s='Sign in' mod='sbs_customersignin'}</span>
                
                </a>
            {else}
                <button
                        data-target="#"
                        data-toggle="dropdown"
                        class="hidden-sm-down btn-unstyle"
                        aria-haspopup="true"
                        aria-expanded="false"
                        aria-label="{l s='Sign in' mod='sbs_customersignin'}"
                >
                    <i class="material-icons header-right-icon">&#xE7FF;</i>
                    <i class="material-icons expand-more">&#xE5C5;</i>
                </button>
                <ul class="dropdown-menu dropdown-menu-right hidden-sm-down" aria-labelledby="user-info-selector-label">
                    {foreach $my_account_urls as $key => $my_account_url}

                            <li>
                                <a class="dropdown-item" href="{$my_account_url.url}" title="{$my_account_url.title}" rel="nofollow">
                                    {$my_account_url.title}
                                </a>
                            </li>
                    {/foreach}
                    {if $logged}
                        <hr />
                        <li>
                            <a class="logout dropdown-item" href="{$logout_url}" rel="nofollow">
                                {l s='Sign out' mod='sbs_customersignin'}
                            </a>
                        </li>
                    {/if}
                </ul>
                <div class="hidden-md-up mobileselector" data-target="#mobile_customer" data-toggle="collapse">
                    <span id="user-info-selector-label">
                         <i class="material-icons">&#xE7FF;</i>
    {*                    {l s='Customer:' mod='sbs_customersignin'}*}
                    </span>
                    <span class="float-xs-right">
                        <span class="navbar-toggler collapse-icons hidden-sm-down">
                          <i class="material-icons add">&#xE313;</i>
                          <i class="material-icons remove">&#xE316;</i>
                        </span>
                    </span>
                </div>
                <ul id="mobile_customer" class="collapse">
                    {foreach $my_account_urls as $key => $my_account_url}
                        <li>
                            <a class="dropdown-item" href="{$my_account_url.url}" title="{$my_account_url.title}" rel="nofollow">
                                {$my_account_url.title}
                            </a>
                        </li>
                    {/foreach}
                </ul>
            {/if}
        </div>
    </div>
