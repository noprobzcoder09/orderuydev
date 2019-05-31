var Menu = function() {
	System.blockUI('#tab1-container');
	System.unblockUI();

	function _init() {
		getSubscriptions();
	}

	function get(subscriptionId, subscriptionCycleId) {
		var _this = this;
        if (subscriptionCycleId == '' || subscriptionCycleId == undefined) return;
        
        System.setAjaxRequest(
            url.menuPageUrl,
            {subscriptionCycleId: subscriptionCycleId, subscriptionId: subscriptionId},
            'GET',
            function(response) {
                System.unblockUI();
                Element.containerSelections.append(response);
            },
            function() {
                
                Alert.error('Error',System.errorTextMessage, 'topRight');
            },
            'HTML', false
        );
    }

    function getSubscriptions()
    {	
    	System.lazyLoading( function() {
            System.setAjaxRequest(
                url.subscriptionIdsUrl,
                '',
                'GET',
                function(response) {
                    // if (response.length > 0) {
                        Element.emptyContainer();
                    // }
                   for (var i in response) {
                       	get(response[i].subscription_id, response[i].subscriptions_cycle_id);
                   }
                },
                function() {
                    Alert.error('Error',System.errorTextMessage, 'topRight');
                },
                'json', true
            );
        });
    }

    var Element = {
    	container: $('#tab1-container'),
        containerSelections: $('#container-selections'),
    	emptyContainer: function() {
    		this.containerSelections.html('');
    	}
    };

	return {
		init: _init
	}
}();