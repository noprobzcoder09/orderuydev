 $('#form-meta').submit( function() {

            if (!isFormValid('#form-meta')) {
                    return false;
                }

                System.blockUI('#meta-container');

                System.setAjaxRequest(
                    url.meta,
                    $('#form-meta').serialize(),
                    'POST',
                    function(response) {
                                if (response.status == 200)
                                {   
                                    System.unblockUI();
                                    if (response.success) {

                                        System.successMessage(response.messages, metaMessageId);
                                        $('#meta-table-container .card-body').html(response.metas);
                                        $('#form-meta input[type="text"]').val('');
                                        return;
                                    } 

                                    System.errorMessage(response.messages, metaMessageId);
                                }
                    },
                    function() {
                                System.errorMessage('', metaMessageId);
                                System.unblockUI();
                    }
                );

            return false;
        });
