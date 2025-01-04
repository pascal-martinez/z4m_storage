<?php
/**
 * ZnetDK, Starter Web Application for rapid & easy development
 * See official website https://mobile.znetdk.fr
 * Copyright (C) 2024 Pascal MARTINEZ (contact@znetdk.fr)
 * License GNU GPL https://www.gnu.org/licenses/gpl-3.0.html GNU GPL
 * --------------------------------------------------------------------
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 * --------------------------------------------------------------------
 * ZnetDK 4 Mobile Storage module view fragment
 *
 * File version: 1.1
 * Last update: 12/23/2024
 */
use \z4m_storage\mod\DocumentManager;
$maxFilesize = DocumentManager::getMaxFileSize();
$maxFilesizeError = str_replace('%max_filesize%',
        DocumentManager::convertBytesToDisplaySize($maxFilesize),
        MOD_Z4M_STORAGE_DOCUMENTS_ERROR_UPLOAD_FILESIZE_EXCEEDED);
$maxFileSelectionSize = DocumentManager::getMaxSelectionSize();
$maxFileSelectionSizeError = str_replace('%max_selectionfilesize%',
        DocumentManager::convertBytesToDisplaySize($maxFileSelectionSize),
        MOD_Z4M_STORAGE_DOCUMENTS_ERROR_UPLOAD_SELECTION_FILESIZE_EXCEEDED);
$uploadButtonLabel = isset($isPhotoUpload) && $isPhotoUpload === TRUE 
        ? MOD_Z4M_STORAGE_PHOTOS_UPLOAD_BUTTON : MOD_Z4M_STORAGE_DOCUMENTS_UPLOAD_BUTTON;
$acceptFile = isset($isPhotoUpload) && $isPhotoUpload === TRUE ? ' accept="image/*"' : '';
$uploadButtonIcon = isset($isPhotoUpload) && $isPhotoUpload === TRUE ? 'fa-camera' : 'fa-folder-open-o';
?>
    <form data-download-url="<?php echo General::getURIforDownload('Z4MStorageCtrl'); ?>"
            data-remove-title="<?php echo MOD_Z4M_STORAGE_DOCUMENTS_REMOVE_BUTTON; ?>"
            data-remove-question="<?php echo MOD_Z4M_STORAGE_DOCUMENTS_REMOVE_QUESTION; ?>">
        <input type="file" name="files[]"
            multiple required<?php echo $acceptFile; ?>
            data-maxfilesize="<?php echo $maxFilesize; ?>"
            data-maxfilesize-error="<?php echo $maxFilesizeError; ?>"
            data-maxselectionsize="<?php echo $maxFileSelectionSize; ?>"
            data-maxselectionsize-error="<?php echo $maxFileSelectionSizeError; ?>"
            data-photo-maxwidth="<?php echo MOD_Z4M_STORAGE_MAX_PHOTO_WIDTH_IN_PIXELS; ?>"
            style="opacity:0;position:absolute">
        <button class="w3-button w3-block w3-section w3-theme-action w3-margin-bottom" type="button">
            <i class="fa <?php echo $uploadButtonIcon; ?> fa-lg"></i> <?php echo $uploadButtonLabel; ?>
        </button>
    </form>
