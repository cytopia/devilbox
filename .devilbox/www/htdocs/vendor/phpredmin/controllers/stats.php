<?php

class Stats_Controller extends Controller
{
    public function __construct($config)
    {
        parent::__construct($config);

        $this->statsModel = new Stats_Model($this->app->current);
        $this->template   = Template::factory('json');
    }

    public function keysAction()
    {
        $result      = array();
        $from        = $this->router->query('from', strtotime('-12 hours'));
        $to          = $this->router->query('to', time());
        $hits        = $this->statsModel->getKeys('hits', $from, $to);
        $misses      = $this->statsModel->getKeys('misses', $from, $to);
        $expiredKeys = $this->statsModel->getKeys('expired_keys', $from, $to);

        $result[] = array('key' => 'Keyspace Misses', 'values' => $misses);
        $result[] = array('key' => 'Keyspace Hits', 'values' => $hits);
        $result[] = array('key' => 'Expired Keys', 'values' => $expiredKeys);

        $this->template->render($result);
    }

    public function commandsAction()
    {
        $result      = array();
        $from        = $this->router->query('from', strtotime('-12 hours'));
        $to          = $this->router->query('to', time());
        $connections = $this->statsModel->getKeys('connections', $from, $to);
        $commands    = $this->statsModel->getKeys('commands', $from, $to);

        $result[] = array('key' => 'Connections Received', 'values' => $connections);
        $result[] = array('key' => 'Commands Processed', 'values' => $commands);

        $this->template->render($result);
    }

    public function clientsAction()
    {
        $result  = array();
        $from    = $this->router->query('from', strtotime('-12 hours'));
        $to      = $this->router->query('to', time());
        $clients = $this->statsModel->getKeys('clients', $from, $to);

        $result[] = array('key' => 'Clients', 'values' => $clients);

        $this->template->render($result);
    }

    public function memoryAction()
    {
        $result = array();
        $from   = $this->router->query('from', strtotime('-12 hours'));
        $to     = $this->router->query('to', time());
        $memory = $this->statsModel->getKeys('memory', $from, $to);

        $result[] = array('key' => 'Memory Usage', 'values' => $memory);

        $this->template->render($result);
    }

    public function dbkeysAction()
    {
        $result = array();
        $from   = $this->router->query('from', strtotime('-12 hours'));
        $to     = $this->router->query('to', time());

        foreach ($this->infoModel->getDbs($this->db->info()) as $db) {
            $keys     = $this->statsModel->getKeys("db{$db}:keys", $from, $to);
            $result[] = array('key' => "DB{$db} Keys", 'values' => $keys);
        }

        $this->template->render($result);
    }

    public function dbexpiresAction()
    {
        $result = array();
        $from   = $this->router->query('from', strtotime('-12 hours'));
        $to     = $this->router->query('to', time());

        foreach ($this->infoModel->getDbs($this->db->info()) as $db) {
            $keys     = $this->statsModel->getKeys("db{$db}:expired_keys", $from, $to);
            $result[] = array('key' => "DB{$db} Expired Keys", 'values' => $keys);
        }

        $this->template->render($result);
    }

    public function cpuAction()
    {
        $result     = array();
        $from       = $this->router->query('from', strtotime('-12 hours'));
        $to         = $this->router->query('to', time());
        $user_cpu   = $this->statsModel->getKeys('user_cpu', $from, $to);
        $system_cpu = $this->statsModel->getKeys('system_cpu', $from, $to);

        $result[] = array('key' => 'User CPU Usage', 'values' => $user_cpu);
        $result[] = array('key' => 'System CPU Usage', 'values' => $system_cpu);

        $this->template->render($result);
    }
    
    public function aofAction()
    {
        $result     = array();
        $from       = $this->router->query('from', strtotime('-12 hours'));
        $to         = $this->router->query('to', time());
        $aof_size   = $this->statsModel->getKeys('aof_size', $from, $to);
        $aof_base   = $this->statsModel->getKeys('aof_base', $from, $to);

        $result[] = array('key' => 'AOF current size', 'values' => $aof_size);
        $result[] = array('key' => 'AOF base size', 'values' => $aof_base);

        $this->template->render($result);
    }
}
