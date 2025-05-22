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
 * ZnetDK 4 Mobile Storage module DAO class
 *
 * File version: 1.2
 * Last update: 05/18/2025
 */
namespace z4m_storage\mod\model;

if (class_exists('\z4m_audittrail\mod\AuditTrailDAO')) {
    class_alias('\z4m_audittrail\mod\AuditTrailDAO', '\z4m_storage\mod\model\DAO_Class');
} else {
    class_alias('\DAO', '\z4m_storage\mod\model\DAO_Class');
}

/**
 * Document DAO
 */
class DocumentDAO extends DAO_Class {
    
    /**
     * Instantiates the DAO.
     * If the Audit trail module is installed, the DocumentDAO class inherits
     * from the \z4m_audittrail\mod\AuditTrailDAO class. Otherwise, it inherits
     * from the ZnetDK \DAO class.
     * @param Boolean $trackingEnabled See \z4m_audittrail\mod\AuditTrailDAO
     * class.
     * @param Boolean $includeDetails See \z4m_audittrail\mod\AuditTrailDAO
     * class.
     */
    public function __construct($trackingEnabled = FALSE, $includeDetails = FALSE) {
        if (get_parent_class($this) === 'z4m_audittrail\mod\AuditTrailDAO') {
            parent::__construct($trackingEnabled, $includeDetails);
        } else {
            parent::__construct();
        }
    }

    /**
     * Initializes the DAO
     */
    protected function initDaoProperties() {
        $this->table = 'z4m_documents';
        $this->setDateColumns('upload_datetime');
    }
    
    /**
     * Sets filter criteria
     * @param array $criteria The criteria to apply. Expected array keys are
     * 'subdirectory', 'business_id', 'start', 'end', 'file_extension' and
     * 'file_size'
     * @return Boolean TRUE on success, FALSE if the $criteria argument is not
     * an array.
     */
    public function setCriteria($criteria) {
        if (!is_array($criteria)) {
            return FALSE;
        }
        if (key_exists('subdirectory', $criteria)) {
            if (is_null($criteria['subdirectory'])) {
                $this->addFilter('subdirectory IS ?', NULL);
            } else {
                $this->addFilter('subdirectory = ?', $criteria['subdirectory']);
            }
        }
        if (key_exists('business_id', $criteria) && !empty($criteria['business_id'])) {
            $this->addFilter('business_id = ?', $criteria['business_id']);
        }
        if (key_exists('start', $criteria) && !is_null($criteria['start'])) {
            $this->addFilter('upload_datetime >= ?', "{$criteria['start']}T00:00:00Z");
        }
        if (key_exists('end', $criteria) && !is_null($criteria['end'])) {
            $this->addFilter('upload_datetime <= ?', "{$criteria['end']}T23:59:59Z");
        }
        if (key_exists('file_extension', $criteria) && !is_null($criteria['file_extension'])) {
            $this->addFilter('original_file_extension = ?', $criteria['file_extension']);
        }
        if (key_exists('file_size', $criteria) && !is_null($criteria['file_size'])) {
            $this->addFilter('filesize > ?', $criteria['file_size']);
        }
        return TRUE;
    }
    
    protected function addFilter($filter, $value) {
        if ($this->filterClause === FALSE) {
            $this->filterClause = "WHERE {$filter}";
        } else {
            $this->filterClause .= " AND {$filter}";
        }
        $this->filterValues []= $value;
    }
    
}