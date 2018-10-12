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
app.controller('MyTransactions',['$scope','WebService',function($scope,WebService){
    $scope.token = token;
    $scope.user_id = user_id;
    $scope.user_type = user_type;
    $scope.pairings = [];
    $scope.formData = {};
    $scope.totalCredit = 0;
    $scope.totalDebit = 0;
    $scope.totalBalance =0;

    $scope.$emit('loadSubscriberBalance',{user_type:$scope.user_type,user_id:$scope.user_id,token:$scope.token});

    var loadPairingID = function(){
        var http = WebService.get('profile/ajax-get-pairing-id/'+$scope.user_id);
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.pairings = data.pairings;
            }
        });
    };

    $scope.getStatements = function(){
        $scope.totalCredit = 0;
        $scope.totalDebit = 0;
        $scope.totalBalance =0;
        $scope.formData.user_id = $scope.user_id;
        if($scope.formData.pairing_id == null){
            $scope.warning_messages = 'Please select search criteria';
            return;
        }
        var http = WebService.post('my-transactions/ajax-get-statements',$scope.formData);
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.transactions = data.transactions;
                for(t in $scope.transactions){
                    $scope.totalCredit += parseFloat($scope.transactions[t].credit);
                    $scope.totalDebit += parseFloat($scope.transactions[t].debit);
                }
                $scope.totalBalance = $scope.totalCredit - $scope.totalDebit;
            }
        });

    };

    $scope.closeAlert = function(){
        $scope.warning_messages = '';
        $scope.success_messages = '';
        $scope.error_messages = '';
    };

    loadPairingID();

}]);