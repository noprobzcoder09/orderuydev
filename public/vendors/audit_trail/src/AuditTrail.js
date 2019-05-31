(function(){
    "use strict";

    angular.module('AuditTrail', ['ngAnimate', 'ui.bootstrap'])

    .config(function($interpolateProvider, $httpProvider) {
        $interpolateProvider.startSymbol('<%');
        $interpolateProvider.endSymbol('%>');
    })

    .constant('BASE', {
        
        'API_URL': window.__env.API_URL,
        
        'ASSETS_URL': 'vendors/audit_trail/dist/templates/',
        
    })

    .filter('firstLetterCapitalize', FirstLetterCapitalize)

    .service('BlockUI', BlockUI)
    
    .factory('AuditTrailRepository', AuditTrailRepository)

    .factory('ActivityLogCredentialsRepository', ActivityLogCredentialsRepository)

    .directive('searchViaEnterButton', SearchViaEnterButton)

    .directive('roleLabel', RoleLabel)

    .directive('auditTrail', AuditTrail)

    .directive('viewAuditTrail', ViewAuditTrail)

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

    function AuditTrail(BASE) {

        return {
            restrict: 'EA',
            scope: {
                customerId: '@'
            },
            controller: AuditTrailController,
            templateUrl: BASE.API_URL + BASE.ASSETS_URL + 'index.html',
            link: function (scope, element, attributes) {  

            }
        }

    }

    function AuditTrailController($scope, AuditTrailRepository, $timeout, $filter, $uibModal, BASE) {

        $scope.isLoading = false;

    
        $scope.doSearch = function(){
            $scope.data();
        }

        $scope.pagination = {
            totalItems: 0,
            currentPage: 1,
            itemsPerPage: 10,
            maxSize: 5,
            search: '',
            objects: [],
        }


        $scope.setPage = function (pageNo) {
            $scope.pagination.currentPage = pageNo;
        };

        $scope.pageChanged = function() {
            $scope.data();
        };

        $scope.data = function(callback = false){
            $scope.isLoading = true;
            var params = {
                page: $scope.pagination.currentPage,
                rows: $scope.pagination.itemsPerPage,
                search: $scope.pagination.search,
                customer_id: $scope.customerId ? $scope.customerId : null,
            };
            
            AuditTrailRepository.get(params).then(function(response){

                let res = response.data.success.data;

                $scope.pagination.objects = res.data;
                $scope.pagination.totalItems = res.total;

                $scope.isLoading = false;

                if (callback) {
                    callback();
                }

            });
            
        }

        $scope.setItemsPerPage = function(num) {
            $scope.pagination.itemsPerPage = num;
            $scope.pagination.currentPage = 1; //reset to first paghe
            $scope.data();
        }

        $timeout(function(){
            $scope.data();
        });

        $scope.roleClass = function(role){

            let class_name;

            if (role == 'customer') {
              class_name = "badge badge-pill badge-primary";
            }else if (role == 'administrator') {
              class_name = "badge badge-pill badge-success";
            }else if (role == 'restricted admin') {
              class_name = "badge badge-pill badge-warning";
            }

            return class_name;
        }

        $scope.viewTransaction = function(transaction){

            $uibModal.open({
                animation: true,
                templateUrl: BASE.API_URL + 'vendors/audit_trail/dist/templates/view.html',
                backdrop: 'static',
                controller: function($uibModalInstance, $scope, BlockUI, $timeout, AuditTrailRepository){


                    $timeout(function(){ 
                        $scope.isLoading = true; 
                        BlockUI.open('.modal-body');
                        $scope.retrieve(); 
                    });

                    $scope.data = {};

                    $scope.retrieve = function(){
                        AuditTrailRepository.show(transaction.id).then(function(response){
                            let res = response.data.success;
                            $scope.data = res.data;

                            BlockUI.close('.modal-body');
                        });
                    }

                    $scope.roleClass = function(role){

                        let class_name;

                        if (role == 'customer') {
                          class_name = "badge badge-pill badge-primary";
                        }else if (role == 'administrator') {
                          class_name = "badge badge-pill badge-success";
                        }else if (role == 'restricted admin') {
                          class_name = "badge badge-pill badge-warning";
                        }

                        return class_name;
                    }

                    $scope.close = function(){
                        $uibModalInstance.dismiss('close');
                    }
                    // if ($scope.customerId ) {
                    //     window.location = "/customers/" + $scope.customerId  + "/audit/logs/" + transaction.id;
                    // }else{
                    //     window.location = "/audit/logs/" + transaction.id;
                    // }

                },
                size: 'lg'
            });
        }


        $scope.deleteLog = function(log, callback = false){


            $uibModal.open({
                animation: true,
                templateUrl: BASE.API_URL + 'vendors/audit_trail/dist/templates/delete-confirmation.html',
                backdrop: 'static',
                controller: function($uibModalInstance, $scope, BlockUI, $timeout, ActivityLogCredentialsRepository){

                    $scope.alert = {
                        is_visible: false,
                        status: '',
                        message: ''
                    };

                    $timeout(function(){ 
                        $scope.isLoading = true; 
                        BlockUI.open('.modal-body');

                        $scope.loaded();
                    });

                    $scope.loaded = function(){
                        $scope.isLoading = false; 
                        BlockUI.close('.modal-body');
                    }

                    $scope.data = {};

                    $scope.deleteNow = function(){

                        $scope.isLoading = true; 
                        BlockUI.open('.modal-body');

                        ActivityLogCredentialsRepository.authenticate({password: $scope.password, id: log.id}).then(function(response){

                            $scope.alert.is_visible = true;
                            $scope.alert.status = 'success';
                            $scope.alert.message = response.data.success.message;

                            $timeout(function(){
                                $scope.isLoading = false; 
                                BlockUI.close('.modal-body');
                                $scope.close();
                            }, 2000);

                            if (callback) {
                                callback();
                            }

                        }, function(response){

                            $scope.isLoading = false; 
                            BlockUI.close('.modal-body');

                            $scope.alert.is_visible = true;
                            $scope.alert.status = 'danger';
                            $scope.alert.message = response.data.error.message;

                            $timeout(function(){
                                $scope.alert.is_visible = false;
                            }, 2000);
                        })
                    }

                    $scope.close = function(){
                        $uibModalInstance.close(false);
                    }

                },
                size: 'sm'
            });
        }


    }


    function ViewAuditTrail(BASE) {

        return {
            restrict: 'EA',
            scope:{
                id: '@',
                customerId: '@',
            },
            controller: ViewAuditTrailController,
            templateUrl: BASE.API_URL + BASE.ASSETS_URL + 'view.html',
            link: function (scope, element, attributes) {  

            }
        }

    }

    function ViewAuditTrailController($scope, AuditTrailRepository, $timeout, $filter, BlockUI) {

        $timeout(function(){ 
            $scope.isLoading = true; 
            BlockUI.open('#user-details .card-body, #ip-address-details .card-body, #activity-details .card-body');
            $scope.retrieve(); 
        });

        $scope.data = {};

        $scope.retrieve = function(){
            AuditTrailRepository.show($scope.id).then(function(response){
                let res = response.data.success;
                $scope.data = res.data;

                BlockUI.close('#user-details .card-body, #ip-address-details .card-body, #activity-details .card-body');
            });
        }

        $scope.roleClass = function(role){

            let class_name;

            if (role == 'customer') {
              class_name = "badge badge-pill badge-primary";
            }else if (role == 'administrator') {
              class_name = "badge badge-pill badge-success";
            }else if (role == 'restricted admin') {
              class_name = "badge badge-pill badge-warning";
            }

            return class_name;
        }

        $scope.goBack = function(){
            if ($scope.customerId ) {
                window.location = "/customers/" + $scope.customerId  + "/audit/logs";
            }else{
                window.location = "/audit/logs";
            }
        }

    }


    function AuditTrailRepository(BASE, $http) {

        var url = BASE.API_URL + 'api/v1/audit/logs';
        var repo = {};

        repo.get = function(params){
            return $http.get(url, {params});
        }

        repo.show = function(id){
            return $http.get(url + '/' + id);
        }

        return repo;
    }

    function ActivityLogCredentialsRepository(BASE, $http) {

        var url = BASE.API_URL + 'api/v1/audit/credentials';
        var repo = {};

        repo.authenticate = function(params){
            return $http.post(url, params);
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

    function RoleLabel($compile) {

      return {
        restrict: 'EA',
        scope: {
          role: '='
        },
        link: function (scope, iElement, iAttrs) {

        console.log(scope.role);

          function refreshElement() {

            var template = '';

            // $(iElement).pulsate({
            //     color: "#36c6d3"
            // })

            if (scope.role == 'customer') {
              template = '<span class="badge badge-pill badge-primary">Customer</span>';
            }else if (scope.role == 'administrator') {
              template = '<span class="badge badge-pill badge-success">Administrator</span>';
            }else if (scope.role == 'restricted admin') {
              template = '<span class="badge badge-pill badge-warning">Restricted Admin</span>';
            }

            var content = $compile(template)(scope);
            iElement.replaceWith(content);
          }

          scope.$watch(scope.role, function(){
            refreshElement();
          });
        }
      };

    }

    function FirstLetterCapitalize() {
        return function(input) {
          return (!!input) ? input.charAt(0).toUpperCase() + input.substr(1).toLowerCase() : '';
        }
    }
  
})();