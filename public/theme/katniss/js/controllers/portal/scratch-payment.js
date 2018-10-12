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
app.controller('ScratchPayment',['$scope','WebService',function($scope,WebService){
    $scope.subscriber_id = user_id;
    $scope.pairings = [];
    $scope.user_id = user_id;
    $scope.user_type = user_type;
    $scope.token = token;
    $scope.formData = {subscriber_id:$scope.subscriber_id,pairing_id:'',serial_no:'',card_no:''};
    var loadSubscriberPairings = function()
    {
        if($scope.subscriber_id !=null) {
            var http = WebService.get('profile/ajax-get-pairing-id/' + $scope.subscriber_id);
            http.then(function (response) {
                var data = response.data;
                if (data.status == 200) {
                    $scope.pairings = data.pairings;
                }
            });
        }
    };

    var formValidation = function(){
        var regex = /^\d+$/;
        if(!regex.test($scope.formData.serial_no)){
            $scope.warning_messages = 'Sorry! Serial No should be numeric';
            return false;
        }

        if(!regex.test($scope.formData.card_no)){
            $scope.warning_messages = 'Sorry! Card No should be numeric';
            return false;
        }

        return true;
    };

    $scope.setPairingID = function(){
        if($scope.pairings.length>0){
            $scope.pairings.filter(function(obj){
                if(obj.id == $scope.formData.pairing_id){
                    $scope.formData.stb_card_id = obj.pairing_id;
                }
            });
        }

    };

    $scope.saveScratchPayment = function(){
        var isValid = formValidation();
        if(!isValid)
        {
            return isValid;
        }

        var http = WebService.post('profile/payment',$scope.formData);
        http.then(function(response){
            var data = response.data;
            if(data.status == 400){
                $scope.warning_messages =  data.warning_messages;
                $scope.success_messages = '';
                $("html,body").animate({scrollTop:"0px"});
            }else{
                $scope.success_messages = data.success_messages;
                $scope.warning_messages = '';
                $scope.loadNotification();
                $("html,body").animate({scrollTop:"0px"});
                //window.location = data.redirect_to;
                $scope.formData = {subscriber_id:token,pairing_id:'',serial_no:'',card_no:''};
            }
        });
    };

    $scope.$emit('loadSubscriberBalance',{user_type:$scope.user_type,user_id:$scope.user_id,token:$scope.token});

    $scope.closeAlert = function(){
      $scope.warning_messages = $scope.success_messages = $scope.error_messages = '';
    };

    loadSubscriberPairings();


}]);
