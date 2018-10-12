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

    $scope.bank_accounts = [];
    $scope.accounts = [];
    $scope.subscribers = [];
    $scope.pairings = [];
    $scope.formData = {};
    $scope.totalCredit = 0;
    $scope.totalDebit = 0;
    $scope.totalBalance = 0;

    var loadAllBankAccounts = function(){
        var http = WebService.get('bank-statement/ajax-get-bank-accounts');
        http.then(function(response){
            var data = response.data;
            if(data.status == 200)
            {
                $scope.bank_accounts = data.bank_accounts;
                for(a in $scope.bank_accounts){
                    var item = {name:'',id:''};
                    item.name = $scope.bank_accounts[a].account_name+' ['+$scope.bank_accounts[a].account_no+']';
                    item.id = $scope.bank_accounts[a].id;
                    $scope.bank_accounts[a]=item;

                }

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
        var http = WebService.post('bank-statement/ajax-get-statements',$scope.formData);
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


    loadAllBankAccounts();

}]);