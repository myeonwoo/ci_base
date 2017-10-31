<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Config for the CodeIgniter Redis library
 *
 * @see ../libraries/Redis.php
 */

// aws 이벤트 서버 사용
if ($_SERVER['HTTP_HOST'] == 'st-event-china.dangi.co.kr') {

	//2016-04-22, 서버 교체
	$config['redis_default']['host'] = '10.110.0.11';		// IP address or host
	$config['redis_default']['port'] = '6079';
	$config['redis_default']['password'] = '';
	
	//2016-04-22, 서버 교체
	$config['redis_slave']['host'] = '10.110.0.12';
	$config['redis_slave']['port'] = '6080';
	$config['redis_slave']['password'] = '';
	
} else {
	$config['redis_default']['host'] = (_IS_DEV_QA)? '61.255.238.197' : '61.255.238.216';		// IP address or host
	$config['redis_default']['port'] = '6379';			// Default Redis port is 6379
	$config['redis_default']['password'] = '';			// Can be left empty when the server does not require AUTH

	$config['redis_slave']['host'] = (_IS_DEV_QA)? '61.255.238.197' : '61.255.238.217';
	$config['redis_slave']['port'] = '6379';
	$config['redis_slave']['password'] = '';
}

