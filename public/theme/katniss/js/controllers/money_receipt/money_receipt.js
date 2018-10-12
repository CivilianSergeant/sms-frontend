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
app.controller('MoneyReceipt',['$scope','WebService',function($scope,WebService){
    
    $scope.tabs = {bulk:1,single:0};
    $scope.formData = {};
    $scope.singleFormData = {};
    $scope.token = token;
    $scope.permissions = [];

    $scope.setTab = function(tab){
        resetMessages();
        switch(tab){
            case 'bulk':
                $scope.tabs = {bulk:1,single:0};
                break;
            case 'single':
                $scope.tabs = {bulk:0,single:1};
                break;
        }
    };

    $scope.closeAlert = function(){
        resetMessages();
    };

    var resetMessages = function(){
         $scope.warning_messages = $scope.success_messages = $scope.error_messages = '';
     };

    var bulkMoneyReceiptFormValidation = function(){
        var regex = /^\d+$/;
        $scope.warning_messages = '';

        if(!regex.test($scope.formData.book_number)){
            $scope.warning_messages = 'Sorry! Receipt Book Number should be numeric';
            $("html,body").animate({"scrollTop":"0px"});
            return false;
        }

        if($scope.formData.from != "" && !regex.test($scope.formData.from)){
            $scope.warning_messages = 'Sorry! Receipt From number should be numeric';
            $("html,body").animate({"scrollTop":"0px"});
            return false;
        }

        if($scope.formData.to != "" && !regex.test($scope.formData.to)){
            $scope.warning_messages = 'Sorry! Receipt To number should be numeric';
            $("html,body").animate({"scrollTop":"0px"});
            return false;
        }
        return true;

    };

    $scope.saveBulkMoneyReceipt = function()
    {
        var isValid = bulkMoneyReceiptFormValidation();
        if(!isValid){
            return isValid;
        }

        resetMessages();
        $scope.formData.token = $scope.token;
        var from = eval($scope.formData.from);
        var to = eval($scope.formData.to);
        var totalPages = 1;
        for(var i=from;i<to;i++){
            totalPages++;
        }
        $scope.formData.pages = totalPages;
        var http = WebService.post('assign-money-receipt/assign_bulk_receipt',$scope.formData);
        http.then(function(response){
            var data = response.data;
            if(data.status == 400){
                $scope.warning_messages = data.warning_messages;
                $scope.success_messages = '';
            } else {
                $scope.success_messages = data.success_messages;
                $scope.warning_messages = '';
                //$scope.formData = {};
                $scope.loadNotification();
            }
        });
    };

    $scope.isBulkInputValid = function()
    {
        if(isNaN($scope.formData.collector_id))
            return true;
        if($scope.formData.collector_id == null)
            return true;
        if($scope.formData.book_number == null)
            return true;
        if($scope.formData.from == null)
            return true;
        if($scope.formData.to == null)
            return true;

        return false;
    }

    $scope.isSingleInputValid = function()
    {
        if(isNaN($scope.singleFormData.collector_id))
            return true;
        if($scope.singleFormData.collector_id == null)
            return true;
        if($scope.singleFormData.book_number == null)
            return true;
        
        return false;
    };

    var singleMoneyReceiptFormValidation = function(){
        var regex = /^\d+$/;
        $scope.warning_messages = '';

        if(!regex.test($scope.singleFormData.book_number)){
            $scope.warning_messages = 'Sorry! Receipt Book Number should be numeric';
            $("html,body").animate({"scrollTop":"0px"});
            return false;
        }

        if(!regex.test($scope.singleFormData.receipt_number)){
            $scope.warning_messages = 'Sorry! Receipt Receipt Number should be numeric';
            $("html,body").animate({"scrollTop":"0px"});
            return false;
        }

        return true;

    };

    $scope.saveSingleMoneyReceipt = function()
    {
        var isValid = singleMoneyReceiptFormValidation();
        if(!isValid){
            return isValid;
        }

        resetMessages();
        $scope.singleFormData.token = $scope.token;
        /*var from = eval($scope.formData.from);
        var to = eval($scope.formData.to);
        var totalPages = 0;
        for(var i=from;i<to;i++){
            totalPages++;
        }
        $scope.formData.pages = totalPages;*/
        var http = WebService.post('assign-money-receipt/assign_single_receipt',$scope.singleFormData);
        http.then(function(response){
            var data = response.data;
            if(data.status == 400){
                $scope.warning_messages = data.warning_messages;
                $scope.success_messages = '';
            } else {
                $scope.success_messages = data.success_messages;
                $scope.warning_messages = '';
                //$scope.singleFormData = {collector_id:'',book_number:'',receipt_number:''};
                $scope.loadNotification();
            }
        });
    };

    var loadCollectors = function()
    {
        resetMessages();
        var http = WebService.get('assign-money-receipt/ajax_get_collectors');
        http.then(function(response){
            var data = response.data;
            if(data.status==200){
                $scope.collectors = data.collectors;
            }
        });
    };

    loadCollectors();

    var loadPermissions = function(){
        var http = WebService.get('assign-money-receipt/ajax_get_permissions');
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.permissions=data.permissions;
            }
        });
    };

    loadPermissions();

}]);