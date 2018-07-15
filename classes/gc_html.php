<?php
/*
* Author: Abdelrahman Mohamed
* Contact: < Abdo.Tasks@Gmail.Com , https://Github.com/abd0m0hamed >
* Project: GC (for Global Classes). 
* Description: Simple library to do some important tasks.
* License: Science not for Monopoly.
*/

class GChtml {
    
    //Available HTML Tags 
    const HTML_Doctype = "!Doctype";
    const HTML_HTML = "html";
    const HTML_Head = "head";
    const HTML_title = "title";
    const HTML_Script = "script";
    const HTML_Style = "style";
    const HTML_Link = "link";
    const HTML_Body = "body";
    const HTML_DIV =  "div";
    const HTML_FORM = "form";
    const HTML_IMG = "img";
    const HTML_INPUT = "input";
    const HTML_OPTION = "option";
    const HTML_P = "p";
    const HTML_RADIO = "radio";
    const HTML_SPAN = "span";
    const HTML_TEXTAREA = "textarea";
    const HTML_Select = "select";
    const HTML_A = "A";
    
    private $Single_Tags = array(self::HTML_IMG,self::HTML_INPUT,self::HTML_Link,self::HTML_Doctype);
    private $tag;
    
    public function __construct($HTLM_Object_Name) {
    
        $this->tag = new \stdClass();
        $this->tag->name = $HTLM_Object_Name;
        $this->tag->type = in_array($this->tag->name, $this->Single_Tags) ? "single" : "double";
        $this->tag->attributes = "";
    }
    
    public function Content_set($HTML_Code = ""){
        $this->tag->content = $HTML_Code;
    }
    
    public function Content_Append($HTML_Code = null){
        $this->tag->content .= $HTML_Code;
    }
    
    private function Attributes_To_HTML(){
    
        $this->tag->attributes = "";
        foreach($this->tag->attributes_array as $k => $v){
            $Attr_Value = ($v!=null) ? "=\"".addslashes($v)."\"" : "";
            $this->tag->attributes.=" ".$k.$Attr_Value;
        }
    
    }
    
    public function Attribute_Add($Attribute_Name,$Attribute_Value = null){
        $this->tag->attributes_array[$Attribute_Name] = $Attribute_Value;
        $this->Attributes_To_HTML();
    }
    
    public function Get_HTML(){
        
        if($this->tag->type=="single"){
        
            $this->tag->open = "<".$this->tag->name.$this->tag->attributes." />";
            $this->tag->close = "";
            $this->tag->code = $this->tag->open;
        
        } else {
        
            $this->tag->open = "<".$this->tag->name.$this->tag->attributes.">";
            $this->tag->close = "</".$this->tag->name.">";
            $this->tag->code = $this->tag->open.$this->tag->content.$this->tag->close;
        }
        
        return $this->tag->code;
    }
}

?>
