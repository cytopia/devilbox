<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * Handles actions related to GIS GEOMETRYCOLLECTION objects
 *
 * @package PhpMyAdmin-GIS
 */
declare(strict_types=1);

namespace PhpMyAdmin\Gis;

use TCPDF;

/**
 * Handles actions related to GIS GEOMETRYCOLLECTION objects
 *
 * @package PhpMyAdmin-GIS
 */
class GisGeometryCollection extends GisGeometry
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
     * @return GisGeometryCollection the singleton
     * @access public
     */
    public static function singleton()
    {
        if (! isset(self::$_instance)) {
            self::$_instance = new GisGeometryCollection();
        }

        return self::$_instance;
    }

    /**
     * Scales each row.
     *
     * @param string $spatial spatial data of a row
     *
     * @return array array containing the min, max values for x and y coordinates
     * @access public
     */
    public function scaleRow($spatial)
    {
        $min_max = [];

        // Trim to remove leading 'GEOMETRYCOLLECTION(' and trailing ')'
        $goem_col
            = mb_substr(
                $spatial,
                19,
                mb_strlen($spatial) - 20
            );

        // Split the geometry collection object to get its constituents.
        $sub_parts = $this->_explodeGeomCol($goem_col);

        foreach ($sub_parts as $sub_part) {
            $type_pos = mb_strpos($sub_part, '(');
            if ($type_pos === false) {
                continue;
            }
            $type = mb_substr($sub_part, 0, $type_pos);

            $gis_obj = GisFactory::factory($type);
            if (! $gis_obj) {
                continue;
            }
            $scale_data = $gis_obj->scaleRow($sub_part);

            // Update minimum/maximum values for x and y coordinates.
            $c_maxX = (float) $scale_data['maxX'];
            if (! isset($min_max['maxX']) || $c_maxX > $min_max['maxX']) {
                $min_max['maxX'] = $c_maxX;
            }

            $c_minX = (float) $scale_data['minX'];
            if (! isset($min_max['minX']) || $c_minX < $min_max['minX']) {
                $min_max['minX'] = $c_minX;
            }

            $c_maxY = (float) $scale_data['maxY'];
            if (! isset($min_max['maxY']) || $c_maxY > $min_max['maxY']) {
                $min_max['maxY'] = $c_maxY;
            }

            $c_minY = (float) $scale_data['minY'];
            if (! isset($min_max['minY']) || $c_minY < $min_max['minY']) {
                $min_max['minY'] = $c_minY;
            }
        }

        return $min_max;
    }

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
     * @access public
     */
    public function prepareRowAsPng($spatial, ?string $label, $color, array $scale_data, $image)
    {
        // Trim to remove leading 'GEOMETRYCOLLECTION(' and trailing ')'
        $goem_col
            = mb_substr(
                $spatial,
                19,
                mb_strlen($spatial) - 20
            );
        // Split the geometry collection object to get its constituents.
        $sub_parts = $this->_explodeGeomCol($goem_col);

        foreach ($sub_parts as $sub_part) {
            $type_pos = mb_strpos($sub_part, '(');
            if ($type_pos === false) {
                continue;
            }
            $type = mb_substr($sub_part, 0, $type_pos);

            $gis_obj = GisFactory::factory($type);
            if (! $gis_obj) {
                continue;
            }
            $image = $gis_obj->prepareRowAsPng(
                $sub_part,
                $label,
                $color,
                $scale_data,
                $image
            );
        }

        return $image;
    }

    /**
     * Adds to the TCPDF instance, the data related to a row in the GIS dataset.
     *
     * @param string      $spatial    GIS GEOMETRYCOLLECTION object
     * @param string|null $label      label for the GIS GEOMETRYCOLLECTION object
     * @param string      $color      color for the GIS GEOMETRYCOLLECTION object
     * @param array       $scale_data array containing data related to scaling
     * @param TCPDF       $pdf        TCPDF instance
     *
     * @return TCPDF the modified TCPDF instance
     * @access public
     */
    public function prepareRowAsPdf($spatial, ?string $label, $color, array $scale_data, $pdf)
    {
        // Trim to remove leading 'GEOMETRYCOLLECTION(' and trailing ')'
        $goem_col
            = mb_substr(
                $spatial,
                19,
                mb_strlen($spatial) - 20
            );
        // Split the geometry collection object to get its constituents.
        $sub_parts = $this->_explodeGeomCol($goem_col);

        foreach ($sub_parts as $sub_part) {
            $type_pos = mb_strpos($sub_part, '(');
            if ($type_pos === false) {
                continue;
            }
            $type = mb_substr($sub_part, 0, $type_pos);

            $gis_obj = GisFactory::factory($type);
            if (! $gis_obj) {
                continue;
            }
            $pdf = $gis_obj->prepareRowAsPdf(
                $sub_part,
                $label,
                $color,
                $scale_data,
                $pdf
            );
        }

        return $pdf;
    }

    /**
     * Prepares and returns the code related to a row in the GIS dataset as SVG.
     *
     * @param string $spatial    GIS GEOMETRYCOLLECTION object
     * @param string $label      label for the GIS GEOMETRYCOLLECTION object
     * @param string $color      color for the GIS GEOMETRYCOLLECTION object
     * @param array  $scale_data array containing data related to scaling
     *
     * @return string the code related to a row in the GIS dataset
     * @access public
     */
    public function prepareRowAsSvg($spatial, $label, $color, array $scale_data)
    {
        $row = '';

        // Trim to remove leading 'GEOMETRYCOLLECTION(' and trailing ')'
        $goem_col
            = mb_substr(
                $spatial,
                19,
                mb_strlen($spatial) - 20
            );
        // Split the geometry collection object to get its constituents.
        $sub_parts = $this->_explodeGeomCol($goem_col);

        foreach ($sub_parts as $sub_part) {
            $type_pos = mb_strpos($sub_part, '(');
            if ($type_pos === false) {
                continue;
            }
            $type = mb_substr($sub_part, 0, $type_pos);

            $gis_obj = GisFactory::factory($type);
            if (! $gis_obj) {
                continue;
            }
            $row .= $gis_obj->prepareRowAsSvg(
                $sub_part,
                $label,
                $color,
                $scale_data
            );
        }

        return $row;
    }

    /**
     * Prepares JavaScript related to a row in the GIS dataset
     * to visualize it with OpenLayers.
     *
     * @param string $spatial    GIS GEOMETRYCOLLECTION object
     * @param int    $srid       spatial reference ID
     * @param string $label      label for the GIS GEOMETRYCOLLECTION object
     * @param string $color      color for the GIS GEOMETRYCOLLECTION object
     * @param array  $scale_data array containing data related to scaling
     *
     * @return string JavaScript related to a row in the GIS dataset
     * @access public
     */
    public function prepareRowAsOl($spatial, $srid, $label, $color, array $scale_data)
    {
        $row = '';

        // Trim to remove leading 'GEOMETRYCOLLECTION(' and trailing ')'
        $goem_col
            = mb_substr(
                $spatial,
                19,
                mb_strlen($spatial) - 20
            );
        // Split the geometry collection object to get its constituents.
        $sub_parts = $this->_explodeGeomCol($goem_col);

        foreach ($sub_parts as $sub_part) {
            $type_pos = mb_strpos($sub_part, '(');
            if ($type_pos === false) {
                continue;
            }
            $type = mb_substr($sub_part, 0, $type_pos);

            $gis_obj = GisFactory::factory($type);
            if (! $gis_obj) {
                continue;
            }
            $row .= $gis_obj->prepareRowAsOl(
                $sub_part,
                $srid,
                $label,
                $color,
                $scale_data
            );
        }

        return $row;
    }

    /**
     * Splits the GEOMETRYCOLLECTION object and get its constituents.
     *
     * @param string $geom_col geometry collection string
     *
     * @return array the constituents of the geometry collection object
     * @access private
     */
    private function _explodeGeomCol($geom_col)
    {
        $sub_parts = [];
        $br_count = 0;
        $start = 0;
        $count = 0;
        foreach (str_split($geom_col) as $char) {
            if ($char == '(') {
                $br_count++;
            } elseif ($char == ')') {
                $br_count--;
                if ($br_count == 0) {
                    $sub_parts[]
                        = mb_substr(
                            $geom_col,
                            $start,
                            $count + 1 - $start
                        );
                    $start = $count + 2;
                }
            }
            $count++;
        }

        return $sub_parts;
    }

    /**
     * Generates the WKT with the set of parameters passed by the GIS editor.
     *
     * @param array  $gis_data GIS data
     * @param int    $index    index into the parameter object
     * @param string $empty    value for empty points
     *
     * @return string WKT with the set of parameters passed by the GIS editor
     * @access public
     */
    public function generateWkt(array $gis_data, $index, $empty = '')
    {
        $geom_count = isset($gis_data['GEOMETRYCOLLECTION']['geom_count'])
            ? $gis_data['GEOMETRYCOLLECTION']['geom_count'] : 1;
        $wkt = 'GEOMETRYCOLLECTION(';
        for ($i = 0; $i < $geom_count; $i++) {
            if (isset($gis_data[$i]['gis_type'])) {
                $type = $gis_data[$i]['gis_type'];
                $gis_obj = GisFactory::factory($type);
                if (! $gis_obj) {
                    continue;
                }
                $wkt .= $gis_obj->generateWkt($gis_data, $i, $empty) . ',';
            }
        }
        if (isset($gis_data[0]['gis_type'])) {
            $wkt
                = mb_substr(
                    $wkt,
                    0,
                    mb_strlen($wkt) - 1
                );
        }
        $wkt .= ')';

        return $wkt;
    }

    /**
     * Generates parameters for the GIS data editor from the value of the GIS column.
     *
     * @param string $value of the GIS column
     *
     * @return array parameters for the GIS editor from the value of the GIS column
     * @access public
     */
    public function generateParams($value)
    {
        $params = [];
        $data = GisGeometry::generateParams($value);
        $params['srid'] = $data['srid'];
        $wkt = $data['wkt'];

        // Trim to remove leading 'GEOMETRYCOLLECTION(' and trailing ')'
        $goem_col
            = mb_substr(
                $wkt,
                19,
                mb_strlen($wkt) - 20
            );
        // Split the geometry collection object to get its constituents.
        $sub_parts = $this->_explodeGeomCol($goem_col);
        $params['GEOMETRYCOLLECTION']['geom_count'] = count($sub_parts);

        $i = 0;
        foreach ($sub_parts as $sub_part) {
            $type_pos = mb_strpos($sub_part, '(');
            if ($type_pos === false) {
                continue;
            }
            $type = mb_substr($sub_part, 0, $type_pos);
            /**
             * @var GisMultiPolygon|GisPolygon|GisMultiPoint|GisPoint|GisMultiLineString|GisLineString $gis_obj
             */
            $gis_obj = GisFactory::factory($type);
            if (! $gis_obj) {
                continue;
            }
            $params = array_merge($params, $gis_obj->generateParams($sub_part, $i));
            $i++;
        }

        return $params;
    }
}
