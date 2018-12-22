<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * Table partition definition
 *
 * @package PhpMyAdmin
 */

use PhpMyAdmin\Core;

if (!isset($partitionDetails)) {

    $partitionDetails = array();

    // Extract some partitioning and subpartitioning parameters from the request
    $partitionParams = array(
        'partition_by', 'partition_expr',
        'subpartition_by', 'subpartition_expr',
    );
    foreach ($partitionParams as $partitionParam) {
        $partitionDetails[$partitionParam] = isset($_POST[$partitionParam])
            ? $_POST[$partitionParam] : '';
    }

    if (Core::isValid($_POST['partition_count'], 'numeric')) {
        // MySQL's limit is 8192, so do not allow more
        $partition_count = min(intval($_POST['partition_count']), 8192);
    } else {
        $partition_count = 0;
    }
    $partitionDetails['partition_count']
        = ($partition_count === 0) ? '' : $partition_count;
    if (Core::isValid($_POST['subpartition_count'], 'numeric')) {
        // MySQL's limit is 8192, so do not allow more
        $subpartition_count = min(intval($_POST['subpartition_count']), 8192);
    } else {
        $subpartition_count = 0;
    }
    $partitionDetails['subpartition_count']
        = ($subpartition_count === 0) ? '' : $subpartition_count;

    // Only LIST and RANGE type parameters allow subpartitioning
    $partitionDetails['can_have_subpartitions'] = $partition_count > 1
        && isset($_POST['partition_by'])
        && ($_POST['partition_by'] == 'RANGE'
        || $_POST['partition_by'] == 'RANGE COLUMNS'
        || $_POST['partition_by'] == 'LIST'
        || $_POST['partition_by'] == 'LIST COLUMNS');

    // Values are specified only for LIST and RANGE type partitions
    $partitionDetails['value_enabled'] = isset($_POST['partition_by'])
        && ($_POST['partition_by'] == 'RANGE'
        || $_POST['partition_by'] == 'RANGE COLUMNS'
        || $_POST['partition_by'] == 'LIST'
        || $_POST['partition_by'] == 'LIST COLUMNS');

    // Has partitions
    if ($partition_count > 1) {
        $partitions = isset($_POST['partitions'])
            ? $_POST['partitions']
            : array();

        // Remove details of the additional partitions
        // when number of partitions have been reduced
        array_splice($partitions, $partition_count);

        for ($i = 0; $i < $partition_count; $i++) {
            if (! isset($partitions[$i])) { // Newly added partition
                $partitions[$i] = array(
                    'name' => 'p' . $i,
                    'value_type' => '',
                    'value' => '',
                    'engine' => '',
                    'comment' => '',
                    'data_directory' => '',
                    'index_directory' => '',
                    'max_rows' => '',
                    'min_rows' => '',
                    'tablespace' => '',
                    'node_group' => '',
                );
            }

            $partition =& $partitions[$i];
            $partition['prefix'] = 'partitions[' . $i . ']';

            // Changing from HASH/KEY to RANGE/LIST
            if (! isset($partition['value_type'])) {
                $partition['value_type'] = '';
                $partition['value'] = '';
            }
            if (! isset($partition['engine'])) { // When removing subpartitioning
                $partition['engine'] = '';
                $partition['comment'] = '';
                $partition['data_directory'] = '';
                $partition['index_directory'] = '';
                $partition['max_rows'] = '';
                $partition['min_rows'] = '';
                $partition['tablespace'] = '';
                $partition['node_group'] = '';
            }

            if ($subpartition_count > 1
                && $partitionDetails['can_have_subpartitions'] == true
            ) { // Has subpartitions
                $partition['subpartition_count'] = $subpartition_count;

                if (! isset($partition['subpartitions'])) {
                    $partition['subpartitions'] = array();
                }
                $subpartitions =& $partition['subpartitions'];

                // Remove details of the additional subpartitions
                // when number of subpartitions have been reduced
                array_splice($subpartitions, $subpartition_count);

                for ($j = 0; $j < $subpartition_count; $j++) {
                    if (! isset($subpartitions[$j])) { // Newly added subpartition
                        $subpartitions[$j] = array(
                            'name' => $partition['name'] . '_s' . $j,
                            'engine' => '',
                            'comment' => '',
                            'data_directory' => '',
                            'index_directory' => '',
                            'max_rows' => '',
                            'min_rows' => '',
                            'tablespace' => '',
                            'node_group' => '',
                        );
                    }

                    $subpartition =& $subpartitions[$j];
                    $subpartition['prefix'] = 'partitions[' . $i . ']'
                        . '[subpartitions][' . $j . ']';
                }
            } else { // No subpartitions
                unset($partition['subpartitions']);
                unset($partition['subpartition_count']);
            }
        }
        $partitionDetails['partitions'] = $partitions;
    }
}
