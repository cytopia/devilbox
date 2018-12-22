<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * Form validation for configuration editor
 *
 * @package PhpMyAdmin
 */
namespace PhpMyAdmin\Config;

use PhpMyAdmin\Config\ConfigFile;
use PhpMyAdmin\Core;
use PhpMyAdmin\DatabaseInterface;
use PhpMyAdmin\Util;

/**
 * Validation class for various validation functions
 *
 * Validation function takes two argument: id for which it is called
 * and array of fields' values (usually values for entire formset, as defined
 * in forms.inc.php).
 * The function must always return an array with an error (or error array)
 * assigned to a form element (formset name or field path). Even if there are
 * no errors, key must be set with an empty value.
 *
 * Validation functions are assigned in $cfg_db['_validators'] (config.values.php).
 *
 * @package PhpMyAdmin
 */
class Validator
{
    /**
     * Returns validator list
     *
     * @param ConfigFile $cf Config file instance
     *
     * @return array
     */
    public static function getValidators(ConfigFile $cf)
    {
        static $validators = null;

        if ($validators !== null) {
            return $validators;
        }

        $validators = $cf->getDbEntry('_validators', array());
        if ($GLOBALS['PMA_Config']->get('is_setup')) {
            return $validators;
        }

        // not in setup script: load additional validators for user
        // preferences we need original config values not overwritten
        // by user preferences, creating a new PhpMyAdmin\Config instance is a
        // better idea than hacking into its code
        $uvs = $cf->getDbEntry('_userValidators', array());
        foreach ($uvs as $field => $uv_list) {
            $uv_list = (array)$uv_list;
            foreach ($uv_list as &$uv) {
                if (!is_array($uv)) {
                    continue;
                }
                for ($i = 1, $nb = count($uv); $i < $nb; $i++) {
                    if (mb_substr($uv[$i], 0, 6) == 'value:') {
                        $uv[$i] = Core::arrayRead(
                            mb_substr($uv[$i], 6),
                            $GLOBALS['PMA_Config']->base_settings
                        );
                    }
                }
            }
            $validators[$field] = isset($validators[$field])
                ? array_merge((array)$validators[$field], $uv_list)
                : $uv_list;
        }
        return $validators;
    }

    /**
     * Runs validation $validator_id on values $values and returns error list.
     *
     * Return values:
     * o array, keys - field path or formset id, values - array of errors
     *   when $isPostSource is true values is an empty array to allow for error list
     *   cleanup in HTML document
     * o false - when no validators match name(s) given by $validator_id
     *
     * @param ConfigFile   $cf           Config file instance
     * @param string|array $validator_id ID of validator(s) to run
     * @param array        &$values      Values to validate
     * @param bool         $isPostSource tells whether $values are directly from
     *                                   POST request
     *
     * @return bool|array
     */
    public static function validate(
        ConfigFile $cf, $validator_id, array &$values, $isPostSource
    ) {
        // find validators
        $validator_id = (array) $validator_id;
        $validators = static::getValidators($cf);
        $vids = array();
        foreach ($validator_id as &$vid) {
            $vid = $cf->getCanonicalPath($vid);
            if (isset($validators[$vid])) {
                $vids[] = $vid;
            }
        }
        if (empty($vids)) {
            return false;
        }

        // create argument list with canonical paths and remember path mapping
        $arguments = array();
        $key_map = array();
        foreach ($values as $k => $v) {
            $k2 = $isPostSource ? str_replace('-', '/', $k) : $k;
            $k2 = mb_strpos($k2, '/')
                ? $cf->getCanonicalPath($k2)
                : $k2;
            $key_map[$k2] = $k;
            $arguments[$k2] = $v;
        }

        // validate
        $result = array();
        foreach ($vids as $vid) {
            // call appropriate validation functions
            foreach ((array)$validators[$vid] as $validator) {
                $vdef = (array) $validator;
                $vname = array_shift($vdef);
                $vname = 'PhpMyAdmin\Config\Validator::' . $vname;
                $args = array_merge(array($vid, &$arguments), $vdef);
                $r = call_user_func_array($vname, $args);

                // merge results
                if (!is_array($r)) {
                    continue;
                }

                foreach ($r as $key => $error_list) {
                    // skip empty values if $isPostSource is false
                    if (! $isPostSource && empty($error_list)) {
                        continue;
                    }
                    if (! isset($result[$key])) {
                        $result[$key] = array();
                    }
                    $result[$key] = array_merge(
                        $result[$key], (array)$error_list
                    );
                }
            }
        }

        // restore original paths
        $new_result = array();
        foreach ($result as $k => $v) {
            $k2 = isset($key_map[$k]) ? $key_map[$k] : $k;
            $new_result[$k2] = $v;
        }
        return empty($new_result) ? true : $new_result;
    }

    /**
     * Test database connection
     *
     * @param string $host      host name
     * @param string $port      tcp port to use
     * @param string $socket    socket to use
     * @param string $user      username to use
     * @param string $pass      password to use
     * @param string $error_key key to use in return array
     *
     * @return bool|array
     */
    public static function testDBConnection(
        $host,
        $port,
        $socket,
        $user,
        $pass = null,
        $error_key = 'Server'
    ) {
        if ($GLOBALS['cfg']['DBG']['demo']) {
            // Connection test disabled on the demo server!
            return true;
        }

        $error = null;
        $host = Core::sanitizeMySQLHost($host);

        if (function_exists('error_clear_last')) {
            /* PHP 7 only code */
            error_clear_last();
        }

        if (DatabaseInterface::checkDbExtension('mysqli')) {
            $socket = empty($socket) ? null : $socket;
            $port = empty($port) ? null : $port;
            $extension = 'mysqli';
        } else {
            $socket = empty($socket) ? null : ':' . ($socket[0] == '/' ? '' : '/') . $socket;
            $port = empty($port) ? null : ':' . $port;
            $extension = 'mysql';
        }

        if ($extension == 'mysql') {
            $conn = @mysql_connect($host . $port . $socket, $user, $pass);
            if (! $conn) {
                $error = __('Could not connect to the database server!');
            } else {
                mysql_close($conn);
            }
        } else {
            $conn = @mysqli_connect($host, $user, $pass, null, $port, $socket);
            if (! $conn) {
                $error = __('Could not connect to the database server!');
            } else {
                mysqli_close($conn);
            }
        }
        if (! is_null($error)) {
            $error .= ' - ' . error_get_last();
        }
        return is_null($error) ? true : array($error_key => $error);
    }

    /**
     * Validate server config
     *
     * @param string $path   path to config, not used
     *                       keep this parameter since the method is invoked using
     *                       reflection along with other similar methods
     * @param array  $values config values
     *
     * @return array
     */
    public static function validateServer($path, array $values)
    {
        $result = array(
            'Server' => '',
            'Servers/1/user' => '',
            'Servers/1/SignonSession' => '',
            'Servers/1/SignonURL' => ''
        );
        $error = false;
        if (empty($values['Servers/1/auth_type'])) {
            $values['Servers/1/auth_type'] = '';
            $result['Servers/1/auth_type'] = __('Invalid authentication type!');
            $error = true;
        }
        if ($values['Servers/1/auth_type'] == 'config'
            && empty($values['Servers/1/user'])
        ) {
            $result['Servers/1/user'] = __(
                'Empty username while using [kbd]config[/kbd] authentication method!'
            );
            $error = true;
        }
        if ($values['Servers/1/auth_type'] == 'signon'
            && empty($values['Servers/1/SignonSession'])
        ) {
            $result['Servers/1/SignonSession'] = __(
                'Empty signon session name '
                . 'while using [kbd]signon[/kbd] authentication method!'
            );
            $error = true;
        }
        if ($values['Servers/1/auth_type'] == 'signon'
            && empty($values['Servers/1/SignonURL'])
        ) {
            $result['Servers/1/SignonURL'] = __(
                'Empty signon URL while using [kbd]signon[/kbd] authentication '
                . 'method!'
            );
            $error = true;
        }

        if (! $error && $values['Servers/1/auth_type'] == 'config') {
            $password = '';
            if (! empty($values['Servers/1/password'])) {
                $password = $values['Servers/1/password'];
            }
            $test = static::testDBConnection(
                empty($values['Servers/1/host']) ? '' : $values['Servers/1/host'],
                empty($values['Servers/1/port']) ? '' : $values['Servers/1/port'],
                empty($values['Servers/1/socket']) ? '' : $values['Servers/1/socket'],
                empty($values['Servers/1/user']) ? '' : $values['Servers/1/user'],
                $password,
                'Server'
            );

            if ($test !== true) {
                $result = array_merge($result, $test);
            }
        }
        return $result;
    }

    /**
     * Validate pmadb config
     *
     * @param string $path   path to config, not used
     *                       keep this parameter since the method is invoked using
     *                       reflection along with other similar methods
     * @param array  $values config values
     *
     * @return array
     */
    public static function validatePMAStorage($path, array $values)
    {
        $result = array(
            'Server_pmadb' => '',
            'Servers/1/controluser' => '',
            'Servers/1/controlpass' => ''
        );
        $error = false;

        if (empty($values['Servers/1/pmadb'])) {
            return $result;
        }

        $result = array();
        if (empty($values['Servers/1/controluser'])) {
            $result['Servers/1/controluser'] = __(
                'Empty phpMyAdmin control user while using phpMyAdmin configuration '
                . 'storage!'
            );
            $error = true;
        }
        if (empty($values['Servers/1/controlpass'])) {
            $result['Servers/1/controlpass'] = __(
                'Empty phpMyAdmin control user password while using phpMyAdmin '
                . 'configuration storage!'
            );
            $error = true;
        }
        if (! $error) {
            $test = static::testDBConnection(
                empty($values['Servers/1/host']) ? '' : $values['Servers/1/host'],
                empty($values['Servers/1/port']) ? '' : $values['Servers/1/port'],
                empty($values['Servers/1/socket']) ? '' : $values['Servers/1/socket'],
                empty($values['Servers/1/controluser']) ? '' : $values['Servers/1/controluser'],
                empty($values['Servers/1/controlpass']) ? '' : $values['Servers/1/controlpass'],
                'Server_pmadb'
            );
            if ($test !== true) {
                $result = array_merge($result, $test);
            }
        }
        return $result;
    }


    /**
     * Validates regular expression
     *
     * @param string $path   path to config
     * @param array  $values config values
     *
     * @return array
     */
    public static function validateRegex($path, array $values)
    {
        $result = array($path => '');

        if (empty($values[$path])) {
            return $result;
        }

        if (function_exists('error_clear_last')) {
            /* PHP 7 only code */
            error_clear_last();
            $last_error = null;
        } else {
            // As fallback we trigger another error to ensure
            // that preg error will be different
            @strpos();
            $last_error = error_get_last();
        }

        $matches = array();
        // in libraries/ListDatabase.php _checkHideDatabase(),
        // a '/' is used as the delimiter for hide_db
        @preg_match('/' . Util::requestString($values[$path]) . '/', '', $matches);

        $current_error = error_get_last();

        if ($current_error !== $last_error) {
            $error = preg_replace('/^preg_match\(\): /', '', $current_error['message']);
            return array($path => $error);
        }

        return $result;
    }

    /**
     * Validates TrustedProxies field
     *
     * @param string $path   path to config
     * @param array  $values config values
     *
     * @return array
     */
    public static function validateTrustedProxies($path, array $values)
    {
        $result = array($path => array());

        if (empty($values[$path])) {
            return $result;
        }

        if (is_array($values[$path]) || is_object($values[$path])) {
            // value already processed by FormDisplay::save
            $lines = array();
            foreach ($values[$path] as $ip => $v) {
                $v = Util::requestString($v);
                $lines[] = preg_match('/^-\d+$/', $ip)
                    ? $v
                    : $ip . ': ' . $v;
            }
        } else {
            // AJAX validation
            $lines = explode("\n", $values[$path]);
        }
        foreach ($lines as $line) {
            $line = trim($line);
            $matches = array();
            // we catch anything that may (or may not) be an IP
            if (!preg_match("/^(.+):(?:[ ]?)\\w+$/", $line, $matches)) {
                $result[$path][] = __('Incorrect value:') . ' '
                    . htmlspecialchars($line);
                continue;
            }
            // now let's check whether we really have an IP address
            if (filter_var($matches[1], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) === false
                && filter_var($matches[1], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) === false
            ) {
                $ip = htmlspecialchars(trim($matches[1]));
                $result[$path][] = sprintf(__('Incorrect IP address: %s'), $ip);
                continue;
            }
        }

        return $result;
    }

    /**
     * Tests integer value
     *
     * @param string $path         path to config
     * @param array  $values       config values
     * @param bool   $allow_neg    allow negative values
     * @param bool   $allow_zero   allow zero
     * @param int    $max_value    max allowed value
     * @param string $error_string error message string
     *
     * @return string  empty string if test is successful
     */
    public static function validateNumber(
        $path,
        array $values,
        $allow_neg,
        $allow_zero,
        $max_value,
        $error_string
    ) {
        if (empty($values[$path])) {
            return '';
        }

        $value = Util::requestString($values[$path]);

        if (intval($value) != $value
            || (! $allow_neg && $value < 0)
            || (! $allow_zero && $value == 0)
            || $value > $max_value
        ) {
            return $error_string;
        }

        return '';
    }

    /**
     * Validates port number
     *
     * @param string $path   path to config
     * @param array  $values config values
     *
     * @return array
     */
    public static function validatePortNumber($path, array $values)
    {
        return array(
            $path => static::validateNumber(
                $path,
                $values,
                false,
                false,
                65535,
                __('Not a valid port number!')
            )
        );
    }

    /**
     * Validates positive number
     *
     * @param string $path   path to config
     * @param array  $values config values
     *
     * @return array
     */
    public static function validatePositiveNumber($path, array $values)
    {
        return array(
            $path => static::validateNumber(
                $path,
                $values,
                false,
                false,
                PHP_INT_MAX,
                __('Not a positive number!')
            )
        );
    }

    /**
     * Validates non-negative number
     *
     * @param string $path   path to config
     * @param array  $values config values
     *
     * @return array
     */
    public static function validateNonNegativeNumber($path, array $values)
    {
        return array(
            $path => static::validateNumber(
                $path,
                $values,
                false,
                true,
                PHP_INT_MAX,
                __('Not a non-negative number!')
            )
        );
    }

    /**
     * Validates value according to given regular expression
     * Pattern and modifiers must be a valid for PCRE <b>and</b> JavaScript RegExp
     *
     * @param string $path   path to config
     * @param array  $values config values
     * @param string $regex  regular expression to match
     *
     * @return array
     */
    public static function validateByRegex($path, array $values, $regex)
    {
        if (!isset($values[$path])) {
            return '';
        }
        $result = preg_match($regex, Util::requestString($values[$path]));
        return array($path => ($result ? '' : __('Incorrect value!')));
    }

    /**
     * Validates upper bound for numeric inputs
     *
     * @param string $path      path to config
     * @param array  $values    config values
     * @param int    $max_value maximal allowed value
     *
     * @return array
     */
    public static function validateUpperBound($path, array $values, $max_value)
    {
        $result = $values[$path] <= $max_value;
        return array($path => ($result ? ''
            : sprintf(__('Value must be equal or lower than %s!'), $max_value)));
    }
}
