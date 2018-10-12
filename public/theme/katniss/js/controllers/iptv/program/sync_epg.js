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
app.controller('SyncEPG',['$scope','WebService',function($scope,WebService){
    $scope.programId = programId;  
    $scope.loading = 0; 
    
    $scope.closeAlert = function(){
       $scope.warning_messages = '';
       $scope.success_messages = '';
    };
    
    $scope.syncEpg = function(){
        $scope.closeAlert();
        
        $scope.loading = 1; 
        var formData = {date:$scope.date,tag_id:$scope.tag_id,channel_id:$scope.programId};
        var http = WebService.post('channels/download-epg',formData);
        http.then(function(response){
             var data = response.data;
             if(data.status==200){
                 $scope.success_messages = "EPG Download completed successfully";
                 $scope.loading = 0; 
             }else{
                 $scope.warning_messages = "some issue occured try again later";
             }
        });
    };
}]);