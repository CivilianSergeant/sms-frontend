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
app.controller('MsoDashboard',['$scope','WebService',function($scope,WebService){

}]);
app.controller('MsoDashboard',['$scope','WebService',function($scope,WebService){

}]);
app.controller('SubscriberDashboard',['$scope','WebService',function($scope,WebService){
    $scope.user_id = user_id;
    $scope.token = token;
    $scope.user_type = user_type;
    $scope.packages = [];
    $scope.add_ons = [];

    $scope.$emit('loadSubscriberBalance',{user_type:$scope.user_type,user_id:$scope.user_id,token:$scope.token});

    $scope.getAvailablePackages = function(){
        var http = WebService.get('dashboard/ajax_get_available_packages');
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.packages = data.packages;
                $scope.add_ons = data.add_ons;
            }
        });
    };

    $scope.getAvailablePackages();
}]);

