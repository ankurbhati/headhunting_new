/*jslint forin: true, sloppy: true, unparam: true, vars: true, white: true, nomen: true, plusplus:true */
/*global window, document, jQuery, console */

/*global NotificationFx */
var theme = window.theme || {};

theme.util = (function (window, $) {

    var init,

        _privateMethod,
        _showBarMsg,        
        _returnError,
        _returnSucess,

        property = null;
    _privateMethod = function () {
        return property;
    };
     _showBarMsg= function (sucess, message, ttl, layout, effect,  type, closeCallback) {
        $('.ns-close').trigger('click');
        setTimeout( function() {        
        var notification = new NotificationFx({
            message : sucess ?_returnSucess(message) :_returnError(message),
            layout : layout || 'bar',
            effect : effect|| 'slidetop',
            ttl: ttl||10000,
            type : type||'notice',
            onClose : closeCallback || function(){
            }
        });
        notification.show();
    }, 100 );
    };
  
     _returnError= function (message) {
        return '<p class="meta1 error"><i class="fa fa-exclamation-triangle" aria-hidden="true" id="barErrorMessage"></i> ' + message + '</p>';
    };
     _returnSucess= function (message) {
        return '<span class="icon-green-check"></span><p class="meta1">'+message+'</p>';
    };
    init = function () {
        return _privateMethod();
    };
    return {
        init: init,
        showBarMsg:_showBarMsg
    };

}(this, jQuery));
