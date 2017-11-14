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

// 라이브 | QA 분기 설정
if(_IS_DEV || _IS_QA){
    define('IMG_DIR', '//qa-global.dangi.co.kr/img/');
    define('M_IMG_DIR', '//qa-global.dangi.co.kr/img/');
    define('UPDATE_ROOT_PATH', $_SERVER["DOCUMENT_ROOT"]);

    // AWS S3정보
    define('AWS_S3_ACCESS_KEY', 'AKIAIR2WUDODEMERF3IQ');
    define('AWS_S3_ACCESS_SECRET_KEY', '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');
	define('AWS_S3_HOST_PATH', 'https://s3.ap-northeast-2.amazonaws.com/st.dev.dangidata/global_dangicokr');
	define('AWS_S3_BUCKET', 'st.dev.dangidata');
    // define('AWS_S3_REGION', 'ap-northeast-2');
}
else{
    define('IMG_DIR', '//qa-global.dangi.co.kr/img/');
    define('M_IMG_DIR', SITE_DOMAIN.'/img/m/');
    define('UPDATE_ROOT_PATH', "/data/NFS/global_dangicokr");

    // AWS S3정보
    define('AWS_S3_ACCESS_KEY', 'AKIAIR2WUDODEMERF3IQ');
    define('AWS_S3_ACCESS_SECRET_KEY', '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');
    define('AWS_S3_HOST_PATH', 'https://s3.ap-northeast-2.amazonaws.com/st.dangidata/global_dangicokr');
    define('AWS_S3_BUCKET', 'st.dangidata');
    // define('AWS_S3_REGION', 'ap-northeast-2');
}
