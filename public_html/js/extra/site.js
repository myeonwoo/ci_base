if(!Array.indexOf){
  Array.prototype.indexOf = function(obj){
    for(var i=0; i<this.length; i++){
      if(this[i]==obj){
        return i;
      }
    }
    return -1;
  }
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

    var roundNumber = function (num, dec) {
        var result = Math.round(num*Math.pow(10,dec))/Math.pow(10,dec);
        return result;
    }

    that.roundNumber = roundNumber;

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

    return that;
}

window.local_site = site();

