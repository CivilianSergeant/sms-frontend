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
app.controller('notificationCTL',['$scope','WebService',function($scope,WebService){
	
	$scope.device_groups = [];
        $scope.fcm_tokens = [];
        $scope.notificationTypes = ['SMALL','LARGE','LOGOUT'];
        $scope.formData = {};
        
        var loadAllDeviceGroups = function(){
            var http = WebService.get('tools-send-notification/ajax-get-device-groups');
            http.then(function(response){
                var data = response.data;
                if(data.status == 200)
                {
                    $scope.device_groups = data.fcm_groups;
                }
            });
	};

	loadAllDeviceGroups();
        
        $scope.loadDevice = function(){
            console.log($scope.device_group_id);
            var http = WebService.get('tools-send-notification/ajax-get-devices/'+$scope.formData.device_group_id);
            http.then(function(response){
               var data = response.data;
               
               if(data.status == 200)
                {
                    $scope.fcm_tokens = data.devices;
                }
               
            });
        };
        
        $scope.closeAlert = function(){
            $scope.warning_messages = '';
            $scope.success_messages = '';
            $scope.error_messages = '';
        };
        
        $scope.sendNotification = function(){
                $scope.closeAlert();
                $scope.formData.img = $("#img").attr('src');
                $scope.formData.imgType = $("#img").attr('data-type');
                var http = WebService.post('tools-send-notification/send-fcm-notification',$scope.formData);
                http.then(function(response){
                    var data = response.data;
                    
                    if(data.status == 200){
                        $scope.success_messages = "Successfull "+data.result; 
                    }else{
                        $scope.warning_message = 'Sorry! Try again later';
                    }
                    
                    $("html,body").animate({scrollTop:'0px'});
                });
        };

}]);