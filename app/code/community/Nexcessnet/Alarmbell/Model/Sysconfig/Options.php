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
class Nexcessnet_Alarmbell_Model_Sysconfig_Options
{
    /**
     * Options getter - creates a list of options.
     */
    public function toOptionArray()
    {
        $options = array();
        $options[] = array('value' => 0, 'label' => 'Inactive');
        $options[] = array('value' => 1, 'label' => 'Active');

        return $options;
    }
}
