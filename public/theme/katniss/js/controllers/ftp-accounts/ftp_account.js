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
app.controller('FTPAccount',['$scope','WebService',function($scope,WebService){
    $scope.accounts = [];
    $scope.user_type = user_type;
    $scope.delete_item = null;

    $scope.closeAlert = function(){
        $scope.warning_messages = $scope.success_messages = $scope.error_messages = '';
    };

    var formValidation = function(){
        var regex = /^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/;
        $scope.warning_messages = '';

        if(!regex.test($scope.ftpAccount.server_ip)){
            $scope.warning_messages = 'Sorry! Ip not valid';
            $("html,body").animate({"scrollTop":"0px"});
            return false;
        }

        var portRegx = /^\d+$/;
        if(!portRegx.test($scope.ftpAccount.server_port)){
            $scope.warning_messages = 'Sorry! Port number must be numeric';
            $("html,body").animate({"scrollTop":"0px"});
            return false;
        }

        return true;
    };

    $scope.saveFTPAccount = function(){
        var isValid = formValidation();
        if(!isValid){
            return isValid;
        }
        var http = WebService.post('ftp-accounts/create',$scope.ftpAccount);
        http.then(function(response){
            var data = response.data;
            if(data.status == 400){
                $scope.warning_messages = data.warning_messages;
                $scope.success_messages = '';
            }else{
                $scope.hideForm();
                $scope.success_messages = data.success_messages;
                $scope.warning_messages = '';
                var gridObj = angular.element("#accountGrid").data('kendoGrid');
                gridObj.dataSource.read();
                gridObj.refresh();

            }
        });
    };

    $scope.hideForm = function(){
        $scope.showFrm = 0;
        $scope.delete_item = null;
    };

    $scope.showForm = function(){
        $scope.showFrm = 1;
        $scope.delete_item = null;
    };

    $scope.delete = function(item){
        $scope.delete_item = item;
    };

    $scope.cancel_delete = function(){
        $scope.delete_item = null;
    };

    $scope.confirm_delete = function(){

        var http = WebService.post('ftp-accounts/delete',{id:$scope.delete_item});
        http.then(function(response){
            var data = response.data;
            if(data.status == 400){
                $scope.warning_messages = data.warning_messages;
            }else{
                $scope.success_messages = data.success_messages;
            }
            $scope.delete_item = null;
            $("html,body").animate({scrollTop:'0px'});
        });

    };

    var generateKendoGird = function(){

        $scope.mainGridOptions = {
            dataSource: {

                transport: {
                    read: {
                        url: "ftp-accounts/ajax-get-accounts",
                        dataType: "json",
                    }
                },
                schema: {
                    data: "accounts",
                    total: "total"
                },
                pageSize: 10,
                serverPaging: true,
                serverSorting:true,
                serverFiltering: true
            },
            filterable: {
                extra: false,
                operators: {
                    string: {
                        startswith: "Starts with",
                        eq: "Is equal to",

                    }
                }
            },
            sortable: true,
            pageable: true,
            scrollable: true,
            resizable: true,

            dataBound: gridDataBound,

            columns: [

                {field: "name", title: "FTP NAME",width: "190px"},
                {field: "server_ip", title: "Server IP",width: "190px"},
                {field: "dir_location", title: "Dir Location",width: "190px"},
                {field: "user_id", title: "User ID",width: "190px"},
                {field: "password", title: "Passowrd",width: "190px"},
                {field: "", title:"Action",width:"auto",template:'<a title="View" class="btn btn-default btn-xs" href="'+SITE_URL+'ftp-accounts/view/#=data.id#"><i class="fa fa-search"></i></a> <a class="btn btn-default btn-xs" title="Edit" href="'+SITE_URL+'ftp-accounts/edit/#=data.id#"><i class="fa fa-pencil"></i></a> <a class="btn btn-danger btn-xs" ng-click="delete(#=data.id#)"><i class="fa fa-trash"></i></a>'}
            ]
        };
    };

    generateKendoGird();

    var loadPermissions = function(){
        var http = WebService.get('ftp-accounts/ajax-get-permissions');
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.permissions=data.permissions;
            }
        });
    };

    loadPermissions();

}]);
app.controller('EditFTPAccount',['$scope','WebService',function($scope,WebService){
    $scope.id = id;
    $scope.ftpAccount = {};

    $scope.closeAlert = function(){
        $scope.warning_messages = $scope.success_messages = $scope.error_messages = '';
    };

    var loadAccountInfo = function(){
        var http = WebService.post('ftp-accounts/ajax-get-account-info',{id:$scope.id});
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.ftpAccount = data.ftp_account;
            }
        });
    };

    loadAccountInfo();

    var formValidation = function(){
        var regex = /^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/;
        $scope.warning_messages = '';

        if(!regex.test($scope.ftpAccount.server_ip)){
            $scope.warning_messages = 'Sorry! Ip not valid';
            $("html,body").animate({"scrollTop":"0px"});
            return false;
        }

        var portRegx = /^\d+$/;
        if(!portRegx.test($scope.ftpAccount.server_port)){
            $scope.warning_messages = 'Sorry! Port number must be numeric';
            $("html,body").animate({"scrollTop":"0px"});
            return false;
        }

        return true;
    };

    $scope.updateFTPAccount = function(){
        var isValid = formValidation();
        if(!isValid){
            return isValid;
        }
        var http = WebService.post('ftp-accounts/update',$scope.ftpAccount);
        http.then(function(response){
            var data = response.data;

            if(data.status == 400){
                $scope.warning_messages = data.warning_messages;
                $scope.success_messages = '';
            }else{
                $scope.success_messages = data.success_messages;
                $scope.warning_messages = '';
                window.location = SITE_URL+'ftp-accounts';
            }

        });
    };
}]);
app.controller('ViewBankAccount',['$scope','WebService',function($scope,WebService){
    $scope.ftpAccount = {};

    $scope.id = id;
    $scope.user_type=user_type;


    var loadBankAccount = function(){

        var http = WebService.post('ftp-accounts/ajax-get-account-info',{id:$scope.id});
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.ftpAccount = data.ftp_account;
            }
        });

    };



    loadBankAccount();

    var loadPermissions = function(){
        var http = WebService.get('bank-accounts/ajax-get-permissions');
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.permissions=data.permissions;
            }
        });
    };

    loadPermissions();
}]);

