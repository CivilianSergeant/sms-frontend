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
app.controller('CollectionStatement',['$scope','WebService',function($scope,WebService){

    $scope.lco = [];
    $scope.subscribers = [];
    $scope.pairings = [];
    $scope.formData = {};
    $scope.totalCredit = 0;
    $scope.totalDebit = 0;
    $scope.totalBalance = 0;

    var loadAllLco = function(){
        var http = WebService.get('collection-statement/ajax-get-lco');
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

    /*$scope.loadSubscriber = function(){
        $scope.subscribers = [];
        $scope.pairings = [];
        $scope.subscriber_id = null;
        $scope.pairing_id = null;
        var http = WebService.get('client-statements/ajax-get-subscriber-by-lco/'+$scope.formData.lco_id);
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.subscribers = (data.subscribers.length>1)? data.subscribers:[];
            }
        });
    };*/

    /*$scope.loadPairings = function(){
        $scope.cards = [];
        $scope.stbcards = [];
        var http = WebService.get('client-statements/ajax-get-pairings/'+$scope.formData.subscriber_id);
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.pairings = data.pairings;

            }
        });
    };*/


    $scope.setTransaction = function(){
        $scope.transactions = [];
        $scope.totalBalance = 0;
        $scope.totalCredit  = 0;
        $scope.totalDebit   = 0;
    };

    $scope.getStatements = function(){
        $scope.totalBalance = 0;
        $scope.totalCredit  = 0;
        $scope.totalDebit   = 0;
        var http = WebService.post('collection-statement/ajax-get-collection-statements',$scope.formData);
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.transactions = data.transactions;
                for(t in $scope.transactions){
                    $scope.totalCredit += parseFloat($scope.transactions[t].credit);
                    $scope.totalDebit += parseFloat($scope.transactions[t].debit);
                }
            }

            $scope.totalBalance = $scope.totalCredit - $scope.totalDebit;
        });


    };



	$scope.subscriber_id = '';
    $scope.pairing_id = '';


    var generateKendoGird = function(){
        $scope.mainGridOptions = {
            dataSource: {
                transport: {
                    read: {
                        url: "client-statement/ajax-load-client-statements?subscriber_id="+$scope.subscriber_id+"&pairing_id="+$scope.pairing_id,
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
                
                {field: "lco_name", title: "SL",filterable:false,width: "auto",template:'# if(data.lco_name==null) {# <span>All</span> #} else {# <span>#=data.lco_name#</span> #}#'},
                {field: "subscriber_name", title: "Description",filterable:false,width: "auto",template:'# if(data.subscriber_name==null) {# <span>All</span> #} else {# <span>#=data.subscriber_name#</span> #}#'},
                /*{field: "mail_title", title: "Mail Title",filterable:false,width: "auto"},*/
                {field: "condition_return_code", title: "Credit",filterable:false,width: "180px"},
                {field: "start_time", title: "Debit",filterable:false,width: "auto"},

            ]
        };
    };

    generateKendoGird();

    loadAllLco();

}]);