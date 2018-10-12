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
app.controller('GroupAssign',['$scope','WebService','FileUploader',function($scope,WebService,FileUploader){

    $scope.groups = [];
    $scope.lcos = [];
    $scope.assigned_lcos = [];
    $scope.selected_item = [];
    $scope.included_item = [];
    $scope.formData ={};

    var loadGroups = function(){
        var http = WebService.get('groups/ajax-load-profiles');
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.groups = data.profiles;
            }
        });
    };

    var loadLco = function(){
        var http = WebService.get('groups/ajax-get-lco');
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.lcos = data.lco;
            }
        });
    };

    loadGroups();
    loadLco();

    $scope.IncludeItems = function(){

        for(p in $scope.lcos){
            for(item in $scope.selected_item){
                if($scope.lcos[p].user_id == $scope.selected_item[item])
                {

                    $scope.assigned_lcos.push($scope.lcos[p]);
                    $scope.lcos.splice(p,1);
                }
            }
        }

    };

    $scope.ExcludeItems = function(){

        for(ap in $scope.assigned_lcos){
            for(item in $scope.included_item){
                if($scope.assigned_lcos[ap].user_id == $scope.included_item[item])
                {
                    $scope.lcos.push($scope.assigned_lcos[ap]);
                    $scope.assigned_lcos.splice(ap,1);
                }
            }
        }

    };

    $scope.loadGroupLco = function(){
        var http = WebService.get('assign-lco/ajax-get-group-lco/'+$scope.formData.group_id);
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.assigned_lcos = data.assigned_lcos;
                $scope.lcos = data.lcos;
            }
        });
    };

    $scope.assignLco = function(){
        $scope.formData.included_list=$scope.assigned_lcos;

        var http = WebService.post('groups/assign-lco-to-group',$scope.formData);
        http.then(function(response){
            var data = response.data;
            if(data.status == 400){
                $scope.warning_messages = data.warning_messages;
                $scope.success_messages = '';
            }else{
                $scope.success_messages = data.success_messages;
                $scope.warning_messages = '';
            }

            $("html,body").animate({scrollTop:'0px'});
        });
    };

    $scope.closeAlert=function(){
        $scope.success_messages = '';
        $scope.warning_messages = '';
        $scope.error_messages = '';
    };

}]);
