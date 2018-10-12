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
app.controller('module',['$scope','WebService',function($scope,WebService){
    $scope.showFrm = 0;
    $scope.module = {};
    $scope.showForm = function(){
        $scope.showFrm = 1;
    };

    $scope.hideForm = function(){
        $scope.showFrm = 0;
    };

    $scope.closeAlert = function(){
        $scope.warning_messages = $scope.success_messages = $scope.error_messages = '';
    };

    $scope.saveModule = function(){
        $scope.closeAlert();
        var http = WebService.post('module/create',$scope.module);
        http.then(function(response){
            var data = response.data;
            if(data.status == 400){
                $scope.success_messages = '';
                $scope.warning_messages = data.warning_messages;
            } else {
                $scope.success_messages = data.success_messages;
                $scope.warning_messages = '';
                $scope.module = {};
            }
        });
    };


    var generateKendoGird = function(data){
            $scope.mainGridOptions = {
            dataSource: {

                schema: {
                    data: "modules",
                    total: "total"
                },

                transport: {
                    read: {
                        url: "module/ajax_load_modules", 
                        dataType: "json",
                    }
                },
                pageSize: 10,
                serverPaging: true,
                serverFiltering: false              
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
                {field: "module_name", title: "Module Name",width: "auto"},
                {field: "route", title: "Route",width: "auto",filterable:false,sortable:false},
                {field: "", filterable: false, title: "Action",width: "auto",template:"<a href='"+SITE_URL+"view-collector/#=data.id#' class='btn btn-default btn-xs' data-toggle='tooltip' data-placement='left' title='View'><i class='fa fa-search'></i></a> <a href='"+SITE_URL+"update-collector/#=data.id#' class='btn btn-default btn-xs' data-toggle='tooltip' data-placement='left' title='Update'><i class='fa fa-pencil'></i></a>"},
            ]
        };
    };

    generateKendoGird();
    
}]);