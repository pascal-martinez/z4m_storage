# CHANGE LOG: Storage (z4m_storage)

## Version 1.2, 2025-01-13
- BUG FIXING: the uploaded documents were displayed twice when the Z4M_StorageDocumentUpload class was instantiated multiple times.
- BUG FIXING: the uploaded photos were displayed twice when the Z4M_Z4M_StoragePhotoUpload class was instantiated multiple times.

## Version 1.1, 2024-12-23
- BUG FIXING: the PHP constant MOD_Z4M_STORAGE_PHOTOS_REMOVE_QUESTION was not used and so was removed.
- BUG FIXING: translations of confirmation and notification messages are now generic and use the term "file" instead of "photo" or "document".
- BUG FIXING: in PHP 8.3, error "E_DEPRECATED - Calling get_parent_class() without arguments is deprecated - ... DocumentDAO.php(48)".

## Version 1.0, 2024-12-08
First version.