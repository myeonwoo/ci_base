/* 퀵메뉴 */
(function (){
	$.fn.quickMenu = function(options){
		//초기값
		var defaults = {
			FIXED_CLS: 'ban_fixed',
			ABSOLUTE_CLS: 'ban_absolute',
			FIXED_SIZE :1462
		};
		//초기값 옵션 배열 저장
		var options = $.extend({}, defaults, options);

		var $el = $(this);//부모 클래스
		var scqTop,scqLeft = 0;
		
		var filter = "win16|win32|win64|mac|macintel";

		if( $('body').find('*').hasClass(options.FIXED_CLS) ){
			var ban_fixed_top1 = $el.find('.'+options.FIXED_CLS).offset().top //배너와 top의 거리
			var ban_fixed_top2 = $el.find('.'+options.FIXED_CLS).position().top //배너와 top의 거리
		};
		if( $('body').find('*').hasClass(options.ABSOLUTE_CLS) ){
			var ban_absolute_top1 = $el.find('.'+options.ABSOLUTE_CLS).offset().top //배너와 top의 거리
			var ban_absolute_top2 = $el.find('.'+options.ABSOLUTE_CLS).position().top //배너와 top의 거리
		};

		$(window).on('resize scroll',function(){
			scqTop = $(this).scrollTop();
			scqLeft = $(this).scrollLeft();

			if(navigator.platform){
				if( filter.indexOf(navigator.platform.toLowerCase()) < 0 ){
					//mobile

					//포지션 엡솔루트용 시작
					if( scqTop <= ban_fixed_top1 && $('body').find('*').hasClass(options.FIXED_CLS) ){
						$el.find('.'+options.FIXED_CLS).stop().animate({ top:ban_fixed_top2 },300);
					}else{
						$el.find('.'+options.FIXED_CLS).stop().animate({ top:scqTop-ban_fixed_top1+ban_fixed_top2+10 },300);
					};
					//포지션 엡솔루트용 끝
				}else{
					//pc

					//포지션 픽스용 시작
					if(scqTop <= ban_fixed_top1 && $('body').find('*').hasClass(options.FIXED_CLS) ){
						$el.find('.'+options.FIXED_CLS).removeAttr('style').removeClass('ban_fixed_x').removeClass('ban_fixed_on');
					}else{
						var w_width = $(window).width(); //브라우져 가로길이
						var manman = w_width - options.FIXED_SIZE;
						if(w_width <= options.FIXED_SIZE){
							$el.find('.'+options.FIXED_CLS).removeClass('ban_fixed_on').addClass('ban_fixed_x').css({
								'right':(manman+scqLeft-5)
							});
						}else{
							$el.find('.'+options.FIXED_CLS).removeAttr('style').removeClass('ban_fixed_x').addClass('ban_fixed_on');
						};
					};
					//포지션 픽스용 끝
				};
			};

			//포지션 엡솔루트용 시작
			if( scqTop <= ban_absolute_top1 && $('body').find('*').hasClass(options.ABSOLUTE_CLS) ){
				$el.find('.'+options.ABSOLUTE_CLS).stop().animate({ top:ban_absolute_top2 },300);
			}else{
				$el.find('.'+options.ABSOLUTE_CLS).stop().animate({ top:scqTop-ban_absolute_top1+ban_absolute_top2+10 },300);
			};
			//포지션 엡솔루트용 끝

		});
	};
})(jQuery);



$(document).ready(function(){

	//스카이 배너
	$('.ban_wrap').quickMenu({
		FIXED_CLS: 'ban_fixed',
		ABSOLUTE_CLS: 'ban_absolute',
		FIXED_SIZE :Number($('#wrap').css('min-width').replace(/[^-\d\.]/g,'')) + Number($('#wrap').css('padding-left').replace(/[^-\d\.]/g,''))
	});

});