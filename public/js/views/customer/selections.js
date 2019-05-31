var Selections = function() {
	var saveSelectionUrl;
	var response = false;

	var validator;
	var noMeals;

	var init = function() {
  		
		var option = 'option_';

		var rules = new Array();

		$(document).find('.lunch-meal-select').each( function() {
			rules[$(this).attr('name')] = 'required';
		});

		$(document).find('.dinner-meal-select').each( function() {
			rules[$(this).attr('name')] = 'required';
		});

		validator = $("#menu-form").validate({
	      	rules: rules,   
	        errorElement: 'em',
	        errorPlacement : function(error, element) {
	            var placement = $(element).data('error');
	            error.addClass( 'invalid-feedback' );

	            if (placement) {
	                $(placement).append(error)
	            } else {
	                element = element.closest('div');
	                error.insertAfter(element);
	            }
	        }
	    });
	}

	var isValid = function() {
		var valid = true;
		$(".menu-form").each( function() {
			if (!isFormValid('#'+$(this).attr('id'), false)) {
				valid = false;
			}
		});
		
		return valid;
	}

	function process() {
		var lunch = new Array();
		var dinner = new Array();
		
		var data = new Array();
		var formObject = Array();
		$(".menu-form").each( function() {
			var _this = $(this);
			if (_this.attr('data-changed') == 1) {
				if (_this.attr('data-id') == '' || _this.attr('data-id') == undefined) {
					return;
				}
				lunch = [];
				dinner = [];
				$(_this).find('.lunch-meal-select').each( function() {
		        	lunch.push($(this).find('option:selected').val())
		        });
		        $(_this).find('.dinner-meal-select').each( function() {
		        	dinner.push($(this).find('option:selected').val())
		        });

		        data.push({
		        	subscriptionId: _this.attr('data-id'),
		        	subscriptionCycleId: _this.attr('data-subcycleid'),
		        	menu: {dinner: JSON.stringify(dinner), lunch: JSON.stringify(lunch)}
		        });

		        formObject.push(_this);
			}
		});
			
		if (data.length <= 0) {
			return System.unblockUI();
		}

		System.blockUI('.tab-content');
		System.lazyLoading( function() {
			System.setAjaxRequest(
	        	saveSelectionUrl,
	        	{'data': data},
	        	'PUT',
	        	function(res) {
	        		System.unblockUI();
	        		if (res.success == true) {
	        			Alert.success('Success!',res.message);
	        			removeChanged();
	        		} else {
	        			Alert.error('Error!',res.message);
	        			$(window).scrollTop(0);
	        		}
	        	},
	        	function() {
	        		System.unblockUI();
	        		$(window).scrollTop(0);
	        		Alert.error('Error!',System.errorTextMessage);
	        	}
	        );
        });

		function removeChanged() {
			for(var i in formObject) {
				formObject[i].removeAttr('data-changed');
			}
		}
	}

	function delivery(id) {
		System.setAjaxRequest(
            url.deliveryTimeUrl+id,
            '',
            'GET',
            function(response) {
                var delivery_timings_id = $('#delivery_zone_timings_id');
                var options = '<option value="">Choose Delivery Time</option>';
                for (var i in response) {
                    options += "<option "+(getCurrentDeliveryZoneTimingId() == response[i].id ? 'selected' : '')+"  "+(response.length <= 1 ? 'selected' : '')+" value='"+response[i].id+"'>"+response[i].date+"</option>";
                }

                delivery_timings_id.html(options);
            }
        );
	}

	return {
		init: function(settings) {
			init();
		},

		process: function() {
			process();
		},

		isValid: function() {
			return isValid();
		},

		getResponse: function() {
			return response;
		},

		setSaveSelectionUrl: function(url) {
			saveSelectionUrl = url;
		}
	}
}();