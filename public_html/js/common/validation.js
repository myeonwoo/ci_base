var input_msg = " 입력 해주세요.";
var select_msg = " 선택 해주세요.";


//null check
//class="notnull" 인 요소의 null check 
function null_check(target){
	var result = true;
	$(target).find(".notnull").each(function(idx, obj){
		//값이 없을 경우
		if(!$(obj).attr("disabled") && $(obj).val() == ""){
			var input_name = $(obj).attr("title");
			var msg = input_name;

			if($(obj).attr("type") == "text"){
				msg += input_msg;
			}else{
				msg += select_msg;
			}

			alert(msg);
			$(obj).focus();

			result = false;
			return false;
		}
	});

	return result;
}

//둘중 하나는 null check
//class="notnull_type2" 인 요소 (target)
//target check시 target의 id를 class로 가지는 요소 null check
function null_check_for_select(target){
	var result = true;

	$(target).find(".notnull_type2").each(function(idx, obj){
		if($(obj).is(":checked")){
			var target_class = $(obj).attr("id");
			var input_obj = $("." + target_class);

			//값이 없을 경우
			if($(input_obj).val() == ""){
				var input_name = $(input_obj).attr("title");
				var msg = input_name;

				if($(input_obj).attr("type") == "text"){
					msg += input_msg;
				}else{
					msg += select_msg;
				}

				alert(msg);
				$(input_obj).focus();

				result = false;
				return false;
			}
		}
	});

	return result;
}