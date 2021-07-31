<?php

namespace JDOUnivers\Helpers\DB;
use Exception;

class Query {

  /**
   *The DB Manager
   */
  protected $dbManager;

  /**
   * We need the table
   */
  protected $tableName;

  /**
   * The query under construction
   */
  protected $sql;


  /**
   * Params needed for the current querry (array)
   */

  protected $params;

   public function __construct($tableName)
   {
     $this->tableName = $tableName;
     $this->dbManager = DBManager::getInstance();
   }

   public function getById($id) {
       $this->dbManager->prepare("SELECT * FROM `".PREFIX."$this->tableName` WHERE `id`=?");
       $this->dbManager->execute(array($id));
       return $this->dbManager->fetch($this->tableName);
   }

   /**
    * Execute the query under construction
    */
   public function execute() {
        $this->dbManager->prepare($this->sql);
        $this->dbManager->execute($this->params);
        return $this->dbManager->fetchAll($this->tableName);
   }
    public function execut() {
        $this->dbManager->prepare($this->sql);
        $this->dbManager->execute($this->params);
        return $this->dbManager->fetch($this->tableName);
    }

    /**
    * Execute the query
    */
    public function executeSQL($sql,$params) {
        $this->dbManager->prepare($sql);
        $this->dbManager->execute($params);
        return $this->dbManager->fetchAll($this->tableName);
    }
    public function executSQL($sql,$params) {
        $this->dbManager->prepare($sql);
        $this->dbManager->execute($params);
        return $this->dbManager->fetch($this->tableName);
    }
 
    public function justexecuteSQL($sql,$params) {
        $this->dbManager->prepare($sql);
        $this->dbManager->execute($params);
    }
    public function delete($cond,$params) {
        $this->dbManager->prepare("DELETE FROM `".PREFIX."$this->tableName` WHERE $cond");
        $this->dbManager->execute($params);
        return $this->dbManager->get_nb_rows_affected();
    }
    public function update($set,$cond,$params) {
        $this->dbManager->prepare("UPDATE `".PREFIX."$this->tableName` SET " . (is_array($set) ? implode($set,",") : $set) . " WHERE $cond");
        $this->dbManager->execute($params);
        return $this->dbManager->get_nb_rows_affected();
    }

    public function exists($cond, $params = false){
        $this->dbManager->prepare("SELECT EXISTS(SELECT * FROM `".PREFIX."$this->tableName` WHERE $cond) AS isexist");
        $this->dbManager->execute($params);
        $ret = $this->dbManager->fetch();
        return ($ret["isexist"]==1);
    }

   /**
    * General Query
    * This is a preparation.
    * The query is execute only when the function execute is called
    * @param $cond : something after the where.....
    * @param $params the array necessary for the execution
    * return $this
    */
    function prepareFindWithCondition($cond, $params = false) {
        $this->sql = "SELECT * FROM `".PREFIX."$this->tableName` WHERE $cond";
        $this->params = $params;
        return $this;
    }


   /**
    * Find all records
    * @return $this
    */
    public function prepareFindAll($columns = false) {
        $this->sql = "SELECT ". (($columns === false) ? "*" : $columns) ." FROM `".PREFIX.$this->tableName."`";
        return $this;
    }

   /**
    * Add a join in query
    * @param $table
    * @param $cond
    * @return $this
    */
    public function join($table, $cond, $type = "INNER") {
        $this->sql .= " $type JOIN `$table` ON $cond";
        return $this;
    }

    public function where($cond, $params = false) {
        $this->sql .= " WHERE $cond";
        $this->params = $params;
        return $this;
    }

    /**
     * Add a group by in query
     * @param $field
     * @return $this
     */
     public function groupBy($field) {
         $this->sql .= " GROUP BY $field";
         return $this;
     }

     /**
      * Add a having in query
      * @param $field
      * @return $this
      */
      public function having($cond) {
          $this->sql .= " HAVING $cond";
          return $this;
      }

   /**
    * Add a limit in query
    * @param $start
    * @param $nb
    * @return $this
    */
    public function limit($start, $nb) {
        $this->sql .= " LIMIT $start,$nb";
        return $this;
    }

   /**
    * Sort results
    */

    public function orderBy($order) {
        $this->sql .= " ORDER BY $order";
        return $this;
    }

    /**
     * Find all records
     * @return $this
     */
    public function findAll() {
        return $this->prepareFindAll()->execute();
    }

    /**
    * @param $table
    * @return $this
    */
    public function union($table, $columns = false) {
        $this->sql .= " UNION SELECT ". (($columns === false) ? "*" : $columns) ." FROM $table";
        return $this;
    }

   /**
    * Automate  function findByXXX where XXX is a field of the given table
    * Automate  function getByXXX where XXX is a field of the given table
    */
    public function __call($name, $arguments) {

        if (!is_array($arguments) || count($arguments) != 1) {
            throw new Exception("Only one argument in " . get_class($this) . "->$name");
        }

        if(strpos($name, "findBy") === 0){
            $field = strtolower(substr($name, strlen("findBy")));
            return $this->prepareFindWithCondition("`$field`=?", $arguments)->execute();
        }
        else if(strpos($name, "getBy") === 0){
            $field = strtolower(substr($name, strlen("getBy")));
            return $this->prepareFindWithCondition("`$field`=?", $arguments)->execut();
        }
        else if(strpos($name, "deleteBy") === 0){
            $field = strtolower(substr($name, strlen("deleteBy")));
            return $this->delete("`$field`=?", $arguments);
        }
        else if(strpos($name, "getLastBy") === 0){
            $field = strtolower(substr($name, strlen("getLastBy")));
            return $this->prepareFindWithCondition("`$field`=?", $arguments)->orderBy("id DESC")->execut();
        }
        else if(strpos($name, "containIn") === 0){
            $field = strtolower(substr($name, strlen("containIn")));
            return $this->prepareFindWithCondition("`$field` LIKE ?", array("%".$arguments[0]."%"))->execute();
        }

        throw new Exception("function $name unknown in class " . get_class($this));
    }
}