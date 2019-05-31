var BillingIssue = function() {
	var $validator;
	function validator(formElement) {
        $validator = Global.formElement.validate({
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

	function showCardModal(_this, userId) {
        System.blockUI($(_this).closest('tr'));
        System.lazyLoading( function() {
            System.setAjaxRequest(
                url.cardModalUrl+'/'+userId,
                {},
                'GET',
                function(response) {
                    System.unblockUI();
                    if (response.success == true) {
                        reloadModal(userId, response.content);
                    } else {
                        Alert.error('Error',response.message);    
                    }
                },
                function(error) {
                    System.unblockUI();
                    Alert.error('Error',System.errorTextMessage);
                },
                'JSON',
                true
            );
        });
	}

	function createNewCard() {
        Form.validate(); 
        Alert.confirm(
            'New','Are you sure you want to save this card?',
            'Yes',
            'No',
            function(instance, toast) {
                Request.createNewCard(Global.userId);
            },
            function(instance, toast) {

            }
        );   
	}

    function updateCardDefault(_this, cardId) {
        Request.updateCardDefault($(_this).closest('tr'), Global.userId, cardId);
    }

	function billNow(_this, userId) {
        Alert.confirm(
            'New','Are you sure you want to bill this now?',
            'Yes',
            'No',
            function(instance, toast) {
                Request.billNow($(_this).closest('tr'), userId);
            },
            function(instance, toast) {

            }
        );  
	}

	function cancelForTheWeek(_this, userId) {
        Alert.confirm(
            'New','Are you sure you want to cancel for the week this?',
            'Yes',
            'No',
            function(instance, toast) {
                Request.cancelForTheWeek($(_this).closest('tr'), userId);
            },
            function(instance, toast) {

            }
        );  
	}

	function cancelSubscription(_this, userId) {
		Alert.confirm(
            'New','Are you sure you want to cancel this customer?',
            'Yes',
            'No',
            function(instance, toast) {
                Request.cancelSubscription($(_this).closest('tr'), userId);
            },
            function(instance, toast) {

            }
        ); 
	}

    function addNewCard() {
        Element.addNewCardButton().fadeOut();
        Element.addNewCardContainer().fadeOut()
        Element.createNewCardButton().fadeIn();;
        Element.createNewCardContainer().fadeIn();
        Element.backButton().fadeIn();
    }

    function resetModal() {
        Element.addNewCardButton().fadeIn();
        Element.addNewCardContainer().fadeIn()
        Element.createNewCardButton().fadeOut();;
        Element.createNewCardContainer().fadeOut();
        Element.backButton().fadeOut();
    }

    function reloadModal(userId, content) {
        Global.userId = userId;
        Element.createModal()
        .find('.modal-body').html(content);
        Element.createModal().modal('show');
        Element.cardAttributes();
        Global.formElement = Element.createModal().find('form');
        validator();
        resetModal();
    }
	var Global = {
		userId: '',
		formElement: ''
	};

	var Form = {
		name: '#credit-card-form',
		form: function() {
			return $(Form.name);
		},
		validate: function() {
			var valid = Global.formElement.valid();
            if(!valid) {
                $validator.focusInvalid();
                throw 'Please complete all fields.'
            }
		},
        getCycleId: function() {
            return $('#cycle_id option:selected').val();
        },
	}

	var Element = {
		cardModal: '#creditcard-modal',
        cardModalContainer: '#creditcard-modal > .modal-dialog',
		createModal: function() {
			return $(Element.cardModal);
		},
		cardAttributes: function() {
            $("#card_expiration_date").mask("99/99");
            $("#card_cvc").mask("999");
            $("#card_number").mask("9999 9999 9999 9999");
		},
        addNewCardButton: function() {
            return $('#btn-add-new-card');
        },
        createNewCardButton: function() {
            return $('#btn-save-card');
        },
        backButton: function() {
            return $('#btn-back');
        },
        addNewCardContainer: function() {
            return $('#card-list-container');
        },
        createNewCardContainer: function() {
            return $('#card-form-container');
        },
        cardMessage: '#card-message'
	}

	var Request = {
		createNewCard: function(userId) {
			System.blockUI(Element.cardModalContainer);
            System.lazyLoading( function() {
                System.setAjaxRequest(
                    url.addNewCreditCardUrl+'/'+userId,
                    $(Form.name).serialize(),
                    'PATCH',
                    function(response) {
                       System.unblockUI();
                       if (response.success == true) {
                            reloadModal(userId, response.content);
                            Alert.success('Successfully created card.');
                       } else {
                            System.errorMessage(response.message, Element.cardMessage);
                            Alert.error('Error!', response.message);
                       }
                    },
                    function() {
                        System.unblockUI();
                        System.errorMessage(response.message, 'Failed to create credit card.');
                        Alert.error('Error!','Failed to create credit card.');
                    }, 'JSON', true
                );
            });
		},
        updateCardDefault: function(_this, userId, cardId) {
            System.blockUI(_this);
            System.lazyLoading( function() {
                System.setAjaxRequest(
                    url.updateCardDefaultUrl+'/'+userId,
                    {cardId: cardId},
                    'PATCH',
                    function(response) {
                       System.unblockUI();
                       if (response.success == true) {
                            reloadModal(userId, response.content);
                       } else {
                            Alert.error('Error!', response.message);
                       }
                    },
                    function() {
                        System.unblockUI();
                        Alert.error('Error!','Failed to update credit card default.');
                    }, 'JSON', true
                );
            });
        },
        cancelForTheWeek: function(_this, userId) {
            System.blockUI(_this);
            System.lazyLoading( function() {
                System.setAjaxRequest(
                    url.cancelForTheWeekUrl+'/'+userId,
                    {cycle_id: Form.getCycleId()},
                    'PATCH',
                    function(response) {
                       System.unblockUI();
                       if (response.success == true) {
                            System.successMessage(response.message);
                       } else {
                            System.errorMessage(response.message);
                       }
                       $(window).scrollTop(0);
                       loadMasterList();
                    },
                    function() {
                        System.unblockUI();
                        System.errorMessage('Failed to cancel subscription for the week.');
                    }, 'JSON', true
                );
            });
        },
        cancelSubscription: function(_this, userId) {
            System.blockUI(_this);
            System.lazyLoading( function() {
                System.setAjaxRequest(
                    url.cancelSubscriptionUrl+'/'+userId,
                    '',
                    'PATCH',
                    function(response) {
                       System.unblockUI();
                       if (response.success == true) {
                            System.successMessage(response.message);
                       } else {
                            System.errorMessage(response.message);
                       }
                       $(window).scrollTop(0);
                       loadMasterList();
                    },
                    function() {
                        System.unblockUI();
                        System.errorMessage('Failed to cancel customer.');
                    }, 'JSON', true
                );
            });
        },
        billNow: function(_this, userId) {
            System.blockUI(_this);
            System.lazyLoading( function() {
                System.setAjaxRequest(
                    url.billNowUrl+'/'+userId,
                    '',
                    'PATCH',
                    function(response) {
                       System.unblockUI();
                       if (response.success == true) {
                            System.successMessage(response.message);
                       } else {
                            System.errorMessage(response.message);
                       }
                       $(window).scrollTop(0);
                       loadMasterList();
                    },
                    function() {
                        System.unblockUI();
                        loadMasterList();
                        System.errorMessage('Failed to bill subscription.');
                    }, 'JSON', true
                );
            });
        }
	}

	return {
		showCardModal: showCardModal,
		billNow: billNow,
		cancelForTheWeek: cancelForTheWeek,
		cancelSubscription: cancelSubscription,
		createNewCard: createNewCard,
        addNewCard: addNewCard,
        updateCardDefault: updateCardDefault,
        resetModal: resetModal
	}

}();