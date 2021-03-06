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
app.controller('BankStatement',['$scope','WebService',function($scope,WebService){

    $scope.collectors = [];
    $scope.subscribers = [];
    $scope.pairings = [];
    $scope.formData = {};
    $scope.totalCredit = 0;
    $scope.totalDebit = 0;
    $scope.totalBalance = 0;

    var loadAllCollectors = function(){
        var http = WebService.get('collector-statement/ajax-get-collectors');
        http.then(function(response){
            var data = response.data;
            if(data.status == 200)
            {
                $scope.collectors = data.collectors;
            }
        });
    };

    $scope.closeAlert = function()
    {
        $scope.success_messages = $scope.warning_messages = $scope.error_messages = '';
    };





    $scope.getStatements = function(){
        $scope.totalBalance = 0;
        $scope.totalCredit  = 0;
        $scope.totalDebit   = 0;
        $scope.transactions = [];
        var http = WebService.post('collector-statement/ajax-get-statements',$scope.formData);
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



    loadAllCollectors();

}]);