/****
    * @Desc     
    * @param    
*****/
if (!Array.prototype.indexOf) {
    Array.prototype.indexOf = function(searchElement) {
        if (this.length === 0) {
            return -1;
        }
        var n = 0;
        if (arguments.length > 1) {
            n = Number(arguments[1]);
            if (isNaN(n)) {
                n = 0;
            } else if (n !== 0 && n !== Infinity && n !== -Infinity) {
                n = (n > 0 || -1) * Math.floor(Math.abs(n));
            }
        }
        if (n >= this.length) {
            return -1;
        }
        var k = n >= 0 ? n : Math.max(this.length - Math.abs(n), 0);
        while (k < this.length) {
            if (k in this && this[k] === searchElement) {
                return k;
            }
            ++k;
        }
        return -1;
    };
}

if (!Array.prototype.filter) {
    Array.prototype.filter = function(fun/*, thisArg*/) {
        if (this === undefined || this === null) {
            throw new TypeError();
        }

        var t = Object(this);
        var len = t.length >>> 0;
        if (typeof fun !== 'function') {
            throw new TypeError();
        }

        var res = [];
        var thisArg = arguments.length >= 2 ? arguments[1] : void 0;
        for (var i = 0; i < len; i++) {
            if (i in t) {
                var val = t[i];
                if (fun.call(thisArg, val, i, t)) {
                    res.push(val);
                }
            }
        }
        return res;
    };
}

if (!Array.isArray) {
    Array.isArray = function(arg) {
        return Object.prototype.toString.call(arg) === '[object Array]';
    };
}

if (!Array.prototype.every) {
    Array.prototype.every = function(callbackfn, thisArg) {
        'use strict';
        var T, k;
        if (this == null) {
            throw new TypeError('this is null or not defined');
        }
        var O = Object(this);
        var len = O.length >>> 0;
        if (typeof callbackfn !== 'function') {
            throw new TypeError();
        }
        if (arguments.length > 1) {
            T = thisArg;
        }
        k = 0;
        while (k < len) {

            var kValue;

            if (k in O) {
                kValue = O[k];
                var testResult = callbackfn.call(T, kValue, k, O);
                if (!testResult) {
                    return false;
                }
            }
            k++;
        }
        return true;
    };
}

if (!Object.create) {
    Object.create = (function() {
        var Object = function() {};
        return function (prototype) {
            if (arguments.length > 1) {
                throw new Error('Second argument not supported');
            }
            if (typeof prototype != 'object') {
                throw new TypeError('Argument must be an object');
            }
            Object.prototype = prototype;
            var result = new Object();
            Object.prototype = null;
            return result;
        };
    })();
}

if (!Array.prototype.forEach) {
    Array.prototype.forEach = function(fun /*, thisArg */) {
        if (this === void 0 || this === null)
            throw new TypeError();

        var t = Object(this);
        var len = t.length >>> 0;
        if (typeof fun !== "function")
            throw new TypeError();

        var thisArg = arguments.length >= 2 ? arguments[1] : void 0;
        for (var i = 0; i < len; ++i) {
            if (i in t)
                fun.call(thisArg, t[i], i, t);
        }
    };
}

if (!String.prototype.trim) {
    String.prototype.trim = function() {
        return this.replace(/^\s+|\s+$/gm, '');
    };
}

(function() {
    //$http uses onload instead of onreadystatechange. Need shimming as IE8 doesn't have onload.
    if (new XMLHttpRequest().onload === undefined) {
        var orig = XMLHttpRequest.prototype.send;
        XMLHttpRequest.prototype.send = function() {
            var self = this;
            if (!this.onreadystatechange && this.onload) {
                this.onreadystatechange = function() {
                    if (self.readyState === 4) {
                        self.onload();
                    }
                };
            }
            orig.apply(self, arguments);
        };
    }
})();

if (!Date.now) {
    Date.now = function() {
        return new Date().getTime();
    };
}

if (!Function.prototype.bind) {
    Function.prototype.bind = function(oThis) {
        if (typeof this !== "function") {
            throw new TypeError("Function.prototype.bind - what is trying to be bound is not callable");
        }
        var aArgs = Array.prototype.slice.call(arguments, 1),
            fToBind = this,
            fNOP = function() {
            },
            fBound = function() {
                return fToBind.apply(this instanceof fNOP && oThis
                        ? this
                        : oThis,
                    aArgs.concat(Array.prototype.slice.call(arguments)));
            };

        fNOP.prototype = this.prototype;
        fBound.prototype = new fNOP();

        return fBound;
    };
}

if (!Object.keys) {
    Object.keys = function(object) {
        var keys = [];
        for (var o in object) {
            if (object.hasOwnProperty(o)) {
                keys.push(o);
            }
        }
        return keys;
    };
}

if (!Object.getPrototypeOf) {
    Object.getPrototypeOf = function(object) {
        return object.__proto__ || object.constructor.prototype;
    };
}

if (!String.prototype.trim) {
    String.prototype.trim = function () {
        return this.replace(/^\s+|\s+$/g, '');
    };
}


Array.prototype.clean = function(deleteValue) 
{
    for (var i = 0; i < this.length; i++) {
        if (this[i].trim() == deleteValue) {         
            this.splice(i, 1);
            i--;
        }
    }
    return this;
};
Array.prototype.max = function() {
  return Math.max.apply(null, this);
};

Array.prototype.min = function() {
  return Math.min.apply(null, this);
};

if ( typeof String.prototype.trim !== 'function' )
{
    String.prototype.trim = function() {
        return this.replace(/^\s+|\s+$/g, ''); 
    }
}

if (jQuery.fn) {
    jQuery.fn.toCSV = function() {
        var data = $(this).first(); //Only one table
        var csvData = [];
        var tmpArr = [];
        var tmpStr = '';
        data.find("tr").each(function() {
            if($(this).find("th").length) {
                $(this).find("th").each(function() {
                    tmpStr = $(this).text().replace(/"/g, '""');
                    // tmpArr.push("'" + tmpStr + "'");
                    tmpArr.push(tmpStr);
                });
                csvData.push(tmpArr);
            } else {
                tmpArr = [];
                $(this).find("td").each(function() {
                    content = "'" + $(this).text() + "'";
                    tmpArr.push(content);
                    // tmpArr.push(encodeURIComponent(content));
                    // tmpArr.push(decodeURIComponent(content));
                    // tmpArr.push(encodeURI(content));
                    // tmpArr.push(decodeURI(content));

                    // if($(this).text().match(/^-{0,1}\d*\.{0,1}\d+$/)) {
                    //  tmpArr.push('"' + parseFloat($(this).text()) + '"');
                    // } else {
                    //  tmpStr = $(this).text().replace(/"/g, '""');
                    //  tmpArr.push('"' + tmpStr + '"');
                    // }
                });
                // csvData.push(tmpArr.join(','));
                csvData.push(tmpArr);
            }
        });
        // var output = csvData.join('\n');
        console.log(csvData);
        // console.log(output);

        
        console.log('start');
        var csvRows = [];

        for(var i=0, l=csvData.length; i<l; ++i){
            tmp = csvData[i].join(',');
            console.log(tmp);
            csvRows.push(tmp);
        }
        var csvString = csvRows.join("%0A");
        var a         = document.createElement('a');
        // a.href        = 'data:attachment/csv,' + csvString;
        a.href        = 'data:attachment/csv;charset=euc-kr,' + csvString;
        // a.href        = 'data:attachment/csv;charset=utf-8,' + csvString;
        a.target      = '_blank';
        a.download    = 'myFile.csv';

        document.body.appendChild(a);
        a.click();

        return;
        var A = [['n','sqrt(n)']];

        for(var j=1; j<10; ++j){ 
            A.push([j, Math.sqrt(j)]);
        }

        var csvRows = [];

        for(var i=0, l=A.length; i<l; ++i){
            csvRows.push(A[i].join(','));
        }
        var csvString = csvRows.join("%0A");
        var a         = document.createElement('a');
        a.href        = 'data:attachment/csv,' + csvString;
        a.target      = '_blank';
        a.download    = 'myFile.csv';

        document.body.appendChild(a);
        a.click();
        return;
        var uri = 'data:application/csv;charset=UTF-8,' + encodeURIComponent(output);
        var uri = 'data:application/csv;charset=UTF-8,' + output;
        window.open(uri);
    }
}

//
// This is a class for stuff that is used site wide. 
// If you need to override these functions just don't call them
// and include them in the javascript for that page.
//
var site = function() {
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
    // var sep = 'board';
    // that.site_url = url.slice(0, url.indexOf(sep)).join('/') + '/';
    that.site_url = url.slice(0, 3).join('/') + '/index.php/';
    that.base_url = url.slice(0, 3).join('/') + '/';
    // that.base_url = url.slice(0, url.indexOf(sep)).join('/') + '/';

    that.nfs_url = that.site_url;
    if (window.location.host == "www.engdangi.com") that.nfs_url = "http://nfs.engdangi.com/job/index.php/";
    // else that.nfs_url = that.base_url;

    that.bbs_url = 'http://bbs.dangi.co.kr/bbsv2/';
    if (that.base_url.indexOf('dev.') >= 0 || that.base_url.indexOf('qa-') > 0) {
        that.bbs_url = 'http://qa-bbs.dangi.co.kr/bbsv2/';
    }

    var initialCap =  function (field) {
        return field.substr(0, 1).toUpperCase() + field.substr(1);
    }
    that.initialCap = initialCap;

    var refresh = function () {
        window.location = document.URL;
    }
    that.refresh = refresh;

    var visible_process_notice = function() {
        $('#screenMask').css({visibility: "visible"});
        $('#screenMask').css('height',$(document).height());
        $('#screenText').css({visibility: "visible"});
    }
    that.visible_process_notice = visible_process_notice;

    var invisible_process_notice = function() {
        $('#screenMask').css({visibility: "hidden"});
        $('#screenText').css({visibility: "hidden"});

        // $('#screenMask').hide();
        // $('#screenText').hide();
    }
    that.invisible_process_notice = invisible_process_notice;

    /* Regex Validation : Integer */
    var isInteger = function (s) {
        var isInteger_re = /^\s*\d+\s*$/;
        return String(s).search (isInteger_re) != -1;
    };
    that.isInteger = isInteger;

    /* Regex Validation : Day (yyyy-mm-dd) */
    var isDay = function (s) {
        s = String(s);
        var re = /^[0-2][0-9][0-9][0-9]-[0-1][0-9]-[0-3][0-9]$/;
        var ret = re.exec(s);
        if (ret) return true;
        else return false;
    }
    that.isDay = isDay;

    /* Regex Validation : Day (yyyy-mm-dd) */
    var validateEmail = function (s) {
        s = String(s);
        if (s.search(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/) != -1) {
            return true;
        } else 
            return false;
    }
    that.validateEmail = validateEmail;

    /* Regex Validation : Day (yyyy-mm-dd) */
    var validatePhoneNumber = function (s) {
        s = String(s);
        var phoneno = /^\(?([0-9]{3})\)?[-. ]?([0-9]{4})[-. ]?([0-9]{4})$/;
        var ret = phoneno.exec(s);
        if (ret) return true;
        else return false;
    }
    that.validatePhoneNumber = validatePhoneNumber;

    /* Regex Validation : image file name */
    var validateImagename = function (s) {
        s = String(s);
        var imagename = /\.(jpg|JPG|jpeg|JPEG|png|PNG|gif|GIF)$/;
        var ret = imagename.exec(s);
        if (ret) return true;
        else return false;
    }
    that.validateImagename = validateImagename;

    /* Regex Validation : host url */
    var validateUrl = function (s) {
        s = String(s);
        var url = /^((http[s]?|ftp):\/)?\/?([^:\/\s]+)((\/\w+)*\/)([\w\-\.]+[^#?\s]+)(.*)?(#[\w\-]+)?$/;
        var ret = url.exec(s);
        if (ret) return true;
        else return false;
    }
    that.validateUrl = validateUrl;

    var mobilecheck = function() {
        var check = false;
        (function(a,b){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4)))check = true})(navigator.userAgent||navigator.vendor||window.opera);
        return check;
    }
    that.mobilecheck = mobilecheck;

    /* Redirect Http request */
    var submitFORM = function(path, params, method) {
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
    that.submitFORM = submitFORM;

    var getCookie = function (cname) {
        var name = cname + "=";
        var ca = document.cookie.split(';');
        for(var i=0; i<ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0)==' ') c = c.substring(1);
            if (c.indexOf(name) != -1) return c.substring(name.length,c.length);
        }
        return "";
    }
    that.getCookie = getCookie;

    /* Get agreement */
    var getAgreement = function (msg) {
        return confirm (msg);
    }
    that.getAgreement = getAgreement;

    var getThisTuesday = function () {
        var today = new Date();
        var tuseday = that.getNextTuesday(today);
        tuseday.setDate(tuseday.getDate() - 7);
        tuseday.setHours(0);
        tuseday.setMinutes(0);
        tuseday.setSeconds(0);
        return tuseday;
    }
    that.getThisTuesday = getThisTuesday;
    var getNextTuesday = function (d) {
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
    that.getNextTuesday = getNextTuesday;
    var getNextWendseday = function (d) {
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
    that.getNextWendseday = getNextWendseday;

    var getThisMonday = function () {
        var today = new Date();
        var monday = that.getNextMonday(today);
        monday.setDate(monday.getDate() - 7);
        monday.setHours(0);
        monday.setMinutes(0);
        monday.setSeconds(0);
        return monday;
    }
    that.getThisMonday = getThisMonday;
    var getNextMonday = function (d) {
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
    that.getNextMonday = getNextMonday;
    
    var getNextSaturday = function (d) {
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
    that.getNextSaturday = getNextSaturday;
    
    var getNextFriday = function (d) {
        var ref = {0:5, 1:4, 2:3, 3:2, 4:1, 5:7, 6:6};

        d = new Date(d);
        var day = d.getDay();
        var diff = d.getDate() + ref[day];

        var result = new Date(d.setDate(diff));
        result.setHours(0);
        result.setMinutes(0);
        result.setSeconds(0);
        return result;
    }
    that.getNextFriday = getNextFriday;
    

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

    var getDateDiff = function (date1, date2, length) {
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
    that.getDateDiff = getDateDiff;

    var pad = function (n, width, z) {
        z = z || '0';
        n = n + '';
        return n.length >= width ? n : new Array(width - n.length + 1).join(z) + n;
    }
    that.pad = pad;

    var roundNumber = function (num, dec) {
        var result = Math.round(num*Math.pow(10,dec))/Math.pow(10,dec);
        return result;
    }

    that.roundNumber = roundNumber;

    var setCookie = function (cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays*24*60*60*1000));
        var expires = "expires="+d.toUTCString();
        document.cookie = cname + "=" + cvalue + "; " + expires + ";domain=.dangi.co.kr;path=/";
        document.cookie = cname + "=" + cvalue + "; " + expires + ";domain=.conects.com;path=/";
    }
    that.setCookie = setCookie;

    var getCookie = function (cname) {
        var name = cname + "=";
        var ca = document.cookie.split(';');
        for(var i=0; i<ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0)==' ') c = c.substring(1);
            if (c.indexOf(name) != -1) return c.substring(name.length,c.length);
        }
        return "";
    }
    that.getCookie = getCookie;

    var setupClicks =  function () {
        // Helper tool Tips
        $('.qtip').qtip({
            style: { name: 'light', width: { min: 100, max: 200 } },
            show: 'mouseover',
            hide: { delay: 2000 } 
        });
    }

    that.setupClicks = setupClicks;

    // Turn the "Setup Day selectors" into a string
    var daysToString = function () {
        var days = [];
        $('#hidden-forms input').each(function () {
            if($(this).val() != 0) {
                days.push(days_of_week[$(this).attr('name')]);
            }
        });
        return days.join('-');
    }

    that.daysToString = daysToString;

    // Turn the "Setup Day selectors" into a string
    var systemsDaysToString = function () {
        var days = [];
        $('#hidden-forms input').each(function () {
            if($(this).val() != 0) {
                days.push($(this).attr('id').split('-')[1]);
            }
        });
        return days.join('-');
    }

    that.systemsDaysToString = systemsDaysToString;


    // converts a string of days of the week - represented
    // as integers separated by dashes into a textual
    // representation of those days
    // sunday is 0, saturday is 6
    var dayStrToTxt = function(daystr) {
        days = daystr.split('-');
        txtstr='';
        for(i=0; i<days.length; i+=1) {
            txtstr += days_of_week_txt[days[i]];
        }
        return txtstr;
    }

    that.dayStrToTxt = dayStrToTxt;

    var genericAjax = function () {
        // This will make an ajax call and use the page loader to make the call.
        $(".loader").live('click', function () {
            var url = $(this).attr('href');
            $('.ui-state-default').addClass('ui-state-disabled');
            $('#loader-content a').click(function () { return false; });
            $('#action-notice').show();

            $.get(url, function (html) {
            $('#loader-content').html(html);
            $('#action-notice').hide();
            $('.ui-state-default').each(function () {
            if($(this).hasClass('ui-state-disabled'))
            $(this).removeClass('ui-state-disabled');
            });

            if(typeof loaderAfter == 'function')
            loaderAfter(url); 
            });
            return false;

        });
    }
  
    that.genericAjax = genericAjax;

    var setupjQueryUi = function () {

        // JqueryUi Widget Stuff.
        $('.ui-state-default').live('mouseover', function () {
            $(this).addClass('ui-state-hover');
        });

        $('.ui-state-default').live('mouseout', function () { 
            $(this).removeClass('ui-state-hover');
        });

        $('.hover-toggle').hover(function () {
            $(this).removeClass('ui-helper-hidden');  
            }, function () {
                $(this).addClass('ui-helper-hidden');
        });

    }

    that.setupjQueryUi = setupjQueryUi;

    var toUpperFirst = function(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    that.toUpperFirst = toUpperFirst;

    formatDate = function(dateobj) {
        var ds = '';
        var month = dateobj.getMonth()+1;
        var dt = dateobj.getDate();
        if(month < 10)
            ds += '0' + month;
        else
            ds += month;
        ds += '/';
        if(dt < 10)
            ds += '0' + dt;
        else
            ds += dt;
        ds += '/';
        ds += dateobj.getFullYear();
        return ds;
    }
    that.formatDate = formatDate;

    formatDate1 = function(dateobj) {
        var ds = '';
        var month = dateobj.getMonth()+1;
        var dt = dateobj.getDate();
        ds += dateobj.getFullYear();
        ds += '-';
        if(month < 10)
            ds += '0' + month;
        else
            ds += month;
        ds += '-';
        if(dt < 10)
            ds += '0' + dt;
        else
            ds += dt;

        return ds;
    }
  
    that.formatDate1 = formatDate1;

    formatHM = function(dateobj) {
        var str = '';
        var hours = dateobj.getHours();
        var minutes = dateobj.getMinutes();
        if(hours < 10)
            str += '0' + hours;
        else
            str += hours;
        str += ':';
        if(minutes < 10)
            str += '0' + minutes;
        else
            str += minutes;
        return str;
    }
    that.formatHM = formatHM;

    formatHMS = function(dateobj) {
        var str = '';
        var hours = dateobj.getHours();
        var minutes = dateobj.getMinutes();
        var seconds = dateobj.getSeconds();
        if(hours < 10)
            str += '0' + hours;
        else
            str += hours;
        str += ':';
        if(minutes < 10)
            str += '0' + minutes;
        else
            str += minutes;
        return str;
    }
    that.formatHMS = formatHMS;

    //======================
    // 플레이어 관련 함수
    //======================

    //Dev QA Server Check (1 : DEV, 2: QA , 3: Live)
    that.checkDevServer = function (){
        var _domain = document.domain;
        var _is_dev = /(dev)/i;
        var _is_qa = /(qa-)/i;
        var _is_dev_domain_search = _domain.search(_is_dev);
        var _is_qa_domain_search = _domain.search(_is_qa);
        var _result = 0;

        if (_is_dev_domain_search > -1){_result = 1;}
        else if (_is_qa_domain_search> -1){_result = 2;}
        else {_result = 3;}

        return _result;
    }
    
    //무료 플레이어 팝업
    // @param : free_movie_kind - 영상 종류,  
    // @ example : 
    //예측특강 - free_lec_player('special', freemovie_id); -우측 리스트 있음
    //무료강의 - free_lec_player('freemovie', freemovie_id); -우측 리스트 있음
    //HOT ISSUE - free_lec_player('hot', freemovie_id); -우측 리스트 있음
    //모의고사 해설 특강 - free_lec_player('project', freemovie_id); -우측 리스트 있음
    //제품 무료 영상- free_lec_player('product', full_movie_url); -리스트 X
    //무료강의- free_lec_player('free', freemovie_id); -리스트 X
    free_lec_player = function (free_movie_kind, freemovie_id){

        var _checkDevServer = that.checkDevServer();
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
    that.free_lec_player = free_lec_player;

    //Domain Return (ex. test.com  Also engdangi.com)
    that.returnDomain = function (){
        var _domain =document.domain;
        var _domain_replace = /([a-z\d\-]+(?:\.(?:asia|info|name|mobi|com|net|org|biz|tel|xxx|kr|co|so|me|eu|cc|or|pe|ne|re|tv|jp|tw)){1,2})(?::\d{1,5})?(?:\/[^\?]*)?(?:\?.+)?$/i;
        var _result = _domain.replace(_domain_replace,"");

        _result = _domain.replace(_result,"");

        return _result;
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

    that.scrollto = function (id) {
         // var position = $('#' + id).position();
         var position = $('#' + id).offset();
         var top = parseInt(position.top) + 'px';
        $('html, body').animate({
            scrollTop: top
        }, 'fast');
    }
    
    that.sayhi = function (){
        alert('hi');
    }

    return that;
}

window.local_site = site();

$(document).ready(function() {
    //local_site.setupjQueryUi();
    //local_site.genericAjax();
    //local_site.setupClicks();
});

