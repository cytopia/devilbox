<?php

class Zsets_Controller extends Controller
{
    public function addAction()
    {
        $added = false;

        if ($this->router->method == Router::POST) {
            $value = $this->inputs->post('value', null);
            $key   = $this->inputs->post('key', null);
            $score = $this->inputs->post('score', null);

            if (isset($value) && trim($value) != '' && isset($key) && trim($key) != '' && isset($score) && trim($score) != '') {
                $added = (boolean) $this->db->zAdd($key, (double) $score, $value);
            }
        }

        Template::factory('json')->render($added);
    }

    public function viewAction($key, $page = 0)
    {
        $count  = $this->db->zSize(urldecode($key));
        $start  = $page * 30;
        $values = $this->db->zRange(urldecode($key), $start, $start + 29, true);

        Template::factory()->render('zsets/view', array('count' => $count, 'values' => $values, 'key' => urldecode($key),
                                                        'page'  => $page));
    }

    public function deleteAction($key, $value)
    {
        Template::factory('json')->render($this->db->zDelete(urldecode($key), urldecode($value)));
    }

    public function delallAction()
    {
        if ($this->router->method == Router::POST) {
            $results = array();
            $values  = $this->inputs->post('values', array());
            $keyinfo = $this->inputs->post('keyinfo', null);

            foreach ($values as $key => $value) {
                $results[$value] = $this->db->zDelete($keyinfo, $value);
            }

            Template::factory('json')->render($results);
        }
    }
}
