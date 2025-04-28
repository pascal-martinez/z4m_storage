<?php

/*
 * ZnetDK, Starter Web Application for rapid & easy development
 * See official website https://www.znetdk.fr
 * Copyright (C) 2025 Pascal MARTINEZ (contact@znetdk.fr)
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
 * ZnetDK 4 Mobile Storage module Document Zip Archive class
 * 
 * File version: 1.0
 * Last update: 04/28/2025
 */

namespace z4m_storage\mod;

/**
 * Document ZIP archive
 */
class DocumentZipArchive {
    
    protected $zipArchive;
    protected $zipFilePath;
    
    /**
     * DocumentZipArchive class constructor 
     * @throws \Exception 'zip' extension not loaded, ZIP file creation on
     * filesystem failed or ZIP archive creation failed.
     */
    public function __construct() {
        if (!self::isZipExtensionLoaded()) {
            throw new \Exception("'zip' extension is not loaded.");
        }
        $zipFilePath = tempnam(sys_get_temp_dir(), 'zip');
        if ($zipFilePath === FALSE) {
            throw new \Exception("Unable to create '{$zipFilePath}' zip file.");
        }
        $this->zipArchive = new \ZipArchive();
        
        $status = $this->zipArchive->open($zipFilePath, \ZipArchive::OVERWRITE);
        if ($status !== TRUE) {
            throw new \Exception("Unable to create the zip archive (error $status)");
        }
        $this->zipFilePath = $zipFilePath;
        // Archive removed once session is closed
        register_shutdown_function('unlink', $zipFilePath);
    }
    
    /**
     * Checks if 'zip' extension is loaded
     * @return boolean Returns FALSE is 'zip' extension is not loaded, TRUE 
     * otherwise.
     */
    static public function isZipExtensionLoaded() {
        return extension_loaded('zip');
    }
    
    /**
     * Adds a document to the ZIP archive
     * @param string $filePath absolute file path of the document to add to the
     * archive.
     * @param string $fileName File name given to the document within the ZIP 
     * archive.
     * @param string $directoryPathInArchive Absolute path of the directory
     * within the ZIP archive where to store the document.
     * @throws \Exception Unable to add the specified file to the ZIP archive.
     */
    public function addDocument($filePath, $fileName, $directoryPathInArchive) {
        $this->addDirectory($directoryPathInArchive);
        $filePathInArchive = "{$directoryPathInArchive}/{$fileName}";
        if ($this->zipArchive->addFile($filePath, $filePathInArchive) === FALSE) {
            throw new \Exception("Unable to add '{$filePath}' file named '{$fileName}' to ZIP archive.");
        }
    }
    
    /**
     * Closes the ZIP archive.
     * @throws \Exception Error on closing the ZIP archive.
     */
    public function close() {
        if ($this->zipArchive->close() === FALSE) {
            throw new \Exception("Unable to close the zip archive");
        }
    }
    
    /**
     * Returns the absolute file path of the generated ZIP archive.
     * @return string Absolute file path.
     */
    public function getFilePath() {
        return $this->zipFilePath;
    }
    
    /**
     * Adds a directory within the ZIP archive
     * @param string $directoryPath The absolute path of the directory.
     * @return bool Returns FALSE if the specified directory path is empty or if
     * the directory already exists.
     * @throws \Exception Error while adding the specified directory.
     */
    protected function addDirectory($directoryPath) {
        if (empty($directoryPath)) {
            return FALSE;
        } elseif ($this->zipArchive->locateName($directoryPath . '/') !== FALSE) {
            return FALSE; // already exists
        }
        if ($this->zipArchive->addEmptyDir($directoryPath) === FALSE) {
            throw new \Exception("Unable to add directory '$directoryPath' to the archive.");
        }
        return TRUE;
    }
}
