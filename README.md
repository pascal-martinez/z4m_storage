# ZnetDK 4 Mobile module: Storage (z4m_storage)
The **z4m_storage** module simplifies the loading and storage of documents and photos in the application. It includes:
- A Javascript API and view fragments to select one or more files on the user's disk, transmit them to the hosting and preview them once stored.
- A PHP API to store and query the files uploaded by the user.
- A view to consult the disk space consumed by the files uploaded to the hosting.
- A view to delete the files uploaded to the hosting according to the chosen criteria (storage period, storage subdirectory, file extension and size).

## FEATURES
- Display of the *disk space used* by the App's database and by the stored documents and photos.

![Disk space used view provided by the ZnetDK 4 Mobile 'z4m_storage' module](https://mobile.znetdk.fr/applications/default/public/images/modules/z4m_storage/screenshot1.png)

- *Purge of the documents and photos* according to the specified period, subdirectory, file extension and file size.

![Documents view provided by the ZnetDK 4 Mobile 'z4m_storage' module](https://mobile.znetdk.fr/applications/default/public/images/modules/z4m_storage/screenshot2.png)

- PHP View templates and JavaScript API to upload documents and photos.
- Blocking downloading of documents beyond the maximum authorized storage space (see [`MOD_Z4M_STORAGE_MAX_SPACE_IN_BYTES`](#user-content-z4m-max-space)).
- Values of the php.ini `post_max_size` and `upload_max_filesize` directives taken in account before uploading documents.
- Limitation of the uploaded file size through the [`MOD_Z4M_STORAGE_MAX_UPLOAD_FILESIZE_IN_BYTES`](#user-content-z4m-max-upload-filesize) PHP constant
to apply a limit lower than the one set for the `upload_max_filesize` directive.
- Photos automatically reduced before upload (see [`MOD_Z4M_STORAGE_MAX_PHOTO_WIDTH_IN_PIXELS`](#user-content-z4m-max-photo-width)).
- Photo thumbnail display for preview (see [`MOD_Z4M_STORAGE_MAX_PHOTO_THUMBNAIL_WIDTH_IN_PIXELS`](#user-content-z4m-max-thumbnail-width)).
- Upload capability restricted to users having access to the specified views (see [`MOD_Z4M_STORAGE_DOCUMENT_MANAGEMENT_VIEWS_ALLOWED`](#user-content-z4m-views-allowed)).

## CONCEPTS
### File storage location in ZnetDK 4 Mobile
In a [ZnetDK 4 Mobile](/../../../znetdk4mobile) application, documents and photos uploaded by users are generally stored within the `documents` directory (see [Stored documents](https://mobile.znetdk.fr/settings#z4m-settings-stored-documents)).

The **z4m_storage** module propose to store uploaded files in a dedicated **subdirectory** for each managed *business object*.   
For example, uploaded files for the *Customer* business object could be stored in a subdirectory named `customer\`, the others uploaded for the *Invoice* business object could be stored in a subdirectory named `invoice\`.

When a file is uploaded through the **z4m_storage** module, the **subdirectory** can be specified to store the file in the appropriate subfolder.

### File index stored in database
When a file is stored by the **z4m_storage** module, a record is saved in the SQL table `z4m_documents`.
This record allows in particular to know the user at the origin of the file upload and to associate with it an identifier of the *business object* to which it is linked (for example `73` which is the identifier of the *Customer* for which the file was uploaded).

When a file is uploaded through the **z4m_storage** module, the **business ID** can be specified to link the file to the appropriate business identifier.

### Photo reduction before upload
Before uploading a photo (jpeg or png file), the **z4m_storage** module reduces it automatically to the number of pixels specified by the PHP constant [`MOD_Z4M_STORAGE_MAX_PHOTO_WIDTH_IN_PIXELS`](#user-content-z4m-max-photo-width).   
This saves both network bandwidth during file transfer and disk space for storing it on the web hosting.

## LICENCE
This module is published under the version 3 of GPL General Public Licence.

## REQUIREMENTS
- [ZnetDK 4 Mobile](/../../../znetdk4mobile) version 2.9 or higher,
- A **MySQL** database [is configured](https://mobile.znetdk.fr/getting-started#z4m-gs-connect-config) to store the application data,
- **PHP version 7.4** or higher,
- Authentication is enabled
([`CFG_AUTHENT_REQUIRED`](https://mobile.znetdk.fr/settings#z4m-settings-auth-required)
is `TRUE` in the App's
[`config.php`](/../../../znetdk4mobile/blob/master/applications/default/app/config.php)).
- The php.ini `file_uploads` directive is set to `true`.

## INSTALLATION
1. Add a new subdirectory named `z4m_storage` within the
[`./engine/modules/`](/../../../znetdk4mobile/tree/master/engine/modules/) subdirectory of your
ZnetDK 4 Mobile starter App,
2. Copy module's code in the new `./engine/modules/z4m_storage/` subdirectory,
or from your IDE, pull the code from this module's GitHub repository,
3. Edit the App's [`menu.php`](/../../../znetdk4mobile/blob/master/applications/default/app/menu.php)
located in the [`./applications/default/app/`](/../../../znetdk4mobile/tree/master/applications/default/app/)
subfolder and include the [`menu.inc`](mod/menu.inc) script to add menu item definition for the
`z4m_storage_stats` and `z4m_storage_documents` views.
```php
require ZNETDK_MOD_ROOT . '/z4m_storage/mod/menu.inc';
```
4. Create a `documents` folder within the [`./applications/default/`](/../../../znetdk4mobile/tree/master/applications/default/) directory. This folder is the root directory where are stored the uploaded files.
5. Reload the ZnetDK 4 Mobile starter App in the web browser to see the new **Storage** menu and the **Disk space used** and **Documents** submenus.

## USERS GRANTED TO MODULE FEATURES
Once the **Storage** menu item is added to the application, you can restrict 
its access via a [user profile](https://mobile.znetdk.fr/settings#z4m-settings-user-rights).  
For example:
1. Create a user profile named `Admin` from the **Authorizations | Profiles** menu,
2. Select for this new profile, the **Disk space used** and **Documents** submenu items,
3. Finally for each allowed user, add them the `Admin` profile from the **Authorizations | Users** menu. 

## CONFIGURING FILE UPLOAD AND STORAGE 
### php.ini
The **php directives** below directly affect file uploads in the application and should be customized if necessary in the PHP configuration `php.ini` script.
- [`file_uploads`](https://www.php.net/manual/en/ini.core.php#ini.file-uploads): to enable file uploads.
- [`upload_tmp_dir`](https://www.php.net/manual/en/ini.core.php#ini.upload-tmp-dir): temporary directory where the files are uploaded.
- [`upload_max_filesize`](https://www.php.net/manual/en/ini.core.php#ini.upload-max-filesize): the maximum file size of an uploaded file.
- [`post_max_size`](https://www.php.net/manual/en/ini.core.php#ini.post-max-size): the maximum size of the multiple files uploaded in a POST request.
- [`max_input_time`](https://www.php.net/manual/en/info.configuration.php#ini.max-input-time): the maximum allowed time in seconds to upload files.

### config.php
Defines the following PHP constants to the [`config.php`](/../../../znetdk4mobile/blob/master/applications/default/app/config.php) script of your Starter App to customize the default configuration of the **Storage** module:
- <a id="z4m-max-upload-filesize">`MOD_Z4M_STORAGE_MAX_UPLOAD_FILESIZE_IN_BYTES`</a>: maximum file size allowed on document upload. This value is only taken into account if the php.ini directive `upload_max_filesize` is set to a higher file size.
   Default value is `20971520` (20 MB).
- <a id="z4m-max-space">`MOD_Z4M_STORAGE_MAX_SPACE_IN_BYTES`</a>: maximum disk space allowed to store the application database and files uploaded by users.
   Default value is `1073741824` (1 GB).
- <a id="z4m-max-photo-width">`MOD_Z4M_STORAGE_MAX_PHOTO_WIDTH_IN_PIXELS`</a>: maximum width in pixels of the photos once reduced.
   Default value is `800`.
- <a id="z4m-max-thumbnail-width">`MOD_Z4M_STORAGE_MAX_PHOTO_THUMBNAIL_WIDTH_IN_PIXELS`</a>: maximum width in pixels of the photo thumbnails displayed for preview.
   Default value is `160`.
- <a id="z4m-views-allowed">`MOD_Z4M_STORAGE_DOCUMENT_MANAGEMENT_VIEWS_ALLOWED`</a>: views that the user must have access to in order to download, view and delete files.
   Default value is `NULL` (no restriction).

## ADD FILE UPLOAD TO YOUR APP
The **z4m_storage** module is shipped with two PHP view fragment `upload_documents.php` and `upload_photos.php`, that can be included in a view or a modal dialog to upload files.
### Document upload: `upload_documents.php` view fragment
The [`upload_documents.php`](mod/view/fragment/upload_documents.php) view fragment is PHP code for document upload (PDF documents and others).
This PHP code displays:
- an `Upload documents...` button to select the files to upload,
- a table of the uploaded documents.

![Upload documents view fragment provided by the ZnetDK 4 Mobile 'z4m_storage' module](https://mobile.znetdk.fr/applications/default/public/images/modules/z4m_storage/screenshot4.png)

To initialize the `upload_documents.php` view fragment, a [`Z4M_StorageDocumentUpload`](public/js/class/z4m_storage_upload.js) object is instantiated from the [`z4m_storage_upload-min.js`](public/js/class/z4m_storage_upload-min.js) JS module.

```php
<div id="my-upload-container" class="w3-content">
<?php require 'z4m_storage/mod/view/fragment/upload_documents.php'; ?>
</div>
<script type="module">
    import { Z4M_StorageDocumentUpload } from './engine/modules/z4m_storage/public/js/class/z4m_storage_upload-min.js';
    const storageObj = new Z4M_StorageDocumentUpload('#my-upload-container');
    storageObj.setBusinessIdCallback(function(){
        return 72; // Identifier of the Invoice business object
    });
    // Subdirectory where documents are stored on the web server.
    storageObj.setStorageSubdirectory('invoice_docs');
    // Existing documents stored in the 'invoice_docs' subdirectory
    // for the business ID = 72 are displayed.
    storageObj.refresh();
</script>
```

### Photo upload: `upload_photos.php` view fragment
The [`upload_photos.php`](mod/view/fragment/upload_photos.php) view fragment is dedicated to photo upload.
This PHP code displays:
- an `Upload photos...` button to select the photos to upload,
- The thumbnails of the uploaded photos.

![Upload photos view fragment provided by the ZnetDK 4 Mobile 'z4m_storage' module](https://mobile.znetdk.fr/applications/default/public/images/modules/z4m_storage/screenshot3.jpg)

To initialize the `upload_photos.php` view fragment, a [`Z4M_StoragePhotoUpload`](public/js/class/z4m_storage_upload.js) object is instantiated from the [`z4m_storage_upload-min.js`](public/js/class/z4m_storage_upload-min.js) JS module.

```php
<div id="my-upload-container" class="w3-content">
<?php require 'z4m_storage/mod/view/fragment/upload_photos.php'; ?>
</div>
<script type="module">
    import { Z4M_StoragePhotoUpload } from './engine/modules/z4m_storage/public/js/class/z4m_storage_upload-min.js';
    const storageObj = new Z4M_StoragePhotoUpload('#my-upload-container');
    storageObj.setBusinessIdCallback(function(){
        return 18; // Identifier of the Customer business object
    });
    // Subdirectory where photos are stored on the web server.
    storageObj.setStorageSubdirectory('customer_photos');
    // Existing photos stored in the 'customer_photos' subdirectory
    // for the business ID = 18 are displayed.
    storageObj.refresh();
</script>
```

## ISSUES
### SQL table
The `z4m_documents` SQL table is created automatically by the module when one of
the module views is displayed for the first time.  
If the MySQL user declared through the
[`CFG_SQL_APPL_USR`](https://mobile.znetdk.fr/settings#z4m-settings-db-user)
PHP constant does not have `CREATE` privilege, the module can't create the
required SQL table.
In this case, you can create the module's SQL table by importing in MySQL or
phpMyAdmin the script [`z4m_storage.sql`](mod/sql/z4m_storage.sql) provided by the module.
### File storage subdirectory
The subdirectories where are stored the uploaded files are not created automatically.
When a subdirectory is specified for storing an uploaded file, the subdirectory must exist within the `documents/` directory (see [Stored documents](https://mobile.znetdk.fr/settings#z4m-settings-stored-documents)).

## CHANGE LOG
See [CHANGELOG.md](CHANGELOG.md) file.

## CONTRIBUTING
Your contribution to the **ZnetDK 4 Mobile** project is welcome. Please refer to the [CONTRIBUTING.md](https://github.com/pascal-martinez/znetdk4mobile/blob/master/CONTRIBUTING.md) file.
