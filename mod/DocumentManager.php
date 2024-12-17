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
 * ZnetDK 4 Mobile Storage module Document Manager class
 *
 * File version: 1.0
 * Last update: 12/03/2024
 */
namespace z4m_storage\mod;

/**
 * Document manager
 */
class DocumentManager {

    /**
     * Returns the rows of the z4m_documents SQL table matching the specified
     * arguments.
     * @param int|NULL $first The first row number to return.
     * @param int|NULL $count The number of rows to return.
     * @param array|NULL $searchCriteria The search criteria. The expected array
     * key values are 'subdirectory', 'business_id', 'start', 'end',
     * 'file_extension' and 'file_size'.
     * @param string $sortCriteria The SQL sort criteria (for example 'id DESC').
     * @param boolean $withThumbnails If TRUE, the thumbnails are also returned
     * in base 64 format for the photos.
     * @param array $rows The SQL rows found for the specified arguments.
     * @return int Total number of rows found. This number is greater than the 
     * number of rows returned if the $first and $count arguments are filled in.
     */
    static public function getRows($first, $count, $searchCriteria, $sortCriteria, $withThumbnails, &$rows) {
        $dao = new \z4m_storage\mod\model\DocumentDAO();
        Document::createModuleSqlTable($dao);
        $dao->setCriteria($searchCriteria);
        $dao->setSortCriteria($sortCriteria);
        $total = $dao->getCount();
        if (!is_null($first) && !is_null($count)) {
            $dao->setLimit($first, $count);
        }
        while ($row = $dao->getResult()) {
            $row['filesize_display'] = self::convertBytesToDisplaySize($row['filesize']);
            if ($withThumbnails === TRUE) {
                $document = new Document($row['id']);
                $row['thumbnail'] = $document->getThumbnail();
            }
            $rows[] = $row;
        }
        return $total;
    }

    /**
     * Returns the disk space used by the documents matching the specified
     * search criteria.
     * @param array $searchCriteria The search criteria (the same than those 
     * specified to the DocumentManager::getRows() method).
     * @return string The total used space in a format suitable for display.
     */
    static public function getDocumentsUsedSpace($searchCriteria) {
        $dao = new \z4m_storage\mod\model\DocumentSizeDAO();
        $dao->setCriteria($searchCriteria);
        $row = $dao->getResult();
        return self::convertBytesToDisplaySize($row['total_size']);
    }

    /**
     * Stores the uploaded files
     * @param string $uploadedFilesPOSTParameterName Name of the POST parameter
     * used to upload the files
     * @param string $storageSubdirectory Name of the subdirectory where the 
     * uploaded files have to be stored.
     * @param int $businessId Business identifier to associate to the uploaded
     * files.
     * @return int The count of uploaded files
     * @throws \Exception
     * @throws Exception
     */
    static public function storeUploadedDocuments($uploadedFilesPOSTParameterName, $storageSubdirectory, $businessId) {
        $storagePath = CFG_DOCUMENTS_DIR . DIRECTORY_SEPARATOR . $storageSubdirectory
                . (!is_null($storageSubdirectory) ? DIRECTORY_SEPARATOR : '');
        if (!is_dir($storagePath)) {
            throw new \Exception('The storage subdirectory does not exist.');
        }
        if (!is_array($_FILES[$uploadedFilesPOSTParameterName])
                || !is_array($_FILES[$uploadedFilesPOSTParameterName]['name'])
                || count($_FILES[$uploadedFilesPOSTParameterName]['name']) === 0
                || $_FILES[$uploadedFilesPOSTParameterName]['name'][0] === '') {
            throw new \Exception('No document to store.');
        }
        $fileCount = 0;
        foreach ($_FILES[$uploadedFilesPOSTParameterName]['name'] as $key => $filename) {
            $sourceFile = $_FILES[$uploadedFilesPOSTParameterName]['tmp_name'][$key];
            $document = new Document();
            $document->setStorageSubdirectory($storageSubdirectory);
            $document->setBusinessId($businessId);
            $document->store($sourceFile, $filename);
            $fileCount++;
        }
        return $fileCount;
    }

    /**
     * Purges the files matching the specified criteria.
     * @param array $searchCriteria The criteria to select the files to purge (
     * the same than those specified for the DocumentManager::getRows() method).
     */
    static public function purge($searchCriteria) {
        $rows = [];
        DocumentManager::getRows(NULL, NULL, $searchCriteria, 'id', false, $rows);
        foreach ($rows as $row) {
            $document = new Document($row['id']);
            $document->remove();
        }
    }

    /**
     * Converts the file size to a format suitable for display.
     * @param int $bytes file size in bytes.
     * @return string The formated file size (for example '12.3 MB)
     */
    static public function convertBytesToDisplaySize($bytes) {
        $units = array_reverse(MOD_Z4M_STORAGE_FILE_SIZE_UNITS, TRUE);
        $forDisplay = $bytes . " {$units[0]}";
        array_pop($units);
        $precision = [0, 0, 1, 1];
        foreach ($units as $exponent => $unit) {
            $maxSize = pow(1024, $exponent);
            if ($bytes >= $maxSize) {
                $forDisplay = strval(round($bytes/$maxSize, $precision[$exponent])) . ' ' . $unit;
                break;
            }
        }
        return $forDisplay;
    }

    /**
     * Returns the subfolders of the CFG_DOCUMENT_DIR directory.
     * @param boolean $includeRootFolder If TRUE, the CFG_DOCUMENT_DIR directory
     * is also returned.
     * @param boolean $getFolderNameOnly If TRUE, only the folder name is
     * returned.
     * @return array The full path of the subfolders found. If 
     * $getFolderNameOnly is TRUE, only subdirectory names are returned.
     */
    static public function getSubfolders($includeRootFolder = FALSE, $getFolderNameOnly = FALSE) {
        $subFolders = glob(CFG_DOCUMENTS_DIR . DIRECTORY_SEPARATOR . '*' , GLOB_ONLYDIR);
        if (!$includeRootFolder && !$getFolderNameOnly) {
            return $subFolders;
        }
        $folders = $includeRootFolder ? [$getFolderNameOnly ? '/' : CFG_DOCUMENTS_DIR] : [];
        foreach ($subFolders as $subfolderPath) {
            $folders []= $getFolderNameOnly ? basename($subfolderPath) : $subfolderPath;
        }
        return $folders;
    }

    /**
     * Returns the file extensions of the documents stored in the application.
     * @return array The file extensions found.
     */
    static public function getStoredDocumentFileExtensions() {
        $extensions = [];
        $dao = new model\FileExtensionsDAO();
        while ($row = $dao->getResult()) {
            $extensions[] = $row['original_file_extension'];
        }
        return $extensions;
    }

    /**
     * Returns the file sizes proposed for purging documents
     * @return array The file sizes
     */
    static public function getFilterFileSizes() {
        $sizes = [1024*500, 1024*1024, 1024*1024*5, 1024*1024*10, 1024*1024*20,
        1024*1024*50, 1024*1024*100, 1024*1024*200, 1024*1024*500];
        $maxFileSize = self::getMaxFileSize();
        $fileSizes = [];
        foreach ($sizes as $size) {
            if ($size <= $maxFileSize) {
                $fileSizes[] = [
                    'bytes' => $size,
                    'display_size' => self::convertBytesToDisplaySize($size)
                ];
            } else {
                break;
            }
        }
        return $fileSizes;
    }

    /**
     * Returns the maximum file of a document that can be uploaded.
     * @return int File size in bytes.
     */
    static public function getMaxFileSize() {
        $phpIniValue = self::convertIniSizeInBytes(ini_get('upload_max_filesize'));
        return MOD_Z4M_STORAGE_MAX_UPLOAD_FILESIZE_IN_BYTES < $phpIniValue
                ? MOD_Z4M_STORAGE_MAX_UPLOAD_FILESIZE_IN_BYTES : $phpIniValue;
    }

    /**
     * Returns the total size in bytes of the selected files for upload.
     * @return int Total file size in bytes
     */
    static public function getMaxSelectionSize() {
        return self::convertIniSizeInBytes(ini_get('post_max_size'));
    }

    /**
     * Converts the size specified for the php.ini 'post_max_size' and 
     * 'upload_max_filesize' directives to bytes
     * @param string $size the file size (for example '10M').
     * @return int The converted size in bytes.
     */
    static protected function convertIniSizeInBytes($size) {
        $bytes = $size;
        switch (strtolower(substr($size,-1))) {
            case 'k':
                return intval(substr($bytes, 0, -1))*1024;
            case 'm':
                return intval(substr($bytes, 0, -1))*1024*1024;
            case 'g':
                return intval(substr($bytes, 0, -1))*1024*1024*1024;
        }
        return intval($size);
    }

}
