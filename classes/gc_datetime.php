<?php
/*
* Author: Abdelrahman Mohamed
* Contact: < Abdo.Tasks@Gmail.Com , https://Github.com/abd0m0hamed >
* Project: GC (for Global Classes). 
* Description: Simple library to do some important tasks.
* License: Science not for Monopoly.
*/

    class GCdate{
       
        public static $format = "";
        static $default_format = "Y-m-d";
        
        function get_timestamp(){
            return time();
        }
        
        public static function get(){
            return date((self::$format) ? self::$format : self::$default_format);
        }
        
    }

