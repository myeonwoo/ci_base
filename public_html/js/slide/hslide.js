// <style>.hslide {display:none; position:absolute;} .hslide.on {display:block}</style>  <<rollingac 사용을 위해 필요한 스타일값
var overNum_r= new Array();
var rollingac_txt = new Array();	//내용
var rollingac_chk = new Array();	//한번 불러온 내용은 안불러오기 위해.
var roll_cli = new Array();	//너무 빠른 클릭 막기
function rollingacseting(roll_id,rollcon,intervals,use_type){
	//2차 배열에 값넣기 [hslide에 들어갈 값]	
	
	if(use_type == "2"){
		rollingac_txt[roll_id] = rollcon;
	}else{
		rollingac_txt[roll_id] = new Array();
		rollingac_txt[roll_id] =  rollcon.split('&&'); 
	}
	
	rollingac_chk[roll_id] = new Array();
	//클릭 값 초기화
	roll_cli[roll_id] = 0;
	
	var rollcon_txt;
	var roll_idmax = $("#"+roll_id+" .hslide").size();	
	var roll_txt = "<a href=\"javascript:rollingac('-','"+roll_id+"')\" class=\"prev_btn\">이전</a><ul>";
	for(var r_val = 0; r_val < roll_idmax; r_val++){
		if(r_val == 0){
			roll_txt += "<li class=\"on\" onclick=\"javascript:rollingac('+','"+roll_id+"','"+r_val+"')\"></li>";
			$("#"+roll_id+" .hslide:eq("+r_val+")").html(rollingac_txt[roll_id][r_val]);
			rollingac_chk[roll_id][r_val] = 1;
		}else{
			roll_txt += "<li onclick=\"javascript:rollingac('+','"+roll_id+"','"+r_val+"')\"></li>";
		}
		rollingac_chk[roll_id][r_val] = 0;
	}
	roll_txt += "</ul><a href=\"javascript:rollingac('+','"+roll_id+"')\" class=\"next_btn\">다음</a>";

	
	$("#"+roll_id+" .hslide_paging").html(roll_txt);


	//intervals 변수에 값이 있을 경우 
	if(intervals){
		rollingac_txt[roll_id][roll_idmax] = setInterval("rollingac('+','"+roll_id+"')",intervals);

		$("#"+roll_id).mouseover(function(){
			clearInterval(rollingac_txt[roll_id][roll_idmax]);
		}).mouseout(function(){
			rollingac_txt[roll_id][roll_idmax] = setInterval("rollingac('+','"+roll_id+"')", intervals);
		});
	}


}

function rollingac(roll_type,roll_id,rollpage){	
	if(roll_cli[roll_id] < 1){
		
		var roll_idmax = $("#"+roll_id+" .hslide").size();					//id 슬라이드 최대값 
		var roll_idwid = $("#"+roll_id+" .hslide").width();					//hslide 넓이값
	
		if(roll_idmax<2){return;}											//슬라이드 이미지가 하나 이하일때 페이징 안됨.
		if(!overNum_r[roll_id] && !rollpage && roll_type != "-"){rollpage=1;}
		if(overNum_r[roll_id] == rollpage && roll_type != "-"){return;}							//같은 페이지 두번 눌렀을 경우 페이징 멈춤 
		
		
		
		if($.inArray(roll_id, overNum_r) < 0){
			overNum_r.push(roll_id);
			overNum_r[roll_id] = 0;
		}
	
		if(rollpage){
			overNum_r[roll_id] = rollpage;
		}else{
			//보고싶은 슬라이드 -:이전 , + 이후
			if(roll_type == "-"){ 
				overNum_r[roll_id] = parseInt(overNum_r[roll_id]) - 1;
			}else{
				overNum_r[roll_id] = parseInt(overNum_r[roll_id]) + 1;
			}			
		
			if(roll_idmax <= overNum_r[roll_id]) overNum_r[roll_id] = 0;		//현 롤링페이지가 최대값을 넘을경우 0
		
			if(overNum_r[roll_id] < 0) overNum_r[roll_id] = roll_idmax - 1;		//현 롤링페이가 최소값보다 낮은 곳이 호출될 경우 제일 끝 슬라이드 호출	
		}
		
		//pre_slide_on = 전에 가지고 있던 슬라이드 class on 값 위치 
		var pre_slide_on;
		for(var roll_slideon = 0; roll_slideon < roll_idmax; roll_slideon++){
			if($("#"+roll_id+" .hslide:eq("+roll_slideon+")").hasClass("on")){
				pre_slide_on = roll_slideon;
				break;									
			}				
		}

		
		//슬라이드 첫 시작 위치
		if(rollpage < pre_slide_on) roll_type = "-";
		
		if(roll_type == "-"){ 
			$("#"+roll_id+" .hslide:eq("+overNum_r[roll_id]+")").css("left",-roll_idwid + roll_idwid / 10);
		}else{
			$("#"+roll_id+" .hslide:eq("+overNum_r[roll_id]+")").css("left",roll_idwid - roll_idwid / 10);
		}		
		
	
		$("#"+roll_id+" .hslide:eq("+overNum_r[roll_id]+")").css("z-index","10");	//현재 위치값 보다 더 높은 z-index를 줌
		$("#"+roll_id+" .hslide:eq("+overNum_r[roll_id]+")").addClass("on");
		roll_cli[roll_id]++;

		if(rollingac_chk[roll_id][overNum_r[roll_id]] < 1){
		$("#"+roll_id+" .hslide:eq("+overNum_r[roll_id]+")").html(rollingac_txt[roll_id][overNum_r[roll_id]]);		//이미지 뿌려주기
		rollingac_chk[roll_id][overNum_r[roll_id]] = 1;
		}
		
		$("#"+roll_id+" .hslide:eq("+overNum_r[roll_id]+")").stop().animate({left:0},300,function(){
			$("#"+roll_id+" .hslide:eq("+pre_slide_on+")").removeClass("on");
			$("#"+roll_id+" .hslide:eq("+overNum_r[roll_id]+")").css("z-index","5");	//다음 슬라이드를 위해 z-index값 낮춤.
			roll_cli[roll_id] = 0;
		});
	
		//페이징 이미지
		$("#"+roll_id+" .hslide_paging > ul >li").removeClass("on");
		$("#"+roll_id+" .hslide_paging > ul >li:eq("+overNum_r[roll_id]+")").addClass("on");
		
	}else{
		return;
	}
}