var Cards = function() {
    var form = '#credit-card-form';
    var saveUrl, updateCardDefaultUrl;
    var $validator;

    function init() {
        $validator = $(form).validate({
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

    function saveNewCard() {
        var valid = $(form).valid();
        if(!valid) {
            $validator.focusInvalid();
            return false;
        }

        System.blockUI('#customer-card-form');
        System.lazyLoading( function() {
            System.setAjaxRequest(
                saveUrl,
                $(form).serialize(),
                'PUT',
                function(res) {
                    System.unblockUI();
                    if (res.success == true) {
                        Alert.success('Success!',res.message);
                        $('#card_name, #card_number, #card_expiration_date, #card_cvc').val('');
                        loadCards();
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

    function updateDefault() {
        System.blockUI('#form-cards-list');
        System.lazyLoading( function() {
            System.setAjaxRequest(
                updateCardDefaultUrl,
                $('#form-cards-list').serialize(),
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
    }

    function bindCreditCards() {
        if (isCheckedNewCard()) {
            toggleCreditCardForm(true);
        } else {
            if (getCardId() == null || getCardId() == undefined) {
                toggleCreditCardForm(true);
            } else {
                toggleCreditCardForm(false);
                updateDefault();
            }            
        }
    }

    function isCheckedNewCard() {
        return $('.radio-cardlist:checked').attr('value') == 'new';
    }

    function getCardId() {
        return $('.radio-cardlist:checked').attr('value');
    }

    function toggleCreditCardForm(show) {
        console.log(show);
        if (show) {
            $('#customer-card-form').fadeIn();
        } else {
            $('#customer-card-form').fadeOut();
        }
    }

    function loadCards() {
        var cardForm = $('#form-cards-list');

        System.blockUI(cardForm);
        System.lazyLoading( function() {
            System.setAjaxRequest(
                cardsUrl,
                '',
                'GET',
                function(response) {
                    System.unblockUI();
                    var cards = '';
                    var defaultCard = response.default != undefined ? response.default : '';

                    for (var i in response.cards) {
                        cards += "<input "+(defaultCard == response.cards[i].id ? 'checked' : '')+" type='radio' "+(i == 0 ? 'checked' : '')+" class='radio-cardlist' name='my_card' id='my_card_"+response.cards[i].id+"' value='"+response.cards[i].id+"'> <label for='my_card_"+response.cards[i].id+"'>Existing Card - Ending "+response.cards[i].last4+"</label><br />";
                    }

                    if (response.cards.length > 0) {
                        cards += "<input type='radio' class='radio-cardlist' name='my_card' id='my_card_new' value='new'> <label for='my_card_new'>New Card</label>";
                    }
                    cardForm.html(cards);
                    bindCreditCards();
                    // toggleCreditCardForm(true);
                },
                function() {
                    System.unblockUI();
                    cardForm.html("Failed to retrieve cards. Try to <a onclick='Cards.loadCards()' href='javascript::'>Reload.</a>");
                }, 'json', true
            );
        });
    }

    return {
        init: function(settings) {
            saveUrl = settings.creditCardSaveUrl;
            updateCardDefaultUrl = settings.updateCardDefaultUrl;
            cardsUrl = settings.cardsUrl;
            init();
        },

        bindCreditCards: function() {
            bindCreditCards();
        },

        saveNewCard: function() {
            saveNewCard();
        },

        updateDefault: function() {
            updateDefault();
        },

        loadCards: function(){
            loadCards();
        }
    }

}();