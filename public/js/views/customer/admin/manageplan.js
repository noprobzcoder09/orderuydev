var ManagePlan = function() {

    System.blockUI('#tab1-container');
    System.unblockUI();
    var storeCouponUrl, orderSubscriptionSummaryUrl, removeCouponUrl, 
    updatePlanUrl, deliveryTimingsUrl, saveNewPlanUrl, saveNewPlanUrlWithBilling;
    var $validator;

    function _init(settings) {
        storeCouponUrl = settings.storeCouponUrl;
        orderSubscriptionSummaryUrl = settings.orderSubscriptionSummaryUrl;
        removeCouponUrl = settings.removeCouponUrl;
        updatePlanUrl = settings.updatePlanUrl;
        deliveryTimingsUrl = settings.deliveryTimingsUrl;
        saveNewPlanUrl = settings.saveNewPlanUrl;
        saveNewPlanUrlWithBilling = settings.saveNewPlanUrlWithBilling;
        Form.inputs.deliveryZoneTimingId = settings.deliveryZoneTimingId;

        Form.validator();

        Coupon.code.select2({theme: "bootstrap", placeholder: "Select Coupons"});
    }

    function _updatePlan() {
        if (Form.inputs.isNoPlan()) return;

        Coupon.clear();
        Request.updatePlan(Form.inputs.plansId());

    }

    function _create() {
        try 
        {   
            Form.validate(); 
            Alert.confirm(
                'New','Are you sure you want to save and bill this new plan?',
                'Yes',
                'No',
                function(instance, toast) {
                    Request.create(saveNewPlanUrl);
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
                'New','Are you sure you want to save this new plan and bill at cutover?',
                'Yes',
                'No',
                function(instance, toast) {
                    Request.create(saveNewPlanUrlWithBilling);
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
        Request.deliveryTimings();
        System.hideMessage(Element.subscriptionModalMessage);
    }

    function _modalClose() {
        Element.hideModal();
        Form.inputs.clear();
    }

    function _clear() {
        Form.inputs.clear();
    }

    function _reloadTables() {
        table.ajax.reload();
        pastTable.ajax.reload();
        invoiceTable.ajax.reload();
        console.log('Please reload invoice.');
    }

    var Element = {
        createModal: $('#active-subscription-modal'),
        deliveryTimingContainer: $('.delivery-timing-container'),
        couponContainer: $('.coupon-container'),
        orderSummaryContainer: $('#subscription-order-summary'),
        modalFooter: $('#subscription-modal .modal-footer'),
        modalContainer: $('.modal-content'),
        hideModal: function() {
            Element.createModal.modal('hide');
        },
        showModal: function() {
            Element.createModal.modal('show');
        },
        redirectIfEmpty: function(planId) {
            if (!Config.iHavePlans) {
                window.location.href = checkoutPlanUrl+planId;
            }
        },
        subscriptionModalMessage: '#subscriptionmodal-message'

    }

    var Coupon = {
        store: function(code) {
            var _this = this;
            try
            {
                Coupon.validator();
                return Request.storeCoupon(code);
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
        code: $('#coupons'),
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
            Coupon.code.trigger('change');
        },
        validator: function() {
            if (Form.inputs.isNoPlan()) {
                throw 'Please select a plan.';
            }
        },
        remove: function(code) {
            Request.removeCoupon(code);
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
                    },
                    card_id: {
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
                return Form.inputs.plan.find('option:selected').val()
            },
            code: function() {
                var data = Coupon.code.val();
                var c = new Array();
                for (var i in data) {
                    c.push(data[i]);
                }
                return c;
            },
            clear: function() {
                Form.inputs.plan.val('');
                Coupon.clear();
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
                    options += "<option "+(Form.inputs.deliveryZoneTimingId == response[i].id ? 'selected' : '')+"  "+(response.length <= 1 ? 'selected' : '')+" value='"+response[i].id+"'>"+response[i].date+"</option>";
                }

                delivery_timings_id.html(options);
            },
            deliveryZoneTimingId: ''
        }
    };

    var Request = {
        storeCoupon: function(code) {
            System.blockUI(Form.container());
            var success = false;
            System.setAjaxRequest(
                storeCouponUrl,
                {coupons: code, meal_plans_id: Form.inputs.plansId()},
                'PUT',
                function(response) {
                   System.unblockUI();
                   if (response.success == true) {
                        success  = true;
                        Coupon.clear();
                        Coupon.hideError();
                        Coupon.createNewPromoCodeButton();
                        Request.orderSummary();
                   } else {
                        success = false;
                        Alert.error('Error!',response.message);
                   }
                },
                function() {
                    System.unblockUI();
                    Alert.error('Error!','Error to store coupon.');
                }
            );
            return success;
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
                    Alert.error('Error!','Error to retrieve added couppns.');
                }, 'HTML', true
            );
        },
        removeCoupon: function(code) {
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
        },
        updatePlan: function(planId) {
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
                            System.successMessage(response.message, Element.subscriptionModalMessage);
                        } else {
                            System.errorMessage(response.message, Element.subscriptionModalMessage);
                            Alert.error('Error',response.message, 'topRight');
                        }
                    },
                    function(error) {
                        System.unblockUI();

                        var error = error.responseJSON.message;
                        error = error == undefined ? System.errorTextMessage : error;
                        System.errorMessage(error, Element.subscriptionModalMessage);
                        Alert.error('Error',error, 'topRight');
                    }
                );
            });
        }
    };

    return {
        init: _init,
        modalCreate: _modalCreate,
        create: _create,
        createBilling: _createBilling,
        storeCoupon: Coupon.store,
        createCoupon: Coupon.createCoupon,
        addNewCoupon: Coupon.addNewCoupon,
        closeNewCoupon: Coupon.closeNewCoupon,
        removeCoupon: Coupon.remove,
        updatePlan: _updatePlan,
        deliveryTimings: Request.deliveryTimings
    }
}();

