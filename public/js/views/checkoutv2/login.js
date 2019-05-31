var Login = function() {

    var validator;

    function _init(settings) {
        loginUrl = settings.loginUrl;

        validateLoginFormInputs();
    }

    function validateLoginFormInputs() {
        validator = $(Default.loginForm).validate({
            rules: {
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: true
                }
            },   
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

    function isValidLoginCredentials() {
        var valid = $(Default.loginForm).valid();
        if(!valid) {
            validator.focusInvalid();
            throw 'Please complete login fields.';
        }
        return true;
    }


    function _verify() {
        try {
            isValidLoginCredentials();
            Request.verifyLogin(
                Inputs.getUsername(), 
                Inputs.getPassword()
            );
        }
        catch (error) {
            console.log(error);
        }
    }


    var Request = {
        verifyLogin: function() {
            System.blockUI(Default.loginForm);
            System.lazyLoading( function() {
                System.setAjaxRequest(
                    loginUrl,
                    $(Default.loginForm).serialize(),
                    'POST',
                    function(response) {
                        System.unblockUI();
                        if (response.success === 1) {
                            System.successMessage('Successfully Login.', Default.loginMessageLabel);
                            System.lazyLoading( function() {
                                window.location.reload();
                            });
                        }
                        else {
                            System.errorMessage(response.message, Default.loginMessageLabel);
                        }
                    },
                    function() {
                        System.unblockUI();
                    }
                );
            });
        }
    }

    var Inputs = {
        getUsername: function() {
            return $(Default.loginForm+' input[type="email"]').val();
        },
        getPassword: function() {
            return $(Default.loginForm+' #password').val();
        }
    }

    var Default = {
        loginForm: '#login-form',
        loginMessageLabel: '#login-message'
    }

	return {
		init: _init,
		verify: _verify
	}

}();