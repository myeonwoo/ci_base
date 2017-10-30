<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['sSiteCode'] 	   	= "O889";																// NICE신용평가정보로부터 부여받은 사이트 코드
$config['sSitePassword'] 	= "24065793";															// NICE신용평가정보로부터 부여받은 사이트 패스워드
$config['sReturnURL']		= $config['base_url']."/memdangi/regist/cp_return";						// 결과 수신 : full URL 입력
$config['cb_encode_path'] 	= $_SERVER['DOCUMENT_ROOT'].'/'."data/namecheck/CPClient";					// NICE신용평가정보로부터 받은 암호화 프로그램의 위치 (절대경로+모듈명)_Linux ..
		
$config['sIPINSiteCode'] 	= "F653";																// NICE신용평가정보로부터 부여받은 아이핀사이트 코드
$config['sIPINPassword'] 	= "34171691";															// NICE신용평가정보로부터 부여받은 아이핀사이트 패스워드
$config['sIPINModulePath']	= $_SERVER['DOCUMENT_ROOT'].'/'."data/namecheck/IPINClient";				// 모듈 경로
$config['sIPINReturnURL']	= $config['base_url']."/memdangi/regist/ipin_return";					// 암호화된 결과 데이타를 리턴받으실 URL
$config['sIPINCPRequest']	= "";

$config['sPhoneSiteCode']		= "G3951";															// NICE신용평가정보로부터 부여받은 사이트 코드
$config['sPhoneSitePassword'] 	= "4WA4X7E22TK2";													// NICE신용평가정보로부터 부여받은 사이트 패스워드
$config['sPhoneReturnURL'] 		= $config['base_url']."/memdangi/regist/phone_return";			//결과 수신 : full URL 입력
$config['sPhoneErrorURL'] 		= $config['base_url']."/memdangi/regist/phone_return";			//결과 수신 : full URL 입력
$config['cb_encode_Phonepath'] 	= $_SERVER['DOCUMENT_ROOT'].'/'."data/namecheck/checkplus/CPClient";	// NICE신용평가정보로부터 받은 암호화 프로그램의 위치 (절대경로+모듈명)_Linux ..

$config['authtype'] 		= "M";
$config['popgubun'] 		= "N";
$config['customize'] 		= "";
		
$config['sClientImg'] 		= "";			//서비스 화면 로고 선택(full 도메인 입력): 사이즈 100*25(px)
$config['sReserved1'] 		= "";
$config['sReserved2'] 		= "";
$config['sReserved3'] 		= "";