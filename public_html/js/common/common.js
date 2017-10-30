//popup open
popupWins = new Array();
function open_win(url, name, width, height, xpos, ypos){
	name =  name || '_blank';
	xpos = xpos || (screen.availWidth-width)/2;
	ypos = ypos || (screen.availHeight-height)/2;

	if ( typeof( popupWins[name] ) != "object" ){
		popupWins[name] = window.open(url, name, 'width='+width+', height='+height+', left='+xpos+', top='+ypos+', menubar=no, status=no, toolbar=no, scrollbars=no, resizable=yes');
	 } else {
		 if (!popupWins[name].closed){
			popupWins[name].location.href = url;
		 } else {
			popupWins[name] = window.open(url, name, 'width='+width+', height='+height+', left='+xpos+', top='+ypos+', menubar=no, status=no, toolbar=no, scrollbars=no, resizable=yes');
		 }
	 }

	 popupWins[name].focus();
}

//input 의 limit attribute의 값을 초과할 수 없다.
function check_num_limmit(obj, msg){
	if(obj.val() == ''){
		return '';
	}
	else if(!check_num(obj)){
		alert('숫자만 입력 가능합니다.');
		return '';
	}
	else if(parseInt(obj.val()) > parseInt(obj.attr('limit'))){
		alert(msg.replace('{limit_val}', parseInt(obj.attr('limit'))));
		return parseInt(obj.attr('limit'))
	}
	else{
		return parseInt(obj.val())
	}
}

//숫자만 가능함
function check_num(obj){
	var num_check=/^[0-9]*$/;
	return num_check.test(obj.val());
}

//숫자형 commify
function commify(n) {
	var reg = /(^[+-]?\d+)(\d{3})/;   // 정규식
	n += '';                          // 숫자를 문자열로 변환

	while (reg.test(n))
		n = n.replace(reg, '$1' + ',' + '$2');

	return n;
}

//슬라이드 :: MY단기
function go_slide(prev_next, wrap_id, contents_width, direction, show_pagination){
	var wrap_obj = $('#' + wrap_id);
	var total_cnt = $('#'+wrap_id+' .slide_contents').length;
	var end_idx = parseInt(wrap_obj.attr('end_idx'));
	var size = parseInt(wrap_obj.attr('size'));
	var status = wrap_obj.attr('status');
	var result_prev_next = 0;
	var cur_page = Math.ceil(end_idx / size);
	var total_page = Math.ceil(total_cnt / size);

	if(status=='false'){ //next/prev
		var now_idx = end_idx;
		var next_idx = end_idx + (prev_next * size);

		if(prev_next > 0 && next_idx > total_cnt) next_idx = total_cnt;
		if(prev_next < 0 && next_idx <= size) next_idx = size;

		if( (prev_next > 0  && now_idx < next_idx) || (prev_next < 0  && now_idx > next_idx) ){

			wrap_obj.attr('status', 'true');
			if(direction == 'left'){
				wrap_obj.animate({ "margin-left": "-="+(contents_width * (next_idx - now_idx))+"px" }, contents_width*Math.abs(next_idx - now_idx), 'swing'
					, function(){
						wrap_obj.attr('status', 'false').attr('end_idx', next_idx);
					} 
				);	
			}
			else if(direction == 'top'){
				wrap_obj.animate({ "margin-top": "-="+(contents_width * (next_idx - now_idx))+"px" }, contents_width*Math.abs(next_idx - now_idx), 'swing'
					, function(){
						wrap_obj.attr('status', 'false').attr('end_idx', next_idx);
					} 
				);
			}
				
			result_prev_next = prev_next;;
		}

		if(show_pagination){ // wrap contents 의 부모 object 안에 small page pagination 표시
			var paging = $('<div class="smallpage"></dlv>');
			if((cur_page + result_prev_next) > 1)
				paging.append ('<a href="javascript:get_freepass_refund('+(cur_page+result_prev_next-1)+', \'\');"><img src="/img/engdangi/mydangi/btn/btn_prev_small.gif" alt="이전" class="prev"></a>');
			paging.append('<span class="on">'+(cur_page + result_prev_next)+'</span> / <span>'+total_page+'</span>');
			if((cur_page + result_prev_next) < total_page)
				paging.append('<a href="javascript:get_freepass_refund('+(cur_page+result_prev_next+1)+', \'\');"><img src="/img/engdangi/mydangi/btn/btn_next_small.gif" alt="다음" class="next"></a>');

			$(wrap_obj).parent().find('div.smallpage').html(paging);
		}
	}

	return result_prev_next;
}
/** 안씀 **/
//무료 플레이어 팝업
/*function free_lec_player(free_movie_kind, freemovie_id){

	var _checkDevServer = checkDevServer();
	var _player_domain = "";
	var url = "";

	if (_checkDevServer==1){
		_player_domain = "dev.myclass.dangi.co.kr";
	}else if (_checkDevServer==2){
		_player_domain = "qa-myclass.dangi.co.kr";
	}else{
		_player_domain = "myclass.dangi.co.kr";
	}

	if (free_movie_kind=="product"){
		 url = "http://" + _player_domain + "/player/free/common/"+ free_movie_kind + "?full_movie_url="+ freemovie_id;

	}else{
		url = "http://" + _player_domain + "/player/free/common/"+ free_movie_kind + "/"+ freemovie_id;

	}

	open_win(url, "lec_player", 1024, 788);

}

//무료 플레이어 아이프레임
// @param :   playbox - div id , free_movie_kind - 무료영상 종류, freemovie_id - 무료 영상 ID 또는 URL, autoplay - 자동 재생여부(Y: 자동 , N: 반자동)
//		playimg - 플레이어 첫이미지, width - iframe 넓이, height - iframe 높이,
// @example :  free_lec_player_iframe("#testdiv" , "freemovie", "111", "N", "http://...../sellingpoint2.jpg", 700, 400);
function free_lec_player_iframe(playbox,free_movie_kind, freemovie_id, autoplay, playimg, width, height){

	var _checkDevServer = checkDevServer();
	var _player_domain = "";
	var url = "";

	if (_checkDevServer==1){
		_player_domain = "dev.myclass.dangi.co.kr";
	}else if (_checkDevServer==2){
		_player_domain = "qa-myclass.dangi.co.kr";
	}else{
		_player_domain = "myclass.dangi.co.kr";
	}

	if (free_movie_kind=="product"){
		 url = "http://" + _player_domain + "/player/free/iframe/"+ free_movie_kind + "?full_movie_url="+ freemovie_id;

	}else{
		url = "http://" + _player_domain + "/player/free/iframe/"+ free_movie_kind + "/"+ freemovie_id;

	}

	$(playbox).html("<iframe width='"+width+"' height='"+height+"' src='"+url+"?&autoplay="+autoplay+"&playimg="+playimg+"&width="+width+"&height="+height+"' border='0' frameborder='0' scrolling='no'></iframe>");
}

//유료 플레이어 팝업
function lec_player(param){

	var _checkDevServer = checkDevServer();
	var _player_domain = "";

	if (_checkDevServer==1){
		_player_domain = "dev.myclass.dangi.co.kr";
	}else if (_checkDevServer==2){
		_player_domain = "qa-myclass.dangi.co.kr/";
	}else{
		_player_domain = "myclass.dangi.co.kr";
	}
  
	var url = "http://" + _player_domain + "/player/main/common/" + param;

	open_win(url, "lec_player", 1024, 788);

}
*/
//Domain Return (ex. test.com  Also engdangi.com)
function returnDomain(){
	var _domain =document.domain;
	var _domain_replace = /([a-z\d\-]+(?:\.(?:asia|info|name|mobi|com|net|org|biz|tel|xxx|kr|co|so|me|eu|cc|or|pe|ne|re|tv|jp|tw)){1,2})(?::\d{1,5})?(?:\/[^\?]*)?(?:\?.+)?$/i;
	var _result = _domain.replace(_domain_replace,"");

	_result = _domain.replace(_result,"");

	return _result;
}

//Dev QA Server Check (1 : DEV, 2: QA , 3: Live)
function checkDevServer(){
	var _domain = document.domain;
	var _is_dev = /(dev)/i;
	var _is_qa = /(qa-)/i;
	var _is_dev_domain_search = _domain.search(_is_dev);
	var _is_qa_domain_search = _domain.search(_is_qa);
	var _result = 0;

	if (_is_dev_domain_search > -1){_result = 1;}
	else if (_is_qa_domain_search> -1){_result = 2;}
	else{_result = 3;}

	return _result;
}


//popup open
popupWins = new Array();
function open_win(url, name, width, height, xpos, ypos){
	name =  name || '_blank';
	xpos = xpos || (screen.availWidth-width)/2;
	ypos = ypos || (screen.availHeight-height)/2;

	if ( typeof( popupWins[name] ) != "object" ){
		popupWins[name] = window.open(url, name, 'width='+width+', height='+height+', left='+xpos+', top='+ypos+', menubar=no, status=no, toolbar=no, scrollbars=no, resizable=yes');
	 } else {
		 if (!popupWins[name].closed){
			popupWins[name].location.href = url;
		 } else {
			popupWins[name] = window.open(url, name, 'width='+width+', height='+height+', left='+xpos+', top='+ypos+', menubar=no, status=no, toolbar=no, scrollbars=no, resizable=yes');
		 }
	 }

	 popupWins[name].focus();
}

//input 의 limit attribute의 값을 초과할 수 없다.
function check_num_limmit(obj, msg){
	if(obj.val() == ''){
		return '';
	}
	else if(!check_num(obj)){
		alert('숫자만 입력 가능합니다.');
		return '';
	}
	else if(parseInt(obj.val()) > parseInt(obj.attr('limit'))){
		alert(msg.replace('{limit_val}', parseInt(obj.attr('limit'))));
		return parseInt(obj.attr('limit'))
	}
	else{
		return parseInt(obj.val())
	}
}

/* 안씀
//숫자만 가능함
function check_num(obj){
	var num_check=/^[0-9]*$/;
	return num_check.test(obj.val());
}

//숫자형 commify
function commify(n) {
	var reg = /(^[+-]?\d+)(\d{3})/;   // 정규식
	n += '';                          // 숫자를 문자열로 변환

	while (reg.test(n))
		n = n.replace(reg, '$1' + ',' + '$2');

	return n;
}
*/
//슬라이드 :: MY단기
function go_slide(prev_next, wrap_id, contents_width, direction, show_pagination){
	var wrap_obj = $('#' + wrap_id);
	var total_cnt = $('#'+wrap_id+' .slide_contents').length;
	var end_idx = parseInt(wrap_obj.attr('end_idx'));
	var size = parseInt(wrap_obj.attr('size'));
	var status = wrap_obj.attr('status');
	var result_prev_next = 0;
	var cur_page = Math.ceil(end_idx / size);
	var total_page = Math.ceil(total_cnt / size);

	if(status=='false'){ //next/prev
		var now_idx = end_idx;
		var next_idx = end_idx + (prev_next * size);

		if(prev_next > 0 && next_idx > total_cnt) next_idx = total_cnt;
		if(prev_next < 0 && next_idx <= size) next_idx = size;

		if( (prev_next > 0  && now_idx < next_idx) || (prev_next < 0  && now_idx > next_idx) ){

			wrap_obj.attr('status', 'true');
			if(direction == 'left'){
				wrap_obj.animate({ "margin-left": "-="+(contents_width * (next_idx - now_idx))+"px" }, contents_width*Math.abs(next_idx - now_idx), 'swing'
					, function(){
						wrap_obj.attr('status', 'false').attr('end_idx', next_idx);
					} 
				);	
			}
			else if(direction == 'top'){
				wrap_obj.animate({ "margin-top": "-="+(contents_width * (next_idx - now_idx))+"px" }, contents_width*Math.abs(next_idx - now_idx), 'swing'
					, function(){
						wrap_obj.attr('status', 'false').attr('end_idx', next_idx);
					} 
				);
			}
				
			result_prev_next = prev_next;;
		}

		if(show_pagination){ // wrap contents 의 부모 object 안에 small page pagination 표시
			var paging = $('<div class="smallpage"></dlv>');
			if((cur_page + result_prev_next) > 1)
				paging.append ('<a href="javascript:get_freepass_refund('+(cur_page+result_prev_next-1)+', \'\');"><img src="/img/engdangi/mydangi/btn/btn_prev_small.gif" alt="이전" class="prev"></a>');
			paging.append('<span class="on">'+(cur_page + result_prev_next)+'</span> / <span>'+total_page+'</span>');
			if((cur_page + result_prev_next) < total_page)
				paging.append('<a href="javascript:get_freepass_refund('+(cur_page+result_prev_next+1)+', \'\');"><img src="/img/engdangi/mydangi/btn/btn_next_small.gif" alt="다음" class="next"></a>');

			$(wrap_obj).parent().find('div.smallpage').html(paging);
		}
	}

	return result_prev_next;
}
/** 안씀 !! **/
//======================
// 플레이어 관련 함수
//======================
//무료 플레이어 팝업
// @param : free_movie_kind - 영상 종류,  
// @ example : 
//예측특강 - free_lec_player('special', freemovie_id); -우측 리스트 있음
//무료강의 - free_lec_player('freemovie', freemovie_id); -우측 리스트 있음
//HOT ISSUE - free_lec_player('hot', freemovie_id); -우측 리스트 있음
//모의고사 해설 특강 - free_lec_player('project', freemovie_id); -우측 리스트 있음
//제품 무료 영상- free_lec_player('product', full_movie_url); -리스트 X
//무료강의- free_lec_player('free', freemovie_id); -리스트 X
/*function free_lec_player(free_movie_kind, freemovie_id){

	var _checkDevServer = checkDevServer();
	var _player_domain = "";
	var url = "";

	if (_checkDevServer==1){
		_player_domain = "dev.myclass.dangi.co.kr";
	}else if (_checkDevServer==2){
		_player_domain = "qa-myclass.dangi.co.kr";
	}else{
		_player_domain = "myclass.dangi.co.kr";
	}

	if (free_movie_kind=="product"){
		 url = "http://" + _player_domain + "/player/free/common/"+ free_movie_kind + "?full_movie_url="+ freemovie_id;

	}else{
		url = "http://" + _player_domain + "/player/free/common/"+ free_movie_kind + "/"+ freemovie_id;

	}

	open_win(url, "lec_player", 1024, 788);
}*/

//중단기용
function free_lec_player_v2(freemovie_id, full_movie_url){

	var _checkDevServer = checkDevServer();
	var _player_domain = "";
	var url = "";
	var free_movie_kind = "product";

	if (_checkDevServer==1){
		_player_domain = "dev.myclass.dangi.co.kr";
	}else if (_checkDevServer==2){
		_player_domain = "qa-myclass.dangi.co.kr";
	}else{
		_player_domain = "myclass.dangi.co.kr";
	}

	if (freemovie_id){
		$.ajax({
			url : "/freemovie/get_freemovie_info"
			, data : {"freemovie_id" : freemovie_id}
			, dataType : "json"
			, success : function(data){
				full_movie_url = data.flv_movie_url;
				url = "http://" + _player_domain + "/player/free/common/"+ free_movie_kind + "?full_movie_url="+ full_movie_url;
				open_win(url, "lec_player", 1024, 788);		
			}
			, error : function(){	
				alert("데이터 조회중 오류가 발생하였습니다.");
			}
		});

	}else{
		url = "http://" + _player_domain + "/player/free/common/"+ free_movie_kind + "?full_movie_url="+ full_movie_url;
		open_win(url, "lec_player", 1024, 788);
	
	}
}

//무료 플레이어 아이프레임
// @param :   playbox - div id , free_movie_kind - 무료영상 종류, freemovie_id - 무료 영상 ID (free_movie_kind = 'product' 일때만 Movie Full URL), autoplay - 자동 재생여부(Y: 자동 , N: 반자동)
//		playimg - 플레이어 첫이미지, width - iframe 넓이, height - iframe 높이,
// @example :  free_lec_player_iframe("#testdiv" , "freemovie", "111", "N", "http://...../sellingpoint2.jpg", 700, 400);
function free_lec_player_iframe(playbox,free_movie_kind, freemovie_id, autoplay, playimg, width, height){

	var _checkDevServer = checkDevServer();
	var _player_domain = "";
	var url = "";

	if (_checkDevServer==1){
		_player_domain = "dev.myclass.dangi.co.kr";
	}else if (_checkDevServer==2){
		_player_domain = "qa-myclass.dangi.co.kr";
	}else{
		_player_domain = "myclass.dangi.co.kr";
	}

	if (free_movie_kind=="product"){
		 url = "http://" + _player_domain + "/player/free/iframe/"+ free_movie_kind + "?full_movie_url="+ freemovie_id;

	}else{
		url = "http://" + _player_domain + "/player/free/iframe/"+ free_movie_kind + "/"+ freemovie_id;

	}

	$(playbox).html("<iframe width='"+width+"' height='"+height+"' src='"+url+"?&autoplay="+autoplay+"&playimg="+playimg+"&width="+width+"&height="+height+"' border='0' frameborder='0' scrolling='no'></iframe>");
}

//무료 플레이어 아이프레임 (free_movie_kind = 'product' 일때만 Movie Full URL)
// @param :   playbox - div id 
//			, freemovie_id - 무료 영상 ID 
//			, full_movie_url - 무료 영상 URL
//			, autoplay - 자동 재생여부(Y: 자동 , N: 반자동)
//			, playimg - 플레이어 첫이미지
//			, width - iframe 넓이
//			, height - iframe 높이,
// @example :  free_lec_player_iframe("#testdiv" , "freemovie", "111", "N", "http://...../sellingpoint2.jpg", 700, 400);
function free_lec_player_iframe_v2(playbox, freemovie_id, full_movie_url, autoplay, playimg, width, height){

	var _checkDevServer = checkDevServer();
	var _player_domain = "";
	var url = "";
	var free_movie_kind = "product";

	if (_checkDevServer==1){
		_player_domain = "dev.myclass.dangi.co.kr";
	}else if (_checkDevServer==2){
		_player_domain = "qa-myclass.dangi.co.kr";
	}else{
		_player_domain = "myclass.dangi.co.kr";
	}

	if (freemovie_id){
		$.ajax({
			url : "/freemovie/get_freemovie_info"
			, data : {"freemovie_id" : freemovie_id}
			, dataType : "json"
			, success : function(data){
				full_movie_url = data.flv_movie_url;
				url = "http://" + _player_domain + "/player/free/common/"+ free_movie_kind + "?full_movie_url="+ full_movie_url;
			}
			, error : function(){	
				alert("데이터 조회중 오류가 발생하였습니다.");
			}
		});

	}else{
		url = "http://" + _player_domain + "/player/free/common/"+ free_movie_kind + "?full_movie_url="+ full_movie_url;	
	}

	$(playbox).html("<iframe width='"+width+"' height='"+height+"' src='"+url+"?&autoplay="+autoplay+"&playimg="+playimg+"&width="+width+"&height="+height+"' border='0' frameborder='0' scrolling='no'></iframe>");
}
//유료 플레이어 팝업
//@ param : param - 코드이그나이터 파라미터 형식
//param : 일반강의 - contents_use_id
//             보카강의 - contents_use_id/voca_id
function lec_player(param){

	var _checkDevServer = checkDevServer();
	var _player_domain = "";

	if (_checkDevServer==1){
		_player_domain = "dev.myclass.dangi.co.kr";
	}else if (_checkDevServer==2){
		_player_domain = "qa-myclass.dangi.co.kr/";
	}else{
		_player_domain = "myclass.dangi.co.kr";
	}
  
	var url = "http://" + _player_domain + "/player/main/common/" + param;

	open_win(url, "lec_player", 1024, 788);

}
//Domain Return (ex. test.com  Also engdangi.com)
function returnDomain(){
	var _domain =document.domain;
	var _domain_replace = /([a-z\d\-]+(?:\.(?:asia|info|name|mobi|com|net|org|biz|tel|xxx|kr|co|so|me|eu|cc|or|pe|ne|re|tv|jp|tw)){1,2})(?::\d{1,5})?(?:\/[^\?]*)?(?:\?.+)?$/i;
	var _result = _domain.replace(_domain_replace,"");

	_result = _domain.replace(_result,"");

	return _result;
}

// 사전 예약 팝업
function window_open(event_no){

	//alert('점검중 입니다.');
	window.open('/promotion/event_reserv_pop/?event_no='+event_no,'','width=100,height=100,scrollbars=no');
}

//쿠키 쓰기
function setCookie(cname, cvalue, exdays) {
	var d = new Date();
	d.setTime(d.getTime() + (exdays*24*60*60*1000));
	var expires = "expires="+d.toUTCString();
	document.cookie = cname + "=" + cvalue + "; " + expires + ";domain=.dangi.co.kr;path=/";
	document.cookie = cname + "=" + cvalue + "; " + expires + ";domain=.conects.com;path=/";
}

//쿠키 가져오기
function getCookie(cname) {
	var name = cname + "=";
	var ca = document.cookie.split(';');
	for(var i=0; i<ca.length; i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1);
			if (c.indexOf(name) != -1) return c.substring(name.length, c.length);
	}
	return "";
}

//----------------------
// 결제 페이지 이동
//----------------------
// 기본 적으로 폼(form)명은 sale_form 통일(※중요)
// action_type(액션 타입) : order - 결제, cart - 장바구니
//sale_id(상품판매정보아이디) : 'checked' 일 경우 sale_form 폼(form)에 있는 
//                                    라디오 버튼 또는 체크박스의 선택한 값을 불러와 submit함.
//                                    그 외 숫자일 경우 단일 상품 구매 또는 장바구니 페이지로 이동함.
function add_form_submit(action_type, sale_id){
	var _checkDevServer = checkDevServer();
	var _domain = "";

	if (_checkDevServer==1){
		_domain = "dev.myclass.dangi.co.kr";
	}else if (_checkDevServer==2){
		_domain = "qa-myclass.dangi.co.kr/";
	}else{
		_domain = "myclass.dangi.co.kr";
	}
	
	//로그인 체크
	var login_obj = login_chk();
	
	if(!login_obj._is_member){
		location.href="https://"+login_obj._domain+"/member/login?redirect_url="+login_obj._redirect_url;
		return;
	}

	//결제 
	var obj = $("form[name='sale_form']");
	obj.attr("action","https://" + _domain + "/payment/" + action_type + "/add_multiple");
	
	if(sale_id !="checked"){
		var num_check=/^[0-9]*$/;
		if(!num_check.test(sale_id)){
			alert("요청정보가 잘못되었습니다.(-1000)");
			return false;	
		}
		if(sale_id){
			obj.append('<input type="hidden" name="saleinfo_id[]" value="'+sale_id+'">');
			obj.submit();
		}else{
			alert("요청정보가 잘못되었습니다.(-1001)");
			return false;
		}		
	}else{
		var _checked_saleinfo_id_chk =false;
		$("input[name='saleinfo_id[]']:checked").each(function(key) {
			_checked_saleinfo_id_chk = true;
		});
		if(_checked_saleinfo_id_chk){
			obj.submit();
		}else{
			alert("구매하실 상품을 선택하세요.");
		}
	}
	
}

//로그인 체크 
function login_chk(){
	var _checkDevServer = checkDevServer();
	var _domain = "";
	var _is_member    = document.getElementById('is_member').value;
	var _redirect_url = document.getElementById('redirect_url').value;

	if (_checkDevServer==1){
		_domain = "dev.member.conects.com";
	}else if (_checkDevServer==2){
		_domain = "qa-member.conects.com";
	}else{
		_domain = "member.conects.com";
	}

	var result_obj = {};
	result_obj._is_member = _is_member;
	result_obj._domain    = _domain;
	result_obj._redirect_url = _redirect_url;

	return result_obj;
}

//숫자 콤마
function comma_number_format (num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
}

//이벤트 예약 팝업
function rsvp_pop(event_id){
	window.open("/promotion/reservation/main/rsvp_pop?event_id="+event_id, "", "width=640,height=910,resizable=no,scrollbars=no,status=no;");
}

//div 높이 지정
function set_height(target, height){
	//alert(height);
	$(target).css("height", height);
}

//레이어 팝업 열기
function popup_op(pop){
	$('.'+pop).fadeIn('slow');
};

//레이어 팝업 닫기
function popup_cl(pop){
	$('.'+pop).fadeOut('slow');
};

//############################################
//팝업용 DIM 스크립트
//############################################
	//var dim_width = 0;	//dim에 윈도우 가로값 저장
	//var dim_height = 0;	//dim에 윈도우 세로값 저장
	var dim_width = $(window).width();
	var dim_height = $(window).height();
	
	//윈도우 로드 완료 후 dim 가로,세로 값 측정하여 dim에 적용
	$(window).load(function(){
		dim_width = $(window).width();
		dim_height = $(window).height();
		$('.popup_dim').css('width',dim_width).css('height',dim_height);
		$('.pop_teacher_lecture_box').css('height',dim_height-150);	//윈도우 로드 후 팝업div에 세로값 적용
	});

	//윈도우 창 사이즈 변경시 변경된 윈도우창에 동일하게 다시 가로갑 세로값 적용
	$(window).resize(function(){
		dim_width = $(window).width();
		dim_height = $(window).height();
		$('.popup_dim').css('width',dim_width).css('height',dim_height);
		$('.pop_teacher_lecture_box').css('height',dim_height-150);	//윈도우 로드 후 팝업div에 세로값 적용
		pop_scroll();
	});

//############################################
//POPUP 스크립트
//############################################
	//팝업창 탭 스크롤바 부분(윈도우 크기에 맞게 조절) > pop_scroll()
	function pop_scroll(){
		pop_1 = $('.pop_teacher_lecture_box').height()
		pop_2 = $('.view_content').height()+100
		pop_1=pop_1-pop_2
		$('.pop_tab_box .tab_cont').css('height',pop_1);
	};

	//팝업용 dim변수에 넣고 #contents 아래쪽에 삽입
	function popup_dim(){
		var popup_bg = "<div class='popup_dim' style='position:fixed;top:0;left:0;width:"+dim_width+"px;height:"+dim_height+"px;background:#000;opacity:0.8;filter:alpha(opacity=80);z-index:100;display:block;'></div>";
		$('#contents').append(popup_bg);
	};

	//선생님 강좌 팝업 열기
	function lecture_popup(saleinfo_id){
		// 리뉴얼
//		 lib_st.open_saleinfo_detail(saleinfo_id);
//		 return;
		
		if(saleinfo_id == 95980){
			// - 해당 상품은 패키지 상품이라 바로 프로모션 페이지로 이동이 필요 - 요청자 : 서우리 	
			window.open('/promotion/start/main/pc');
		}else{
			//정보 조회 
			$.ajax({
				url : "/registration/saleinfo_detail"
				, data : {"saleinfo_id" : saleinfo_id}
				, dataType : "html"
				, success : function(data){
					$("#saleinfo_detail").html(data);
					$(".pop_teacher_lecture_box").slideDown('slow',function(){
						//팝업 노출 완료후 > 팝업창 탭부분(스크롤바 유동적으로 조절)
						pop_scroll();
					});
					popup_dim();	//팝업 열릴때 딤도 호출하여 노출
				}
				, error : function(){
					$("#saleinfo_detail").html("상품정보를 불러오는 도중 오류가 발생했습니다.");
				}
			});
		}
		
	};
	
	/*
	 * 강좌 호출 함수
	 * 
	 * store_category_ids	: 상품카테고리
	 * saleinfo_ids			: 상품 id
	 * 
	 * ex) 
	 *  var store_category_ids = 473;
	 *	var saleinfo_ids = "38640,38641,38642,38644,38646,38648,38649,38650,38651,38652,38653,38654,38655,38657,38658,38659,38660,38661,38662,38663,38664";
	 *	get_lecture_list_by_id(store_category_ids, saleinfo_ids);
	 */
	function get_lecture_list_by_id(play_area, store_category_ids, saleinfo_ids){
		
		var data = {'store_category_ids':store_category_ids,'saleinfo_ids':saleinfo_ids}
		$.ajax({
			type:'POST',
			url:'/registration/saleinfo_lec_list_by_id',
			data:data,
			success:function(data){
				$(play_area).html('');
				$(play_area).html(data);		//#lecture_list 강좌 노출 div
			},
			error:function(e){
				alert("목록을 불러오는데 실패하였습니다.");
			}
		});
	}

	//유의사항 호출 함수
	function notice_call(class_name , device_type){
	    var url_info = document.location.href.split("://");
	    $.post("/notice_information",{
	    		url:url_info[1]
	    		,device_type:device_type}
	    		,function(data){
	    			if(data){
	    				$("."+class_name).html(data);
	    			}
	    });
	}

//내강의실 url
function get_host_myclass(){
	var s = document.URL;
	s = s.match("//(.*)china.dangi");
	prefix = '';
	if (s && s.length>1) {
		prefix = s[1];
	}
	var data = {};
	data.host = "http://" + prefix + "myclass.dangi.co.kr/";
	data.shost = "https://" + prefix + "myclass.dangi.co.kr/";

	return data;
}

// 상품 주문 : 예제 order_products([9275,4618]) 
function order_products(saleinfo_ids) {
	$.ajax({
		url: '/api/member/whoami',
		type: 'post',
		data: {},
		success: function (data) {
			if (data.is_login) {
				/** form submit **/
				if ($('form[name=order]')) $('form[name=order]').remove();
				var url = get_host_myclass()['shost'] + 'payment/order/add_multiple';
				var myform = document.createElement("form");
				myform.name = 'order';
			    myform.action = url;
			    myform.method = "post";

			    saleinfo_ids.forEach(function(saleinfo_id, i){
					var i = document.createElement("input"); //input element, text
					i.setAttribute('type',"text");
					i.setAttribute('name',"saleinfo_id[]");
					i.setAttribute('value', saleinfo_id);
					myform.appendChild(i);
				});
			    document.body.appendChild(myform);
			    myform.submit();
			} else {
				window.location = 'https://member.conects.com/member/login?redirect_url=' + window.location.href;
			}
		}
	});

}
function order_products_bytag(){
	
	$.ajax({
		url: '/api/member/whoami',
		type: 'post',
		data: {},
		success: function (data) {
			if (data.is_login) {
				/** form submit **/
				var items = $('input[name^=saleinfo_id]:checked');
				if (items.length < 1) {
					alert('구매할 상품을 선택해주세요.');
					return;
				}

				var url = get_host_myclass()['shost'] + 'payment/order/add_multiple';

				window.f = document.createElement("form");
				f.setAttribute('method',"post");
				f.setAttribute('action', url);

				items.each(function(i, item){
					var i = document.createElement("input"); //input element, text
					i.setAttribute('type',"text");
					i.setAttribute('name',"saleinfo_id[]");
					i.setAttribute('value', $(item).attr('value'));
					f.appendChild(i);
				});
				document.body.appendChild(f);
				f.submit();
			} else {
				window.location = 'https://member.conects.com/member/login?redirect_url=' + window.location.href;
			}
		}
	});
}
// 상품 담기 : 예제 cart_products([9275,4618])
function cart_products(saleinfo_ids) {

	$.ajax({
		url: '/api/member/whoami',
		type: 'post',
		data: {},
		success: function (data) {
			if (data.is_login) {
				var items = $('input[name^=saleinfo_id]:checked');

				if ($('iframe[name=mycart]')) $('iframe[name=mycart]').remove();
				var myform = document.createElement('form');
				myform.name = 'mycart';
			    myform.action = get_host_myclass()['shost'] + 'payment/cart/add_multiple';;
			    myform.target = "cart";
			    myform.method = "post";
			    myform.style.display = 'none';

			    saleinfo_ids.forEach(function(saleinfo_id, i){
					var i = document.createElement("input"); //input element, text
					i.setAttribute('type',"text");
					i.setAttribute('name',"saleinfo_id[]");
					i.setAttribute('value', saleinfo_id);
					myform.appendChild(i);
				});

			    if ($('iframe[name=cart]')) $('iframe[name=cart]').remove();
			    var iframe = document.createElement('iframe');
				iframe.name = 'cart';
				iframe.style.display = 'none';
				document.body.appendChild(iframe);
				document.body.appendChild(myform);
				myform.submit();
				
				if (confirm('선택하신 상품이 장바구니에 담겼습니다. 장바구니로 이동하시겠습니까?')) {
					url = get_host_myclass()['shost'] + 'payment/cart';
					window.top.location.href = url;
				}
			} else {
				window.location = 'https://member.conects.com/member/login?redirect_url=' + window.location.href;
			}
		}
	});
}
function cart_products_bytag(){
	$.ajax({
		url: '/api/member/whoami',
		type: 'post',
		data: {},
		success: function (data) {
			if (data.is_login) {
				var items = $('input[name^=saleinfo_id]:checked');

				if ($('iframe[name=mycart]')) $('iframe[name=mycart]').remove();
				var myform = document.createElement('form');
				myform.name = 'mycart';
			    myform.action = get_host_myclass()['shost'] + 'payment/cart/add_multiple';;
			    myform.target = "cart";
			    myform.method = "post";
			    myform.style.display = 'none';

			    items.each(function(i, item){
					var i = document.createElement("input"); //input element, text
					i.setAttribute('type',"text");
					i.setAttribute('name',"saleinfo_id[]");
					i.setAttribute('value', $(item).attr('value'));
					myform.appendChild(i);
				});

			    if ($('iframe[name=cart]')) $('iframe[name=cart]').remove();
			    var iframe = document.createElement('iframe');
				iframe.name = 'cart';
				iframe.style.display = 'none';
				document.body.appendChild(iframe);
				document.body.appendChild(myform);
				myform.submit();
				
				if (confirm('선택하신 상품이 장바구니에 담겼습니다. 장바구니로 이동하시겠습니까?')) {
					url = get_host_myclass()['shost'] + 'payment/cart';
					window.top.location.href = url;
				}
				return;
			} else {
				window.location = 'https://member.conects.com/member/login?redirect_url=' + window.location.href;
			}
		}
	});
}
function play_movie_wiframe(tag_id, movie_url){
    target = $('#'+tag_id);
	var ifrm = document.createElement("iframe");
	ifrm.setAttribute("src", movie_url);
	ifrm.setAttribute("frameborder", "0");
	ifrm.setAttribute("marginheight", "0");
	ifrm.setAttribute("scrolling", "no");
	ifrm.setAttribute("frameborder", "0");
	ifrm.setAttribute('allowFullScreen', '')
    ifrm.style.width = target.width() + "px";
    ifrm.style.height = target.height() + "px";
    target.html(ifrm);
    return;
}
var create_lib_st = function() {
    var that = { };
    var url = null;
    
    days_of_week = {
        sunday: 0,
        monday: 1,
        tuesday: 2,
        wednesday: 3,
        thursday: 4,
        friday: 5,
        saturday: 6
    };

    days_of_week_txt = {
        0: 'Su',
        1: 'M',
        2: 'Tu',
        3: 'W',
        4: 'Th',
        5: 'F',
        6: 'Sa'
    };

    url = document.URL.split('/');
    that.base_url = url.slice(0, 3).join('/') + '/';

    that.initialCap =  function (field) {
        return field.substr(0, 1).toUpperCase() + field.substr(1);
    }

    that.refresh_page = function () {
        window.location = document.URL;
    }

    /* Regex Validation : Integer */
    that.isInteger = function (s) {
        var isInteger_re = /^\s*\d+\s*$/;
        return String(s).search (isInteger_re) != -1;
    };

    /* Regex Validation : Day (yyyy-mm-dd) */
    that.isDay = function (s) {
        s = String(s);
        var re = /^[0-2][0-9][0-9][0-9]-[0-1][0-9]-[0-3][0-9]$/;
        var ret = re.exec(s);
        if (ret) return true;
        else return false;
    }

    /* Regex Validation : email */
    that.validateEmail = function (s) {
        s = String(s);
        if (s.search(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/) != -1) {
            return true;
        } else 
            return false;
    }

    /* Regex Validation : Day (xxx-xxxx-xxxx) */
    that.validatePhoneNumber = function (s) {
        s = String(s);
        var phoneno = /^\(?([0-9]{3})\)?[-. ]?([0-9]{4})[-. ]?([0-9]{4})$/;
        var ret = phoneno.exec(s);
        if (ret) return true;
        else return false;
    }

    /* Regex Validation : image file name */
    that.validateImagename = function (s) {
        s = String(s);
        var imagename = /\.(jpg|JPG|jpeg|JPEG|png|PNG|gif|GIF)$/;
        var ret = imagename.exec(s);
        if (ret) return true;
        else return false;
    }

    /* Regex Validation : host url */
    that.validateUrl = function (s) {
        s = String(s);
        var url = /^((http[s]?|ftp):\/)?\/?([^:\/\s]+)((\/\w+)*\/)([\w\-\.]+[^#?\s]+)(.*)?(#[\w\-]+)?$/;
        var ret = url.exec(s);
        if (ret) return true;
        else return false;
    }

    that.validateNumeric = function (input)
    {
        return (input - 0) == input && (''+input).trim().length > 0;
    }

    that.mobilecheck = function() {
        var check = false;
        (function(a,b){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4)))check = true})(navigator.userAgent||navigator.vendor||window.opera);
        return check;
    }

    /* Redirect Http request */
    that.submitFORM = function(path, params, method) {
        method = method || "post";

        var form = document.createElement("form");
        form.setAttribute("method", method);
        form.setAttribute("action", path);

        //Move the submit function to another variable
        //so that it doesn't get overwritten.
        form._submit_function_ = form.submit;

        for(var key in params) {
            if(params.hasOwnProperty(key)) {
                var hiddenField = document.createElement("input");
                hiddenField.setAttribute("type", "hidden");
                hiddenField.setAttribute("name", key);
                hiddenField.setAttribute("value", params[key]);

                form.appendChild(hiddenField);
            }
        }

        document.body.appendChild(form);
        form._submit_function_();
    }

    that.getCookie = function (cname) {
        var name = cname + "=";
        var ca = document.cookie.split(';');
        for(var i=0; i<ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0)==' ') c = c.substring(1);
            if (c.indexOf(name) != -1) return c.substring(name.length,c.length);
        }
        return "";
    }
    that.setCookie = function (cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays*24*60*60*1000));
        var expires = "expires="+d.toUTCString();
        document.cookie = cname + "=" + cvalue + "; " + expires + ";domain=.dangi.co.kr;path=/";
    }
    that.deleteCookie = function(cookieName) {
        try {
            var expireDate = new Date();
            // expireDate.setDate(expireDate.getDate() - 1);
            expireDate.setFullYear(expireDate.getFullYear() - 2);
            // var c_value = "" + ((expireDate==null) ? "":";expires="+expireDate.toGMTString());
            var c_value = "" + ((expireDate==null) ? "":";expires="+expireDate.toUTCString() + ";domain=.dangi.co.kr;path=/");
            document.cookie = cookieName + "=" + c_value;
        } catch(e){
            alert(e.message);
        }
    }

    that.getThisTuesday = function () {
        var today = new Date();
        var tuseday = that.getNextTuesday(today);
        tuseday.setDate(tuseday.getDate() - 7);
        tuseday.setHours(0);
        tuseday.setMinutes(0);
        tuseday.setSeconds(0);
        return tuseday;
    }
    that.getNextTuesday = function (d) {
        var ref = {0:2, 1:1, 2:7, 3:6, 4:5, 5:4, 6:3};

        d = new Date(d);
        var day = d.getDay();
        var diff = d.getDate() + ref[day];

        var result = new Date(d.setDate(diff));
        result.setHours(0);
        result.setMinutes(0);
        result.setSeconds(0);
        return result;
    }
    that.getNextWendseday = function (d) {
        var ref = {0:3, 1:2, 2:1, 3:7, 4:6, 5:5, 6:4};

        d = new Date(d);
        var day = d.getDay();
        var diff = d.getDate() + ref[day];

        var result = new Date(d.setDate(diff));
        result.setHours(0);
        result.setMinutes(0);
        result.setSeconds(0);
        return result;
    }
 
    that.getThisMonday = function () {
        var today = new Date();
        var monday = that.getNextMonday(today);
        monday.setDate(monday.getDate() - 7);
        monday.setHours(0);
        monday.setMinutes(0);
        monday.setSeconds(0);
        return monday;
    }
    that.getNextMonday = function (d) {
        var ref = {0:1, 1:7, 2:6, 3:5, 4:4, 5:3, 6:2};

        d = new Date(d);
        var day = d.getDay();
        var diff = d.getDate() + ref[day];

        var result = new Date(d.setDate(diff));
        result.setHours(0);
        result.setMinutes(0);
        result.setSeconds(0);
        return result;
    }

    that.getNextSaturday = function (d) {
        var ref = {0:6, 1:5, 2:4, 3:3, 4:2, 5:1, 6:7};

        d = new Date(d);
        var day = d.getDay();
        var diff = d.getDate() + ref[day];

        var result = new Date(d.setDate(diff));
        result.setHours(0);
        result.setMinutes(0);
        result.setSeconds(0);
        return result;
    }
    
    // 주기 화요일 -> 월요일
    that.period_tue_mon = function(){
        var now = new Date();
        info = {};

        // 월요일이면
        if (now.getDay() == 1) {
            info.next_monday = that.getNextWendseday(now);
            info.next_monday = new Date(info.next_monday.setDate(info.next_monday.getDate() - 2));
        } 
        else {
            info.next_monday = that.getNextMonday(now);
        }
        var days = 6;
        info.this_tuesday = new Date(info.next_monday.getTime() - (days * 24 * 60 * 60 * 1000));

        return info;
    }
    // 주기 수요일 -> 화요일
    that.period_wed_tue = function(){
        var now = new Date();

        if(now.getMonth()+1 ==4 && now.getDate() == 4){
        	now = new Date("April 5, 2017");
        }
        info = {};

        // 화요일이면
        if (now.getDay() == 2) {
        	
            info.next_tuesday = that.getNextWendseday(now);
            info.next_tuesday = new Date(info.next_tuesday.setDate(info.next_tuesday.getDate() - 1));
        	
        } 
        else {
            info.next_tuesday = that.getNextTuesday(now);
        }
        var days = 6;
        
        	info.this_wednesday = new Date(info.next_tuesday.getTime() - (days * 24 * 60 * 60 * 1000));
      
        return info;
    }
    // 시간 대비 횟수 계산
    that.cal_year = function(start, base){
        var now = new Date();
        info = that.getDateDiff(start, now, 2);
        return base + parseInt(info.diffdays / 7);
    }
    that.getDateDiff = function (date1, date2, length) {
        var data = {};
        timediff = Math.abs(date2.getTime() - date1.getTime());
        data.diffdays = Math.floor(timediff / (1000 * 3600 * 24));
        timediff = timediff - data.diffdays * 1000 * 3600 * 24;
        data.diffhours = Math.floor(timediff / (1000 * 3600));
        timediff = timediff - data.diffhours * 1000 * 3600;
        data.diffmins = Math.floor(timediff / (1000 * 60));
        timediff = timediff - data.diffmins * 1000 * 60;
        data.diffsecs = Math.floor(timediff / (1000));

        data.days = that.pad(data.diffdays, length);
        data.hours = that.pad(data.diffhours, length);
        data.mins = that.pad(data.diffmins, length);
        data.secs = that.pad(data.diffsecs, length);

        return data;
    }
    that.pad = function (n, width, z) {
        z = z || '0';
        n = n + '';
        return n.length >= width ? n : new Array(width - n.length + 1).join(z) + n;
    }
    that.numberFormat = function (nStr)
    {
        nStr += '';
        var x = nStr.split('.');
        var x1 = x[0];
        var x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
        }
        return x1 + x2;
    }
    that.roundNumber = function (num, dec) {
        var result = Math.round(num*Math.pow(10,dec))/Math.pow(10,dec);
        return result;
    }
    that.getParameterByName = function (name) {
        name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
        var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
            results = regex.exec(location.search);
        return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
    }
    that.getParameterByNameForUrl = function (name, url) {
        name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
        var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
            results = regex.exec(url);
        return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
    }
    that.formatInteger = function (num, lenth) {
        var n = num.toString();
        var howmany_pad = lenth - n.length;
        var str = '';
        for (var i = 0; i < howmany_pad; i++) {
            str += '0';
        };
        return str + n;
    }
    // 페이지내 이동
    that.scrollto = function (tag_id) {
        var position = $('#' + tag_id).offset();
        var top = parseInt(position.top) + 'px';
        $('html, body').animate({
            scrollTop: top
        }, 'fast');
    }
    that.goBack = function () {
        window.history.back();
    }
    that.confirm_to_purchase = function (saleinfo_id, msg){
		url = that.get_host_myclass()['shost'] + "payment/order/add/"+ saleinfo_id;
		if (confirm(msg)) {
			window.location = url;
		}
	}
    that.confirm_to_login = function (msg){
		if (confirm(msg)) {
			window.location = that.get_host_member()['shost'] + 'member/login?redirect_url=' + window.location.href;
		}
	}
    //내강의실 url
    that.get_host_myclass = function (){
        var s = document.URL;
        s = s.match("//(.*)china.dangi");
        prefix = '';
        if (s && s.length>1) {
            prefix = s[1];
        }
        if (prefix=='dev-') prefix ='dev.';
        var data = {};
        data.host = "http://" + prefix + "myclass.dangi.co.kr/";
        data.shost = "https://" + prefix + "myclass.dangi.co.kr/";
        return data;
    }
    // 멤버단기 url
    that.get_host_member = function (){
        var s = document.URL;
        s = s.match("//(.*)china.dangi");
        prefix = '';
        if (s && s.length>1) {
            prefix = s[1];
        }
        if (prefix=='dev-') prefix ='dev.';
        var data = {};
        data.host = "http://" + prefix + "member.conects.com/";
        data.shost = "https://" + prefix + "member.conects.com/";
        return data;
    }
    // 커넥츠 url
    that.get_host_conects = function (){
        var s = document.URL;
        s = s.match("//(.*)china.dangi");
        prefix = '';
        if (s && s.length>1) {
            prefix = s[1];
        }
        if (prefix=='dev-') prefix ='qa-';
        var data = {};
        data.host = "http://" + prefix + "my.conects.com/";
        data.shost = "https://" + prefix + "my.conects.com/";
        return data;
    }
    that.open_win = function (url, name, width, height, xpos, ypos){
        popupWins = new Array();
        name =  name || '_blank';
        xpos = xpos || (screen.availWidth-width)/2;
        ypos = ypos || (screen.availHeight-height)/2;

        if ( typeof( popupWins[name] ) != "object" ){
            popupWins[name] = window.open(url, name, 'width='+width+', height='+height+', left='+xpos+', top='+ypos+', menubar=no, status=no, toolbar=no, scrollbars=no, resizable=yes');
         } else {
             if (!popupWins[name].closed){
                popupWins[name].location.href = url;
             } else {
                popupWins[name] = window.open(url, name, 'width='+width+', height='+height+', left='+xpos+', top='+ypos+', menubar=no, status=no, toolbar=no, scrollbars=no, resizable=yes');
             }
         }

         popupWins[name].focus();
    }
    that.onlyNumber = function(event){
		event = event || window.event;
		var keyID = (event.which) ? event.which : event.keyCode;
		if((keyID >= 48 && keyID <= 57) || (keyID >= 96 && keyID <= 105) || keyID == 8 || keyID == 46 || keyID == 37 || keyID == 39 || keyID == 9 || keyID == 86 || keyID == 67){
			return;
		}else{
			return false;
		}
	}
    that.removeChar = function(event) {
		event = event || window.event;
		var keyID = (event.which) ? event.which : event.keyCode;
		if ( keyID == 8 || keyID == 46 || keyID == 37 || keyID == 39 ) 
			return;
		else
			event.target.value = event.target.value.replace(/[^0-9]/g, "");
	}
	that.open_saleinfo_detail = function(saleinfo_id){
    	var url = that.get_host_conects()['host'] + "course/lecture/detail?sale_info_id="+saleinfo_id+'&return_url='+window.location.href;
    	var a = document.createElement('a');
    	a.id = 'tmp';
    	a.href = url;
    	a.target = '_blank';
        a.click();
    }
    // 영상 플레이
    that.play_movie = function (freemovie_id, movie_url){
        // view count up
        if (freemovie_id) {
            $.ajax({
                url: '/api/free_content/lecture/inc_freemovie_view_count?freemovie_id=' + freemovie_id,
                type: 'post',
                data: {},
                success: function (data) {
                }
            });
        }

        if (movie_url.indexOf('myclass.dangi.co.kr/player/kollus/open') >= 0) {
            open_win(movie_url, "play_movie", 1400, 800);
        } else {
            var url = that.get_host_myclass()['host'] + "player/free/common/product?full_movie_url="+ movie_url;
            open_win(url, "play_movie", 1400, 800);
        }
    }
    that.play_movie_wiframe_onmyclass = function (tag_id, movie_url, playimg){
        target = $('#' + tag_id);
        width = target.width();
        height = target.height();

        url = that.get_host_myclass()['host'] + "player/free/iframe/product?full_movie_url="+ movie_url +"&autoplay=Y&playimg="+playimg+"&width="+width+"&height="+height;
        $('#'+ tag_id).html("<iframe width='"+width+"' height='"+height+"' src='"+url+"' border='0' frameborder='0' scrolling='no'></iframe>");
    }
    that.play_movie_wiframe = function (tag_id, movie_url){
        target = $('#'+tag_id);
        var ifrm = document.createElement("iframe");
        ifrm.setAttribute("src", movie_url);
        ifrm.style.width = target.width() + "px";
        ifrm.style.height = target.height() + "px";
        target.html(ifrm);
        return;
    }
    //유의사항 호출 함수
    that.notice_call = function (class_name,device_type){
        var url_info = document.location.href.split("://");
        
        $.post("/notice_information/main",{url:url_info[1],device_type:device_type},function(data){
            $("."+class_name).html(data);
        });
    }
    // 상품 주문 : 예제 order_products([9275,4618]) 
    that.order_products = function (saleinfo_ids) {
        $.ajax({
            url: '/api/member/whoami',
            type: 'post',
            data: {},
            success: function (data) {
                if (data.is_login) {
                    /** form submit **/
                    if ($('form[name=order]')) $('form[name=order]').remove();
                    var url = that.get_host_myclass()['shost'] + 'payment/order/add_multiple';
                    var myform = document.createElement("form");
                    myform.name = 'order';
                    myform.action = url;
                    myform.method = "post";

                    saleinfo_ids.forEach(function(saleinfo_id, i){
                        var i = document.createElement("input"); //input element, text
                        i.setAttribute('type',"text");
                        i.setAttribute('name',"saleinfo_id[]");
                        i.setAttribute('value', saleinfo_id);
                        myform.appendChild(i);
                    });
                    document.body.appendChild(myform);
                    myform.submit();
                } else {
                    // window.location = 'https://member.conects.com/member/login?redirect_url=' + window.location.href;
                    window.location = that.get_host_member()['shost'] + 'member/login?redirect_url=' + window.location.href;
                }
            }
        });
    }
    
    that.order_products_bytag = function (){
        $.ajax({
            url: '/api/member/whoami',
            type: 'post',
            data: {},
            success: function (data) {
                if (data.is_login) {
                    /** form submit **/
                    var items = $('input[name^=saleinfo_id]:checked');
                    if (items.length < 1) {
                        alert('구매할 상품을 선택해주세요.');
                        return;
                    }

                    var url = that.get_host_myclass()['shost'] + 'payment/order/add_multiple';

                    window.f = document.createElement("form");
                    f.setAttribute('method',"post");
                    f.setAttribute('action', url);

                    items.each(function(i, item){
                        var i = document.createElement("input"); //input element, text
                        i.setAttribute('type',"text");
                        i.setAttribute('name',"saleinfo_id[]");
                        i.setAttribute('value', $(item).attr('value'));
                        f.appendChild(i);
                    });
                    document.body.appendChild(f);
                    f.submit();
                } else {
                    // window.location = 'https://member.conects.com/member/login?redirect_url=' + window.location.href;
                    window.location = that.get_host_member()['shost'] + 'member/login?redirect_url=' + window.location.href;
                }
            }
        });
    }
    // 상품 담기 : 예제 cart_products([9275,4618])
    that.cart_products = function (saleinfo_ids) {
        $.ajax({
            url: '/api/member/whoami',
            type: 'post',
            data: {},
            success: function (data) {
                if (data.is_login) {
                    var items = $('input[name^=saleinfo_id]:checked');

                    if ($('iframe[name=mycart]')) $('iframe[name=mycart]').remove();
                    var myform = document.createElement('form');
                    myform.name = 'mycart';
                    myform.action = that.get_host_myclass()['shost'] + 'payment/cart/add_multiple';;
                    myform.target = "cart";
                    myform.method = "post";
                    myform.style.display = 'none';

                    saleinfo_ids.forEach(function(saleinfo_id, i){
                        var i = document.createElement("input"); //input element, text
                        i.setAttribute('type',"text");
                        i.setAttribute('name',"saleinfo_id[]");
                        i.setAttribute('value', saleinfo_id);
                        myform.appendChild(i);
                    });

                    if ($('iframe[name=cart]')) $('iframe[name=cart]').remove();
                    var iframe = document.createElement('iframe');
                    iframe.name = 'cart';
                    iframe.style.display = 'none';
                    document.body.appendChild(iframe);
                    document.body.appendChild(myform);
                    myform.submit();
                    
                    if (confirm('선택하신 상품이 장바구니에 담겼습니다. 장바구니로 이동하시겠습니까?')) {
                        url = that.get_host_myclass()['shost'] + 'payment/cart';
                        window.top.location.href = url;
                    }
                } else {
                    // window.location = 'https://member.conects.com/member/login?redirect_url=' + window.location.href;
                    window.location = that.get_host_member()['shost'] + 'member/login?redirect_url=' + window.location.href;
                }
            }
        });
    }
    that.cart_products_bytag = function (){
        $.ajax({
            url: '/api/member/whoami',
            type: 'post',
            data: {},
            success: function (data) {
                if (data.is_login) {
                    var items = $('input[name^=saleinfo_id]:checked');

                    if ($('iframe[name=mycart]')) $('iframe[name=mycart]').remove();
                    var myform = document.createElement('form');
                    myform.name = 'mycart';
                    myform.action = that.get_host_myclass()['shost'] + 'payment/cart/add_multiple';;
                    myform.target = "cart";
                    myform.method = "post";
                    myform.style.display = 'none';

                    items.each(function(i, item){
                        var i = document.createElement("input"); //input element, text
                        i.setAttribute('type',"text");
                        i.setAttribute('name',"saleinfo_id[]");
                        i.setAttribute('value', $(item).attr('value'));
                        myform.appendChild(i);
                    });

                    if ($('iframe[name=cart]')) $('iframe[name=cart]').remove();
                    var iframe = document.createElement('iframe');
                    iframe.name = 'cart';
                    iframe.style.display = 'none';
                    document.body.appendChild(iframe);
                    document.body.appendChild(myform);
                    myform.submit();
                    
                    if (confirm('선택하신 상품이 장바구니에 담겼습니다. 장바구니로 이동하시겠습니까?')) {
                        url = that.get_host_myclass()['shost'] + 'payment/cart';
                        window.top.location.href = url;
                    }
                    return;
                } else {
                    // window.location = 'https://member.conects.com/member/login?redirect_url=' + window.location.href;
                    window.location = that.get_host_member()['shost'] + 'member/login?redirect_url=' + window.location.href;
                }
            }
        });
    }
    // 로그인 페이지 이동
    that.goto_login = function (){
        // location.href = that.get_host_member()['shost'] + "member/login?redirect_url="+location.href;
        location.href = that.get_host_member()['shost'] + "member/login?redirect_url="+document.URL;
    }
    that.logout = function (){
        location.href = that.get_host_member()['shost'] + "member/logout";
    }
    that.remove_me = function(tag_id){
        $('#' + tag_id).remove();
    }
    that.show_me = function(tag_id){
        $('#' + tag_id).show();
    }
    that.hide_me = function(tag_id){
        $('#' + tag_id).hide();
    }
    that.toggle_me = function(tag_id){
        $('#' + tag_id).toggleClass('on');
    }
    that.test1 = function(){
        return 'test1';
    }
    that.test2 = function(){
        return that.test1();
    }

    // 지적재산권 유저 리스트
    that.bad_user_list = function (){
    	$.ajax({
    		url : '/api/test/baduser',
    		data : {},
    		dataType : "json",
    		type : "POST",
    		success : function(r){
//    			console.log(r);
    			$.each(r['users']['result'], function(idx, val){
    				$('.js-bx-id-list').append('<li>'+val['user_id']+'</li>');
    			});
    			setInterval(function(){
    				$('.js-bx-id-list li:first').slideUp('slow',function(){ $(this).appendTo('.js-bx-id-list').css('display','block'); });
    			},3000);
    			return;
    		}
    	});
    }
    
    return that;
}

window.lib_st = create_lib_st();