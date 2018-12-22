<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * Main loader script
 *
 * @package PhpMyAdmin
 */
use PhpMyAdmin\Charsets;
use PhpMyAdmin\Config;
use PhpMyAdmin\Core;
use PhpMyAdmin\Display\GitRevision;
use PhpMyAdmin\LanguageManager;
use PhpMyAdmin\Message;
use PhpMyAdmin\RecentFavoriteTable;
use PhpMyAdmin\Relation;
use PhpMyAdmin\Response;
use PhpMyAdmin\Sanitize;
use PhpMyAdmin\Server\Select;
use PhpMyAdmin\ThemeManager;
use PhpMyAdmin\Url;
use PhpMyAdmin\Util;
use PhpMyAdmin\UserPreferences;

/**
 * Gets some core libraries and displays a top message if required
 */
require_once 'libraries/common.inc.php';

/**
 * pass variables to child pages
 */
$drops = array(
    'lang',
    'server',
    'collation_connection',
    'db',
    'table'
);
foreach ($drops as $each_drop) {
    if (array_key_exists($each_drop, $_GET)) {
        unset($_GET[$each_drop]);
    }
}
unset($drops, $each_drop);

/*
 * Black list of all scripts to which front-end must submit data.
 * Such scripts must not be loaded on home page.
 *
 */
$target_blacklist = array (
    'import.php', 'export.php'
);

// If we have a valid target, let's load that script instead
if (! empty($_REQUEST['target'])
    && is_string($_REQUEST['target'])
    && ! preg_match('/^index/', $_REQUEST['target'])
    && ! in_array($_REQUEST['target'], $target_blacklist)
    && Core::checkPageValidity($_REQUEST['target'], [], true)
) {
    include $_REQUEST['target'];
    exit;
}

if (isset($_REQUEST['ajax_request']) && ! empty($_REQUEST['access_time'])) {
    exit;
}

// user selected font size
if (isset($_POST['set_fontsize']) && preg_match('/^[0-9.]+(px|em|pt|\%)$/', $_POST['set_fontsize'])) {
    $GLOBALS['PMA_Config']->setUserValue(
        null,
        'FontSize',
        $_POST['set_fontsize'],
        '82%'
    );
    header('Location: index.php' . Url::getCommonRaw());
    exit();
}
// if user selected a theme
if (isset($_POST['set_theme'])) {
    $tmanager = ThemeManager::getInstance();
    $tmanager->setActiveTheme($_POST['set_theme']);
    $tmanager->setThemeCookie();

    $userPreferences = new UserPreferences();
    $prefs = $userPreferences->load();
    $prefs["config_data"]["ThemeDefault"] = $_POST['set_theme'];
    $userPreferences->save($prefs["config_data"]);

    header('Location: index.php' . Url::getCommonRaw());
    exit();
}
// Change collation connection
if (isset($_POST['collation_connection'])) {
    $GLOBALS['PMA_Config']->setUserValue(
        null,
        'DefaultConnectionCollation',
        $_POST['collation_connection'],
        'utf8mb4_unicode_ci'
    );
    header('Location: index.php' . Url::getCommonRaw());
    exit();
}


// See FAQ 1.34
if (! empty($_REQUEST['db'])) {
    $page = null;
    if (! empty($_REQUEST['table'])) {
        $page = Util::getScriptNameForOption(
            $GLOBALS['cfg']['DefaultTabTable'], 'table'
        );
    } else {
        $page = Util::getScriptNameForOption(
            $GLOBALS['cfg']['DefaultTabDatabase'], 'database'
        );
    }
    include $page;
    exit;
}

$response = Response::getInstance();
/**
 * Check if it is an ajax request to reload the recent tables list.
 */
if ($response->isAjax() && ! empty($_REQUEST['recent_table'])) {
    $response->addJSON(
        'list',
        RecentFavoriteTable::getInstance('recent')->getHtmlList()
    );
    exit;
}

if ($GLOBALS['PMA_Config']->isGitRevision()) {
    if (isset($_REQUEST['git_revision']) && $response->isAjax()) {
        GitRevision::display();
        exit;
    }
    echo '<div id="is_git_revision"></div>';
}

// Handles some variables that may have been sent by the calling script
$GLOBALS['db'] = '';
$GLOBALS['table'] = '';
$show_query = '1';

// Any message to display?
if (! empty($message)) {
    echo Util::getMessage($message);
    unset($message);
}
if (isset($_SESSION['partial_logout'])) {
    Message::success(
        __('You were logged out from one server, to logout completely from phpMyAdmin, you need to logout from all servers.')
    )->display();
    unset($_SESSION['partial_logout']);
}

$common_url_query =  Url::getCommon();
$mysql_cur_user_and_host = '';

// when $server > 0, a server has been chosen so we can display
// all MySQL-related information
if ($server > 0) {
    include 'libraries/server_common.inc.php';

    // Use the verbose name of the server instead of the hostname
    // if a value is set
    $server_info = '';
    if (! empty($cfg['Server']['verbose'])) {
        $server_info .= htmlspecialchars($cfg['Server']['verbose']);
        if ($GLOBALS['cfg']['ShowServerInfo']) {
            $server_info .= ' (';
        }
    }
    if ($GLOBALS['cfg']['ShowServerInfo'] || empty($cfg['Server']['verbose'])) {
        $server_info .= $GLOBALS['dbi']->getHostInfo();
    }
    if (! empty($cfg['Server']['verbose']) && $GLOBALS['cfg']['ShowServerInfo']) {
        $server_info .= ')';
    }
    $mysql_cur_user_and_host = $GLOBALS['dbi']->fetchValue('SELECT USER();');

    // should we add the port info here?
    $short_server_info = (!empty($GLOBALS['cfg']['Server']['verbose'])
                ? $GLOBALS['cfg']['Server']['verbose']
                : $GLOBALS['cfg']['Server']['host']);
}

echo '<div id="maincontainer">' , "\n";
// Anchor for favorite tables synchronization.
echo RecentFavoriteTable::getInstance('favorite')->getHtmlSyncFavoriteTables();
echo '<div id="main_pane_left">';
if ($server > 0 || count($cfg['Servers']) > 1
) {
    if ($cfg['DBG']['demo']) {
        echo '<div class="group">';
        echo '<h2>' , __('phpMyAdmin Demo Server') , '</h2>';
        echo '<p class="cfg_dbg_demo">';
        printf(
            __(
                'You are using the demo server. You can do anything here, but '
                . 'please do not change root, debian-sys-maint and pma users. '
                . 'More information is available at %s.'
            ),
            '<a href="url.php?url=https://demo.phpmyadmin.net/" target="_blank" rel="noopener noreferrer">demo.phpmyadmin.net</a>'
        );
        echo '</p>';
        echo '</div>';
    }
    echo '<div class="group">';
    echo '<h2>' , __('General settings') , '</h2>';
    echo '<ul>';

    /**
     * Displays the MySQL servers choice form
     */
    if ($cfg['ServerDefault'] == 0
        || (! $cfg['NavigationDisplayServers']
        && (count($cfg['Servers']) > 1
        || ($server == 0 && count($cfg['Servers']) == 1)))
    ) {
        echo '<li id="li_select_server" class="no_bullets" >';
        echo Util::getImage('s_host') , " "
            , Select::render(true, true);
        echo '</li>';
    }

    /**
     * Displays the mysql server related links
     */
    if ($server > 0) {
        include_once 'libraries/check_user_privileges.inc.php';

        // Logout for advanced authentication
        if ($cfg['Server']['auth_type'] != 'config') {
            if ($cfg['ShowChgPassword']) {
                $conditional_class = 'ajax';
                Core::printListItem(
                    Util::getImage('s_passwd') . "&nbsp;" . __(
                        'Change password'
                    ),
                    'li_change_password',
                    'user_password.php' . $common_url_query,
                    null,
                    null,
                    'change_password_anchor',
                    "no_bullets",
                    $conditional_class
                );
            }
        } // end if
        echo '    <li id="li_select_mysql_collation" class="no_bullets" >';
        echo '        <form class="disableAjax" method="post" action="index.php">' , "\n"
           . Url::getHiddenInputs(null, null, 4, 'collation_connection')
           . '            <label for="select_collation_connection">' . "\n"
           . '                ' . Util::getImage('s_asci')
            . "&nbsp;" . __('Server connection collation') . "\n"
           // put the doc link in the form so that it appears on the same line
           . Util::showMySQLDocu('Charset-connection')
           . ': ' .  "\n"
           . '            </label>' . "\n"

           . Charsets::getCollationDropdownBox(
               $GLOBALS['dbi'],
               $GLOBALS['cfg']['Server']['DisableIS'],
               'collation_connection',
               'select_collation_connection',
               $collation_connection,
               true,
               true
           )
           . '        </form>' . "\n"
           . '    </li>' . "\n";
    } // end of if ($server > 0)
    echo '</ul>';
    echo '</div>';
}

echo '<div class="group">';
echo '<h2>' , __('Appearance settings') , '</h2>';
echo '  <ul>';

// Displays language selection combo
$language_manager = LanguageManager::getInstance();
if (empty($cfg['Lang']) && $language_manager->hasChoice()) {
    echo '<li id="li_select_lang" class="no_bullets">';

    echo Util::getImage('s_lang') , " "
        , $language_manager->getSelectorDisplay();
    echo '</li>';
}

// ThemeManager if available

if ($GLOBALS['cfg']['ThemeManager']) {
    echo '<li id="li_select_theme" class="no_bullets">';
    echo Util::getImage('s_theme') , " "
            ,  ThemeManager::getInstance()->getHtmlSelectBox();
    echo '</li>';
}
echo '<li id="li_select_fontsize">';
echo Config::getFontsizeForm();
echo '</li>';

echo '</ul>';

// User preferences

if ($server > 0) {
    echo '<ul>';
    Core::printListItem(
        Util::getImage('b_tblops') . "&nbsp;" . __(
            'More settings'
        ),
        'li_user_preferences',
        'prefs_manage.php' . $common_url_query,
        null,
        null,
        null,
        "no_bullets"
    );
    echo '</ul>';
}

echo '</div>';


echo '</div>';
echo '<div id="main_pane_right">';


if ($server > 0 && $GLOBALS['cfg']['ShowServerInfo']) {

    echo '<div class="group">';
    echo '<h2>' , __('Database server') , '</h2>';
    echo '<ul>' , "\n";
    Core::printListItem(
        __('Server:') . ' ' . $server_info,
        'li_server_info'
    );
    Core::printListItem(
        __('Server type:') . ' ' . Util::getServerType(),
        'li_server_type'
    );
    Core::printListItem(
        __('Server connection:') . ' ' . Util::getServerSSL(),
        'li_server_type'
    );
    Core::printListItem(
        __('Server version:')
        . ' '
        . $GLOBALS['dbi']->getVersionString() . ' - ' . $GLOBALS['dbi']->getVersionComment(),
        'li_server_version'
    );
    Core::printListItem(
        __('Protocol version:') . ' ' . $GLOBALS['dbi']->getProtoInfo(),
        'li_mysql_proto'
    );
    Core::printListItem(
        __('User:') . ' ' . htmlspecialchars($mysql_cur_user_and_host),
        'li_user_info'
    );

    echo '    <li id="li_select_mysql_charset">';
    echo '        ' , __('Server charset:') , ' '
       . '        <span lang="en" dir="ltr">';
    $unicode = Charsets::$mysql_charset_map['utf-8'];
    $charsets = Charsets::getMySQLCharsetsDescriptions(
        $GLOBALS['dbi'],
        $GLOBALS['cfg']['Server']['DisableIS']
    );
    echo '           ' , $charsets[$unicode], ' (' . $unicode, ')';
    echo '        </span>'
       . '    </li>'
       . '  </ul>'
       . ' </div>';
}

if ($GLOBALS['cfg']['ShowServerInfo'] || $GLOBALS['cfg']['ShowPhpInfo']) {
    echo '<div class="group">';
    echo '<h2>' , __('Web server') , '</h2>';
    echo '<ul>';
    if ($GLOBALS['cfg']['ShowServerInfo']) {
        Core::printListItem($_SERVER['SERVER_SOFTWARE'], 'li_web_server_software');

        if ($server > 0) {
            $client_version_str = $GLOBALS['dbi']->getClientInfo();
            if (preg_match('#\d+\.\d+\.\d+#', $client_version_str)) {
                $client_version_str = 'libmysql - ' . $client_version_str;
            }
            Core::printListItem(
                __('Database client version:') . ' ' . $client_version_str,
                'li_mysql_client_version'
            );

            $php_ext_string = __('PHP extension:') . ' ';

            $extensions = Util::listPHPExtensions();

            foreach ($extensions as $extension) {
                $php_ext_string  .= '  ' . $extension
                    . Util::showPHPDocu('book.' . $extension . '.php');
            }

            Core::printListItem(
                $php_ext_string,
                'li_used_php_extension'
            );

            $php_version_string = __('PHP version:') . ' ' . phpversion();

            Core::printListItem(
                $php_version_string,
                'li_used_php_version'
            );
        }
    }

    if ($cfg['ShowPhpInfo']) {
        Core::printListItem(
            __('Show PHP information'),
            'li_phpinfo',
            'phpinfo.php' . $common_url_query,
            null,
            '_blank'
        );
    }
    echo '  </ul>';
    echo ' </div>';
}

echo '<div class="group pmagroup">';
echo '<h2>phpMyAdmin</h2>';
echo '<ul>';
$class = null;
if ($GLOBALS['cfg']['VersionCheck']) {
    $class = 'jsversioncheck';
}
Core::printListItem(
    __('Version information:') . ' <span class="version">' . PMA_VERSION . '</span>',
    'li_pma_version',
    null,
    null,
    null,
    null,
    $class
);
Core::printListItem(
    __('Documentation'),
    'li_pma_docs',
    Util::getDocuLink('index'),
    null,
    '_blank'
);

// does not work if no target specified, don't know why
Core::printListItem(
    __('Official Homepage'),
    'li_pma_homepage',
    Core::linkURL('https://www.phpmyadmin.net/'),
    null,
    '_blank'
);
Core::printListItem(
    __('Contribute'),
    'li_pma_contribute',
    Core::linkURL('https://www.phpmyadmin.net/contribute/'),
    null,
    '_blank'
);
Core::printListItem(
    __('Get support'),
    'li_pma_support',
    Core::linkURL('https://www.phpmyadmin.net/support/'),
    null,
    '_blank'
);
Core::printListItem(
    __('List of changes'),
    'li_pma_changes',
    'changelog.php' . Url::getCommon(),
    null,
    '_blank'
);
Core::printListItem(
    __('License'),
    'li_pma_license',
    'license.php' . Url::getCommon(),
    null,
    '_blank'
);
echo '    </ul>';
echo ' </div>';

echo '</div>';

echo '</div>';

/**
 * mbstring is used for handling multibytes inside parser, so it is good
 * to tell user something might be broken without it, see bug #1063149.
 */
if (! extension_loaded('mbstring')) {
    trigger_error(
        __(
            'The mbstring PHP extension was not found and you seem to be using'
            . ' a multibyte charset. Without the mbstring extension phpMyAdmin'
            . ' is unable to split strings correctly and it may result in'
            . ' unexpected results.'
        ),
        E_USER_WARNING
    );
}

/**
 * Missing functionality
 */
if (! extension_loaded('curl') && ! ini_get('allow_url_fopen')) {
    trigger_error(
        __(
            'The curl extension was not found and allow_url_fopen is '
            . 'disabled. Due to this some features such as error reporting '
            . 'or version check are disabled.'
        )
    );
}

if ($cfg['LoginCookieValidityDisableWarning'] == false) {
    /**
     * Check whether session.gc_maxlifetime limits session validity.
     */
    $gc_time = (int)ini_get('session.gc_maxlifetime');
    if ($gc_time < $GLOBALS['cfg']['LoginCookieValidity'] ) {
        trigger_error(
            __(
                'Your PHP parameter [a@https://secure.php.net/manual/en/session.' .
                'configuration.php#ini.session.gc-maxlifetime@_blank]session.' .
                'gc_maxlifetime[/a] is lower than cookie validity configured ' .
                'in phpMyAdmin, because of this, your login might expire sooner ' .
                'than configured in phpMyAdmin.'
            ),
            E_USER_WARNING
        );
    }
}

/**
 * Check whether LoginCookieValidity is limited by LoginCookieStore.
 */
if ($GLOBALS['cfg']['LoginCookieStore'] != 0
    && $GLOBALS['cfg']['LoginCookieStore'] < $GLOBALS['cfg']['LoginCookieValidity']
) {
    trigger_error(
        __(
            'Login cookie store is lower than cookie validity configured in ' .
            'phpMyAdmin, because of this, your login will expire sooner than ' .
            'configured in phpMyAdmin.'
        ),
        E_USER_WARNING
    );
}

/**
 * Check if user does not have defined blowfish secret and it is being used.
 */
if (! empty($_SESSION['encryption_key'])) {
    if (empty($GLOBALS['cfg']['blowfish_secret'])) {
        trigger_error(
            __(
                'The configuration file now needs a secret passphrase (blowfish_secret).'
            ),
            E_USER_WARNING
        );
    } elseif (strlen($GLOBALS['cfg']['blowfish_secret']) < 32) {
        trigger_error(
            __(
                'The secret passphrase in configuration (blowfish_secret) is too short.'
            ),
            E_USER_WARNING
        );
    }
}

/**
 * Check for existence of config directory which should not exist in
 * production environment.
 */
if (@file_exists('config')) {
    trigger_error(
        __(
            'Directory [code]config[/code], which is used by the setup script, ' .
            'still exists in your phpMyAdmin directory. It is strongly ' .
            'recommended to remove it once phpMyAdmin has been configured. ' .
            'Otherwise the security of your server may be compromised by ' .
            'unauthorized people downloading your configuration.'
        ),
        E_USER_WARNING
    );
}

$relation = new Relation();

if ($server > 0) {
    $cfgRelation = $relation->getRelationsParam();
    if (! $cfgRelation['allworks']
        && $cfg['PmaNoRelation_DisableWarning'] == false
    ) {
        $msg_text = __(
            'The phpMyAdmin configuration storage is not completely '
            . 'configured, some extended features have been deactivated. '
            . '%sFind out why%s. '
        );
        if ($cfg['ZeroConf'] == true) {
            $msg_text .= '<br>' .
                __(
                    'Or alternately go to \'Operations\' tab of any database '
                    . 'to set it up there.'
                );
        }
        $msg = Message::notice($msg_text);
        $msg->addParamHtml('<a href="./chk_rel.php" data-post="' . $common_url_query . '">');
        $msg->addParamHtml('</a>');
        /* Show error if user has configured something, notice elsewhere */
        if (!empty($cfg['Servers'][$server]['pmadb'])) {
            $msg->isError(true);
        }
        $msg->display();
    } // end if
}

/**
 * Warning about Suhosin only if its simulation mode is not enabled
 */
if ($cfg['SuhosinDisableWarning'] == false
    && ini_get('suhosin.request.max_value_length')
    && ini_get('suhosin.simulation') == '0'
) {
    trigger_error(
        sprintf(
            __(
                'Server running with Suhosin. Please refer to %sdocumentation%s ' .
                'for possible issues.'
            ),
            '[doc@faq1-38]',
            '[/doc]'
        ),
        E_USER_WARNING
    );
}

/* Missing template cache */
if (is_null($GLOBALS['PMA_Config']->getTempDir('twig'))) {
    trigger_error(
        sprintf(
            __('The $cfg[\'TempDir\'] (%s) is not accessible. phpMyAdmin is not able to cache templates and will be slow because of this.'),
            $GLOBALS['PMA_Config']->get('TempDir')
        ),
        E_USER_WARNING
    );
}

/**
 * Warning about incomplete translations.
 *
 * The data file is created while creating release by ./scripts/remove-incomplete-mo
 */
if (@file_exists('libraries/language_stats.inc.php')) {
    include 'libraries/language_stats.inc.php';
    /*
     * This message is intentionally not translated, because we're
     * handling incomplete translations here and focus on english
     * speaking users.
     */
    if (isset($GLOBALS['language_stats'][$lang])
        && $GLOBALS['language_stats'][$lang] < $cfg['TranslationWarningThreshold']
    ) {
        trigger_error(
            'You are using an incomplete translation, please help to make it '
            . 'better by [a@https://www.phpmyadmin.net/translate/'
            . '@_blank]contributing[/a].',
            E_USER_NOTICE
        );
    }
}
