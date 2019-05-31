var Login = function() {

	var auth = false;
	var ajaxResponse = false;
	var emailChecked = false;
	var loginUrl;
	var logoutUrl;
	var accountUrl;
	var verifyEmailUrl;
    var shipppingUrl;
    var signupUrl;
	var loginForm = '#login-form';
	var validator;
    var loginElement = '.login-wrapper';
    var signupElement = '.signup-wrapper';
    var loggedElement = '.logged-wrapper';

	var init = function() {
		validator = $(loginForm).validate({
          rules: {
            email: {
              required: true,
              email: true
            },
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

        eventBinder();
	}

    function eventBinder() {
        $(loginForm).find('input[name="semail"]').bind('input change click', function() {
            $(loginElement).find("input[name='email']").val($(signupElement).find('input[name="semail"]').val());
        });

        $(loginForm).keyup( function(e) {
            if (e.keyCode == 13) {
                $(this).find('.btn-continue').trigger('click');
            }
        });
    }

	var isValid = function() {
		var valid = $(loginForm).valid();
        if(!valid) {
            validator.focusInvalid();
            return false;
        }
        return true;
	}

    var signup = function() {
        validateSignup();
        copyEmailToShipping();
        return ajaxResponse = isValid();
    }

	var login = function() {
        validateLogin();
        
        if (!isValid()) return ajaxResponse = false;

        System.blockUI(loginForm);
		System.setAjaxRequest(
        	loginUrl,
        	$(loginForm).serialize(),
        	'POST',
        	function(response) {
                System.unblockUI();
        		ajaxResponse = false;
        		if (response.success === 1) {
        			ajaxResponse = true;
                    showLoggedContent();
                    getShipping();
                    Cards.loadCards();
                    auth = true;
        		}
        		else {
        			System.errorMessage(response.message, '#login-message');
        		}
        	},
            function() {
                System.unblockUI();
            }
        );
	}

    function copyEmailToShipping() {
        var form = $('#shipping-form');
        form.find("input[name='email']").val($(signupElement).find('input[name="semail"]').val());
    }

	function showLoggedContent() {
    	$('.logged-wrapper').show();
    	$('.login-wrapper').hide();
    }

	function showLoginForm() {
    	$(loginElement).fadeIn();
        $(signupElement).fadeOut();
        $(loginElement).find('input[name="password"]').parent().fadeIn();
    }

    function showSignupForm() {
        $(loginElement).fadeOut();
        $(signupElement).fadeIn();

        $(signupElement).find('input[name="semail"]')
            .val($(loginElement).find('input[name="email"]').val());
    }

    function showLoginDefault() {
        $(loginElement).fadeIn();
        $(signupElement).fadeOut();
        $(loggedElement).fadeOut();
        $(loginElement).find('input[name="password"]').parent().fadeOut();
        emailChecked = false;
    }

    function getShipping() {
        System.setAjaxRequest(
            accountUrl,
            '',
            'GET',
            function(response) {
                if (response != '') {   
                    setShippingDetails(response);
                }
            },
            function() {
                System.unblockUI();
            },'json',false
        );
    }

    function setShippingDetails(data) {
    	var form = $('#shipping-form');
        if (data.details != null) {
            for(var i in data.details) {
                form.find("input[name='"+i+"']").val(data.details[i]);
            }
            
            form.find("select[name='delivery_zone_id']").val(data.details.delivery_zone_id);
            form.find("textarea[name='delivery_notes']").val(data.details.delivery_notes);
            $('myloggedname').text(data.account.name);

            setDeliveryTimings(data.details.delivery_zone_id);
            form.find("select[name='delivery_zone_timings_id']").val(data.details.delivery_zone_timings_id);
        }

        if (data.address != null) {
            for(var i in data.address) {
                form.find("input[name='"+i+"']").val(data.address[i]);
            }
            form.find("select[name='state']").val(data.address.state);
        }
        if (data.account != 'null') {
            form.find("input[name='email']").val(data.account.email);
        }  
    }

	var checkEmail = function() {
        auth = false;
		ajaxResponse = false;
    	System.blockUI(loginForm);
        System.setAjaxRequest(
        	verifyEmailUrl,
        	{email: $(loginForm+' input[type="email"]').val()},
        	'GET',
        	function(response) {
        		System.unblockUI();                
        		if (response === '1') {
        		  	showLoginForm();
        		} else {
                    showSignupForm();
                }
        	},
        	function (error) {
                System.unblockUI();
        		console.log(error);
        	},
        	'html'
        );
	}

    function clearCheckoutForm() {
        var form = $('#shipping-form');

        form.find("select").val('');
        form.find("input[type='text']").val('');
        form.find("input[name='email']").val($(loginForm).find("input[name='email']").val());
    }

    function clearLoginForm() {
        var form = $(loginForm);
        form.find("input[type='password']").val('');
        form.find("input[name='email']").val('');
    }
    
    function validateLogin() {
        addRules(loginForm, {
            password: {
                required: true
            },
            email: {
                required: true,
                email: true
            }
        });

        removeRules(loginForm, ['spassword', 'scpassword', 'semail']);
    }  

    function validateSignup() {
        addRules(loginForm, {
            password: {
                required: true
            },
            spassword: {
                required: true,
                minlength: 6,
                equalTo: '#spassword'
            },
            scpassword: {
                required: true,
                minlength: 6,
                equalTo: '#spassword'
            },
            semail: {
                required: true,
                email: true
            }
        });

        removeRules(loginForm, ['password', 'email']);
    }    

    function showCheckoutPage() {
        passwordVisible = false;
        ajaxResponse = true;
        removeRules(loginForm, {password});
        clearCheckoutForm();
    }
    
    function logout() {
		System.blockUI(loginForm);
		System.setAjaxRequest(
        	logoutUrl,
        	'',
        	'POST',
        	function(response) {
        		System.unblockUI();
        		if (response.success == 1) {
        			auth = false;
        			showLoginForm();
        			clearCheckoutForm();
        			clearLoginForm();
                    showLoginDefault();
                    Cards.loadCards();
        		}
        	},
            function() {
                System.unblockUI();
            }
        );
	}

	function addRules(form, rulesObj){
        for (var item in rulesObj){ 
           $($(form).find('input[name="'+item+'"]')).rules('add', rulesObj[item]);  
        } 
    }

    function removeRules(form, rulesObj){
        for (var item in rulesObj) {
           $($(form).find('input[name="'+item+'"]')).rules('remove', rulesObj[item]);  
        } 
    }

    function gotoLogin() {
        showLoginForm();
        $('.nav.nav-pills li:nth-child(2) > a').trigger('click');
    }

	return {
		init: function(settings) {
			loginUrl = settings.loginUrl;
			logoutUrl = settings.logoutUrl;
			accountUrl = settings.accountUrl;
			verifyEmailUrl = settings.verifyEmailUrl;
            shipppingUrl = settings.shipppingUrl;
            signupUrl = settings.signupUrl;
			init();
		},
		isValid: function() {
			return isValid();
		},
		logout: function() {
			logout();
		},
		checkEmail() {
			checkEmail();
		},
		response: function() {
			return ajaxResponse;
		},
		auth: function() {
			return auth;
		},
		setAuth: function(a) {
			auth = a;
		},
        passwordVisible: function() {
            return passwordVisible;
        },
        getCardId: function() {
            return getCardId();
        },
        showLoginForm: function() {
            showLoginForm();
        },
        gotoLogin: function() {
            gotoLogin();
        },
        getShipping: function() {
            getShipping();
        },
        isLogin: function() {
            if ($(loginElement).is(':visible')) {
                return true;
            }
        },
        emailChecked: function() {
            return emailChecked;
        },
        signup: signup,
        login: login
	}
}();