{$component = "soc-buts"}

{component_define_params params=[ 'aButs', 'classes']}
{capture 'buttons'}
    {foreach from=$aButs item=button}
        <a style="margin:2px;" href="{$button.url}" title="{$button.title}" alt="{$button.title}">
            <img src="{$button.img}" title="{$button.title}"/>
        </a> 
    {/foreach}
{/capture}

{component 'block' classes="{$component}" content=$smarty.capture.buttons title={lang name="plugin.sociality.auth.buts.title.{$sType}"}}