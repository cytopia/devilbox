<?php
/**
 * Base class for all GIS data type classes
 */

declare(strict_types=1);

namespace PhpMyAdmin\Gis;

use TCPDF;
use function explode;
use function floatval;
use function intval;
use function mb_strlen;
use function mb_strripos;
use function mb_substr;
use function mt_rand;
use function preg_match;
use function sprintf;
use function str_replace;
use function trim;

/**
 * Base class for all GIS data type classes.
 */
abstract class GisGeometry
{
    /**
     * Prepares and returns the code related to a row in the GIS dataset as SVG.
     *
     * @param string $spatial    GIS data object
     * @param string $label      label for the GIS data object
     * @param string $color      color for the GIS data object
     * @param array  $scale_data data related to scaling
     *
     * @return string the code related to a row in the GIS dataset
     *
     * @access public
     */
    abstract public function prepareRowAsSvg($spatial, $label, $color, array $scale_data);

    /**
     * Adds to the PNG image object, the data related to a row in the GIS dataset.
     *
     * @param string      $spatial    GIS POLYGON object
     * @param string|null $label      Label for the GIS POLYGON object
     * @param string      $color      Color for the GIS POLYGON object
     * @param array       $scale_data Array containing data related to scaling
     * @param resource    $image      Image object
     *
     * @return resource the modified image object
     *
     * @access public
     */
    abstract public function prepareRowAsPng(
        $spatial,
        ?string $label,
        $color,
        array $scale_data,
        $image
    );

    /**
     * Adds to the TCPDF instance, the data related to a row in the GIS dataset.
     *
     * @param string      $spatial    GIS data object
     * @param string|null $label      label for the GIS data object
     * @param string      $color      color for the GIS data object
     * @param array       $scale_data array containing data related to scaling
     * @param TCPDF       $pdf        TCPDF instance
     *
     * @return TCPDF the modified TCPDF instance
     *
     * @access public
     */
    abstract public function prepareRowAsPdf(
        $spatial,
        ?string $label,
        $color,
        array $scale_data,
        $pdf
    );

    /**
     * Prepares the JavaScript related to a row in the GIS dataset
     * to visualize it with OpenLayers.
     *
     * @param string $spatial    GIS data object
     * @param int    $srid       spatial reference ID
     * @param string $label      label for the GIS data object
     * @param array  $color      color for the GIS data object
     * @param array  $scale_data array containing data related to scaling
     *
     * @return string the JavaScript related to a row in the GIS dataset
     *
     * @access public
     */
    abstract public function prepareRowAsOl(
        $spatial,
        $srid,
        $label,
        $color,
        array $scale_data
    );

    /**
     * Scales each row.
     *
     * @param string $spatial spatial data of a row
     *
     * @return array array containing the min, max values for x and y coordinates
     *
     * @access public
     */
    abstract public function scaleRow($spatial);

    /**
     * Generates the WKT with the set of parameters passed by the GIS editor.
     *
     * @param array  $gis_data GIS data
     * @param int    $index    index into the parameter object
     * @param string $empty    value for empty points
     *
     * @return string WKT with the set of parameters passed by the GIS editor
     *
     * @access public
     */
    abstract public function generateWkt(array $gis_data, $index, $empty = '');

    /**
     * Returns OpenLayers.Bounds object that correspond to the bounds of GIS data.
     *
     * @param string $srid       spatial reference ID
     * @param array  $scale_data data related to scaling
     *
     * @return string OpenLayers.Bounds object that
     *                correspond to the bounds of GIS data
     *
     * @access protected
     */
    protected function getBoundsForOl($srid, array $scale_data)
    {
        return sprintf(
            'var minLoc = [%s, %s];'
            . 'var maxLoc = [%s, %s];'
            . 'var ext = ol.extent.boundingExtent([minLoc, maxLoc]);'
            . 'ext = ol.proj.transformExtent(ext, ol.proj.get("EPSG:%s"), ol.proj.get(\'EPSG:3857\'));'
            . 'map.getView().fit(ext, map.getSize());',
            $scale_data['minX'],
            $scale_data['minY'],
            $scale_data['maxX'],
            $scale_data['maxY'],
            intval($srid)
        );
    }

    /**
     * Updates the min, max values with the given point set.
     *
     * @param string $point_set point set
     * @param array  $min_max   existing min, max values
     *
     * @return array the updated min, max values
     *
     * @access protected
     */
    protected function setMinMax($point_set, array $min_max)
    {
        // Separate each point
        $points = explode(',', $point_set);

        foreach ($points as $point) {
            // Extract coordinates of the point
            $cordinates = explode(' ', $point);

            $x = (float) $cordinates[0];
            if (! isset($min_max['maxX']) || $x > $min_max['maxX']) {
                $min_max['maxX'] = $x;
            }
            if (! isset($min_max['minX']) || $x < $min_max['minX']) {
                $min_max['minX'] = $x;
            }
            $y = (float) $cordinates[1];
            if (! isset($min_max['maxY']) || $y > $min_max['maxY']) {
                $min_max['maxY'] = $y;
            }
            if (isset($min_max['minY']) && $y >= $min_max['minY']) {
                continue;
            }

            $min_max['minY'] = $y;
        }

        return $min_max;
    }

    /**
     * Generates parameters for the GIS data editor from the value of the GIS column.
     * This method performs common work.
     * More specific work is performed by each of the geom classes.
     *
     * @param string $value value of the GIS column
     *
     * @return array parameters for the GIS editor from the value of the GIS column
     *
     * @access protected
     */
    public function generateParams($value)
    {
        $geom_types = '(POINT|MULTIPOINT|LINESTRING|MULTILINESTRING'
            . '|POLYGON|MULTIPOLYGON|GEOMETRYCOLLECTION)';
        $srid = 0;
        $wkt = '';

        if (preg_match("/^'" . $geom_types . "\(.*\)',[0-9]*$/i", $value)) {
            $last_comma = mb_strripos($value, ',');
            $srid = trim(mb_substr($value, $last_comma + 1));
            $wkt = trim(mb_substr($value, 1, $last_comma - 2));
        } elseif (preg_match('/^' . $geom_types . '\(.*\)$/i', $value)) {
            $wkt = $value;
        }

        return [
            'srid' => $srid,
            'wkt' => $wkt,
        ];
    }

    /**
     * Extracts points, scales and returns them as an array.
     *
     * @param string     $point_set  string of comma separated points
     * @param array|null $scale_data data related to scaling
     * @param bool       $linear     if true, as a 1D array, else as a 2D array
     *
     * @return array scaled points
     *
     * @access protected
     */
    protected function extractPoints($point_set, $scale_data, $linear = false): array
    {
        $points_arr = [];

        // Separate each point
        $points = explode(',', $point_set);

        foreach ($points as $point) {
            $point = str_replace(['(', ')'], '', $point);
            // Extract coordinates of the point
            $coordinates = explode(' ', $point);

            if (isset($coordinates[0], $coordinates[1]) && trim($coordinates[0]) != '' && trim($coordinates[1]) != '') {
                if ($scale_data != null) {
                    $x = ($coordinates[0] - $scale_data['x']) * $scale_data['scale'];
                    $y = $scale_data['height']
                        - ($coordinates[1] - $scale_data['y']) * $scale_data['scale'];
                } else {
                    $x = floatval(trim($coordinates[0]));
                    $y = floatval(trim($coordinates[1]));
                }
            } else {
                $x = 0;
                $y = 0;
            }

            if (! $linear) {
                $points_arr[] = [
                    $x,
                    $y,
                ];
            } else {
                $points_arr[] = $x;
                $points_arr[] = $y;
            }
        }

        return $points_arr;
    }

    /**
     * Generates JavaScript for adding an array of polygons to OpenLayers.
     *
     * @param array  $polygons x and y coordinates for each polygon
     * @param string $srid     spatial reference id
     *
     * @return string JavaScript for adding an array of polygons to OpenLayers
     *
     * @access protected
     */
    protected function getPolygonArrayForOpenLayers(array $polygons, $srid)
    {
        $ol_array = 'var polygonArray = [];';
        foreach ($polygons as $polygon) {
            $rings = explode('),(', $polygon);
            $ol_array .= $this->getPolygonForOpenLayers($rings, $srid);
            $ol_array .= 'polygonArray.push(polygon);';
        }

        return $ol_array;
    }

    /**
     * Generates JavaScript for adding points for OpenLayers polygon.
     *
     * @param array  $polygon x and y coordinates for each line
     * @param string $srid    spatial reference id
     *
     * @return string JavaScript for adding points for OpenLayers polygon
     *
     * @access protected
     */
    protected function getPolygonForOpenLayers(array $polygon, $srid)
    {
        return $this->getLineArrayForOpenLayers($polygon, $srid, false)
        . 'var polygon = new ol.geom.Polygon(arr);';
    }

    /**
     * Generates JavaScript for adding an array of LineString
     * or LineRing to OpenLayers.
     *
     * @param array  $lines          x and y coordinates for each line
     * @param string $srid           spatial reference id
     * @param bool   $is_line_string whether it's an array of LineString
     *
     * @return string JavaScript for adding an array of LineString
     *                or LineRing to OpenLayers
     *
     * @access protected
     */
    protected function getLineArrayForOpenLayers(
        array $lines,
        $srid,
        $is_line_string = true
    ) {
        $ol_array = 'var arr = [];';
        foreach ($lines as $line) {
            $ol_array .= 'var lineArr = [];';
            $points_arr = $this->extractPoints($line, null);
            $ol_array .= 'var line = ' . $this->getLineForOpenLayers(
                $points_arr,
                $srid,
                $is_line_string
            ) . ';';
            $ol_array .= 'var coord = line.getCoordinates();';
            $ol_array .= 'for (var i = 0; i < coord.length; i++) lineArr.push(coord[i]);';
            $ol_array .= 'arr.push(lineArr);';
        }

        return $ol_array;
    }

    /**
     * Generates JavaScript for adding a LineString or LineRing to OpenLayers.
     *
     * @param array  $points_arr     x and y coordinates for each point
     * @param string $srid           spatial reference id
     * @param bool   $is_line_string whether it's a LineString
     *
     * @return string JavaScript for adding a LineString or LineRing to OpenLayers
     *
     * @access protected
     */
    protected function getLineForOpenLayers(
        array $points_arr,
        $srid,
        $is_line_string = true
    ) {
        return 'new ol.geom.'
        . ($is_line_string ? 'LineString' : 'LinearRing') . '('
        . $this->getPointsArrayForOpenLayers($points_arr, $srid)
        . ')';
    }

    /**
     * Generates JavaScript for adding an array of points to OpenLayers.
     *
     * @param array  $points_arr x and y coordinates for each point
     * @param string $srid       spatial reference id
     *
     * @return string JavaScript for adding an array of points to OpenLayers
     *
     * @access protected
     */
    protected function getPointsArrayForOpenLayers(array $points_arr, $srid)
    {
        $ol_array = 'new Array(';
        foreach ($points_arr as $point) {
            $ol_array .= $this->getPointForOpenLayers($point, $srid) . '.getCoordinates(), ';
        }

        $ol_array
            = mb_substr(
                $ol_array,
                0,
                mb_strlen($ol_array) - 2
            );

        return $ol_array . ')';
    }

    /**
     * Generates JavaScript for adding a point to OpenLayers.
     *
     * @param array  $point array containing the x and y coordinates of the point
     * @param string $srid  spatial reference id
     *
     * @return string JavaScript for adding points to OpenLayers
     *
     * @access protected
     */
    protected function getPointForOpenLayers(array $point, $srid)
    {
        return '(new ol.geom.Point([' . $point[0] . ',' . $point[1] . '])'
        . '.transform(ol.proj.get("EPSG:' . ((int) $srid) . '")'
        . ', ol.proj.get(\'EPSG:3857\')))';
    }

    protected function getRandomId(): int
    {
        return mt_rand();
    }
}
