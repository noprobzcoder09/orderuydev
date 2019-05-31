var Register = function() {

    var validator;

    function _init(settings) {
        registerSessionUrl = settings.registerSessionUrl;

        validateRegisterFormInputs();
    }

    function _showRegistrationForm() {
        Element.showRegistrationForm();
    }

    function _showLoginForm() {
        Element.showLoginForm();
    }

    function validateRegisterFormInputs() {
        validator = $(Default.registerForm).validate({
            rules: {
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: true
                },
                confirm_password: {
                    required: true,
                    equalTo: Default.registerForm+' #password'
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

    function isValidRegisterCredentials() {
        var valid = $(Default.registerForm).valid();
        if(!valid) {
            validator.focusInvalid();
            throw 'Please complete all account registration fields.';
        }
        return true;
    }


    function _saveRegistration() {
        try {
            isValidRegisterCredentials();
            Request.savedRegistration();
        }
        catch (error) {
            console.log(error);
        }
    }


    var Request = {
        savedRegistration: function() {
            System.blockUI(Default.registerForm);
            System.lazyLoading( function() {
                System.setAjaxRequest(
                    registerSessionUrl,
                    $(Default.registerForm).serialize(),
                    'PUT',
                    function(response) {
                        System.unblockUI();
                        if (response.success === true) {
                            System.successMessage(response.message, Default.loginMessageLabel);
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
        registerForm: '#register-form',
        loginForm: '#login-form',
        emailLoginForm: '#email-login-form',
        loginMessageLabel: '#login-message'
    }

    var Element = {
        showRegistrationForm: function() {
            $(Default.loginForm).hide();
            $(Default.emailLoginForm).hide();
            $(Default.registerForm).show();
            System.hideMessage(Default.loginMessageLabel);
        },
        showLoginForm: function() {
            $(Default.loginForm).show();
            $(Default.emailLoginForm).hide();
            $(Default.registerForm).hide();
            System.hideMessage(Default.loginMessageLabel);
        }
    }

    return {
        init: _init,
        saveRegistration: _saveRegistration,
        showRegistrationForm: _showRegistrationForm,
        showLoginForm: _showLoginForm
    }

}();