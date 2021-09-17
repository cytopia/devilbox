<?php
/**
 * Library for extracting information about the available storage engines
 */

declare(strict_types=1);

namespace PhpMyAdmin;

use PhpMyAdmin\Engines\Bdb;
use PhpMyAdmin\Engines\Berkeleydb;
use PhpMyAdmin\Engines\Binlog;
use PhpMyAdmin\Engines\Innobase;
use PhpMyAdmin\Engines\Innodb;
use PhpMyAdmin\Engines\Memory;
use PhpMyAdmin\Engines\Merge;
use PhpMyAdmin\Engines\MrgMyisam;
use PhpMyAdmin\Engines\Myisam;
use PhpMyAdmin\Engines\Ndbcluster;
use PhpMyAdmin\Engines\Pbxt;
use PhpMyAdmin\Engines\PerformanceSchema;
use PhpMyAdmin\Html\Generator;
use PhpMyAdmin\Utils\SessionCache;
use function array_key_exists;
use function define;
use function explode;
use function htmlspecialchars;
use function mb_stripos;
use function mb_strtolower;
use function sprintf;

/**
* defines
*/
define('PMA_ENGINE_SUPPORT_NO', 0);
define('PMA_ENGINE_SUPPORT_DISABLED', 1);
define('PMA_ENGINE_SUPPORT_YES', 2);
define('PMA_ENGINE_SUPPORT_DEFAULT', 3);

define('PMA_ENGINE_DETAILS_TYPE_PLAINTEXT', 0);
define('PMA_ENGINE_DETAILS_TYPE_SIZE', 1);
define('PMA_ENGINE_DETAILS_TYPE_NUMERIC', 2); //Has no effect yet...
define('PMA_ENGINE_DETAILS_TYPE_BOOLEAN', 3); // 'ON' or 'OFF'

/**
 * Base Storage Engine Class
 */
class StorageEngine
{
    /** @var string engine name */
    public $engine  = 'dummy';

    /** @var string engine title/description */
    public $title   = 'PMA Dummy Engine Class';

    /** @var string engine lang description */
    public $comment
        = 'If you read this text inside phpMyAdmin, something went wrong...';

    /** @var int engine supported by current server */
    public $support = PMA_ENGINE_SUPPORT_NO;

    /**
     * @param string $engine The engine ID
     */
    public function __construct($engine)
    {
        $storage_engines = self::getStorageEngines();
        if (empty($storage_engines[$engine])) {
            return;
        }

        $this->engine  = $engine;
        $this->title   = $storage_engines[$engine]['Engine'];
        $this->comment = ($storage_engines[$engine]['Comment'] ?? '');
        switch ($storage_engines[$engine]['Support']) {
            case 'DEFAULT':
                $this->support = PMA_ENGINE_SUPPORT_DEFAULT;
                break;
            case 'YES':
                $this->support = PMA_ENGINE_SUPPORT_YES;
                break;
            case 'DISABLED':
                $this->support = PMA_ENGINE_SUPPORT_DISABLED;
                break;
            case 'NO':
            default:
                $this->support = PMA_ENGINE_SUPPORT_NO;
        }
    }

    /**
     * Returns array of storage engines
     *
     * @return array[] array of storage engines
     *
     * @static
     * @staticvar array $storage_engines storage engines
     * @access public
     */
    public static function getStorageEngines()
    {
        global $dbi;

        static $storage_engines = null;

        if ($storage_engines == null) {
            $storage_engines = $dbi->fetchResult('SHOW STORAGE ENGINES', 'Engine');
            if ($dbi->getVersion() >= 50708) {
                $disabled = (string) SessionCache::get(
                    'disabled_storage_engines',
                    static function () use ($dbi) {
                        return $dbi->fetchValue(
                            'SELECT @@disabled_storage_engines'
                        );
                    }
                );
                foreach (explode(',', $disabled) as $engine) {
                    if (! isset($storage_engines[$engine])) {
                        continue;
                    }

                    $storage_engines[$engine]['Support'] = 'DISABLED';
                }
            }
        }

        return $storage_engines;
    }

    /**
     * @return array<int|string, array<string, mixed>>
     */
    public static function getArray(): array
    {
        $engines = [];

        foreach (self::getStorageEngines() as $details) {
            // Don't show PERFORMANCE_SCHEMA engine (MySQL 5.5)
            if ($details['Support'] === 'NO'
                || $details['Support'] === 'DISABLED'
                || $details['Engine'] === 'PERFORMANCE_SCHEMA'
            ) {
                continue;
            }

            $engines[$details['Engine']] = [
                'name' => $details['Engine'],
                'comment' => $details['Comment'],
                'is_default' => $details['Support'] === 'DEFAULT',
            ];
        }

        return $engines;
    }

    /**
     * Loads the corresponding engine plugin, if available.
     *
     * @param string $engine The engine ID
     *
     * @return StorageEngine The engine plugin
     *
     * @static
     */
    public static function getEngine($engine)
    {
        switch (mb_strtolower($engine)) {
            case 'bdb':
                return new Bdb($engine);
            case 'berkeleydb':
                return new Berkeleydb($engine);
            case 'binlog':
                return new Binlog($engine);
            case 'innobase':
                return new Innobase($engine);
            case 'innodb':
                return new Innodb($engine);
            case 'memory':
                return new Memory($engine);
            case 'merge':
                return new Merge($engine);
            case 'mrg_myisam':
                return new MrgMyisam($engine);
            case 'myisam':
                return new Myisam($engine);
            case 'ndbcluster':
                return new Ndbcluster($engine);
            case 'pbxt':
                return new Pbxt($engine);
            case 'performance_schema':
                return new PerformanceSchema($engine);
            default:
                return new StorageEngine($engine);
        }
    }

    /**
     * Returns true if given engine name is supported/valid, otherwise false
     *
     * @param string $engine name of engine
     *
     * @return bool whether $engine is valid or not
     *
     * @static
     */
    public static function isValid($engine)
    {
        if ($engine === 'PBMS') {
            return true;
        }
        $storage_engines = self::getStorageEngines();

        return isset($storage_engines[$engine]);
    }

    /**
     * Returns as HTML table of the engine's server variables
     *
     * @return string The table that was generated based on the retrieved
     *                information
     */
    public function getHtmlVariables()
    {
        $ret        = '';

        foreach ($this->getVariablesStatus() as $details) {
            $ret .= '<tr>' . "\n"
                  . '    <td>' . "\n";
            if (! empty($details['desc'])) {
                $ret .= '        '
                    . Generator::showHint($details['desc'])
                    . "\n";
            }
            $ret .= '    </td>' . "\n"
                  . '    <th scope="row">' . htmlspecialchars($details['title']) . '</th>'
                  . "\n"
                  . '    <td class="text-monospace text-right">';
            switch ($details['type']) {
                case PMA_ENGINE_DETAILS_TYPE_SIZE:
                    $parsed_size = $this->resolveTypeSize($details['value']);
                    $ret .= $parsed_size[0] . '&nbsp;' . $parsed_size[1];
                    unset($parsed_size);
                    break;
                case PMA_ENGINE_DETAILS_TYPE_NUMERIC:
                    $ret .= Util::formatNumber($details['value']) . ' ';
                    break;
                default:
                    $ret .= htmlspecialchars($details['value']) . '   ';
            }
            $ret .= '</td>' . "\n"
                  . '</tr>' . "\n";
        }

        if (! $ret) {
            $ret = '<p>' . "\n"
                . '    '
                . __(
                    'There is no detailed status information available for this '
                    . 'storage engine.'
                )
                . "\n"
                . '</p>' . "\n";
        } else {
            $ret = '<table class="table table-light table-striped table-hover w-auto">'
                . "\n" . $ret . '</table>' . "\n";
        }

        return $ret;
    }

    /**
     * Returns the engine specific handling for
     * PMA_ENGINE_DETAILS_TYPE_SIZE type variables.
     *
     * This function should be overridden when
     * PMA_ENGINE_DETAILS_TYPE_SIZE type needs to be
     * handled differently for a particular engine.
     *
     * @param int $value Value to format
     *
     * @return array the formatted value and its unit
     */
    public function resolveTypeSize($value)
    {
        return Util::formatByteDown($value);
    }

    /**
     * Returns array with detailed info about engine specific server variables
     *
     * @return array array with detailed info about specific engine server variables
     */
    public function getVariablesStatus()
    {
        global $dbi;

        $variables = $this->getVariables();
        $like = $this->getVariablesLikePattern();

        if ($like) {
            $like = " LIKE '" . $like . "' ";
        } else {
            $like = '';
        }

        $mysql_vars = [];

        $sql_query = 'SHOW GLOBAL VARIABLES ' . $like . ';';
        $res = $dbi->query($sql_query);
        while ($row = $dbi->fetchAssoc($res)) {
            if (isset($variables[$row['Variable_name']])) {
                $mysql_vars[$row['Variable_name']]
                    = $variables[$row['Variable_name']];
            } elseif (! $like
                && mb_stripos($row['Variable_name'], $this->engine) !== 0
            ) {
                continue;
            }
            $mysql_vars[$row['Variable_name']]['value'] = $row['Value'];

            if (empty($mysql_vars[$row['Variable_name']]['title'])) {
                $mysql_vars[$row['Variable_name']]['title'] = $row['Variable_name'];
            }

            if (isset($mysql_vars[$row['Variable_name']]['type'])) {
                continue;
            }

            $mysql_vars[$row['Variable_name']]['type']
                = PMA_ENGINE_DETAILS_TYPE_PLAINTEXT;
        }
        $dbi->freeResult($res);

        return $mysql_vars;
    }

    /**
     * Reveals the engine's title
     *
     * @return string The title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Fetches the server's comment about this engine
     *
     * @return string The comment
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Information message on whether this storage engine is supported
     *
     * @return string The localized message.
     */
    public function getSupportInformationMessage()
    {
        switch ($this->support) {
            case PMA_ENGINE_SUPPORT_DEFAULT:
                $message = __('%s is the default storage engine on this MySQL server.');
                break;
            case PMA_ENGINE_SUPPORT_YES:
                $message = __('%s is available on this MySQL server.');
                break;
            case PMA_ENGINE_SUPPORT_DISABLED:
                $message = __('%s has been disabled for this MySQL server.');
                break;
            case PMA_ENGINE_SUPPORT_NO:
            default:
                $message = __(
                    'This MySQL server does not support the %s storage engine.'
                );
        }

        return sprintf($message, htmlspecialchars($this->title));
    }

    /**
     * Generates a list of MySQL variables that provide information about this
     * engine. This function should be overridden when extending this class
     * for a particular engine.
     *
     * @return array The list of variables.
     */
    public function getVariables()
    {
        return [];
    }

    /**
     * Returns string with filename for the MySQL helppage
     * about this storage engine
     *
     * @return string MySQL help page filename
     */
    public function getMysqlHelpPage()
    {
        return $this->engine . '-storage-engine';
    }

    /**
     * Returns the pattern to be used in the query for SQL variables
     * related to the storage engine
     *
     * @return string SQL query LIKE pattern
     */
    public function getVariablesLikePattern()
    {
        return '';
    }

    /**
     * Returns a list of available information pages with labels
     *
     * @return string[] The list
     */
    public function getInfoPages()
    {
        return [];
    }

    /**
     * Generates the requested information page
     *
     * @param string $id page id
     *
     * @return string html output
     */
    public function getPage($id)
    {
        if (! array_key_exists($id, $this->getInfoPages())) {
            return '';
        }

        $id = 'getPage' . $id;

        return $this->$id();
    }
}
