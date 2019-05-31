var Customer = function() {

	function cancel(_this, userId, subscribeId, subscriptionCycleId) {
        Alert.confirm(
            'Cancel','Are you sure you want to cancel this subscription?',
            'Yes',
            'No',
            function(instance, toast) {
                System.blockUI($(_this).closest('tr'));
                System.lazyLoading( function() {
                    System.setAjaxRequest(
                        url.cancelUrl+userId+'/'+subscribeId+'/'+subscriptionCycleId,
                        '',
                        'PATCH',
                        function(response) {
                            System.unblockUI();
                            if (parseInt(response) > 0) {
                            	table.ajax.reload();
                            	pastTable.ajax.reload();
                                Alert.success('Success!','Successfully Cancelled Subscription.');
                            } else {
                            	Alert.error('Error','Could not cancel subscription.', 'topRight');
                            }
                        },
                        function() {
                            System.unblockUI();
                            Alert.error('Error',System.errorTextMessage, 'topRight');
                        }
                    );
                });
            },
            function(instance, toast) {

            }
        );
    }

    function pause(_this, date) {
        if (date == '' || date == undefined) return;

        Alert.confirm(
            'Pause','Are you sure you want to pause this subscription?',
            'Yes',
            'No',
            function(instance, toast) {
                System.blockUI($(_this).closest('tr'));
                System.lazyLoading( function() {
                    System.setAjaxRequest(
                        url.pauseUrl+Global.getUserId()+'/'+Global.getSubscriptionCycleId(),
                        {date: date},
                        'PATCH',
                        function(response) {
                            System.unblockUI();
                            if (parseInt(response) > 0) {
                            	table.ajax.reload();
                            	pastTable.ajax.reload();
                                Alert.success('Success!','Successfully paused Subscription.');
                            } else {
                            	Alert.error('Error','Could not paused subscription.', 'topRight');
                            }
                        },
                        function() {
                            System.unblockUI();
                            Alert.error('Error',System.errorTextMessage, 'topRight');
                        }
                    );
                });
            },
            function(instance, toast) {

            }
        );
    }

    function play(_this, userId, subscriptionCycleId) {
        Alert.confirm(
            'Pause','Are you sure you want to start this subscription?',
            'Yes',
            'No',
            function(instance, toast) {
                System.blockUI($(_this).closest('tr'));
                System.lazyLoading( function() {
                    System.setAjaxRequest(
                        url.playUrl+userId+'/'+subscriptionCycleId,
                        '',
                        'PATCH',
                        function(response) {
                            System.unblockUI();
                            if (parseInt(response) > 0) {
                            	table.ajax.reload();
                            	pastTable.ajax.reload();
                                Alert.success('Success!','Successfully started Subscription.');
                            } else {
                            	Alert.error('Error','Could not paused subscription.', 'topRight');
                            }
                        },
                        function() {
                            System.unblockUI();
                            Alert.error('Error',System.errorTextMessage, 'topRight');
                        }
                    );
                });
            },
            function(instance, toast) {

            }
        );
    }

    function invoice(_this, userId, subscribeId) {
        var _this = $(_this);
        var selControl = _this.closest('tr').find('.active-selection-control');
        var tr = _this.closest('tr');
        var rtable = _this.attr('data-table') == 'past' ? pastTable : table;
        var row = rtable.row( tr );
        
        if (selControl.hasClass('shown')) {
            row.child.hide();
            tr.removeClass('shown');
            _this.removeClass('shown');
            selControl.removeClass('shown');
        }

        if ( row.child.isShown()) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
            _this.removeClass('show');
        }
        else {
            System.blockUI(tr);
            System.setAjaxRequest(
                url.invoices,
                {subscribeId: subscribeId, userId: userId},
                'GET',
                function(response) {
                    System.unblockUI();
                    setTimeout( function() {
                        // Open this row
                        row.child(response).show();
                        tr.addClass('shown');
                        _this.addClass('shown');
                    },1400);
                },
                function(error) {
                    System.unblockUI();
                },
                'html',
                true
            );
        }
    }

    function invoiceDownload(orderId) {
        if (orderId == 0 || orderId == '' || orderId == undefined || orderId == 'null') {
            return;
        }
        var link = appLink+'/Job/manageJob.jsp?view=edit&ID='+orderId;
        window.open(link,'_blank')
    }

    function inputPause(_this, userId, subscriptionCycleId) {
        var _this = $(_this);
        System.blockUI(_this);
        System.setAjaxRequest(
            url.futureDeliveryTimingScheduleUrl+userId+'/'+subscriptionCycleId,
            '',
            'GET',
            function(response) {
                System.unblockUI();
                _this.hide();
                _this.closest('td').prepend(response);
                Global.setUserId(userId);
                Global.setSubscriptionCycleId(subscriptionCycleId);
            },
            function(error) {
                System.unblockUI();
            },
            'html',
            true
        );
    }

    function modifyStatus(_this, userId, subscriptionCycleId, status) {
        if (status == '') return;
        Alert.confirm(
            'Status','Are you sure you want to change this status?',
            'Yes',
            'No',
            function(instance, toast) {
                var _this = $(_this);
                System.blockUI(_this);
                System.setAjaxRequest(
                    url.updateStatusUrl+'/'+userId+'/'+subscriptionCycleId,
                    {status: status},
                    'PATCH',
                    function(response) {
                        System.unblockUI();
                        if (response.success == true) {
                            table.ajax.reload();
                            pastTable.ajax.reload();
                        } else {
                            Alert.error('Error',response.message);
                        }
                    },
                    function(error) {
                        System.unblockUI();
                    },
                    'json',
                    true
                );
            },
            function() {
                $(_this).parent().find('select[name="newstatus"]')
                    .val('');
            }
        );
    }

    function closeInputDate(_this) {
        var _this = $(_this);
        _this.closest('td').find('.btn-pause-date').show();
        _this.closest('#date-wrapper').remove();
    }

    function resetPassword(id) {
        Alert.confirm(
            'Reset','Are you sure you want to send reset password to this user?',
            'Yes',
            'No',
            function(instance, toast) {
                System.blockUI($('.main'));
                System.lazyLoading( function() {
                    System.setAjaxRequest(
                        url.resetPasswordUrl+id,
                        '',
                        'GET',
                        function(response) {
                            System.unblockUI();
                            if (response.success == true) {
                            	Alert.success('Success!','Successfully sending reset password email to the customer.');
                            } else {
                            	Alert.error('Error','Could not send reset password email.', 'topRight');
                            }
                        },
                        function() {
                            System.unblockUI();
                            Alert.error('Error',System.errorTextMessage, 'topRight');
                        }
                    );
                });
            },
            function(instance, toast) {

            }
        );
    }

    var Element = {
        dateContainer: $('#date-wrapper')
    };

    var Global = {
        userId: '',
        subscriptionCycleId: '',
        setUserId: function(id) {
            Global.userId = id;
        },
        setSubscriptionCycleId: function(id) {
            Global.subscriptionCycleId = id;
        },
        getUserId: function(id) {
            return Global.userId;
        },
        getSubscriptionCycleId: function(id) {
            return Global.subscriptionCycleId;
        },
    };


	return {
		cancel: cancel,
		play: play,
        invoice: invoice,
        pause: pause,
        invoiceDownload: invoiceDownload,
        inputPause: inputPause,
        closeInputDate: closeInputDate,
        modifyStatus: modifyStatus,
        resetPassword: resetPassword
	}
}();