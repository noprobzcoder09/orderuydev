(function(){
    "use strict";

    angular.module('ApiSetting', ['ngAnimate', 'ui.bootstrap'])

    .config(function($interpolateProvider, $httpProvider) {
        $interpolateProvider.startSymbol('<%');
        $interpolateProvider.endSymbol('%>');
    })

    .constant('BASE', {
        
        'API_URL': window.__env.API_URL,
        
        'ASSETS_URL': 'vendors/api_settings/templates/',
        
    })

    .filter('firstLetterCapitalize', FirstLetterCapitalize)

    .service('BlockUI', BlockUI)
    
    .factory('ApiRepository', ApiRepository)

    .directive('searchViaEnterButton', SearchViaEnterButton)

    .directive('apiSetting', ApiSetting)

    function SearchViaEnterButton() {

        return {
            restrict: 'EA',
            link: function (scope, element, attributes) {

                element.bind('keydown keypress', function(event){
                  if (event.which === 13) {
                    scope.$apply(function(){
                      scope.$eval(attributes.myEnter);
                    });

                    event.preventDefault();
                  }
                });

            }

        }

    }

    function ApiSetting(BASE) {

        return {
            restrict: 'EA',
            scope: {
                envType: '@',
                reauthStatus: '@'
            },
            controller: ApiSettingController,
            templateUrl: BASE.API_URL + BASE.ASSETS_URL + 'index.html',
            link: function (scope, element, attributes) {  

            }
        }

    }

    function ApiSettingController($scope, ApiRepository, $timeout, $filter, $uibModal, BASE, BlockUI) {

        $scope.api = {
            'app_name': '',
            'version': '',
            'legacy_key': '',
            'client_id': '',
            'client_secret': '',
            'access_token': '',
            'refresh_token': '',
            'expires_in': '',
            'scope': '',
            'redirect_url': '',
            'environment': '',
        };
        
        $scope.alert = {
            type: '',
            message: '',
            visible: false,
        }

        $timeout(function(){
            $scope.isLoading = true; 
            BlockUI.open('.m-portlet__body');
            BlockUI.open('.m-portlet__head-tools .btn');
            $scope.loadDefault();
            $scope.reauthSuccessful();
        })

        $scope.loadDefault = function(){

            ApiRepository.show($scope.envType).then(function(response){

                let response_data = response.data.success.data;

                $scope.api = response_data;

                $scope.isLoading = false; 
                BlockUI.close('.m-portlet__body');
                BlockUI.close('.m-portlet__head-tools .btn');
          
            });
        }

        $scope.saveApiSetting = function(){

            $scope.isLoading = true; 
            BlockUI.open('.m-portlet__body');
            BlockUI.open('.m-portlet__head-tools .btn');

            ApiRepository.patch($scope.api.id, $scope.api).then(function(response){

                let response_data = response.data;

                $scope.isLoading = false; 
                BlockUI.close('.m-portlet__body');
                BlockUI.close('.m-portlet__head-tools .btn');

                $scope.alert.type       = 'success';
                $scope.alert.message    = response_data.success.message;
                $scope.alert.visible    = true;


            }, function(response){
                
                let response_data = response.data;

                $scope.alert.type       = 'danger';
                $scope.alert.message    = response_data.message;
                $scope.alert.visible    = true;

                $scope.isLoading = false; 
                BlockUI.close('.m-portlet__body');
                BlockUI.close('.m-portlet__head-tools .btn');

            })
        }

        $scope.reauth = function(){

            const swalWithBootstrapButtons = Swal.mixin({
              customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
              },
              buttonsStyling: false,
            })

            swalWithBootstrapButtons.fire({
              title: 'Are you sure?',
              text: "You will receive a new access token and refresh token!",
              type: 'warning',
              showCancelButton: true,
              confirmButtonText: 'Yes, Re-Authenticate it!',
              cancelButtonText: 'No, cancel!',
              reverseButtons: true
            }).then((result) => {
              if (result.value) {

                window.open(BASE.API_URL + 'infusionsoft/oauth/authenticate', '_blank');

                swalWithBootstrapButtons.fire(
                  'Success!',
                  'You have a new access token and refresh token. You are authenticated.',
                  'success'
                )
              } else if (
                // Read more about handling dismissals
                result.dismiss === Swal.DismissReason.cancel
              ) {
                swalWithBootstrapButtons.fire(
                  'Cancelled',
                  'Your previous api setting is safe :)',
                  'error'
                )
              }
            })
        }

        $scope.reauthSuccessful = function(){

            console.log($scope.reauthStatus);

            if ($scope.reauthStatus == 200) {

                const swalWithBootstrapButtons = Swal.mixin({
                  customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                  },
                  buttonsStyling: false,
                })

                swalWithBootstrapButtons.fire(
                  'Success!',
                  'You have a new access token and refresh token. You are authenticated.',
                  'success'
                )

                $scope.loadDefault();
            }
        }

    }


    function ApiRepository(BASE, $http) {

        var url = BASE.API_URL + 'api/v1/settings/api';
        var repo = {};

        repo.show = function(env_type){
            return $http.get(url + '/' + env_type);
        }

        repo.patch = function(id, params){
            return $http.patch(url + '/' + id, params);
        }

        return repo;
    }

    function BlockUI() {

        this.open = function(element, message) {
            var message = message == undefined ? '<div class="fa-spinner fa fa-spin fa-lg font-medium-2"></div>' : message;
            $(element).block({
                message: message,//'<div class="fa-spinner fa fa-spin font-medium-2"></div>',
                // timeout: 2000, //unblock after 2 seconds
                overlayCSS: {
                    backgroundColor: '#fff',
                    opacity: 0.8,
                    cursor: 'wait'
                },
                css: {
                    border: 0,
                    padding: 0,
                    backgroundColor: 'transparent'
                }
            });
        }
        
        this.close = function(element, time) {
            $(element).unblock();
        }

    }

    function FirstLetterCapitalize() {
        return function(input) {
          return (!!input) ? input.charAt(0).toUpperCase() + input.substr(1).toLowerCase() : '';
        }
    }
  
})();
