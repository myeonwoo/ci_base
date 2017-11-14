<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// 
function array_column($myarray, $column_name) {
	$namearray = array();

	foreach ($myarray as $item) {
		$namearray[] = $item[$column_name];
	}

	return $namearray;
}

function in_multiarray($elem, $array, $field)
{
    $top = sizeof($array) - 1;
    $bottom = 0;
    while($bottom <= $top)
    {
        if($array[$bottom][$field] == $elem)
            return true;
        else 
            if(is_array($array[$bottom][$field]))
                if(in_multiarray($elem, ($array[$bottom][$field])))
                    return true;

        $bottom++;
    }        
    return false;
}

function in_array_m($needle, $haystack, $field) {
    $found = false;
    foreach ($haystack as $item) {
    	if ($item[$field] === $needle) { 
            return true;
        }
    }
    return false;
}