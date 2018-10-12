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

app.controller('StreamerInstance',['$scope','WebService',function($scope,WebService){
    $scope.instances = [];
    $scope.operators = [];
    $scope.showFrm = false;
    $scope.formData = {};
    $scope.delete_item = null;
    $scope.lspTypeId = lspTypeId;
    
    
    $scope.showForm = function(){
        $scope.showFrm = true;
        $scope.formData = {};
    };

    $scope.hideForm = function(){
        $scope.showFrm = false;
        $scope.formData = {};
    };

    $scope.closeAlert = function(){
        $scope.warning_messages = '';
        $scope.success_messages = '';
        $scope.error_messages   = '';
    };

    $scope.delete = function(i){
        $scope.delete_item = i;
    };

    $scope.confirm_delete = function(){
        var http = WebService.post('streamer-instance/delete',{id:$scope.delete_item});
        http.then(function(response){
            var data = response.data;
            if(data.status == 400){
                $scope.warning_messages = data.warning_messages;
            }else{
                $scope.success_messages = data.success_messages;
                generateKendoGird();
                $scope.delete_item = null;
            }
        });
    };

    $scope.cancel_delete = function(){
        $scope.delete_item = null;
    };

    $scope.sync = function(i){
        $scope.sync_item = i;
    };

    $scope.confirm_sync = function(){
        var http = WebService.post('streamer-instance/sync',{id:$scope.sync_item});
        http.then(function(response){
            var data = response.data;
            if(data.status == 400){
                $scope.warning_messages = data.warning_messages;
            }else{
                $scope.success_messages = data.success_messages;
                generateKendoGird();
                $scope.sync_item = null;
            }
        });
    };

    $scope.cancel_sync = function(){
        $scope.sync_item = '';
    };

    var generateKendoGird = function(){
        $scope.mainGridOptions = {
            dataSource: {
                transport: {
                    read: {
                        url: "streamer-instance/ajax-get-instances",
                        dataType: "json",
                    }
                },
                schema: {
                    data: "instances",
                    total: "total"
                },
                pageSize: 10,
                serverPaging: true,
                serverSorting:true,
                serverFiltering: true

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

                {field: "instance_name", title: "Instance Name", width: "200px",template: '<a href="javascript:void(0);" data-toggle="tooltip" data-placement="right" title="#=data.alias_domain_url#">#=data.instance_name#</a>'},
                {field: "instance_local_ip", title: "Local IP", width: "120px",filterable:false},
                {field: "instance_global_ip", title: "Global IP", width: "120px",filterable:false},
                {field: "instance_index", title: "INDEX", width: "80px",filterable:false},
                {field: "operator", title: "Operator", width: "auto",filterable:false},
                {field: "assigned_hls", title: "Assigned HLS", width: "auto",filterable:false},
                {field: "instance_capacity", title: "Capacity", width: "100px",filterable:false},
                {field: "is_active", title: "Status", width: "auto",filterable:false,template: '# if(data.is_active=="1") {# <span class="label label-success">Active</span> #} else {# <span class="label label-danger">Inactive</span> #}#'},
                {field: "", title: "Action", width: "auto",filterable:false,template:"<a href='"+SITE_URL+"streamer-instance/view/#=data.id#' class='btn btn-default btn-xs' data-toggle='tooltip' data-placement='left' title='View'><i class='fa fa-search'></i></a>"+
                ' <a ng-if="permissions.edit_permission==\'1\'" href="'+SITE_URL+'streamer-instance/edit/#=data.id#" class="btn btn-default btn-xs" data-toggle="tooltip" data-placement="left" title="Edit"><i class="fa fa-pencil"></i></a>  <a ng-click="sync(#=data.id#)" class="btn btn-info btn-xs" data-toggle="tooltip" data-placement="left" title="Sync"><i class="fa fa-refresh"></i></a> <a ng-if="permissions.delete_permission==\'1\'" ng-click="delete(#=data.id#)" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="left" title="Delete"><i class="fa fa-trash"></i></a>'},
            ]
        };

    };

    var formValidation = function(){
        var regex = /^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/;
        $scope.warning_messages = '';

        if(!regex.test($scope.formData.instance_local_ip)){
            $scope.warning_messages = 'Sorry! Local Ip not valid';
            $("html,body").animate({"scrollTop":"0px"});
            return false;
        }

        if(!regex.test($scope.formData.instance_global_ip)){
            $scope.warning_messages = 'Sorry! Global Ip not valid';
            $("html,body").animate({"scrollTop":"0px"});
            return false;
        }


        return true;
    };

    $scope.saveStreamerInstance = function(){
        $scope.closeAlert();
        var isValid = formValidation();
        if(!isValid){
            return isValid;
        }
        var http = WebService.post('streamer-instance/save',$scope.formData);
        http.then(function(response){
            var data = response.data;
            if(data.status == 400){
                $scope.warning_messages = data.warning_messages.replace('<p>','').replace('</p>','');
            }else{
                $scope.success_messages = data.success_messages;
                generateKendoGird();
                $scope.hideForm();
            }
        });
    };

    var loadPermissions = function(){

        var http = WebService.get('streamer-instance/ajax-get-permissions');
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.permissions=data.permissions;
            }
        });
    };

    var loadAllLco = function(){
        var http = WebService.get('streamer-instance/ajax-get-lco');
        http.then(function(response){
            var data = response.data;
            if(data.status == 200)
            {
                $scope.operators = data.lco;
            }
        });
    };



    generateKendoGird();
    loadAllLco();
    loadPermissions();

}]);

app.controller('EditStreamerInstance',['$scope','WebService',function($scope,WebService){
    $scope.instanceId = instanceId;
    $scope.formData = null;

    $scope.tabs = {detail:1,hls:0};

    $scope.setTab = function(tab){
        switch(tab){
            case 'detail':
                $scope.tabs = {detail:1,hls:0};
                break;

            case 'hls':
                $scope.tabs = {detail:0,hls:1};
                break;
        }
    };

    $scope.closeAlert = function(){
        $scope.warning_messages = '';
        $scope.success_messages = '';
        $scope.error_messages   = '';
    };

    var loadPermissions = function(){

        var http = WebService.get('streamer-instance/ajax-get-permissions');
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.permissions=data.permissions;
            }
        });
    };

    var loadAllLco = function(){
        var http = WebService.get('streamer-instance/ajax-get-lco');
        http.then(function(response){
            var data = response.data;
            if(data.status == 200)
            {
                $scope.operators = data.lco;
            }

        });
    };

    var loadInstanceInfo = function()
    {

        var http = WebService.get('streamer-instance/ajax-get-instance/'+$scope.instanceId);
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.formData = data.instance;
                $scope.hls = data.hls;
            }
        });
    };

    loadPermissions();
    loadAllLco();
    loadInstanceInfo();



    var formValidation = function(){
        var regex = /^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/;
        $scope.warning_messages = '';

        if(!regex.test($scope.formData.instance_local_ip)){
            $scope.warning_messages = 'Sorry! Local Ip not valid';
            $("html,body").animate({"scrollTop":"0px"});
            return false;
        }

        if(!regex.test($scope.formData.instance_global_ip)){
            $scope.warning_messages = 'Sorry! Global Ip not valid';
            $("html,body").animate({"scrollTop":"0px"});
            return false;
        }


        return true;
    };

    $scope.saveStreamerInstance = function(){
        $scope.closeAlert();
        var isValid = formValidation();
        if(!isValid){
            return isValid;
        }

        console.log($scope.formData);
        var http = WebService.post('streamer-instance/update',$scope.formData);
        http.then(function(response){
            var data = response.data;
            if(data.status == 400){

                $scope.warning_messages = data.warning_messages.replace('<p>','').replace('</p>','');
            }else{
                $scope.success_messages = data.success_messages;
                window.location = SITE_URL + 'streamer-instance';

            }
        });
    };

}]);