{$component = "soc-profile"}
{component_define_params params=[ 'avatar', 'name', 'email', 'number']}

{component 'block' classes="{$component}" content={component 'item'
    title=$name
    desc="{$email}<br>{$number}"
    image=[
        'path' => $avatar,
        'classes' => "{$component}-avatar"
    ]}
    footer={component 'button' text="Отменить" url=$sUrlResetData}}