var ManagePlan = function() {
	System.blockUI('#tab1-container');
	System.unblockUI();
    var storeCouponUrl, orderSubscriptionSummaryUrl, removeCouponUrl, 
    updatePlanUrl, deliveryTimingsUrl, saveNewPlanUrl, checkoutPlanUrl;
    var $validator;

	function _init(settings) {
        storeCouponUrl = settings.storeCouponUrl;
        orderSubscriptionSummaryUrl = settings.orderSubscriptionSummaryUrl;
        removeCouponUrl = settings.removeCouponUrl;
        updatePlanUrl = settings.updatePlanUrl;
        deliveryTimingsUrl = settings.deliveryTimingsUrl;
        saveNewPlanUrl = settings.saveNewPlanUrl;
        checkoutPlanUrl = settings.checkoutPlanUrl;
        deliveryTimingsSettingsUrl = settings.deliveryTimingsSettingsUrl;

        Form.validator();
	}

    function _updatePlan() {
        if (Form.inputs.isNoPlan()) return;

        Request.updatePlan(Form.inputs.plansId(), Form.inputs.planSku());
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
                    Request.create();
                },
                function(instance, toast) {

                }
            );   
        }
        catch (error) {
            Alert.error('Error!',error);
        }
        
    }

    function _modalCreate() {
        Element.showModal();
        Request.orderSummary();
        Request.getDeliveryTimingSettings();
        Request.deliveryTimings();
        
    }

    var Element = {
        createModal: $('#subscription-modal'),
        deliveryTimingContainer: $('.delivery-timing-container'),
        couponContainer: $('.coupon-container'),
        orderSummaryContainer: $('#subscription-order-summary'),
        modalFooter: $('#subscription-modal .modal-footer'),
        hideModal: function() {
            Element.createModal.modal('hide');
        },
        showModal: function() {
            Form.inputs.plan.val('');
            Element.createModal.modal('show');
            Form.hiddenFieldsForNonSubscription();
        },
        redirectIfEmpty: function(sku) {
            if (!Config.iHavePlans) {
                window.location.href = checkoutPlanUrl+sku;
            }
        }
    }

    var Coupon = {
        store: function() {
            var _this = this;
            try
            {
                Coupon.validator();
                Request.storeCoupon();
            }
            catch (error) {
                Alert.error('Error!',error);
            }
        },
        list: function () {
            Request.listCoupons();
        },
        addNewPromoCode: function() {
            Coupon.showodeInputs();
            Coupon.hideButonAddNew();
        },
        createNewPromoCodeButton: function() {
            Coupon.showButonAddNew();
            Coupon.hideCodeInputs();
        },
        hideCodeInputs: function() {
            Coupon.container.hide();
        },
        showodeInputs: function() {
            Coupon.container.show();
        },
        showButonAddNew: function() {
            Coupon.createLinkNewCode.show();
        },
        hideButonAddNew: function() {
            Coupon.createLinkNewCode.hide();
        },
        createCoupon: function() {
            Coupon.container.show();
            Coupon.createLinkNewCode.hide();
        },
        closeNewCoupon: function() {
            Coupon.container.hide();
            Coupon.createLinkNewCode.show();
        },
        addNewCoupon: function() {
            Coupon.container.show();
            Coupon.createLinkNewCode.hide();
        },
        container: $('#coupon-input-wrapper'),
        element: $('#coupon_error'),
        elementNew: $('#promo-new-button-container'),
        containerList: $('#subscription-order-summary'),
        createLinkNewCode: $('#coupon-link-wrapper'),
        newLinkCoupon: $('#promo-new-button-container'),
        code: $('#coupon_code'),
        showError: function(message) {
            this.element.html(message);
            this.element.show();
        },
        hideError: function() {
            this.element.hide();
            this.element.html('');
        },
        clear: function() {
            Coupon.code.val('');
        },
        validator: function() {
            if (Form.inputs.isNoPlan()) {
                throw 'Please select a plan.';
            }
        },
        remove: function(_this, code) {
            Request.removeCoupon($(_this).closest('tr'), code);
        }
    };

    var Form = {
        name: '#subscription-form',
        container: function() {
            return $(this.name);
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
                    delivery_zone_id: {
                        required: true
                    },
                    delivery_zone_timings_id: {
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
        },
        inputs: {
            plan: $('#meal_plans_id'),
            plansId: function() {
                return Form.inputs.plan.find('option:selected').val();
            },
            planSku: function() {
                return Form.inputs.plan.find('option:selected').attr('data-sku');
            },
            code: function() {
                return $('#coupon_code').val()
            },
            isNoPlan: function() {
                if (Form.inputs.plansId() == '') {
                    return true;
                }
                return false;
            },
            zoneId: function() {
                return $('#manageplan_delivery_zone_id option:selected').val()
            },
            setTimings: function(response) {                
                var delivery_timings_id = $('#manageplan_delivery_zone_timings_id');
                var options = '<option value="">Choose Delivery Time</option>';
                
                for (var i in response) {
                    options += "<option "+(getCurrentDeliveryZoneTimingId() == response[i].id ? 'selected' : '')+"  "+(response.length <= 1 ? 'selected' : '')+" value='"+response[i].id+"'>"+response[i].date+"</option>";
                } 
               
                delivery_timings_id.html(options);
            },
            newPlanModalSetDeliveryTimings: function(response) {
                // Victor P. Tagupa Jr
                // Date: March 14, 2019 13:17 PM PH
                // commented because it causing an unselected value

                // const newPlanModalDeliveryZoneId = $('#manageplan_delivery_zone_id');
                // const newPlanModalDeliveryTimingId = $('#manageplan_delivery_zone_timings_id'); 

                // if (newPlanModalDeliveryZoneId.val() != response.delivery_zone_id) {
                //     newPlanModalDeliveryZoneId.val(response.delivery_zone_id).change();
                // }

                // const checkExist = setInterval(function() {
                //     if ($('#manageplan_delivery_zone_timings_id option[value="'+response.id+'"]').length) {
                //         newPlanModalDeliveryTimingId.val(response.id);
                //         clearInterval(checkExist);
                //     }
                // }, 100); // check every 100ms
                
            }
        },
        hiddenFieldsForNonSubscription: function() {
            if (!Config.iHavePlans) {
                Element.deliveryTimingContainer.hide();
                Element.couponContainer.hide();
                Element.orderSummaryContainer.hide();
                Element.modalFooter.hide();
            }
        }
    };

    var Request = {
        storeCoupon: function() {
            System.blockUI(Form.container());
            System.setAjaxRequest(
                storeCouponUrl,
                {coupons: Form.inputs.code(), meal_plans_id: Form.inputs.plansId()},
                'GET',
                function(response) {
                   System.unblockUI();
                   if (response.success == true) {
                        Coupon.clear();
                        Coupon.hideError();
                        Coupon.createNewPromoCodeButton();
                        Request.orderSummary();
                   } else {
                        Coupon.showError(response.message);
                   }
                },
                function() {
                    System.unblockUI();
                    Alert.error('Error!',System.errorTextMessage);
                }
            );
        },
        orderSummary: function() {
            System.setAjaxRequest(
                orderSubscriptionSummaryUrl,
                '',
                'GET',
                function(response) {
                   System.unblockUI();
                   Coupon.containerList.html(response);
                },
                function() {
                    System.unblockUI();
                    Alert.error('Error!',System.errorTextMessage);
                }, 'HTML', true
            );
        },
        removeCoupon: function(_this, code) {
            System.blockUI(_this);
            System.lazyLoading( function() {
                System.setAjaxRequest(
                    removeCouponUrl+'?coupons='+code,
                    '',
                    'DELETE',
                    function(response) {
                       System.unblockUI();
                       Request.orderSummary();
                    },
                    function() {
                        System.unblockUI();
                        Alert.error('Error!','Error to retrieve added couppns.');
                    }, 'HTML', true
                );
            });
        },
        updatePlan: function(planId, sku) {
            Element.redirectIfEmpty(sku);
            System.blockUI(Form.container());
            System.lazyLoading( function() {
                System.setAjaxRequest(
                    updatePlanUrl,
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
        deliveryTimings: function() {
            const _this = this;
            if (Form.inputs.zoneId() == '') return;
            System.setAjaxRequest(
                deliveryTimingsUrl+Form.inputs.zoneId(),
                '',
                'GET',
                function(response) {
                   System.unblockUI();
                   Form.inputs.setTimings(response);
                },
                function() {
                    System.unblockUI();
                    Alert.error('Error!','Failed to update plan.');
                }, 'JSON', true
            );            
        },

        getDeliveryTimingSettings: function() {
            System.setAjaxRequest(
                deliveryTimingsSettingsUrl,
                '',
                'GET',
                function(response) {                   
                   System.unblockUI();   
                   Form.inputs.newPlanModalSetDeliveryTimings(response);                
                },
                function() {
                    System.unblockUI();
                    Alert.error('Error!','Failed to get delivery timings settings.');
                }, 'JSON', true
            );
        },

        create: function() {
            System.blockUI(Form.name);
            System.lazyLoading( function() {
                System.setAjaxRequest(
                    saveNewPlanUrl,
                    $(Form.name).serialize(),
                    'POST',
                    function(response) {
                        System.unblockUI();
                        if (response.success == true) {
                            listAllPlans();
                            Menu.init();
                            Element.hideModal();
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
    };

	return {
		init: _init,
        modalCreate: _modalCreate,
        create: _create,
        storeCoupon: Coupon.store,
        createCoupon: Coupon.createCoupon,
        addNewCoupon: Coupon.addNewCoupon,
        closeNewCoupon: Coupon.closeNewCoupon,
        removeCoupon: Coupon.remove,
        updatePlan: _updatePlan,
        deliveryTimings: Request.deliveryTimings
	}
}();

