<?php

class ServiceBusMigration extends CDbMigration {
    

    /**
     * Gets the module-specific table name with the applied suffix.
     * @param string $suffix the suffix to append to the table.
     * @return string the table name appeneded with the suffix.
     */
    public function getTableName($suffix) {
        return 'mod_servicebus_' . $suffix;
    }

    /**
     * Delete data and drop table.
     * 
     * @param table_name the name of the table to delete data from; afterward,
     * drop the table.
     */
    public function deleteTableAndData($table_name) {

        $this->delete($table_name);
        $this->dropTable($table_name);
    }

    /**
     * Returns all the default table array elements that all tables share.
     * This is a convenience method for all table creation.
     * 
     * @param $suffix the table name suffix - this is the name of the table
     * without the formal table name 'et_[spec][group][code]_'.
     * 
     * @param useEvent by default, the event type is created as a foreign
     * key to the event table; set this to false to not create this key.
     * 
     * @return an array of defaults to merge in to the table array data required.
     */
    public function getDefaults($suffix) {
        $defaults = array('last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
            'last_modified_date' => 'datetime NOT NULL DEFAULT \'1901-01-01
        00:00:00\'',
            'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
            'created_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
            'PRIMARY KEY (`id`)',
            'KEY `' . $this->getTableName($suffix . '_last_modified_user_id_fk') . '`
        (`last_modified_user_id`)',
            'CONSTRAINT `' . $this->getTableName($suffix . '_created_user_id_fk') . '`
        FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
            'CONSTRAINT
        `' . $this->getTableName($suffix . '_last_modified_user_id_fk') . '` FOREIGN KEY
        (`last_modified_user_id`) REFERENCES `user` (`id`)');
        return $defaults;
    }
}
?>
