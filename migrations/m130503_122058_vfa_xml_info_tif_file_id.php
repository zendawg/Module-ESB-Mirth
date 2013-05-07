<?php

class m130503_122058_vfa_xml_info_tif_file_id extends CDbMigration {

    // Use safeUp/safeDown to do migration with transaction
    public function safeUp() {
        $this->addColumn('mod_servicebus_vfa_xml_info', 'tif_file_id', "int(10) unsigned default NULL");
        $this->addForeignKey('mod_servicebus_vfa_xml_info_tif_file_id_fk', 'mod_servicebus_vfa_xml_info', 'tif_file_id', 'mod_servicebus_vfa_files', 'file_id');
    }

    public function down() {
        $this->dropForeignKey('mod_servicebus_vfa_xml_info_tif_file_id_fk', 'mod_servicebus_vfa_xml_info');
        $this->dropColumn('mod_servicebus_vfa_xml_info', 'tif_file_id');
    }
}