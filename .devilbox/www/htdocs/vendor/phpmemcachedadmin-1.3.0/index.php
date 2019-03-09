<?php
/**
 * Copyright 2010 Cyrille Mahieux
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and limitations
 * under the License.
 *
 * ><)))°> ><)))°> ><)))°> ><)))°> ><)))°> ><)))°> ><)))°> ><)))°> ><)))°>
 *
 * Stats viewing
 *
 * @author elijaa@free.fr
 * @since 20/03/2010
 */
# Require
require_once 'Library/Bootstrap.php';

# Initializing requests
$request = (isset($_REQUEST['show'])) ? $_REQUEST['show'] : null;

# Getting default cluster
if (! isset($_REQUEST['server'])) {
    $clusters = array_keys($_ini->get('servers'));
    $cluster = isset($clusters[0]) ? $clusters[0] : null;
    $_REQUEST['server'] = $cluster;
}

# Showing header
include 'View/Header.phtml';

# Display by request type
switch ($request) {
    # Items : Display of all items for a single slab for a single server
    case 'items' :
        # Initializing items array
        $server = null;
        $items = false;

        # Ask for one server and one slabs items
        if (isset($_REQUEST['server']) && ($server = $_ini->server($_REQUEST['server']))) {
            $items = Library_Command_Factory::instance('items_api')->items($server['hostname'], $server['port'], $_REQUEST['slab']);
        }

        # Getting stats to calculate server boot time
        $stats = Library_Command_Factory::instance('stats_api')->stats($server['hostname'], $server['port']);
        $infinite = (isset($stats['time'], $stats['uptime'])) ? ($stats['time'] - $stats['uptime']) : 0;

        # Items are well formed
        if ($items !== false) {
            # Showing items
            include 'View/Stats/Items.phtml';
        }         # Items are not well formed
        else {
            include 'View/Stats/Error.phtml';
        }
        unset($items);
        break;

    # Slabs : Display of all slabs for a single server
    case 'slabs' :
        # Initializing slabs array
        $slabs = false;

        # Ask for one server slabs
        if (isset($_REQUEST['server']) && ($server = $_ini->server($_REQUEST['server']))) {
            # Spliting server in hostname:port
            $slabs = Library_Command_Factory::instance('slabs_api')->slabs($server['hostname'], $server['port']);
        }

        # Slabs are well formed
        if ($slabs !== false) {
            # Analysis
            $slabs = Library_Data_Analysis::slabs($slabs);
            include 'View/Stats/Slabs.phtml';
        }         # Slabs are not well formed
        else {
            include 'View/Stats/Error.phtml';
        }
        unset($slabs);
        break;

    # Default : Stats for all or specific single server
    default :
        # Initializing stats & settings array
        $stats = array();
        $slabs = array();
        $slabs['total_malloced'] = 0;
        $slabs['total_wasted'] = 0;
        $settings = array();
        $status = array();

        $cluster = null;
        $server = null;

        # Ask for a particular cluster stats
        if (isset($_REQUEST['server']) && ($cluster = $_ini->cluster($_REQUEST['server']))) {
            foreach ($cluster as $name => $server) {
                # Getting Stats & Slabs stats
                $data = array();
                $data['stats'] = Library_Command_Factory::instance('stats_api')->stats($server['hostname'], $server['port']);
                $data['slabs'] = Library_Data_Analysis::slabs(Library_Command_Factory::instance('slabs_api')->slabs($server['hostname'], $server['port']));
                $stats = Library_Data_Analysis::merge($stats, $data['stats']);

                # Computing stats
                if (isset($data['slabs']['total_malloced'], $data['slabs']['total_wasted'])) {
                    $slabs['total_malloced'] += $data['slabs']['total_malloced'];
                    $slabs['total_wasted'] += $data['slabs']['total_wasted'];
                }
                $status[$name] = ($data['stats'] != array()) ? $data['stats']['version'] : '';
                $uptime[$name] = ($data['stats'] != array()) ? $data['stats']['uptime'] : '';
            }
        }        # Asking for a server stats
        elseif (isset($_REQUEST['server']) && ($server = $_ini->server($_REQUEST['server']))) {
            # Getting Stats & Slabs stats
            $stats = Library_Command_Factory::instance('stats_api')->stats($server['hostname'], $server['port']);
            $slabs = Library_Data_Analysis::slabs(Library_Command_Factory::instance('slabs_api')->slabs($server['hostname'], $server['port']));
            $settings = Library_Command_Factory::instance('stats_api')->settings($server['hostname'], $server['port']);
        }

        # Stats are well formed
        if (($stats !== false) && ($stats != array())) {
            # Analysis
            $stats = Library_Data_Analysis::stats($stats);
            include 'View/Stats/Stats.phtml';
        }         # Stats are not well formed
        else {
            include 'View/Stats/Error.phtml';
        }
        unset($stats);
        break;
}
# Showing footer
include 'View/Footer.phtml';