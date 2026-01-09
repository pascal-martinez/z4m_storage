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
 * Last update: 01/09/2026
 */
$thumbnailWidth = MOD_Z4M_STORAGE_MAX_PHOTO_THUMBNAIL_WIDTH_IN_PIXELS;
$downloadIcon = MOD_Z4M_STORAGE_SHOW_PHOTO_IN_MODAL ? 'fa-eye' : 'fa-download';
$downloadTitle = MOD_Z4M_STORAGE_SHOW_PHOTO_IN_MODAL ? MOD_Z4M_STORAGE_DOCUMENTS_DISPLAY_LINK : MOD_Z4M_STORAGE_DOCUMENTS_DOWNLOAD_LINK;
$downloadModalCls = MOD_Z4M_STORAGE_SHOW_PHOTO_IN_MODAL ? ' modal' : '';
?>
<style>
    .z4m-storage-upload.photos .file {
        width: <?php echo $thumbnailWidth; ?>px;
    }
    .z4m-storage-upload.photos .no-thumbnail {
        width: <?php echo $thumbnailWidth; ?>px;
        height:<?php echo $thumbnailWidth; ?>px;
    }
    .z4m-storage-upload.photos .download .icon {
        padding: 6px;
    }
    .z4m-storage-upload.photos .remove {
        cursor: pointer;
    }
    .z4m-storage-upload.photos .filename {
        width: 100%;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }
</style>
<div class="z4m-storage-upload photos">
<?php $isPhotoUpload = TRUE; require 'upload_common.php'; ?>
    <div class="file-container w3-bar w3-section">
        <div class="file w3-margin-right w3-hide" data-id="0">
            <div class="w3-display-container w3-tooltip">
                <a class="download<?php echo $downloadModalCls; ?>" href="#" title="<?php echo $downloadTitle; ?>">
                    <div class="no-thumbnail w3-center w3-xxlarge w3-white w3-hide">
                        <span class="fa-stack w3-padding-24">
                            <i class="fa fa-camera fa-stack-1x"></i>
                            <i class="fa fa-ban fa-stack-2x w3-text-red"></i>
                        </span>
                    </div>
                    <span class="icon w3-text w3-display-topmiddle w3-badge w3-opacity">
                        <i class="fa <?php echo $downloadIcon; ?> fa-lg fa-fw w3-text-white"></i>
                    </span>
                    <img class="w3-image" src="" alt="">
                </a>
                <div class="w3-display-bottomleft w3-padding-small w3-white">
                    <a class="remove w3-text-red w3-hover-opacity"
                          title="<?php echo MOD_Z4M_STORAGE_DOCUMENTS_REMOVE_BUTTON; ?>">
                        <i class="fa fa-trash fa-lg"></i>
                    </a>
                    <span class="filesize"></span>
                </div>
            </div>
            <div class="filename w3-padding-small w3-white w3-border-top w3-border-theme w3-small" title=""></div>
        </div>
        <div class="no-file w3-center w3-hide"><i><?php echo MOD_Z4M_STORAGE_PHOTOS_EMPTY; ?></i></div>
    </div>
</div>