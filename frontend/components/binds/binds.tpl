{$component = "soc-binds"}
{component_define_params params=[ 'aSocials' ]}
<fieldset class="js-user-fields">
    <legend>Привязанные социальные сети</legend>
    {if !{$aSocials|@sizeof}}
        {component 'blankslate' title="Не привязано"}
    {/if}
    {foreach from=$aSocials item=oSocial}
        {component 'actionbar' mods="large" items=[
            [
                'buttons' => [
                    [ 'text' => $oSocial->getSocialType(), 'type'=>'button', 'mods' => 'primary large' ],
                    [ 'text' => $oSocial->getProfileUrl(), 'mods'=>"large",'url' => $oSocial->getProfileUrl()],
                    [ 'icon'=> 'close', 
                        'classes'=>"js-bindremove-confirm",
                        'url'=>"{router page='oauth'}{$oSocial->getSocialType()}/remove/?security_ls_key={$LIVESTREET_SECURITY_KEY}", 
                        'mods'=>"large danger", 'title' => 'Отвязать']
                ]
            ]
        ]}
    {/foreach}
   
    <legend>Привязать:</legend><br>
    {foreach from=$aButtonsNames item=but}
        <a style="margin:2px;" href="{router page="oauth/{$but}"}bind" title="{$but}" alt="{$but}">
            <img src="{$sUriPluginSkin}/img/{$but}-{$sSizeButton}.png"/></a> 
    {/foreach}
</fieldset>