<?php

require_once 'ServiceBusMigration.php';

class m121024_121958_uid_table extends ServiceBusMigration {

    public function safeUp() {
        $this->createUidTable();
    }

    public function safeDown() {
        $this->deleteTableAndData($this->getTableName('uid'));
    }
    
    /**
     * Creates a table to identify unique IDs with patient IDs.
     */
    private function createUidTable() {
        
        $suffix = 'uid';
        $this->createTable($this->getTableName($suffix), array_merge(array(
                    'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
                    'pid' => 'varchar(40) NOT NULL',
            ), $this->getDefaults($suffix)), 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin');

    }


}
