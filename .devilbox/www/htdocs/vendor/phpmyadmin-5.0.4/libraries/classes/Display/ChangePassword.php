<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * Displays form for password change
 *
 * @package PhpMyAdmin
 */
declare(strict_types=1);

namespace PhpMyAdmin\Display;

use PhpMyAdmin\Message;
use PhpMyAdmin\Relation;
use PhpMyAdmin\RelationCleanup;
use PhpMyAdmin\Server\Privileges;
use PhpMyAdmin\Template;
use PhpMyAdmin\Url;
use PhpMyAdmin\Util;

/**
 * Displays form for password change
 *
 * @package PhpMyAdmin
 */
class ChangePassword
{
    /**
     * Get HTML for the Change password dialog
     *
     * @param string $mode     where is the function being called?
     *                         values : 'change_pw' or 'edit_other'
     * @param string $username username
     * @param string $hostname hostname
     *
     * @return string html snippet
     */
    public static function getHtml($mode, $username, $hostname)
    {
        $relation = new Relation($GLOBALS['dbi']);
        $serverPrivileges = new Privileges(
            new Template(),
            $GLOBALS['dbi'],
            $relation,
            new RelationCleanup($GLOBALS['dbi'], $relation)
        );

        /**
         * autocomplete feature of IE kills the "onchange" event handler and it
         * must be replaced by the "onpropertychange" one in this case
         */
        $chg_evt_handler = 'onchange';

        $is_privileges = basename($_SERVER['SCRIPT_NAME']) === 'server_privileges.php';

        $html = '<form method="post" id="change_password_form" '
            . 'action="' . basename($GLOBALS['PMA_PHP_SELF']) . '" '
            . 'name="chgPassword" '
            . 'class="' . ($is_privileges ? 'submenu-item' : '') . '">';

        $html .= Url::getHiddenInputs();

        if (strpos($GLOBALS['PMA_PHP_SELF'], 'server_privileges') !== false) {
            $html .= '<input type="hidden" name="username" '
                . 'value="' . htmlspecialchars($username) . '">'
                . '<input type="hidden" name="hostname" '
                . 'value="' . htmlspecialchars($hostname) . '">';
        }
        $html .= '<fieldset id="fieldset_change_password">'
            . '<legend'
            . ($is_privileges
                ? ' data-submenu-label="' . __('Change password') . '"'
                : ''
            )
            . '>' . __('Change password') . '</legend>'
            . '<table class="data noclick">'
            . '<tr>'
            . '<td colspan="2">'
            . '<input type="radio" name="nopass" value="1" id="nopass_1" '
            . 'onclick="pma_pw.value = \'\'; pma_pw2.value = \'\'; '
            . 'this.checked = true">'
            . '<label for="nopass_1">' . __('No Password') . '</label>'
            . '</td>'
            . '</tr>'
            . '<tr class="vmiddle">'
            . '<td>'
            . '<input type="radio" name="nopass" value="0" id="nopass_0" '
            . 'onclick="document.getElementById(\'text_pma_change_pw\').focus();" '
            . 'checked="checked">'
            . '<label for="nopass_0">' . __('Password:') . '&nbsp;</label>'
            . '</td>'
            . '<td>'
            . __('Enter:') . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp'
            . '<input type="password" name="pma_pw" id="text_pma_change_pw" size="10" '
            . 'class="textfield"'
            . 'onkeyup="checkPasswordStrength($(this).val(), $(\'#change_password_strength_meter\'), meter_obj_label = $(\'#change_password_strength\'), CommonParams.get(\'user\'));" '
            . $chg_evt_handler . '="nopass[1].checked = true">'
            . '<span>Strength:</span> '
            . '<meter max="4" id="change_password_strength_meter" name="pw_meter"></meter> '
            . '<span id="change_password_strength" name="pw_strength">Good</span>'
            . '<br>' . __('Re-type:') . '&nbsp;'
            . '<input type="password" name="pma_pw2" id="text_pma_change_pw2" size="10" '
            . 'class="textfield"'
            . $chg_evt_handler . '="nopass[1].checked = true">'
            . '</td>'
            . '</tr>';

        $serverType = Util::getServerType();
        $serverVersion = $GLOBALS['dbi']->getVersion();
        $orig_auth_plugin = $serverPrivileges->getCurrentAuthenticationPlugin(
            'change',
            $username,
            $hostname
        );

        if (($serverType == 'MySQL'
            && $serverVersion >= 50507)
            || ($serverType == 'MariaDB'
            && $serverVersion >= 50200)
        ) {
            // Provide this option only for 5.7.6+
            // OR for privileged users in 5.5.7+
            if (($serverType == 'MySQL'
                && $serverVersion >= 50706)
                || ($GLOBALS['dbi']->isSuperuser() && $mode == 'edit_other')
            ) {
                $auth_plugin_dropdown = $serverPrivileges->getHtmlForAuthPluginsDropdown(
                    $orig_auth_plugin,
                    'change_pw',
                    'new'
                );

                $html .= '<tr class="vmiddle">'
                    . '<td>' . __('Password Hashing:') . '</td><td>';
                $html .= $auth_plugin_dropdown;
                $html .= '</td></tr>'
                    . '<tr id="tr_element_before_generate_password"></tr>'
                    . '</table>';

                $html .= '<div'
                    . ($orig_auth_plugin != 'sha256_password'
                        ? ' class="hide"'
                        : '')
                    . ' id="ssl_reqd_warning_cp">'
                    . Message::notice(
                        __(
                            'This method requires using an \'<i>SSL connection</i>\' '
                            . 'or an \'<i>unencrypted connection that encrypts the '
                            . 'password using RSA</i>\'; while connecting to the server.'
                        )
                        . Util::showMySQLDocu(
                            'sha256-authentication-plugin'
                        )
                    )
                        ->getDisplay()
                    . '</div>';
            } else {
                $html .= '<tr id="tr_element_before_generate_password"></tr>'
                    . '</table>';
            }
        } else {
            $auth_plugin_dropdown = $serverPrivileges->getHtmlForAuthPluginsDropdown(
                $orig_auth_plugin,
                'change_pw',
                'old'
            );

            $html .= '<tr class="vmiddle">'
                . '<td>' . __('Password Hashing:') . '</td><td>';
            $html .= $auth_plugin_dropdown . '</td></tr>'
                . '<tr id="tr_element_before_generate_password"></tr>'
                . '</table>';
        }

        $html .= '</fieldset>'
            . '<fieldset id="fieldset_change_password_footer" class="tblFooters">'
            . '<input type="hidden" name="change_pw" value="1">'
            . '<input class="btn btn-primary" type="submit" value="' . __('Go') . '">'
            . '</fieldset>'
            . '</form>';
        return $html;
    }
}
