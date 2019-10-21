<?php

function AjustaTexto($string){

	$string = str_replace('\u00f3','ó',$string);
	$string = str_replace('\u00e3','ã',$string);
	$string = str_replace('\u00e9','é',$string);
	$string = str_replace('\u00ed','í',$string);
	
	return $string;

}