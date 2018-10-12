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
app.controller('MonitorStreamerInstance',['$scope','WebService',function($scope,WebService){

    $scope.instances = null;
    $scope.formData = {};
    var loadInstances = function(){
        var http = WebService.get('monitor-instance/ajax-get-monitor-instances');
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.instances = data.instances;
                if($scope.instances.length>1){
                    $scope.formData.instance_id = $scope.instances[0].id;
                }else{
                    $scope.formData.instance_id = 'All';
                }
               // $scope.instances.push({id:-1,instance_name:"ALL"},0);
            }
        });
    };

    loadInstances();

    $scope.getStreamerInstanceData = function(){
        var formData = {instance_id:$scope.formData.instance_id};
        var http = WebService.post('monitor-instance/ajax-get-instance-data',formData);
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.instance_data = data.instance_data;

                //generateGrid($scope.instance_data);
                var grid = $("#monitorInstanceGrid").data("kendoGrid");
                grid.dataSource.data($scope.instance_data);
                //$('#monitorInstanceGrid').data('kendoGrid').options.dataSource.data = $scope.instance_data;
                //$('#monitorInstanceGrid').data('kendoGrid').refresh();
            }
        });
    };



    var generateGrid = function(data){
        $scope.mainGridOptions = {
            dataSource: {
                data: data,
                schema: {
                    model: {
                        fields: {
                            streamerId: { type: "string" },
                            customerId: { type: "string" },
                            customerToken: { type: "string" },
                            startTime: { type: "string" }
                        }
                    }
                },
                pageSize: 10
            },

            scrollable: true,
            sortable: true,
            filterable: false,
            resizeable:true,
            pageable: {
                input: true,
                numeric: false
            },
            columns: [
                {field: "ip",title: "Server IP",width: "160px"},
                {field: "streamerId",title: "Streamer Id",width: "100px"},
                {field: "customerId",title: "Customer ID",width: "100px"},
                {field: "customerName",title: "Customer Name",width: "150px",template:"<a href='"+SITE_URL+"subscriber/view/#=data.profileToken#' title='View'>#=data.customerName#</a>"},
                {field: "watchTime",title: "Start Time",width: "100px"},
                {field: "userIp",title: "User IP",width: "90px"},
                {field: "channelName",title: "Channel Name",width: "90px"},
                {field: "bitRate",title: "Bit Rate",width: "70px"},
                {field: "duration",title: "Duration",width: "70px"}
            ],

        };
    };


    generateGrid();
    

}]);