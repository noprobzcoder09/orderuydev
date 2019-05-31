var PreviousWeekMenu = function() {

	var $validator;

	function init() {
		
	}

    function _modalClose() {
        Element.hideModal();
        Form.inputs.clear();
    }

    function _reloadTables() {
        table.ajax.reload();
        pastTable.ajax.reload();
        invoiceTable.ajax.reload();
        console.log('Please reload invoice.');
    }

    function cycleState() {
        Form.inputs.setDeliveryZoneId(
            $(Form.name+' '+Element.cycle).find('option:selected')
            .attr('data-delivery_zone_id')
        );
    }

	function addMenuPrevWeekModal(_this, userId, subscribeId, subscriptionCycleId) {
        console.log('test console');
        System.setAjaxRequest(
            url.addMenuPrevWeekContentUrl+'/'+userId+'/'+subscribeId,
            {subscriptionCycleId: subscriptionCycleId},
            'GET',
            function(response) {
                if (response.success == true) {
                    $(Element.modalContainer+' .modal-body').html(response.message);
                    $(Element.modalContainer+' .modal-footer button[data-action="billnow"]').show();
                    $(Element.modalContainer+' .modal-footer button[data-action="billcutover"]').show(); 
                    Element.createModal.modal('show');
                    Request.orderSummary();
                    Form.validator();
                    cycleState();
                    loadCards($(Element.loadCardsSpinner));
                } else {
                    $(Element.modalContainer+' .modal-body').html(response.message);
                    $(Element.modalContainer+' .modal-footer button[data-action="billnow"]').hide();
                    $(Element.modalContainer+' .modal-footer button[data-action="billcutover"]').hide();                    
                    Element.createModal.modal('show');
                    //Alert.error('Error',response.message);    
                }
            },
            function(error) {
                Alert.error('Error',System.errorTextMessage);
            },
            'JSON',
            true
        );
    }

    function updatePlan() {
        if (Form.inputs.isNoPlan()) return;

        Request.updatePlan(Form.inputs.previousWeekMenuPlansId());

    }

    function _create() {
        try 
        {   
            Form.validate(); 
            Alert.confirm(
                'New','Are you sure you want to save this new plan?',
                'Yes',
                'No',
                function(instance, toast) {
                    Request.create(url.addMenuPrevWeekOrderUrl);
                },
                function(instance, toast) {

                }
            );   
        }
        catch (error) {
            Alert.error('Error!',error);
        }
    }

    function _createBilling() {
        try 
        {   
            Form.validate(); 
            Alert.confirm(
                'New','Are you sure you want to save and bill this new plan?',
                'Yes',
                'No',
                function(instance, toast) {
                    Request.create(url.addMenuPrevWeekOrderWithBillingUrl);
                },
                function(instance, toast) {

                }
            );   
        }
        catch (error) {
            Alert.error('Error!',error);
        }
    }

    var Request = {
        orderSummary: function() {
            System.setAjaxRequest(
                url.addMenuPrevWeekOrderSubscriptionSummaryUrl,
                '',
                'GET',
                function(response) {
                    $(Element.orderSummary).html(response);
                    System.unblockUI();
                },
                function() {
                    System.unblockUI();
                }, 'HTML', true
            );
        },
        updatePlan: function(planId) {
            System.blockUI(Form.form());
            System.lazyLoading( function() {
                System.setAjaxRequest(
                    url.addMenuPrevWeekOrderUpdatePlanUrl,
                    {meal_plans_id: planId},
                    'PATCH',
                    function(response) {
                       System.unblockUI();
                       Request.orderSummary();
                    },
                    function() {
                        System.unblockUI();
                        Alert.error('Error!','Failed to update plan.');
                    }, 'HTML', true
                );
            });
        },
        create: function(url) {
            System.blockUI(Element.modalContainer);
            System.lazyLoading( function() {
                System.setAjaxRequest(
                    url,
                    $(Form.name).serialize(),
                    'PUT',
                    function(response) {
                        System.unblockUI();
                        if (response.success == true) {
                            _modalClose();
                            _reloadTables();
                            Alert.success('Success!',response.message);
                        } else {
                            Alert.error('Error',response.message, 'topRight');
                        }
                    },
                    function() {
                        System.unblockUI();
                        Alert.error('Error',System.errorTextMessage, 'topRight');
                    }
                );
            });
        }
    }

    var Form = {
    	name: '#addnewmenuprevweek-form',
        form: function() {
        	return $('#addnewmenuprevweek-form');
        },
        inputs: {
            plan: function() {
                return $('#prev_week_meal_plans_id');
            },
            previousWeekMenuPlansId: function() {
                return Form.inputs.plan().find('option:selected').val();
            },
            clear: function() {
                Form.inputs.plan().val('');
            },
            isNoPlan: function() {
                if (Form.inputs.previousWeekMenuPlansId() == '') {
                    return true;
                }
                return false;
            },
            setDeliveryZoneId: function(id) {
                $(Form.name+' input[name="delivery_zone_id"]').val(id);
            }
        },
        validate: function() {
            var valid = $(Form.name).valid();
            if(!valid) {
                $validator.focusInvalid();
                throw 'Please complete all fields.'
            }
        },
        validator: function() {
            $validator = $(Form.name).validate({
                rules: {
                    meal_plans_id: {
                        required: true
                    },
                    cycle_id: {
                        required: true
                    },
                    card_id: {
                        required: true
                    },
                    subscriptions_id: true,
                    delivery_zone_id: true
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
        },
    };

    var Element = {
        modalContainer: '#addmenuprevweek-modal', 
        createModal: $('#addmenuprevweek-modal'),
        orderSummary: '#addnewmenuprevweek-subscription-order-summary',
        loadCardsSpinner: '#addnewmenuprevweek-form .card-spinner',
        cycle: '#cycle_id',
        hideModal: function() {
            Element.createModal.modal('hide');
        }
    };

	return {
		init: init,
		addMenuPrevWeekModal: addMenuPrevWeekModal,
        updatePlan: updatePlan,
        create: _create,
        createBilling: _createBilling,
        cycleState: cycleState
	}
}();