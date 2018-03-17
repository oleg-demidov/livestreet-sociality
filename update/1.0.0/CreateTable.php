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
	public function up() {
		if (!$this->isTableExists('prefix_sociality')) {
			/**
			 * При активации выполняем SQL дамп
			 */
			$this->exportSQL(Plugin::GetPath(__CLASS__).'/dump.sql');
		}
	}

	/**
	 * Выполняется при откате версии
	 */
	public function down() {
            if ($this->isTableExists('prefix_sociality')){
		$this->exportSQLQuery('DROP TABLE prefix_sociality');
            }
	}
}