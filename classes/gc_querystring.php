<?php
/*
* Author: Abdelrahman Helaly
* Contact: < AH3laly@gmail.com , https://Github.com/AH3laly >
* Project: GC (for Global Classes). 
* Description: Simple library to do some important tasks.
* License: Science not for Monopoly.
*/

class GCQueryString {
    
    const Query_Type_Select = "Select";
    const Query_Type_Update = "Update";
    const Query_Type_Delete = "Delete";
    const Query_Type_Drop = "Drop";
    const Query_Type_Insert = "Insert";
    
    private $operation = "";
    private $columns = array();
    private $tables = array();
    private $Where = array();
    private $conditions = array();
    private $limit = "";
    private $order = array();
    private $joins = array();
    private $join_Conditions = array();
    private $Values = array();
    
    
    public function __construct($Query_Type) {
        $this->operation = $Query_Type;
    }
    
    /**
    * Add Column or Array of Columns to Current Query
    * @param type $column_Name
    * @param type $display_as 
    */
    public function Add_Column($columns){
        if(is_array($columns)){
            foreach($columns as $ColumnName){
                array_push($this->columns, $ColumnName);
            }
        } else {
            array_push($this->columns, $columns);
        }
        return $this->Get_Columns();
    }
    
    public function Add_Where($operator,$condition){
        $Where = $operator." (".$condition.")";
        array_push($this->Where, $Where);
        return $Where;
    }
    
    public function Add_Condition($First_value,$Operator,$Second_Value){
        $Condition = "$First_value"." ".$Operator." ".$Second_Value;
        array_push($this->conditions,$Condition);
        return $Condition;
    }
    
    public function Add_Condition_Between($Value_To_Check,$Min_Value,$Max_Value){
        $Condition = $Value_To_Check." between ".$Min_Value." and ".$Max_Value;
        array_push($this->conditions,$Condition);
        return $Condition;
    }
    
    public function Add_Condition_UpperThan($Value_To_Check,$Max_Value){
        $Condition = $Value_To_Check." > ".$Max_Value;
        array_push($this->conditions,$Condition);
        return $Condition;
    }
    
    public function Add_Condition_LowerThan($Value_To_Check,$Min_Value){
        $Condition = $Value_To_Check." < ".$Min_Value;
        array_push($this->conditions,$Condition);
        return $Condition;
    }
    
    public function Add_Condition_In($Value_To_Check,$Values){
        $in_Values = is_array($Values) ? "'".implode("','",$Values)."'" : $Values;
        $Condition = $Value_To_Check." in (".$in_Values.")";
        array_push($this->conditions,$Condition);
        return $Condition;
    }
    
    /**
    * Add Table Or Array Of Tables
    * @param type $Table_Name
    * @return type 
    */
    public function Add_Table($Table_Name){
        if(is_array($Table_Name)){
            foreach($Table_Name as $TableName){
                array_push($this->tables, $TableName);
            }
        } else {
            array_push($this->tables, $Table_Name);
        }
        return $this->Get_Tables();
    }
    
    public function Add_Order($Order_Column,$Order_Type = "ASC"){
        array_push($this->order, $Order_Column." ".$Order_Type);
    }
    
    
    public function Add_Join($Join_Type,$Table_Name,$Join_Condition){
        $Join = $Join_Type." join ".$Table_Name." on ".$Join_Condition;
        array_push($this->joins, $Join);
        return $Join;
    }
    
    public function Add_Join_Condition($Operator_Type,$First_Field,$Join_Operator,$Second_Field){
        return $Operator_Type." ".$First_Field." ".$Join_Operator." ".$Second_Field;
    }
    
    public function Add_Values(array $Values_Array){
        foreach($Values_Array as $Field_Name => $Field_Value){
            $this->Values[$Field_Name] = $Field_Value;
        }
        return $this->Values;
    }
    
    public function Get_Columns(){
        return implode(",", $this->columns)." ";
    }
    
    public function Get_Tables(){
        return implode(",", $this->tables)." ";
    }
    
    public function Get_Conditions(){
        return $this->conditions;
    }
    
    public function Get_Where(){
        return (count($this->Where)>0) ? "Where ".implode(" ", $this->Where) : "";
    }
    
    public function Get_Order(){
        return (count($this->order)>0) ? " order By ".implode(" ", $this->order) : "";
    }
    
    public function Get_Limit(){
        return $this->limit;
    }
    
    public function Get_Joins(){
        return implode(" ", $this->joins)." ";
    }
    
    public function Set_Limit($Starting_Point,$Rows_Count){
        $Starting_Point .= ($Starting_Point!=NULL) ? " , " : "";
        $this->limit = " Limit ".$Starting_Point.$Rows_Count." ";
    }
    
    private function Build_Insert_Values(){
        $Fields = array_keys($this->Values);
        $Values = array_values($this->Values);
        
        foreach($Values as &$Field_Value){
            $Field_Value = addslashes($Field_Value);
        }
        
        $insert_Values = "(".implode(",",$Fields).")"." values ('".  implode("','", $Values)."')";
        return $insert_Values;
    }
    
    private function Build_Update_Values(){
        $update_Values = array();
        
        foreach($this->Values as $Field_Name => $Field_Value){
            array_push($update_Values,"$Field_Name = '".addslashes($Field_Value)."'");
        }
        
        return implode(" , ", $update_Values)." ";
    }
    
    public function Get_Query(){
        return $this->{"Get_".$this->operation."_Query"}();
    }
    
    public function Get_Operation(){
        return $this->operation." ";
    }
    
    public function Get_Select_Query(){
        return $this->Get_Operation().$this->Get_Columns()."From ".$this->Get_Tables().$this->Get_Joins().$this->Get_Where().$this->Get_Order().$this->Get_Limit();
    }
    
    public function Get_Delete_Query(){
        return $this->Get_Operation()."From ".$this->Get_Tables().$this->Get_Where().$this->Get_Limit();
    }
    
    public function Get_Insert_Query(){
        return $this->Get_Operation()."Into ".$this->Get_Tables().$this->Build_Insert_Values();
    }
    
    public function Get_Update_Query(){
        return $this->Get_Operation().$this->Get_Tables()."Set ".$this->Build_Update_Values().$this->Get_Where().$this->Get_Limit();
    }
    
}

