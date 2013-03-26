<?php

require_once 'ServiceBusMigration.php';

class m121010_180143_migrate extends ServiceBusMigration {

    /**
     * 
     */
    public function safeUp() {
        $this->createFileAuditTable();
        $this->createStereoDiskTables();
        $this->createVfaTables();
    }

    /**
     * 
     */
    public function safeDown() {
        $this->deleteTableAndData($this->getTableName('disc_info'));
        $this->deleteTableAndData($this->getTableName('disc_files'));
        $this->deleteTableAndData($this->getTableName('file_audit'));
        $this->deleteTableAndData($this->getTableName('vfa_xml_info'));
        $this->deleteTableAndData($this->getTableName('vfa_files'));
    }
    
    /**
     * Creates the tables necessary for stereo disk image data, specifically
     * Kowa Stereoscopy cameras. However, these tables do not require that
     * such equipment is used.
     * 
     * Two tables are created:
     * 
     * - one to maintain information about each individual file as it is
     *   imported;
     * - one to maintain information about the patient, eye, diagnosis etc.
     *   related directly to the image.
     * 
     */
    private function createStereoDiskTables() {
        
        $suffix = 'disc_info';
        $this->createTable($this->getTableName($suffix), array_merge(array(
                    'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
                    'photo_id' => 'int(10)',
                    'pid' => 'varchar(40) NOT NULL',
                    'name' => 'varchar(50)',
                    'exam_date' => 'date',
                    'exam_time' => 'time',
                    'dob' => 'varchar(20)',
                    'gender' => 'char',
                    'diagnosis1' => 'text',
                    'diagnosis2' => 'text',
                    'diagnosis3' => 'text',
                    'diagnosis4' => 'text',
                    'examiner' => 'text',
                    'eye' => 'char',
                    'comments' => 'text'
            ), $this->getDefaults($suffix)), 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin');
        $this->createIndex('photo_id_index', $this->getTableName($suffix), 'photo_id');
        
        $suffix = 'disc_files';
        $this->createTable($this->getTableName($suffix), array_merge(array(
                    'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
                    'photo_id' => 'int(10) unsigned NOT NULL',
                    'pid' => 'varchar(40) NOT NULL',
                    'original_filename' => 'varchar(100) NOT NULL',
//            'KEY `' . $this->getTableName($suffix . '_photo_id_in dex_fk') . '`
//			(`photo_id`)',
//            'CONSTRAINT `' . $this->getTableName($suffix . '_photo_id_index_fk') . '` FOREIGN KEY
//			(`photo_id`) REFERENCES `'. $this->getTableName('disc_info') . '` (`photo_id_index`)',
            ), $this->getDefaults($suffix)), 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin');

    }

    /**
     * 
     */
    private function createVfaTables() {
        $suffix = 'vfa_files';
        $this->createTable($this->getTableName($suffix), array_merge(array(
                    'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
                    'original_filename' => 'varchar(100) NOT NULL',), $this->getDefaults($suffix)), 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin');


        $suffix = 'vfa_xml_info';
        $this->createTable($this->getTableName($suffix), array_merge(array(
                    'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
                    'pid' => 'varchar(40) NOT NULL',
                    'given_name' => 'varchar(50)',
                    'middle_name' => 'varchar(50)',
                    'family_name' => 'varchar(50)',
                    'birth_date' => 'varchar(10)',
                    'study_date' => 'varchar(10)',
                    'study_time' => 'varchar(12)',
                    'gender' => 'char',
                    'eye' => 'char',
                    'file_name' => 'varchar(100) NOT NULL',
                    'test_strategy' => 'varchar(100) NOT NULL',
                        ), $this->getDefaults($suffix)), 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin');
    }

    /**
     * 
     */
    private function createFileAuditTable() {
        $suffix = 'file_audit';
        $this->createTable($this->getTableName($suffix), array_merge(array(
                    'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
                    'src_size' => 'int(10) unsigned NOT NULL',
                    'dest_size' => 'int(10) unsigned',
                    'operation' => 'char NOT NULL',
                    'type' => 'char NOT NULL',
                    'src_parent' => 'varchar(255)',
                    'dest_parent' => 'varchar(255)',
                    'src_child' => 'varchar(255)',
                    'dest_child' => 'varchar(255)'), $this->getDefaults($suffix)), 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin');
    }
}
