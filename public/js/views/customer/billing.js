var Billing = function() {
    var form = '#billing-form';
    var saveUrl;
    var $validator;

    function init() {
        $validator = $(form).validate({
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
                address2: {
                    required: false
                },
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
                deliviery_time: {
                    required: true
                },
                notes: {
                    required: false
                }
             },   
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

    function saveInfoAddress() {
        var valid = $(form).valid();
        if(!valid) {
            $validator.focusInvalid();
            return false;
        }

        System.blockUI(form);
        System.lazyLoading( function() {
            System.setAjaxRequest(
                saveUrl,
                $(form).serialize(),
                'PATCH',
                function(res) {
                    System.unblockUI();
                    if (res.success == true) {
                        Alert.success('Success!',res.message);
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
        return true;
    }


    return {
        init: function(settings) {
            saveUrl = settings.billingInfoAddressSaveUrl;
            init();
        },

        saveInfoAddress: function() {
            saveInfoAddress();
        }
    }

}();