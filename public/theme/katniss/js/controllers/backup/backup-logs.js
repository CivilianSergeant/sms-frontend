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

app.controller('BackLogs',['$scope','WebService',function($scope,WebService){
    var generateKendoGird = function(){

            $scope.mainGridOptions = {
                dataSource: {
                    transport: {
                        read: {
                            url: "db-backup-logs/ajax-get-db-backup-logs",
                            dataType: "json",
                        }
                    },
                    schema: {
                        data: "logs",
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

                    {field: "name", title: "FTP Name",filterable:false,width: "auto"},
                    {field: "server_ip", title: "Server IP",filterable:false,width: "100px"},
                    {field: "server_port", title: "Server Port",filterable:false,width: "100px"},
                    {field: "file_name", title: "File Name",filterable:false,width: "auto",template:'<span title="#=data.file_name#">#=data.file_name#</span>'},
                    {field: "type", title: "Type",filterable:false,width: "auto"},
                    {field: "status", title: "Status",filterable:false,width: "auto",template:'#if(data.status==1) {# <span class="label label-success">Done</span> #} else {# <span class="label label-danger">Pending</span> #}#'},
                    {field: "done_time", title: "Done Time",filterable:false,width: "auto",template:'#if(data.done_time==null) {# <span>Not Done yet</span> #}else{# <span>#=data.done_time#</span> #}#'}

                ]
            };

    };

    generateKendoGird();
}]);