<?php

namespace JDOUnivers\Helpers\DB;

use PDO;
use PDOException;
/**
 * Implement the adapter for PDO
 */
class DBManager {

    private $pdo = false;
    protected static $instance;
    protected $sql; // to display errors

    /**
     * Create the pdo instance
     */
    private function __construct($host = DB_HOST, $db = DB_NAME, $user = DB_USER, $passwd = DB_PASS) {
        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$db", $user, $passwd, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (Exception $e) {
            echo "Erreur : " . $e->getMessage() . "<br />";
        }
        $this->getTablesClass();
    }

    /** */
    private function getTablesClass(){
      $tables = $this->executeSQL("SHOW TABLES");
      foreach ($tables as $table) {
        if(substr($table[0], 0, strlen(PREFIX)) === PREFIX){
          $table[0] = substr($table[0],strlen(PREFIX));
          $columns = $this->executeSQL("SHOW COLUMNS FROM ".PREFIX.$table[0]);
          $classcode = "class ".$table[0]." extends JDOUnivers\Helpers\DB\Entity { ";
          $primaryColumns=array();
          foreach ($columns as $column){
            if($column['Key'] == "PRI") $primaryColumns[] = $column['Field']; 
            $classcode .= "public \${$column['Field']}; ";
          }
          $classcode .= "public \$primaryColumns = [\"".implode('","',$primaryColumns)."\"]; ";
          $classcode .= "} ";
          eval($classcode);
        }
      }
    }

    protected function __clone() {
    }

    public static function getInstance() {
        if (!isset(self::$instance)) // Si on n'a pas encore instancié notre classe.
        {
            self::$instance = new self; // On s'instancie nous-mêmes. :)
        }
        return self::$instance;
    }


    /**
     * Prepare the query
     */
    function prepare($sql) {
        $this->sql = $sql;
        $this->query = $this->pdo->prepare($sql);
    }

    /**
     * Execute the query
     */
    function execute($params = false) {
        try {
            if ($params != false)
              $this->bindparams($params);

            $this->query->execute();
        } catch (PDOException $e) {
            throw  new \Exception($e->getMessage() . " " . $this->sql);
        }
    }


    function get_nb_rows_affected(){
        return $this->query->rowCount();
    }

    /**
     * Execute the query
     */
    function bindparams($params) {
        for ($i=0; $i < count($params); $i++) {
          $pdoparam = PDO::PARAM_STR;
          switch (gettype($params[$i])) {
            case "boolean":
              $pdoparam = PDO::PARAM_BOOL;
              break;
            case "integer":
              $pdoparam = PDO::PARAM_INT;
              break;
            case "NULL":
              $pdoparam = PDO::PARAM_NULL;
              break;
          }
          $this->query->bindValue($i+1, $params[$i], $pdoparam);
        }
    }


    /**
     * Execute the query
     */
    function executeSQL($sql,$params = false) {
        $this->sql = $sql;
        $this->query = $this->pdo->prepare($sql);
        try {
            if ($params == false)
                $this->query->execute();
            else
                $this->query->execute($params);
        } catch (PDOException $e) {
            throw  new \Exception($e->getMessage() . " " . $this->sql);
        }
        return $this->query->fetchAll();
    }

    /**
     * Fetch one record
     * Take a look at the mode !!!!
     */
    function fetch($className = false) {
        if($className !== false)
            $this->query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $className);
        return $this->query->fetch();
    }

    /**
     * Fetch all records
     */
    function fetchAll($className) {
        if($className !== false)
            $this->query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $className);
        return $this->query->fetchAll();
    }

    /**
     * Retrieve last insert id
     */
    function lastInsertId() {
        return $this->pdo->lastInsertId();
    }
}
