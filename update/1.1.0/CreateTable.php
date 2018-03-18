<?php


/**
 * Description of CreateTable
 *
 * @author oleg
 */

class PluginSociality_Update_CreateTable extends ModulePluginManager_EntityUpdate {
	/**
	 * Выполняется при обновлении версии
	 */
    private $aDumps = [
        'prefix_sociality_social' => 'dump'
    ];


    public function up() {  
        /*
         * Никто не устанавливал. Можно сносить таблицу
         */
        $this->removeTable('prefix_sociality');
        /*
         * Меняем ключ email
         */
        $this->exportSQL(Plugin::GetPath(__CLASS__).'update/1.1.0/dump_user.sql');
        
        foreach($this->aDumps as $sTable => $sFile){
            if(!$this->exportDump($sTable, $sFile)){
                return false;
            }
        }
        
	return true;
    }
    
    protected function exportDump($sTable, $sFile) {
        if (!$this->isTableExists($sTable)) {
            $sResult = $this->exportSQL(Plugin::GetPath(__CLASS__).'update/1.1.0/'.$sFile.'.sql');
            $this->Logger_Notice(serialize($sResult));             
        }
        return true;
    }
    
    protected function removeTable($sTable) {
        if ($this->isTableExists($sTable)){
            return $this->exportSQLQuery("DROP TABLE IF EXISTS $sTable");
        }
    }

    /**
     * Выполняется при откате версии
     */
    public function down() {
        foreach($this->aDumps as $sTable => $sFile){
            $aResult = $this->removeTable($sTable);
            $this->Logger_Notice(serialize($aResult));
        }
    }
}