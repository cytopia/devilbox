<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * set of functions for user group handling
 *
 * @package PhpMyAdmin
 */
declare(strict_types=1);

namespace PhpMyAdmin\Server;

use PhpMyAdmin\Relation;
use PhpMyAdmin\Url;
use PhpMyAdmin\Util;

/**
 * PhpMyAdmin\Server\UserGroups class
 *
 * @package PhpMyAdmin
 */
class UserGroups
{
    /**
     * Return HTML to list the users belonging to a given user group
     *
     * @param string $userGroup user group name
     *
     * @return string HTML to list the users belonging to a given user group
     */
    public static function getHtmlForListingUsersofAGroup($userGroup)
    {
        $relation = new Relation($GLOBALS['dbi']);
        $html_output  = '<h2>'
            . sprintf(__('Users of \'%s\' user group'), htmlspecialchars($userGroup))
            . '</h2>';

        $cfgRelation = $relation->getRelationsParam();
        $usersTable = Util::backquote($cfgRelation['db'])
            . "." . Util::backquote($cfgRelation['users']);
        $sql_query = "SELECT `username` FROM " . $usersTable
            . " WHERE `usergroup`='" . $GLOBALS['dbi']->escapeString($userGroup)
            . "'";
        $result = $relation->queryAsControlUser($sql_query, false);
        if ($result) {
            if ($GLOBALS['dbi']->numRows($result) == 0) {
                $html_output .= '<p>'
                    . __('No users were found belonging to this user group.')
                    . '</p>';
            } else {
                $html_output .= '<table>'
                    . '<thead><tr><th>#</th><th>' . __('User') . '</th></tr></thead>'
                    . '<tbody>';
                $i = 0;
                while ($row = $GLOBALS['dbi']->fetchRow($result)) {
                    $i++;
                    $html_output .= '<tr>'
                        . '<td>' . $i . ' </td>'
                        . '<td>' . htmlspecialchars($row[0]) . '</td>'
                        . '</tr>';
                }
                $html_output .= '</tbody>'
                    . '</table>';
            }
        }
        $GLOBALS['dbi']->freeResult($result);
        return $html_output;
    }

    /**
     * Returns HTML for the 'user groups' table
     *
     * @return string HTML for the 'user groups' table
     */
    public static function getHtmlForUserGroupsTable()
    {
        $relation = new Relation($GLOBALS['dbi']);
        $html_output  = '<h2>' . __('User groups') . '</h2>';
        $cfgRelation = $relation->getRelationsParam();
        $groupTable = Util::backquote($cfgRelation['db'])
            . "." . Util::backquote($cfgRelation['usergroups']);
        $sql_query = "SELECT * FROM " . $groupTable . " ORDER BY `usergroup` ASC";
        $result = $relation->queryAsControlUser($sql_query, false);

        if ($result && $GLOBALS['dbi']->numRows($result)) {
            $html_output .= '<form name="userGroupsForm" id="userGroupsForm"'
                . ' action="server_privileges.php" method="post">';
            $html_output .= Url::getHiddenInputs();
            $html_output .= '<table id="userGroupsTable">';
            $html_output .= '<thead><tr>';
            $html_output .= '<th style="white-space: nowrap">'
                . __('User group') . '</th>';
            $html_output .= '<th>' . __('Server level tabs') . '</th>';
            $html_output .= '<th>' . __('Database level tabs') . '</th>';
            $html_output .= '<th>' . __('Table level tabs') . '</th>';
            $html_output .= '<th>' . __('Action') . '</th>';
            $html_output .= '</tr></thead>';
            $html_output .= '<tbody>';

            $userGroups = [];
            while ($row = $GLOBALS['dbi']->fetchAssoc($result)) {
                $groupName = $row['usergroup'];
                if (! isset($userGroups[$groupName])) {
                    $userGroups[$groupName] = [];
                }
                $userGroups[$groupName][$row['tab']] = $row['allowed'];
            }
            foreach ($userGroups as $groupName => $tabs) {
                $html_output .= '<tr>';
                $html_output .= '<td>' . htmlspecialchars($groupName) . '</td>';
                $html_output .= '<td>' . self::getAllowedTabNames($tabs, 'server') . '</td>';
                $html_output .= '<td>' . self::getAllowedTabNames($tabs, 'db') . '</td>';
                $html_output .= '<td>' . self::getAllowedTabNames($tabs, 'table') . '</td>';

                $html_output .= '<td>';
                $html_output .= '<a class="" href="server_user_groups.php" data-post="'
                    . Url::getCommon(
                        [
                            'viewUsers' => 1,
                            'userGroup' => $groupName,
                        ],
                        ''
                    )
                    . '">'
                    . Util::getIcon('b_usrlist', __('View users'))
                    . '</a>';
                $html_output .= '&nbsp;&nbsp;';
                $html_output .= '<a class="" href="server_user_groups.php" data-post="'
                    . Url::getCommon(
                        [
                            'editUserGroup' => 1,
                            'userGroup' => $groupName,
                        ],
                        ''
                    )
                    . '">'
                    . Util::getIcon('b_edit', __('Edit')) . '</a>';
                $html_output .= '&nbsp;&nbsp;';
                $html_output .= '<a class="deleteUserGroup ajax"'
                    . ' href="server_user_groups.php" data-post="'
                    . Url::getCommon(
                        [
                            'deleteUserGroup' => 1,
                            'userGroup' => $groupName,
                        ],
                        ''
                    )
                    . '">'
                    . Util::getIcon('b_drop', __('Delete')) . '</a>';
                $html_output .= '</td>';

                $html_output .= '</tr>';
            }

            $html_output .= '</tbody>';
            $html_output .= '</table>';
            $html_output .= '</form>';
        }
        $GLOBALS['dbi']->freeResult($result);

        $html_output .= '<fieldset id="fieldset_add_user_group">';
        $html_output .= '<a href="server_user_groups.php'
            . Url::getCommon(['addUserGroup' => 1]) . '">'
            . Util::getIcon('b_usradd')
            . __('Add user group') . '</a>';
        $html_output .= '</fieldset>';

        return $html_output;
    }

    /**
     * Returns the list of allowed menu tab names
     * based on a data row from usergroup table.
     *
     * @param array  $row   row of usergroup table
     * @param string $level 'server', 'db' or 'table'
     *
     * @return string comma separated list of allowed menu tab names
     */
    public static function getAllowedTabNames(array $row, $level)
    {
        $tabNames = [];
        $tabs = Util::getMenuTabList($level);
        foreach ($tabs as $tab => $tabName) {
            if (! isset($row[$level . '_' . $tab])
                || $row[$level . '_' . $tab] == 'Y'
            ) {
                $tabNames[] = $tabName;
            }
        }
        return implode(', ', $tabNames);
    }

    /**
     * Deletes a user group
     *
     * @param string $userGroup user group name
     *
     * @return void
     */
    public static function delete($userGroup)
    {
        $relation = new Relation($GLOBALS['dbi']);
        $cfgRelation = $relation->getRelationsParam();
        $userTable = Util::backquote($cfgRelation['db'])
            . "." . Util::backquote($cfgRelation['users']);
        $groupTable = Util::backquote($cfgRelation['db'])
            . "." . Util::backquote($cfgRelation['usergroups']);
        $sql_query = "DELETE FROM " . $userTable
            . " WHERE `usergroup`='" . $GLOBALS['dbi']->escapeString($userGroup)
            . "'";
        $relation->queryAsControlUser($sql_query, true);
        $sql_query = "DELETE FROM " . $groupTable
            . " WHERE `usergroup`='" . $GLOBALS['dbi']->escapeString($userGroup)
            . "'";
        $relation->queryAsControlUser($sql_query, true);
    }

    /**
     * Returns HTML for add/edit user group dialog
     *
     * @param string $userGroup name of the user group in case of editing
     *
     * @return string HTML for add/edit user group dialog
     */
    public static function getHtmlToEditUserGroup($userGroup = null)
    {
        $relation = new Relation($GLOBALS['dbi']);
        $html_output = '';
        if ($userGroup == null) {
            $html_output .= '<h2>' . __('Add user group') . '</h2>';
        } else {
            $html_output .= '<h2>'
                . sprintf(__('Edit user group: \'%s\''), htmlspecialchars($userGroup))
                . '</h2>';
        }

        $html_output .= '<form name="userGroupForm" id="userGroupForm"'
            . ' action="server_user_groups.php" method="post">';
        $urlParams = [];
        if ($userGroup != null) {
            $urlParams['userGroup'] = $userGroup;
            $urlParams['editUserGroupSubmit'] = '1';
        } else {
            $urlParams['addUserGroupSubmit'] = '1';
        }
        $html_output .= Url::getHiddenInputs($urlParams);

        $html_output .= '<fieldset id="fieldset_user_group_rights">';
        $html_output .= '<legend>' . __('User group menu assignments')
            . '&nbsp;&nbsp;&nbsp;'
            . '<input type="checkbox" id="addUsersForm_checkall" '
            . 'class="checkall_box" title="Check all">'
            . '<label for="addUsersForm_checkall">' . __('Check all') . '</label>'
            . '</legend>';

        if ($userGroup == null) {
            $html_output .= '<label for="userGroup">' . __('Group name:') . '</label>';
            $html_output .= '<input type="text" name="userGroup" maxlength="64" autocomplete="off" required="required">';
            $html_output .= '<div class="clearfloat"></div>';
        }

        $allowedTabs = [
            'server' => [],
            'db'     => [],
            'table'  => [],
        ];
        if ($userGroup != null) {
            $cfgRelation = $relation->getRelationsParam();
            $groupTable = Util::backquote($cfgRelation['db'])
                . "." . Util::backquote($cfgRelation['usergroups']);
            $sql_query = "SELECT * FROM " . $groupTable
                . " WHERE `usergroup`='" . $GLOBALS['dbi']->escapeString($userGroup)
                . "'";
            $result = $relation->queryAsControlUser($sql_query, false);
            if ($result) {
                while ($row = $GLOBALS['dbi']->fetchAssoc($result)) {
                    $key = $row['tab'];
                    $value = $row['allowed'];
                    if (substr($key, 0, 7) == 'server_' && $value == 'Y') {
                        $allowedTabs['server'][] = mb_substr($key, 7);
                    } elseif (substr($key, 0, 3) == 'db_' && $value == 'Y') {
                        $allowedTabs['db'][] = mb_substr($key, 3);
                    } elseif (substr($key, 0, 6) == 'table_'
                        && $value == 'Y'
                    ) {
                        $allowedTabs['table'][] = mb_substr($key, 6);
                    }
                }
            }
            $GLOBALS['dbi']->freeResult($result);
        }

        $html_output .= self::getTabList(
            __('Server-level tabs'),
            'server',
            $allowedTabs['server']
        );
        $html_output .= self::getTabList(
            __('Database-level tabs'),
            'db',
            $allowedTabs['db']
        );
        $html_output .= self::getTabList(
            __('Table-level tabs'),
            'table',
            $allowedTabs['table']
        );

        $html_output .= '</fieldset>';

        $html_output .= '<fieldset id="fieldset_user_group_rights_footer"'
            . ' class="tblFooters">';
        $html_output .= '<input class="btn btn-primary" type="submit" value="' . __('Go') . '">';
        $html_output .= '</fieldset>';

        return $html_output;
    }

    /**
     * Returns HTML for checkbox groups to choose
     * tabs of 'server', 'db' or 'table' levels.
     *
     * @param string $title    title of the checkbox group
     * @param string $level    'server', 'db' or 'table'
     * @param array  $selected array of selected allowed tabs
     *
     * @return string HTML for checkbox groups
     */
    public static function getTabList($title, $level, array $selected)
    {
        $tabs = Util::getMenuTabList($level);
        $html_output = '<fieldset>';
        $html_output .= '<legend>' . $title . '</legend>';
        foreach ($tabs as $tab => $tabName) {
            $html_output .= '<div class="item">';
            $html_output .= '<input type="checkbox" class="checkall"'
                . (in_array($tab, $selected) ? ' checked="checked"' : '')
                . ' name="' . $level . '_' . $tab . '" value="Y">';
            $html_output .= '<label for="' . $level . '_' . $tab . '">'
                . '<code>' . $tabName . '</code>'
                . '</label>';
            $html_output .= '</div>';
        }
        $html_output .= '</fieldset>';
        return $html_output;
    }

    /**
     * Add/update a user group with allowed menu tabs.
     *
     * @param string  $userGroup user group name
     * @param boolean $new       whether this is a new user group
     *
     * @return void
     */
    public static function edit($userGroup, $new = false)
    {
        $relation = new Relation($GLOBALS['dbi']);
        $tabs = Util::getMenuTabList();
        $cfgRelation = $relation->getRelationsParam();
        $groupTable = Util::backquote($cfgRelation['db'])
            . "." . Util::backquote($cfgRelation['usergroups']);

        if (! $new) {
            $sql_query = "DELETE FROM " . $groupTable
                . " WHERE `usergroup`='" . $GLOBALS['dbi']->escapeString($userGroup)
                . "';";
            $relation->queryAsControlUser($sql_query, true);
        }

        $sql_query = "INSERT INTO " . $groupTable
            . "(`usergroup`, `tab`, `allowed`)"
            . " VALUES ";
        $first = true;
        foreach ($tabs as $tabGroupName => $tabGroup) {
            foreach ($tabGroup as $tab => $tabName) {
                if (! $first) {
                    $sql_query .= ", ";
                }
                $tabName = $tabGroupName . '_' . $tab;
                $allowed = isset($_POST[$tabName]) && $_POST[$tabName] == 'Y';
                $sql_query .= "('" . $GLOBALS['dbi']->escapeString($userGroup) . "', '" . $tabName . "', '"
                    . ($allowed ? "Y" : "N") . "')";
                $first = false;
            }
        }
        $sql_query .= ";";
        $relation->queryAsControlUser($sql_query, true);
    }
}
