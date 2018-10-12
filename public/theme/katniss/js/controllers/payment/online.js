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
app.controller('OnlinePayment',['$scope','WebService',function($scope,WebService){
    $scope.user_id = user_id;
    $scope.formData = {};
    $scope.lco = [];
    $scope.subscribers = [];
    $scope.pairings = [];

    var loadAllLco = function(){

        var http = WebService.get('payments-online/ajax-get-lco');
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

    $scope.loadSubscribers = function(){

        var http = WebService.get('payments-online/ajax-get-subscriber/'+$scope.formData.lco_id);
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.subscribers   = data.subscribers;
                $scope.pairings =[];
                $scope.formData.subscriber_id = null;
                $scope.formData.pairing_id = null;

            }
        });
    };

    $scope.getPairingId = function(){

        var http = WebService.get('payments-online/ajax-get-pairing-id/'+$scope.formData.subscriber_id);
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.pairings = data.pairings;
            }
        });
    };

    loadAllLco();



}]);
