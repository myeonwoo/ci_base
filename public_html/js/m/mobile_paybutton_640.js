

/* 구매버튼 */
var PayBtn = {
	SPEED : 200, //옵션선택영역 애니메이션 스피드
	SPEED1DP : 150, //1depth옵션 클릭 시 2depth 상세옵션 애니메이션 스피드
	SPEEDPAY : 100, //총 상품가격 애니메이션 스피드
	SPEED1DPSCROLL : 100, //1depth 클릭시 선택 1depth옵션 포커스 애니메이션 스피드

	init : function(wrap){
		$(wrap).css('position','relative');
		this.listener(wrap);
	},

	listener : function(wrap){
		var _this = this;

		var $wrap = $(wrap);
		var payBtnH = $('.artc_paybutton').innerHeight();

		// 상품신청버튼 또는 총상품가격 클릭 시
		$(document).on('click','.artc_paybutton .btn_purchasing, .artc_paybutton .total_pay',function(){
			if($('.artc_paybutton').hasClass('on')){
				_this.close();
			}else{
				_this.open();
			}

			event.preventDefault();
		});

		// 1depth 클릭 시
		$(document).on('click','.artc_paybutton .option .tit',function(){
			if($(this).hasClass('on')){
				_this.close1dp($(this).next('.list'));
			}else{
				_this.open1dp($(this).next('.list'));
			}

			event.preventDefault();
		});

		// option 클릭 시
		$(document).on('change click','.artc_paybutton .option .rdo',function(){
			_this.slctValue($(this));
		});

		// scroll
		// $(window).on('scroll',function (){
		// 	var p = $(document).scrollTop();
		// 	var demarcationH = $wrap.offset().top + $wrap.innerHeight() - $(window).height() - payBtnH;

		// 	if(p>=demarcationH){
		// 		$('.artc_paybutton').css('position','absolute');
		// 	}else{
		// 		$('.artc_paybutton').css('position','fixed');
		// 	}
		// });
	},

	dim : function(){
		var _this = this;

		if($('.artc_paybutton').hasClass('on')){
			$("body").append('<div class="bg_dim_full_layer"></div>');
			$('html, body').css({
				'position':'relative',
				'height':'100%',
				'overflow':'hidden'
			});
		}else{
			$('.bg_dim_full_layer').remove();
			$('html, body').css({
				'position':'static',
				'height':'auto',
				'overflow':'auto'
			});
		}
	},

	calHeight : function(){
		var _this = this;

		var $o = $('.artc_paybutton');
		var $options = $o.children('.wrap_option');

		if($o.hasClass('pass')){
			var optH = parseInt($(window).height()-$o.children('.wrap_payment').height());
		}else{
			var optH = parseInt($(window).height())
		}

		$options.css('max-height',optH);
	},

	open : function(){
		var _this = this;

		var $o = $('.artc_paybutton');
		var $options = $o.children('.wrap_option');

		_this.calHeight();
		$o.addClass('on');
		$options.stop().slideDown(_this.SPEED);
		// this.dim();
	},

	close : function(){
		var _this = this;

		var $o = $('.artc_paybutton');
		var $options = $o.children('.wrap_option');

		var $bH = $o.children('.btn_purchasing').children('.txt').height();

		$options.stop().slideUp(_this.SPEED, function(){
			$o.removeClass('on');
			// _this.dim();
		});
	},

	open1dp : function($o){
		var _this = this;

		var $option = $o.parent('.option');
		var btnH = $('.artc_paybutton').find('.btn_purchasing').innerHeight();

		$o.stop().slideDown(_this.SPEED1DP, function(){
			$o.parents('.wrap_option').stop().animate({scrollTop:$option.position().top-btnH}, _this.SPEED1DPSCROLL, function(){
				$o.prev('.tit').addClass('on');
			});
		});
	},

	close1dp : function($o){
		var _this = this;

		$o.stop().slideUp(_this.SPEED1DP, function(){
			$o.prev('.tit').removeClass('on');
		});
	},

	slctValue : function($o){
		var _this = this;

		var $option = $o.parents('.option')
		var $tit = $option.children('.tit');
		var slctTxt = $o.next('.lbl').children('.opt').text();

		$option.addClass('selected');
		$tit.text(slctTxt);
		_this.close1dp($tit.next('.list'));
	},

	openPay : function(){
		var _this = this;

		var $o = $('.artc_paybutton');
		var $payment = $o.children('.wrap_payment');

		$payment.stop().slideDown(_this.SPEEDPAY, function(){
			$o.addClass('pass');
			_this.calHeight();
		});
	},

	closePay : function(){
		var _this = this;

		var $o = $('.artc_paybutton');
		var $payment = $o.children('.wrap_payment');

		$payment.stop().stop().slideUp(_this.SPEEDPAY, function(){
			$o.removeClass('pass');
		});
	}
}
