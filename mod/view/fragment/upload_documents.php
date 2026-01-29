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
 * Last update: 01/22/2026
 */
?>
<div class="z4m-storage-upload">
<?php $isPhotoUpload = FALSE; require 'upload_common.php'; ?>
    <div class="w3-responsive">
        <table class="w3-table-all w3-margin-bottom">
            <thead>
                <tr>
                    <th><?php echo MOD_Z4M_STORAGE_DOCUMENTS_UPLOAD_DATE_LABEL; ?></th>
                    <th><?php echo MOD_Z4M_STORAGE_DOCUMENTS_FILENAME_LABEL; ?></th>
                    <th><?php echo MOD_Z4M_STORAGE_DOCUMENTS_FILESIZE_LABEL; ?></th>
                    <th></th>
                </tr>
            </thead>
            <tbody class="file-container">
                <tr class="file w3-hide" data-id="0">
                    <td class="datetime"></td>
                    <td>
                        <input class="w3-check" type="checkbox">
                        <a class="filename download" href="#" title="<?php echo MOD_Z4M_STORAGE_DOCUMENTS_DOWNLOAD_LINK; ?>"></a>
                    </td>
                    <td class="filesize"></td>
                    <td>
                        <button class="remove w3-button w3-theme-action" title="<?php echo MOD_Z4M_STORAGE_DOCUMENTS_REMOVE_BUTTON; ?>">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
                <tr class="no-file w3-hide">
                    <td class="w3-center" colspan="4"><i><?php echo MOD_Z4M_STORAGE_DOCUMENTS_EMPTY; ?></i></td>
                </tr>
            </tbody>
        </table>
    </div>
    <button class="multidownload w3-button w3-theme-action" type="button">
        <i class="fa fa-file-archive-o fa-lg"></i>
        <?php echo MOD_Z4M_STORAGE_DOWNLOAD_BUTTON_LABEL; ?>
    </button>
</div>