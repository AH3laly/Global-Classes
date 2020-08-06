<?php
/*
* Author: Abdelrahman Helaly
* Contact: < AH3laly@gmail.com , https://Github.com/AH3laly >
* Project: GC (for Global Classes). 
* Description: Simple library to do some important tasks.
* License: Science not for Monopoly.
*/

class GCcss {
        
    private static $attributes = array();
    
    
    private static function Attributes_Add($Attributes_Array = array()){
        foreach($Attributes_Array as $attr_Name => $attr_value){
            self::Attribute_Add($attr_Name,$attr_value);
        }
    }
    
    private static function Attribute_Add($Attribute_Name,$Attribute_Value){
        array_push(self::$attributes, $Attribute_Name.": ".$Attribute_Value,";");
    }
    
    private static function Group_Attributes(){
        return implode(" ",self::$attributes);
    }
    
    public static function File($contents){
        return $contents;
    }
    
    public static function Border_Radius($value){
        
        $attrs = array(
            "-moz-border-radius"=>$value,
            "-ms-border-radius"=>$value,
            "-o-border-radius"=>$value,
            "-webkit-border-radius"=>$value,
            "border-radius"=>$value
        );
        return self::Group_Attributes(self::Attributes_Add($attrs));
    }
    
    public static function padding($value){
        
        $attrs = array(
            "padding"=>$value
        );
        return self::Group_Attributes(self::Attributes_Add($attrs));
    }
    
        public static function line_height($value){
        
        $attrs = array(
            "line-height"=>$value
        );
        return self::Group_Attributes(self::Attributes_Add($attrs));
    }
    
    public static function Vertical_Align($value){
        
        $attrs = array(
            "vertical-align"=>$value
        );
        return self::Group_Attributes(self::Attributes_Add($attrs));
    }
    
    public static function text_Align($value){
        
        $attrs = array(
            "text-align"=>$value
        );
        return self::Group_Attributes(self::Attributes_Add($attrs));
    }
    
    public static function Object($object_Name,$elements_Array){
        return "$object_Name"."{".implode(" ", $elements_Array)."}";
    }
    
}

