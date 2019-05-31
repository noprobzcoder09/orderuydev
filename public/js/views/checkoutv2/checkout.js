var Checkout = function() {
	var checkoutUrl, successUrl;
	var validator; 

	function _init(settings) {
		checkoutUrl = settings.checkoutUrl;
        removeOrderPlanUrl = settings.removeOrderPlanUrl;
		cardObject = settings.cardObject;
        successUrl = settings.successUrl;

		validateCheckoutShippingForm();
	}

	function validateCheckoutShippingForm() {
		validator = $(Default.checkoutShippingForm).validate({
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

	function isValidCheckoutShippingForm() {
        var valid = $(Default.checkoutShippingForm).valid();
        if(!valid) {
            validator.focusInvalid();
            throw 'Please complete all required fields.';
        }
        return true;
	}
	
	function isCheckoutCheckboxChecked() {
		const checkoutCheckbox = $(document).find('#checkout-checkbox');
		console.log(checkoutCheckbox.is(':checked'));
		
		if(!checkoutCheckbox.is(':checked')) {
			throw 'Please check the checkbox below to confirm.';
		}
        return true;
    }

	function _saveCheckoutForm() {
		try {
			
			isValidCheckoutShippingForm();
			cardObject.isValidCreditCardDetails();
			isCheckoutCheckboxChecked();
			
            Request.saveCheckoutForm();
        }
        catch (error) {

			if (error == 'Please check the checkbox below to confirm.') {
				Alert.error('Checkbox', error);
			} else {
				console.log(error);
			}
			
		}
	}

    function removeOrder(_this, planId) {
        if (Elements.empty(planId)) return;
        Request.removeOrder($(_this).closest('tr'), planId);
	}
	
	function checkoutCheckbox(deliverZoneTimingsId) {
		const checkoutCheckboxWrapper = $('#checkout-checkbox-wrapper');
		let _url;

		if (deliverZoneTimingsId != '' && typeof deliverZoneTimingsId != 'undefined' && deliverZoneTimingsId != null) {
			_url = url.getDeliveryTimingsCutoffDateTimeByDeliveryZoneTimingsUrl + '/' + deliverZoneTimingsId;
		} else {
			_url = url.getDeliveryTimingsCutoffDateTimeByTimingsUrl + '/' + Config.delivery_timings_id;
		}

		if (parseInt(Config.delivery_timings_id) != 0 || (deliverZoneTimingsId!= '' && deliverZoneTimingsId != '' && typeof deliverZoneTimingsId != 'undefined' && deliverZoneTimingsId != null)) {
			System.setAjaxRequest(
				_url,
				'',
				'GET',
				function(response) {
					checkoutCheckboxWrapper.find('span.cutoff-time').html(response.cutoff_time);
					checkoutCheckboxWrapper.find('span.cutoff-day').html(response.cutoff_day);
					checkoutCheckboxWrapper.find('span.delivery-day').html(response.delivery_day + "'s");
	
					checkoutCheckboxWrapper.show();
				}
			);
		}
		

	}
		
	var Request = {
        saveCheckoutForm: function() {
            System.blockUI(Default.pageContainer);
            System.lazyLoading( function() {

            	var data = $(Default.checkoutShippingForm).serialize()+'&'+$(Default.creditCardForm).serialize()+'&card='+cardObject.getCardId();

                System.setAjaxRequest(
                    checkoutUrl,
                    data,
                    'PUT',
                    function(response) {
                        System.unblockUI();
		        		if (response.code == 200) {
			        		if (response.success == true) {
			        			// System.successMessage(response.message, Default.loginMessageLabel);
			        			System.lazyLoading( function() {
			        				window.location.href = successUrl;
			        			});
			        		} else {
			        			System.errorMessage(response.message, Default.loginMessageLabel);
			        			$(window).scrollTop(0);
			        		}
		        		}
		        		else {
                            console.log(response.code);
		        			$(window).scrollTop(0);
		        			if (response.code == config.responseCodes.authExpired) {
		        				Elements.loginExpired(response.message);
		        			}
		        			if (!inArray(response.code, [config.responseCodes.authExpired])) {
		        				System.errorMessage(response.message, Default.loginMessageLabel);
		        			}
		        		}	
                    },
                    function() {
                        System.unblockUI();
                    }
                );
            });
        },
        removeOrder: function (_this, planId) {
            System.blockUI(_this);
            System.lazyLoading( function() {
                System.setAjaxRequest(
                    removeOrderPlanUrl+'/'+planId,
                    '',
                    'DELETE',
                    function(response) {
                        System.unblockUI();
                        Request.orderSummary();       
                    },
                    function(response) {
                        System.unblockUI();
                        if (response.responseJSON.message != undefined) {
                            System.errorMessage(response.responseJSON.message, Default.loginMessageLabel);
                        } else {
                            System.errorMessage('', Default.loginMessageLabel);
                        }
                    }
                );
            });
        },
        orderSummary: function() {
            orderSummary();
        }
    }
    

	var Default = {
		checkoutShippingForm: '#shipping-form',
		creditCardForm: '#credit-card-form',
		loginForm: '#login-form',
        registrationForm: '#register-form',
        pageContainer: '#checkout-container',
		loginMessageLabel: '#login-message'
	}

	var Elements = {
		loginExpired: function (message) {
			System.errorMessage(
				message+" Click <a onclick='Checkout.showLoginForm()' href='javascript:;'>here</a> to login.", 
				Default.loginMessageLabel
			);
		},
		showLoginForm: function() {
			$(Default.registrationForm).hide();
            $(Default.checkoutShippingForm).hide();
            $(Default.loginForm).show();
		},
        empty: function(value) {
            if (value == '' || value == null || value == undefined) {
                return true;
            }
            return false;
        }
	}

	return {
		init: _init,
		saveCheckoutForm: _saveCheckoutForm,
		showLoginForm: Elements.showLoginForm,
		removeOrder: removeOrder,
		checkoutCheckbox: checkoutCheckbox
	}
}();