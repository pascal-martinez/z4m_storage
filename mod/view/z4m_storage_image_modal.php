<?php

/* 
 * ZnetDK, Starter Web Application for rapid & easy development
 * See official website https://www.znetdk.fr
 * Copyright (C) 2026 Pascal MARTINEZ (contact@znetdk.fr)
 * License GNU GPL http://www.gnu.org/licenses/gpl-3.0.html GNU GPL
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
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * --------------------------------------------------------------------
 * ZnetDK 4 Mobile Storage module view
 * 
 * File version: 1.1
 * Last update: 01/24/2026
 */
?>
<div id="z4m-storage-image-modal" class="w3-modal" style="padding-top:0">
    <div class="w3-modal-content w3-black w3-display-container w3-animate-opacity" style="width:100%;height:100vh;margin:0">
        <template class="image-tpl" data-error-title="<?php echo LC_MSG_CRI_ERR_SUMMARY; ?>" data-error-msg="<?php echo MOD_Z4M_STORAGE_DOCUMENTS_ERROR_DOWNLOAD_NOT_EXISTS; ?>">
            <img class="w3-image w3-display-middle" style="width:100%">
        </template>
        <header>
            <a class="close w3-button w3-padding w3-display-topright w3-opacity w3-xlarge w3-grey" href="javascript:void(0)" aria-label="<?php echo LC_BTN_CLOSE; ?>">
                <i class="fa fa-times-circle fa-lg" aria-hidden="true" title="<?php echo LC_BTN_CLOSE; ?>"></i>
            </a>
        </header>
        <a class="prev w3-button w3-padding w3-display-left w3-opacity w3-xlarge w3-grey" href="javascript:void(0)" aria-label="<?php echo MOD_Z4M_STORAGE_PHOTOS_PREVIOUS_BUTTON; ?>">
            <i class="fa fa-arrow-circle-left fa-lg" aria-hidden="true" title="<?php echo MOD_Z4M_STORAGE_PHOTOS_PREVIOUS_BUTTON; ?>"></i>
        </a>
        <a class="next w3-button w3-padding w3-display-right w3-opacity w3-xlarge w3-grey" href="javascript:void(0)" aria-label="<?php echo MOD_Z4M_STORAGE_PHOTOS_NEXT_BUTTON; ?>">
            <i class="fa fa-arrow-circle-right fa-lg" aria-hidden="true" title="<?php echo MOD_Z4M_STORAGE_PHOTOS_NEXT_BUTTON; ?>"></i>
        </a>
    </div>
</div>
