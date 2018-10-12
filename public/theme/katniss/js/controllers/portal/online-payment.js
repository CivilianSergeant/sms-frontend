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
    $scope.user_type = user_type;
    $scope.token = token;

    var loadSubscriberPairings = function()
    {
        if($scope.user_id !=null) {
            var http = WebService.get('profile/ajax-get-pairing-id/' + $scope.user_id);
            http.then(function (response) {
                var data = response.data;
                if (data.status == 200) {
                    $scope.pairings = data.pairings;
                }
            });
        }
    };

    loadSubscriberPairings();

    $scope.$emit('loadSubscriberBalance',{user_type:$scope.user_type,user_id:$scope.user_id,token:$scope.token});

}]);