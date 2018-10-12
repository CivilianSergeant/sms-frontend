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
app.controller('BkashPayment',['$scope','WebService',function($scope,WebService){
    $scope.user_id = user_id;
    $scope.user_type = user_type;
    $scope.formData = {};
    $scope.lco = [];
    $scope.subscribers = [];
    $scope.pairings = [];
    $scope.pairing_id = null;
    $scope.subscriber_id = subscriber_id;
    if($scope.user_type != 'MSO'){
        $scope.formData.lco_id = $scope.user_id;
    }

    var loadAllLco = function(){

        var http = WebService.get('payments-bkash/ajax-get-lco');
        http.then(function(response){
            var data = response.data;
            if(data.status == 200)
            {
                $scope.lco = data.lco;
                $scope.subscribers =[];
                $scope.formData.subscriber_id = null;
                $scope.formData.pairing_id = null;
            }
        });
    };

    $scope.loadSubscribers = function(id){

        var http = WebService.get('payments-bkash/ajax-get-subscriber/'+id);
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.subscribers   = data.subscribers;
                $scope.pairings =[];
                $scope.formData.subscriber_id = null;
                $scope.formData.pairing_id = null;
                if($scope.subscriber_id != ""){
                    $scope.formData.subscriber_id = $scope.subscriber_id;
                    $scope.getPairingId();
                }
            }
        });
    };

    $scope.getPairingId = function(){
        if($scope.formData.subscriber_id != null){
            var http = WebService.get('payments-bkash/ajax-get-pairing-id/'+$scope.formData.subscriber_id);
            http.then(function(response){
                var data = response.data;
                if(data.status == 200){
                    $scope.pairings = data.pairings;
                }
            });
        }
    };

    $scope.setPairingId = function(){
        $scope.pairings.filter(function(obj){

            if(obj.id == $scope.formData.stb_card_id){
                $scope.pairing_id = obj.pairing_id;
            }
        });
    };

    $scope.saveBkashPayment = function(){
        var http = WebService.post('payments-bkash/payment',$scope.formData);
        http.then(function (response) {
            var data = response.data;
            if(data.status == 400){
                $scope.warning_messages = data.warning_messages;
                $scope.success_messages = '';
                $("html,body").animate({scrollTop:"0px"});
            }else{
                $scope.success_messages = data.success_messages;
                $scope.warning_messages = '';
                $scope.loadNotification();

                $("html,body").animate({scrollTop:"0px"}, function(){
                    window.location = data.redirect_to;
                });
            }
        });
    };

    loadAllLco();

    if($scope.user_type == 'MSO'){
        if($scope.subscriber_id != ""){
            $scope.formData.lco_id = -1;
            $scope.loadSubscribers(1);
        }

    }
    if($scope.user_type == 'LCO'){

        $scope.loadSubscribers($scope.formData.lco_id);
    }


}]);
