<?php require '../config.php'; ?>
<?php loadClass('Helper')->authPage(); ?>
<?php

/**
 * OPcache GUI
 *
 * A simple but effective single-file GUI for the OPcache PHP extension.
 *
 * @author Andrew Collington, andy@amnuts.com
 * @version 2.2.3
 * @link https://github.com/amnuts/opcache-gui
 * @license MIT, http://acollington.mit-license.org/
 */

error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);

/*
 * User configuration
 */

$options = [
    'allow_filelist'   => true,  // show/hide the files tab
    'allow_invalidate' => true,  // give a link to invalidate files
    'allow_reset'      => true,  // give option to reset the whole cache
    'allow_realtime'   => true,  // give option to enable/disable real-time updates
    'refresh_time'     => 5,     // how often the data will refresh, in seconds
    'size_precision'   => 2,     // Digits after decimal point
    'size_space'       => false, // have '1MB' or '1 MB' when showing sizes
    'charts'           => true,  // show gauge chart or just big numbers
    'debounce_rate'    => 250    // milliseconds after key press to send keyup event when filtering
];

/*
 * Shouldn't need to alter anything else below here
 */

if (!extension_loaded('Zend OPcache')) {
    die('The Zend OPcache extension does not appear to be installed');
}

class OpCacheService
{
    protected $data;
    protected $options;
    protected $defaults = [
        'allow_filelist'   => true,
        'allow_invalidate' => true,
        'allow_reset'      => true,
        'allow_realtime'   => true,
        'refresh_time'     => 5,
        'size_precision'   => 2,
        'size_space'       => false,
        'charts'           => true,
        'debounce_rate'    => 250
    ];

    private function __construct($options = [])
    {
        $this->options = array_merge($this->defaults, $options);
        $this->data = $this->compileState();
    }

    public static function init($options = [])
    {
        $self = new self($options);
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'
        ) {
            if (isset($_GET['reset']) && $self->getOption('allow_reset')) {
                echo '{ "success": "' . ($self->resetCache() ? 'yes' : 'no') . '" }';
            } else if (isset($_GET['invalidate']) && $self->getOption('allow_invalidate')) {
                echo '{ "success": "' . ($self->resetCache($_GET['invalidate']) ? 'yes' : 'no') . '" }';
            } else {
                echo json_encode($self->getData((empty($_GET['section']) ? null : $_GET['section'])));
            }
            exit;
        } else if (isset($_GET['reset']) && $self->getOption('allow_reset')) {
            $self->resetCache();
            header('Location: ?');
            exit;
        } else if (isset($_GET['invalidate']) && $self->getOption('allow_invalidate')) {
            $self->resetCache($_GET['invalidate']);
            header('Location: ?');
            exit;
        }
        return $self;
    }

    public function getOption($name = null)
    {
        if ($name === null) {
            return $this->options;
        }
        return (isset($this->options[$name])
            ? $this->options[$name]
            : null
        );
    }

    public function getData($section = null, $property = null)
    {
        if ($section === null) {
            return $this->data;
        }
        $section = strtolower($section);
        if (isset($this->data[$section])) {
            if ($property === null || !isset($this->data[$section][$property])) {
                return $this->data[$section];
            }
            return $this->data[$section][$property];
        }
        return null;
    }

    public function canInvalidate()
    {
        return ($this->getOption('allow_invalidate') && function_exists('opcache_invalidate'));
    }

    public function resetCache($file = null)
    {
        $success = false;
        if ($file === null) {
            $success = opcache_reset();
        } else if (function_exists('opcache_invalidate')) {
            $success = opcache_invalidate(urldecode($file), true);
        }
        if ($success) {
            $this->compileState();
        }
        return $success;
    }

    protected function size($size)
    {
        $i = 0;
        $val = array('b', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        while (($size / 1024) > 1) {
            $size /= 1024;
            ++$i;
        }
        return sprintf('%.'.$this->getOption('size_precision').'f%s%s',
            $size, ($this->getOption('size_space') ? ' ' : ''), $val[$i]
        );
    }

    protected function compileState()
    {
        $status = opcache_get_status();
        $config = opcache_get_configuration();

        $files = [];
        if (!empty($status['scripts']) && $this->getOption('allow_filelist')) {
            uasort($status['scripts'], function($a, $b) {
                return $a['hits'] < $b['hits'];
            });
            foreach ($status['scripts'] as &$file) {
                $file['full_path'] = str_replace('\\', '/', $file['full_path']);
                $file['readable'] = [
                    'hits'               => number_format($file['hits']),
                    'memory_consumption' => $this->size($file['memory_consumption'])
                ];
            }
            $files = array_values($status['scripts']);
        }

        $overview = array_merge(
            $status['memory_usage'], $status['opcache_statistics'], [
                'used_memory_percentage'  => round(100 * (
                        ($status['memory_usage']['used_memory'] + $status['memory_usage']['wasted_memory'])
                        / $config['directives']['opcache.memory_consumption'])),
                'hit_rate_percentage'     => round($status['opcache_statistics']['opcache_hit_rate']),
                'wasted_percentage'       => round($status['memory_usage']['current_wasted_percentage'], 2),
                'readable' => [
                    'total_memory'       => $this->size($config['directives']['opcache.memory_consumption']),
                    'used_memory'        => $this->size($status['memory_usage']['used_memory']),
                    'free_memory'        => $this->size($status['memory_usage']['free_memory']),
                    'wasted_memory'      => $this->size($status['memory_usage']['wasted_memory']),
                    'num_cached_scripts' => number_format($status['opcache_statistics']['num_cached_scripts']),
                    'hits'               => number_format($status['opcache_statistics']['hits']),
                    'misses'             => number_format($status['opcache_statistics']['misses']),
                    'blacklist_miss'     => number_format($status['opcache_statistics']['blacklist_misses']),
                    'num_cached_keys'    => number_format($status['opcache_statistics']['num_cached_keys']),
                    'max_cached_keys'    => number_format($status['opcache_statistics']['max_cached_keys']),
                    'start_time'         => date('Y-m-d H:i:s', $status['opcache_statistics']['start_time']),
                    'last_restart_time'  => ($status['opcache_statistics']['last_restart_time'] == 0
                            ? 'never'
                            : date('Y-m-d H:i:s', $status['opcache_statistics']['last_restart_time'])
                        )
                ]
            ]
        );

        $directives = [];
        ksort($config['directives']);
        foreach ($config['directives'] as $k => $v) {
            $directives[] = ['k' => $k, 'v' => $v];
        }

        $version = array_merge(
            $config['version'],
            [
                'php'    => phpversion(),
                'server' => $_SERVER['SERVER_SOFTWARE'],
                'host'   => (function_exists('gethostname')
                    ? gethostname()
                    : (php_uname('n')
                        ?: (empty($_SERVER['SERVER_NAME'])
                            ? $_SERVER['HOST_NAME']
                            : $_SERVER['SERVER_NAME']
                        )
                    )
                )
            ]
        );

        return [
            'version'    => $version,
            'overview'   => $overview,
            'files'      => $files,
            'directives' => $directives,
            'blacklist'  => $config['blacklist'],
            'functions'  => get_extension_funcs('Zend OPcache')
        ];
    }
}

$opcache = OpCacheService::init($options);

?>
<!doctype html>
<html>
<head>
    <?php echo loadClass('Html')->getHead(); /* devilbox edit */ ?>


    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>OPcache statistics on <?php echo $opcache->getData('version', 'host'); ?></title>
    <script src="/vendor/react/react.min.js"></script>
    <script src="/vendor/react/react-dom.min.js"></script>
    <script src="/vendor/jquery/jquery-3.2.1.min.js"></script>
    <style type="text/css">
        body { font-family:sans-serif; font-size:90%; padding: 0; margin: 0 }
        nav { padding-top: 20px; }
        nav > ul { list-style-type: none; padding-left: 8px; margin: 0; border-bottom: 1px solid #ccc; }
        nav > ul > li { display: inline-block; padding: 0; margin: 0 0 -1px 0; }
        nav > ul > li > a { display: block; margin: 0 10px; padding: 15px 30px; border: 1px solid transparent; border-bottom-color: #ccc; text-decoration: none; }
        nav > ul > li > a:hover { background-color: #f4f4f4; text-decoration: underline; }
        nav > ul > li > a.active:hover { background-color: initial; }
        nav > ul > li > a[data-for].active { border: 1px solid #ccc; border-bottom-color: #ffffff; border-top: 3px solid #6ca6ef; }
        table { margin: 0 0 1em 0; border-collapse: collapse; border-color: #fff; width: 100%; table-layout: fixed; }
        table caption { text-align: left; font-size: 1.5em; }
        table tr { background-color: #99D0DF; border-color: #fff; }
        table th { text-align: left; padding: 6px; background-color: #6ca6ef; color: #fff; border-color: #fff; font-weight: normal; }
        table td { padding: 4px 6px; line-height: 1.4em; vertical-align: top; border-color: #fff; }
        table tr:nth-child(odd) { background-color: #EFFEFF; }
        table tr:nth-child(even) { background-color: #E0ECEF; }
        #filelist table tr { background-color: #EFFEFF; }
        #filelist table tr.alternate { background-color: #E0ECEF; }
        td.pathname { width: 70%; }
        footer { border-top: 1px solid #ccc; padding: 1em 2em; }
        footer a { padding: 2em; text-decoration: none; opacity: 0.7; }
        footer a:hover { opacity: 1; }
        canvas { display: block; width: 250px; height: 250px; margin: 0 auto; }
        #tabs { padding: 2em; }
        #tabs > div { display: none; }
        #tabs > div#overview { display:block; }
        #resetCache, #toggleRealtime, footer > a { background-position: 5px 50%; background-repeat: no-repeat; background-color: transparent; }
        footer > a { background-position: 0 50%; background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABIAAAAQCAYAAAAbBi9cAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyBpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMC1jMDYwIDYxLjEzNDc3NywgMjAxMC8wMi8xMi0xNzozMjowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNSBXaW5kb3dzIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOjE2MENCRkExNzVBQjExRTQ5NDBGRTUzMzQyMDVDNzFFIiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOjE2MENCRkEyNzVBQjExRTQ5NDBGRTUzMzQyMDVDNzFFIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6MTYwQ0JGOUY3NUFCMTFFNDk0MEZFNTMzNDIwNUM3MUUiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6MTYwQ0JGQTA3NUFCMTFFNDk0MEZFNTMzNDIwNUM3MUUiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz7HtUU1AAABN0lEQVR42qyUvWoCQRSF77hCLLKC+FOlCKTyIbYQUuhbWPkSFnZ2NpabUvANLGyz5CkkYGMlFtFAUmiSM8lZOVkWsgm58K079+fMnTusZl92BXbgDrTtZ2szd8fas/XBOzmBKaiCEFyTkL4pc9L8vgpNJJDyWtDna61EoXpO+xcFfXUVqtrf7Vx7m9Pub/EatvgHoYXD4ylztC14BBVwydvydgDPHPgNaErN3jLKIxAUmEvAXK21I18SJpXBGAxyBAaMlblOWOs1bMXFkMGeBFsi0pJNe/QNuV7563+gs8LfhrRfE6GaHLuRqfnUiKi6lJ034B44EXL0baTTJWujNGkG3kBX5uRyZuRkPl3WzDTBtzjnxxiDDq83yNxUk7GYuXM53jeLuMNavvAXkv4zrJkTaeGHAAMAIal3icPMsyQAAAAASUVORK5CYII='); font-size: 80%; }
        #resetCache { background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABIAAAASCAYAAABWzo5XAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6NjBFMUMyMjI3NDlGMTFFNEE3QzNGNjQ0OEFDQzQ1MkMiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6NjBFMUMyMjM3NDlGMTFFNEE3QzNGNjQ0OEFDQzQ1MkMiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDo2MEUxQzIyMDc0OUYxMUU0QTdDM0Y2NDQ4QUNDNDUyQyIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDo2MEUxQzIyMTc0OUYxMUU0QTdDM0Y2NDQ4QUNDNDUyQyIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PplZ+ZkAAAD1SURBVHjazFPtDYJADIUJZAMZ4UbACWQENjBO4Ao6AW5AnODcADZQJwAnwJ55NbWhB/6zycsdpX39uDZNpsURtjgzwkDoCBecs5ITPGGMwCNAkIrQw+8ri36GhBHsavFdpILEo4wEpZxRigy009EhG760gr0VhFoyZfvJKPwsheIWIeGejBZRIxRVhMRFevbuUXBew/iE/lhlBduV0j8Jx+TvJEWPphq8n5li9utgaw6cW/h6NSt/JcnVBhQxotIgKTBrbNvIHo2G0x1rwlKqTDusxiAz6hHNL1zayTVqVKRKpa/LPljPH1sJh6l/oNSrZfwSYABtq3tFdZA5BAAAAABJRU5ErkJggg=='); }
        #toggleRealtime { position: relative; background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABIAAAAUCAYAAACAl21KAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6ODE5RUU4NUE3NDlGMTFFNDkyMzA4QzY1RjRBQkIzQjUiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6ODE5RUU4NUI3NDlGMTFFNDkyMzA4QzY1RjRBQkIzQjUiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDo4MTlFRTg1ODc0OUYxMUU0OTIzMDhDNjVGNEFCQjNCNSIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDo4MTlFRTg1OTc0OUYxMUU0OTIzMDhDNjVGNEFCQjNCNSIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PpXjpvMAAAD2SURBVHjarFQBEcMgDKR3E1AJldA5wMEqAQmTgINqmILdFCChdUAdMAeMcukuSwnQbbnLlZLwJPkQIcrSiT/IGNQHNb8CGQDyRw+2QWUBqC+luzo4OKQZIAVrB+ssyKp3Bkijf0+ijzIh4wQppoBauMSjyDZfMSCDxYZMsfHF120T36AqWZMkgyguQ3GOfottJ5TKnHC+wfeRsC2oDVayPgr3bbN2tHBH3tWuJCPa0JUgKtFzMQrcZH3FNHAc0yOp1cCASALyngoN6lhDopkJWxdifwY9A3u7l29ImpxOFSWIOVsGwHKENIWxss2eBVKdOeeXAAMAk/Z9h4QhXmUAAAAASUVORK5CYII='); }
        #counts { width: 270px; float: right; }
        #counts > div > div { background-color: #ededed; margin-bottom: 10px; }
        #counts > div > div > h3 { background-color: #cdcdcd; padding: 4px 6px; margin: 0; text-align: center; }
        #counts > div > div > p { margin: 0; text-align: center; }
        #counts > div > div > p span.large + span { font-size: 20pt; margin: 0; color: #6ca6ef; }
        #counts > div > div > p span.large { color: #6ca6ef; font-size: 80pt; margin: 0; padding: 0; text-align: center; }
        #info { margin-right: 280px; }
        #frmFilter { width: 520px; }
        #moreinfo { padding: 10px; }
        #moreinfo > p { text-align: left !important; line-height: 180%; }
        .metainfo { font-size: 80%; }
        .hide { display: none; }
        #toggleRealtime.pulse::before {
            content: ""; position: absolute;
            top: 13px; left: 3px; width: 18px; height: 18px;
            z-index: 10; opacity: 0; background-color: transparent;
            border: 2px solid rgb(255, 116, 0); border-radius: 100%;
            -webkit-animation: pulse 1s linear 2;
            -moz-animation: pulse 1s linear 2;
            animation: pulse 1s linear 2;
        }
        @media screen and (max-width: 750px) {
            #info { margin-right:auto; clear:both; }
            nav > ul { border-bottom: 0; }
            nav > ul > li { display: block; margin: 0; }
            nav > ul > li > a { display: block; margin: 0 10px; padding: 10px 0 10px 30px; border: 0; }
            nav > ul > li > a[data-for].active { border-bottom-color: #ccc; }
            #counts { position:relative; display:block; width:100%; }
            #toggleRealtime.pulse::before { top: 8px; }
        }
        @media screen and (max-width: 550px) {
            #frmFilter { width: 100%; }
        }
        @keyframes pulse {
            0% {transform: scale(1); opacity: 0;}
            50% {transform: scale(1.3); opacity: 0.7;}
            100% {transform: scale(1.6); opacity: 1;}
        }
        @-webkit-keyframes pulse {
            0% {-webkit-transform: scale(1); opacity: 0;}
            50% {-webkit-transform: scale(1.3); opacity: 0.7;}
            100% {-webkit-transform: scale(1.6); opacity: 0;}
        }
        @-moz-keyframes pulse {
            0% {-moz-transform: scale(1); opacity: 0;}
            50% {-moz-transform: scale(1.3); opacity: 0.7;}
            100% {-moz-transform: scale(1.6); opacity: 0;}
        }
    </style>
</head>

<body>
<?php echo loadClass('Html')->getNavbar(); /* devilbox edit */?>




<header>
    <nav>
        <ul>
            <li><a data-for="overview" href="#overview" class="active">Overview</a></li>
            <?php if ($opcache->getOption('allow_filelist')): ?>
            <li><a data-for="files" href="#files">File usage</a></li>
            <?php endif; ?>
            <?php if ($opcache->getOption('allow_reset')): ?>
            <li><a href="?reset=1" id="resetCache" onclick="return confirm('Are you sure you want to reset the cache?');">Reset cache</a></li>
            <?php endif; ?>
            <?php if ($opcache->getOption('allow_realtime')): ?>
            <li><a href="#" id="toggleRealtime">Enable real-time update</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<div id="tabs">
    <div id="overview">
        <div class="container">
            <div id="counts"></div>
            <div id="info">
                <div id="generalInfo"></div>
                <div id="directives"></div>
                <div id="functions">
                    <table>
                        <thead>
                            <tr><th>Available functions</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($opcache->getData('functions') as $func): ?>
                            <tr><td><a href="http://php.net/<?php echo $func; ?>" title="View manual page" target="_blank"><?php echo $func; ?></a></td></tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <br style="clear:both;" />
            </div>
        </div>
    </div>
    <div id="files">
        <?php if ($opcache->getOption('allow_filelist')): ?>
        <p><label>Start typing to filter on script path<br/><input type="text" name="filter" id="frmFilter" /><label></p>
        <?php endif; ?>
        <div class="container" id="filelist"></div>
    </div>
</div>

<footer>
    <a href="https://github.com/amnuts/opcache-gui" target="_blank">https://github.com/amnuts/opcache-gui</a>
</footer>

<script type="text/javascript">
    var realtime = false;
    var opstate = <?php echo json_encode($opcache->getData()); ?>;
    var canInvalidate = <?php echo json_encode($opcache->canInvalidate()); ?>;
    var useCharts = <?php echo json_encode($opcache->getOption('charts')); ?>;
    var allowFiles = <?php echo json_encode($opcache->getOption('allow_filelist')); ?>;
    var debounce = function(func, wait, immediate) {
        var timeout;
        wait = wait || 250;
        return function() {
            var context = this, args = arguments;
            var later = function() {
                timeout = null;
                if (!immediate) {
                    func.apply(context, args);
                }
            };
            var callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) {
                func.apply(context, args);
            }
        };
    };
    function keyUp(event){
        var compare = $('#frmFilter').val().toLowerCase();
        $('#filelist').find('table tbody tr').each(function(index){
            if ($(this).data('path').indexOf(compare) == -1) {
                $(this).addClass('hide');
            } else {
                $(this).removeClass('hide');
            }
        });
        $('#filelist table tbody').trigger('paint');
    };

    <?php if ($opcache->getOption('charts')): ?>
    var Gauge = function(el, colour) {
        this.canvas  = $(el).get(0);
        this.ctx     = this.canvas.getContext('2d');
        this.width   = this.canvas.width;
        this.height  = this.canvas.height;
        this.colour  = colour || '#6ca6ef';
        this.loop    = null;
        this.degrees = 0;
        this.newdegs = 0;
        this.text    = '';
        this.init = function() {
            this.ctx.clearRect(0, 0, this.width, this.height);
            this.ctx.beginPath();
            this.ctx.strokeStyle = '#e2e2e2';
            this.ctx.lineWidth = 30;
            this.ctx.arc(this.width/2, this.height/2, 100, 0, Math.PI*2, false);
            this.ctx.stroke();
            this.ctx.beginPath();
            this.ctx.strokeStyle = this.colour;
            this.ctx.lineWidth = 30;
            this.ctx.arc(this.width/2, this.height/2, 100, 0 - (90 * Math.PI / 180), (this.degrees * Math.PI / 180) - (90 * Math.PI / 180), false);
            this.ctx.stroke();
            this.ctx.fillStyle = this.colour;
            this.ctx.font = '60px sans-serif';
            this.text = Math.round((this.degrees/360)*100) + '%';
            this.ctx.fillText(this.text, (this.width/2) - (this.ctx.measureText(this.text).width/2), (this.height/2) + 20);
        };
        this.draw = function() {
            if (typeof this.loop != 'undefined') {
                clearInterval(this.loop);
            }
            var self = this;
            self.loop = setInterval(function(){ self.animate(); }, 1000/(this.newdegs - this.degrees));
        };
        this.animate = function() {
            if (this.degrees == this.newdegs) {
                clearInterval(this.loop);
            }
            if (this.degrees < this.newdegs) {
                ++this.degrees;
            } else {
                --this.degrees;
            }
            this.init();
        };
        this.setValue = function(val) {
            this.newdegs = Math.round(3.6 * val);
            this.draw();
        };
    }
    <?php endif; ?>

    $(function(){
        <?php if ($opcache->getOption('allow_realtime')): ?>
        function updateStatus() {
            $('#toggleRealtime').removeClass('pulse');
            $.ajax({
                url: "#",
                dataType: "json",
                cache: false,
                success: function(data) {
                    $('#toggleRealtime').addClass('pulse');
                    opstate = data;
                    overviewCountsObj.setState({
                        data : opstate.overview
                    });
                    generalInfoObj.setState({
                        version : opstate.version,
                        start : opstate.overview.readable.start_time,
                        reset : opstate.overview.readable.last_restart_time
                    });
                    filesObj.setState({
                        data : opstate.files,
                        count_formatted : opstate.overview.readable.num_cached_scripts,
                        count : opstate.overview.num_cached_scripts
                    });
                    keyUp();
                }
            });
        }
        $('#toggleRealtime').click(function(){
            if (realtime === false) {
                realtime = setInterval(function(){updateStatus()}, <?php echo (int)$opcache->getOption('refresh_time') * 1000; ?>);
                $(this).text('Disable real-time update');
            } else {
                clearInterval(realtime);
                realtime = false;
                $(this).text('Enable real-time update').removeClass('pulse');
            }
        });
        <?php endif; ?>
        $('nav a[data-for]').click(function(){
            $('#tabs > div').hide();
            $('#' + $(this).data('for')).show();
            $('nav a[data-for]').removeClass('active');
            $(this).addClass('active');
            return false;
        });
        $(document).on('paint', '#filelist table tbody', function(event, params) {
            var trs = $('#filelist').find('tbody tr');
            trs.removeClass('alternate');
            trs.filter(':not(.hide):odd').addClass('alternate');
            filesObj.setState({showing: trs.filter(':not(.hide)').length});
        });
        $('#frmFilter').bind('keyup', debounce(keyUp, <?php echo $opcache->getOption('debounce_rate'); ?>));
    });

    var MemoryUsage = React.createClass({displayName: "MemoryUsage",
        getInitialState: function() {
            return {
                memoryUsageGauge : null
            };
        },
        componentDidMount: function() {
            if (this.props.chart) {
                this.state.memoryUsageGauge = new Gauge('#memoryUsageCanvas');
                this.state.memoryUsageGauge.setValue(this.props.value);
            }
        },
        componentDidUpdate: function() {
            if (this.state.memoryUsageGauge != null) {
                this.state.memoryUsageGauge.setValue(this.props.value);
            }
        },
        render: function() {
            if (this.props.chart == true) {
                return(React.createElement("canvas", {id: "memoryUsageCanvas", width: "250", height: "250", "data-value": this.props.value}));
            }
            return(React.createElement("p", null, React.createElement("span", {className: "large"}, this.props.value), React.createElement("span", null, "%")));
        }
    });

    var HitRate = React.createClass({displayName: "HitRate",
        getInitialState: function() {
            return {
                hitRateGauge : null
            };
        },
        componentDidMount: function() {
            if (this.props.chart) {
                this.state.hitRateGauge = new Gauge('#hitRateCanvas');
                this.state.hitRateGauge.setValue(this.props.value)
            }
        },
        componentDidUpdate: function() {
            if (this.state.hitRateGauge != null) {
                this.state.hitRateGauge.setValue(this.props.value);
            }
        },
        render: function() {
            if (this.props.chart == true) {
                return(React.createElement("canvas", {id: "hitRateCanvas", width: "250", height: "250", "data-value": this.props.value}));
            }
            return(React.createElement("p", null, React.createElement("span", {className: "large"}, this.props.value), React.createElement("span", null, "%")));
        }
    });

    var OverviewCounts = React.createClass({displayName: "OverviewCounts",
        getInitialState: function() {
            return {
                data  : opstate.overview,
                chart : useCharts
            };
        },
        render: function() {
            return (
                React.createElement("div", null,
                    React.createElement("div", null,
                        React.createElement("h3", null, "memory usage"),
                        React.createElement("p", null, React.createElement(MemoryUsage, {chart: this.state.chart, value: this.state.data.used_memory_percentage}))
                    ),
                    React.createElement("div", null,
                        React.createElement("h3", null, "hit rate"),
                        React.createElement("p", null, React.createElement(HitRate, {chart: this.state.chart, value: this.state.data.hit_rate_percentage}))
                    ),
                    React.createElement("div", {id: "moreinfo"},
                        React.createElement("p", null, React.createElement("b", null, "total memory:"), " ", this.state.data.readable.total_memory),
                        React.createElement("p", null, React.createElement("b", null, "used memory:"), " ", this.state.data.readable.used_memory),
                        React.createElement("p", null, React.createElement("b", null, "free memory:"), " ", this.state.data.readable.free_memory),
                        React.createElement("p", null, React.createElement("b", null, "wasted memory:"), " ", this.state.data.readable.wasted_memory, " (", this.state.data.wasted_percentage, "%)"),
                        React.createElement("p", null, React.createElement("b", null, "number of cached files:"), " ", this.state.data.readable.num_cached_scripts),
                        React.createElement("p", null, React.createElement("b", null, "number of hits:"), " ", this.state.data.readable.hits),
                        React.createElement("p", null, React.createElement("b", null, "number of misses:"), " ", this.state.data.readable.misses),
                        React.createElement("p", null, React.createElement("b", null, "blacklist misses:"), " ", this.state.data.readable.blacklist_miss),
                        React.createElement("p", null, React.createElement("b", null, "number of cached keys:"), " ", this.state.data.readable.num_cached_keys),
                        React.createElement("p", null, React.createElement("b", null, "max cached keys:"), " ", this.state.data.readable.max_cached_keys)
                    )
                )
            );
        }
    });

    var GeneralInfo = React.createClass({displayName: "GeneralInfo",
        getInitialState: function() {
            return {
                version : opstate.version,
                start : opstate.overview.readable.start_time,
                reset : opstate.overview.readable.last_restart_time
            };
        },
        render: function() {
            return (
                React.createElement("table", null,
                    React.createElement("thead", null,
                        React.createElement("tr", null, React.createElement("th", {colSpan: "2"}, "General info"))
                    ),
                    React.createElement("tbody", null,
                        React.createElement("tr", null, React.createElement("td", null, "Zend OPcache"), React.createElement("td", null, this.state.version.version)),
                        React.createElement("tr", null, React.createElement("td", null, "PHP"), React.createElement("td", null, this.state.version.php)),
                        React.createElement("tr", null, React.createElement("td", null, "Host"), React.createElement("td", null, this.state.version.host)),
                        React.createElement("tr", null, React.createElement("td", null, "Server Software"), React.createElement("td", null, this.state.version.server)),
                        React.createElement("tr", null, React.createElement("td", null, "Start time"), React.createElement("td", null, this.state.start)),
                        React.createElement("tr", null, React.createElement("td", null, "Last reset"), React.createElement("td", null, this.state.reset))
                    )
                )
            );
        }
    });

    var Directives = React.createClass({displayName: "Directives",
        getInitialState: function() {
            return { data : opstate.directives };
        },
        render: function() {
            var directiveNodes = this.state.data.map(function(directive) {
                var map = { 'opcache.':'', '_':' ' };
                var dShow = directive.k.replace(/opcache\.|_/gi, function(matched){
                    return map[matched];
                });
                var vShow;
                if (directive.v === true || directive.v === false) {
                    vShow = React.createElement('i', {}, directive.v.toString());
                } else if (directive.v === '') {
                    vShow = React.createElement('i', {}, 'no value');
                } else {
                    vShow = directive.v;
                }
                return (
                    React.createElement("tr", {key: directive.k},
                        React.createElement("td", {title: 'View ' + directive.k + ' manual entry'}, React.createElement("a", {href: 'http://php.net/manual/en/opcache.configuration.php#ini.'
                        + (directive.k).replace(/_/g,'-'), target: "_blank"}, dShow)),
                        React.createElement("td", null, vShow)
                    )
                );
            });
            return (
                React.createElement("table", null,
                    React.createElement("thead", null,
                        React.createElement("tr", null, React.createElement("th", {colSpan: "2"}, "Directives"))
                    ),
                    React.createElement("tbody", null, directiveNodes)
                )
            );
        }
    });

    var Files = React.createClass({displayName: "Files",
        getInitialState: function() {
            return {
                data : opstate.files,
                showing: null,
                allowFiles: allowFiles
            };
        },
        handleInvalidate: function(e) {
            e.preventDefault();
            if (realtime) {
                $.get('#', { invalidate: e.currentTarget.getAttribute('data-file') }, function(data) {
                    console.log('success: ' + data.success);
                }, 'json');
            } else {
                window.location.href = e.currentTarget.href;
            }
        },
        render: function() {
            if (this.state.allowFiles) {
                var fileNodes = this.state.data.map(function(file, i) {
                    var invalidate, invalidated;
                    if (file.timestamp == 0) {
                        invalidated = React.createElement("span", null, React.createElement("i", {className: "invalid metainfo"}, " - has been invalidated"));
                    }
                    if (canInvalidate) {
                        invalidate = React.createElement("span", null, ", ", React.createElement("a", {className: "metainfo", href: '?invalidate='
                        + file.full_path, "data-file": file.full_path, onClick: this.handleInvalidate}, "force file invalidation"));
                    }
                    return (
                        React.createElement("tr", {key: file.full_path, "data-path": file.full_path.toLowerCase(), className: i%2?'alternate':''},
                            React.createElement("td", null,
                                React.createElement("div", null,
                                    React.createElement("span", {className: "pathname"}, file.full_path), React.createElement("br", null),
                                    React.createElement(FilesMeta, {data: [file.readable.hits, file.readable.memory_consumption, file.last_used]}),
                                    invalidate,
                                    invalidated
                                )
                            )
                        )
                    );
                }.bind(this));
                return (
                    React.createElement("div", null,
                        React.createElement(FilesListed, {showing: this.state.showing}),
                        React.createElement("table", null,
                            React.createElement("thead", null,
                                React.createElement("tr", null,
                                    React.createElement("th", null, "Script")
                                )
                            ),
                            React.createElement("tbody", null, fileNodes)
                        )
                    )
                );
            } else {
                return React.createElement("span", null);
            }
        }
    });

    var FilesMeta = React.createClass({displayName: "FilesMeta",
        render: function() {
            return (
                React.createElement("span", {className: "metainfo"},
                    React.createElement("b", null, "hits: "), React.createElement("span", null, this.props.data[0], ", "),
                    React.createElement("b", null, "memory: "), React.createElement("span", null, this.props.data[1], ", "),
                    React.createElement("b", null, "last used: "), React.createElement("span", null, this.props.data[2])
                )
            );
        }
    });

    var FilesListed = React.createClass({displayName: "FilesListed",
        getInitialState: function() {
            return {
                formatted : opstate.overview.readable.num_cached_scripts,
                total     : opstate.overview.num_cached_scripts
            };
        },
        render: function() {
            var display = this.state.formatted + ' file' + (this.state.total == 1 ? '' : 's') + ' cached';
            if (this.props.showing !== null && this.props.showing != this.state.total) {
                display += ', ' + this.props.showing + ' showing due to filter';
            }
            return (React.createElement("h3", null, display));
        }
    });

    var overviewCountsObj = ReactDOM.render(React.createElement(OverviewCounts, null), document.getElementById('counts'));
    var generalInfoObj = ReactDOM.render(React.createElement(GeneralInfo, null), document.getElementById('generalInfo'));
    var filesObj = ReactDOM.render(React.createElement(Files, null), document.getElementById('filelist'));
    ReactDOM.render(React.createElement(Directives, null), document.getElementById('directives'));
</script>
<?php echo loadClass('Html')->getFooter(); /* devilbox edit */?>
</body>
</html>
