<?php
class Welcome_Controller extends Controller
{

    public function indexAction($db = null)
    {
        Template::factory()->render('welcome/index');
    }

    public function configAction()
    {
        $config = $this->db->config('GET', '*');

        Template::factory()->render('welcome/config', array('config' => $config));
    }

    public function statsAction()
    {
        Template::factory()->render('welcome/stats');
    }

    public function infoAction()
    {
        $info       = $this->db->info();
        $uptimeDays = floor($info['uptime_in_seconds'] / 86400);
        $dbSize     = $this->db->dbSize();
        $lastSave   = $this->db->lastSave();


        Template::factory()->render('welcome/info', array('info'       => $info,
                                                          'uptimeDays' => $uptimeDays,
                                                          'dbSize'     => $dbSize,
                                                          'lastSave'   => $lastSave));
    }

    public function saveAction($async = 0)
    {
        $saved    = $async ? $this->db->bgSave() : $this->db->save();
        $filename = current($this->db->config('GET', 'dbfilename'));

        Template::factory('json')->render(array(
            'status' => $saved,
            'async' => $async,
            'filename' => $filename,
        ));
    }

    public function slowlogAction()
    {
        $support    = false;
        $slowlogs   = array();
        $serverInfo = $this->db->info('server');
        $count      = $this->inputs->post('count', null);
        $count      = isset($count) ? $count : 10;

        if (!preg_match('/^(0|1)/', $serverInfo['redis_version']) && !preg_match('/^2\.[0-5]/', $serverInfo['redis_version'])) {
            $slowlogs = $this->db->eval("return redis.call('slowlog', 'get', {$count})");
            $support  = true;
        }

        Template::factory()->render('welcome/slowlog', array('slowlogs' => $slowlogs,
                                                             'support'  => $support,
                                                             'version'  => $serverInfo['redis_version'],
                                                             'count'    => $count));
    }
}
