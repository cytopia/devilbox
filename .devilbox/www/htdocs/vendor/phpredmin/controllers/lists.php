<?php

class Lists_Controller extends Controller
{
    public function addAction()
    {
        $added = false;

        if ($this->router->method == Router::POST) {
            $value = $this->inputs->post('value', null);
            $key   = $this->inputs->post('key', null);
            $type  = $this->inputs->post('type', null);
            $pivot = $this->inputs->post('pivot', null);

            if (isset($value) && trim($value) != '' && isset($key) && trim($key) != '' &&
                (isset($type) && in_array($type, array('before', 'after', 'prepend', 'append')))) {
                if (($type == 'before' || $type == 'after') && (!isset($pivot) || $pivot == '')) {
                    $added = false;
                } else {
                    switch ($type) {
                        case 'prepend':
                            $added = (boolean) $this->db->lPush($key, $value);
                            break;
                        case 'append':
                            $added = (boolean) $this->db->rPush($key, $value);
                            break;
                        case 'before':
                            $added = (boolean) $this->db->lInsert($key, Redis::BEFORE, $pivot, $value);
                            break;
                        case 'after':
                            $added = (boolean) $this->db->lInsert($key, Redis::AFTER, $pivot, $value);
                            break;
                    }
                }
            }
        }

        Template::factory('json')->render($added);
    }

    public function viewAction($key, $page = 0)
    {
        $count  = $this->db->lSize(urldecode($key));
        $start  = $page * 30;
        $values = $this->db->lRange(urldecode($key), $start, $start + 29);

        Template::factory()->render('lists/view', array('count' => $count, 'values' => $values, 'key' => urldecode($key),
                                                        'page'  => $page));
    }

    public function delAction()
    {
        if ($this->router->method == Router::POST) {
            $key   = $this->inputs->post('key');
            $value = $this->inputs->post('value');
            $type  = $this->inputs->post('type_options');

            if ($type == 'all') {
                $this->db->lRem($key, $value, 0);
            } elseif ($type == 'count') {
                $count = $this->inputs->post('count');
                $this->db->lRem($key, $value, $count);
            }
        }

        $this->router->redirect("lists/view/{$this->app->current['serverId']}/{$this->app->current['database']}/" . urlencode($key));
    }
}
