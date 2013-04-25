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
 * Utilities to get files from the stereo image directory after images are
 * imported.
 */
class DiscUtils {

    /** Image gallery location. */
    const IMAGE_GALLERY = "/images/stereoscopy-images/";
    /** Image file name extension. */
    const STEREO_IMAGE_EXT = ".jpg";
    
    /**
     * Get the list of files associated with the specified patient.
     * 
     * @param Patient $patient the patient under scrutiny.
     * 
     * @param char $side which eye to select files from, one of 'L' or 'R'.
     * 
     * @return the list of files, if there are any; otherwise, the empty list
     * is returned.
     */
    public static function getDiscFileList($patient, $eye = 'L') {
        $tableInfo = DiscInfo::model()->tableName();
        $tableFiles = DiscFiles::model()->tableName();
        $exam_criteria = new CDbCriteria;
        $exam_criteria->alias = $tableFiles;
        $exam_criteria->select = $tableFiles . '.file_id';
        $exam_criteria->join = 'left join '
                . $tableInfo . ' on ' . $tableFiles
                . '.pid=' 
                . $tableInfo . '.pid and ' . $tableFiles . '.photo_id='
                . $tableInfo . '.photo_id and '
                . $tableInfo . '.eye=\'' . $eye . '\'';
        $exam_criteria->distinct = true;
        $exam_criteria->group = $tableFiles . ".photo_id";
        $exam_criteria->condition = $tableInfo . '.pid=\''
                . $patient->hos_num . '\' and '
                . $tableInfo . '.eye=\'' . $eye . '\'';
        return DiscFiles::model()->findAll($exam_criteria);
        return array();
    }
    

    /**
     * Get the text for displaying the thumbnail links for the specified
     * patient's hospital number and file name. Note that the data includes
     * links to the pre-defined gallery of images, except that the hospital
     * number is encoded via MD5 hash (as is the filename).
     * 
     * @param int $pid the patient's unique DB value
     * @param int $hos_num the hospital number of the 
     * @param string $original_filename
     * @return string data for the HTML HREF links; both hospital number and
     * file name are encoded.
     */
    public static function getDiscFileLinkText($pid, $hos_num, $original_filename) {
        return
                // first part - actual file image location
                "<a href=\"" . self::IMAGE_GALLERY
                . DiscUtils::getUid($hos_num) . '-' . md5($hos_num)
                . "/" . md5($original_filename) . self::STEREO_IMAGE_EXT
                . "\">"
                // 2nd part - thumbnail image location:
                . "<img src=\"" . self::IMAGE_GALLERY
                . DiscUtils::getUid($hos_num) . '-' . md5($hos_num) . "/thumbs/"
                . md5($original_filename) . self::STEREO_IMAGE_EXT . "\""
                . "/></a>";
    }
    
    /**
     * Given some hospital number, and a file name, return the encoded
     * directory name based on the information.
     * 
     * @param type $hosNum non-null hospital number.
     * @param type $original_filename the non-null filename.
     * @return type a string containinf the full (YII) path to the image
     * file and patient number after encoding using MD5 hash.
     */
    public static function getEncodedDiscFileName($hos_num, $original_filename) {
        return
                // first part - actual file image location
                self::IMAGE_GALLERY
                . DiscUtils::getUid($hos_num) . '-' . md5($hos_num)
                . "/thumbs/" . md5($original_filename) . self::STEREO_IMAGE_EXT;
    }
    
    /**
     * REPEATED CODE!
     * @param type $hosNum
     * @return type
     */
    private static function getUid($hosNum) {
        $list = Yii::app()->db->createCommand(
                'select id from mod_servicebus_uid where pid=\''
                . $hosNum . '\'')->queryAll();
        $uid = null;
        foreach($list as $item) {
            $uid = $item['id'];
        }
        return $uid;
    }
}

?>
