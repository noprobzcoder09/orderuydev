const PreviousSelections = {

    modify: function(id) {

        System.blockUI();

        System.setAjaxRequest(
            url.pastMenusPreviousSubscriptionsUrl + '/' + id,
            '',
            'GET',
            function(response) {
                
                //console.log(response);
                
                System.lazyLoading( function() {
                   $('#previous-menu-selections-'+id).html(response);
                },1000);
                System.unblockUI();
            },
            function(error) {
                System.unblockUI();
            },
            'html',
            true
        );
        
    },

    update: function(form, id) {
        System.blockUI();

        System.setAjaxRequest(
            url.updatePreviousSubscriptionsUrl + '/' + id,
            $(form).serialize(),
            'POST',
            function(response) {
                console.log(response);
                if (response.result === true) {
                    Alert.success('Success!', response.message);
                } else {
                    Alert.error('Error',response.message);
                }
            },
            function(error) {
                console.log(error);
                System.unblockUI();
            }
        );
    }

};