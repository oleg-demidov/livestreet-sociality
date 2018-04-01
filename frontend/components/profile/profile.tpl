{$component = "profile"}
{component_define_params params=[ 'oProfileData', 'sProvider', 'sAttach', 'sAttachText' ]}

<style>
    .soc-profile-image{
        width:120px;
    }
</style>

{capture 'content'}
    {component 'item'
        title="{$oProfileData->firstName} {$oProfileData->lastName}"
        desc={component 'info-list' list=[
                [ 'label' => 'Провайдер:', 'content' => $sProvider ],
                [ 'label' => 'Идентификатор:', 'content' => $oProfileData->identifier ],
                [ 'label' => 'Email:', 'content' => $oProfileData->email ]
            ]}
        image=[
            'path' => $oProfileData->photoURL,
            'classes' => "soc-profile-image"
        ]}<br>
    {if $sAttach}
        {component 'alert' title=$sAttach visible=true text=$sAttachText}
    {/if}
{/capture}

{capture 'footer'}
    {component 'button' 
            url={router page="sociality/reset_profile"}
            text={lang name="plugin.sociality.auth.profile.cancel_profile"}}
    {if $sAttach}
        {component 'button' 
                mods="success"
                url={router page="sociality/attach_profile"}
                text={lang name="plugin.sociality.auth.profile.attach_button" params=['provider' => $sProvider]}
        }
    {/if}
{/capture}

{component 'block' 
    title={lang name="plugin.sociality.auth.profile.social_profile"}
    content=$smarty.capture.content
    footer=$smarty.capture.footer
}