<?php
include BASEPATH.'st/config/constants.php';

//SITE_DOMAIN - 도메인 이름.
define('SERVER_DOMAIN',$_SERVER["HTTP_HOST"]);

//SKIN, IMAGE, CSS 각 단기학교 폴더
define('SITE_DIR', 'default');
define('MSG_TITLE', '글로벌 1위의 근거 있는 자신감');

define('SKIN_ROOT', $_SERVER['DOCUMENT_ROOT'].'/skin/');
define('JS_ROOT',  SITE_DOMAIN.'/js/');
define('CSS_ROOT', SITE_DOMAIN.'/css/');
define('IMG_ROOT', SITE_DOMAIN.'/img/');

define('SKIN_PATH', $_SERVER['DOCUMENT_ROOT'].'/skin/default/');
define('JS_DIR',  SITE_DOMAIN.'/js/');
define('CSS_DIR', SITE_DOMAIN.'/css/');
define('DATA_DIR', SITE_DOMAIN.'/data/');
define('DATA_PATH', $_SERVER['DOCUMENT_ROOT']);
define('FILE_IMG_DIR', SITE_DOMAIN.'/data/file');   // 업로드 이미지 파일 경로


define('DANGI', 'global_dangicokr');
define('SITE_NAME', '미정');
define('BIZ_CODE', '미정');
define('ADM_F', 'adm'); // 관리자폴더

if(_IS_DEV || _IS_QA){
    define('IMG_DIR', '//qa-global.dangi.co.kr/img/');
    define('M_IMG_DIR', '//qa-global.dangi.co.kr/img/m');
    define('UPDATE_ROOT_PATH', $_SERVER["DOCUMENT_ROOT"]);
}
else{
    define('IMG_DIR', '//qa-global.dangi.co.kr/img/');
    define('M_IMG_DIR', SITE_DOMAIN.'/img/m/');
    define('UPDATE_ROOT_PATH', "/data/NFS/global_dangicokr");
}
