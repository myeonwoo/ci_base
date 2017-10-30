function board_list(page, type, mid,sort_index, search_word){
		
		var search = "";
		var search_option = "";
		if(search_word == 'search'){
			search = $("#search").val();
			search_option = $("#search_option").val();
			
			if(search.length <= 0){
				if(search_option != 'all'){
					alert('검색어를 입력해주세요');
					return preventDefaultAction(false);
				}
			}
			

			$("#temp_search").val(search);
			$("#temp_search_option").val(search_option);
				
		}else{
			if($("#temp_search").val().length > 0){
                search = $("#temp_search").val();
           }
            if($("#temp_search_option").val().length > 0){
                search_option = $("#temp_search_option").val();
           }
		}
		
		$.ajax({
            url: "/community/sub/main/board_list/"+page,
            type: "POST",
            data: {
            	type : type
            	, mid : mid
            	, sort_index : sort_index
            	, search : search
            	, search_option : search_option
            },
            success:  function(response){
            	document.getElementById("bbs_content").innerHTML = response;
            	//$('#bbs_content').html(response);
            	var position = $('.bbs').offset();		// 위치값
            	$('html,body').animate({ scrollTop : position.top }, 100);
            }
       });
	}

	function board_view(document_srl, type, page, mid, sort_index, search, teacher_id){
			$.ajax({
	            url: "/community/sub/main/board_view",
	            type: "POST",
	            data: {
	            	document_srl : document_srl
	            	, type : type
	            	, page : page
	            	, mid : mid
	            	, sort_index : sort_index
	            	, search : search
	            	, teacher_id : teacher_id
	            },
	            success:  function(response){
	            	$('#bbs_content').html(response);
	            	var position = $('.bbs').offset();		// 위치값
	            	$('html,body').animate({ scrollTop : position.top }, 100);
	            }
	       });
		       
	}


	function board_write(type, mid, sort_index, search, page, document_srl, teacher_id){

		$.ajax({
            url: "/community/sub/main/board_write",
            type: "POST",
            data: {
            	type : type
            	, mid : mid
            	, sort_index : sort_index
            	, search : search
            	, page : page
            	, document_srl : document_srl
            	, teacher_id : teacher_id
            },
            success:  function(response){
            	$('#bbs_content').html(response);
            	var position = $('.bbs').offset();		// 위치값
            	$('html,body').animate({ scrollTop : position.top }, 100);
            }
       });
	}


	function chang_lectures_list(teacher_id, mid, saleinfo_id){
		if(mid == 'china_teacher_review'){
			$.ajax({
	            url: "/community/sub/main/api_teacher_lectures",
	            type: "POST",
	            data: {
	            	teacher_id : teacher_id
	            },
	            success:  function(response){
					var text = "<option value=\"\" id=\"\">선택</option>";
	                for (var i=0; i < response.list.length; i++){
						var temp = "";
						if(response.list[i].saleinfo_id == saleinfo_id){
							temp = "<option id='"+response.list[i].saleinfo_id+"' value='"+response.list[i].sale_name+"' selected>"+response.list[i].sale_name+"</option>";
						}else{
							temp = "<option id='"+response.list[i].saleinfo_id+"' value='"+response.list[i].sale_name+"' >"+response.list[i].sale_name+"</option>";
						}
	                	text = text + temp;
	                }
	            	$('#lectures_list').html(text);
	            }
	       });
		}
	}

	function board_insert(type, mid, page, search, sort_index, is_admin){
	
		var teacher_id = $("#teacher_list option:selected").attr('id');
		var saleinfo_id = $("#lectures_list option:selected").attr('id');
		var title = $("#title").val();
		var search_priority = $(":input:radio[name=t_check]:checked").val();
		var tmp = "";
		var content = "";
		var ie_version = "";
		
		if(navigator.appVersion.indexOf("MSIE 7") > -1){
			content = $("#content").val();
			$("#ie_version").val("ie7");
		}else{
			tmp = tinymce.get('content');
			content = tmp.getContent();
		}
		
		if(mid == 'china_teacher_review'){
			if(typeof teacher_id == 'undefined'){
				alert("선생님을 선택해주세요.");
				$("#teacher_list").focus();
				return preventDefaultAction(false); 
			}
		}

		if(typeof saleinfo_id == 'undefined' || saleinfo_id == ""){
			if(mid == 'china_teacher_review'){
				alert("강좌명을 선택해주세요.");
				$("#lectures_list").focus();
				return preventDefaultAction(false);
			}else{
				alert("시험 급수를 선택해주세요.");
				$("#lectures_list").focus();
				return preventDefaultAction(false);
			}
		}else{
			$("#saleinfo_id").val(saleinfo_id);
		}

		if(title.length <= 0){
			alert('제목을 작성해주세요.');
			$("#title").focus();
			return preventDefaultAction(false); 
		}
		
		if(is_admin == 1){
			if($(":input:radio[name=t_check]:checked").length <= 0){
				alert('노출분류 설정을 해주세요');
				$(":input:radio[name=t_check]").focus();
				flag = false;
				return preventDefaultAction(flag);
			}
			$("#search_priority").val(search_priority);
		}else{
			$("#search_priority").val('1');
		}
		
		if(content.length <= 0){
			alert('내용을 작성해주세요');
			return preventDefaultAction(false); 
		}

		if(!$("#agree").is(":checked")){
			alert('개인정보 수집 및 활용에 동의해 주십시오.');
			$("#agree").focus();
			return preventDefaultAction(false); 
		}
		
		$("#teacher_id").val(teacher_id);
		document.frm1.submit();
	}

	
	function board_update(type, mid, page, search, sort_index, teacher_id, is_admin){

		if(version_check()){
			var teacher_id = $("#teacher_list option:selected").attr('id');
			var saleinfo_id = $("#lectures_list option:selected").attr('id');
			var search_priority = $(":input:radio[name=t_check]:checked").val();
			var title = $("#title").val();
			var tmp = "";
			var content = "";
			var ie_version = "";
			
			if(navigator.appVersion.indexOf("MSIE 7") > -1){
				content = $("#content").val();
				$("#ie_version").val("ie7");
			}else{
				tmp = tinymce.get('content');
				content = tmp.getContent();
			}
			
			if(teacher_id == ''){
				alert("선생님을 선택해주세요.");
				$("#teacher_list").focus();
				return preventDefaultAction(false); 
			}

			
			if(typeof saleinfo_id == 'undefined' || saleinfo_id == ""){
				if(mid == 'china_teacher_review'){
					alert("강좌명을 선택해주세요.");
					$("#lectures_list").focus();
					return preventDefaultAction(false);
				}
			}else{
				$("#saleinfo_id").val(saleinfo_id);
			}

			if(title.length <= 0){
				alert('제목을 작성해주세요.');
				$("#title").focus();
				return preventDefaultAction(false); 
			}
			
			if(is_admin == 1){
				if($(":input:radio[name=t_check]:checked").length <= 0){
					alert('노출분류 설정을 해주세요');
					$(":input:radio[name=t_check]").focus();
					flag = false;
					return preventDefaultAction(flag);
				}
				$("#search_priority").val(search_priority);
			}else{
				$("#search_priority").val('1');
			}
			
			if(content.length <= 0){
				alert('내용을 작성해주세요');
				return preventDefaultAction(false); 
			}
			
			if(!$("#agree").is(":checked")){
				alert('개인정보 수집 및 활용에 동의해 주십시오.');
				$("#agree").focus();
				return preventDefaultAction(false); 
			}
			
			$("#teacher_id").val(teacher_id);
			
			if(confirm("수정하시겠습니까?")){
				document.frm1.submit();
			}
			
		}else{

			var send_data = new Object();
			var formData = new FormData();

			var teacher_id = $("#teacher_list option:selected").attr('id');
			var saleinfo_id = $("#lectures_list option:selected").attr('id');
			var search_priority = $(":input:radio[name=t_check]:checked").val();
			var title = $("#title").val();
			var tmp = tinymce.get('content');
			var content = tmp.getContent();
			var document_srl;
			var text = "수정하시겠습니까?";;
			
			document_srl = $("#document_srl").val();
			var file1_check = $("#file1_check").val();
			var file2_check = $("#file2_check").val();

			if(teacher_id == ''){
				alert("선생님을 선택해주세요.");
				$("#teacher_list").focus();
				return preventDefaultAction(false); 
			}

			if(typeof saleinfo_id == 'undefined' || saleinfo_id == ""){
				if(mid == 'china_teacher_review'){
					alert("강좌명을 선택해주세요.");
					$("#lectures_list").focus();
					return preventDefaultAction(false);
				}
			}else{
				$("#saleinfo_id").val(saleinfo_id);
				formData.append("saleinfo_id", saleinfo_id);
			}
			
			if(title == ""){
				alert('제목을 입력해주세요');
				$("#title").focus();
				return preventDefaultAction(false);
			}
			
			if(is_admin == 1){
				if($(":input:radio[name=t_check]:checked").length <= 0){
					alert('노출분류 설정을 해주세요');
					$(":input:radio[name=t_check]").focus();
					flag = false;
					return preventDefaultAction(flag);
				}
				formData.append("search_priority", search_priority);
			}else{
				formData.append("search_priority", '1');
			}
			
			if(content == ""){
				alert('내용을 입력해주세요.');
				$("#content").focus();
				return preventDefaultAction(false);
			}
			
			if(!$("#agree").is(":checked")){
				alert('개인정보 수집 및 활용에 동의해 주십시오.');
				$("#agree").focus();
				return preventDefaultAction(false); 
			}
			
			if(file1_check == 1){
				formData.append("file1_srl", $("#file1_srl").val());
			}

			if(file2_check == 1){
				formData.append("file2_srl", $("#file2_srl").val());
			}

			if($("#file1").length > 0){
				var file1 = $("#file1")[0].files[0];
				formData.append("file1", file1);
			}
			
			if($("#file2").length > 0){
				var file2 = $("#file2")[0].files[0];
				formData.append("file2", file2);
			}
				
		
			// - 수강 후기 일 경우 강좌명 앞에 붙는다.
//			var sub_title = $("#lectures_list").val();
//			if($("#lectures_list_check").val() == 1){
//				if(sub_title != ''){
//					title = "["+sub_title+"] "+title;
//				}
//			}
			var saleinfo_id = $("#lectures_list option:selected").attr('id');
			formData.append("saleinfo_id", saleinfo_id);
			formData.append("mid", mid);
			formData.append("teacher_id", teacher_id);
			formData.append("page", page);
			formData.append("search", search);
			formData.append("title", title);
			formData.append("content", content);
			formData.append("document_srl", document_srl);
			
			
			if(confirm(text)){
				$.ajax({
		            url: "/community/sub/main/board_update_ajax",
		            type: "POST",
		            data: formData,
		            async: false,
		            cache: false,
		            contentType: false,
		            processData: false,
		            success:  function(response){
		                 if(response.response == true){
							alert('수정되었습니다.');
							board_view(document_srl, 'view', page, mid, sort_index, search, teacher_id);
		                 }else{
		                	alert('잠시후 다시 이용하여 주십시요.');
							board_view(document_srl, 'view', page, mid, sort_index, search, teacher_id);
		                 }
		            }
		       });
			}
			
		}
	}

	function board_delete(document_srl, mid){
		if(confirm("정말 삭제하시겠습니까?")){

			var file1_srl = null;
			var file2_srl = null;
			
			if($("#file1_check").length > 0){
				file1_srl = $("#file1_check").val();
			}
			if($("#file2_check").length > 0){
				file2_srl = $("#file2_check").val();
			}
			
			$.ajax({
	            url: "/community/sub/main/board_delete",
	            type: "POST",
	            data: {
	            	document_srl : document_srl
	            	, mid : mid
	            	, file1_srl : file1_srl
	            	, file2_srl : file2_srl
	            },
	            success:  function(response){
	            	if(response.error == 0){
	            		$("#temp_search_option").val('');
	                	$("#temp_search").val('');
	                	alert('삭제되었습니다.');
	                	board_list('1', 'list' , mid , 'regdate', '');
	            	}else{
	                	alert('삭제에 실패하였습니다. 잠시후 다시 이용하여 주십시요.');
	            	}
	            }
	       });
			
		}
	}
	
	function comment_save(document_srl , comment_srl, mid, page, teacher_id, sort_index, search){
		
			var tmp = "";
			var content = "";
			var ie_version = "";
			
			if(navigator.appVersion.indexOf("MSIE 7") > -1){
				content = $("#content").val();
				ie_version = "ie7";
			}else{
				tmp = tinymce.get('content');
				content = tmp.getContent();
			}
			
			if(content == ""){
				alert('내용을 입력해주세요.');
				$("#content").focus();
				return preventDefaultAction(false);
			}
			
			$.ajax({
	            url: "/teacher/board/comment_save",
	            type: "POST",
	            data: {
	            	document_srl : document_srl
	            	, comment_srl : comment_srl
	            	, content : content
	            	, mid : mid
	            	, ie_version : ie_version
	            },
	            success:  function(response){
	            	if(response.error == 0){
						alert('답변이 등록되었습니다.');
						board_view(document_srl, 'view', page, mid, sort_index, search, teacher_id);
		 			}else{
			 			alert("에러발생, 관리자에게 문의해주십시요.");
		 			}
	            }
	       });
			
	}
	
	// - 답변삭제
	function comment_delete(document_srl, comment_srl, mid, page, teacher_id, sort_index, search){
		
		if(confirm("답변을 삭제하시겠습니까?")){
			$.ajax({
	            url: "/community/sub/main/comment_delete",
	            type: "POST",
	            data: {
	            	comment_srl : comment_srl
	            	, mid : mid
	            },
	            success:  function(response){
	            	if(response.error == 0){
						alert('답변이 삭제되었습니다.');
						board_view(document_srl, 'view', page, mid, sort_index, search, teacher_id);
		 			}else{
			 			alert("에러발생, 관리자에게 문의해주십시요.");
		 			}
	            }
	       });
		}
	}
	
	function img_delete(id, file_srl){
		if(id == 'file_1_div'){
			$("#"+id).html("<input type=\"file\" id=\"file1\" name=\"file1\">");
	        $("#file1_check").val('1');
	        $("#file1_srl").val(file_srl);
		}else{
			$("#"+id).html("<input type=\"file\" id=\"file2\" name=\"file2\">");
	        $("#file2_check").val('1');
	        $("#file2_srl").val(file_srl);
		}
	}
	
	function preventDefaultAction(rtnValue) {
		if(!rtnValue){
	       	if(typeof event.preventDefault!= 'undefined'){
	       		return false;
	       	}else{
	       		event.returnValue = false; // IE
	       	}
		}
		return rtnValue;
	}

	function version_check(){
		var _ua = navigator.userAgent;
		var rv = -1;
	         
		//IE 11,10,9,8
		var trident = _ua.match(/Trident\/(\d.\d)/i);
		if(trident != null ){
			if(trident[1] == "7.0" ) return false;
			if(trident[1] == "6.0" ) return false;
			if(trident[1] == "5.0" ) return true;
			if(trident[1] == "4.0" ) return true;
		}
	         
		//IE 7...
		if( navigator.appName == 'Microsoft Internet Explorer'){
			return true;
		}
	        
		var agt = _ua.toLowerCase();
		if (agt.indexOf("chrome") != -1) return false;
		if (agt.indexOf("opera") != -1) return false;
		if (agt.indexOf("staroffice") != -1) return false;
		if (agt.indexOf("webtv") != -1) return false;
		if (agt.indexOf("beonex") != -1) return false;
		if (agt.indexOf("chimera") != -1) return false;
		if (agt.indexOf("netpositive") != -1) return false;
		if (agt.indexOf("phoenix") != -1) return false;
		if (agt.indexOf("firefox") != -1) return false;
		if (agt.indexOf("safari") != -1) return false;
		if (agt.indexOf("skipstone") != -1) return false;
		if (agt.indexOf("netscape") != -1) return false;
		if (agt.indexOf("mozilla/5.0") != -1) return false;
		
	}