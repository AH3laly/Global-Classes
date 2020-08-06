<?php
/*
* Author: Abdelrahman Helaly
* Contact: < AH3laly@gmail.com , https://Github.com/AH3laly >
* Project: GC (for Global Classes). 
* Description: Simple library to do some important tasks.
* License: Science not for Monopoly.
*/
   
//File System Class
class GCfilesystem{
    
    public $error = false;
    public $error_number = "";
    public $error_message = "";
    
    /** Clear Error Error */
    private function clear_error(){
        $this->error = false;
        $this->error_number = "";
        $this->error_message = "";
    }
    
    /** Trigger Errror and Reture with false */
    private function trigger_error(){
        $last_error = error_get_last();
        $this->error = true;
        $this->error_number = $last_error["number"];
        $this->error_message = $last_error["message"];
        
    }
    
    /** Get Directory Contents
     * Parameters:
     * $directory_path > {string} for Directory path
     * On success: return array of contents.
     * On Failure: Call method $this->trigger_error();
    */
    function directory_get_contents($directory_path){
        $this->clear_error();
        $directory_contents["directories"] = array();
        $directory_contents["files"] = array();
        ($curdir = opendir($directory_path)) ? null : $this->trigger_error();
        
        while($dir_content = readdir($curdir)){
            if($dir_content!=".." && $dir_content!="."){
            
                $full_path = $directory_path."/".$dir_content;
                $filesize = filesize($full_path);
                $filesize = ($filesize>1024) ? round(($filesize/1024),1)." KB" : $filesize." Bytes";
                
                if(is_dir($full_path)){
                
                    $directory_contents["directories"][$dir_content]["name"]=$dir_content;
                    $directory_contents["directories"][$dir_content]["type"]="directory";
                    $directory_contents["directories"][$dir_content]["size"]=$filesize;
                    
                }else{
                
                    $directory_contents["files"][$dir_content]["name"]=$dir_content;
                    $directory_contents["files"][$dir_content]["type"]="file";
                    $directory_contents["files"][$dir_content]["size"]=$filesize;
                }
            }
        }
        return $directory_contents;
    }
    
    function return_result($value_to_return){
        return $value_to_return;
    }
    
    /** Delete file from server,
    * Parameters:
    * $file_path > Fill path for file to be deleted
    * On success: return true,
    * On Failure: set arror information and return false
    */
    function file_delete($file_path){
        $this->clear_error();
        unlink($file_path) ? $this->return_result(true) : $this->trigger_error();
    }
    
    /** Remove Directory,
     * Parameters:
     * $directory_path > {string} string path for directory to be removed.
     * On Success: return true.
     * On Failure: set error information using $this->trigger_error() and returen false.
     */
    function directory_remove($direcroty_path){
        $this->clear_error();
        rmdir($direcroty_path) ? $this->return_result(true) : $this->trigger_error();
    }
    
    /** Create Directory,
    * Paramters:
    * $directory_path > path to be created
    * On Success: return true.
    * On failure: set error information using method $this->set_error() and return false.
    */
    function directory_create($directory_path){
        $this->clear_error();
            mkdir($directory_path) ? $this->return_result(true) : $this->trigger_error();
    }
    
    /** Create file or append data to existing file
    * Paramters:
    * @parameter $file_path > {string} full path for file to be created,
    * @parameter $file_contents > {string} data to be writtin into file,
    * @parameter $append_data > will append data into file if true otherwise the file will be created or overwritten.
    * On success: return true
    * On Failure: set error using method $this->trigger_error() and return false
    */
    function file_create($file_path,$file_contents,$append_data = false){
        $this->clear_error();
        $flag_type = ($append_data) ? FILE_APPEND : false;
        file_put_contents($file_path,$file_contents,$flag_type) ? $this->return_result(true) : $this->trigger_error();
    }
    
    function file_upload($filearray){
    
    }
    
    /** Copy source file to destination path
    * Paramters:
    * $source_file > {string} full path of source file,
    * $destination_file > {string} full path of destination file.
    * On Success: return true.
    * On failure: set error information using method $this->trigger_error() and return woith false.
    */
    function file_copy($source_file,$destination_file){
        copy($source_file,$destination_file) ? $this->return_result(true) : $this->trigger_error();
    }
    
    /**
    * Get File Extension
    * Parameters:
    * $filename > {string} string of file name.
    */
    function file_get_extension($filename){
        return strchr($filename,".");
    }
}

