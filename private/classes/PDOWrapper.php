<?php

class PDOWrapper {
    private $_error_msg;
    private $_database;
    private $_pdo;
    private $_constants;

    public function __construct($host='localhost', $username, $password, $database, $port=3306) {
         $c = array(
              'db_host'=>$host,
              'username'=>$username,
              'password'=>$password,
              'database'=>$database,
              'port'=>$port
         );
         $this->_constants = $c;
         $this->_database = $database;
    }


    public function query($query,$querydata=''){
        try {
            if (empty($querydata)) {
                 $querydata = array();
            }
            $start = explode(' ', microtime());
            $results = TRUE;
            $c = $this->_constants;
            $bits = explode(' ', $query);
            $first = trim(strtolower($bits[0]));


            // $c['db_host'] = $host;

            foreach($c as $key=>$value) $c['key'] = trim($value);

            $this->_pdo = NULL;
            $this->_pdo = new PDO("mysql:host={$c['db_host']};port={$c['port']};dbname={$c['database']}", $c['username'], $c['password']);
            $this->_pdo->beginTransaction();
            if(!empty($querydata)){
                $dbh = $this->_pdo->prepare($query);
                $multi_dimensional = (@is_array($querydata[0])) ? true : false;
                if($multi_dimensional == true){
                    foreach($querydata as $d){
                        if (!$dbh->execute($d)) {
                            throw new PDOException(print_r($dbh->errorInfo(), 1));
                        }
                    }
                } else {
                    if (!$dbh->execute($querydata)) {
                        throw new PDOException(print_r($dbh->errorInfo(), 1));
                    }
                }
            } else {
                $dbh = $this->_pdo->prepare($query);
                if (!$dbh->execute()) {
                    throw new PDOException(print_r($dbh->errorInfo(), 1));
                }
            }
            $bits = explode(" ",strtolower($query));
            switch(trim($bits[0])) {
                case "select":
                    $results = $dbh->fetchAll(PDO::FETCH_ASSOC);
                    break;
                case "insert":
                    $results = $this->_pdo->lastInsertId();
                    break;
                case "update":
                    $results = true;
                    break;
                case "replace":
                    $results = true;
                    break;
                case "delete":
                    $results = true;
                    break;
            }
            $this->_pdo->commit();
            $this->_pdo = null; // close connection
            $end = explode(' ', microtime());
            $query_time = ($end[1] + $end[0]) - ($start[1] + $start[0]);
            if ($query_time > 10) {
                 mail("michaeljonathanbeck@gmail.com", "slow query report success", $query . "\n\nSuccess\n\n" . print_r($querydata,1) . "\n\n" . print_r($_SERVER,1) . print_r(get_included_files(), 1));
            }
            return $results;
        } catch (PDOException $e) {
            $end = explode(' ', microtime());
            $query_time = ($end[1] + $end[0]) - ($start[1] + $start[0]);
            if ($query_time > 10) {
                 mail("michaeljonathanbeck@gmail.com", "slow query report PDO Error", "error\n\n" . $query . "\n\n" . print_r($querydata,1)."\n\n".print_r($_SERVER,1)."\n\n".print_r(get_included_files(), 1) . " " . $e->getMessage() . " " . print_r($e->getMessage(), 1));
            }
            $this->_error_msg = $e->getMessage();
            $this->_pdo->rollBack();
            $this->_pdo = null;
            return false;
        }
    }

    public function getErrorMsg() {
        return $this->_error_msg;
    }

}
