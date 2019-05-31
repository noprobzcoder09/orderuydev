var Checkout = function() {
	var shippingForm = '#shipping-form';
	var CDRForm = '#credit-card-form';
	var promoForm = '#promo-form';
	var loginForm = '#login-form';
	var checkoutUrl, successUrl;
	var $validator; 
	var $validatorCDR;
	var ajaxResponse = false;
	var init = function() {
		$validator = $(shippingForm).validate({
          	rules: {
	            email: {
	              	required: true,
	              	email: true
	            },
	            first_name: {
	              	required: true
	            },
	            last_name: {
	              	required: true
	            },
	            mobile_phone: {
	              	required: true
	            },
	            address1: {
	              	required: true
	            },
	            // address2: {
	            //   	required: true
	            // },
	            state: {
	              	required: true
	            },
	            postcode: {
	              	required: true
	            },
	            suburb: {
	              	required: true
	            },
	            delivery_zone_timings_id: {
	              	required: true
	            },
	            delivery_zone_id: {
	              	required: true
	            },
	            deliviery_time: {
	              	required: true
	            },
	            notes: {
	              	required: false
	            }
	         },   
            errorElement: 'em',
            errorPlacement : function(error, element) {
                var placement = $(element).data('error');
                error.addClass( 'invalid-feedback' );

               	element.attr('style','border: 1px solid red !important;');
            },
            unhighlight: function (element, errorClass, validClass) {
		      $( element ).removeAttr('style');
		    },
		    highlight: function (element, errorClass, validClass) {
		      $(element).attr('style','border: 1px solid red !important;');
		    }
        });
	}

	var process = function() {
		var data = $(shippingForm).serialize()+'&'+$(CDRForm).serialize()+'&'+$(promoForm).serialize()+'&card='+Cards.getCardId();
		data += '&auth='+Login.auth();
		data += '&password='+$(loginForm).find('input[name="spassword"]').val();
		data += '&state_desc='+$('#state option:selected').text();
		System.blockUI('#tab3');
		System.lazyLoading( function() {
			System.setAjaxRequest(
	        	checkoutUrl,
	        	data,
	        	'PUT',
	        	function(response) {
	        		System.unblockUI();
	        		if (response.code == 200) {
		        		if (response.success == true) {
		        			System.successMessage(response.message,'#checkout-message');
		        			ajaxResponse = true;
		        			System.lazyLoading( function() {
		        				window.location.href=successUrl;
		        			});
		        		} else {
		        			ajaxResponse = false;
		        			System.errorMessage(response.message,'#checkout-message');
		        			$(window).scrollTop(0);
		        		}
	        		}
	        		else {
	        			ajaxResponse = false;
	        			$(window).scrollTop(0);
	        			if (response.code == config.responseCodes.authExpired) {
	        				loginExpired(response.message);
	        			}
	        			if (!inArray(response.code, [config.responseCodes.authExpired])) {
	        				System.errorMessage(response.message,'#checkout-message');
	        			}
	        		}	        		
	        	},
	        	function(response) {
	        		$(window).scrollTop(0);
	        		System.unblockUI();
	        		if (response.responseJSON.message != undefined) {
	        			System.errorMessage(response.responseJSON.message,'#checkout-message');
	        		} else {
	        			System.errorMessage('','#checkout-message');
	        		}
	        	}
	        );
		});
	}

	var isValid = function() {
		if (Cards.isCheckedNewCard()) {
			return true;
		}
		var valid = $(shippingForm).valid();
        if(!valid) {
            $validator.focusInvalid();
            return false;
        }
        return true;
	}

	function _removeOrder(planId) {
		System.blockUI('#table-order-summary');
		System.lazyLoading( function() {
			System.setAjaxRequest(
	        	removeOrderPlanUrl+'/'+planId,
	        	'',
	        	'DELETE',
	        	function(response) {
	        		System.unblockUI();
	        		 Menu.orderSummary();		
	        	},
	        	function(response) {
	        		System.unblockUI();
	        		if (response.responseJSON.message != undefined) {
	        			System.errorMessage(response.responseJSON.message,'#checkout-message');
	        		} else {
	        			System.errorMessage('','#checkout-message');
	        		}
	        	}
	        );
		});
	}

	function loginExpired(message) {
		System.errorMessage(message+" Click <a onclick='Login.gotoLogin()' href='javascript:;'>here</a> to login.",'#checkout-message');
		Login.setAuth(false);
	}
    
	return {
		init: function(settings) {
			checkoutUrl = settings.checkoutUrl;
			successUrl = settings.successUrl;
			removeOrderPlanUrl = settings.removeOrderPlanUrl;
			init();
		},
		isValid: function() {
			return isValid();
		},
		isValidCRD: function() {
			return isValidCRD();
		},
		process: function() {
			process();
		},
		response: function() {
			return ajaxResponse;
		},
		removeOrder: _removeOrder
	}
}();