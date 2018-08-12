<?php

class Actions_Controller extends Controller
{
    public function __construct($config)
    {
        parent::__construct($config);

        $this->statsModel = new Stats_Model($this->app->current);
        $this->template   = Template::factory('json');
    }

    public function resetAction()
    {
        $this->statsModel->resetStats();

        $this->template->render(true);
    }

    public function fallAction()
    {
        $this->db->flushAll();

        $this->template->render(true);
    }

    public function fdbAction()
    {
        $this->db->flushDB();

        $this->template->render(true);
    }
}
