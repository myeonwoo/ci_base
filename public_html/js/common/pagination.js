
window.pagination = (function(){
    
    var that = { };

    var refresh = function () {
        window.location = document.URL;
    }
    that.refresh = refresh;
    
    that.sayhi = function (){
        alert('hi');
    }

    return that;
})();
