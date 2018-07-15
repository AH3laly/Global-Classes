<?php
/*
* Author: Abdelrahman Mohamed
* Contact: < Abdo.Tasks@Gmail.Com , https://Github.com/abd0m0hamed >
* Project: GC (for Global Classes). 
* Description: Simple library to do some important tasks.
* License: Science not for Monopoly.
*/

class GChtmlController{
    
    private static $UlHTMLCode = "";
    private static $ClassCounter = 0;
    private static $ElementsInfoArray = array();
    private static $LiName_key = null;
    private static $LiValue_key = null;
    private static $HeadElementCssClass = null;
    private static $SelectedElementID = null;
    private static $LiDomainName = "";
    private static $SectionDir = null;
    
    public static function Selectbox_options_from_array($Values_Array,$Names_Array,$Selected_Value = null,$selection_item = null,$Empty_Element = false){
        
        if((count($Values_Array)) != count($Names_Array)) {
            return false;
        }
        
        $Values_Array = array_values($Values_Array);
        $Names_Array = array_values($Names_Array);
        
        $html_code = "";
        if($Empty_Element){
            $option = new htmlnew("option");
            $option->Content_Append($Empty_Element);
            $option->Attribute_Add("value","");
            $html_code.=$option->Get_HTML();
        }
        
        foreach($Values_Array as $k => $v){
            $option = new htmlnew("option");
            $option->Content_set($Names_Array[$k]);
            $option->Attribute_Add("value",$v);
            
            if(!is_null($Selected_Value)){
                (is_null($selection_item)) ? (($v==$Selected_Value) ? $option->Attribute_Add("selected","selected") : null) : (($Names_Array[$k]==$Selected_Value) ? $option->Attribute_Add("selected","selected") : null);
            }
            
            $html_code.=$option->Get_HTML();
        }
        return $html_code;
    }

    private static function ConvertArrayToUL($TreeArray){
    
        $IsFirtsElement = true;
        
        foreach($TreeArray as $k => $v){
        
            if($IsFirtsElement){
                self::$UlHTMLCode.="<ul>";
                self::$ClassCounter++;
            }
            
            $IsFirtsElement = false;
            
            if(is_array($v)){
                
                $ElementTitle = (!is_null(self::$LiName_key)) ? self::$ElementsInfoArray[$k][self::$LiName_key] : $k;
                $ElementHeadCssClass = self::$HeadElementCssClass;
                $ElementURL = self::$LiDomainName."/".self::$SectionDir.shell::$Caregories->paths[$k]["path"];
                $SelectedElement = (self::$SelectedElementID == $k) ? "selected" : "";
                $ElementHTML = new GChtml(GChtml::HTML_A);
                $ElementHTML->Content_Append($ElementTitle);
                $ElementHTML->Attribute_Add("href",$ElementURL);
                //self::$UlHTMLCode.="<li id='li".self::$ClassCounter."'>".$ElementHTML->Get_HTML()."</li>";
                self::$UlHTMLCode.="<li class='{$SelectedElement} {$ElementHeadCssClass} ulparent'>".$ElementHTML->Get_HTML()."</li>";
                //self::$UlHTMLCode.="<li class='{$ElementHeadCssClass}' id='ulparent'>".$ElementTitle."</li>";
                self::ConvertArrayToUL($v);
                
            } else {
            
                $ElementTitle = (!is_null(self::$LiName_key)) ? self::$ElementsInfoArray[$v][self::$LiName_key] : $v;
                $ElementURL = self::$LiDomainName."/".self::$SectionDir.shell::$Caregories->paths[$v]["path"];
                $SelectedElement = (self::$SelectedElementID == $v) ? "selected" : "";
                $ElementHTML = new GChtml(GChtml::HTML_A);
                $ElementHTML->Content_Append($ElementTitle);
                $ElementHTML->Attribute_Add("href",$ElementURL);
                self::$UlHTMLCode.="<li class='{$SelectedElement}'>".$ElementHTML->Get_HTML()."</li>";
            }
        
        }
        
        self::$UlHTMLCode.="</ul>";
    }
    
    public static function ULFromArray($TreeArray,$LiName_key = null,$LiValue_key = null,$DataArray = null,$HeadElementCssClass = null,$SelectedElementID = null,$DomainName = "",$SectionDir = null){
    
        self::$ElementsInfoArray = $DataArray;
        self::$LiName_key = $LiName_key;
        self::$LiValue_key = $LiValue_key;
        self::$HeadElementCssClass = $HeadElementCssClass;
        self::$LiDomainName = $DomainName;
        self::$SelectedElementID = $SelectedElementID;
        self::$SectionDir = $SectionDir;
        self::ConvertArrayToUL($TreeArray);
        return self::$UlHTMLCode;
    
    }

}
?>
