<?php
/*
* Author: Abdelrahman Mohamed
* Contact: < Abdo.Tasks@Gmail.Com , https://Github.com/abd0m0hamed >
* Project: GC (for Global Classes). 
* Description: Simple library to do some important tasks.
* License: Science not for Monopoly.
*/

class GCxml{
    
    public $xml_file;
    public $xml_hwnd;
    private static $xmlresult = "";
    
    /** Load XML File,
    * ParametersL
    * $xmlfile > Xml file to load, Ex: xml_file_path/xml_file_name.xml,
    * On Success: Load $xmlfile file to variable $this->xml_hwnd and Return true,
    * Of Failure: return false.
    */
    function load($xmlfile){
        $this->xml_file = $xmlfile;
        $this->xml_hwnd = new DomDocument();
        
        if($this->xml_hwnd->load($this->xml_file)){
            return true;
        } else {
            return false;
        }
    }
    
    /** Get Elements from XML to array,
    * Paramaters:
    * $tagname > Tag name as string
    * $attributes > Attributes list to be loaded from every element,
    * 
    * Result Ex: $xml_array[xml_id]= array("attribute1_name"=>"attribute1_value","attribute1_name"=>"attribute1_value","contents"=>"element value")
    * On Success: Return array of elements
    * On Failure: Return False
    */
    function getelementsbytagname($tagname,$attributes){
        
        $xml_elements_array=array();
        $attributeslist = explode(",",$attributes);
        $xml_elements = $this->xml_hwnd->getElementsByTagName($tagname);
        
        if(!$xml_elements){
            return false;
        }
        
        foreach($xml_elements as $xml_element){
            
            $elementinfo["id"]=$xml_element->getAttribute("xml:id");
            $elementinfo["contents"]=$xml_element->nodeValue;
            
            //get Required Attrbutes
            if(!empty($attributes)){
                foreach($attributeslist as $attribute_key=>$attribute_name){
                    $elementinfo[$attribute_name]=$xml_element->getAttribute($attribute_name);
                }
            }
            
            //get Element to array
            $xml_elements_array[$elementinfo["id"]]=array();
            foreach($elementinfo as $element_key=>$element_value){
                $xml_elements_array[$elementinfo["id"]][$element_key]=$element_value;
            }
        
        }
        
        return $xml_elements_array;
    }
    
    /**
    * Get Element From  Xml file By ID
    * On Success: return element value
    * On Failure: Return false 
    */
    function get_element_by_id($element_id){
        
        $element_hwnd = $this->xml_hwnd->getElementById($element_id);
        if(!$element_hwnd){
            return false;
        }
        return $element_hwnd->nodeValue;
    }
    
    private static function Convert_Array_to_XML($array_to_convert) {
    
        foreach($array_to_convert as $k => $v){
            //$keyname = preg_match("/^[0-9]+$/", $k) ? "row" : $k;
            $keyname = $k;
            self::$xmlresult .= "<".$keyname.">";
            
            if(is_array($v)){
                self::Convert_Array_to_XML($v);
            } else {
                self::$xmlresult .= $v;
            }
            
            self::$xmlresult .= "</".$keyname.">";
        }
    }
    
    public static function Array_To_XML($array){
        //$array = array("parent1"=>array("child11","ch"=>"child12","child13"=>array("1","2","3","4"=>array("qwqwqwq","ewewew","ewewewew"=>array("rtttr","trtrtre","erwejkj")))));
        self::Convert_Array_to_XML($array);
        //header("Content-Type: text/xml; charset=utf-8");
        return self::$xmlresult;
    }
    
}

