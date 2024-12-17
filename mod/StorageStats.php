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
 * ZnetDK 4 Mobile Storage module Statistics class
 *
 * File version: 1.0
 * Last update: 12/04/2024
 */
namespace z4m_storage\mod;
/**
 * Storage Statistics
 */
class StorageStats {
    
    /**
     * Returns the statistics displayed by the 'Disk space used' view.
     * @return array Disk space statistics
     */
    static public function getAll() {        
        $stats = ['max_allowed' => self::getMaxAllowedSpace()];
        $stats['database'] = self::getDatabaseUsedSpace();
        $stats['documents'] = self::getDocumentsUsedSpace();
        $stats['total'] = self::getSpace($stats['database']['space_bytes']
                + $stats['documents']['space_bytes']);
        $stats['total']['percent'] = $stats['database']['percent']
                + $stats['documents']['percent'];
        return $stats;
    }
    
    /**
     * Disk space left in bytes
     * @return int Disk space in bytes
     */
    static public function getDiskSpaceLeftInBytes() {
        $stats = self::getAll();
        return $stats['max_allowed']['space_bytes'] - $stats['total']['space_bytes'];
    }
    
    /**
     * Maximum allowed space for file storage
     * @return array Space in bytes, formated for display and in percent.
     */
    static public function getMaxAllowedSpace() {
        $bytes = MOD_Z4M_STORAGE_MAX_SPACE_IN_BYTES;
        return self::getSpace($bytes);
    }
    
    /**
     * Convert bytes to space suitable for display
     * @param int $bytes space in bytes
     * @return array Space in bytes, formated for display and in percent.
     */
    static protected function getSpace($bytes) {
        $forDisplay = DocumentManager::convertBytesToDisplaySize($bytes);        
        return [
            'space_bytes' => $bytes,
            'space_display' => $forDisplay,
            'percent' => round($bytes/MOD_Z4M_STORAGE_MAX_SPACE_IN_BYTES*100)
        ];
    }
    
    /**
     * Space used in database
     * @return array Space in bytes, formated for display and in percent.
     */
    static protected function getDatabaseUsedSpace() {
        $bytes = 0;
        $dao = new \z4m_storage\mod\model\DatabaseUsedSpace();
        $row = $dao->getResult();
        if (is_array($row) && key_exists('bytes', $row)) {
            $bytes = $row['bytes'];
        }
        return self::getSpace($bytes);
    }
    
    /**
     * Space used by stored files.
     * @return array space used by subdirectory and totally.
     */
    static protected function getDocumentsUsedSpace() {
        $folders = DocumentManager::getSubfolders(TRUE);
        $usedSpace = ['folders' => []];
        $totalBytes = 0; $totalFileCount = 0;
        foreach ($folders as $folder) {
            $bytes = 0; $fileCount = 0;
            $files = glob($folder . DIRECTORY_SEPARATOR . '*');
            foreach ($files as $filePath) {
                if (is_file($filePath) && pathinfo($filePath, PATHINFO_EXTENSION) === 'dat') {
                    $bytes += filesize($filePath);
                    $fileCount++;
                }
            }
            $usedSpace['folders'][basename($folder)] = self::getSpace($bytes);
            $usedSpace['folders'][basename($folder)]['filecount'] = $fileCount;
            $totalBytes += $bytes;
            $totalFileCount += $fileCount;
        }
        $total = self::getSpace($totalBytes);
        $usedSpace['space_bytes'] = $totalBytes;
        $usedSpace['space_display'] = $total['space_display'];
        $usedSpace['percent'] = $total['percent'];
        $usedSpace['filecount'] = $totalFileCount;
        return $usedSpace;
    }
}
