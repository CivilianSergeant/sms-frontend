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

app.controller('CreateVendor',['$scope','WebService',function($scope,WebService){

    $scope.closeAlert = function(){
        $scope.success_messages = '';
        $scope.warning_messages = '';
        $scope.error_messages = '';
    };
    $scope.formData = {};

    $scope.hideForm = function(){
        $scope.showFrm = 0;
        $scope.formData = {};
    };

    $scope.showForm = function()
    {
        if($scope.permissions.create_permission == '1'){
            $scope.showFrm = 1;
        }else{
            $scope.warning_messages = "Sorry! You don't have permission to create vendor";
        }

    };

    var loadPermissions = function(){
        var http = WebService.get('vendor/ajax-get-permissions');
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.permissions=data.permissions;
            }
        });
    };

    loadPermissions();

    var generateKendoGird = function(){
        $scope.mainGridOptions = {

            dataSource: {
                schema: {
                    data: "vendors",
                    total: "total"
                },
                transport: {
                    read: {
                        url: "vendor/ajax-get-vendors",
                        dataType: "json",
                    },
                    cache: false
                },
                pageSize: 10,
                serverPaging: true,
                serverFiltering: true

            },
            sortable: true,
            pageable: true,
            scrollable: true,
            resizable: true,
            filterable: {
                extra: false,
            },

            dataBound: gridDataBound,

            columns: [
                {field: "name", title: "Name",width: "auto",filterable:true},
                {field: "email", title: "Email",width: "auto",filterable:true},
                {field: "address", title: "Address",width: "auto",filterable:false},
                {field: "mobile_number",title: 'Cell Phone Number',width:"auto",filterable:false},
                {field: "phone_number",title: 'Phone Number',width:"auto",filterable:false},
                {field: "", title: "Action",width: "auto",template:"<a href='"+SITE_URL+"vendor/view/#=data.id#' class='btn btn-default btn-xs' data-toggle='tooltip' data-placement='left' title='View'><i class='fa fa-search'></i></a> <a ng-if='permissions.edit_permission==\"1\"' href='"+SITE_URL+"vendor/edit/#=data.id#' class='btn btn-default btn-xs' data-toggle='tooltip' data-placement='left' title='Update'><i class='fa fa-pencil'></i></a> <a ng-if='permissions.delete_permission==\"1\"' ng-click='deleteVendor(#=data.id#)' class='btn btn-danger btn-xs'><i class='fa fa-trash'></i></a>"},
            ]
        };
    };

    generateKendoGird();

    $scope.saveVendor = function(){
        var http = WebService.post('vendor/save-vendor',$scope.formData);
        http.then(function(response){
           var data = response.data;
            if(data.status==400){
                $scope.warning_messages = data.warning_messages;
                $("html,body").animate({scrollTop:'0px'});
            }else{
                $scope.success_messages = data.success_messages;
                $("html,body").animate({scrollTop:'0px'});
                $scope.hideForm();
                generateKendoGird();
            }

        });
    };

    $scope.deleteVendor = function(id){
        $scope.delete_flag = id;

    };

    $scope.confirm_delete = function(){
        var http = WebService.get('vendor/delete/'+$scope.delete_flag);
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                window.location.reload();
            }
        });
    };

    $scope.cancel_delete = function(){
        $scope.delete_flag = '';
    };

}]);