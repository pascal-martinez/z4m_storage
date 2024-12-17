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
 * ZnetDK 4 Mobile Storage module Document class
 *
 * File version: 1.0
 * Last update: 12/08/2024
 */
namespace z4m_storage\mod;

/**
 * Document storage
 */
class Document {

    protected $businessId = NULL;
    protected $data = NULL;

    /**
     * Instantiates a new document
     * @param int $id Internal identifier of the document in the z4m_documents
     * SQL Table
     */
    public function __construct($id = NULL) {
        $this->data = [
            'id' => $id,
            'subdirectory' => NULL,
            'business_id' => NULL
        ];
        if (!is_null($id)) {
            $this->fetchData($id);
        }
    }

    /**
     * Sets the subdirectory where the document is to store
     * @param string $subdirectory Name of the subdirectory.
     */
    public function setStorageSubdirectory($subdirectory) {
        $this->data['subdirectory'] = $subdirectory;
    }

    /**
     * Sets the business identifier associated to the document. 
     * @param int $businessId Business identifier.
     */
    public function setBusinessId($businessId) {
        $this->businessId = $businessId;
    }

    /**
     * Fetches the informations of the document 
     * @throws \Exception No document found.
     */
    protected function fetchData() {
        $dao = new model\DocumentDAO();
        $row = $dao->getById($this->data['id']);
        if ($row === FALSE) {
            throw new \Exception("No document found for ID={$this->data['id']}.");
        }
        $this->data = $row;
    }

    /**
     * Gets the value of the specified document property name.
     * @param string $name The property name (column name in the z4m_documents
     * SQL table).
     * @return string The value found.
     * @throws \Exception The specified property name is unknown.
     */
    public function __get($name) {
        if (!key_exists($name, $this->data)) {
            throw new \Exception("No value found for property named '{$name}'.");
        }
        return $this->data[$name];
    }

    /**
     * Returns the storage path of the subdirectory where to store the document.
     * @return string The absolute file path of the subdirectory.
     * @throws \Exception Storage path does not exist.
     */
    protected function getStoragePath() {
        $storagePath = CFG_DOCUMENTS_DIR . DIRECTORY_SEPARATOR
                . strval($this->data['subdirectory']);
        if (!is_dir($storagePath)) {
            \General::writeErrorLog(__METHOD__, "'{$storagePath}' does not exist.");
            throw new \Exception('Storage path is invalid.');
        }
        return $storagePath;
    }

    /**
     * Gets the file name of the stored document.
     * @return string file name
     */
    protected function getStoredFileName() {
        return "file_{$this->data['id']}.dat";
    }

    /**
     * Gets the file name of the stored document when it is a photo.
     * @return string file name.
     */
    protected function getStoredFilePreviewName() {
        return "file_{$this->data['id']}.preview";
    }

    /**
     * Gets the file path of the document.
     * @param boolean $checkIfExists
     * @param boolean $isPreviewFile
     * @return string The absolute file path of the stored document.
     * @throws \Exception The file is missing
     */
    public function getStoredFilePath($checkIfExists = FALSE, $isPreviewFile = FALSE) {
        $filePath = $this->getStoragePath() . DIRECTORY_SEPARATOR
                . ($isPreviewFile ? $this->getStoredFilePreviewName()
                    : $this->getStoredFileName());
        if ($checkIfExists && !file_exists($filePath)) {
            throw new \Exception("File '{$filePath}' does not exist.");
        }
        return $filePath;
    }

    /**
     * Returns the thumbnail of the document.
     * @return string|FALSE The thumbnail photo in base 64 format prefixed by 
     * 'data:image/jpg;base64,' for the 'src' attribute of the HTML image.
     * Otherwise returns FALSE if the document is not a photo or if the
     * thumbnail generation has failed.
     */
    public function getThumbnail() {
        if (array_search($this->original_file_extension, ['jpg', 'jpeg', 'png']) === FALSE) {
            return FALSE;
        }
        $thumbnailPath = $this->getStoredFilePath(FALSE, TRUE);
        $base64Begin = 'data:image/' . $this->original_file_extension . ';base64,';
        if (file_exists($thumbnailPath)) {
            return $base64Begin . base64_encode(file_get_contents($thumbnailPath));
        }
        $filePath = $this->getStoredFilePath(TRUE);
        $tempPhotoFilePath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . session_id()
                . basename($filePath, 'dat') . $this->original_file_extension;
        copy($filePath, $tempPhotoFilePath);
        try {
            $newWidth = MOD_Z4M_STORAGE_MAX_PHOTO_THUMBNAIL_WIDTH_IN_PIXELS;
            $thumbnailPhoto = \General::reducePictureSize($tempPhotoFilePath, $newWidth, $newWidth, FALSE);
        } catch (\Exception $ex) {
            return FALSE; // Thumbnail generation failed
        } finally {
            unlink($tempPhotoFilePath);
        }
        file_put_contents($thumbnailPath, $thumbnailPhoto);
        return $base64Begin . base64_encode($thumbnailPhoto);
    }

    /**
     * Throws an exception if the maximum file size is exceeded.
     * The maximum file size is specified by the php.ini 'upload_max_filesize'
     * and the MOD_Z4M_STORAGE_MAX_UPLOAD_FILESIZE_IN_BYTES PHP constant.
     * The lower value is taken in account.
     * @param string $tempUploadedFilePath temporary file path of the uploaded
     * document.
     * @param string $originalUploadedFilename original file name.
     * @throws \Exception Maximum allowed size exceeded.
     */
    static protected function checkMaxAllowedFileSize($tempUploadedFilePath, $originalUploadedFilename) {
        $maxFileSize = DocumentManager::getMaxFileSize();
        if (filesize($tempUploadedFilePath) > $maxFileSize) {
            throw new \Exception("Maximum allowed size of {$maxFileSize} bytes exceeded for the following file: '{$originalUploadedFilename}'", 1);
        }
    }

    /**
     * Throws an exception if the maximum disk space is exceeded after storage
     * of the document.
     * @param string $tempUploadedFilePath temporary file path of the uploaded
     * document.
     * @param string $originalUploadedFilename original file name.
     * @throws \Exception Maximum allowed disk space exceeded
     */
    static protected function checkMaxDiskSpaceExceeded($tempUploadedFilePath, $originalUploadedFilename) {
        $maxAllowedSpace = StorageStats::getMaxAllowedSpace();
        $diskSpaceLeft = StorageStats::getDiskSpaceLeftInBytes();
        if (filesize($tempUploadedFilePath) > $diskSpaceLeft) {
            throw new \Exception("Maximum allowed disk space of {$maxAllowedSpace['space_bytes']} bytes exceeded for storing the following file: '{$originalUploadedFilename}'", 2);
        }
    }

    /**
     * Stores the file of the document
     * @param string $tempUploadedFilePath temporary file path of the uploaded
     * document.
     * @throws \Exception The temporary file does not exist, the file to store
     * already exists or storage has failed.
     */
    protected function storeFile($tempUploadedFilePath) {
        if (!file_exists($tempUploadedFilePath)) {
            throw new \Exception("Uploaded temporary file '{$tempUploadedFilePath}' does not exists.");
        }
        $targetFilePath = $this->getStoredFilePath();
        if (file_exists($targetFilePath)) {
            throw new \Exception("File to store '{$targetFilePath}' already exists.");
        }
        if (move_uploaded_file($tempUploadedFilePath, $targetFilePath) === FALSE) {
            throw new \Exception("File storage failed: move_uploaded_file('{$tempUploadedFilePath}', '{$targetFilePath}') error.");
        }
    }

    /**
     * Returns the SQL table row to save for the document.
     * @param string $tempUploadedFilePath temporary file path of the uploaded
     * document.
     * @param string $originalUploadedFilename original file name.
     * @return array SQL row to store.
     */
    protected function getFileRowToStore($tempUploadedFilePath, $originalUploadedFilename) {
        return [
            'original_basename' => basename($originalUploadedFilename),
            'original_file_extension' => pathinfo($originalUploadedFilename, PATHINFO_EXTENSION),
            'stored_basename' => basename($tempUploadedFilePath),
            'subdirectory' => $this->data['subdirectory'],
            'filesize' => filesize($tempUploadedFilePath),
            'upload_datetime' => \General::getCurrentW3CDate(TRUE),
            'username' => \UserSession::getUserName(),
            'business_id' => $this->businessId
        ];
    }

    /**
     * Stores the document.
     * @param string $tempUploadedFilePath temporary file path of the uploaded
     * document.
     * @param string $originalUploadedFilename original file name.
     * @throws \Exception Document's internal identifier in database has been
     * wrongly set in the constructor.
     */
    public function store($tempUploadedFilePath, $originalUploadedFilename) {
        if (!is_null($this->data['id'])) {
            throw new \Exception('Document ID must not be set.');
        }
        self::checkMaxAllowedFileSize($tempUploadedFilePath, $originalUploadedFilename);
        self::checkMaxDiskSpaceExceeded($tempUploadedFilePath, $originalUploadedFilename);
        $dao = new model\DocumentDAO(TRUE, TRUE);
        self::createModuleSqlTable($dao);
        $dao->beginTransaction();
        $fileRow = $this->getFileRowToStore($tempUploadedFilePath, $originalUploadedFilename);
        $this->data['id'] = $dao->store($fileRow, FALSE);
        $this->storeFile($tempUploadedFilePath);
        $dao->store([
            'id' => $this->data['id'],
            'stored_basename' => $this->getStoredFileName()
        ], FALSE);
        $dao->commit();
    }

    /**
     * Removes the file of the document.
     * @throws Exception Unable to remove the file of the document or its 
     * thumbnail when the document is a photo.
     */
    protected function removeFile() {
        $filePath = $this->getStoredFilePath(TRUE);
        if (!unlink($filePath)) {
            throw new Exception("Unable to remove file '{$filePath}'.");
        }
        $previewFilePath = $this->getStoredFilePath(FALSE, TRUE);
        if (file_exists($previewFilePath) && !unlink($previewFilePath)) {
            throw new Exception("Unable to remove preview file '{$filePath}'.");
        }
    }

    /**
     * Removes the document.
     * @throws \Exception The document's internal identifier is not set.
     */
    public function remove() {
        if (is_null($this->data['id'])) {
            throw new \Exception('Document ID not set.');
        }
        $dao = new model\DocumentDAO(TRUE, TRUE);
        $dao->beginTransaction();
        $dao->remove($this->data['id'], FALSE);
        $this->removeFile();
        $dao->commit();
    }

    /**
     * Create the SQL table required for the module.
     * The table is created from the SQL script defined via the
     * MOD_Z4M_STORAGE_SQL_SCRIPT_PATH constant.
     * @param DAO $dao DAO for which existence is checked
     * @throws \Exception SQL script is missing and SQL table creation failed.
     */
    static public function createModuleSqlTable($dao) {
        if ($dao->doesTableExist()) {
            return;
        }
        if (!file_exists(MOD_Z4M_STORAGE_SQL_SCRIPT_PATH)) {
            $error = "SQL script '" . MOD_Z4M_STORAGE_SQL_SCRIPT_PATH . "' is missing.";
            throw new \Exception($error);
        }
        $sqlScript = file_get_contents(MOD_Z4M_STORAGE_SQL_SCRIPT_PATH);
        $db = \Database::getApplDbConnection();
        $db->exec($sqlScript);
    }
}
