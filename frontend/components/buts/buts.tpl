{$component = "soc-buts"}
{capture 'buttons'}
{foreach from=$aButtonsNames item=but}
    <a style="margin:2px;" href={router page="oauth/{$but}"} title="{$but}" alt="{$but}">
        <img src="{$sUriPluginSkin}/img/{$but}-{$sSizeButton}.png"/></a> 
{/foreach}
{/capture}
{component 'block' classes="{$component}" content=$smarty.capture.buttons title="Вход через соцсети:"}