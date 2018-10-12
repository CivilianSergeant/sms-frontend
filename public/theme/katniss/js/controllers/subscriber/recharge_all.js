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
app.controller('RechargeAll',['$scope','WebService',function($scope,WebService){
        
    $scope.formData = {recharge_filter: 1};
    var resetMessage = function()
    {
        $scope.success_messages = '';
        $scope.warning_messages = '';
        $scope.error_messages = '';
    };
    
    $scope.closeAlert = function(){
        resetMessage();
    };
    
    $scope.rechargeAll = function(i){
        if($scope.formData.is_cache_received == true){
            var is_cash_receive = 1;
        }else{
            is_cash_receive = 0;
        }
        var formData = {
            amount: $scope.formData.amount,
            is_payment_received: is_cash_receive,
            recharge_filter: $scope.formData.recharge_filter,
        }
        var http = WebService.post('subscriber/save-recharge-all',formData);
        http.then(function(response){
            var data = response.data;
            if(data.status==400){
                $scope.warning_messages = data.warning_messages;
                $scope.success_messages = '';
            } else {
                $scope.success_messages = data.success_messages;
                $scope.warning_messages = '';
                $scope.formData = '';
            }
            $("html,body").animate({scrollTop:'0px'});
        });
    };
    
    var formValidate = function(){
      $scope.$watch('formData.amount',function(val){
          
            var pattern = /[0-9]/
                if(val != null)
                {
                    if(!pattern.test(val)){
                        $scope.warning_messages = 'Amount should be numerice value';
                        $scope.formData.amount = '';
                        $("html,body").animate({scrollTop:"0px"});
                    }
                }
        });  
    };
    
    formValidate();


}]);
