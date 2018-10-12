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
app.controller('BulkSubscriberView',['$scope','WebService',function($scope,WebService){
        
    var generateKendoGird = function(){
        $scope.mainGridOptions = {
            dataSource: {
                transport: {
                    read: {
                        url: SITE_URL+"subscriber/get-bulk-subscriber-acc-cards?process_id=" + process_id, 
                        dataType: "json",
                    }
                },
                schema: {
                    data: "subscriber_cards",
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
                {field: "username",title:"User Name", width:"300px",filterable:true},
                {field: "password", title: "Password",width: "300px"},
                {field: "initial_balance", title: "Initial Balance",width: "200px",filterable:false},
                {field: "", title: "Status",width:"100",headerAttributes:{"style":"text-align:center;"},attributes: {"class": "text-center"},filterable:false,sortable:false,template: '# if(data.usage_status==0) {# <span class="label label-success">Unused</span> #} else {# <span class="label label-danger">Used</span> #}#'},
            ]
        };
    };
    generateKendoGird();
}]);
