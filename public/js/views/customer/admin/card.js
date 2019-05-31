var Card = function() {
    var createdCardUrl;
    var $validatorCDR;
    var CDRForm = '#credit-card-form';
    
    function init() {
        $validatorCDR = $(CDRForm).validate({
            rules: {
                card_name: {
                    required: true
                },
                card_number: {
                    required: true
                },
                card_expiration_date: {
                    required: true
                },
                card_cvc: {
                    required: true
                }
                // card_postcode: 'required'
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

    function isValidCRD() {
        var valid = $(CDRForm).valid();
        if(!valid) {
            $validatorCDR.focusInvalid();
            return false;
        }
        return true;
    }

    function save() {
        if (!isValidCRD()) {
            return false;
        }

        Request.save();
    }

    var Request = {
        save: function() {
            System.blockUI(Element.form);
            System.lazyLoading( function() {
                System.setAjaxRequest(
                    createdCardUrl,
                    Element.form.serialize(),
                    'PUT',
                    function(response) {
                        System.unblockUI();
                        if (response.success == true) {
                            loadCards();
                            Form.clear();
                            System.successMessage(response.message, Element.cardMessageId);
                        } else {
                            System.errorMessage(response.message, Element.cardMessageId);
                        }
                       
                    },
                    function() {
                        System.unblockUI();
                        System.errorMessage(System.errorTextMessage, Element.cardMessageId);
                    }
                );
            });
        }
    }
    
    var Form = {
        name: '#credit-card-form',
        clear: function() {
            Element.form.find('input').val('');
        }
    }

    var Element = {
        form: $(Form.name),
        cardMessageId: '#card-message'
    }

    return {
        init: function(settings) {
            createdCardUrl = settings.createdCardUrl;
            init();
        },
        save: save
        
    }
}();