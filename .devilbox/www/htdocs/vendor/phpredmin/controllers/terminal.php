<?php

class Terminal_Controller extends Controller
{
    const NAVIGATION_UP   = 'up';
    const NAVIGATION_DOWN = 'down';

    public function __construct($config)
    {
        parent::__construct($config);

        $this->config = App::instance()->config['terminal'];
    }

    public function indexAction()
    {
        $this->db->set("phpredmin:terminal:history:pointer", -1);

        Template::factory()->render('terminal/index');
    }

    public function runAction()
    {
        if (!$this->config['enable']) {
            echo "Terminal is not enabled";
            die;
        }

        $command = $this->inputs->post('command', null);

        if (isset($command)) {
            $historylimit = $this->config['history'];
            $historykey   = "phpredmin:terminal:history";

            if ($historylimit > 0) {
                $this->db->lRem($historykey, $command, 0);
                $this->db->rPush($historykey, $command);
                $this->db->lTrim($historykey, $historylimit * -1, -1);

                $this->db->set("phpredmin:terminal:history:pointer", -1);
            } else {
                $this->db->del($historykey);
            }

            $command = escapeshellcmd($command);
            exec("redis-cli -h {$this->app->current['host']} -p {$this->app->current['port']} {$command}", $result);

            Template::factory('json')->render(array('result' => $result));
        }
    }

    public function historyAction()
    {
        $historylimit = $this->config['history'];
        $historykey   = "phpredmin:terminal:history";
        $historylen   = $this->db->lLen($historykey);
        $command      = '';
        $reset        = false;

        if ($historylimit > 0 && $historylen > 0) {
            $navigation = $this->inputs->get('navigation', self::NAVIGATION_UP);
            $start      = $this->inputs->get('start');
            $pointer    = $this->db->get("phpredmin:terminal:history:pointer");

            if ($historylen > $historylimit) {
                $this->db->lTrim($historykey, $historylimit * -1, -1);
                $historylen = $historylimit;
            }

            if ($navigation == self::NAVIGATION_UP) {
                if ($historylen <= ($pointer * -1)) {
                    $pointer = $historylen * -1;
                } elseif (!isset($start)) {
                    $pointer--;
                }
            } elseif ($pointer != -1) {
                $pointer++;
            } else {
                $reset = true;
            }

            $command = $this->db->lRange($historykey, $pointer, $pointer);

            $this->db->set("phpredmin:terminal:history:pointer", $pointer);
        }

        Template::factory('json')->render(array('command' => $command, 'reset' => $reset));
    }
}
