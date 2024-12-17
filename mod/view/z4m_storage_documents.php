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
 * ZnetDK 4 Mobile Storage module view
 *
 * File version: 1.0
 * Last update: 12/02/2024
 */
$color = defined('CFG_MOBILE_W3CSS_THEME_COLOR_SCHEME')
        ? CFG_MOBILE_W3CSS_THEME_COLOR_SCHEME
        : ['filter_bar' => 'w3-theme', 'content' => 'w3-theme-light',
            'list_border_bottom' => 'w3-border-theme', 'btn_action' => 'w3-theme-action',
            'icon' => 'w3-text-theme', 'tag' => 'w3-theme',   'msg_error' => 'w3-red'];

use \z4m_storage\mod\DocumentManager;
?>
<style>
    #z4m-storage-documents-list-filter input,
    #z4m-storage-documents-list-filter select {
        height: 44px;
    }
    #z4m-storage-documents-list-filter input[name=total_size] {
        width: 90px;
    }
    #z4m-storage-documents-list-header {
        position: sticky;
    }
    #z4m-storage-documents-list-header li {
        padding-top: 0;
        padding-bottom: 0;
    }
</style>
<!-- Filter by dates and status -->
<form id="z4m-storage-documents-list-filter" class="w3-padding w3-panel <?php echo $color['filter_bar']; ?>">

    <div class="w3-bar w3-stretch">
        <div class="w3-bar-item">
            <label>
                <b><i class="fa fa-calendar"></i>&nbsp;<?php echo MOD_Z4M_STORAGE_LIST_FILTER_PERIOD_START; ?></b>
                <input class="w3-input" type="date" name="start_filter">
            </label>
        </div>
        <div class="w3-bar-item">
            <label>
                <b><i class="fa fa-calendar"></i>&nbsp;<?php echo MOD_Z4M_STORAGE_LIST_FILTER_PERIOD_END; ?></b>
                <input class="w3-input" type="date" name="end_filter">
            </label>
        </div>

        <div class="w3-bar-item">
            <label>
                <b><i class="fa fa-folder-open-o"></i>&nbsp;<?php echo MOD_Z4M_STORAGE_LIST_FILTER_SUBDIRECTORY_LABEL; ?></b>
                <select class="w3-padding w3-show-block" name="subdirectory">
                    <option value=""><?php echo MOD_Z4M_STORAGE_LIST_FILTER_OPTION_CHOOSE_LABEL; ?></option>
<?php $subdirs = DocumentManager::getSubfolders(TRUE, TRUE);
 foreach ($subdirs as $subdirectory) : ?>
                    <option value="<?php echo $subdirectory; ?>"><?php echo $subdirectory; ?></option>
<?php endforeach; ?>
                </select>
            </label>
        </div>
        <div class="w3-bar-item">
            <label>
                <b><i class="fa fa-file-o"></i>&nbsp;<?php echo MOD_Z4M_STORAGE_LIST_FILTER_FILE_EXTENSION_LABEL; ?></b>
                <select class="w3-padding w3-show-block" name="file_extension">
                    <option value=""><?php echo MOD_Z4M_STORAGE_LIST_FILTER_OPTION_CHOOSE_LABEL; ?></option>
<?php $fileExtensions = DocumentManager::getStoredDocumentFileExtensions();
 foreach ($fileExtensions as $fileExt) : ?>
                    <option value="<?php echo $fileExt; ?>"><?php echo $fileExt; ?></option>
<?php endforeach; ?>
                </select>
            </label>
        </div>
        <div class="w3-bar-item">
            <label>
                <b><i class="fa fa-cube"></i>&nbsp;<?php echo MOD_Z4M_STORAGE_LIST_FILTER_FILE_SIZE_LABEL; ?></b>
                <select class="w3-padding w3-show-block" name="file_size">
                    <option value=""><?php echo MOD_Z4M_STORAGE_LIST_FILTER_OPTION_CHOOSE_LABEL; ?></option>
<?php $fileSizes = DocumentManager::getFilterFileSizes();
 foreach ($fileSizes as $size) : ?>
                    <option value="<?php echo $size['bytes']; ?>">&gt; <?php echo $size['display_size']; ?></option>
<?php endforeach; ?>
                </select>
            </label>
        </div>
        <div class="w3-bar-item">
            <label>
                <b><?php echo MOD_Z4M_STORAGE_USED_TOTAL_LABEL; ?></b>
                <input class="w3-input w3-center" name="total_size" readonly>
            </label>
        </div>
        <div class="w3-bar-item">
            <div>&nbsp;</div>
            <button class="purge w3-button <?php echo $color['btn_action']; ?>"
                type="button" data-confirmation="<?php echo MOD_Z4M_STORAGE_PURGE_CONFIRMATION_TEXT; ?>">
            <i class="fa fa-trash fa-lg"></i> <?php echo MOD_Z4M_STORAGE_PURGE_BUTTON_LABEL; ?>
            </button>
        </div>
    </div>
</form>
<!-- Header -->
<div id="z4m-storage-documents-list-header" class="w3-row <?php echo $color['content']; ?> w3-hide-small w3-border-bottom <?php echo $color['list_border_bottom']; ?>">
    <div class="w3-col m3 l3 w3-padding-small"><b><?php echo MOD_Z4M_STORAGE_DOCUMENTS_FILENAME_LABEL; ?></b></div>
    <div class="w3-col m2 l2 w3-padding-small"><b><?php echo MOD_Z4M_STORAGE_DOCUMENTS_UPLOAD_DATE_LABEL; ?></b></div>
    <div class="w3-col m3 l3 w3-padding-small"><b><?php echo MOD_Z4M_STORAGE_DOCUMENTS_USER_LABEL; ?></b></div>
    <div class="w3-col m2 l2 w3-padding-small"><b><?php echo MOD_Z4M_STORAGE_DOCUMENTS_SUBDIRECTORY_LABEL; ?></b></div>
    <div class="w3-col m2 l2 w3-padding-small w3-right-align"><b><?php echo MOD_Z4M_STORAGE_DOCUMENTS_FILESIZE_LABEL; ?></b></div>

</div>
<!-- Data List -->
<ul id="z4m-storage-documents-list" class="w3-ul w3-hide w3-margin-bottom" data-zdk-load="Z4MStorageCtrl:all">
    <li class="<?php echo $color['list_border_bottom']; ?> w3-hover-light-grey" data-id="{{id}}">
        <div class="w3-row w3-stretch">
            <div class="w3-col s12 l3 m3 w3-padding-small">
                <b>{{original_basename}}</b>
            </div>
            <div class="w3-col s12 l2 m2 w3-padding-small">
                <i class="fa fa-calendar w3-hide-medium w3-hide-large"></i>
                {{upload_datetime_locale}}
            </div>
            <div class="w3-col s12 l3 m3 w3-padding-small">
                <i class="fa fa-user w3-hide-medium w3-hide-large"></i>
                {{username}}
            </div>
            <div class="w3-col s8 l2 m2 w3-padding-small">
                <i class="fa fa-folder-open-o w3-hide-medium w3-hide-large"></i>
                {{subdirectory}}
            </div>
            <div class="w3-col s4 l2 m2 w3-padding-small w3-right-align"><span class="w3-tag w3-theme">{{filesize_display}}</span></div>
        </div>
    </li>
    <li><h3 class="<?php echo $color['msg_error']; ?> w3-center w3-stretch"><i class="fa fa-frown-o"></i>&nbsp;<?php echo MOD_Z4M_STORAGE_DOCUMENTS_EMPTY; ?></h3></li>
</ul>
<script>
<?php if (CFG_DEV_JS_ENABLED) : ?>
    console.log("'z4m_storage_documents.php' ** For debug purpose **");
<?php endif; ?>
    $(function(){
        const filterSelector = '#z4m-storage-documents-list-filter';
        var documentList = z4m.list.make('#z4m-storage-documents-list', false, false);
        documentList.beforeSearchRequestCallback = function(requestData) {
            const JSONFilters = getFilterCriteria();
            if (JSONFilters !== null) {
                requestData.search_criteria = JSONFilters;
            }
            $(filterSelector + ' input[name=total_size]').val(0);
        };
        documentList.loadedCallback = function(rowCount, pageNumber) {
            const purgeBtn = $(filterSelector + ' button.purge');
            purgeBtn.prop('disabled', rowCount === 0 && pageNumber === 1);
        };
        documentList.beforeInsertRowCallback = function(rowData) {
            if (rowData.hasOwnProperty('total_size')) {
                $(filterSelector + ' input[name=total_size]').val(rowData.total_size);
            }
        }
        function getFilterCriteria() {
            const filterForm = z4m.form.make(filterSelector),
                startDate = filterForm.getInputValue('start_filter'),
                endDate = filterForm.getInputValue('end_filter'),
                subdirectory = filterForm.getInputValue('subdirectory'),
                fileExtension = filterForm.getInputValue('file_extension'),
                fileSize = filterForm.getInputValue('file_size'),
                filters = {};
            if (startDate !== '') {
                filters.start = startDate;
            }
            if (endDate !== '') {
                filters.end = endDate;
            }
            if (subdirectory !== '') {
                filters.subdirectory = subdirectory;
            }
            if (fileExtension !== '') {
                filters.file_extension = fileExtension;
            }
            if (fileSize !== '') {
                filters.file_size = fileSize;
            }
            if (Object.keys(filters).length > 0) {
                return JSON.stringify(filters);
            }
            return null;
        }
        // Filter change events
        $(filterSelector + ' input, '+ filterSelector + ' select').on('change.z4m_storage', function(){
            if ($(this).attr('name') === 'start_filter') {
                const startDate = new Date($(this).val()),
                    endDateEl = $(filterSelector + ' input[name=end_filter]'),
                    endDate = new Date(endDateEl.val());
                if (startDate > endDate) {
                    endDateEl.val($(this).val());
                }
            } else if ($(this).attr('name') === 'end_filter') {
                const endDate = new Date($(this).val()),
                    startDateEl = $(filterSelector + ' input[name=start_filter]'),
                    startDate = new Date(startDateEl.val());
                if (startDate > endDate) {
                    startDateEl.val($(this).val());
                }
            }
            documentList.refresh();
        });
        // Purge button click events
        $(filterSelector + ' button.purge').on('click.z4m_storage', function(){
            z4m.messages.ask($(this).text(), $(this).data('confirmation'), null, function(isOK){
                if(!isOK) {
                    return;
                }
                const requestObj = {
                    controller: 'Z4MStorageCtrl',
                    action: 'purge',
                    callback(response) {
                        if (response.success) {
                            documentList.refresh();
                            z4m.messages.showSnackbar(response.msg);
                        }
                    }
                };
                const JSONFilters = getFilterCriteria();
                if (JSONFilters !== null) {
                    requestObj.data = {search_criteria: JSONFilters};
                }
                z4m.ajax.request(requestObj);
            });
        });
    });
</script>