<?php namespace Miraget\Base ;
/**
 * @package  Miraget Generator
 */ 
    class Deactivate
    {
        public static function deactivate() {
             flush_rewrite_rules();
        }
    }