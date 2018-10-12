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
app.controller('IptvPackage',['$scope','WebService','FileUploader',function($scope,WebService,FileUploader){

    $scope.addFromFlag = 0;
    $scope.uri = uri;
    $scope.programs = [];
    $scope.assigned_programs = [];

    $scope.categories = [];
    $scope.sub_categories = [];
    $scope.permissions = {};
    $scope.formData = {};


    $scope.showForm = function(){
        $scope.addFormFlag = 1;

    };

    $scope.hideForm = function(){
        $scope.addFormFlag = 0;
        $scope.formData = {};
    };

    $scope.closeAlert = function(){
        $scope.warning_messages = '';
        $scope.error_messages = '';
        $scope.success_messages = '';
    };

    var loadPermissions = function(){
        var http = WebService.get($scope.uri+'/ajax-get-permissions');
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.permissions = data.permissions;
            }
        });
    };

    var loadPrograms = function(){
        var http = WebService.get($scope.uri+'/ajax-get-programs');
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.programs = data.programs;
            }
        });
    };

    var loadCategories = function(){
        var http = WebService.get($scope.uri+'/ajax-get-categories');
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.categories = data.categories;
            }
        });
    };

    loadPermissions();
    loadCategories();
    loadPrograms();

    $scope.IncludeItems = function(){

        for(p in $scope.programs){
            console.log($scope.formData.selected_item)
            for(item in $scope.formData.selected_item){

                if($scope.programs[p].id == $scope.formData.selected_item[item])
                {
                    $scope.assigned_programs.push($scope.programs[p]);
                    $scope.programs.splice(p,1);
                }
            }
        }
    };

    $scope.ExcludeItems = function(){

        for(ap in $scope.assigned_programs){
            for(item in $scope.formData.included_item){
                if($scope.assigned_programs[ap].id == $scope.formData.included_item[item])
                {
                    $scope.programs.push($scope.assigned_programs[ap]);
                    $scope.assigned_programs.splice(ap,1);
                }
            }
        }

    };


    $scope.loadSubCategories = function(){
        var http = WebService.get($scope.uri+'/ajax-get-sub-categories/'+$scope.formData.category);
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.sub_categories = data.sub_categories;
            }
        });
    };

    var generateKendoGird = function(){
        $scope.mainGridOptions = {
            dataSource: {
                transport: {
                    read: {
                        url: $scope.uri+"/ajax-get-packages",
                        dataType: "json",
                    }
                },
                schema: {
                    data: "packages",
                    total: "total"
                },
                pageSize: 10,
                serverPaging: true,
                serverSorting:true,
                serverFiltering: true

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
                {field: "id", title: "ID", width: "60px",filterable:false},
                {field: "package_name", title: "Package Name", width: "200px"},
                {field: "programs",title:"Programs",width:"200px", filterable:false},
                {field: "duration",title:"Duration",width:"200px",filterable:false},
                {field: "price",title:"Price",width:"100px",filterable:false},
                {field: "isCommercial", title: "Is Commercial", width: "auto",filterable:false,template: '# if(data.is_commercial) {# <span class="label label-success">Yes</span> #} else {# <span class="label label-danger">No</span> #}#'},
                {field: "", title: "Action", width: "auto",filterable:false,template:"<a href='"+SITE_URL+$scope.uri+"/view/#=data.id#' class='btn btn-default btn-xs' data-toggle='tooltip' data-placement='left' title='View'><i class='fa fa-search'></i></a>"+
                ' <a ng-if="permissions.edit_permission==\'1\'" href="'+SITE_URL+$scope.uri+'/edit/#=data.id#" class="btn btn-default btn-xs" data-toggle="tooltip" data-placement="left" title="Edit"><i class="fa fa-pencil"></i></a> # if ((data.assigned>0))  {##} else {#<a ng-if="permissions.delete_permission==\'1\'&& (!#=data.not_deleteable#)"  ng-click="delete(#=data.id#)" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="left" title="Delete"><i class="fa fa-trash"></i></a>#}#'},
            ]
        };


    };

    generateKendoGird();

    $scope.closeAlert = function(){
        $scope.success_messages = '';
        $scope.warning_messages = '';
        $scope.error_messages = '';
    };

    var UploaderLogoSTB = $scope.UploaderLogoSTB = new FileUploader({
        headers: {'X-Requested-With':'XMLHttpRequest'},
        url: SITE_URL+$scope.uri+'/upload-logo-stb'
    });

    var UploaderLogoMobile = $scope.UploaderLogoMobile = new FileUploader({
        headers: {'X-Requested-With':'XMLHttpRequest'},
        url: SITE_URL+$scope.uri+'/upload-logo-mobile'
    });


    var UploaderPosterSTB = $scope.UploaderPosterSTB = new FileUploader({
        headers: {'X-Requested-With':'XMLHttpRequest'},
        url: SITE_URL+$scope.uri+'/upload-poster-stb'
    });

    var UploaderPosterMobile = $scope.UploaderPosterMobile = new FileUploader({
        headers: {'X-Requested-With':'XMLHttpRequest'},
        url: SITE_URL+$scope.uri+'/upload-poster-mobile'
    });

    $scope.saveIptvPackage = function()
    {
        $scope.closeAlert();
        $scope.formData.programs = [];
        $scope.formData.selected_item=[];
        $scope.formData.included_item=[];
        for(p in $scope.assigned_programs)
        {
            $scope.formData.programs.push($scope.assigned_programs[p].id);
        }
        var http = WebService.post($scope.uri+'/save-package',$scope.formData);
        http.then(function(response){
           var data = response.data;
            if(data.status == 400){
                $scope.warning_messages = data.warning_messages;
            }else{

                // upload Logo STB
                UploaderLogoSTB.onBeforeUploadItem = function(item) {
                    UploaderLogoUrl.progress = 0;
                    $scope.fileUploadPhotoProgress = 0;
                    item.formData.push({id:data.id,form_type:0});
                };
                UploaderLogoSTB.uploadAll();

                // upload Logo Mobile
                UploaderLogoMobile.onBeforeUploadItem = function(item) {
                    UploaderLogoUrl.progress = 0;
                    $scope.fileUploadPhotoProgress = 0;
                    item.formData.push({id:data.id,form_type:0});
                };
                UploaderLogoMobile.uploadAll();

                // upload Poster STB
                UploaderPosterSTB.onBeforeUploadItem = function(item) {
                    UploaderPosterSTB.progress = 0;
                    $scope.fileUploadPhotoProgress = 0;
                    item.formData.push({id:data.id,form_type:0});
                };
                UploaderPosterSTB.uploadAll();

                // upload Poster Mobile
                UploaderPosterMobile.onBeforeUploadItem = function(item) {
                    UploaderPosterMobile.progress = 0;
                    $scope.fileUploadPhotoProgress = 0;
                    item.formData.push({id:data.id,form_type:0});
                };
                UploaderPosterMobile.uploadAll();



                $scope.success_messages = data.success_messages;
                generateKendoGird();
                $scope.hideForm();
            }
            $("html,body").animate({scrollTop:'0px'});
        });

    }

    $scope.delete = function(i)
    {

        $scope.delete_item = i;
    }

    $scope.confirm_delete = function()
    {
        var location = SITE_URL + $scope.uri+'/delete/'+$scope.delete_item;
        window.location = location;
    }

    $scope.cancel_delete = function()
    {
        $scope.delete_item = 0;
    }


}]);