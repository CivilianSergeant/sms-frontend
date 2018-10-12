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
app.controller('ScratchPayment',['$scope','WebService',function($scope,WebService){
    $scope.user_id = user_id;
    $scope.user_type = user_type;
    $scope.formData = {};
    $scope.lco = [];
    $scope.subscribers = [];
    $scope.pairings = [];
    $scope.serials = [];
    $scope.cards = [];
    $scope.subscriber_id = subscriber_id;
    if($scope.user_type == 'LCO') {
        $scope.formData.lco_id = user_id;
    }

    var loadAllLco = function(){

        var http = WebService.get('payments-scratch-card/ajax-get-lco');
        http.then(function(response){
            var data = response.data;
            if(data.status == 200)
            {
                $scope.lco = data.lco;
                $scope.subscribers =[];
                $scope.formData.subscriber_id = null;
                $scope.formData.pairing_id = null;
            }
        });
    };

    var loadSerialCards = function(){
        var http = WebService.get('payments-scratch-card/ajax-get-serial-cards');
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.serials = data.serials;
                $scope.cards   = data.cards;
            }
        });
    };

    loadSerialCards();

    $scope.loadSubscribers = function(id){
        $scope.subscribers = [];

        var http = WebService.get('payments-scratch-card/ajax-get-subscriber/'+id);
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.subscribers   = data.subscribers;
                $scope.pairings =[];

                $scope.formData.pairing_id = null;
                if($scope.subscriber_id != ""){
                    $scope.formData.subscriber_id = $scope.subscriber_id;
                    $scope.getPairingId();
                }
            }
        });
    };

    $scope.getPairingId = function(){
        if($scope.formData.subscriber_id !=null) {

            var id = $scope.formData.subscriber_id;

            var http = WebService.get('payments-scratch-card/ajax-get-pairing-id/' + id);
            http.then(function (response) {
                var data = response.data;
                if (data.status == 200) {
                    $scope.pairings = data.pairings;
                }
            });
        }
    };

    $scope.setPairingID = function(){
        if($scope.pairings.length>0){
            $scope.pairings.filter(function(obj){
                if(obj.id == $scope.formData.pairing_id){
                    $scope.formData.stb_card_id = obj.pairing_id;
                }
            });
        }

    };

    var formValidation = function(){
        var regex = /^\d+$/;
        if(!regex.test($scope.formData.serial_no)){
            $scope.warning_messages = 'Sorry! Serial No should be numeric';
            return false;
        }

        if(!regex.test($scope.formData.card_no)){
            $scope.warning_messages = 'Sorry! Card No should be numeric';
            return false;
        }

        return true;
    };

    $scope.saveScratchPayment = function(){
        var isValid = formValidation();
        if(!isValid)
        {
            return isValid;
        }
        //console.log($scope.formData);

        var http = WebService.post('payments-scratch-card/payment',$scope.formData);
        http.then(function(response){
            var data = response.data;
            if(data.status == 400){
                $scope.warning_messages =  data.warning_messages;
                $scope.success_messages = '';
                $("html,body").animate({scrollTop:"0px"});
            }else{
                $scope.formData = {};
                $scope.success_messages = data.success_messages;
                $scope.warning_messages = '';
                $scope.loadNotification();

                $("html,body").animate({scrollTop:"0px"}, function(){
                    window.location = data.redirect_to;
                });
            }
        });
    };

    $scope.closeAlert = function(){
        $scope.warning_messages = $scope.success_messages = $scope.error_messages = '';
    };

    loadAllLco();
    if($scope.user_type == 'MSO'){
        if($scope.subscriber_id != ""){
            $scope.formData.lco_id = 1;
        }
        $scope.loadSubscribers($scope.formData.lco_id);

    }


    if($scope.formData.lco_id != null && $scope.user_type == 'LCO'){
        $scope.loadSubscribers($scope.formData.lco_id);
    }




}]);
