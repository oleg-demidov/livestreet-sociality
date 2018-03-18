# livestreet-sociality

Чтобы отображался email в поле регистрации, необходимо исправить файл шаблона application/frontend/components/auth/auth.registration.tpl

{component 'field' template='email' rules=[ 'remote' => "{router page='auth'}ajax-validate-email" ]}

на 

{component 'field' name="mail" template='email' rules=[ 'remote' => "{router page='auth'}ajax-validate-email" ]}