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
app.controller('BulkSubscriber',['$scope','WebService',function($scope,WebService){
        
    $scope.formData = {subscriber_amount: 100, initial_balance: 50, package_expire_after: 30, account_expire_after: 0};
    
    var resetMessage = function()
    {
        $scope.success_messages = '';
        $scope.warning_messages = '';
        $scope.error_messages = '';
    };
    
    $scope.hideForm = function(){ 
        resetMessage();
        $scope.showFrm = 0;
    };
    
    $scope.showForm = function()
    {
        $scope.showFrm = 1;
        resetMessage();
    };
    
    $scope.closeAlert = function(){
        resetMessage();
    };
    
    $scope.resetForm = function()
    {
        $scope.formData = {subscriber_amount: 100, initial_balance: 50};
    };
    
    var loadPackages = function(){
        var http = WebService.get('subscriber/get-packages');
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.packages = data.packages;
                console.log(data.packages);
            }
        });
    };
    loadPackages();
    
    $scope.saveBulkSubscriber = function(i){
        var formData = {
            subscriber_amount: $scope.formData.subscriber_amount,
            user_prefix: $scope.formData.user_prefix,
            initial_balance: $scope.formData.initial_balance,
            default_package: $scope.formData.default_package,
            package_expire_after: $scope.formData.package_expire_after,
            account_expire_after: $scope.formData.account_expire_after,
        }
        var http = WebService.post('subscriber/save-bulk-subscriber',formData);
        http.then(function(response){
            var data = response.data;
            if(data.status==400){
                $scope.warning_messages = data.warning_messages;
                $scope.success_messages = '';
            } else {
                $scope.success_messages = data.success_messages;
                $scope.warning_messages = '';
                $scope.formData = {subscriber_amount: 100, initial_balance: 50, package_expire_after: 30, account_expire_after: 0};
                $scope.showFrm = 0;
                generateKendoGird();
            }
            $("html,body").animate({scrollTop:'0px'});
        });
    };
    
    
    var generateKendoGird = function(){
        $scope.mainGridOptions = {
            dataSource: {
               /* 
                static non server-side
                type: "jsonp",
                data:items,
                pageSize: 10,*/
                transport: {
                    read: {
                        url: "subscriber/get-bulk-subscriber", 
                        dataType: "json",
                    }
                },
                schema: {
                    data: "subscribers",
                    total: "total"
                },
                pageSize: 10,
                serverPaging: true,
                serverSorting:true,
                serverFiltering: true
            },
            filterable: {
               extra: false,
               operators: {
                    string: {
                        startswith: "Starts with",
                        eq: "Is equal to",
                       
                    }
                }
            },
            sortable: true,
            pageable: true,
            scrollable: true,
            resizable: true,
            
            dataBound: gridDataBound,

            columns: [
                
                {field: "process_id", title: "Card Batch ID",width: "350px"},
                {field: "created_at", title: "Date Time",width: "auto"},
                {field: "user_prefix",title:"User Prefix", headerAttributes:{"style":"text-align:center;"},attributes: {"class": "text-center"}, width:"150px",filterable:true},
                {field: "initial_balance", title: "Initial Balance",width: "150px",filterable:false},
                {field: "subscriber_amount", title: "Total Subscriber",width: "150px",filterable:false},
                {field: "", title: "",width: "50px",
                template:"<a  href='"+SITE_URL+"subscriber/bulk-subscriber/view/#=data.id#' class='btn btn-default btn-xs' data-toggle='tooltip' data-placement='left' title='View'><i class='fa fa-search'></i></a>"},
            ]
        };
    };
    generateKendoGird();
    
    
    var formValidate = function(){
      $scope.$watch('formData.user_prefix',function(val){
          
            var pattern = /^[a-zA-Z0-9]{1,11}$/
                if(val != null)
                {
                    if(!pattern.test(val)){
                        $scope.warning_messages = 'Prefix only alpha numaric characters';
                        $scope.formData.amount = '';
                        $("html,body").animate({scrollTop:"0px"});
                    }else{
                        $scope.warning_messages = '';
                    }
                }
        });  
    };
    
    formValidate();


}]);
