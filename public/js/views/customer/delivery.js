var Delivery = function() {

	var updateDeliveryUrl;
	var $validator;
	
	function deliveryForm() {
		return $('#delivery-form');
	}

	function container() {
		return '#tab4';
	}

	function _update() {
		if (!isValidForm()) return false;
		System.blockUI(container());
		System.lazyLoading( function() {
			System.setAjaxRequest(
	        	updateDeliveryUrl,
	        	deliveryForm().serialize()+'&location='+$('#delivery_zone_id option:selected').text(),
	        	'PATCH',
	        	function(res) {
	        		System.unblockUI();
	        		if (res.success == true) {
	        			Alert.success('Success!',res.message);
	        			Menu.init();
	        		} else {
	        			Alert.error('Error!',res.message);
	        		}
	        	},
	        	function() {
	        		System.unblockUI();
	        		Alert.error('Error!',System.errorTextMessage);
	        	}
	        );
		});
	}

	function isValidForm() {
		var valid = deliveryForm().valid();
        if(!valid) {
            $validator.focusInvalid();
            return false;
        }
        return true;
	}

	function init() {
		$validator = deliveryForm().validate({
            rules: {
                delivery_zone_id: {
                    required: true
                },
                delivery_zone_timings_id: {
                    required: true
                },
                delivery_notes: {
                    required: false
                }
             },   
            errorPlacement : function(error, element) {
                var placement = $(element).data('error');
                error.addClass( 'invalid-feedback' );
                if(element.attr('name') == 'agree') {
                    element.parent().find('label').attr('style','color: red !important');
                } else {
                    element.attr('style','border: 1px solid red !important;');
                }
            },
            unhighlight: function (element, errorClass, validClass) {
                var element = $(element);
                if(element.attr('name') == 'agree') {
                    element.parent().find('label').removeAttr('style');
                } else {
                    $( element ).removeAttr('style');
                }
            },
            highlight: function (element, errorClass, validClass) {
                var element = $(element);
                if(element.attr('name') == 'agree') {
                    element.parent().find('label').attr('style','color: red !important');
                } else { 
                    element.attr('style','border: 1px solid red !important;');
                }
            }
        });
	}

	return {
		init: function(settings) {
			updateDeliveryUrl = settings.updateDeliveryUrl;
			init();
		},
		update: _update
	}

}();