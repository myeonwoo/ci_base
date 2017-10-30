/*
 * jXMLTree jQuery Plugin
 * Examples and documentation at: http://roy-jin.appspot.com/jsp/jqueryXmlMenuTreeDemo.jsp
 * Copyright (c) 2010 Roy Jin
 * Version: 2.0.0 (01-10-2010)
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 * Requires: jQuery v1.4.2 or later
 */
(function($){  
	$.fn.xmltree = function(options) {
		var defaults = {  
			xmlUrl: '',
			treePanelId: 'xmlJSTree',
			loadingText: 'Loading...',
			loadingError: 'Error: Loading Error.',
			initialExpanded: false,
			storeState: false,
			view_type: 'tree',
			cur_menu : '',
			adm_level_num : 0, //관리자 levle_num
			toggleSpeed: 400
		};
		
		var options = $.extend(defaults, options);
		
		var CONSTANT_PREFIX = "xmltree_";
		var toggle_hide_status = "HIDE";
		var toggle_show_status = "SHOW";
		
		var container = $(this);
		
		
		// var treePanel = $('<div class="xmlJSTree"></div>');
		var treePanel = $('<ul id="menu" calss="collapse"></ul>');
		if(options.view_type == 'top_menu') treePanel = $('<ul class="nav navbar-nav" ></ul>'); 
		
		var parents = new Array();
		var XML_TREE_KEYWORDS = {
				menuTree : 'menuTree', 
				link : 'link', 
				type : 'type',				
				parentId : 'parentId', 
				linkName : 'linkName',
				linkTarget : 'linkTarget',				
				linkOnClick : 'linkOnClick',
				linkTargetStatus : 'linkTargetStatus',
				parent : 'parent',
				child : 'child',
				parentIcon : 'parentIcon',
				childIcon : 'childIcon',
				linkDefault : 'linkDefault',  //추가 대메뉴의 경우 default link
				memLevel : 'memLevel', //추가 회원 level_num 제한
		};
		
		if(options.xmlUrl == ''){
			alert(options.loadingError);
			return;
		}
		
		//check browser support cookies?
		if(options.storeState){
			setCookie("testCookie", "testCookie");
			if(getCookie("testCookie") == ""){
				//browser doesn't support cooikes
				options.storeState = false;
			}else{
				del_cookie("testCookie");
			}
		}
		
		$.ajax({
			type: "GET",
			url: options.xmlUrl,
			dataType: ($.browser.msie) ? "text" : "xml",
			success: function(data) {wrapTree(data, options.view_type);}
			//,error:function(){alert(options.loadingError);}
		});
		
		function wrapTree(data, view_type){
			//Fix the IE local access xml issue
			//Only Google Chrome cannot access local xml
			//FF, Safari, Opera works fine
			var xml;
			if (typeof data == "string") {
				xml = new ActiveXObject("Microsoft.XMLDOM");
				xml.async = false;
				xml.loadXML(data);
			} else {
		       xml = data;
			}

			container.html(options.loadingText);
			traverseTree($(xml).find(XML_TREE_KEYWORDS.menuTree), view_type);
			container.html('');
			treePanel.appendTo(container);

			//2014-12-05(pdscj007) : Exception Handling
			$('#menu>.panel>ul').removeClass();
			$('#menu>.panel>ul').addClass('collapse');
			$('#menu>.panel>ul').height(0);$('#menu>.panel>a').removeClass();
			$('#menu>.panel>a').addClass('accordion-toggle collapsed');			
			
			if(view_type == 'tree'){
				$('.menu-title').each(function(){
					var isInit = true;
					if(options.storeState){
						var parentId = $(this).attr('id');
						if(isCookieExist(parentId)){
							var status = getCookie(parentId);
							// check cookie status to restore the state when page refresh
							if(status == toggle_hide_status){
								hideChildren(this);
							}else{
								showChildren(this);
							}
							isInit = false;
						}
					}
					if(isInit){
						// expand/collapse based on initialExpanded
						initToggle(this);
					}
				});				
	
				$('.menu-title').bind('click', 
					function(){
						var parentId = $(this).attr('id');
						var status = "";
						if(isCookieExist(parentId)){
							del_cookie(parentId);
						}
						if($(this).hasClass('toggleIconDown')){
							status = toggle_hide_status;
							$(this).addClass('toggleIconUp').removeClass('toggleIconDown');
							//$(this).parent('ul').children().not('div').not('span').slideUp(options.toggleSpeed);
							$(this).parent('div.menu').children('div.sub-menu').slideUp(options.toggleSpeed);
							
						}else{
							status = toggle_show_status;
							$(this).addClass('toggleIconDown').removeClass('toggleIconUp');
							//$(this).parent('ul').children().not('div').not('span').slideDown(options.toggleSpeed);
							$(this).parent('div.menu').children('div.sub-menu').slideDown(options.toggleSpeed);
						}
						//if storeState is true, set show/hide status into the cookie
						if(options.storeState){
							setCookie(parentId, status);
						}
					}
				);
				
				$('.parentToggle').click(function(){
					var closeToggle = $(this).parent('span').siblings('.toggleIcon');
					closeToggle.trigger('click');
				});
				
				if($('#xmltree_'+options.cur_menu).hasClass('toggleIconUp')){//현재 열려있지 않은 경우에만, 현재 메뉴 확장 trigger
					$('#xmltree_'+options.cur_menu).removeClass("toggleIconUp").addClass("toggleIconDown");
					$('#xmltree_'+options.cur_menu).parent().find(".sub-menu").show();
				}
			}
			else if(view_type == 'top_menu'){
				if(!$('#top_xmltree_'+options.cur_menu).hasClass('active')){//현재 active 아닌 경우에만, 현재 메뉴 active
					$('#top_xmltree_'+options.cur_menu).addClass('active');
				}
			}

			//2014-12-05(pdscj007) : Exception Handling
			$('#xmltree_'+options.cur_menu).removeClass();
			$('#xmltree_'+options.cur_menu).addClass('collapse in');
			$('#xmltree_'+options.cur_menu).height('auto');
			$('#xmltree_'+options.cur_menu).parent().filter('a').removeClass();
			$('#xmltree_'+options.cur_menu).parent().filter('a').addClass('accordion-toggle');
		}
		
		function traverseTree(node, view_type){
			node.children().each(function(){
				if($(this).attr(XML_TREE_KEYWORDS.type) == XML_TREE_KEYWORDS.parent){
					buildParentBlock(this, view_type);
					traverseTree($(this), view_type);
				}
				
				if($(this).attr(XML_TREE_KEYWORDS.type) == XML_TREE_KEYWORDS.child && view_type=='tree'){
				//else if($(this).attr(XML_TREE_KEYWORDS.type) == XML_TREE_KEYWORDS.child ){
					buildChildrenBlock(this, view_type);
					return;
				}
				return;
			});
		}
		
		function buildParentBlock(node, view_type){
			var treeData = getXmlTreeData(node);
			var parentId = $(node).attr(XML_TREE_KEYWORDS.parentId);

			//if(options.adm_level_num >= treeData[XML_TREE_KEYWORDS.memLevel]){ // admin level 이 접근 가능인 경우에만 표시
			var treeLevel = treeData[XML_TREE_KEYWORDS.memLevel].split(",");
			if($.inArray(options.adm_level_num,treeLevel) >= 0){ // admin level 이 포함된 경우에만 표시
				
				if(view_type == 'tree'){
					// var parentBlockDiv = $('<div class="menu"></div>');
					var parentBlockDiv = $('<li class="panel"></li>');
					var parentBlockDivTitle = $('<a href="#" data-parent="#menu" data-toggle="collapse" class="accordion-toggle " data-target="#' + CONSTANT_PREFIX + parentId + '" shape="rect"></a>');
					var parentBlockSpan = $('<span class="pull-right"> <i class="icon-angle-left"></i></span>');
					var parentBlockLink = $('<i class=" icon-folder-open-alt"></i>');

					parentBlockDivTitle.append(parentBlockLink);
					parentBlockDivTitle.append(' ' + treeData[XML_TREE_KEYWORDS.linkName]);
					parentBlockDivTitle.append(parentBlockSpan);
					
					var parentBlockBuilder =  parentBlockDiv.append(parentBlockDivTitle);
					
					//Store to parent array
					parents[parentId] = parentBlockBuilder;
					
					if($(node).parent().attr(XML_TREE_KEYWORDS.type) == XML_TREE_KEYWORDS.parent){
						parentBlockBuilder.appendTo(parents[$(node).parent().attr(XML_TREE_KEYWORDS.parentId)]);
					}else{
						parentBlockBuilder.appendTo(treePanel);
					}
				}
				else if(view_type == 'top_menu'){
					var parentBlockLi = $("<li id='top_" + CONSTANT_PREFIX + parentId + "'></li>");
					var parentBlockLink = $("<a>" + treeData[XML_TREE_KEYWORDS.linkName] + "</a>");
					
					
					if(treeData[XML_TREE_KEYWORDS.linkDefault] != ''){
						parentBlockLink.attr('href', treeData[XML_TREE_KEYWORDS.linkDefault]);
					}

                    if(treeData[XML_TREE_KEYWORDS.linkTargetStatus] != ''){
                        parentBlockLink.attr('target', treeData[XML_TREE_KEYWORDS.linkTargetStatus]);
                    }
					
					var parentBlockBuilder =  parentBlockLi.append(parentBlockLink);
					//Store to parent array
					parents[parentId] = parentBlockBuilder;
					parentBlockBuilder.appendTo(treePanel);
				}
			}
		}
		
		function buildChildrenBlock(node, view_type){
				var treeData = getXmlTreeData(node);
				
				if(typeof parents[$(node).parent().attr(XML_TREE_KEYWORDS.parentId)] != 'undefined'){ //parent menu node가 존재시에만 (level 제한으로 비표시인 경우 하위도 모두 비표시한다)
					
					//if(options.adm_level_num >= treeData[XML_TREE_KEYWORDS.memLevel]){ // admin level 이 접근 가능인 경우에만 표시
					var treeLevel = treeData[XML_TREE_KEYWORDS.memLevel].split(",");
					if($.inArray(options.adm_level_num,treeLevel) >= 0){ // admin level 이 포함된 경우에만 표시
						
						if(view_type == 'tree'){
							var childBlockLi = $('<li></li>');
							var childBlockLink = $('<a><i class="icon-angle-right"></i> ' + treeData[XML_TREE_KEYWORDS.linkName] + '</a>');
							if(treeData[XML_TREE_KEYWORDS.linkTarget] != ''){
								childBlockLink.attr('href', treeData[XML_TREE_KEYWORDS.linkTarget]);
							}
							if(treeData[XML_TREE_KEYWORDS.linkTargetStatus] != ''){
								childBlockLink.attr('target', treeData[XML_TREE_KEYWORDS.linkTargetStatus]);
							}
							if(treeData[XML_TREE_KEYWORDS.linkOnClick] != ''){
								//Fix for IE. Fire the click event other   than simply pass the string
								var new_click = new Function(treeData[XML_TREE_KEYWORDS.linkOnClick]);
								childBlockLink.click(new_click);
							}
							
							//Append Children DOM
							childBlockLi.append(childBlockLink);
							// var childBlockBuilder = childBlockDiv.append(childBlockLi);
							if(parents[$(node).parent().attr(XML_TREE_KEYWORDS.parentId)].find('ul').length <1){
								var childBlockDiv = $('<ul class="in" id="'+CONSTANT_PREFIX +treeData[XML_TREE_KEYWORDS.parentId]+'" style="height: auto;"></ul>');
								var childBlockBuilder = childBlockDiv.append(childBlockLi);
								$(childBlockBuilder).appendTo(parents[$(node).parent().attr(XML_TREE_KEYWORDS.parentId)]);
							}
							else{
								$(childBlockLi).appendTo(parents[$(node).parent().attr(XML_TREE_KEYWORDS.parentId)].find('ul'));	
							}
						}
					}
				}
				
		}
		
		function hideChildren(toggle){
			$(toggle).addClass('toggleIconUp').removeClass('toggleIconDown');
			//$(toggle).parent('ul').children().not('div').not('span').hide();
			$(toggle).parent('div.menu').children('div.sub-menu').hide();
		}
		
		function showChildren(toggle){
			$(toggle).addClass('toggleIconDown').removeClass('toggleIconUp');
		}
		
		function initToggle(toggle){
			if(options.initialExpanded){
				showChildren(toggle);
			}else{
				hideChildren(toggle);
			}
		}
		
		function getXmlTreeData(node){
			var treeData = new Array();
			treeData[XML_TREE_KEYWORDS.linkName] = $(node).attr(XML_TREE_KEYWORDS.linkName);
			treeData[XML_TREE_KEYWORDS.linkDefault] = typeof $(node).attr(XML_TREE_KEYWORDS.linkDefault) != 'undefined' ? $(node).attr(XML_TREE_KEYWORDS.linkDefault) : '';
			treeData[XML_TREE_KEYWORDS.linkTarget] = typeof $(node).attr(XML_TREE_KEYWORDS.linkTarget) != 'undefined' ? $(node).attr(XML_TREE_KEYWORDS.linkTarget) : '';
			treeData[XML_TREE_KEYWORDS.linkTargetStatus] = typeof $(node).attr(XML_TREE_KEYWORDS.linkTargetStatus) != 'undefined' ? $(node).attr(XML_TREE_KEYWORDS.linkTargetStatus) : '';
			treeData[XML_TREE_KEYWORDS.linkOnClick] = typeof $(node).attr(XML_TREE_KEYWORDS.linkOnClick) != 'undefined' ? $(node).attr(XML_TREE_KEYWORDS.linkOnClick) : '';
			treeData[XML_TREE_KEYWORDS.parentIcon] = typeof $(node).attr(XML_TREE_KEYWORDS.parentIcon) != 'undefined' ? $(node).attr(XML_TREE_KEYWORDS.parentIcon) : '';
			treeData[XML_TREE_KEYWORDS.childIcon] = typeof $(node).attr(XML_TREE_KEYWORDS.childIcon) != 'undefined' ? $(node).attr(XML_TREE_KEYWORDS.childIcon) : '';
			treeData[XML_TREE_KEYWORDS.memLevel] = typeof $(node).attr(XML_TREE_KEYWORDS.memLevel) != 'undefined' ? $(node).attr(XML_TREE_KEYWORDS.memLevel) : 0;
			treeData[XML_TREE_KEYWORDS.parentId] = typeof $(node).attr(XML_TREE_KEYWORDS.parentId) != 'undefined' ? $(node).attr(XML_TREE_KEYWORDS.parentId) : '';
			
 			return treeData;
		}
		
		function setCookie(c_name, value, expiredays){
			var exdate = new Date();
			exdate.setDate(exdate.getDate() + expiredays);
			document.cookie = c_name + "=" + escape(value) + ((expiredays == null) ? "" : ";expires=" + exdate.toUTCString());
		}
		
		function getCookie(c_name){
			if(document.cookie.length>0){
				c_start = document.cookie.indexOf(c_name + "=");
				if(c_start!=-1){
					c_start = c_start + c_name.length+1;
					c_end=document.cookie.indexOf(";",c_start);
					if(c_end==-1)
						c_end=document.cookie.length;
					return unescape(document.cookie.substring(c_start, c_end));
				}
			}
			return "";
		}
		
		function isCookieExist(c_name){
			var c_value = getCookie(c_name);
			if(c_value == ""){
				return false;
			}
			return true;
		}
		
		function del_cookie(c_name) {
			document.cookie = c_name + '=; expires=Thu, 01-Jan-70 00:00:01 GMT;';
		} 
	}
})(jQuery);