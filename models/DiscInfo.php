<?php

/**
 * OpenEyes
 *
 * (C) OpenEyes Foundation, 2011-2012
 * This file is part of OpenEyes.
 * OpenEyes is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * OpenEyes is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License along with OpenEyes in a file titled COPYING. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package OpenEyes
 * @link http://www.openeyes.org.uk
 * @author OpenEyes <info@openeyes.org.uk>
 * @copyright Copyright (c) 2011-2012, OpenEyes Foundation
 * @license http://www.gnu.org/licenses/gpl-3.0.html The GNU General Public License V3.0
 */
?>
<?php

/**
 * Encapsulates information about Kowa .txt files that are associated with
 * each imported Kowa stereo image.
 *
 * The followings are the available model relations:
 * 
 * @property ServiceBusFile $file
 * 
 * Note that unlike the similar VfaInfo model, which contains an explicit
 * reference to the name of the file in the XML file, the disc info contains
 * no such reference and is implied by the two attributes PID and photo ID.
 * Thus no relation is provided and related images are obtained through
 * the function call getRelatedImage().
 */
class DiscInfo extends BaseEventTypeElement {

    /**
     * Returns the static model of the specified AR class.
     * @return ElementOperation the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'mod_servicebus_disc_info';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'file' => array(self::BELONGS_TO, 'ServiceBusFile', 'file_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
        );
    }
    
    /**
     * Get the image associated with this text file.
     * 
     * @return the name of the image file associated with this info file.
     */
    public function getRelatedImage() {
        
        $exam_criteria = new CDbCriteria;
        $exam_criteria->distinct = true;
        $exam_criteria->select = "name";
        $foo = $this->file_name;
        $exam_criteria->condition = 'pid=\''
                . $this->pid . '\' and photo_id=' . $this->photo_id;
        return DiscFiles::model()->findAll($exam_criteria);
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);

        return new CActiveDataProvider(get_class($this), array(
                    'criteria' => $criteria,
                ));
    }

}

?>
