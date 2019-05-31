'use strickt';

var System = function() {

	var errorTextMessage = "Oops! Something went wrong. Please try again.";

	var hideMessage = function(ref) {
		var _this = ref == '' || ref == undefined ? $('#alert-message') : $(ref);
		var info = _this.find('.alert-info');
		var success = _this.find('.alert-success');
		var danger = _this.find('.alert-danger');
		info.fadeOut();
		danger.fadeOut();
		success.fadeOut();
	}

	var successMessage = function (message, ref) {
		var _this = ref == '' || ref == undefined ? $('#alert-message') : $(ref);
		var info = _this.find('.alert-info');
		var success = _this.find('.alert-success');
		var danger = _this.find('.alert-danger');
		info.fadeOut();
		danger.fadeOut();
		success.fadeIn();
		success.html(message);
	}

	var infoMessage = function (message, ref) {
		var _this = ref == '' || ref == undefined ? $('#alert-message') : $(ref);
		var info = _this.find('.alert-info');
		var success = _this.find('.alert-success');
		var danger = _this.find('.alert-danger');
		info.fadeIn();
		danger.fadeOut();
		success.fadeOut();
		info.html(message);
	}

	var errorMessage = function (message, ref) {
		var _this = ref == '' || ref == undefined ? $('#alert-message') : $(ref);

		var info = _this.find('.alert-info');
		var success = _this.find('.alert-success');
		var danger = _this.find('.alert-danger');
		var message = message == '' || message == undefined ? 'Sorry! something went wrong. Please try again!' : message;
		
		info.fadeOut();
		danger.fadeIn();
		success.fadeOut();
		danger.html(message);
	}

	var progress = function( turnOn ) {
		if ( turnOn ) {
			$('#modal_progress').modal('show');
		} else {
			$('#modal_progress').modal('hide');
		}
	}

	var spinnerEle = '';

	var addSpinner = function (_this,_class) {
		_this
			.addClass('fa')
			.addClass('fa-spinner')
			.addClass('fa-spin')
				.removeClass(_class);
	}

	var setCaption = function (_this, caption) {
		
	}

	var removeSpinner = function (_this,_class) {
		var _class = _class == undefined ? '<i class="fa"></i>' : _class;
		_this
			.removeClass('fa-spinner')
			.removeClass('fa-spin')
				.addClass(_class);
	}

	var changeClass = function(_this, prevClass, newClass) {
		_this
			.removeClass(prevClass)
				.addClass(newClass);
	}


	var setAjaxRequest = function (url, data, type, success, error, dataType, async) {

	    var dataType= dataType == undefined ? 'json' : dataType;

	    var type = type == undefined ? 'POST' : type;

	    var async = async == undefined ? false : async;
	    
	    if (error == undefined || error == '') {
	        error = function(error) {
	            console.log(error);
	        }
	    }

	    $.ajaxSetup({
	    	headers: {
	    		'X-CSRF-TOKEN' : $('meta[name="meta-csrf"]').attr('content')
	    	}
	    })

	    $.ajax({
	        url: url,
	        dataType: dataType,
	        data: data,
	        type: type,
	        async: async,
	        success: success,
	        error: error
	    });
	    return;
	}

	var setAjaxFile = function (url, data, type, success, error, dataType, async) {

	    var dataType= dataType == undefined ? 'json' : dataType;

	    var type = type == undefined ? 'POST' : type;

	    var async = async == undefined ? false : async;
	    
	    if (error == undefined || error == '') {
	        error = function(error) {
	            console.log(error);
	        }
	    }

	    $.ajaxSetup({
	    	headers: {
	    		'X-CSRF-TOKEN' : $('meta[name="meta-csrf"]').attr('content')
	    	}
	    })

	    $.ajax({
	        url: url,
	        dataType: dataType,
	        data: data,
	        type: type,
	        async: async,
	        success: success,
	        error: error,
	        contentType: false,
        	processData: false,
	    });
	    return;
	}

	var blockUIElement = '';
	var blockUI = function(element, message) {
		var message = message == undefined ? '<div class="fa-spinner fa fa-spin fa-lg font-medium-2"></div>' : message;
	    this.blockUIElement = element;
	    $(this.blockUIElement).block({
	        message: message,//'<div class="fa-spinner fa fa-spin font-medium-2"></div>',
	        // timeout: 2000, //unblock after 2 seconds
	        overlayCSS: {
	            backgroundColor: '#fff',
	            opacity: 0.8,
	            cursor: 'wait'
	        },
	        css: {
	            border: 0,
	            padding: 0,
	            backgroundColor: 'transparent'
	        }
	    });
	    console.log('activated');
	}
	
	var unblockUI = function(element, time) {
		var time = time == undefined ? 1000 : time;
		var element = element == undefined ? this.blockUIElement : element;
		System.lazyLoading( function() {
			$(element).unblock({timeout: time});
		},time);
	}

	return {
		init: function() {
			
		},

		eventsInit: function() {			
		},

		progress: function(turnOn) {
			progress(turnOn);
		},

		lazyLoading: function(__callback, _time) {
			var _time = _time == undefined ? 1000 : _time;
			setTimeout( function () {
				__callback();
			},_time);
		},

		addSpinner: function(_this, _class) {
			addSpinner(_this, _class);
		},

		removeSpinner: function(_this, _class) {
			removeSpinner(_this, _class);
		},

		changeClass: function(_this, prevClass, newClass) {
			changeClass(_this, prevClass, newClass);
		},

		setAjaxRequest: function(url, data, type, success, error, dataType, async) {
			setAjaxRequest(url, data, type, success, error, dataType, async);
		},

		setAjaxFile: function(url, data, type, success, error, dataType, async) {
			setAjaxFile(url, data, type, success, error, dataType, async);
		},
		
		successMessage: function(message, el) {
			successMessage(message, el);
		},

		errorMessage: function(message, el) {
			errorMessage(message, el);
		},

		infoMessage: function(message, el) {
			infoMessage(message, el);
		},

		hideMessage: function(el) {
			hideMessage(el);
		},
		blockUI: function(element, message) {
			blockUI(element, message);
		},
		unblockUI: function(element, time) {
			unblockUI(element, time);
		},

		errorTextMessage: errorTextMessage
	}
}();