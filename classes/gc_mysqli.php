<?php
/*
* Author: Abdelrahman Mohamed
* Contact: < Abdo.Tasks@Gmail.Com , https://Github.com/abd0m0hamed >
* Project: GC (for Global Classes). 
* Description: Simple library to do some important tasks.
* License: Science not for Monopoly.
*/

class GCmysqli{
    
    /** Mysqli Connection Resource,*/
    private $connection="";
    public $severity;
    public $query = "";
    
    /** Database Host name EX: www.hostname.com or server ip address like (192.168.1.200) */ 
    public $host;
    
    /** Database name */ 
    public $database;
    
    /** Database server User name */
    public $username;
    
    /** Database server Password */
    public $password;
    
    /**
    * Create Connection to database and set connection resource to $this->connection,
    * Notice: Variables $this->$host, $this->database, $this->username, $this->password should be set to proccess connection to database
    * On Success: set Mysql Connection Resource ID to variable $this->connection.
    * On failure: Call mothod $this->set_error();
    */
    function __construct(){
        /** Create Severity Object */
        $this->severity = new \stdClass();
    }
       
    public function connect($host = null,$username = null,$password = null,$database = null){
    
        $this->clear_error();
        $this->connection = mysqli_connect((!is_null($host)) ? $host : $this->host,(!is_null($username)) ? $username : $this->username,(!is_null($password)) ? $password : $this->password,(!is_null($database)) ? $database : $this->database);
        
        if(!$this->connection) {
            return $this->set_error(mysqli_connect_errno(),mysqli_connect_error());
        } else {
            return true;
        }
    
    }
    
    /**
    * Set Mysql Error Number to variable $this->error_number,
    * Set Mysql Error Message to variable $this->error_string,
    * Set variable $this->error to True, 
    * Return 'False' 
    */
    public function set_error($error_number = null,$error_string = null){
        $this->severity->type = "error";
        $this->severity->error = true;
        $this->severity->errornumber = $error_number;
        $this->severity->errordescription = $error_string;
        return false;
    }
    
    /** Clear last Error that set by function 'set_error()' */
    private function clear_error(){
        $this->severity->type = "";
        $this->severity->error = false;
        $this->severity->errornumber = "";
        $this->severity->errordescription = "";
    }

    /** Open Database ($database_to_open) or open database $this->database if $database_to_open parameter not set,
    * Notice: $this->database variable should be set or parameter $database_to_open should be passed
    * On Success: Return true and set Database Resource to variable '$this->connection',
    * On Failure: call method $this->set_error()
    */
    public function open_database($database_to_open = null){
        $this->clear_error();
        $db2open = is_null($database_to_open) ? $this->database : $database_to_open;
        if($this->connection->select_db($db2open)){return true;} else {return $this->set_error(mysqli_errno($this->connection),mysqli_error($this->connection));}
    }
    
    /** Get Rows from Table $tablename,
    * Parameters:
     * $tablename: Table name to operate with,
     * $columns: columns to get,
     * $joins: Join commands Ex: left join table1 on table1.field1 = table2.field2 left join table3 on table3.field3 = table1.field2.
     * $where: Condition will be executed if this condition occurred Ex: id='1' or name like '%Abdo Mohamed%',
     * $rows_limit: set limit for returned results, Ex: 0,10 to get ten results starting form row 0.
     * $rows_order: order returned results Ex1: id desc to order by id descending, Ex2: name asc to order by name ascending,
     * $idkey: column name to be set as array key.
     * 
     * Notice: with parameters $joins,$where use MySql common Syntax.
     * 
     * On Success: Return array of results
     * On Failure: call method $this->set_error();   
     */
    function get_rows($tablename,$columns,$joins = null,$where = null,$rows_limit = null,$rows_order = null,$idkey = null){
    
        $this->clear_error();
        
        //get Condetion
        (!is_null($where)) ? $condition = " where ".$where." " : $condition="";
        
        //set KEY Column
        if(is_null($idkey)){
            $idkey="0";
            $custom_id="no";
        }
        
        //set Columns array
        ($columns=="*") ? $columns=implode(",",$this->get_columns($tablename)) : null;
        
        //Prepare Join query
        $joins = is_null($joins) ? " ".$joins." " : null;
        $columnsarray = explode(",",$columns);
        
        //set limit
        $orderby = (!empty($rows_order)) ? " order by $rows_order" : null;
        
        //set Rows Limit
        $limit = (!empty($rows_limit)) ? " limit $rows_limit" :  null;
        
        //Process Keys Name (Remove As from Column Name to be set as key)
        foreach($columnsarray as $key => &$value){
            if(strstr($value," as ")){
                $newvalue = explode(" as ",$value);
                $columnsarray[$key]=$newvalue[1];
            }
        }
        
        //set Query String
        $querystring = "select $columns from $tablename".$joins.$condition.$orderby.$limit;
        $stmt = $this->execute_query($querystring);
        
        if(!$stmt){
            return $this->set_error(mysqli_errno($this->connection),mysqli_error($this->connection));
        }
        
        $rowsarray = array();
        
        while($result = $stmt->fetch_row()){
        
            //set single row array
            foreach($columnsarray as $columnid=>$columnname){
                $rowitem[$columnname]=$result[$columnid];
            }
            
            if(isset($custom_id) && $custom_id=="no"){
                array_push($rowsarray, $rowitem);
            } else {
                $array_key = $rowitem[$idkey];
                $rowsarray[$array_key]=$rowitem;
            }
        }
        
        return $rowsarray;
    }
    
     /** Get columns list to array,
     * Parameters:
     * $tablename > Table name to operate with>
     * On Success: return array of columns,
     * On Failure: return false.  
     */
    function get_columns($tablename){
    
        $this->clear_error();
        $columnsarray = array();
        $querystring = "select * from $tablename limit 1";
        $stmt = $this->execute_query($querystring);
        
        if(!$stmt){
            return $this->set_error(mysqli_errno($this->connection),mysqli_error($this->connection));
        }
        
        while($results = $stmt->fetch_field()){
            array_push($columnsarray,$results->name);
        }
        
        return $columnsarray;
    }
    
     /** Delete Rows from table,
     * Parameters:
     * $tablename > that table witch you want to delete rows from,
     * $condetion > use mysql Query type just like this (column1='value1' and column2='value2' or column3='value3'),
     * On Success: Return true.
     * On Failure: Return false.  
     */
    function delete_rows($tablename,$condition){
        $this->clear_error();
        if(!empty($condition)){
            $condition=" where $condition";
        } else {
            $condition="";
        }
        $querystring = "delete from $tablename".$condition;
        return (!$this->execute_query($querystring)) ? $this->set_error(mysqli_errno($this->connection),mysqli_error($this->connection)) : true;
    }
    
     /** Get Rows Count
     * Parameters:
     * $tablename > Name of table to operate with,
     * $column > Column name to be counted,
     * $condition > Contition for count operation,, Use Mysql Syntax,, Ex: id='1' and id in ('9','10','5')  ,
     * On success: returns number (rows count),
     * On failure: call $this->set_error() method.
     */
    function get_rowscount($tablename,$column,$condition){
        $this->clear_error();
        if(!empty($condition)){
            $condition=" where $condition";
        } else{
            $condition="";
        }
        $querystring = "select count($column) from $tablename".$condition;
        $stmt = $this->execute_query($querystring);
        if(!$stmt){
            $this->set_error(mysqli_errno($this->connection),mysqli_error($this->connection));
        }
        $result = $stmt->fetch_row();
        return $result[0];
    }
    
    
     /** Insert row to specified table,
     * Paramters:
     * $tablename > Table to operate with,
     * $columnsarray > array of columns to insert,
     * $valuesarray > array of values to insert
     * Notice: $columnsarray length shoud be the same length of $valuesarray
     * On success: return True
     * On failure: Call method set_error().
     */
    function insert_row($tablename,$columnsarray,$valuesarray){
        $this->clear_error();
        $columns = implode(",",$columnsarray);
        $values = "\"".implode("\",\"",$valuesarray)."\"";
        //$values = "'".str_replace(",","','",$values)."'";
        $querystring = "insert into $tablename ($columns) values ($values)";
        if($this->execute_query("$querystring")){
            return true;
        } else {
            return $this->set_error(mysqli_errno($this->connection),mysqli_error($this->connection));
        }
    }
    
    /** Update rows to specified table,
    * Paramters:
    * $tablename > Table to be updated,
    * $columnsarray > array of columns to insert,
    * $valuesarray > array of values to insert,
    * $condition > Update rows only if condition occurred,
    * Notice: $columnsarray length shoud be the same length of $valuesarray. 
    * Notice: for $condition use mysql syntax EX: id='1' or id='8'.
    * On success: return True.
    * On failure: call method set_error().
    */
    function update_rows($tablename,$columnsarray,$valuesarray,$condition){
        $this->clear_error();
        $itemstoupdate = array();
        
        foreach($columnsarray as $itemkey => $columnname){
            array_push($itemstoupdate,"$columnname=\"".$valuesarray[$itemkey]."\"");
        }
        
        $itemstoupdate = implode(",",$itemstoupdate);
        if(!empty($condition)){
            $condition=" where ".$condition;
        } else {
            $condition="";
        }
        
        $querystring = "update `$tablename` set ".$itemstoupdate.$condition;
        $stmt = $this->execute_query("$querystring");
        return (!$stmt) ? $this->set_error(mysqli_errno($this->connection),mysqli_error($this->connection)) : true;
    }
    /** Get Last Insert ID
    * No Parameters    
    * On Success: returns number refers to last inserted id.
    * On Failure: Call method $this->set_error(). 
    */
    function get_last_insert_id(){
        $this->clear_error();
        $stmt = $this->execute_query("select last_insert_id()");
        
        if(!$stmt){
            return $this->set_error(mysqli_errno($this->connection),mysqli_error($this->connection));
        }
        
        $result = $stmt->fetch_row();
        return $result[0];
    }
    
    /** Check if rows exitsts in table $table when condition $condition occurs,
    * Parameters:
    * $tablename > table name to operate with,
    * $condition > rows will be checked if condition '$condition' occurred ,
    * Notice: in $condition use MySql Common syntax Ex: id='1' and id='2' or id='9'.
    * On success: return true
    * On failure: return false
    */
    function check_rows($tablename,$condition){
        $this->clear_error();
        !empty($condition) ? $condition=" where $condition" : $condition="";
        $querystring = "select count(id) from $tablename".$condition." limit 1";
        $stmt = $this->execute_query($querystring);
        if(!$stmt){
            return $this->set_error(mysqli_errno($this->connection),mysqli_error($this->connection));
        }
        $result = $stmt->fetch_row();
        if($result[0]>0){
            return True;
        } else {
            return False;
        }
    }
    
    /**
    * Create Table in selected database,
    * Parameters:
    * $tablename -> Table name to be created,
    * $columns -> Array of columns, use Mysql Common Syntax, Ex: array("id int not null Primary key auto_increment","column1 varchar(255) not null default '0'"),
    */
    function create_table($tablename,$columns_array){
        $this->clear_error();
        $columns = implode(",",$columns_array);
        $querystring = "create table $tablename ($columns)";
        $stmt = $this->execute_query($querystring);
        return (!$stmt) ? $this->set_error(mysqli_errno($this->connection),mysqli_error($this->connection)) : true;
    }
    
    /** Drop table '$tablename' from selected database
    * Parameters:
    * $tablename -> table name to be dropped :D
    * On success:  return True.
    * On Failure: return false.
    */
    function delete_table($tablename){
        $this->clear_error();
        $querystring = "drop table $tablename";
        if($this->execute_query($querystring)){
            return true;
        } else {
            return $this->set_error(mysqli_errno($this->connection),mysqli_error($this->connection));
        }
    }
    
     /** Execute Query String,
     * Parameters:
     * $query_string > Mysql String to execute directly,
     * On Success: Return Database Resource ID
     * On Failure: call method $this->set_error()
     */
    function execute_query($query_string,$Return_Type = "resource",$Key_Field = "ID"){
        $this->clear_error();
        $this->query = "$query_string";
        $query_string = stripslashes($query_string);
        //$query_string = mysqli_real_escape_string($this->connection,$query_string);
        $stmt = $this->connection->query($query_string) or die(mysqli_error($this->connection));
        if(!$stmt) {
            return $this->set_error(mysqli_errno($this->connection),mysqli_error($this->connection));
        }
        
        if($Return_Type=="array"){
            return $this->Convert_Result_To_Array($stmt,$Key_Field);
            
        }else if($Return_Type=="object"){
            return $this->Convert_Result_To_Object($stmt,$Key_Field);
            
        }else{
            return $stmt;
        }
    }
    
    /**
    * Convert MySQLi Query Result Object To Array
    * @param type $Result_Object
    * @param type $Columnt_ID
    * @return type 
    */
    public function Convert_Result_To_Array($Result_Object,$Columnt_ID = "ID"){
    
        $Result_Array = array();
        while($row = mysqli_fetch_assoc($Result_Object)){
            isset($row[$Columnt_ID]) ? $Result_Array[$row[$Columnt_ID]] = $row : array_push($Result_Array, $row);
        }
        
        return $Result_Array;
    }
    
    /**
    * Convert MySQLi Query Result Resource To Object
    * @param type $Result_Object
    * @param type $Columnt_ID
    * @return type 
    */
    public function Convert_Result_To_Object($Result_Object,$Columnt_ID = "ID"){
        $Result_Array = array();
        while($row = mysqli_fetch_assoc($Result_Object)){
            
            $rowObject = new stdClass();
            foreach ($row as $k=>$v) {
                $rowObject->$k = $v;
            }
            
            isset($row[$Columnt_ID]) ? $Result_Array[$row[$Columnt_ID]] = $rowObject : array_push($Result_Array, $rowObject);
        }
        return $Result_Array;
    }
    
    
    /**
    * Get Max item of $field_name from table $table_name
    * Parameters: 
    * $tablename > {string} name of table to operate with,
    * $field_name > {string} the field name witch max proccess will operate with,
    * $condition > {MySql string} Specify condition for lookup proccess
    * 
    * Notice for parameter $condition, Use common MySql Syntax Ex: id='1' or id='8'
    * On Success: Return number refers to max vslue in $field_name
    * On Failure: Call $this->set_error();
    */
    function get_max($table_name,$field_name,$condition = null){
    
        $this->clear_error();
        $where = (!is_null($condition)) ?  "where $condition" : "";
        $querystring = "select max($field_name) from `$tablename` where $condition";
        $stmt = $this->execute_query($querystring);
        
        if(!$stmt){
            return $this->set_error(mysql_errno($this->connection),mysqli_error($this->connection));
        }
        
        $result = $stmt->fetch_row();
        
        return $result[0];
    }
    
    /**
    * Get Minimal value in $field_name from table $table_name
    * Parameters: 
    * $tablename > {string} name of table to operate with,
    * $field_name > {string} the field name witch min proccess will operate with,
    * $condition > {MySql string} Specify condition for lookup proccess
    * 
    * Notice for parameter $condition, Use common MySql Syntax Ex: id='1' or id='8'
    * On Success: Return number refers to min vslue in $field_name
    * On Failure: Call $this->set_error();
    */  
    function get_min($table_name,$field_name,$condition = null){
    
        $this->clear_error();
        $where = (!is_null($condition)) ?  "where $condition" : "";
        $querystring = "select min($field_name) from `$tablename` where $condition";
        $stmt = $this->execute_query($querystring);
        
        if(!$stmt){
            return $this->set_error(mysql_errno($this->connection),mysqli_error($this->connection));
        }
        
        $result = $stmt->fetch_row();
        return $result[0];
    }
    /**
    * Get Tables List from current database into Single Dimension Array,
    * On Success: Return array of tables.
    * On Failure: Call $this->set_error();
    */
    function get_tables(){
        $this->clear_error();
        $tables_array = array();
        $stmt = $this->execute_query("show tables");
        
        if(!$stmt){
            return $this->set_error(mysql_errno($this->connection),mysqli_error($this->connection));
        }
        
        while($row = mysqli_fetch_row($stmt)){
            $tables_array[$row[0]] = $row[0];
        }
        
        return $tables_array;
    }
}

