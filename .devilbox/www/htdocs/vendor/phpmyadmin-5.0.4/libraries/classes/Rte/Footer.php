<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * Common functions for generating the footer for Routines, Triggers and Events.
 *
 * @package PhpMyAdmin
 */
declare(strict_types=1);

namespace PhpMyAdmin\Rte;

use PhpMyAdmin\DatabaseInterface;
use PhpMyAdmin\Util;

/**
 * PhpMyAdmin\Rte\Footer class
 *
 * @package PhpMyAdmin
 */
class Footer
{
    /**
     * @var Words
     */
    private $words;

    /**
     * @var DatabaseInterface
     */
    private $dbi;

    /**
     * Footer constructor.
     *
     * @param DatabaseInterface $dbi DatabaseInterface object
     */
    public function __construct(DatabaseInterface $dbi)
    {
        $this->dbi = $dbi;
        $this->words = new Words();
    }

    /**
     * Creates a fieldset for adding a new item, if the user has the privileges.
     *
     * @param string $docu String used to create a link to the MySQL docs
     * @param string $priv Privilege to check for adding a new item
     * @param string $name MySQL name of the item
     *
     * @return string An HTML snippet with the link to add a new item
     */
    private function getLinks($docu, $priv, $name)
    {
        global $db, $table, $url_query;

        $icon = mb_strtolower($name) . '_add';
        $retval  = "";
        $retval .= "<!-- ADD " . $name . " FORM START -->\n";
        $retval .= "<fieldset class='left'>\n";
        $retval .= "<legend>" . _pgettext('Create new procedure', 'New') . "</legend>\n";
        $retval .= "        <div class='wrap'>\n";
        if (Util::currentUserHasPrivilege($priv, $db, $table)) {
            $retval .= '            <a class="ajax add_anchor" ';
            $retval .= "href='db_" . mb_strtolower($name) . "s.php";
            $retval .= "$url_query&amp;add_item=1' ";
            $retval .= "onclick='$.datepicker.initialized = false;'>";
            $icon = 'b_' . $icon;
            $retval .= Util::getIcon($icon);
            $retval .= $this->words->get('add') . "</a>\n";
        } else {
            $icon = 'bd_' . $icon;
            $retval .= Util::getIcon($icon);
            $retval .= $this->words->get('add') . "\n";
        }
        $retval .= "            " . Util::showMySQLDocu($docu) . "\n";
        $retval .= "        </div>\n";
        $retval .= "</fieldset>\n";
        $retval .= "<!-- ADD " . $name . " FORM END -->\n\n";

        return $retval;
    }

    /**
     * Creates a fieldset for adding a new routine, if the user has the privileges.
     *
     * @return string    HTML code with containing the footer fieldset
     */
    public function routines()
    {
        return $this->getLinks('CREATE_PROCEDURE', 'CREATE ROUTINE', 'ROUTINE');
    }

    /**
     * Creates a fieldset for adding a new trigger, if the user has the privileges.
     *
     * @return string    HTML code with containing the footer fieldset
     */
    public function triggers()
    {
        return $this->getLinks('CREATE_TRIGGER', 'TRIGGER', 'TRIGGER');
    }

    /**
     * Creates a fieldset for adding a new event, if the user has the privileges.
     *
     * @return string    HTML code with containing the footer fieldset
     */
    public function events()
    {
        global $db, $url_query;

        /**
         * For events, we show the usual 'Add event' form and also
         * a form for toggling the state of the event scheduler
         */
        // Init options for the event scheduler toggle functionality
        $es_state = $this->dbi->fetchValue(
            "SHOW GLOBAL VARIABLES LIKE 'event_scheduler'",
            0,
            1
        );
        $es_state = mb_strtolower($es_state);
        $options = [
            0 => [
                'label' => __('OFF'),
                'value' => "SET GLOBAL event_scheduler=\"OFF\"",
                'selected' => $es_state != 'on',
            ],
            1 => [
                'label' => __('ON'),
                'value' => "SET GLOBAL event_scheduler=\"ON\"",
                'selected' => $es_state == 'on',
            ],
        ];
        // Generate output
        $retval  = "<!-- FOOTER LINKS START -->\n";
        $retval .= "<div class='doubleFieldset'>\n";
        // show the usual footer
        $retval .= $this->getLinks('CREATE_EVENT', 'EVENT', 'EVENT');
        $retval .= "    <fieldset class='right'>\n";
        $retval .= "        <legend>\n";
        $retval .= "            " . __('Event scheduler status') . "\n";
        $retval .= "        </legend>\n";
        $retval .= "        <div class='wrap'>\n";
        // show the toggle button
        $retval .= Util::toggleButton(
            "sql.php$url_query&amp;goto=db_events.php" . urlencode("?db=$db"),
            'sql_query',
            $options,
            'Functions.slidingMessage(data.sql_query);'
        );
        $retval .= "        </div>\n";
        $retval .= "    </fieldset>\n";
        $retval .= "    <div class='clearfloat'></div>\n";
        $retval .= "</div>";
        $retval .= "<!-- FOOTER LINKS END -->\n";

        return $retval;
    }
}
