<?php

namespace Test;

class Database {
	const DRIVER_MSSQL = 'mssql';
	const DRIVER_MYSQL = 'mysql';
	const DRIVER_CSV = 'csv';
	const DRIVER_SQLITE = 'sqplite';
	const DRIVER_ORACLE = 'oracle';
	
	public static function factory($str_sdn)
	{
	    $str_driver = parse_url($str_sdn, PHP_URL_SCHEME);
	    switch(strtolower($str_driver)){
	        case self::DRIVER_CSV:
	            return new Database\Csv(str_replace(sprintf('%s://', $str_driver), '', $str_sdn));
	            break;
	        default: 
	            trigger_error(sprintf('No driver support for "%s",  yet.', $str_driver), E_USER_ERROR);
	    }
	    return null;
	}
}