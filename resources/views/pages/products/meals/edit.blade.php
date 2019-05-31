@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('product-meals-edit', $id))

@section('content')

<div class="row">
    <div class="col-md-6">
        @include('errors.messages')
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="card" id="meal-container">
            <div class="card-header">
                <strong>Meals</strong>
                <small>Edit</small>
            </div>
            <div class="card-body">
                @include($view.'form')
            </div>
        </div>
    </div>
    <!--/.col-->
</div>

<div class="row">
    <div class="col-sm-6">
        @include('errors.messages', ['id' => 'meta-message-id'])
        <div class="card" id="meta-container">
            <div class="card-header">
                <strong>Meta</strong>
            </div>
            <div class="card-body">
                @include($view.'form-meta')
            </div>
        </div>
    </div>
    <!--/.col-->
    <div class="col-sm-6">
        <div class="card" id="meta-table-container">
            <form id="form-meal">
                <div class="card-header">
                    <strong>Meta</strong>
                    <small>List</small>
                </div>
                <div class="card-body">
                    @include($view.'table-meta')
                </div>
                <div class="card-footer">
                   
                </div>
            </form>
        </div>
    </div>
    <!--/.col-->
</div>

@endsection


@section('css')
    <link href="{{asset('vendors/css/select2.min.css')}}" rel="stylesheet">
@endsection

@section('script')
    <script src="{{asset('vendors/js/jquery.validate.min.js')}}"></script>
    <script src="{{asset('js/validator.js')}}"></script>
    <script src="{{asset('vendors/js/select2.min.js')}}"></script>
    <script type="text/javascript">

        var baseUrl = "{{url('/')}}/products/meals/";
        var url = {
            metaNewUrl: "{{url($metaNewUrl)}}",
            metaDelete: "{{url($metaDeleteUrl)}}/",
            metaEditUrl: "{{url($metaEditUrl)}}/",
            metaUpdateUrl: "{{url($metaUpdateUrl)}}/",
            metaSearchFieldUrl: "{{url($metaSearchFieldUrl)}}",
            actionUrl: "{{url($actionUrl)}}"
        }

        var metaMessageId = '#meta-message-id';

        var form = '#meals-form';
        var formMeta = '#meta-form';

        $(document).ready( function () {

            // Meta form validator
            Validator.init(formMeta, {
                rules: {
                    searchField: {
                        required: true
                    },
                    meta_key: {
                        required: true
                    },
                    meta_value: {
                        required: true
                    }
                },
                messages: {
                    searchField: {
                        required: 'Please search a field.',
                    },
                    meta_key: {
                        required: 'Please enter a field.',
                    },
                    meta_value: {
                        required: 'Please enter a value.',
                    }
                },
                submitHandler: function () {
                    System.setAjaxRequest(
                        url.metaNewUrl,
                        $(formMeta).serialize(),
                        'PUT',
                        function(response) {
                            if (response.status == 200)
                            {
                                if (response.success) {
                                    $(formMeta)
                                        .find('input[name="meta_key"]')
                                            .val('');

                                    $(formMeta)
                                        .find('input[name="meta_value"]')
                                            .val('');
                                            
                                    $('#meta-table-container .card-body').html(response.table);
                                    Alert.success('Success!',response.message);
                                } else {
                                    Alert.error('Error!',response.message);
                                }
                            }
                        },
                        function(error) {
                            Alert.error('Error!',System.errorTextMessage);
                        }
                    );
                    return false;
                }
            });

            // Meal form validator
            Validator.init(form, {
                rules: {
                    meal_sku: {
                        required: true
                    },
                    meal_name: {
                        required: true
                    }
                },
                messages: {
                    meal_sku: {
                        required: 'Please enter a sku.',
                    },
                    meal_name: {
                        required: 'Please enter a meal name.',
                    }
                },
                submitHandler: function () {
                    System.setAjaxRequest(
                        url.actionUrl,
                        $(form).serialize(),
                        'PATCH',
                        function(response) {
                            if (response.status == 200)
                            {
                                if (response.success) {
                                    Alert.success('Success!',response.message);
                                } else {
                                    Alert.error('Error!',response.message);
                                }
                            }
                        },
                        function(error) {
                            Alert.error('Error!',System.errorTextMessage);
                        }
                    );
                    return false;
                }
            });

            // Search field remotely
            $('#search_field').select2({ 
                ajax: {
                    url: url.metaSearchFieldUrl,
                    data: function (params) {
                      var query = {
                        search: params.term,
                        page: params.page || 1
                      }
                      // Query parameters will be ?search=[term]&page=[page]
                      return query;
                    },
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data, params) {
                      return {
                            results: data.items
                      };
                    },
                    cache: true,
                  },
                  placeholder: 'Search',
                minimumInputLength: 2,
                theme: "bootstrap",
                allowClear: true,
                "language": {
                   "noResults": function(){
                       return "No Results Found. Check New Above to create new field.";
                   }
                }
            });

            toggleInputFieldWrapper();

            $('#create_new').click( function() {
                toggleInputFieldWrapper();
            });
        });
    
        function addRules(form, rulesObj){
            for (var item in rulesObj){
               $(form).rules('add',item);  
            } 
        }

        function removeRules(rulesObj){
            for (var item in rulesObj){
               $(form).rules('remove', item);  
            } 
        }

        function isUpdateMeta() {
            var id = $(formMeta).find('input[name="id"]').val();

            if (id != '' && id != null && id != undefined) {
                return true;
            }
            return false;
        }

        function toggleInputFieldWrapper() {
            
            if (isUpdateMeta()) {
                hideCreateNewCheckBox();
                return showFieldInputWrapper();
            }

            showCreateNewCheckBox();

            if ($('#create_new').is(':checked')) {
                showFieldInputWrapper();
                removeRules({search_field});
                addRules({
                    meta_key: {
                        required: true
                    }
                });
            } else {
                showSearchWrapper();
                removeRules({meta_key});
                addRules({
                    search_field: {
                        required: true
                    }
                });
            }
        }

        function reset() {
            showSearchWrapper();
            showCreateNewCheckBox();
        }

        function showSearchWrapper() {
            $('.field-search-wrapper').fadeIn();
            $('.field-input-wrapper').fadeOut();
        }

        function showFieldInputWrapper() {
            $('.field-search-wrapper').fadeOut();
            $('.field-input-wrapper').fadeIn();
        }

        function hideCreateNewCheckBox() {
            $('.checkbox-creat-new-wrapper').hide();
        }

        function showCreateNewCheckBox() {
            $('.checkbox-creat-new-wrapper').show();
        }

        function editMetaData(_this, id) {
            var _this = $(_this).closest('tr');

            System.blockUI(_this);
            System.setAjaxRequest(
                url.metaEditUrl+id,
                '',
                'GET',
                function(response) {
                    if (response.status == 200)
                    {   
                        showFieldInputWrapper();
                        hideCreateNewCheckBox();
                        $(formMeta)
                            .find('input[name="meta_key"]')
                                .val(response.data.meta_key);

                        $(formMeta)
                            .find('input[name="meta_value"]')
                                .val(response.data.meta_value);

                        $(formMeta)
                            .find('input[name="id"]')
                                .val(response.data.id);

                    }
                    System.unblockUI();
                },
                function() {
                    Alert.error('Error', System.errorTextMessage);
                    System.unblockUI();
                }
            );
        }

        function deleteMetaData(_this, id) {
            var _this = $(_this).closest('tr');

            Alert.confirm(
                'Delete','Are you sure you want to delete this?',
                'Yes',
                'No',
                function(instance, toast) {
                    System.blockUI(_this);
                    System.setAjaxRequest(
                        url.metaDelete+{{$id}}+'/'+id,
                        '',
                        'DELETE',
                        function(response) {
                            if (response.status == 200)
                            {   
                                System.unblockUI();
                                if (response.success) {
                                    _this.remove();
                                    $('#meta-table-container .card-body').html(response.table);
                                    Alert.success('Success!',response.message);
                                    return;
                                } 
                                Alert.error('Error!',response.message);
                            }
                        },
                        function() {
                            Alert.error('Error', System.errorTextMessage);
                            System.unblockUI();
                        }
                    );
                },
                function(instance, toast) {

                }
            );
        }

    </script>
@endsection