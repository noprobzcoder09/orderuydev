var Subscription = function() {
	var Validator, $validator;

	function _billNow() {
		if (!form.isValid()) return;
		System.blockUI(form.id);
		System.lazyLoading( function() {
			form.save(form.getBillNowUrl());
		});
		System.unblockUI();
	}

	function _billAtCutover() {
		if (!form.isValid()) return;
		System.blockUI(form.id);
		System.lazyLoading( function() {
			form.save(form.getBillCutoverUrl());
		});
		System.unblockUI();
	}

	var createSubscriptionUrl, userId;
	var form = {
		id: '#subscription-form',
		setUrl: function(url) {
			createSubscriptionUrl = url;
		},
		setUserId: function(id) {
			userId = id;
		},
		getBillCutoverUrl: function() {
			return createSubscriptionUrl+'-billcutover';
		},
		getBillNowUrl: function() {
			return createSubscriptionUrl+'-billnow';
		},
		data: function() {
			return $(form.id).serialize()+'&userId='+userId+'&coupons='+this.coupons();
		},
		coupons: function() {
			return $('#coupons').val()
		},
		init: function() {
			$validator = $(this.id).validate({
                rules: {
                    meal_plans_id: 'required',
                    card_id: 'required'
                },
                messages: {
                    meal_plans_id: {
                        required: 'Please select a meal plan.',
                    },
                }
            });
		},
		isValid: function() {
			var valid = $(this.id).valid();
	        if(!valid) {
	            $validator.focusInvalid();
	            return false;
	        }
	        return true;
		},
		save: function(url) {
			System.setAjaxRequest(
	            url,
	            form.data(),
	            'PUT',
	            function(response) {
	                if (response.code == 200)
	                {
	                    if (response.success == true) {
	                        table.ajax.reload();
	                        pastTable.ajax.reload();
	                        Alert.success('Success!', response.message);
	                        hideAddNewActiveSubscription();
	                        return;
	                    } 
	                } 
	                Alert.error('Error!', response.message);
	            },
	            function(error) {
	                Alert.error('Error!', System.errorTextMessage);
	            }
	        );
		}
	}

	return {
		init: function(settings) {
			form.setUrl(settings.createSubscriptionUrl);
			form.setUserId(settings.userId);
			form.init();
		},

		billNow: _billNow,

		billAtCutover: _billAtCutover,
	}

}();