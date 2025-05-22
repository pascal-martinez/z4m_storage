# CHANGE LOG: Storage (z4m_storage)

## Version 1.4, 2025-05-22
- CHANGE: new monitoring box 'homemenu_storage.php' to display on the home page to get the current disk space consumption percent.
- BUG FIXING: after deleting a photo without a business ID set, all other photos were incorrectly hidden when refreshing the photo preview.

## Version 1.3, 2025-04-28
- CHANGE: new Download... button added to the 'Documents' view to generate and download a ZIP archive of the documents matching the specified filter criteria.
- BUG FIXING: the SQL table 'z4m_documents' was not automatically created when showing the 'z4m_storage_documents' view for the first time.
- BUG FIXING: SQL error on file upload "SQLSTATE[22001]: String data, right truncated: 1406 Data too long for column 'stored_basename' at row 1".

## Version 1.2, 2025-01-13
- BUG FIXING: the uploaded documents were displayed twice when the Z4M_StorageDocumentUpload class was instantiated multiple times.
- BUG FIXING: the uploaded photos were displayed twice when the Z4M_Z4M_StoragePhotoUpload class was instantiated multiple times.

## Version 1.1, 2024-12-23
- BUG FIXING: the PHP constant MOD_Z4M_STORAGE_PHOTOS_REMOVE_QUESTION was not used and so was removed.
- BUG FIXING: translations of confirmation and notification messages are now generic and use the term "file" instead of "photo" or "document".
- BUG FIXING: in PHP 8.3, error "E_DEPRECATED - Calling get_parent_class() without arguments is deprecated - ... DocumentDAO.php(48)".

## Version 1.0, 2024-12-08
First version.