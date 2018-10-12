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
app.controller('SyncEPGCustom',['$scope','WebService',function($scope,WebService){
    
    $scope.loading = 0; 
    var today = new Date();
    var fullYear = today.getFullYear();
    var month = today.getMonth()+1;
    month = (month<9)? ('0'+month) : month;
    var day   = today.getDate();
    day = (day<9)? ('0'+day) : day;
    
    $scope.formData = {date:fullYear+'-'+month+'-'+day,tag:'Auto'};
    $scope.channels = [];
    
    $scope.closeAlert = function(){
       $scope.warning_messages = '';
       $scope.success_messages = '';
    };
    
    $scope.loadMapping = function(){
        $scope.closeAlert();
        var http = WebService.post('sync-epg/ajax-get-mappings',$scope.formData);
        http.then(function(response){
            
            var data = response.data;
            console.log(data);
            if(data.status == 200){
                $scope.channels = data.mappings;
            }
        });
    };
    
    $scope.syncEpg = function(){
        $scope.closeAlert();
        
        $scope.loading = 1; 
        var formData = {date:$scope.formData.date,tag_id:$scope.formData.tag,channel_id:$scope.formData.providerChannel};
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