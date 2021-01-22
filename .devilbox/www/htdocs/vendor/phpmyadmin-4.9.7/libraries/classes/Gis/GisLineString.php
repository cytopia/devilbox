<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * Handles actions related to GIS LINESTRING objects
 *
 * @package PhpMyAdmin-GIS
 */

namespace PhpMyAdmin\Gis;

use TCPDF;

/**
 * Handles actions related to GIS LINESTRING objects
 *
 * @package PhpMyAdmin-GIS
 */
class GisLineString extends GisGeometry
{
    // Hold the singleton instance of the class
    private static $_instance;

    /**
     * A private constructor; prevents direct creation of object.
     *
     * @access private
     */
    private function __construct()
    {
    }

    /**
     * Returns the singleton.
     *
     * @return GisLineString the singleton
     * @access public
     */
    public static function singleton()
    {
        if (!isset(self::$_instance)) {
            $class = __CLASS__;
            self::$_instance = new $class;
        }

        return self::$_instance;
    }

    /**
     * Scales each row.
     *
     * @param string $spatial spatial data of a row
     *
     * @return array an array containing the min, max values for x and y coordinates
     * @access public
     */
    public function scaleRow($spatial)
    {
        // Trim to remove leading 'LINESTRING(' and trailing ')'
        $linestring
            = mb_substr(
                $spatial,
                11,
                mb_strlen($spatial) - 12
            );

        return $this->setMinMax($linestring, array());
    }

    /**
     * Adds to the PNG image object, the data related to a row in the GIS dataset.
     *
     * @param string $spatial    GIS LINESTRING object
     * @param string $label      Label for the GIS LINESTRING object
     * @param string $line_color Color for the GIS LINESTRING object
     * @param array  $scale_data Array containing data related to scaling
     * @param object $image      Image object
     *
     * @return resource the modified image object
     * @access public
     */
    public function prepareRowAsPng(
        $spatial,
        $label,
        $line_color,
        array $scale_data,
        $image
    ) {
        // allocate colors
        $black = imagecolorallocate($image, 0, 0, 0);
        $red = hexdec(mb_substr($line_color, 1, 2));
        $green = hexdec(mb_substr($line_color, 3, 2));
        $blue = hexdec(mb_substr($line_color, 4, 2));
        $color = imagecolorallocate($image, $red, $green, $blue);

        // Trim to remove leading 'LINESTRING(' and trailing ')'
        $linesrting
            = mb_substr(
                $spatial,
                11,
                mb_strlen($spatial) - 12
            );
        $points_arr = $this->extractPoints($linesrting, $scale_data);

        foreach ($points_arr as $point) {
            if (!isset($temp_point)) {
                $temp_point = $point;
            } else {
                // draw line section
                imageline(
                    $image,
                    $temp_point[0],
                    $temp_point[1],
                    $point[0],
                    $point[1],
                    $color
                );
                $temp_point = $point;
            }
        }
        // print label if applicable
        if (isset($label) && trim($label) != '') {
            imagestring(
                $image,
                1,
                $points_arr[1][0],
                $points_arr[1][1],
                trim($label),
                $black
            );
        }

        return $image;
    }

    /**
     * Adds to the TCPDF instance, the data related to a row in the GIS dataset.
     *
     * @param string $spatial    GIS LINESTRING object
     * @param string $label      Label for the GIS LINESTRING object
     * @param string $line_color Color for the GIS LINESTRING object
     * @param array  $scale_data Array containing data related to scaling
     * @param TCPDF  $pdf        TCPDF instance
     *
     * @return TCPDF the modified TCPDF instance
     * @access public
     */
    public function prepareRowAsPdf($spatial, $label, $line_color, array $scale_data, $pdf)
    {
        // allocate colors
        $red = hexdec(mb_substr($line_color, 1, 2));
        $green = hexdec(mb_substr($line_color, 3, 2));
        $blue = hexdec(mb_substr($line_color, 4, 2));
        $line = array('width' => 1.5, 'color' => array($red, $green, $blue));

        // Trim to remove leading 'LINESTRING(' and trailing ')'
        $linesrting
            = mb_substr(
                $spatial,
                11,
                mb_strlen($spatial) - 12
            );
        $points_arr = $this->extractPoints($linesrting, $scale_data);

        foreach ($points_arr as $point) {
            if (!isset($temp_point)) {
                $temp_point = $point;
            } else {
                // draw line section
                $pdf->Line(
                    $temp_point[0],
                    $temp_point[1],
                    $point[0],
                    $point[1],
                    $line
                );
                $temp_point = $point;
            }
        }
        // print label
        if (isset($label) && trim($label) != '') {
            $pdf->SetXY($points_arr[1][0], $points_arr[1][1]);
            $pdf->SetFontSize(5);
            $pdf->Cell(0, 0, trim($label));
        }

        return $pdf;
    }

    /**
     * Prepares and returns the code related to a row in the GIS dataset as SVG.
     *
     * @param string $spatial    GIS LINESTRING object
     * @param string $label      Label for the GIS LINESTRING object
     * @param string $line_color Color for the GIS LINESTRING object
     * @param array  $scale_data Array containing data related to scaling
     *
     * @return string the code related to a row in the GIS dataset
     * @access public
     */
    public function prepareRowAsSvg($spatial, $label, $line_color, array $scale_data)
    {
        $line_options = array(
            'name'         => $label,
            'id'           => $label . rand(),
            'class'        => 'linestring vector',
            'fill'         => 'none',
            'stroke'       => $line_color,
            'stroke-width' => 2,
        );

        // Trim to remove leading 'LINESTRING(' and trailing ')'
        $linesrting
            = mb_substr(
                $spatial,
                11,
                mb_strlen($spatial) - 12
            );
        $points_arr = $this->extractPoints($linesrting, $scale_data);

        $row = '<polyline points="';
        foreach ($points_arr as $point) {
            $row .= $point[0] . ',' . $point[1] . ' ';
        }
        $row .= '"';
        foreach ($line_options as $option => $val) {
            $row .= ' ' . $option . '="' . trim($val) . '"';
        }
        $row .= '/>';

        return $row;
    }

    /**
     * Prepares JavaScript related to a row in the GIS dataset
     * to visualize it with OpenLayers.
     *
     * @param string $spatial    GIS LINESTRING object
     * @param int    $srid       Spatial reference ID
     * @param string $label      Label for the GIS LINESTRING object
     * @param string $line_color Color for the GIS LINESTRING object
     * @param array  $scale_data Array containing data related to scaling
     *
     * @return string JavaScript related to a row in the GIS dataset
     * @access public
     */
    public function prepareRowAsOl($spatial, $srid, $label, $line_color, array $scale_data)
    {
        $style_options = array(
            'strokeColor' => $line_color,
            'strokeWidth' => 2,
            'label'       => $label,
            'fontSize'    => 10,
        );
        if ($srid == 0) {
            $srid = 4326;
        }
        $result = $this->getBoundsForOl($srid, $scale_data);

        // Trim to remove leading 'LINESTRING(' and trailing ')'
        $linesrting
            = mb_substr(
                $spatial,
                11,
                mb_strlen($spatial) - 12
            );
        $points_arr = $this->extractPoints($linesrting, null);

        $result .= 'vectorLayer.addFeatures(new OpenLayers.Feature.Vector('
            . $this->getLineForOpenLayers($points_arr, $srid)
            . ', null, ' . json_encode($style_options) . '));';

        return $result;
    }

    /**
     * Generate the WKT with the set of parameters passed by the GIS editor.
     *
     * @param array  $gis_data GIS data
     * @param int    $index    Index into the parameter object
     * @param string $empty    Value for empty points
     *
     * @return string WKT with the set of parameters passed by the GIS editor
     * @access public
     */
    public function generateWkt(array $gis_data, $index, $empty = '')
    {
        $no_of_points = isset($gis_data[$index]['LINESTRING']['no_of_points'])
            ? $gis_data[$index]['LINESTRING']['no_of_points'] : 2;
        if ($no_of_points < 2) {
            $no_of_points = 2;
        }
        $wkt = 'LINESTRING(';
        for ($i = 0; $i < $no_of_points; $i++) {
            $wkt .= ((isset($gis_data[$index]['LINESTRING'][$i]['x'])
                    && trim($gis_data[$index]['LINESTRING'][$i]['x']) != '')
                    ? $gis_data[$index]['LINESTRING'][$i]['x'] : $empty)
                . ' ' . ((isset($gis_data[$index]['LINESTRING'][$i]['y'])
                    && trim($gis_data[$index]['LINESTRING'][$i]['y']) != '')
                    ? $gis_data[$index]['LINESTRING'][$i]['y'] : $empty) . ',';
        }

        $wkt
            = mb_substr(
                $wkt,
                0,
                mb_strlen($wkt) - 1
            );
        $wkt .= ')';

        return $wkt;
    }

    /**
     * Generate parameters for the GIS data editor from the value of the GIS column.
     *
     * @param string $value of the GIS column
     * @param int    $index of the geometry
     *
     * @return array params for the GIS data editor from the value of the GIS column
     * @access public
     */
    public function generateParams($value, $index = -1)
    {
        $params = array();
        if ($index == -1) {
            $index = 0;
            $data = GisGeometry::generateParams($value);
            $params['srid'] = $data['srid'];
            $wkt = $data['wkt'];
        } else {
            $params[$index]['gis_type'] = 'LINESTRING';
            $wkt = $value;
        }

        // Trim to remove leading 'LINESTRING(' and trailing ')'
        $linestring
            = mb_substr(
                $wkt,
                11,
                mb_strlen($wkt) - 12
            );
        $points_arr = $this->extractPoints($linestring, null);

        $no_of_points = count($points_arr);
        $params[$index]['LINESTRING']['no_of_points'] = $no_of_points;
        for ($i = 0; $i < $no_of_points; $i++) {
            $params[$index]['LINESTRING'][$i]['x'] = $points_arr[$i][0];
            $params[$index]['LINESTRING'][$i]['y'] = $points_arr[$i][1];
        }

        return $params;
    }
}
