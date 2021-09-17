<?php
/**
 * Editor for Geometry data types.
 */

declare(strict_types=1);

namespace PhpMyAdmin\Controllers;

use PhpMyAdmin\Core;
use PhpMyAdmin\Gis\GisFactory;
use PhpMyAdmin\Gis\GisVisualization;
use function array_merge;
use function in_array;
use function intval;
use function mb_strpos;
use function mb_strtoupper;
use function mb_substr;
use function substr;
use function trim;

/**
 * Editor for Geometry data types.
 */
class GisDataEditorController extends AbstractController
{
    public function index(): void
    {
        global $gis_data, $gis_types, $start, $geom_type, $gis_obj, $srid, $wkt, $wkt_with_zero, $PMA_Theme;
        global $result, $visualizationSettings, $data, $visualization, $open_layers, $geom_count, $dbi;

        if (! isset($_POST['field'])) {
            return;
        }

        // Get data if any posted
        $gis_data = [];
        if (Core::isValid($_POST['gis_data'], 'array')) {
            $gis_data = $_POST['gis_data'];
        }

        $gis_types = [
            'POINT',
            'MULTIPOINT',
            'LINESTRING',
            'MULTILINESTRING',
            'POLYGON',
            'MULTIPOLYGON',
            'GEOMETRYCOLLECTION',
        ];

        // Extract type from the initial call and make sure that it's a valid one.
        // Extract from field's values if available, if not use the column type passed.
        if (! isset($gis_data['gis_type'])) {
            if (isset($_POST['type']) && $_POST['type'] != '') {
                $gis_data['gis_type'] = mb_strtoupper($_POST['type']);
            }
            if (isset($_POST['value']) && trim($_POST['value']) != '') {
                $start = substr($_POST['value'], 0, 1) == "'" ? 1 : 0;
                $gis_data['gis_type'] = mb_substr(
                    $_POST['value'],
                    $start,
                    mb_strpos($_POST['value'], '(') - $start
                );
            }
            if (! isset($gis_data['gis_type'])
                || (! in_array($gis_data['gis_type'], $gis_types))
            ) {
                $gis_data['gis_type'] = $gis_types[0];
            }
        }
        $geom_type = $gis_data['gis_type'];

        // Generate parameters from value passed.
        $gis_obj = GisFactory::factory($geom_type);
        if ($gis_obj === false) {
            return;
        }

        if (isset($_POST['value'])) {
            $gis_data = array_merge(
                $gis_data,
                $gis_obj->generateParams($_POST['value'])
            );
        }

        // Generate Well Known Text
        $srid = isset($gis_data['srid']) && $gis_data['srid'] != '' ? $gis_data['srid'] : 0;
        $wkt = $gis_obj->generateWkt($gis_data, 0);
        $wkt_with_zero = $gis_obj->generateWkt($gis_data, 0, '0');
        $result = "'" . $wkt . "'," . $srid;

        // Generate SVG based visualization
        $visualizationSettings = [
            'width' => 450,
            'height' => 300,
            'spatialColumn' => 'wkt',
            'mysqlVersion' => $dbi->getVersion(),
            'isMariaDB' => $dbi->isMariaDB(),
        ];
        $data = [
            [
                'wkt' => $wkt_with_zero,
                'srid' => $srid,
            ],
        ];
        $visualization = GisVisualization::getByData($data, $visualizationSettings)
            ->toImage('svg');

        $open_layers = GisVisualization::getByData($data, $visualizationSettings)
            ->asOl();

        // If the call is to update the WKT and visualization make an AJAX response
        if (isset($_POST['generate']) && $_POST['generate'] == true) {
            $this->response->addJSON([
                'result' => $result,
                'visualization' => $visualization,
                'openLayers' => $open_layers,
            ]);

            return;
        }

        $geom_count = 1;
        if ($geom_type === 'GEOMETRYCOLLECTION') {
            $geom_count = isset($gis_data[$geom_type]['geom_count'])
                ? intval($gis_data[$geom_type]['geom_count']) : 1;
            if (isset($gis_data[$geom_type]['add_geom'])) {
                $geom_count++;
            }
        }

        $templateOutput = $this->template->render('gis_data_editor_form', [
            'width' => $visualizationSettings['width'],
            'height' => $visualizationSettings['height'],
            'theme_image_path' => $PMA_Theme->getImgPath(),
            'field' => $_POST['field'],
            'input_name' => $_POST['input_name'],
            'srid' => $srid,
            'visualization' => $visualization,
            'open_layers' => $open_layers,
            'gis_types' => $gis_types,
            'geom_type' => $geom_type,
            'geom_count' => $geom_count,
            'gis_data' => $gis_data,
            'result' => $result,
        ]);

        $this->response->addJSON(['gis_editor' => $templateOutput]);
    }
}
