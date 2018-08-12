<?php

class Info_Model extends Model
{
    public function getDbs($info)
    {
        $result = array();
        $keys   = array_keys($info);
        $dbs    = preg_grep('/^db[0-9]+?$/', $keys);

        foreach ($dbs as $db) {
            if (preg_match('/^db([0-9]+)$/', $db, $matches)) {
                $result[] = $matches[1];
            }
        }

        return $result;
    }
}
