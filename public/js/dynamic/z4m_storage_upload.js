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
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * --------------------------------------------------------------------
 * ZnetDK 4 Mobile Storage module JS library
 *
 * File version: 1.3
 * Last update: 01/24/2026
 */

/* global z4m */
/**
 * File upload JS API
 */
class Z4M_StorageUpload {
    #containerEl
    #form
    #uploadButton
    #fileInput
    #fileContainer
    #noAttachmentTableRow
    #businessIdCallback
    #fileCountChangeCallback
    #storageSubdirectory
    #refreshHandler
    #withPhotoThumbnails
    #isReadOnly
    #isMultiDownload
    #multiDownloadButton
    #fileTemplate
    #noFileTemplate
    /**
     * Instantiates a new file upload object.
     * @param {string} containerSelector Selector of the container that embeds
     * the PHP view fragment ('upload_documents.php' or 'upload_photos.php').
     * @param {string} withPhotoThumbnails Value 'yes' or 'no'
     * @param {boolean} isReadOnly If true (false by default), the upload and
     * remove buttons are hidden.
     * @param {boolean} isMultiDownload If true (false by default), photos and
     * documents can be selected for download as a ZIP archive.
     * @returns {Z4M_StorageUpload} Instantiated object.
     */
    constructor(containerSelector, withPhotoThumbnails, isReadOnly, isMultiDownload) {
        this.#withPhotoThumbnails = withPhotoThumbnails;
        this.#isReadOnly = isReadOnly;
        this.#isMultiDownload = isMultiDownload;
        this.#storageSubdirectory = null;
        this.#containerEl = $(containerSelector + ' .z4m-storage-upload');
        this.#form = this.#containerEl.find('form');
        this.#uploadButton = this.#containerEl.find('form button');
        this.#fileInput = this.#containerEl.find('form input');
        this.#fileContainer = this.#containerEl.find('.file-container');
        this.#multiDownloadButton = this.#containerEl.find('.multidownload');
        // Handle events
        this.#handleEvents();
        // File templates initialized
        this.#initFileTemplates();
    }
    /**
     * File and no file templates are memorized in the object
     */
    #initFileTemplates() {
        this.#fileTemplate = this.#fileContainer.find('.file').clone();
        this.#fileTemplate.removeClass('w3-hide');
        this.#noFileTemplate = this.#fileContainer.find('.no-file').clone();
        this.#noFileTemplate.removeClass('w3-hide');
    }
    /**
     * The business identifier
     * @returns {int|null} The businness identifier
     */
    #getBusinessId() {
        return typeof this.#businessIdCallback === 'function'
            ? this.#businessIdCallback() : null;
    }
    /**
     * The title of the confirmation dialog displayed before file removal.
     * @returns {String} The remove title
     */
    #getRemoveTitle() {
        return $(this.#form).data('remove-title');
    }
    /**
     * The question displayed for file removal
     * @param {string} filename The name of the file to remove.
     * @returns {string} The question to display for confirmation
     */
    #getRemoveQuestion(filename) {
        return $(this.#form).data('remove-question').replace('%filename%', filename);
    }
    /*
     * The width of a photo after reduction.
     * @returns {int} Photo width in pixels.
     */
    #getMaxPhotoWidth() {
        return parseInt(this.#fileInput.data('photo-maxwidth'), 10);
    }
    /**
     * Handle UI events:
     * - Click on the file selction button,
     * - File selection change
     * - Click on the remove button of a file
     * - Click on the file to download it
     * If is read only, the Upload button is removed
     */
    #handleEvents() {
        const $this = this;
        if (this.#isReadOnly) {
            this.#uploadButton.remove();
            this.#fileContainer.find('.remove').remove();
        } else {
            // handle upload button click events...
            this.#uploadButton.on('click.Z4M_StorageUpload', function(){
                $this.#fileInput.val(''); // Bug fixing, selection reset
                $this.#hideFormError();
                $this.#fileInput.trigger('click');
            });
            // handle file selection
            this.#fileInput.on('change.Z4M_StorageUpload', function(){
                const selectedFiles = this.files;
                if (selectedFiles.length > 0) {
                    $this.#upload(selectedFiles);
                }
            });
            // Handle click remove document
            this.#fileContainer.on('click.Z4M_StorageUpload', '.remove', function(){
                const filename = $(this).closest('.file').find('.filename').text(),
                    documentId = $(this).closest('.file').data('id');
                z4m.messages.ask($this.#getRemoveTitle(), $this.#getRemoveQuestion(filename), null, function(isYes){
                    if (isYes) {
                        $this.#remove(documentId);
                    }
                });
            });
        }
        // Handle download document
        this.#fileContainer.on('click.Z4M_StorageUpload', '.download', function(event){
            $this.#download($(this));
            event.preventDefault();
        });
        // Handle Multi-upload
        if (this.#isMultiDownload) {
            // Handle Multi-upload button click events
            this.#multiDownloadButton.on('click.Z4M_StorageUpload', function(){
                $this.#multiDownload();
            });
            // Handle checkbox checked/unchecked events
            this.#fileContainer.on('change.Z4M_StorageUpload', 'input[type=checkbox]', function(){
                const selectedCount = $this.#fileContainer.find('input[type=checkbox]:checked').length;
                $this.#multiDownloadButton.prop('disabled', selectedCount < 2);
            });
        } else {
            this.#fileContainer.find('input[type=checkbox]').remove();
            this.#multiDownloadButton.remove();
        }
    }
    /**
     * Checks both:
     * - if each file size does not exceed the maximum allowed file size (see
     * 'upload_max_filesize' directive in php.ini).
     * - if the total size of the specified files does not exceed the
     * maximum allowed size (see 'post_max_size' directive in php.ini).
     * @param {FileList} files Selected files
     * @returns {Boolean} Value true if the total file size does not exceed the
     * maximum allowed size, false otherwise.
     */
    #checkSizeOfSelectedFiles(files) {
        const maxFileSize = parseInt(this.#fileInput.data('maxfilesize'), 10),
            maxFileSizeError = this.#fileInput.data('maxfilesize-error');
        let selectionSize = 0;
        for (const file of files) {
            if (file.size > maxFileSize) {
                this.#showFormError(maxFileSizeError.replace('%filename%', file.name));
                return false;
            }
            selectionSize += file.size;
        }
        const maxFileSelectionSize = parseInt(this.#fileInput.data('maxselectionsize'), 10),
            maxFileSelectionSizeError = this.#fileInput.data('maxselectionsize-error');
        if (selectionSize > maxFileSelectionSize) {
            this.#showFormError(maxFileSelectionSizeError.replace('%file_count%', files.length));
            return false;
        }
        return true;
    }
    /**
     * The URL for downloading the file
     * @returns {string} The URL without parameter.
     */
    #getDownloadUrl() {
        return $(this.#form).data('download-url');
    }
    /**
     * Download the specified file
     * @param {jQuery} clickedAnchor The clicked anchor element corresponding to
     * the file to download
     */
    #download(clickedAnchor) {
        const documentId = clickedAnchor.closest('.file').data('id');
        const url = this.#getDownloadUrl(),
            fullUrl = url + '&doc_id=' + encodeURIComponent(documentId);
        if (clickedAnchor.hasClass('modal')) {
            const alt = clickedAnchor.find('img').attr('alt');
            this.#showPhotoInModal(fullUrl, alt);
        } else {
            z4m.file.display(fullUrl);
        }
    }
    #showPhotoInModal(src, alt) {
        const $this = this, ver = this.#form.data('version');
        import('./z4m_storage_photo_viewer.min.js?v'+ver).then(function(module){
            const viewer = new module['Z4M_StoragePhotoViewer']($this.#fileContainer,
                $this.#getDownloadUrl());
            viewer.show(src, alt);
        }).catch(function(error) {
            console.error('Failed to load module Z4M_StoragePhotoViewer.', error);
            z4m.file.display(src);
        });
    }
    /**
     * Download the selected files
     */
    #multiDownload() {
        const selection = this.#fileContainer.find('input[type=checkbox]:checked'),
                docIds = [];
        selection.each(function(){
            const docId = $(this).closest('.file').data('id');
            docIds.push(docId);
        });
        if (docIds.length === 0) {
            console.error('No selected file to download.');
            return;
        }
        const url = this.#getDownloadUrl(),
            fullUrl = url + '&doc_ids=' + encodeURIComponent(docIds.join());
        z4m.file.display(fullUrl);
    }
    /**
     * Displays the specified error message in the form containing the file
     * input.
     * @param {string} message The error message.
     */
    #showFormError(message) {
        const formObj = z4m.form.make(this.#form);
        formObj.showError(message);
    }
    /**
     * Hides error displayed in the form containing the file input.
     */
    #hideFormError() {
        const formObj = z4m.form.make(this.#form);
        formObj.hideError();
    }
    /**
     * Reduces the size of the photo specified as File type
     * @param {File} photoAsFile The image to reduce
     * @return {Promise} Photo reduced to JPG format and of type File.
     * If reduction failed, the original photo is returned instead.
     */
    #reducePhotoSize(photoAsFile) {
        const maxWidth = this.#getMaxPhotoWidth(), maxHeight = maxWidth;
        return new Promise(function(resolve) {
            var img = new Image();
            img.src = window.URL.createObjectURL(photoAsFile);
            img.onload = function () {
                var MAX_WIDTH = maxWidth;
                var MAX_HEIGHT = maxHeight;
                var width = img.width;
                var height = img.height;
                if (width > MAX_WIDTH) {
                    height = height * (MAX_WIDTH / width);
                    width = MAX_WIDTH;
                }
                if (height > MAX_HEIGHT) {
                    width = width * (MAX_HEIGHT / height);
                    height = MAX_HEIGHT;
                }
                var canvas = document.createElement("canvas");
                canvas.width = width;
                canvas.height = height;
                var ctx = canvas.getContext("2d");
                ctx.drawImage(img, 0, 0, width, height);
                // Return resized image as Blog type in JPEG Format
                try {
                    canvas.toBlob(function(reducedImageAsBlob){
                        var reducedImageAsFile = null;
                        if (reducedImageAsBlob !== null) {
                            const newBasename = photoAsFile.name.substring(0,
                                photoAsFile.name.lastIndexOf('.')) || photoAsFile.name;
                            reducedImageAsFile = new File([reducedImageAsBlob],
                                newBasename + '.jpg', {
                                    lastModified: photoAsFile.lastModifiedDate,
                                    type: reducedImageAsBlob.type
                                }
                            );
                        }
                        resolve(reducedImageAsFile); // Reduced photo is returned
                    }, 'image/jpeg');
                } catch (err) {
                    console.error(err);
                    resolve(photoAsFile); // Original photo is returned
                }
                // Free memory
                window.URL.revokeObjectURL(this.src);
            };
        });
    }
    /**
     * Returns data fot the AJAX request about the files to upload: the business
     * identifier, the subdirectory where storing the files and if photo
     * thumbnails have to be returned by the web server.
     * @returns {FormData} The data for the AJAX request
     */
    #getAjaxRequestData() {
        const formData = new FormData();
        const businessId = this.#getBusinessId();
        if (businessId !== null) {
            formData.append('business_id', businessId);
        }
        if (this.#storageSubdirectory !== null) {
            formData.append('subdirectory', this.#storageSubdirectory);
        }
        formData.append('with_thumbnails', this.#withPhotoThumbnails);
        return formData;
    }
    /**
     * Upload the specified files to the web server for storage.
     * @param {FileList} files selected files to upload
     */
    async #upload(files) {
        const filesToUpload = [];
        if (typeof z4m.ajax.toggleLoader === 'function') {z4m.ajax.toggleLoader(true);}
        for (const file of files) {
            filesToUpload.push(file.type.startsWith("image/")
                ? await this.#reducePhotoSize(file) : file);
        }
        if (typeof z4m.ajax.toggleLoader === 'function') {z4m.ajax.toggleLoader(false);}
        if (!this.#checkSizeOfSelectedFiles(filesToUpload)) {
            return; // Uploaded aborted
        }
        const formData = this.#getAjaxRequestData();
        for (const file of filesToUpload) {
            formData.append('files[]', file);
        }
        this.#hideFormError();
        const $this = this;
        z4m.ajax.request({
            controller: 'Z4MStorageCtrl',
            action: 'upload',
            data: formData,
            callback: function(response) {
                if (response.success) {
                    $this.refresh(response.rows);
                    z4m.messages.showSnackbar(response.msg, false, $this.#containerEl);
                } else {
                    $this.#showFormError(response.msg);
                }
            }
        });
    }
    /**
     * Returns the list of files stored on the server side.
     * @param {function} callback Function that handles the display of the file
     * list.
     */
    #getTableRows(callback) {
        const $this = this;
        this.#hideFormError();
        z4m.ajax.request({
            controller: 'Z4MStorageCtrl',
            action: 'documents',
            data: this.#getAjaxRequestData(),
            callback: function(response) {
                if (response.success) {
                    callback(response.rows);
                } else {
                    $this.#showFormError(response.msg);
                }
            }
        });
    }
    /**
     * Removes on the server side the specified file
     * @param {int} documentId Identifier in the database of the file to remove.
     */
    #remove(documentId) {
        const $this = this;
        z4m.ajax.request({
            controller: 'Z4MStorageCtrl',
            action: 'remove',
            data: {doc_id: documentId, with_thumbnails: this.#withPhotoThumbnails},
            callback: function(response) {
                if (response.success) {
                    $this.refresh(response.rows);
                    z4m.messages.showSnackbar(response.msg, false, $this.#containerEl);
                } else {
                    $this.#showFormError(response.msg);
                }
            }
        });
    }
    /**
     * Sets the function to call back each time the file list is to display.
     * @param {function} refreshHandler Function that handles the file list
     * display.
     */
    _setRefreshHandler(refreshHandler) {
        this.#refreshHandler = refreshHandler;
    }
    /**
     * Sets the new file count for notification
     * @param {int} newCount The new file count.
     */
    notifyNewDocumentCount(newCount) {
        if (typeof this.#fileCountChangeCallback === 'function') {
            this.#fileCountChangeCallback(newCount);
        }
    }
    /**
     * Sets the function to call back each time the business identifier is
     * required for storing the uploaded files.
     * @param {function} callback Function called to get the business
     * identifier in return.
     */
    setBusinessIdCallback(callback) {
        this.#businessIdCallback = callback;
    }
    /**
     * Sets the function to call back when the file count has changed
     * @param {function} callback Function to call back. The function has one
     * argument giving the file count.
     */
    setFileCountChangeCallback(callback) {
        this.#fileCountChangeCallback = callback;
    }
    /**
     * Sets the subdirectory where selected files are stored.
     * @param {string} $subdirectory The name of the subdirectory
     */
    setStorageSubdirectory($subdirectory) {
        this.#storageSubdirectory = $subdirectory;
    }
    /**
     * Resets the file list
     */
    reset() {
        this.#fileContainer.empty();
        this.#multiDownloadButton.prop('disabled', true);
    }
    /**
     * Returns a new file template.
     * @returns {jQuery} The jQuery element of the file template.
     */
    _getNewFileTemplate() {
        return this.#fileTemplate.clone();
    }
    /**
     * Returns a new NO file template.
     * @returns {jQuery} The jQuery element of the NO file template.
     */
    _getNewNoFileTemplate() {
        return this.#noFileTemplate.clone();
    }
    /**
     * Returns the file container.
     * @returns {jQuery} The file container.
     */
    _getFileContainer() {
        return this.#fileContainer;
    }
    /**
     * Refresh the file list to display
     * @param {undefined|array} rows The files to display
     */
    refresh(rows) {
        const $this = this;
        if (rows === undefined) {
            this.#getTableRows(_refresh);
        } else {
            _refresh(rows);
        }
        function _refresh(rowsFound) {
            $this.reset();
            if (typeof $this.#refreshHandler === 'function') {
                $this.#refreshHandler.call($this, rowsFound);
            }
            $this.notifyNewDocumentCount(rowsFound.length);
        }
    }
}
/**
 * Document upload JS API
 */
class Z4M_StorageDocumentUpload extends Z4M_StorageUpload {
    static #documentTemplate = null;
    static #noDocumentTemplate = null;
    /**
     * Instantiates a new document upload object.
     * @param {string} containerSelector the selector of the HTML element
     * containing the 'upload_documents.php' view fragment.
     * @param {boolean} isReadOnly If true (false by default), the upload and
     * remove buttons are hidden.
     * @param {boolean} isMultiDownload If true (false by default), documents can
     * be selected for download as a ZIP archive.
     * @returns {Z4M_StorageDocumentUpload} The upload object
     */
    constructor(containerSelector, isReadOnly = false, isMultiDownload = false) {
        super(containerSelector, 'no', isReadOnly, isMultiDownload);
        this._setRefreshHandler(this.#_refresh);
    }
    /**
     * Displays the uploaded documents in an HTML table
     * @param {FileList} files The documents to display.
     */
    #_refresh(files) {
        for (const file of files) {
            let newFile = this._getNewFileTemplate();
            newFile.attr('data-id', file.id);
            newFile.find('.datetime').html(file.upload_datetime_locale);
            newFile.find('.filename').html(file.original_basename);
            newFile.find('.filesize').html(file.filesize_display);
            this._getFileContainer().append(newFile);
        }
        if (files.length === 0) {
            this._getFileContainer().append(this._getNewNoFileTemplate());
        }
    }
}
/**
 * Photo upload API
 */
class Z4M_StoragePhotoUpload extends Z4M_StorageUpload {
    static #photoTemplate = null;
    static #noPhotoTemplate = null;
    /**
     * Instantiates a new photo upload object.
     * @param {string} containerSelector the selector of the HTML element
     * containing the 'upload_photos.php' view fragment.
     * @param {boolean} isReadOnly If true (false by default), the upload and
     * remove buttons are hidden.
     * @param {boolean} isMultiDownload If true (false by default), photos can be
     * selected for download as a ZIP archive.
     * @returns {Z4M_StoragePhotoUpload} The upload object
     */
    constructor(containerSelector, isReadOnly = false, isMultiDownload = false) {
        super(containerSelector, 'yes', isReadOnly, isMultiDownload);
        this._setRefreshHandler(this.#_refresh);
    }
    /**
     * Displays the uploaded photos
     * @param {FileList} files The photos to display.
     */
    #_refresh(files) {
        for (const file of files) {
            let newFile = this._getNewFileTemplate();
            newFile.addClass('w3-show-inline-block');
            newFile.attr('data-id', file.id);
            newFile.find('.datetime').html(file.upload_datetime_locale);
            newFile.find('.filename').html(file.original_basename);
            newFile.find('.filename').attr('title', file.original_basename);
            newFile.find('.filesize').html(file.filesize_display);
            if (file.thumbnail !== false) {
                newFile.find('img').attr('src', file.thumbnail);
                newFile.find('img').attr('alt', file.original_basename);
            } else { // No thumbnail
                newFile.find('img').addClass('w3-hide');
                newFile.find('.no-thumbnail').removeClass('w3-hide');
            }
            this._getFileContainer().append(newFile);
        }
        if (files.length === 0) {
            this._getFileContainer().append(this._getNewNoFileTemplate());
        }
    }
}
export {Z4M_StorageDocumentUpload, Z4M_StoragePhotoUpload};
