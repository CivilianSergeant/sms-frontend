var app = angular.module('plaasApp');
app.factory('WebService',function($http){
    return {
        get:function(url){
            return $http({
                method:"POST",
                url : SITE_URL+url,
                headers:{'X-Requested-With':'XMLHttpRequest',
                         'Content-Type': 'application/x-www-form-urlencoded'},
            });
        },
        post:function(url,data){
            return $http({
                method:"POST",
                url : SITE_URL+url,
                headers:{'X-Requested-With':'XMLHttpRequest',
                         'Content-Type': 'application/x-www-form-urlencoded'},
                data:$.param(data)
            });
        }
        
    };
});
app.controller('RechargeCTRL',['$scope','WebService',function($scope,WebService){
    $scope.segment = (segment != undefined && segment !="")? segment+'/':'';

    var loadPaymentMehtods = function(){
        var http = WebService.get($scope.segment+'subscriber-recharge/ajax_load_payment_methods');
        http.then(function(response){
            var data = response.data;
            $scope.payment_methods = data.payment_methods;
        });
    };

    var loadSubscribers = function(){
        var http = WebService.get($scope.segment+'subscriber-recharge/ajax_load_subscribers');
        http.then(function(response){
            var data = response.data;
            $scope.subscribers = data.subscribers;
        });
    };

    $scope.closeAlert = function(){
        $scope.warning_messages = $scope.success_messages = $scope.error_messages = '';
    };

    $scope.accountRecharge = function(){
        var http = WebService.post($scope.segment+'subscriber-recharge/ajax_get_payment_url',$scope.recharge);
        http.then(function(response){
            var data = response.data;
            if(data.status == 400){
                $scope.warning_messages = data.warning_messages;
                $scope.success_messages = '';
            } else {
                window.location = data.redirect_to;
                $scope.success_messages = data.success_messages;
                $scope.warning_messages = '';
                $scope.recharge = {};
            }

        });
    };

    loadSubscribers();
    
    loadPaymentMehtods();
}]);
app.controller('RechargeSubscriber',['$scope','WebService',function($scope,WebService){

   $scope.segment = (segment != undefined)? segment+'/':'';

    var loadPaymentMehtods = function(){
        var http = WebService.get($scope.segment+'subscriber-recharge/ajax_load_payment_methods');
        http.then(function(response){
            var data = response.data;
            $scope.payment_methods = data.payment_methods;
        });
    };

    $scope.closeAlert = function(){
        $scope.warning_messages = $scope.success_messages = $scope.error_messages = '';
    };

    $scope.accountRecharge = function(){
        $scope.recharge.subscriber_user_id = id;
        
        var http = WebService.post($scope.segment+'subscriber-recharge/ajax_get_payment_url',$scope.recharge);
        http.then(function(response){
            var data = response.data;
            console.log(data);
            if(data.status == 400){
                $scope.warning_messages = data.warning_messages;
                $scope.success_messages = '';
            } else {
                window.location = data.redirect_to;
                /*$scope.success_messages = data.success_messages;
                $scope.warning_messages = '';
                $scope.recharge = {};*/
            }

        });
    };


    loadPaymentMehtods();
}]);


