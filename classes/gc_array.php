<?php
/*
* Author: Abdelrahman Mohamed
* Contact: < Abdo.Tasks@Gmail.Com , https://Github.com/abd0m0hamed >
* Project: GC (for Global Classes). 
* Description: Simple library to do some important tasks.
* License: Science not for Monopoly.
*/

class GCArray_MD{
    /**Get Mult-Dimensional Array Column to Single Array */
    public static function Column_Get($MD_Array,$Column_Name){
        $columns = array();
        foreach($MD_Array as $k=>$v){
            array_push($columns,$v[$Column_Name]);
        }
        return $columns;
    }
    
    public static function Search($Array,$Item_Name,$Item_Value,$limit = 1){
        $Result_Array = array();
        $array_limit = 0;
        foreach($Array as $Array_Key => $Array_Value){
            if(trim(strtolower($Array_Value[$Item_Name]))==trim(strtolower($Item_Value))){
                $Result_Array[$Array_Key]=$Array_Value;
                $array_limit++;
            }
            if($array_limit>=$limit) return $Result_Array;
        }
        return $Result_Array;
    }
    
    /**Get Mult-Dimensional Array Column to Single Array */
    public static function Slice_Array($MD_Array,$Columns_Array,$Resort_Keys = true){
        $columns = array();
        foreach($MD_Array as $k=>$v){
            //Will Return MultiDimentional Array
            foreach($Columns_Array as $ColName){
                $columns[$k][$ColName]=$v[$ColName];
            }
        }
        return ($Resort_Keys) ? array_values($columns) : $columns;
    }
    
    /**Get Mult-Dimensional Array Column to Single Array */
    public static function Column_Gets($MD_Array,$Columns_Array,$Resort_Keys = true){
        $columns = array();
        foreach($Columns_Array as $colname){
            $columns[$colname] =array();
            foreach($MD_Array as $ElementName){
                array_push($columns[$colname],$ElementName[$colname]);
            }
        }
        return $columns;
    }
}
?>
