<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.customcode.php
 * Type:     function
 * Name:     customcode
 * Purpose:  includes custom php code based on the page name
 * -------------------------------------------------------------
 */
function smarty_function_customcode($params, Smarty_Internal_Template $template)
{
    global $includes_dir,$the_page,$libraries_dir;
    if(file_exists("$includes_dir/$the_page.php")){
    	include "$includes_dir/$the_page.php";
    }
}
