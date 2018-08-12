<?php

class Strings_Controller extends Controller
{
    public function viewAction($key)
    {
        $edited = null;

        if ($this->router->method == Router::POST) {
            $newvalue = $this->inputs->post('newvalue', null);
            $key      = $this->inputs->post('key', null);

            if (!isset($newvalue) || trim($newvalue) == '' || !isset($key) || trim($key) == '') {
                $edited = false;
            } else {
                $edited = $this->db->set($key, $newvalue);
            }
        }

        $value = $this->db->get(urldecode($key));

        Template::factory()->render('strings/view', array('edited' => $edited, 'key' => urldecode($key), 'value' => $value));
    }

    public function addAction()
    {
        $added = false;

        if ($this->router->method == Router::POST) {
            $value = $this->inputs->post('value', null);
            $key   = $this->inputs->post('key', null);

            if (isset($value) && trim($value) != '' && isset($key) && trim($key) != '') {
                $added = $this->db->set($key, $value);
            }
        }

        Template::factory('json')->render($added);
    }
}
