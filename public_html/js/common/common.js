var create_lib_st = function() {
    var that = { };
    var url = null;
    
    that.days_of_week = {
        sunday: 0,
        monday: 1,
        tuesday: 2,
        wednesday: 3,
        thursday: 4,
        friday: 5,
        saturday: 6
    };

    that.days_of_week_txt = {
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
    that.validateDay = function (s) {
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

    /* Regex Validation : Phone (xxx-xxxx-xxxx) */
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

    that.removeItemInArray = function(array, element) {
        const index = array.indexOf(element);

        if (index !== -1) {
            array.splice(index, 1);
        }
        return array;
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
        document.cookie = cname + "=" + cvalue + "; " + expires + ";domain=.conects.com;path=/";
    }
    that.deleteCookie = function(cookieName) {
        try {
            var expireDate = new Date();
            expireDate.setFullYear(expireDate.getFullYear() - 2);
            var c_value = "" + ((expireDate==null) ? "":";expires="+expireDate.toUTCString() + ";domain=.conects.com;path=/");
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
    // 구매페이지 이동전 동의받기
    that.confirm_to_purchase = function (saleinfo_id, msg){
        url = that.get_host_myclass()['shost'] + "payment/order/add/"+ saleinfo_id;
        if (confirm(msg)) {
            window.location = url;
        }
    }
    // 로그인 이동전 동의받기
    that.confirm_to_login = function (msg){
        if (confirm(msg)) {
            window.location = that.get_host_member()['shost'] + 'member/login?redirect_url=' + window.location.href;
        }
    }
    //내강의실 url : Needs to chang on site
    that.get_host_myclass = function (){
        var s = document.URL;
        s = s.match("//(.*)global");
        prefix = '';
        if (s && s.length>1) {
            prefix = s[1];
        }
        if (['qa-','dev-','dev.'].indexOf(prefix)>=0) prefix="qa-";
        var data = {};
        data.host = "http://" + prefix + "my.conects.com/";
        data.shost = "https://" + prefix + "my.conects.com/";
        return data;
    }
    // 멤버단기 urll : Needs to chang on site
    that.get_host_member = function (){
        var s = document.URL;
        s = s.match("//(.*)global");
        prefix = '';
        if (s && s.length>1) {
            prefix = s[1];
        }
        if (['qa-','dev-','dev.'].indexOf(prefix)>=0) prefix="qa-";
        var data = {};
        data.host = "http://" + prefix + "member.conects.com/";
        data.shost = "https://" + prefix + "member.conects.com/";
        return data;
    }
    // 커넥츠 urll : Needs to chang on site
    that.get_host_conects = function (){
        var s = document.URL;
        s = s.match("//(.*)global");
        prefix = '';
        if (s && s.length>1) {
            prefix = s[1];
        }
        if (['qa-','dev-','dev.'].indexOf(prefix)>=0) prefix="qa-";
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
    // 상품 상세: Needs to chang on site
    that.open_saleinfo_detail = function(saleinfo_id){
        var url = that.get_host_conects()['host'] + "course/lecture/detail?sale_info_id="+saleinfo_id+'&return_url='+window.location.href;
        var a = document.createElement('a');
        a.id = 'tmp';
        a.href = url;
        a.target = '_blank';
        a.click();
    }
    // 영상 플레이
    that.play_movie_wiframe = function (tag_id, movie_url){
        target = $('#'+tag_id);
        var ifrm = document.createElement("iframe");
        ifrm.setAttribute("src", movie_url);
        ifrm.style.width = target.width() + "px";
        ifrm.style.height = target.height() + "px";
        target.html(ifrm);
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
    // 상품 주문 (모든 name=saleinfo_id INPUT 태그)
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
    that.toggle_me = function(tag_id, target_class){
        target_class = target_class || 'on';
        $('#' + tag_id).toggleClass(target_class);
    }
    that.click_atag = function(url, target){
        target_class = target_class || '_self'; // _blank, _self
        var a = document.createElement("a");
        a.setAttribute("target",  target);
        a.setAttribute("href", url);
        a.click();
    }

    // HTML 삽입(유의사항): Needs to chang on site
    that.snippet_html = function (){
        var url_info = document.location.href.split("://");
        var data = {};
        data.pagepath = url_info[1];
        data.url_main_page = window.location.pathname;
        $.ajax({
            url: '/api/content/main/html_injector',
            type: 'post',
            data: data,
            success: function (data) {
                _.each(data.contents, function(content){
                    // inject html
                    if (content.position_el_selector.length > 3) $(content.position_el_selector).html(content.desc_main);
                });
            }
        });
    }
    // 페이지 플로팅 배너 달기
    that.snippet_floating_banner = function(){
        var data = {};
        data.url_main_page = window.location.pathname;
        $.ajax({
            url: '/api/content/main/html_injector_floating_banner',
            type: 'post',
            data: data,
            success: function (data) {
                _.each(data.contents, function(content){
                    $('body').append(content.html);
                });
            }
        });
    }
    // 페이지 딥팝업 달기
    that.snippet_page_dimpopup = function(){
        var data = {};
        data.url_main_page = window.location.pathname;
        $.ajax({
            url: '/api/content/main/html_injector_page_dimpopup',
            type: 'post',
            data: data,
            success: function (data) {
                $('#' + data.tag_id).remove();
                if (lib_st.getCookie(data.tag_id) == 'y') {

                } else {
                    $('body').append(data.html);
                }
            }
        });
    }
    // 페이지 띠배너 달기
    that.snippet_page_linebanner = function(){
        var data = {};
        data.url_main_page = window.location.pathname;
        $.ajax({
            url: '/api/content/main/html_injector_page_linebanner',
            type: 'post',
            data: data,
            success: function (data) {
                $('body').append(data.html);
            }
        });
    }

    /**
     * 한줄 게시판 삽입: Needs to chang on site
     * @param  {int} tag_id   태그 아이디
     * @param  {int} comment_config_id 그룹 아이디
     * @param  {array} options 추가 변수
     * @return {void}          html 삽입
     */
    that.snippet_comment_list = function(tag_id, comment_config_id, options) {
        var data = {};
        data.comment_config_id = comment_config_id;
        data.render_type = 'json';
        
        Object.keys(options).forEach(function(key) { data[key] = options[key]; });

        $.ajax({
            url: '/comment/oneline/page',
            type: 'post',
            data: data,
            success: function (data) {
                $('#'+tag_id).html(data.html);
            }
        });
    }
    
    return that;
}

window.lib_st = create_lib_st();

$(document).ready(function(){
    // 서비스 페이지인 경우
    if (window.location.protocol == 'http:') {
        lib_st.snippet_floating_banner();
        lib_st.snippet_html();
        lib_st.snippet_page_dimpopup();
        lib_st.snippet_page_linebanner();
    }
});
