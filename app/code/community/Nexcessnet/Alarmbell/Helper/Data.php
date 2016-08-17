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
class Nexcessnet_Alarmbell_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Formats and logs a message through Magento's logging facility.
     *
     * @param string $message
     *
     * @return string
     */
    public function log($message)
    {
        // get the client IP
        $remoteIp = Mage::helper('core/http')->getRemoteAddr();

        // get user's admin username (if logged-in)
        $adminUsername = '';
        $admin = Mage::getSingleton('admin/session')->getUser();
        if (is_object($admin)) {
            if ($admin->getId()) {
                $adminUsername = $admin->getUsername();
            }
        } else {
            $adminusername = '';
        }

        // construct log message, and log it
        $logMessage = 'ALARMBELL ('.$remoteIp.')';
        if (!empty($adminUsername)) {
            $logMessage .= " [$adminUsername]";
        }
        $logMessage .= ': ' . $message;
        Mage::log($logMessage);

        //$this->email($logMessage);

        return $logMessage;
    }

    /**
     * Sends an email via Zend framework - bypassing Magento's cron
     * to avoid mail delays, problems with cron, etc.
     *
     * @param string $message
     * @param string $subject
     * @param string $emailAddress
     *
     * @return bool
     */
    public function email($message, $subject = '', $emailAddress)
    {
        if (empty($subject)) {
            $subject = $message;
        }

        // check for blank/invalid email
        $fromEmailAddress = Mage::getStoreConfig('alarmbell_options/admin_user_monitoring/alarmbell_admin_user_email_from');

        if (!empty($emailAddress)) {
            $validator = new Zend_Validate_EmailAddress();
            if ($validator->isValid($emailAddress)) { // check for valid 'to' email address
                $emailSubjectPrefix = Mage::getStoreConfig('alarmbell_options/admin_user_monitoring/alarmbell_admin_user_email_subject');

                if ((empty($fromEmailAddress)) || (!$validator->isValid($fromEmailAddress))) {
                    // use current admin user's email address for 'from' address
                    $fromEmailAddress = Mage::getSingleton('admin/session')->getUser()->getEmail();
                }

                Mage::log('Sending email from '.$fromEmailAddress.' to '.$emailAddress);

                // send it
                $mail = new Zend_Mail();
                $mail->setBodyText($message)
                ->setFrom($fromEmailAddress)
                ->addTo($emailAddress)
                ->setSubject($emailSubjectPrefix.' '.$subject)
                ->send();

                return true;
            }
        }

        return false;
    }
}
