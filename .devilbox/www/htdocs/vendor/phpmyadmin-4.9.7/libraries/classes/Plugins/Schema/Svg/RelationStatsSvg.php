<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * Contains PhpMyAdmin\Plugins\Schema\Svg\RelationStatsSvg class
 *
 * @package PhpMyAdmin
 */
namespace PhpMyAdmin\Plugins\Schema\Svg;

use PhpMyAdmin\Plugins\Schema\RelationStats;

/**
 * Relation preferences/statistics
 *
 * This class fetches the table master and foreign fields positions
 * and helps in generating the Table references and then connects
 * master table's master field to foreign table's foreign key
 * in SVG XML document.
 *
 * @package PhpMyAdmin
 * @name    Relation_Stats_Svg
 * @see     PMA_SVG::printElementLine
 */
class RelationStatsSvg extends RelationStats
{
    /**
     * The "PhpMyAdmin\Plugins\Schema\Svg\RelationStatsSvg" constructor
     *
     * @param object $diagram       The SVG diagram
     * @param string $master_table  The master table name
     * @param string $master_field  The relation field in the master table
     * @param string $foreign_table The foreign table name
     * @param string $foreign_field The relation field in the foreign table
     */
    public function __construct(
        $diagram,
        $master_table,
        $master_field,
        $foreign_table,
        $foreign_field
    ) {
        $this->wTick = 10;
        parent::__construct(
            $diagram,
            $master_table,
            $master_field,
            $foreign_table,
            $foreign_field
        );
    }

    /**
     * draws relation links and arrows shows foreign key relations
     *
     * @param boolean $showColor Whether to use one color per relation or not
     *
     * @return void
     * @access public
     *
     * @see    PMA_SVG
     */
    public function relationDraw($showColor)
    {
        if ($showColor) {
            $listOfColors = array(
                '#c00',
                '#bbb',
                '#333',
                '#cb0',
                '#0b0',
                '#0bf',
                '#b0b',
            );
            shuffle($listOfColors);
            $color = $listOfColors[0];
        } else {
            $color = '#333';
        }

        $this->diagram->printElementLine(
            'line',
            $this->xSrc,
            $this->ySrc,
            $this->xSrc + $this->srcDir * $this->wTick,
            $this->ySrc,
            'stroke:' . $color . ';stroke-width:1;'
        );
        $this->diagram->printElementLine(
            'line',
            $this->xDest + $this->destDir * $this->wTick,
            $this->yDest,
            $this->xDest,
            $this->yDest,
            'stroke:' . $color . ';stroke-width:1;'
        );
        $this->diagram->printElementLine(
            'line',
            $this->xSrc + $this->srcDir * $this->wTick,
            $this->ySrc,
            $this->xDest + $this->destDir * $this->wTick,
            $this->yDest,
            'stroke:' . $color . ';stroke-width:1;'
        );
        $root2 = 2 * sqrt(2);
        $this->diagram->printElementLine(
            'line',
            $this->xSrc + $this->srcDir * $this->wTick * 0.75,
            $this->ySrc,
            $this->xSrc + $this->srcDir * (0.75 - 1 / $root2) * $this->wTick,
            $this->ySrc + $this->wTick / $root2,
            'stroke:' . $color . ';stroke-width:2;'
        );
        $this->diagram->printElementLine(
            'line',
            $this->xSrc + $this->srcDir * $this->wTick * 0.75,
            $this->ySrc,
            $this->xSrc + $this->srcDir * (0.75 - 1 / $root2) * $this->wTick,
            $this->ySrc - $this->wTick / $root2,
            'stroke:' . $color . ';stroke-width:2;'
        );
        $this->diagram->printElementLine(
            'line',
            $this->xDest + $this->destDir * $this->wTick / 2,
            $this->yDest,
            $this->xDest + $this->destDir * (0.5 + 1 / $root2) * $this->wTick,
            $this->yDest + $this->wTick / $root2,
            'stroke:' . $color . ';stroke-width:2;'
        );
        $this->diagram->printElementLine(
            'line',
            $this->xDest + $this->destDir * $this->wTick / 2,
            $this->yDest,
            $this->xDest + $this->destDir * (0.5 + 1 / $root2) * $this->wTick,
            $this->yDest - $this->wTick / $root2,
            'stroke:' . $color . ';stroke-width:2;'
        );
    }
}
