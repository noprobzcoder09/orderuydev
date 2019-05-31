var EmailLogin = function() {

    var validator;

    function _init(settings) {
        verifyEmailLoginUrl = settings.verifyEmailLoginUrl;

        validateEmailLoginFormInputs();
    }

    function validateEmailLoginFormInputs() {
        validator = $(Default.form).validate({
            rules: {
                email: {
                    required: true,
                    email: true
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

    function isValidEmailLoginCredentials() {
        var valid = $(Default.form).valid();
        if(!valid) {
            validator.focusInvalid();
            throw 'Please complete login fields.';
        }
        return true;
    }


    function _verify() {
        try {
            isValidEmailLoginCredentials();
            Request.verifyEmailLogin(
                Inputs.getUsername()
            );
        }
        catch (error) {
            console.log(error);
        }
    }


    var Request = {
        verifyEmailLogin: function() { 
            System.blockUI(Default.form);
            System.lazyLoading( function() {
                System.setAjaxRequest(
                    verifyEmailLoginUrl,
                    $(Default.form).serialize(),
                    'GET',
                    function(response) {
                        System.unblockUI();
                        Inputs.setLoginRegisterEmailValue(Inputs.getUsername());
                        if (response === 1) {
                            Element.showLoginForm();
                        }
                        else {
                            Element.showRegistrationForm();
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
            return $(Default.form+' input[type="email"]').val();
        },
        setLoginRegisterEmailValue: function(email) {
            Default.loginEmail().val(email);
            Default.registerEmail().val(email);
        }
    }

    var Default = {
        form: '#email-login-form',
        loginForm: '#login-form',
        registrationForm: '#register-form',
        loginMessageLabel: '#login-message',
        loginEmail: function() {
            return $(loginForm+ ' input[type="email"]');
        },
        registerEmail: function() {
            return $(registrationForm+ ' input[type="email"]');
        }
    }

    var Element = {
        showLoginForm: function() {
            $(Default.registrationForm).hide();
            $(Default.form).hide();
            $(Default.loginForm).show();
        },

        showRegistrationForm: function() {
            $(Default.form).hide();
            $(Default.loginForm).hide();
            $(Default.registrationForm).show();
            System.hideMessage(Default.loginMessageLabel);
        }

    }

    return {
        init: _init,
        verify: _verify
    }

}();