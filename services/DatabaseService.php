<?php
if ( !isset( $_SERVER['HTTP_REFERER']) ) die ("Direct access not permitted");

require './database/DB.php';

class DatabaseService {
    public static function getDbInstance(): DB
    {
        $dbConfig = require_once('./config/db.php');

        $dbClass = new DB(
            $dbConfig['DB_USERNAME'],
            $dbConfig['DB_PASSWORD'],
            $dbConfig['DB_HOST'],
            $dbConfig['DB_DATABASE']
        );

        $conn = $dbClass->connect()
            ->setChar($dbConfig['DB_CHARSET'])
            ->conn;

        if ($conn && $conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        return $dbClass;
    }

    public static function insertVisitorData($db, $data)
    {
        $values = array_values($data);
        $columns = array_keys($data);

        return $db->insert($columns, $values);
    }

    public static function updateVisitorData($db, $visitorData)
    {
        $visitorData['views_count'] += 1;
        $visitorData['view_date'] = DB::now();
        $id = (int)$visitorData['id'];

        unset($visitorData['id']);

        return $db->updateVisit($id, $visitorData);
    }
}
?>