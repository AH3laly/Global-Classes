<?php
/*
* Author: Abdelrahman Mohamed
* Contact: < Abdo.Tasks@Gmail.Com , https://Github.com/abd0m0hamed >
* Project: GC (for Global Classes). 
* Description: Simple library to do some important tasks.
* License: Science not for Monopoly.
*/

class GCDataStructure {
    
    private static $Tree;
    private static $Tree_rows_childs_path = array();
    private static $Tree_rows_parents_path = array();
    private static $Tree_rows_grandsons = array();
    private static $Tree_columns = array();
    private static $Tree_Parents = array();
    private static $Tree_Childs = array();
    
    private static $paths_keyname,$paths_elements_array;
    
    private static function Tree_Get_Childs($Element_ID){
        $Element_Childs = array();
        foreach (self::$Tree_Childs as $key => $ChildID){
            (self::$Tree_Parents[$key]==$Element_ID) ? $Element_Childs[$ChildID] = $ChildID : null;
        }
        return $Element_Childs;
    }
    
    //Get Elements that has no Parents (It's Parent ID is 0)
    private static function Tree_Get_Roots(){
        $Roots_Array = array();
        foreach(self::$Tree_Parents as $key => $Parent){
            $parentID = self::$Tree_Childs[$key];
            $Parent=="0" ? $Roots_Array[$parentID] = $parentID : null;
        }
        return $Roots_Array;
    }
    
    private static function Tree_Generate_Childs(&$Parents_Array,$parentpath = null){
        foreach($Parents_Array as $k => &$ChildID){
        $Element_Childs = self::Tree_Get_Childs($ChildID);
        array_push(self::$Tree_rows_grandsons, $k);
        !is_null($parentpath) ? $elementparent=$parentpath."/".$ChildID : $elementparent=$ChildID;
            //$elementparent=$parentpath."/".$ChildID;
            if(is_array($Element_Childs) && (count($Element_Childs) > 0)){
                self::$Tree_rows_parents_path[$ChildID]=$elementparent;
                self::$Tree_rows_childs_path[$ChildID]=implode("/",$Element_Childs);
                $ChildID = $Element_Childs;
                self::Tree_Generate_Childs($ChildID,$elementparent);
            }else{
                self::$Tree_rows_childs_path[$ChildID]=$ChildID;
                self::$Tree_rows_parents_path[$ChildID]=$elementparent;
            }
            array_push(self::$Tree_rows_grandsons, $k);
        }
    }
    
    private static function Tree_Generate(){
        $Tree = self::Tree_Get_Roots();
        self::Tree_Generate_Childs($Tree);
        return $Tree;
    }
    
    public static function Get_Grandsons($Element_ID){
    
        $doit = array_keys(self::$Tree->grandsons, $Element_ID);
        //print_r(self::$Tree->grandsons);
        //exit;
        $sliceLength = ($doit[1]-$doit[0]);
        return array_slice(self::$Tree->grandsons,$doit[0], $sliceLength);
    }
    
    /**
     * Create Tree From two Parents and Childs Arrays
     * @param type $Parents_Array
     * @param type $Childs_Array 
     * 
     * @Returen Array
     */
    public static function Tree_Create($Parents_Array,$Childs_Array){
        self::$Tree = new stdClass();
        self::$Tree_Parents = $Parents_Array;
        self::$Tree_Childs = $Childs_Array;
        self::$Tree->array = self::Tree_Generate();
        self::$Tree->childs_path = self::$Tree_rows_childs_path;
        self::$Tree->parents_path = self::$Tree_rows_parents_path;
        self::$Tree->grandsons = self::$Tree_rows_grandsons;
        return self::$Tree;
    }
    
    private static function translate_path($path){
        $path_array = explode("/",$path);
        $new_path = array();
        foreach($path_array as $path_element){
            if(trim($path_element)!=""){
              array_push($new_path,trim(self::$paths_elements_array[$path_element][self::$paths_keyname]));
            }
        }
        //Generate Path
        $readypath = "";
        foreach($new_path as $v){
            $readypath.=$v."/";
        }
        return $readypath;
    }
    
    public static function translate_paths($elements_array,$paths_array,$element_key_name){
        self::$paths_elements_array = $elements_array;
        self::$paths_keyname = $element_key_name;
        $new_paths_array = array();
        foreach($paths_array as $path_key => $path_value){
            $new_paths_array[$path_key]["IDPath"] = $path_value;
            $new_paths_array[$path_key]["path"]=self::translate_path($path_value);
            $new_paths_array[$path_key]["ID"]=$path_key;
        }
        return $new_paths_array;
    }
    
}

?>
