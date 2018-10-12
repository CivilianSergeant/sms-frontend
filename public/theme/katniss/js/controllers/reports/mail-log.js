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
app.controller('mailLog',['$scope','WebService',function($scope,WebService){
    $scope.type = 'Mail';

    var loadAllLco = function(){
        var http = WebService.get('reports-system-log/ajax-get-lco');
        http.then(function(response){
            var data = response.data;
            if(data.status == 200)
            {
                $scope.lco = data.lco;
            }
        });
    };

    $scope.closeAlert = function()
    {
        $scope.success_messages = $scope.warning_messages = $scope.error_messages = '';
    };

    $scope.loadSubscriber = function(){
        $scope.subscribers = [];
        $scope.pairings = [];
        $scope.subscriber_id = null;
        $scope.pairing_id = null;
        var http = WebService.get('reports-system-log/ajax-get-subscriber-by-lco/'+$scope.lco_id);
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.subscribers = (data.subscribers.length>1)? data.subscribers:[];
            }
        });
    };

    $scope.loadCards = function(){
        $scope.cards = [];
        $scope.stbcards = [];
        var http = WebService.get('reports-system-log/ajax-load-cards/'+$scope.subscriber_id);
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.cards = (data.cards.length>0)? data.cards:[];
                $scope.stbcards = (data.stbcards.length>0)? data.stbcards:[];
                $scope.smart_card = '';
                $scope.stb = '';
            }
        });
    };

    $scope.getLogs = function()
    {     
        if($scope.stb == 'All')
        {
            $scope.stb = '';
        }
        if($scope.smart_card == 'All')
        {
            $scope.smart_card = '';
        }

        var grid = $('#grid').data("kendoGrid");
        grid.dataSource.transport.options.read.url="reports-system-log/ajax-load-mail-logs?subscriber_id="+$scope.subscriber_id+"&stb="+$scope.stb+"&smart_card="+$scope.smart_card+"&type="+$scope.type+"&status="+$scope.status,
        grid.dataSource.read();
        grid.refresh();

    };

    $scope.stop = function(i){
        var http = WebService.post('reports-system-log/stop_mail',{id:i});
        http.then(function(response){
            var data = response.data;
            if(data.status == 400)
            {
                $scope.warning_messages = data.warning_messages;
                $scope.success_messages = '';
            }
            else 
            {
                $scope.getLogs();
                $scope.success_messages = data.success_messages;
                $scope.warning_messages = '';
            }
        });
    };

	$scope.subscriber_id = '';
    $scope.stb = '';
    $scope.smart_card= '';

    var generateKendoGird = function(){
        $scope.mainGridOptions = {
            dataSource: {
                transport: {
                    read: {
                        url: "reports-system-log/ajax-load-mail-logs?subscriber_id="+$scope.subscriber_id+"&stb="+$scope.stb+"&smart_card="+$scope.smart_card+"&type="+$scope.type,
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
                
                {field: "lco_name", title: "LCO",filterable:false,width: "auto",template:'# if(data.lco_name==null) {# <span>All</span> #} else {# <span>#=data.lco_name#</span> #}#'},
                {field: "subscriber_name", title: "Subscriber",filterable:false,width: "auto",template:'# if(data.subscriber_name==null) {# <span>All</span> #} else {# <span>#=data.subscriber_name#</span> #}#'},
                /*{field: "mail_title", title: "Mail Title",filterable:false,width: "auto"},*/
                {field: "condition_return_code", title: "Return Code",filterable:false,width: "180px"},
                {field: "start_time", title: "Start Time",filterable:false,width: "auto"},
                {field: "end_time", title: "End Time",filterable:false,width: "auto"},
                {field: "expired", title: "Is Expired",filterable:false,width: "90px",template:'# if(data.expired==1) {# <span class="label label-danger">Expired</span> #} else {# <span class="label label-success">Active</span> #}#'},
                {field: "is_stoped", title: "Is Stopped",filterable:false,width: "90px",template:'# if(data.is_stoped==1) {# <span class="label label-default">Stopped</span> #} else {# <span class="label label-success">Active</span> #}#'},
                {field: "", title: "Action",width: "80px",template:"<a ng-click='stop(#=data.id#)' class='btn btn-danger btn-xs' data-toggle='tooltip' data-placement='left' title='Stop'><i class='fa fa-trash'></i></a>"},
            ]
        };
    };

    generateKendoGird();

    loadAllLco();

}]);