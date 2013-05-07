<?php
/**
 * OpenEyes
 *
 * (C) University of Cardiff, 2012
 * (C) OpenEyes Foundation, 2011-2012
 * This file is part of OpenEyes.
 * OpenEyes is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * OpenEyes is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License along with OpenEyes in a file titled COPYING. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package OpenEyes
 * @link http://www.openeyes.org.uk
 * @author OpenEyes <info@openeyes.org.uk>
 * @copyright Copyright (c) University of Cardiff, 2012
 * @copyright Copyright (c) 2011-2012, OpenEyes Foundation
 * @license http://www.gnu.org/licenses/gpl-3.0.html The GNU General Public License V3.0
 */
?>
<?php

/**
 * Class to help with the summary view which caters for IOP graphs and
 * stereo image disc files.
 * 
 * Future releases will also include HVF images.
 */
class ServiceBusUtils {


    /**
     * Constructot; initialise this class and get all IOP values.
     * 
     * @param type $event the event object that is currently linked to the
     * view that fired this constructor.
     */
    function __construct() {
    }

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
    public static function getFile($assetId, $side='L') {
        $exam_criteria = new CDbCriteria;
        $exam_criteria->condition = 'id='
                . $assetId;
        $data = Asset::model()->find($exam_criteria);
//        if ($data) {
//            $exam_criteria = new CDbCriteria;
//            $exam_criteria->condition = 'asset_id='
//                    . $assetId;
//            $f = $exam_criteria->condition;
//            $data = ServiceBusFile::model()->find($exam_criteria);
////            $arse = $data->asset;
//        }
        return $data;
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
    public static function getEncodedFileName($assetId, $hosNum, $dir='/') {
        
        $exam_criteria = new CDbCriteria;
        $exam_criteria->condition = 'pid=\'' . $hosNum . '\'';
        $uid = PatientUid::model()->find($exam_criteria);
        $exam_criteria->condition = 'asset_id='
                . $assetId;
        $data = ServiceBusFile::model()->find($exam_criteria);
        if ($data) {
            $exam_criteria = new CDbCriteria;
            $exam_criteria->condition = 'file_id='
                    . $data->id;
            $x = $data->id;
            $files = VfaFiles::model()->find($exam_criteria);
            if ($files) {
                $ext =  $data->asset->extension;
                $f = $files->file->name;
                return VfaUtils::getEncodedDiscFileName($hosNum) . $dir . $files->file->name;
            }
            
            $files = DiscFiles::model()->find($exam_criteria);
            if ($files) {
                return DiscUtils::getEncodedDiscFileName($hosNum) . $dir . $files->file->name;
            }
        }
        
    }

}

?>
