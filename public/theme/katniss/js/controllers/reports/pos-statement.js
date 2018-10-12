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
app.controller('PosStatement',['$scope','WebService',function($scope,WebService){

    $scope.pos = [];
    $scope.subscribers = [];
    $scope.pairings = [];
    $scope.formData = {};
    $scope.totalCredit = 0;
    $scope.totalDebit = 0;
    $scope.totalBalance = 0;

    var loadAllPos = function(){
        var http = WebService.get('pos-statement/ajax-get-pos');
        http.then(function(response){
            var data = response.data;
            if(data.status == 200)
            {
                $scope.pos = data.pos;
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
        var http = WebService.post('pos-statement/ajax-get-statements',$scope.formData);
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

    loadAllPos();

}]);