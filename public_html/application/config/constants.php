<?php
include BASEPATH.'st/config/constants.php';

//SITE_DOMAIN - 도메인 이름.
define('SERVER_DOMAIN',$_SERVER["HTTP_HOST"]);

//SKIN, IMAGE, CSS 각 단기학교 폴더
define('SITE_DIR', 'chinadangi');
define('RT_PATH', '');

define('SKIN_ROOT', $_SERVER['DOCUMENT_ROOT'].'/skin/');
define('JS_ROOT',  SITE_DOMAIN.'/js/');
define('CSS_ROOT', SITE_DOMAIN.'/css/');
define('IMG_ROOT', SITE_DOMAIN.'/img/');

define('SKIN_PATH', $_SERVER['DOCUMENT_ROOT'].'/skin/default/');
define('JS_DIR',  SITE_DOMAIN.'/js/');
define('CSS_DIR', SITE_DOMAIN.'/css/default/');
define('DATA_DIR', SITE_DOMAIN.'/data/default/');
define('DATA_PATH', $_SERVER['DOCUMENT_ROOT']);
define('FILE_IMG_DIR', SITE_DOMAIN.'/data/file');   // 업로드 이미지 파일 경로


define('DANGI', 'eng_dangicokr');
define('SITE_NAME', '커넥츠 중단기');
define('BIZ_CODE', 'CHINADANGI');
define('ADMIN', 'admin'); // 최고관리자
define('ADM_F', 'adm'); // 관리자폴더

if(_IS_DEV || _IS_QA){ 
    define('IMG_DIR', '//qa-china.dangi.co.kr/img/');
    define('M_IMG_DIR', '//qa-china.dangi.co.kr/img/m');
    define('UPDATE_ROOT_PATH', $_SERVER["DOCUMENT_ROOT"]);
}
else{
    define('IMG_DIR', SITE_DOMAIN.'/img/');
    define('M_IMG_DIR', SITE_DOMAIN.'/img/m/');
    define('UPDATE_ROOT_PATH', "/data/NFS/china_dangicokr");
}
