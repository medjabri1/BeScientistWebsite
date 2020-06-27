<?php

    class Model {

        protected static function findBy($value, $column, $table_name) {

            $conn = Database::getConnection();
            $query = "select distinct * from ". $table_name ." where $column = '$value'";
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $data = $stmt->fetchAll();

            return count($data) ? $data[0] : null;
        }

        protected static function findAll($table_name) {
            $conn = Database::getConnection();
            $query = "select * from ". $table_name ." order by created_at desc";
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $data = $stmt->fetchAll();

            return count($data) ? $data : null;
        }

        public static function submitData($query, $params) : bool {
            $conn = Database::getConnection();
            $stmt = $conn->prepare($query);
            
            if($stmt->execute($params)) return true;
            else return false;
        }

    }

?>