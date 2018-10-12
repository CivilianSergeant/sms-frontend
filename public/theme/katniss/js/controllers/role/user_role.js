/**
 * Created by user on 1/27/2016.
 */
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
app.controller('UserRole',['$scope','WebService',function($scope,WebService){

    $scope.showFrm = 0;
    $scope.user_types = ['MSO','LCO','Subscriber'];
    $scope.role_types = ['Admin','Staff'];
    $scope.formData = {role_name:'',user_type:'',role_type:'Staff'};

    $scope.showForm = function(){
        $scope.showFrm = 1;
    };
    $scope.hideForm = function(){
        $scope.showFrm = 0;
    };

    $scope.setRoleType = function(){

        if($scope.formData.user_type == "MSO" || $scope.formData.user_type == "LCO"){
            $scope.role_types = ['Admin','Staff'];
        }else{
            $scope.role_types = ['Subscriber'];
        }
    };

    $scope.saveRole = function(){


        //console.log($scope.formData);
        var http = WebService.post('user-role/create',$scope.formData);
        http.then(function(response){
            var data = response.data;
            if(data.status == 400){
                $scope.warning_messages = data.warning_messages;
                $scope.success_messages = '';
            }else{
                $scope.success_messages = data.success_messages;
                $scope.warning_messages = '';
                window.location = SITE_URL+'permissions/'+data.role_id;
                $scope.loadNotification();
            }
        });
    };


    var generateKendoGird = function(){
        $scope.mainGridOptions = {
            dataSource: {
                transport: {
                    read: {
                        url: "user-role/ajax_get_roles",
                        dataType: "json",
                    }
                },
                schema: {
                    data: "roles",
                    total: "total"
                },

                pageSize: 10,
                serverPaging: true,
                serverSorting:true,
                serverFiltering: true,
            },
            sortable: true,
            pageable: true,
            scrollable: true,
            resizable: true,

            filterable: {
                extra: false,
                operators: {
                    string: {
                        startswith: "Starts with",
                        eq: "Is equal to",

                    }
                }
            },
            dataBound: gridDataBound,
            columns: [
                {field: "role_name", title: "Role Name",width: "auto"},
                {field: "user_type", title: "USER Type",width: "auto",template:"<span style='text-transform: uppercase;'>#=data.user_type#</span>"},
                {field: "role_type", title: "ROLE Type",width: "auto",template:"<span style='text-transform: uppercase;'>#=data.role_type#</span>"},


                {field: "", title: "Action",width: "auto",template:'#if(data.id > 5){# <a ng-if="permissions.edit_permission==1" href="'+SITE_URL+'user-role/edit/#=data.id#" class="btn btn-default btn-xs" data-toggle="tooltip" data-placement="left" title="Edit"><i class="fa fa-pencil"></i></a> #} if(data.is_assigned<=0 && data.id > 5){# <a ng-if="(permissions.delete_permission==\'1\')" href="'+SITE_URL+'user-role/delete/#=data.id#" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="left" title="Edit"><i class="fa fa-trash"></i></a>#}#'},
            ]
        };
    };

    generateKendoGird();

    var loadPermissions = function(){
        var http = WebService.get('user-role/ajax_get_permissions');
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.permissions=data.permissions;
            }
        });
    };

    loadPermissions();

    $scope.closeAlert = function(){
      $scope.warning_messages = $scope.success_messages = '';
    };
}]);

app.controller('EditUserRole',['$scope','WebService',function($scope,WebService){

    $scope.role_id = role_id;

    $scope.formData = {id:'',role_name:'',user_type:'',role_type:''};

    $scope.user_types = ['MSO','LCO'];
    $scope.role_types = ['Admin','Staff','Subscriber'];
    $scope.permissions = [];

    $scope.setRoleType = function(){

        if($scope.formData.user_type == "MSO" || $scope.formData.user_type == "LCO"){
            $scope.role_types = ['Admin','Staff'];
        }else{
            $scope.role_types = ['Subscriber'];
        }
    };

    var loadRole = function(id){
        var http = WebService.get('user-role/ajax_get_role/'+$scope.role_id);
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                console.log(data);

                $scope.formData.id = data.role.id;
                $scope.formData.role_name = data.role.role_name;
                $scope.formData.user_type = data.role.user_type;
                var roleType = data.role.role_type;
                $scope.formData.role_type = roleType.charAt(0).toUpperCase() + roleType.substring(1,roleType.length);
                $scope.setRoleType();
            }
        });

    };



    loadRole($scope.role_id);

    $scope.saveRole = function(){
        $scope.formData.role_type = 'staff';
        var http = WebService.post('user-role/update',$scope.formData);
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.loadNotification();
                window.location = SITE_URL + 'user-role';
            }else{
                $scope.warning_messages = data.warning_messages;
            }
        });
    };

    $scope.closeAlert = function(){
        $scope.warning_messages = $scope.success_messages = '';
    };
}]);