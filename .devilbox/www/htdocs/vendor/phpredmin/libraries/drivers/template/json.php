<?php
class JsonTemplate
{
    public function render($data, $setHeader = false)
    {
        $setHeader and ($data ? header('HTTP/1.0 200 OK') : header('HTTP/1.0 404 Not Found'));

        header('Content-Type: application/json');

        echo json_encode($data);
        die();
    }
}
