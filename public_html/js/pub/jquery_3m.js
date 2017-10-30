
$(document).ready(function(){
	
//	$('a').click(function (e) {
//		var tg = $(this).attr('href');
//		if(tg.substr(0,1) == '#'){
//			e.preventDefault();
//			var position = $(tg).offset();
//			$('html,body').animate({scrollTop:position.top},500);
//		}
//		
//	});
//	
//	$('map').find('area').click(function (e) {
//		var tg = $(this).attr('href');
//		if(tg.substr(0,1) == '#'){
//			e.preventDefault();
//			var position = $(tg).offset();
//			$('html,body').animate({scrollTop:position.top},500);
//		}
//		
//	});

});


//팝업 닫기
function popClose(layer_popup){
	$('.'+layer_popup).fadeOut('fast');
};

//팝업 열기 
function popOpen(layer_popup){
	$('.'+layer_popup).fadeIn('fast');
};

/* 퀵메뉴 */
(function (){
   $.fn.quickMenu = function(options){
      //초기값
      var defaults = {
         HEADER_ID: '#header',
		 FIXED_CLS: 'ban_fixed',
		 FIXED_SIZE :1462 
      };
      //초기값 옵션 배열 저장
      var options = $.extend({}, defaults, options);
      var el = $(this);
	  var scqTop = 0;
	  var scqLeft = 0;
		$(window).on('resize scroll',function(){
			var h_height = $(options.HEADER_ID).height(); //헤더 높이
			scqTop = $(this).scrollTop();
			scqLeft = $(this).scrollLeft();
			if(scqTop <= h_height){
				//상단에 붙어있을때 absolute
				el.removeClass(options.FIXED_CLS).removeAttr('style');
			}else{
				//상단에 떨어져있을때 fixed
				el.addClass(options.FIXED_CLS);
				left_scroll();
			}
		}),
		left_scroll = function(){
			var w_width = $(window).width();
			var manman = w_width - options.FIXED_SIZE;
			if(w_width <= options.FIXED_SIZE) el.css({'right' : (manman+scqLeft-5) , 'margin-right' : 5}); //브라우져 창이 1462보다 작은 경우
			else el.removeAttr('style'); //브라우져 창이 1462보다 큰 경우
		},
		
		el.find('a').click(function(e){
			var tg = $(this).attr('href');
		    if(tg.substr(0,1) == '#'){
				e.preventDefault();
				var position = $(tg).offset();
				$('html,body').animate({scrollTop:position.top},500);
			}
		});
   };
})(jQuery);

/* (sec_top_nav) 따라다니는 네비 */
(function (){
	$.fn.moveBar = function(options){

		//기본옵션
		var defaults = {
			FIXED: true,
			CLICK: true,
			FIXED_SIZE :1462,
			FIXED_LEFT : 71,
			SHADOW_HEIGHT: 0,
			MOVE_SPEED: 1000			
		};

		//옵션 연결해주는 함수
		var options = $.extend({}, defaults, options);
		
		win_width = $(window).width();
		var manman = win_width - options.FIXED_SIZE;

		var this_bar = $(this); //자기자신
		var bar_height = $(this).height()-options.SHADOW_HEIGHT; //자기자신 높이
		if ( options.FIXED == false ){ bar_height = 0; } //고정바 높이 변수 0


		if( options.CLICK == true ){
			//마우스 클릭시 해당ID가있는 영역으로 스크롤 이동
			this_bar.find('a').on('click', function(){
				var click_top = $($(this).attr('href')).offset().top; //클릭한 요소 위치 top값 측정
				$('html,body').animate({scrollTop:click_top-bar_height}, options.MOVE_SPEED); //해당 곳으로 스크롤 이동
				return false;
			});
		};

		var this_top = this_bar.offset().top; //스크롤 맨 상단과 자기자신의 거리
		//윈도우 로드후 실행
		$(window).load(function(){
			this_top = this_bar.offset().top; //스크롤 맨 상단과 자기자신의 거리
		
			//배열(하단에 for문 돌려서 배열에 각요소들 top값 및 높이값저장
			var are_top = new Array();
			var are_height = new Array();

			// 스크롤 이벤트
			var scroll_num = 	$(window).on("scroll",function(){
				
					var scTop=$(this).scrollTop(); //스크롤 현재 top값

					if( options.CLICK == true ){
						//포문(바 안에 li갯수 만큼 동작한다
						for (var i = 0; i < this_bar.find('li').length; i++) {
							are_top[i] = $(this_bar.find('li').eq(i).find('a').attr('href')).offset().top;
							are_height[i] = $(this_bar.find('li').eq(i).find('a').attr('href')).height();
							
							if( scTop < are_top[i]-bar_height ){	this_bar.find('li:eq('+i+') a').removeClass('on');	};
							if( scTop >= are_top[i]-bar_height && scTop <= are_top[i]+are_height[i]-bar_height ){
								this_bar.find('li:eq('+i+') a').addClass('on').parent().siblings().find('a').removeClass('on');
							};
						};
					};

				if ( options.FIXED == true ){
					if( scTop >= this_top ){
						//bar 가 브라우져 상단에 다으면 고정시키기
						$('body').css('padding-top',bar_height).css('position','relative');
						this_bar.css({
							'position' : 'fixed',
							'top' : '0',
							'left' : options.FIXED_LEFT,
							'width' : '100%'
						});
						if(win_width <= options.FIXED_SIZE){
							//브라우져 창이 1462보다 작은 경우
							$('body').removeAttr('style');
							this_bar.removeAttr('style');
							bar_height = 0;
						};
					}else{
						//bar 보다 스크롤 올라가면 스타일삭제
						$('body').removeAttr('style');
						this_bar.removeAttr('style');
					};
				};

			});
		});
		//윈도우 로드 펑션 끝

	};
})(jQuery);

/* 토글(유의사항) */
(function (){
   $.fn.secToggle = function(options){
      //초기값
      var defaults = {
         TOGGLE_SPEED: 'slow',
         DIV_CONT:'.cont_box'
      };
      //초기값 옵션 배열 저장
      var options = $.extend({}, defaults, options);
      var el = $(this);
      el.click(function(){
         if(el.parent().find(options.DIV_CONT).css('display')=='block'){
            el.removeClass('on');
            el.parent().find(options.DIV_CONT).slideUp(options.TOGGLE_SPEED);
            //$(this).parent().find(.cont_box).slideUp('slow')
         }else{
            el.addClass('on');
            el.parent().find(options.DIV_CONT).slideDown(options.TOGGLE_SPEED);
         }
      });
   };
})(jQuery);

/* 리스트 자동롤링 */
(function (){
	$.fn.listRolling = function(options){
		var defaults = {
			DELAY: 3000,
			LIST_SPEED: 'slow'
		};

		var options = $.extend({}, defaults, options);

		var list_num = $(this);

		var interval = setInterval(function(){
			list_num.children().eq(0).slideUp(options.LIST_SPEED,function(){ $(this).show().appendTo( list_num ); });
		}, options.DELAY);

	};
})(jQuery);

