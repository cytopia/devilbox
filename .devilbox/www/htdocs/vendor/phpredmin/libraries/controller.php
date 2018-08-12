<?php
class controller
{
    private $_objects = null;

    public function __construct($config)
    {
        $this->_objects['app']     = App::instance();
        $this->_objects['router']  = Router::instance();
        $this->_objects['inputs']  = Inputs::instance();
        $this->_objects['session'] = Session::instance();
        $this->_objects['log']     = Log::factory();
        
        if (!isset($this->app->config['database']['redis'][$config['serverId']])) {
            $config['serverId'] = 0;
        }
        
        $current = $this->app->config['database']['redis'][$config['serverId']];
        $current['serverId'] = $config['serverId'];
        
        $this->_objects['db']      = Db::factory($current);
        $this->_objects['infoModel'] = new Info_Model($current);
        
        $info = $this->db->info();
        $dbs = $this->infoModel->getDbs($info);

        if (!isset($current['max_databases'])) {
            $databasesConfig = $this->_objects['db']->config('GET', 'databases');
            $current['max_databases'] = $databasesConfig['databases'];
        }

        // Take care of invalid dbId's. If invalid, set to first available database
        if (!is_numeric($config['dbId'])
            || $config['dbId'] < 0
            || $config['dbId'] >= $current['max_databases']
        ) {
            $config['dbId'] = $dbs[0];
        }

        $current['newDB'] = (!in_array($config['dbId'], $dbs) ? true : false);

        $current['database'] = $config['dbId'];

        // Extract number of keys
        foreach ($dbs as $i) {
            if (preg_match('/^keys=([0-9]+),expires=([0-9]+)/', $info["db{$i}"], $matches)) {
                $current['dbs'][$i] = array(
                    'id' => $i,
                    'keys' => $matches[1],
                    'name' => (isset($current['dbNames'][$i]) ? $current['dbNames'][$i] : null),
                );
            }
        }
        $this->db->select($current['database']);

        $this->app->current = $current;
    }

    public function __get($object)
    {
        return isset($this->_objects[$object]) ? $this->_objects[$object] : null;
    }
}
