var Coupons = function() {

	var verifyCouponUrl, promoInputsUrl, deletePromoUrl;
	var promoForm = '#promo-form';
	var $validator;
	var isValid = true;
	function init() {
            $(document).ready( function() {
                  // validate();
            });
      	$(document).on('bind change', '#coupon_code', function() {
      		// validate();
      	});
	}

	function validate() {
		if ($('#coupon_code').val() == '') {
			isValid = true;
			$('#coupon_error').hide();
			clear();
			return;
		}
            // System.blockUI('#promo-wrapper');
		System.setAjaxRequest(
			verifyCouponUrl,
                  $(promoForm).serialize(),
                  'POST',
                  function(response) {
                        // System.unblockUI();
                  	$('#coupon_error').hide();
                  	if (response.success == true) {
                  		isValid = true;
                              verified();
                              showAddNewPromoCode();
                              Menu.orderSummary();
                  		return true;
                  	}
                        $('#coupon_error').html(response.message);
                        $('#coupon_error').show();
                  	isValid = false;
                  	invalid();
                  	return false;
                  },
                  function(response) {
                  	isValid = false;
                        // System.unblockUI();
                  	if (response.status == 500) {
                  		$('#coupon_error').html(response.responseJSON.message);
                  		$('#coupon_error').show();
                  	}
                  	invalid();
                  	return false;
                  }
		);
	}

      function promoInputs() {
            System.setAjaxRequest(
                  promoInputsUrl,
                  '',
                  'GET',
                  function(response) {
                        $('#promo-wrapper').html(response);
                  },
                  function(response) {
                        $('#promo-wrapper').html(response.responseJSON.message);    
                  },
                  'HTML', true
            );
      }

      function removePrommo(_this, code) {
            System.setAjaxRequest(
                  deletePromoUrl,
                  {coupons: code},
                  'DELETE',
                  function(response) {
                        verified();
                        showAddNewPromoCode();
                        Menu.orderSummary();
                  },
                  function(response) {
                        Alert.error('Error!',response.statusText);
                  },
                  'HTML', true
            );
      }

      function addNewPromoCode() {
            $('#coupon_code').val('');
            $('#promo-container').show();
            $('#promo-add-container').hide();
      }

      function showAddNewPromoCode() {
            $('#promo-container').hide();
            $('#promo-add-container').show();
      }

      function clear() {
            $('#coupon_code')
                .parent()
                .find('.input-group-prepend span')
                        .removeClass('invalid');
            $('#coupon_code')
                  .parent()
                  .find('.input-group-prepend span')
                        .removeClass('verified');
      }

      function verified() {
            $('#coupon_error').hide();
            $('#coupon_code')
                .parent()
                .find('.input-group-prepend span')
                        .removeClass('invalid');
            $('#coupon_code')
                  .parent()
                  .find('.input-group-prepend span')
                        .addClass('verified');
      }

      function invalid() {
            $('#coupon_code')
                  .parent()
                  .find('.input-group-prepend span')
                        .addClass('invalid');
            $('#coupon_code')
                  .parent()
                  .find('.input-group-prepend span')
                        .removeClass('verified');
      }


	return {
		init: function(settings) {
			verifyCouponUrl = settings.verifyCouponUrl;
                  promoInputsUrl = settings.promoInputsUrl;
                  deletePromoUrl = settings.deletePromoUrl;
			init();
		},
		isValid: function() {
			return isValid;
		},

		validate: function() {
			validate();
		},

            promoInputs: function() {
                  promoInputs();
            },

            addNewPromoCode: function() {
                  addNewPromoCode();
            },

            removePrommo: function(_this, code) {
                  removePrommo(_this, code);
            }
	}
}();