<?php
/**
 * Nexcess.net Alarmbell Extension for Magento
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

class Nexcessnet_Alarmbell_Model_Observer
{

    protected $_helper;
    protected $_userLoginMonitoringEmail;
    protected $_adminUserEmail;

    public function __construct()
    {
        $this->_helper = Mage::helper('alarmbell/data');
        $this->_userLoginMonitoringEmail = Mage::getStoreConfig('alarmbell_options/admin_user_monitoring/alarmbell_admin_user_login_monitoring_email');
        $this->_adminUserEmail = Mage::getStoreConfig('alarmbell_options/admin_user_monitoring/alarmbell_admin_user_email');
    }

    /**
     * Log the admin user action
     * @param  Varien_Event_Observer $observer 
     * @return Varien_Event_Observer
     */
    public function logAdminUserSave(Varien_Event_Observer $observer)
    {
        // only log if enabled via config
        $enabled = Mage::getStoreConfigFlag('alarmbell_options/admin_user_monitoring/alarmbell_admin_user_change_monitoring_enable');
        if (!$enabled) {
            return $this;
        }

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
        $logMessage = $this->_helper->log($message);
        $emailAddress = $this->_adminUserEmail;
        $this->_helper->email($logMessage, $emailSubject,$emailAddress);

        return $this;
    }

    /**
     * Log an admin user delete
     * @param  Varien_Event_Observer $observer 
     * @return Varien_Event_Observer
     */
    public function logAdminUserDelete(Varien_Event_Observer $observer)
    {
        // only log if enabled via config
        $enabled = Mage::getStoreConfigFlag('alarmbell_options/admin_user_monitoring/alarmbell_admin_user_change_monitoring_enable');
        if (!$enabled) {
            return $this;
        }

        $user_data = Mage::getModel('admin/user')->load( $observer->getEvent()->getObject()->getUserId() )->getData();
        $message = "Admin user '" . $user_data['username'] . "' deleted";
        $logMessage = $this->_helper->log($message);
        $emailAddress = $this->_adminUserEmail;

        $this->_helper->email($logMessage, 'Admin user deleted', $emailAddress);

        return $this;
    }

    /**
     * Log admin user login
     * @param  Varien_Event_Observer $observer 
     * @return Varien_Event_Observer
     */
    public function logAdminUserLoginSuccess(Varien_Event_Observer $observer)
    {
        // only log if enabled via config
        $enabled = Mage::getStoreConfigFlag('alarmbell_options/admin_user_monitoring/alarmbell_admin_user_login_monitoring_enable');
        if (!$enabled) {
            return $this;
        }

        $admin = Mage::getSingleton('admin/session')->getUser();

        if ($admin->getId()) {
            $admin_username = $admin->getUsername();
            $message = "Successful admin user log in";
            $logMessage = $this->_helper->log($message);
            $emailAddress = $this->_userLoginMonitoringEmail;
            $this->_helper->email($logMessage, $message, $emailAddress);
        }

        return $this;
    }

    /**
     * Log admin user failed login
     * @param  Varien_Event_Observer $observer 
     * @return Varien_Event_Observer
     */
    public function logAdminUserLoginFail(Varien_Event_Observer $observer)
    {
        $username = $observer->getEvent()->getUserName();

        // only log if enabled via config
        $enabled = Mage::getStoreConfigFlag('alarmbell_options/admin_user_monitoring/alarmbell_admin_user_login_monitoring_enable');
        if (!$enabled) {
            return $this;
        }

        $message = "Failed admin user login for '" . $username . "'";
        $logMessage = $this->_helper->log($message);
        $emailAddress = $this->_userLoginMonitoringEmail;

        $this->_helper->email($logMessage, "Failed admin user login", $emailAddress);

        return $this;
    }

}
