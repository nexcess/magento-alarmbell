<?php
/**
 * Nexcess.net AlarmBell Extension for Magento
 * Copyright (C) 2015  Nexcess.net L.L.C.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 */

class Nexcessnet_Alarmbell_Model_Observer {

    public function logAdminUserSave($observer) {
        // only log if enabled via config
        $enabled = Mage::getStoreConfig('alarmbell_options/admin_user_monitoring/alarmbell_admin_user_monitoring_enable');
        if ($enabled == 1) {
            $adminUser = $observer->getEvent()->getObject();
            $message = '';

            if ($adminUser->getOrigData('user_id') == null) { 
                $message .= "New admin user '" . $adminUser->getUsername() . "' created";
                $emailSubject = "Admin user created";
            } 
            else {
                $message .= "Existing admin user '" . $adminUser->getOrigData('username') . "' updated";
                $emailSubject = "Admin user updated";
            }
            $logMessage = Mage::helper('alarmbell/data')->log($message);
            $emailAddress = Mage::getStoreConfig('alarmbell_options/admin_user_monitoring/alarmbell_admin_user_email');
            Mage::helper('alarmbell/data')->email($logMessage, $emailSubject,$emailAddress);
        }
    }

    public function logAdminUserDelete($observer) {
        // only log if enabled via config
        $enabled = Mage::getStoreConfig('alarmbell_options/admin_user_monitoring/alarmbell_admin_user_monitoring_enable');
        if ($enabled == 1) {
            $user_data = Mage::getModel('admin/user')->load( $observer->getEvent()->getObject()->getUserId() )->getData();
            $message = "Admin user '" . $user_data['username'] . "' deleted";
            $logMessage = Mage::helper('alarmbell/data')->log($message);
            $emailAddress = Mage::getStoreConfig('alarmbell_options/admin_user_monitoring/alarmbell_admin_user_email');
            Mage::helper('alarmbell/data')->email($logMessage, 'Admin user deleted', $emailAddress);
        }
    }

    public function logAdminUserLoginSuccess($observer) {
        // only log if enabled via config
        $enabled = Mage::getStoreConfig('alarmbell_options/admin_user_monitoring/alarmbell_admin_user_login_monitoring_enable');
        if ($enabled == 1) {
            $admin = Mage::getSingleton('admin/session')->getUser();
            if ($admin->getId()) {
                $admin_username = $admin->getUsername();
                $message = "Successful admin user log in";
                $logMessage = Mage::helper('alarmbell/data')->log($message);
                $emailAddress = Mage::getStoreConfig('alarmbell_options/admin_user_monitoring/alarmbell_admin_user_login_monitoring_email');
                Mage::helper('alarmbell/data')->email($logMessage, $message, $emailAddress);
            }
        }
    }

    public function logAdminUserLoginFail($observer) {
        // only log if enabled via config
        $enabled = Mage::getStoreConfig('alarmbell_options/admin_user_monitoring/alarmbell_admin_user_login_monitoring_enable');
        if ($enabled == 1) {
            $message = "Failed admin user login for '" . $observer->user_name . "'";
            $logMessage = Mage::helper('alarmbell/data')->log($message);
            $emailAddress = Mage::getStoreConfig('alarmbell_options/admin_user_monitoring/alarmbell_admin_user_login_monitoring_email');

            Mage::helper('alarmbell/data')->email($logMessage, "Failed admin user login", $emailAddress);
        }
    }

}
