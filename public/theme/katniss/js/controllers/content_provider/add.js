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

app.controller('CreateContentProvider',['$scope','WebService',function($scope,WebService){

    // $scope.content_aggregator_type ='0';

    $scope.closeAlert = function(){
        $scope.success_messages = '';
        $scope.warning_messages = '';
        $scope.error_messages = '';
    };
    $scope.formData = {is_active:1,content_aggregator_type:0};
    $scope.formData.duration = 30;
    $scope.formData.repeats = [];

    $scope.addRow = function(){
        var repeats = {repeat_date:'',repeat_time:''};
        $scope.formData.repeats.push(repeats);
    };

    $scope.hideForm = function(){
        $scope.showFrm = 0;
        $scope.formData = {};
    };

    $scope.showForm = function()
    {
        $scope.formData = {is_active:1};
        if($scope.permissions.create_permission == '1'){
            $scope.showFrm = 1;
        }else{
            $scope.warning_messages = "Sorry! You don't have permission to create Set-top Box";
        }

    };

    var loadPermissions = function(){
        var http = WebService.get('content-provider/ajax-get-permissions');
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.permissions=data.permissions;
            }
        });
    };

    loadPermissions();



    var generateKendoGird = function(){
        $scope.mainGridOptions = {

            dataSource: {
                schema: {
                    data: "content_provider",
                    total: "total"
                },
                transport: {
                    read: {
                        url: "content-provider/ajax-get-content-providers",
                        dataType: "json",
                    },
                    cache: false
                },
                pageSize: 10,
                serverPaging: true,
                serverFiltering: true

            },
            sortable: true,
            pageable: true,
            scrollable: true,
            resizable: true,
            filterable: {
                extra: false,
            },

            dataBound: gridDataBound,

            columns: [
                {field: "company_name", title: "Company Name",width: "auto",filterable:true},
                {field: "address", title: "Address",width: "auto",filterable:true},
                {field: "phone", title: "Phone",width: "auto",filterable:false},
                {field: "mobile",title: 'Mobile',width:"auto",filterable:false},
                {field: "lat",title: 'Lat',width:"auto",filterable:false},
                {field: "contact_person_1",title: 'Contact Person',width:"auto",filterable:false},
                {field: "contact_person_1_phone",title: 'Contact Person Phone',width:"auto",filterable:false},
                {field: "contact_person_1_email",title: 'Contact Person Email',width:"auto",filterable:false},
                {field: "contact_person_1_email",title: 'Contact Person Email',width:"auto",filterable:false},
                {field: "contact_person_1_designation",title: 'Contact Person Designation',width:"auto",filterable:false},
                {field: "", title: "Action",width: "auto",template:"<a href='"+SITE_URL+"content-provider/view/#=data.id#' class='btn btn-default btn-xs' data-toggle='tooltip' data-placement='left' title='View'><i class='fa fa-search'></i></a> <a ng-if='permissions.edit_permission==\"1\"' href='"+SITE_URL+"content-provider/edit/#=data.id#' class='btn btn-default btn-xs' data-toggle='tooltip' data-placement='left' title='Update'><i class='fa fa-pencil'></i></a>"},
            ]
        };
    };

    generateKendoGird();

   
    $scope.content_aggregator = function(){
        
        
        var http = WebService.get('content-provider/content_aggregator');
        http.then(function(response){
           var data = response.data;
            if(data.status==200){

                $scope.content_aggregator = data.content_aggregator;

            }


        });
    };
    $scope.content_aggregator();

    $scope.saveContent_provider = function(){

        var http = WebService.post('content-provider/save-content-provider',$scope.formData);
        http.then(function(response){
           var data = response.data;
            if(data.status==400){
                $scope.warning_messages = data.warning_messages;
                $("html,body").animate({scrollTop:'0px'});
            }else{
                $scope.success_messages = data.success_messages;
                $("html,body").animate({scrollTop:'0px'});
                $scope.hideForm();
                // generateKendoGird();
            }

        });
    };

    $scope.deleteItem = function(id){
        $scope.delete_flag = id;

    };

    $scope.cancel_delete = function(){
        $scope.delete_flag = '';
    };
    
    $scope.confirm_delete = function(){
        var http = WebService.get('content-provider/delete/'+$scope.delete_flag);
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                window.location.reload();
            }
        });
    };



    /*$scope.deleteRepeatRow = function(i){
        if($scope.formData.repeats  != undefined){
            $scope.formData.repeats.splice(i,1);
        }
    };*/

}]);