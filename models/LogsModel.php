<?php

use ActiveRecord\Model;

class LogsModel extends Model
{

    public static $table_name = 'logs';

    public static function novo($log)
    {
        $register = new LogsModel();
        $register->method = $log['method'];
        $register->uri = $log['uri'];
        $register->query = $log['query'];
        $register->body = $log['body'];
        $register->headers = $log['headers'];
        $register->usuario = $log['usuario'];
        $register->create_at = date('Y-m-d H:i:s');
        $register->save();

        return true;
    }

}