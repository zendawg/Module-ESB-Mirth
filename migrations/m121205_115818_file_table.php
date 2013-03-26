<?php

require_once 'ServiceBusMigration.php';

class m121205_115818_file_table extends ServiceBusMigration {

    private $suffixFileTable = 'file';
    private $suffixDirTable = 'directory';
    private $fileLinkTables = array('disc_files', 'disc_info',
        'vfa_files', 'vfa_xml_info');
    
    public function safeUp() {
        $this->createDirectoryTable();
        $this->createFileTable();
        foreach ($this->fileLinkTables as $tableName) {
            $this->addColumn($this->getTableName($tableName), 'file_id', 'int(10) unsigned NOT NULL');
            $this->createIndex($this->getTableName($tableName) . '_file_id_fk',$this->getTableName($tableName),'file_id');
            $this->addForeignKey($this->getTableName($tableName) . '_file_id_fk',$this->getTableName($tableName),'file_id',$this->getTableName($this->suffixFileTable),'id');
        }
        $this->dropColumn($this->getTableName('vfa_files'), 'original_filename');
        $this->dropColumn($this->getTableName('disc_files'), 'original_filename');
        
    }

    public function safeDown() {
        $this->addColumn($this->getTableName('vfa_files'), 'original_filename', 'varchar(100) COLLATE utf8_bin NOT NULL');
        $this->addColumn($this->getTableName('disc_files'), 'original_filename', 'varchar(100) COLLATE utf8_bin NOT NULL');
        foreach ($this->fileLinkTables as $tableName) {
            $this->dropForeignKey($this->getTableName($tableName) . '_file_id_fk',$this->getTableName($tableName));
            $this->dropIndex($this->getTableName($tableName) . '_file_id_fk',$this->getTableName($tableName));
            $this->dropColumn($this->getTableName($tableName), 'file_id');
        }
        $this->deleteTableAndData($this->getTableName($this->suffixFileTable));
        $this->deleteTableAndData($this->getTableName($this->suffixDirTable));
    }
    
    /**
     * Creates a table to identify unique IDs with patient IDs.
     */
    private function createFileTable() {
        
        $this->createTable($this->getTableName($this->suffixFileTable), array_merge(array(
                    'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
                    'name' => 'varchar(128) NOT NULL',
                    'modified' => 'bigint signed NOT NULL',
                    'length' => 'int(10) signed NOT NULL',
                    'chronological_key' => 'bigint unsigned NOT NULL',
                    'dir_id' => 'int(10) unsigned NOT NULL',
            
            ), $this->getDefaults($this->suffixFileTable)), 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin');
        $this->createIndex($this->getTableName($this->suffixFileTable) . '_dir_id_fk',$this->getTableName($this->suffixFileTable),'dir_id');
        $this->addForeignKey($this->getTableName($this->suffixFileTable) . '_dir_id_fk',$this->getTableName($this->suffixFileTable),'dir_id',$this->getTableName($this->suffixDirTable),'id');
        
    }
    
    /**
     * Creates a table to identify unique IDs with patient IDs.
     */
    private function createDirectoryTable() {
        
        $this->createTable($this->getTableName($this->suffixDirTable), array_merge(array(
                    'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
                    'modified' => 'bigint signed NOT NULL',
                    'path' => 'text NOT NULL',
            
            ), $this->getDefaults($this->suffixDirTable)), 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin');
    }


}
