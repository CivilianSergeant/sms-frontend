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



    $scope.closeAlert = function()
    {
        $scope.success_messages = $scope.warning_messages = $scope.error_messages = '';
    };




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
        var http = WebService.post('foc-collection/ajax-get-collection-statements',$scope.formData);
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



}]);