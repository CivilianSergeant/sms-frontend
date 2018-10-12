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
app.controller('BankPayment',['$scope','WebService',function($scope,WebService){
    $scope.user_id = user_id;
    $scope.accounts = [];
    $scope.subscribers = [];
    $scope.pairings = [];
    $scope.payment_types = [];
    $scope.user_type = user_type;
    $scope.formData = {};
    $scope.pairing_id = null;
    $scope.subscriber_id = (subscriber_id)? subscriber_id : null;

    var loadAccounts = function(){
        var http = WebService.get('bank-payment/ajax-get-accounts');
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.accounts = data.accounts;
                $scope.payment_types = data.payment_types;
                for(a in $scope.accounts){
                    $scope.accounts[a].name = $scope.accounts[a].account_name+' ['+$scope.accounts[a].account_no+']';
                    $scope.accounts[a].id = $scope.accounts[a].id;
                }
            }
        });
    };

    var loadSubscribers = function(id){

        var http = WebService.get('bank-payment/ajax-get-subscriber/'+id);
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.subscribers   = data.subscribers;

                if($scope.subscriber_id != null && $scope.subscriber_id != ""){

                    $scope.formData.subscriber_id = $scope.subscriber_id;
                    $scope.getPairingId();

                }
            }
        });
    };






    $scope.closeAlert = function(){
        $scope.warning_messages = $scope.success_messages = $scope.error_messages = '';
    };

    $scope.getPairingId = function(){
        if($scope.formData.subscriber_id !=null){

            var http = WebService.get('bank-payment/ajax-get-pairing-id/'+$scope.formData.subscriber_id);
            http.then(function(response){
                var data = response.data;
                if(data.status == 200){

                    $scope.pairings = data.pairings;
                    $scope.formData.stb_card_id = null;
                }
            });
        }
    };

    $scope.setPairingId = function(){
        $scope.pairings.filter(function(obj){

            if(obj.id == $scope.formData.stb_card_id){
                $scope.pairing_id = obj.pairing_id;
            }
        });
    };

    var formValidation = function(){
        var regex = /^\d+$/;
        $scope.warning_messages = '';

        if(!regex.test($scope.formData.amount)){
            $scope.warning_messages = 'Sorry! Amount should be numeric';
            $("html,body").animate({"scrollTop":"0px"});
            return false;
        }

        if(!regex.test($scope.formData.check_no)){
            $scope.warning_messages = 'Sorry! Check No should be numeric';
            $("html,body").animate({"scrollTop":"0px"});
            return false;
        }

        if(!regex.test($scope.formData.transaction_id)){
            $scope.warning_messages = 'Sorry! Transaction ID should be numeric';
            $("html,body").animate({"scrollTop":"0px"});
            return false;
        }

        if(!regex.test($scope.formData.depositor_phone)){
            $scope.warning_messages = 'Sorry! Depositor Phone should be numeric';
            $("html,body").animate({"scrollTop":"0px"});
            return false;
        }
        return true;
    };

    $scope.checkValid = function(){
        var valid = false;
        if($scope.subscriber_id != null){
            $scope.formData.subscriber_id = $scope.subscriber_id;
        }
        if($scope.formData.bank_account_id == "" ||
            $scope.formData.bank_account_id == null ||
            $scope.formData.bank_account_id == undefined){
            valid= true;
        }
        if($scope.formData.subscriber_id == "" ||
            $scope.formData.subscriber_id == null ||
            $scope.formData.subscriber_id == undefined){
            valid= true;
        }
        if($scope.formData.stb_card_id == "" ||
            $scope.formData.stb_card_id == null ||
            $scope.formData.stb_card_id == undefined){
            valid= true;
        }
        if($scope.formData.type_id == "" ||
            $scope.formData.type_id == null ||
            $scope.formData.type_id == undefined){
            valid= true;
        }
        if($scope.formData.amount == "" ||
            $scope.formData.amount == null ||
            $scope.formData.amount == undefined){
            valid= true;
        }
        if($scope.formData.check_no == "" ||
            $scope.formData.check_no == null ||
            $scope.formData.check_no == undefined){
            valid= true;
        }
        if($scope.formData.transaction_id == "" ||
            $scope.formData.transaction_id == null ||
            $scope.formData.transaction_id == undefined){
            valid= true;
        }
        if($scope.formData.depositor_name == "" ||
            $scope.formData.depositor_name == null ||
            $scope.formData.depositor_name == undefined){
            valid= true;
        }
        if($scope.formData.depositor_phone == "" ||
            $scope.formData.depositor_phone == null ||
            $scope.formData.depositor_phone == undefined){
            valid= true;
        }
        if($scope.formData.deposit_date == "" ||
            $scope.formData.depositor_phone == null ||
            $scope.formData.depositor_phone == undefined){
            valid= true;
        }

        return valid;

    };

    $scope.saveBankPayment = function(){
        var isValid = formValidation();
        if(!isValid){
            return isValid;
        }

        $scope.formData.pairing_id = $scope.pairing_id;

        var http = WebService.post('bank-payment/payment',$scope.formData);
        http.then(function(response){
           var data = response.data;
           if(data.status == 400){
               $scope.warning_messages = data.warning_messages;
               $scope.success_messages = '';
               $("html,body").animate({scrollTop:"0px"});
           }else{
               $scope.success_messages = data.success_messages;
               $scope.warning_messages = '';
               $scope.loadNotification();

               $("html,body").animate({scrollTop:"0px"}, function(){
                   window.location = data.redirect_to;
               });
           }
        });
    };

    loadAccounts();
    if($scope.user_type == "MSO"){
        loadSubscribers(1);

    }else{
        loadSubscribers($scope.user_id);
    }

}]);
