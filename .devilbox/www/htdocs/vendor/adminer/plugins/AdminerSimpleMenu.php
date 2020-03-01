<?php

/**
 * Displays only one prefered action in table list.
 *
 * Get rid of schizophrenic decisions between selecting data and showing table structure.
 * Optimize your workflow!
 *
 * @link https://github.com/pematon/adminer-plugins
 *
 * @author Peter Knut
 * @copyright 2014-2018 Pematon, s.r.o. (http://www.pematon.com/)
 */
class AdminerSimpleMenu
{
    /** @var bool */
    private $preferSelect;

    /** @var bool */
    private $reorderLinks;

    /**
     * @param bool $preferSelect Whether to prefer table selection before editing structure.
     * @param bool $reorderLinks Whether links above data table will be reordered to reflect workflow priority.
     */
    public function __construct($preferSelect = true, $reorderLinks = true)
    {
        $this->preferSelect = $preferSelect;
        $this->reorderLinks = $reorderLinks;
    }

    /**
     * Prints table list in menu.
     *
     * @param array $tables Table list.
     * @return bool|null
     */
    public function tablesPrint($tables)
    {
        if (defined("PMTN_ADMINER_THEME")) {
            echo "<ul id='tables' class='simple'>\n";
        } else {
            echo "<ul id='tables' class='simple'>" . script("mixin(qs('#tables'), {onmouseover: menuOver, onmouseout: menuOut});");
        }

        $actions = [$_GET["select"], $_GET["edit"], $_GET["table"], $_GET["create"], $_GET["indexes"], $_GET["foreign"], $_GET["trigger"]];

        foreach ($tables as $table => $status) {
            $name = Adminer::tableName($status);
            if ($name == "") {
                continue;
            }

            $active = in_array($table, $actions);

            if ($this->preferSelect) {
                $action = "select";
                $title = "Select data";
            } else {
                $action = "table";
                $title = "Show structure";
            }

            echo "<li data-table-name='$name'>";
            if ($this->preferSelect || support("table") || support("indexes")) {
                echo '<a href="' . h(ME) . $action . '=' . urlencode($table) . '"' . bold($active, (is_view($status) ? "view" : "")) . "' data-link='main'>$name</a>";
            } else {
                echo "<span data-link='main'>$name</span>";
            }
            echo "</li>\n";
        }

        echo "</ul>\n";

        return true;
    }

    /*
     * Prints links after select heading.
     *
     * @param array $tableStatus Result of SHOW TABLE STATUS.
     * @param string $set New item options, NULL for no new item.
     *
     * @return bool|null
     */
    public function selectLinks($tableStatus, $set = "")
    {
        if (!$this->reorderLinks) {
            return null; // null has to be returned to force Adminer print original links.
        }

        echo '<p class="links">';

        $links = [];

        if ($this->preferSelect) {
            $links["select"] = lang('Select data');
        }

        if (support("table") || support("indexes")) {
            $links["table"] = lang('Show structure');
        }

        if (!$this->preferSelect) {
            $links["select"] = lang('Select data');
        }

        if (support("table")) {
            if (is_view($tableStatus)) {
                $links["view"] = lang('Alter view');
            } else {
                $links["create"] = lang('Alter table');
            }
        }

        if ($set !== null) {
            $links["edit"] = lang('New item');
        }

        foreach ($links as $key => $val) {
            echo " <a href='" . h(ME) . "$key=" . urlencode($tableStatus["Name"]) . ($key == "edit" ? $set : "") . "'" . bold(isset($_GET[$key])) . ">$val</a>";
        }

        echo "\n";
        return true;
    }
}
