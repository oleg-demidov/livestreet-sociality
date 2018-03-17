<?php


if (!class_exists('Plugin')) {
    die('Hacking attempt!');
}

class PluginSociality extends Plugin
{

        /**
     * Активация плагина
     *
     * @return bool
     */
    public function Activate()
    {
        return true;
    }


    /**
     * Инициализация плагина
     */
    public function Init()
    {
        $this->Component_Add('sociality:buts');
    }


    /**
     * Проверка зависимостей плагина
     *
     * @return bool
     */
    public function Deactivate() 
    {
        if ($this->isTableExists('prefix_sociality')){
            $this->exportSQLQuery('DROP TABLE prefix_sociality');
        }
        return true;
    }

}

?>