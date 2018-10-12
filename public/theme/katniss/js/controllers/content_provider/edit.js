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

app.controller('EditContentProvider',['$scope','WebService',function($scope,WebService){

    $scope.epgId = epgId;

    $scope.closeAlert = function(){
        $scope.success_messages = '';
        $scope.warning_messages = '';
        $scope.error_messages = '';
    };
    $scope.formData = {};
    $scope.formData.duration = 30;
    $scope.formData.repeats = [];

    $scope.addRow = function(){
        var repeats = {repeat_date:'',repeat_time:''};
        $scope.formData.repeats.push(repeats);
    };

    $scope.hideForm = function(){
        $scope.showFrm = 0;
    };

    $scope.showForm = function()
    {
        if($scope.permissions.create_permission == '1'){
            $scope.showFrm = 1;
        }else{
            $scope.warning_messages = "Sorry! You don't have permission to create Set-top Box";
        }

    };

    var loadPermissions = function(){
        var http = WebService.get('manage-epg/ajax-get-permissions');
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.permissions=data.permissions;
            }
        });
    };

    loadPermissions();

    var getContent_aggregator = function(){
        
        
        var http = WebService.get('content-provider/content_aggregator');
        http.then(function(response){
           var data = response.data;
            if(data.status==200){

                $scope.content_aggregator = data.content_aggregator;

            }


        });
    };
    
    getContent_aggregator();

    var loadEpg = function(){
        var http = WebService.get('content-provider/ajax-get-content-provider/'+$scope.epgId);
        http.then(function(response){
            var data = response.data;
            if(data.status==200){
                $scope.formData = data.content_provider;

            }
        });


    };

    loadEpg();


    

    

    $scope.saveContent_provider = function(){


        var http = WebService.post('content-provider/update-content-provider',$scope.formData);
        http.then(function(response){
           var data = response.data;
            if(data.status==400){
                $scope.warning_messages = data.warning_messages;
                $("html,body").animate({scrollTop:'0px'});
            }else{
                $scope.success_messages = data.success_messages;
                $("html,body").animate({scrollTop:'0px'});
                window.location = SITE_URL+'content-provider';
            }

        });
    };

    $scope.deleteRepeatRow = function(i){
        if($scope.formData.repeats  != undefined){
            $scope.formData.repeats.splice(i,1);
        }
    };

}]);