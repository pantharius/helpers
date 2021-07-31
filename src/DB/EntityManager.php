<?php

namespace JDOUnivers\Helpers\DB;


/**
 * PDO implementation to CRUD
 */
class EntityManager {

    protected static $crud;

    /**
     * Need a connection to the database
     */
    private $dbmanager;

    /**
     * Constructor
     */
    public function __construct() {
        $this->dbmanager = DBManager::getInstance();
    }

    protected function __clone() {
    } // Méthode de clonage en privé aussi.

    public static function getInstance() {
        if (!isset(self::$crud)) // Si on n'a pas encore instancié notre classe.
        {
            self::$crud = new self; // On s'instancie nous-mêmes. :)
        }

        return self::$crud;
    }

    /**
     * Save inside the DB
     */
    public function save($instance,$forceNew=false,$ignoreColumns=array()) {
        if (!$forceNew) {
            $conditions = array();
            $values = array();
            foreach ($instance->primaryColumns as $columname) {
                $conditions[] = $columname."=?";
                $values[] = $instance->$columname;
            }
            $condition = implode(" AND ", $conditions);
            $query = new Query(get_class($instance));
            if($query->exists($condition,$values)){
                return $this->update($instance, $condition, $values);
            }
        }
        return $this->insert($instance);
    }

    /**
     * Insert INTO ....
     */
    public function insert($instance) {
        $tableName = get_class($instance);

        $sql = "INSERT INTO `".PREFIX."$tableName` (";

        $vars = get_class_vars(get_class($instance));
        unset($vars["primaryColumns"]);

        $sql .= join(",",array_map(function($key) { return '`'.$key.'`'; },array_keys($vars)));
        $sql .= ") VALUES(";
        $sql .= join(",",str_split(str_repeat("?", count($vars))));
        $sql .= ") ";

        $values = array();
        foreach ($vars as $k => $v) {
                $values[] = $instance->$k;
        }
        $this->dbmanager->prepare($sql);
        $this->dbmanager->execute($values);
        return $this->dbmanager->lastInsertId();
    }


    /**
     * UPDATE ...
     */
    public function update($instance, $condition, $params) {
        $tableName = get_class($instance);
        $sql = "UPDATE ".PREFIX."$tableName SET ";
        $vars = get_class_vars(get_class($instance));
        unset($vars["primaryColumns"]);

        $values = array();
        $first = true;
        $notPrimaryColumns = array_diff(array_keys($vars),$instance->primaryColumns);
        $sql .= implode("=?, ",$notPrimaryColumns)."=?";
        foreach ($notPrimaryColumns as $column) {
            if($column != "primaryColumns")
                $values[]=$instance->$column;
        }
        $sql .= " WHERE ". $condition;
        $values = array_merge($values,$params);
        $this->dbmanager->prepare($sql);
        $this->dbmanager->execute($values);
    }

    /**
     * DELETE FROM ....
     */
    public function delete($instance) {
        $tableName = get_class($instance);
        $query = new Query($tableName);

        $conditions = array();
        $values = array();
        foreach ($instance->primaryColumns as $columname) {
            $conditions[] = $columname."=?";
            $values[] = $instance->$columname;
        }
        $condition = implode(" AND ", $conditions);
        if($query->exists($condition,$values)){
            $sql = "DELETE FROM ".PREFIX."$tableName WHERE $condition";
        }
        $this->dbmanager->prepare($sql);
        $this->dbmanager->execute($values);
    }
}


?>
