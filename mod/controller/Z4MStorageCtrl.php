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
 * ZnetDK 4 Mobile Storage module App Controller
 *
 * File version: 1.2
 * Last update: 08/08/2025
 */

namespace z4m_storage\mod\controller;

use \z4m_storage\mod\DocumentManager;

/**
 * App Controller of the Storage module (z4m_storage).
 */
class Z4MStorageCtrl extends \AppController {
    
    /**
     * Checks whether the controller's action can be executed or not by the user
     * @param string $action Name of the controller's action
     * @return boolean If authentication is required, returns TRUE if:
     * - user has right on the 'z4m_storage_documents' view for the 'all' and
     * 'purge' actions,
     * - MOD_Z4M_STORAGE_DOCUMENT_MANAGEMENT_VIEWS_ALLOWED constant is NULL or
     * is an array and user has right on the specified view in the constant for
     * the 'upload', 'remove', 'download' and 'document' actions.
     * If no authentication is required, returns TRUE if the views
     * 'z4m_storage_documents' and those declared through the 
     * MOD_Z4M_STORAGE_DOCUMENT_MANAGEMENT_VIEWS_ALLOWED constant are defined in
     * the 'menu.php' script of the application.
     * Otherwise returns FALSE and so the action is not executed.
     */
    static public function isActionAllowed($action) {
        if (parent::isActionAllowed($action) === FALSE) {
            return FALSE;
        }
        if ($action === 'all' || $action === 'purge' || $action === 'downloadzip') {
            $menuItem = 'z4m_storage_documents';
            return CFG_AUTHENT_REQUIRED === TRUE
                ? \controller\Users::hasMenuItem($menuItem)
                : \MenuManager::getMenuItem($menuItem) !== NULL;
        }
        if (!is_array(MOD_Z4M_STORAGE_DOCUMENT_MANAGEMENT_VIEWS_ALLOWED)) {
            return TRUE; // document management actions allowed to all users.
        }
        foreach (MOD_Z4M_STORAGE_DOCUMENT_MANAGEMENT_VIEWS_ALLOWED as $menuItem) {
            if (CFG_AUTHENT_REQUIRED === TRUE ? \controller\Users::hasMenuItem($menuItem)
                : \MenuManager::getMenuItem($menuItem) !== NULL) {
                return TRUE;
            }
        }
        return FALSE;
    }
    
    /**
     * Returns the list of stored documents to the 'z4m_storage_documents' view.
     * @return \Response JSON response containing the found documents.
     */
    static protected function action_all() {
        $request = new \Request();
        $first = $request->first;
        $count = $request->count;
        $searchCriteria = is_string($request->search_criteria) ? json_decode($request->search_criteria, TRUE) : NULL;
        $rows = [];
        // Success response returned to the main controller
        $response = new \Response();
        $response->total = DocumentManager::getRows($first, $count, $searchCriteria, 'id DESC', false, $rows);
        if (count($rows) > 0) { 
            // Total size of the documents found added to the first row for display
            $rows[0]['total_size'] = DocumentManager::getDocumentsUsedSpace($searchCriteria);
        }
        $response->rows = $rows;
        $response->success = TRUE;
        return $response;
    }
    
    /**
     * Purges all documents or only documents matching the specified filter
     * criteria.
     * Expected POST parameter is:
     * - search_criteria: optional criteria to apply in JSON format. Expected
     * properties are 'start' (W3C start date) and 'end' (W3C end date).
     * @return \Response Success or failed message in JSON format
     */
    static protected function action_purge() {
        $request = new \Request();
        $searchCriteria = is_string($request->search_criteria) ? json_decode($request->search_criteria, TRUE) : NULL;
        $response = new \Response();
        try {
            DocumentManager::purge($searchCriteria);
            $response->setSuccessMessage(NULL, MOD_Z4M_STORAGE_PURGE_SUCCESS);
        } catch (Exception $ex) {
            \General::writeErrorLog(__METHOD__, $ex->getMessage());
            $response->setFailedMessage(LC_MSG_CRI_ERR_SUMMARY, LC_MSG_CRI_ERR_GENERIC);
        }
        return $response;
    }
    
    /**
     * Stores the uploaded documents.
     * POST parameters: files, subdirectory, business_id and with_thumbnails
     * (value 'true' if thumbnails are requested).
     * This action is called from the 'upload_documents.php' and 'upload_photos'
     * view fragments by the Z4M_StorageUpload JS class.
     * @return \Response The updated list of documents after upload in the same
     * subdirectory and for the same business identifier.
     */
    static protected function action_upload() {
        $request = new \Request();
        $response = new \Response();
        try {
            $attachementCount = DocumentManager::storeUploadedDocuments('files',
                    $request->subdirectory, $request->business_id);
            $rows = [];
            DocumentManager::getRows(NULL, NULL, [
                'subdirectory' => $request->subdirectory,
                'business_id' => $request->business_id
            ], 'upload_datetime DESC', $request->with_thumbnails === 'yes', $rows);
            $response->rows = $rows;
            $response->setSuccessMessage(NULL,
                    str_replace('%count%', $attachementCount, MOD_Z4M_STORAGE_DOCUMENTS_SUCCESS_UPLOAD));
        } catch (\Exception $ex) {
            if ($ex->getCode() === 1) { // Max file size exceeded...
                $maxFileSize = DocumentManager::convertBytesToDisplaySize(DocumentManager::getMaxFileSize());
                $filename = explode(': ', $ex->getMessage())[1];
                $error = str_replace(['%max_filesize%', '%filename%'],
                    [$maxFileSize, $filename],
                    MOD_Z4M_STORAGE_DOCUMENTS_ERROR_UPLOAD_FILESIZE_EXCEEDED);
            } elseif ($ex->getCode() === 2) { // Disk space allowed exceeded...
                $maxDiskSpace = DocumentManager::convertBytesToDisplaySize(MOD_Z4M_STORAGE_MAX_SPACE_IN_BYTES);
                $filename = explode(': ', $ex->getMessage())[1];
                $error = str_replace(['%max_diskspace%', '%filename%'],
                    [$maxDiskSpace, $filename], MOD_Z4M_STORAGE_DOCUMENTS_ERROR_UPLOAD_DISKSPACE_EXCEEDED);
            } else {
                \General::writeErrorLog(__METHOD__, $ex->getMessage());
                $error = MOD_Z4M_STORAGE_DOCUMENTS_ERROR_UPLOAD_OTHER;
            }
            $response->setFailedMessage(NULL, $error);
        }
        return $response;
    }
    
    /**
     * Returns the list of stored documents.
     * POST parameters: subdirectory, business_id and with_thumbnails (value 
     * 'true' if thumbnails are requested).
     * This action is called from the 'upload_documents.php' and 'upload_photos'
     * view fragments by the Z4M_StorageUpload JS class.
     * @return \Response The list of the documents found in the specified 
     * subdirectory for the specified business identifier.
     */
    static protected function action_documents() {
        $request = new \Request();
        $response = new \Response();
        $rows = [];
        try {
            DocumentManager::getRows(NULL, NULL, [
                'subdirectory' => $request->subdirectory,
                'business_id' => $request->business_id
            ], 'upload_datetime DESC', $request->with_thumbnails === 'yes', $rows);
            $response->rows = $rows;
            $response->success = TRUE;
        } catch (\Exception $ex) {
            \General::writeErrorLog(__METHOD__, $ex->getMessage());
            $response->setFailedMessage(NULL, MOD_Z4M_STORAGE_DOCUMENTS_ERROR_FETCH);
        }
        return $response;
    }
    
    /**
     * Downloads the specified document.
     * POST parameter: doc_id
     * This action is called from the 'upload_documents.php' and 'upload_photos'
     * view fragments by the Z4M_StorageUpload JS class.
     * @return \Response The requested file for download.
     */
    static protected function action_download() {
        // TODO ZnetDK : if 404 HTTP error, display the error message even if 
        // CFG_DISPLAY_ERROR_DETAIL = FALSE.
        $request = new \Request();
        $response = new \Response();
        try {
            $document = new \z4m_storage\mod\Document($request->doc_id);
            $filePath = $document->getStoredFilePath(TRUE);
            $originalBasename = $document->original_basename;
            $response->setFileToDownload($filePath, TRUE, $originalBasename);
        } catch (\Exception $ex) {
            \General::writeErrorLog(__METHOD__, $ex->getMessage());
            $response->doHttpError(404, MOD_Z4M_STORAGE_DOCUMENTS_DOWNLOAD_LINK,
                    MOD_Z4M_STORAGE_DOCUMENTS_ERROR_DOWNLOAD_NOT_EXISTS);
        }
        return $response;
    }
    
    /**
     * Downloads a ZIP archive containing the documents matching the specified
     * filter criteria.
     * @return \Response the ZIP archive of the selected documents.
     */
    static protected function action_downloadzip() {
        $request = new \Request();
        $criteria = $request->getValuesAsMap('start', 'end', 'subdirectory', 'file_extension', 'file_size');
        $allCriteriaAreNull = TRUE;
        foreach ($criteria as $value) {
            if (!is_null($value)) {
                $allCriteriaAreNull = FALSE;
                break;
            }
        }
        if ($allCriteriaAreNull) {
            $criteria = NULL;
        }
        $archive = new \z4m_storage\mod\DocumentZipArchive();
        $rows = [];
        DocumentManager::getRows(NULL, NULL, $criteria, 'id DESC', false, $rows);
        foreach ($rows as $row) {
            $document = new \z4m_storage\mod\Document($row['id']);
            $uniqueFileNameInArchive = $row['id'] . '_' . $document->original_basename;
            $archive->addDocument($document->getStoredFilePath(), $uniqueFileNameInArchive, $document->subdirectory);
        }
        $archive->close();
        $response = new \Response();
        if (count($rows) === 0) {
            $response->doHttpError(404, MOD_Z4M_STORAGE_DOCUMENTS_DOWNLOAD_LINK,
                    MOD_Z4M_STORAGE_DOCUMENTS_ERROR_DOWNLOAD_NOT_EXISTS);
        }
        $response->setFileToDownload($archive->getFilePath(), TRUE, MOD_Z4M_STORAGE_DOWNLOAD_ZIP_FILENAME);
        return $response;
    }
    
    /**
     * Removes the specified document.
     * POST parameter: doc_id
     * This action is called from the 'upload_documents.php' and 'upload_photos'
     * view fragments by the Z4M_StorageUpload JS class.
     * @return \Response The list of the documents found in the same 
     * subdirectory and for the same business identifier than the removed
     * document.
     */
    static protected function action_remove() {
        $request = new \Request();
        $response = new \Response();
        try {
            $document = new \z4m_storage\mod\Document($request->doc_id);
            $originalBasename = $document->original_basename;
            $subdirectory = $document->subdirectory;
            $businessId = $document->business_id;
            $document->remove();
            $rows = [];
            DocumentManager::getRows(NULL, NULL, [
                'subdirectory' => $subdirectory,
                'business_id' => $businessId
            ], 'upload_datetime DESC', $request->with_thumbnails === 'yes', $rows);
            $response->rows = $rows;
            $response->setSuccessMessage(NULL, str_replace('%filename%',
                    $originalBasename, MOD_Z4M_STORAGE_DOCUMENTS_SUCCESS_REMOVE));
        } catch (\Exception $ex) {
            \General::writeErrorLog(__METHOD__, $ex->getMessage());
            $response->setFailedMessage(NULL, MOD_Z4M_STORAGE_DOCUMENTS_ERROR_REMOVE);
        }
        return $response;
    }
}