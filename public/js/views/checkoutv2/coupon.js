var Coupon = function() {

	var verifyCouponUrl, deletePromoUrl;
	var validator;

	function init() {
        
        validateCouponForm();
	}

    function validateCouponForm() {
        validator = $(Default.couponForm).validate({
            rules: {
                coupon_code: {
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

    function isValidCouponForm() {
        var valid = $(Default.couponForm).valid();
        if(!valid) {
            validator.focusInvalid();
            throw 'Please complete coupon field.';
        }
        return true;
    }

    function storeCouponCode() {
        try {
            isValidCouponForm();
            Request.storeCouponCode();
        }
        catch (error) {
            console.log(error);
        }
    }

    function removePrommo(_this, code) {
        Request.removePrommo($(_this).closest('tr'), code);
    }

    var Request = {
        storeCouponCode: function() {
            System.blockUI(Default.couponForm);
            System.lazyLoading( function() {
                System.setAjaxRequest(
                    verifyCouponUrl,
                    $(Default.couponForm).serialize(),
                    'POST',
                    function(response) {
                        System.unblockUI();
                        Elements.hideError();
                        if (response.success == true) {
                            Elements.couponActionButtonValid();
                            Elements.showAddNewPromoCode();
                            Request.orderSummary();
                        } else {
                            Elements.couponShowError(
                                response.message
                            );
                            Elements.couponActionButtonInvalid();
                        }
                    },
                    function(response) {
                        System.unblockUI();
                        if (response.status == 500) {
                            Elements.couponShowError(
                                response.responseJSON.message
                            );
                        }
                        Elemets.couponActionButtonInvalid();
                    }
                );
            });
        },
        removePrommo: function(_this, code) {
            System.blockUI(_this);
            System.lazyLoading( function() {
                System.setAjaxRequest(
                    deletePromoUrl,
                    {coupon_code: code},
                    'DELETE',
                    function(response) {
                        System.unblockUI();
                        Elements.couponActionButtonValid();
                        Elements.showAddNewPromoCode();
                        Request.orderSummary();
                    },
                    function(response) {
                        System.unblockUI();
                        Alert.error('Error!',response.statusText);
                    },
                    'HTML', true
                );
            });
        },
        orderSummary: function() {
            orderSummary();
        }
    }

    var Inputs = {
        
    }

    var Default = {
        couponForm: '#promo-form',
        loginMessageLabel: '#login-message'
    }

    var Elements = {
        couponShowError: function(message) {
            $('#coupon_error').html(message);
            $('#coupon_error').show();
        },
        couponActionButtonValid: function() {
            $('#coupon_error').hide();
            $('#coupon_code')
                .parent()
                .find('.input-group-prepend span')
                    .removeClass('invalid');
            $('#coupon_code')
                  .parent()
                  .find('.input-group-prepend span')
                        .addClass('verified');
        },
        couponActionButtonInvalid: function() {
            $('#coupon_code')
                  .parent()
                  .find('.input-group-prepend span')
                        .addClass('invalid');
            $('#coupon_code')
                  .parent()
                  .find('.input-group-prepend span')
                        .removeClass('verified');
        },
        addNewPromoCode: function () {
            $('#coupon_code').val('');
            $('#promo-container').show();
            $('#promo-add-container').hide();
            Elements.showCloseContainer();
        },

        showAddNewPromoCode: function () {
            $('#promo-container').hide();
            $('#promo-add-container').show();
            Elements.hideCloseContainer();
        },
        clear: function () {
            $('#coupon_code')
                .parent()
                .find('.input-group-prepend span')
                        .removeClass('invalid');
            $('#coupon_code')
                  .parent()
                  .find('.input-group-prepend span')
                        .removeClass('verified');
        },
        hideError: function() {
            $('#coupon_error').hide();
        },
        hideCloseContainer: function() {
            $('#promo-close-container').hide();
        },
        showCloseContainer: function() {
            $('#promo-close-container').show();
        },
        closePromo: function() {
            Elements.showAddNewPromoCode();
            Elements.hideError();
            Elements.hideCloseContainer();
        }
    }

	return {
		init: function(settings) {
			verifyCouponUrl = settings.verifyCouponUrl;
            promoInputsUrl = settings.promoInputsUrl;
            deletePromoUrl = settings.deletePromoUrl;
			init();
		},
		isValidCouponForm: isValidCouponForm,
		storeCouponCode: storeCouponCode,
        addNewPromoCode: Elements.addNewPromoCode,
        removePrommo: removePrommo,
        closePromo: Elements.closePromo
	}
}();