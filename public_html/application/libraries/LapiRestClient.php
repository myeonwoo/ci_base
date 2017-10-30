<?php
/* 
if(file_exists("../../../codeigniter/st/libraries/lapi/LapiRestClient.php"))
	include("../../../codeigniter/st/libraries/lapi/LapiRestClient.php");
else
	include("../../codeigniter/st/libraries/lapi/LapiRestClient.php");
*/
// 2015.08.31 김진환 수정. 경로변경. 찬샘님이 요청
if(file_exists("../../../codeigniter/st/libraries/bbs/LapiRestClient.php"))
	include("../../../codeigniter/st/libraries/bbs/LapiRestClient.php");
else
	include("../../codeigniter/st/libraries/bbs/LapiRestClient.php");	
?>
