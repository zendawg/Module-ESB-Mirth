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
class VfaUtils {
  /** Image gallery location; note that we're only interested in SITA
   * standard at the moment. */

  const IMAGE_GALLERY = "/images/vfa-images/SITA-Standard/";
  /** Image file name extension. */
  const VFA_IMAGE_EXT = ".tif";
  /** Thumb image file name extension. */
  const VFA_THUMB_IMAGE_EXT = ".jpg";

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
  public static function getVfaFileList($patient, $eye = 'L', $test_type = 'Humphrey', $strategy = 'Site Standard') {
    $x = $patient->hos_num;
    $exam_criteria = new CDbCriteria;
    $exam_criteria->condition = 'pid=\''
            . $patient->hos_num . '\' and '
            . 'eye=\'' . $eye . '\'';
    $x = $exam_criteria->condition;
    $data = VfaInfo::model()->findAll($exam_criteria);
    return $data;
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
  public static function getVfaFile($patient, $asset_id, $eye = 'L') {
    $exam_criteria = new CDbCriteria;
    $exam_criteria->condition = ServiceBusFile::model()->tableName() . '.asset_id=' . $asset_id;
    $exam_criteria->join =
            ' left join ' . ServiceBusFile::model()->tableName()
            . ' on ' . 'asset_id=' . ServiceBusFile::model()->tableName()
            . '.asset_id'
    ;
    try {
      $data = VfaFiles::model()->find($exam_criteria);
    } catch (Exception $e) {
      $foo = $e;
    }
    return $data;
  }

  /**
   * Get the text for displaying the thumbnail links for the specified
   * patient's hospital number and file name. Note that the data includes
   * links to the pre-defined gallery of images, except that the hospital
   * number is encoded via MD5 hash (as is the filename).
   * 
   * @param int $hos_num the hospital number of the 
   * @param string $original_filename
   * @return string data for the HTML HREF links; both hospital number and
   * file name are encoded.
   */
  public static function getVfaFileLinkText($hos_num, $original_filename) {
    return
            // first part - actual file image location
            "<a href=\"" . self::IMAGE_GALLERY
            . VfaUtils::getUid($hosNum) . '-' . md5($hos_num)
            . "/" . md5($original_filename) . self::VFA_IMAGE_EXT
            . "\">"
            // 2nd part - thumbnail image location:
            . "<img src=\"" . self::IMAGE_GALLERY
            . VfaUtils::getUid($hosNum) . '-' . md5($hos_num) . "/thumbs/"
            . md5($original_filename) . self::VFA_IMAGE_EXT . "\""
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
  public static function getEncodedDiscFileName($hosNum) {
    return
            // first part - actual file image location
            self::IMAGE_GALLERY
            . VfaUtils::getUid($hosNum); // . '-' . md5($hosNum);
  }

  /**
   * 
   * REPEATED CODE!
   * @param type $hosNum
   */
  private static function getUid($hosNum) {
    $uid = null;
    $patient_uid = ScannedDocumentUid::model()->find('pid=\'' . $hosNum . '\'');
    if ($patient_uid) {
      $uid = $patient_uid->id;
    }
    return $uid;
  }

}

?>
