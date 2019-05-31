var Cards = function() {
    var cardsUrl;
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
                        cards += "<input "+(defaultCard == response.cards[i].id ? 'checked' : '')+" type='radio' "+(i == 0 ? 'checked' : '')+" class='radio-cardlist' name='my_card' id='my_card_"+response.cards[i].id+"' value='"+response.cards[i].id+"'> <label for='my_card_"+response.cards[i].id+"'>Existing Card - Ending "+response.cards[i].Last4+"</label><br />";
                    }

                    if (response.cards.length > 0) {
                        cards += "<input type='radio' class='radio-cardlist' name='my_card' id='my_card_new' value='new'> <label for='my_card_new'>New Card</label>";
                    }
                    cardForm.html(cards);
                    bindCreditCards();
                },
                function() {
                    System.unblockUI();
                    cardForm.html("Failed to retrieve cards. Try to <a onclick='Cards.loadCards()' href='javascript::'>Reload.</a>");
                }, 'json', true
            );
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

    function bindCreditCards() {
        if (isCheckedNewCard()) {
            toggleCreditCardForm(true);
        } else {
            if (getCardId() == null || getCardId() == undefined) {
                toggleCreditCardForm(true);
            } else {
                toggleCreditCardForm(false);
            }            
        }
    }

    function toggleCreditCardForm(show) {
        if (show) {
            $('#customer-card-form').fadeIn();
        } else {
            $('#customer-card-form').fadeOut();
        }
    }

    function isCheckedNewCard() {
        return $('.radio-cardlist:checked').attr('value') == 'new';
    }

    function getCardId() {
        return $('.radio-cardlist:checked').attr('value');
    }

    return {
        init: function(settings) {
            cardsUrl = settings.cardsUrl;
            init();
        },
        isValidCRD: function() {
            return isValidCRD();
        },
        loadCards: function() {
            loadCards();
        },
        bindCreditCards: function() {
            bindCreditCards();
        },
        isCheckedNewCard: function() {
            return isCheckedNewCard();
        },
        getCardId: function() {
            return getCardId();
        },
    }
}();