class Z4M_StorageUpload{#containerEl
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
fileTemplate
noFileTemplate
constructor(containerSelector,withPhotoThumbnails){this.#withPhotoThumbnails=withPhotoThumbnails;this.#storageSubdirectory=null;this.#containerEl=$(containerSelector+' .z4m-storage-upload');this.#form=this.#containerEl.find('form');this.#uploadButton=this.#containerEl.find('form button');this.#fileInput=this.#containerEl.find('form input');this.#fileContainer=this.#containerEl.find('.file-container');this.fileTemplate=this.getFileContainer().find('.file').clone();this.fileTemplate.removeClass('w3-hide');this.noFileTemplate=this.getFileContainer().find('.no-file').clone();this.noFileTemplate.removeClass('w3-hide');this.#handleEvents()}
#getBusinessId(){return typeof this.#businessIdCallback==='function'?this.#businessIdCallback():null}
#getRemoveTitle(){return $(this.#form).data('remove-title')}
#getRemoveQuestion(filename){return $(this.#form).data('remove-question').replace('%filename%',filename)}
#getMaxPhotoWidth(){return parseInt(this.#fileInput.data('photo-maxwidth'),10)}
#handleEvents(){const $this=this;this.#uploadButton.on('click.Z4M_StorageUpload',function(){$this.#fileInput.val('');$this.#hideFormError();$this.#fileInput.trigger('click')});this.#fileInput.on('change.Z4M_StorageUpload',function(){const selectedFiles=this.files;if(selectedFiles.length>0){$this.#upload(selectedFiles)}});this.#fileContainer.on('click.Z4M_StorageUpload','.remove',function(){const filename=$(this).closest('.file').find('.filename').text(),documentId=$(this).closest('.file').data('id');z4m.messages.ask($this.#getRemoveTitle(),$this.#getRemoveQuestion(filename),null,function(isYes){if(isYes){$this.#remove(documentId)}})});this.#fileContainer.on('click.Z4M_StorageUpload','.download',function(event){$this.#download($(this));event.preventDefault()})}
#checkSizeOfSelectedFiles(files){const maxFileSize=parseInt(this.#fileInput.data('maxfilesize'),10),maxFileSizeError=this.#fileInput.data('maxfilesize-error');let selectionSize=0;for(const file of files){if(file.size>maxFileSize){this.#showFormError(maxFileSizeError.replace('%filename%',file.name));return!1}
selectionSize+=file.size}
const maxFileSelectionSize=parseInt(this.#fileInput.data('maxselectionsize'),10),maxFileSelectionSizeError=this.#fileInput.data('maxselectionsize-error');if(selectionSize>maxFileSelectionSize){this.#showFormError(maxFileSelectionSizeError.replace('%file_count%',files.length));return!1}
return!0}
#getDownloadUrl(){return $(this.#form).data('download-url')}
#download(clickedAnchor){const documentId=clickedAnchor.closest('.file').data('id');const url=this.#getDownloadUrl();z4m.file.display(url+'&doc_id='+encodeURIComponent(documentId))}
#showFormError(message){const formObj=z4m.form.make(this.#form);formObj.showError(message)}
#hideFormError(){const formObj=z4m.form.make(this.#form);formObj.hideError()}
#reducePhotoSize(photoAsFile){const maxWidth=this.#getMaxPhotoWidth(),maxHeight=maxWidth;return new Promise(function(resolve){var img=new Image();img.src=window.URL.createObjectURL(photoAsFile);img.onload=function(){var MAX_WIDTH=maxWidth;var MAX_HEIGHT=maxHeight;var width=img.width;var height=img.height;if(width>MAX_WIDTH){height=height*(MAX_WIDTH/width);width=MAX_WIDTH}
if(height>MAX_HEIGHT){width=width*(MAX_HEIGHT/height);height=MAX_HEIGHT}
var canvas=document.createElement("canvas");canvas.width=width;canvas.height=height;var ctx=canvas.getContext("2d");ctx.drawImage(img,0,0,width,height);try{canvas.toBlob(function(reducedImageAsBlob){var reducedImageAsFile=null;if(reducedImageAsBlob!==null){const newBasename=photoAsFile.name.substring(0,photoAsFile.name.lastIndexOf('.'))||photoAsFile.name;reducedImageAsFile=new File([reducedImageAsBlob],newBasename+'.jpg',{lastModified:photoAsFile.lastModifiedDate,type:reducedImageAsBlob.type})}
resolve(reducedImageAsFile)},'image/jpeg')}catch(err){console.error(err);resolve(photoAsFile)}
window.URL.revokeObjectURL(this.src)}})}
#getAjaxRequestData(){const formData=new FormData();const businessId=this.#getBusinessId();if(businessId!==null){formData.append('business_id',businessId)}
if(this.#storageSubdirectory!==null){formData.append('subdirectory',this.#storageSubdirectory)}
formData.append('with_thumbnails',this.#withPhotoThumbnails);return formData}
async #upload(files){const filesToUpload=[];if(typeof z4m.ajax.toggleLoader==='function'){z4m.ajax.toggleLoader(!0)}
for(const file of files){filesToUpload.push(file.type.startsWith("image/")?await this.#reducePhotoSize(file):file)}
if(typeof z4m.ajax.toggleLoader==='function'){z4m.ajax.toggleLoader(!1)}
if(!this.#checkSizeOfSelectedFiles(filesToUpload)){return}
const formData=this.#getAjaxRequestData();for(const file of filesToUpload){formData.append('files[]',file)}
this.#hideFormError();const $this=this;z4m.ajax.request({controller:'Z4MStorageCtrl',action:'upload',data:formData,callback:function(response){if(response.success){$this.refresh(response.rows);z4m.messages.showSnackbar(response.msg,!1,$this.#containerEl)}else{$this.#showFormError(response.msg)}}})}
#getTableRows(callback){const $this=this;this.#hideFormError();z4m.ajax.request({controller:'Z4MStorageCtrl',action:'documents',data:this.#getAjaxRequestData(),callback:function(response){if(response.success){callback(response.rows)}else{$this.#showFormError(response.msg)}}})}
#remove(documentId){const $this=this;z4m.ajax.request({controller:'Z4MStorageCtrl',action:'remove',data:{doc_id:documentId,with_thumbnails:this.#withPhotoThumbnails},callback:function(response){if(response.success){$this.refresh(response.rows);z4m.messages.showSnackbar(response.msg,!1,$this.#containerEl)}else{$this.#showFormError(response.msg)}}})}
setRefreshHandler(refreshHandler){this.#refreshHandler=refreshHandler}
notifyNewDocumentCount(newCount){if(typeof this.#fileCountChangeCallback==='function'){this.#fileCountChangeCallback(newCount)}}
setBusinessIdCallback(callback){this.#businessIdCallback=callback}
setFileCountChangeCallback(callback){this.#fileCountChangeCallback=callback}
setStorageSubdirectory($subdirectory){this.#storageSubdirectory=$subdirectory}
reset(){this.#fileContainer.empty()}
getFileContainer(){return this.#fileContainer}
refresh(rows){const $this=this;if(rows===undefined){this.#getTableRows(_refresh)}else{_refresh(rows)}
function _refresh(rowsFound){$this.reset();if(typeof $this.#refreshHandler==='function'){$this.#refreshHandler.call($this,rowsFound)}
$this.notifyNewDocumentCount(rowsFound.length)}}}
class Z4M_StorageDocumentUpload extends Z4M_StorageUpload{constructor(containerSelector){super(containerSelector,'no');this.setRefreshHandler(this.#_refresh)}
#_refresh(files){for(const file of files){let newFile=this.fileTemplate.clone();newFile.attr('data-id',file.id);newFile.find('.datetime').html(file.upload_datetime_locale);newFile.find('.filename').html(file.original_basename);newFile.find('.filesize').html(file.filesize_display);this.getFileContainer().append(newFile)}
if(files.length===0){this.getFileContainer().append(this.noFileTemplate.clone())}}}
class Z4M_StoragePhotoUpload extends Z4M_StorageUpload{constructor(containerSelector){super(containerSelector,'yes');this.setRefreshHandler(this.#_refresh)}
#_refresh(files){for(const file of files){let newFile=this.fileTemplate.clone();newFile.addClass('w3-show-inline-block');newFile.attr('data-id',file.id);newFile.find('.datetime').html(file.upload_datetime_locale);newFile.find('.filename').html(file.original_basename);newFile.find('.filename').attr('title',file.original_basename);newFile.find('.filesize').html(file.filesize_display);if(file.thumbnail!==!1){newFile.find('img').attr('src',file.thumbnail);newFile.find('img').attr('alt',file.original_basename)}else{newFile.find('img').addClass('w3-hide');newFile.find('.no-thumbnail').removeClass('w3-hide')}
this.getFileContainer().append(newFile)}
if(files.length===0){this.getFileContainer().append(this.noFileTemplate.clone())}}}
export{Z4M_StorageDocumentUpload,Z4M_StoragePhotoUpload}