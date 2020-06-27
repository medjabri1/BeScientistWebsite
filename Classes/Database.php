<?php

    class Database {

        private static $db = 'mysql';
        private static $host = 'localhost';
        private static $db_name = 'id13569561_pfe';
        private static $username = 'id13569561_root';
        private static $password = 'site-PFE-esto2020';

        private static $dsn = '';

        public static function getConnection(): PDO {

            self::$dsn = self::$db .':host='. self::$host .';dbname='. self::$db_name;

            $pdo = new PDO(self::$dsn, self::$username, self::$password);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            return $pdo;
        }

    }

?>