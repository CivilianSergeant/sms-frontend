/**
 * Created by user on 2/15/2016.
 */
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

app.controller('Pos',['$scope','WebService',function($scope,WebService){

    $scope.pos_lists   = [];
    $scope.collectors  = [];
    $scope.permissions = [];
    $scope.pos = {};
    $scope.showFrm = 0;
    $scope.lco_user_id = lco_user_id;

    var loadPermissions = function(){
        var http = WebService.get('pos-settings/ajax-get-permissions');
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.permissions=data.permissions;
            }
        });
    };

    loadPermissions();

    var loadBankAccounts = function(){
        var http = WebService.get('bank-accounts/ajax-get-accounts');
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.accounts = data.accounts;
                for(a in $scope.accounts){
                    $scope.accounts[a].name = $scope.accounts[a].account_name+' ['+$scope.accounts[a].account_no+']';
                }
            }
        });
    };

    loadBankAccounts();

    var loadCollectors = function(){
        var http = WebService.get('pos-settings/ajax-get-collectors/'+$scope.lco_user_id);
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.collectors = data.collectors;
            }
        });
    };

    loadCollectors();

    $scope.showForm = function(){
        $scope.showFrm = 1;
        $scope.pos = {};
    };

    $scope.hideForm = function(){
        $scope.showFrm = 0;

    };

    var formValidation = function(){
        var regex = /^\d+$/;
        $scope.warning_messages = '';

        if(!regex.test($scope.pos.pos_machine_id)){
            $scope.warning_messages = 'Sorry! POS Machine No should be numeric';
            $("html,body").animate({"scrollTop":"0px"});
            return false;
        }

        return true;
    };

    $scope.savePOS = function(){
        var isValid = formValidation();
        if(!isValid){
            return isValid;
        }

        var http = WebService.post('pos-settings/create',$scope.pos);
        http.then(function(response){
            var data = response.data;
            if(data.status == 400){
                $scope.success_messages = '';
                $scope.warning_messages = data.warning_messages;
            }else{
                $scope.warning_messages = '';
                $scope.success_messages = data.success_messages;
                $scope.hideForm();
            }
            $("html,body").animate({scrollTop:'0px'});
        });
    };

    var generateKendoGird = function() {
        $scope.mainGridOptions = {
            dataSource: {

                schema: {
                    data: "pos_machines",
                    total: "total"
                },

                transport: {
                    read: {
                        url: SITE_URL + "pos-settings/ajax-get-pos-machines",
                        dataType: "json",
                    }
                },
                pageSize: 10,
                serverPaging: true,
                serverFiltering: false
            },
            sortable: true,
            pageable: true,
            scrollable: true,
            resizable: true,
            filterable: {
                extra: false,
                operators: {
                    string: {
                        startswith: "Starts with",
                        eq: "Is equal to",

                    }
                }
            },

            dataBound: gridDataBound,

            columns: [
                {field: "account_info", title: "Bank Acccount", width: "290px"},
                {field: "pos_machine_id", title: "POS Machine no", width: "auto"},
                {field: "name", title: "Collector", width: "auto"},
                {field: "charge_interest", title: "Charge Interest", width:""},
                {
                    field: "is_active",
                    title: "Is Active",
                    width: "120px",
                    template: '#if(is_active){# <span class="label label-success">Yes</span> #}else{# <span class="label label-primary">No</span> #}#'
                },
                 {
                    field: "",
                    filterable: false,
                    title: "Action",
                    width: "auto",
                    template: "<a href='" + SITE_URL + "pos-settings/view/#=data.token#' class='btn btn-default btn-xs' data-toggle='tooltip' data-placement='left' title='View'><i class='fa fa-search'></i></a> <a ng-if='permissions.edit_permission == \"1\"' href='" + SITE_URL + "pos-settings/edit/#=data.token#' class='btn btn-default btn-xs' data-toggle='tooltip' data-placement='left' title='Edit'><i class='fa fa-pencil'></i></a>"
                }
            ]
        };
    };

    generateKendoGird();

    $scope.closeAlert = function(){
        $scope.warning_messages = $scope.success_messages = $scope.error_messages = '';
    };


}]);
app.controller('PosEdit',['$scope','WebService',function($scope,WebService){
    $scope.token = token;
    $scope.pos = {};
    $scope.user_id = user_id;

    var loadPermissions = function(){
        var http = WebService.get('pos-settings/ajax-get-permissions');
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.permissions=data.permissions;
            }
        });
    };

    loadPermissions();

    var loadPosInfo = function(){
        var http = WebService.get('pos-settings/ajax-get-pos-machine/'+$scope.token);
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.pos = data.pos;
            }
        });
    };

    loadPosInfo();

    var loadBankAccounts = function(){
        var http = WebService.get('bank-accounts/ajax-get-accounts');
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.accounts = data.accounts;
                for(a in $scope.accounts){
                    $scope.accounts[a].name = $scope.accounts[a].account_name+' ['+$scope.accounts[a].account_no+']';
                }
            }
        });
    };

    loadBankAccounts();

    var loadCollectors = function(){
        var http = WebService.get('pos-settings/ajax-get-collectors/'+$scope.user_id);
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.collectors = data.collectors;
            }
        });
    };

    loadCollectors();

    var formValidation = function(){
        var regex = /^\d+$/;
        $scope.warning_messages = '';

        if(!regex.test($scope.pos.pos_machine_id)){
            $scope.warning_messages = 'Sorry! POS Machine No should be numeric';
            $("html,body").animate({"scrollTop":"0px"});
            return false;
        }

        return true;
    };

    $scope.updatePOS = function(){
        var isValid = formValidation();
        if(!isValid){
            return isValid;
        }

        $scope.pos.token = $scope.token;
        var http = WebService.post('pos-settings/update',$scope.pos);
        http.then(function(response){
            var data = response.data;
            if(data.status == 400){
                $scope.success_messages = '';
                $scope.warning_messages = data.warning_messages;
            }else{
                $scope.warning_messages = '';
                $scope.success_messages = data.success_messages;
                $("html,body").animate({scrollTop:'0px'});

            }
        });
    };

    $scope.showBankAccount = function(){
        var bankName = '';
        $scope.accounts.filter(function(obj){
            if(obj.id == $scope.pos.bank_account_id){
                bankName=obj.name;
            }
        });

        return bankName;
    };

    $scope.showCollector = function(){
        var collectorName = '';
        $scope.collectors.filter(function(obj){
            if(obj.id == $scope.pos.collector_id){
                collectorName=obj.name;
            }
        });

        return collectorName;
    };

    $scope.closeAlert = function(){
      $scope.warning_messages = $scope.success_messages = $scope.error_messages = '';
    };

}]);
app.controller('PosPayment',['$scope','WebService',function($scope,WebService){
    $scope.user_type = user_type;
    $scope.user_id = user_id;
    $scope.lco = [];
    $scope.collectors = [];
    $scope.subscribers = [];
    $scope.pairings = [];
    $scope.pos = [];
    $scope.payment_types = [];
    $scope.pairing_id = null;
    $scope.formData = {};
    $scope.subscriber_id = subscriber_id;

    if($scope.user_type != 'MSO') {
        $scope.formData.lco_id = $scope.user_id;
    }
    if($scope.user_type == "MSO"){
        if($scope.subscriber_id != ""){
            $scope.formData.lco_id = 1;
        }

    }

    var loadAllLco = function(){

        var http = WebService.get('pos-payment/ajax-get-lco');
        http.then(function(response){
            var data = response.data;
            if(data.status == 200)
            {
                $scope.lco = data.lco;
                $scope.payment_types = data.payment_types;
                $scope.subscribers =[];
                $scope.formData.subscriber_id = null;
                $scope.formData.pairing_id = null;
            }
        });
    };

    var loadPosMachines = function(option){
        id = (option < 0)? 1 : option;
        var http = WebService.get('pos-payment/ajax-get-pos/'+id);
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.pos = data.pos;
            }
        });
    };

    $scope.loadData = function(){
        loadCollectors($scope.formData.lco_id);
        loadSubscribers($scope.formData.lco_id);

        loadPosMachines($scope.formData.lco_id);
    };

    var loadCollectors = function(option){
        id = (option < 0)? 1 : option;
        var http = WebService.get('pos-payment/ajax-get-collectors/'+id);
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.collectors = data.collectors;
            }
        });
    };

    var loadSubscribers = function(option){
        id = (option < 0)? 1 : option;

        var http = WebService.get('pos-payment/ajax-get-subscriber/'+id);
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.subscribers = [];
                $scope.subscribers   = data.subscribers;
                $scope.pairings =[];
                $scope.formData.subscriber_id = null;
                if($scope.subscriber_id != "" && $scope.subscriber_id != null) {
                    $scope.formData.subscriber_id = $scope.subscriber_id;
                    $scope.getPairingId();
                }
                $scope.formData.pairing_id = null;

            }
        });
    };

    $scope.getPairingId = function(id){

        var http = WebService.get('pos-payment/ajax-get-pairing-id/'+$scope.formData.subscriber_id);
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.pairings = data.pairings;
                $scope.formData.stb_card_id = null;
            }
        });
    };

    $scope.setPairingId = function(){
        $scope.pairings.filter(function(obj){

            if(obj.id == $scope.formData.stb_card_id){
                $scope.pairing_id = obj.pairing_id;
            }
        });
    };

    var formValidation = function(){
        var regex = /^\d+$/
        if(!regex.test($scope.formData.tid)){
            $scope.warning_messages = 'Sorry! TID number should be numeric';
            return false;
        }

        if(!regex.test($scope.formData.mid)){
            $scope.warning_messages = 'Sorry! MID number should be numeric';
            return false;
        }

        if(!regex.test($scope.formData.invoice_no)){
            $scope.warning_messages = 'Sorry! Invoice number should be numeric';
            return false;
        }

        if(!regex.test($scope.formData.batch_no)){
            $scope.warning_messages = 'Sorry! Batch number should be numeric';
            return false;
        }

        if(!regex.test($scope.formData.approval_code)){
            $scope.warning_messages = 'Sorry! Approval Code should be numeric';
            return false;
        }

        if(!regex.test($scope.formData.last_four)){
            $scope.warning_messages = 'Sorry! Card Last Four number should be numeric';
            return false;
        }

        if(!regex.test($scope.formData.rpn)){
            $scope.warning_messages = 'Sorry! RPN Code should be numeric';
            return false;
        }

        if(!regex.test($scope.formData.amount)){
            $scope.warning_messages = 'Sorry! Amount should be numeric';
            return false;
        }

        return true;
    };

    $scope.savePosPayment = function(){

        var isValid = formValidation();
        if(!isValid){
            return isValid;
        }

        $scope.formData.pairing_id = $scope.pairing_id;
        //console.log($scope.formData);

        var http = WebService.post('pos-payment/payment',$scope.formData);
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

    $scope.closeAlert = function(){
        $scope.warning_messages = $scope.success_messages = $scope.error_messages = '';
    };


    loadAllLco();

    if($scope.formData.lco_id != null && $scope.formData.lco_id > 0 && $scope.user_type == 'LCO'){
        loadCollectors($scope.formData.lco_id);
        loadSubscribers($scope.formData.lco_id);
        loadPosMachines($scope.formData.lco_id);
    }


    if($scope.formData.lco_id < 0){

        loadCollectors(1);
        loadSubscribers(1);
        loadPosMachines(1);

    }

    $scope.loadData();


}]);