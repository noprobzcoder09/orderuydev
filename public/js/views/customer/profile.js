var Profile = function() {

	var updateProfileUrl, updatePasswordUrl;
	var $validator, $validatorPassword;
	function passwordForm() {
		return $('#profile-password');
	}

	function profileForm() {
		return $('#profile-form');
	}

	function container() {
		return '#tab5';
	}

	function updateProfile() {
		if (!isValidProfile()) {
			return false;
		}
		
		if (!needToUpdatePassword()) {
			System.blockUI(container());
			_updateProfile();
			System.unblockUI();
			return;
		}
		
		if (!isValidPassword()) {
			return;
		}

		System.blockUI(container());
		_updateProfile();
		updateAccountPassword();
		System.unblockUI();
	}

	function updateAccountPassword() {
		System.lazyLoading( function() {
			System.setAjaxRequest(
	        	updatePasswordUrl,
	        	passwordForm().serialize(),
	        	'PATCH',
	        	function(res) {
	        		if (res.success == true) {
	        			Alert.success('Success!',res.message);
	        			passwordForm().find('input[type="password"]').val("");
	        		} else {
	        			Alert.error('Error!',res.message);
	        		}
	        	},
	        	function() {
	        		Alert.error('Error!',System.errorTextMessage);
	        	}
	        );
		});
	}

	function _updateProfile() {
		System.lazyLoading( function() {
			System.setAjaxRequest(
	        	updateProfileUrl,
	        	profileForm().serialize(),
	        	'PATCH',
	        	function(res) {
	        		if (res.success == true) {
	        			Alert.success('Success!',res.message);
	        		} else {
	        			Alert.error('Error!',res.message);
	        		}
	        	},
	        	function() {
	        		Alert.error('Error!',System.errorTextMessage);
	        	}
	        );
		});
	}

	function needToUpdatePassword() {
		var i = false;
		passwordForm().find('input[type="password"]').each( function() {
			if ($(this).val() != '') {
				i = true;
			}
		});
		return i;
	}

	function isValidProfile() {
		var valid = profileForm().valid();
        if(!valid) {
            $validator.focusInvalid();
            return false;
        }
        return true;
	}

	function isValidPassword() {
		var valid = passwordForm().valid();
        console.log(valid);
        if(!valid) {
            $validatorPassword.focusInvalid();
            return false;
        }
        return true;
	}

	function init() {
		$validator = profileForm().validate({
            rules: {
                first_name: {
                    required: true
                },
                last_name: {
                    required: true
                },
                mobile_phone: {
                    required: true
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

        $validatorPassword = passwordForm().validate({
            rules: {
                current_password: {
                    required: true
                },
                password: {
                    required: true,
                    minlength: 6,
                },
                confirm_password: {
                    required: true,
                    minlength: 6,
                    equalTo: '#password'
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
			updateProfileUrl = settings.updateProfileUrl;
			updatePasswordUrl = settings.updatePasswordUrl;
			init();
		},
		update: updateProfile
	}

}();