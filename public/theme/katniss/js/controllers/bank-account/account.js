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
app.controller('BankAccount',['$scope','WebService',function($scope,WebService){
    $scope.accounts = [];
    $scope.user_type = user_type;

    $scope.closeAlert = function(){
        $scope.warning_messages = $scope.success_messages = $scope.error_messages = '';
    };

    var formValidation = function(){
        var regex = /^\d+$/;
        $scope.warning_messages = '';

        if(!regex.test($scope.bankAccount.account_no)){
            $scope.warning_messages = 'Sorry! Account Number should be numeric';
            $("html,body").animate({"scrollTop":"0px"});
            return false;
        }

        return true;
    };

    $scope.saveBankAccount = function(){
        var isValid = formValidation();
        if(!isValid){
            return isValid;
        }
        var http = WebService.post('bank-accounts/create',$scope.bankAccount);
        http.then(function(response){
           var data = response.data;
           if(data.status == 400){
                $scope.warning_messages = data.warning_messages;
                $scope.success_messages = '';
           }else{
               $scope.hideForm();
               $scope.success_messages = data.success_messages;
               $scope.warning_messages = '';
               var gridObj = angular.element("#accountGrid").data('kendoGrid');
               gridObj.dataSource.read();
               gridObj.refresh();
           }
        });
    };

    $scope.hideForm = function(){
        $scope.showFrm = 0;
    }

    $scope.showForm = function()
    {
        $scope.showFrm = 1;
    }

    var generateKendoGird = function(){
        $scope.mainGridOptions = {
            dataSource: {

                schema: {
                    data: "accounts",
                    total: "total"
                },

                transport: {
                    read: {
                        url: SITE_URL+"bank-accounts/ajax-get-accounts",
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
                {field: "account_name", title: "Account Name",width: "auto"},
                {field: "account_no", title: "Account Number",width: "auto"},
                {field: "bank_name", title: "Bank Name",width: "auto"},
                {field: "shared_account_id", title: "Is Shared",width: "auto",template:'#if(shared_account_id!=null){# <span class="label label-success">Yes</span> #}else{# <span class="label label-primary">No</span> #}#'},
                {field: "", filterable: false, title: "Action",width: "auto",template:"#if(shared_account_id!=null){#<a href='"+SITE_URL+"bank-accounts/view/#=data.token#' class='btn btn-default btn-xs' data-toggle='tooltip' data-placement='left' title='View'><i class='fa fa-search'></i></a>#}else{#<a href='"+SITE_URL+"bank-accounts/view/#=data.token#' class='btn btn-default btn-xs' data-toggle='tooltip' data-placement='left' title='View'><i class='fa fa-search'></i></a> <a ng-if='permissions.edit_permission == \"1\"' href='"+SITE_URL+"bank-accounts/edit/#=data.token#' class='btn btn-default btn-xs' data-toggle='tooltip' data-placement='left' title='Edit'><i class='fa fa-pencil'></i></a>#}#"},
            ]
        };

        if($scope.user_type == 'MSO'){
            $scope.mainGridOptions.columns[4].template= " <a href='"+SITE_URL+"bank-accounts/view/#=data.token#' class='btn btn-default btn-xs' data-toggle='tooltip' data-placement='left' title='View'><i class='fa fa-search'></i></a> <a ng-if='permissions.edit_permission == \"1\"' href='"+SITE_URL+"bank-accounts/edit/#=data.token#' class='btn btn-default btn-xs' data-toggle='tooltip' data-placement='left' title='Edit'><i class='fa fa-pencil'></i></a> <a ng-if='permissions.edit_permission == \"1\"' href='"+SITE_URL+"bank-accounts/share/#=data.token#' class='btn btn-default btn-xs' data-toggle='tooltip' data-placement='left' title='Share'><i class='fa fa-share-alt'></i></a> ";
        }
    };

    generateKendoGird();

    var loadPermissions = function(){
        var http = WebService.get('bank-accounts/ajax-get-permissions');
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.permissions=data.permissions;
            }
        });
    };

    loadPermissions();

}]);
app.controller('EditBankAccount',['$scope','WebService',function($scope,WebService){
    $scope.token = token;
    $scope.bankAccount = {};

    $scope.closeAlert = function(){
        $scope.warning_messages = $scope.success_messages = $scope.error_messages = '';
    };

    var loadAccountInfo = function(){
        var http = WebService.post('bank-accounts/ajax-get-account-info',{token:$scope.token});
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.bankAccount = data.bank_account;
            }
        });
    };

    loadAccountInfo();

    var formValidation = function(){
        var regex = /^\d+$/;
        $scope.warning_messages = '';

        if(!regex.test($scope.bankAccount.account_no)){
            $scope.warning_messages = 'Sorry! Account Number should be numeric';
            $("html,body").animate({"scrollTop":"0px"});
            return false;
        }

        return true;
    };

    $scope.updateBankAccount = function(){
        var isValid = formValidation();
        if(!isValid){
            return isValid;
        }
        var http = WebService.post('bank-accounts/update',$scope.bankAccount);
        http.then(function(response){
            var data = response.data;

            if(data.status == 400){
                $scope.warning_messages = data.warning_messages;
                $scope.success_messages = '';
            }else{
                $scope.success_messages = data.success_messages;
                $scope.warning_messages = '';
                window.location = SITE_URL+'bank-accounts';
            }

        });
    };
}]);
app.controller('ViewBankAccount',['$scope','WebService',function($scope,WebService){
    $scope.account = {};
    $scope.account_details = [];
    $scope.token = token;
    $scope.user_type=user_type;
    $scope.hideFlag = 0;
    var loadBankAccount = function(){
        var http = WebService.get('bank-accounts/ajax-get-bank-account-details/'+$scope.token);
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.account = data.account;
                $scope.account_details = data.account_details;

                $scope.hideFlag = 1;

            }
        });

    };
    $scope.getStatus = function(){
      if($scope.account_details.length>0){
          return 'Yes';
      }
      return 'No';
    };


    loadBankAccount();

    var loadPermissions = function(){
        var http = WebService.get('bank-accounts/ajax-get-permissions');
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.permissions=data.permissions;
            }
        });
    };

    loadPermissions();
}]);
app.controller('ShareBankAccount',['$scope','WebService',function($scope,WebService){
    $scope.lco_profiles = [];
    $scope.accounts = [];
    $scope.sharedAccountList = [];
    $scope.accountShareList = [];
    $scope.permissions = [];

    var loadLco = function(){
        var http = WebService.get('bank-accounts/ajax-get-lco');
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.lco_profiles = data.lco_profiles;
            }
        });
    };

    var loadAccounts = function(){
        var http = WebService.get('bank-accounts/ajax-get-accounts');
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.accounts = data.accounts;
            }
        });
    };

    $scope.addShareAcc = function(){
        var account = {};
        var lco = {};

        $scope.accounts.filter(function(obj){
           if(obj.account_no == $scope.account_no) {
               account = obj;
           }
        });

        $scope.lco_profiles.filter(function(obj){
           if(obj.user_id == $scope.lco_user_id){
               lco = obj;
           }
        });

        var item = {
            id:'',
            account_name:account.account_name,
            account_no: account.account_no,
            bank_name : account.bank_name,
            lco_name  : lco.lco_name,
            lco_user_id : lco.user_id,
            bank_account_id : account.id
        };

        $scope.accountShareList.push(item);


    };

    $scope.deleteItem = function(i){
        if($scope.accountShareList[i] != undefined){
            $scope.accountShareList.splice(i,1);
        }
    };

    $scope.confirmShareAccount = function(i){

        if($scope.accountShareList[i] != null){
            var item = $scope.accountShareList[i];
            var http = WebService.post('bank-accounts/share-account',item);
            http.then(function(response){
               var data = response.data;
               if(data.status == 400){
                    $scope.warning_messages = data.warning_messages;
               }else{
                    $scope.success_messages = data.success_messages;
                    $scope.accountShareList[i].id = data.id;
               }
            });
        }
    };

    $scope.closeAlert = function(){
        $scope.warning_messages = $scope.success_messages = $scope.error_messages = '';
    }

    var loadSharedAccounts = function(){
        var http = WebService.get('bank-accounts/ajax-get-shared-accounts');
        http.then(function(response){
           var data = response.data;
           if(data.status == 200){
                $scope.sharedAccountList = data.shared_accounts;
           }
        });
    };



    loadLco();
    loadAccounts();
    //loadSharedAccounts();


}]);
