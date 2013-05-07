<?php

/**
 * This migration deals with the JIRA 2045 use of 'assets' as a core OE
 * concept for file relationships - in this case, against the
 * OphScImagesstereoscopic module.
 */
class m130429_153238_asset_file_link extends CDbMigration {

    // Use safeUp/safeDown to do migration with transaction
    public function safeUp() {
        $this->addColumn('mod_servicebus_file', 'asset_id', "int(10) unsigned default NULL");
        $this->addForeignKey('mod_servicebus_file_asset_id_fk', 'mod_servicebus_file', 'asset_id', 'asset', 'id');
    }

    public function down() {
        $this->dropForeignKey('mod_servicebus_file_asset_id_fk', 'mod_servicebus_file');
        $this->dropColumn('mod_servicebus_file', 'asset_id');
    }

}