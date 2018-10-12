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
app.controller('CashStatement',['$scope','WebService',function($scope,WebService){

    $scope.lco = [];
    $scope.subscribers = [];
    $scope.pairings = [];
    $scope.formData = {};
    $scope.totalCredit = 0;
    $scope.totalDebit = 0;
    $scope.totalBalance = 0;

    var loadAllLco = function(){
        var http = WebService.get('cash-statement/ajax-get-lco');
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
        var http = WebService.get('cash-statement/ajax-get-subscriber-by-lco/'+$scope.formData.lco_id);
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.subscribers = (data.subscribers.length>1)? data.subscribers:[];
            }
        });
    };

    $scope.loadPairings = function(){
        $scope.cards = [];
        $scope.stbcards = [];
        var http = WebService.get('cash-statement/ajax-get-pairings/'+$scope.formData.subscriber_id);
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.pairings = data.pairings;

            }
        });
    };



    $scope.getStatements = function(){
        $scope.totalBalance = 0;
        $scope.totalCredit  = 0;
        $scope.totalDebit   = 0;
        $scope.transactions = [];
        var http = WebService.post('cash-statement/ajax-get-statements',$scope.formData);
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





    loadAllLco();

}]);