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
app.directive('stringToNumber', function() {
    return {
        require: 'ngModel',
        link: function(scope, element, attrs, ngModel) {
            ngModel.$parsers.push(function(value) {
                return '' + value;
            });
            ngModel.$formatters.push(function(value) {
                return parseFloat(value, 10);
            });
        }
    };
});
app.controller('EditIptvPackage',['$scope','WebService','FileUploader',function($scope,WebService,FileUploader){

    $scope.uri = uri;
    $scope.addFromFlag = 0;
    $scope.programs = [];
    $scope.assigned_programs = [];

    $scope.categories;
    $scope.sub_categories = [];
    $scope.permissions = {};
    $scope.formData = package;
   // $scope.formData.category = package.category_id; $scope.types = [{name:'Live',value:'live'},{name:'VOD',value:'vod'},{name:'Catch Up',value:'catchup'}];

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

    var loadPackagePrograms = function(){
        var http = WebService.get($scope.uri+'/ajax-get-package-programs/'+$scope.formData.id);
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.programs = data.programs;
                $scope.assigned_programs = data.assigned_programs;
            }
        });
    };



    loadPermissions();

    loadPackagePrograms();

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

    UploaderLogoSTB.onSuccessItem = function(fileItem, response, status, headers){
        if(response.status == 400){
            $scope.warning_messages = response.warning_messages;
        }
    };

    UploaderLogoMobile.onSuccessItem = function(fileItem, response, status, headers){
        if(response.status == 400){
            $scope.warning_messages = response.warning_messages;
        }
    };

    UploaderPosterMobile.onSuccessItem = function(fileItem, response, status, headers){
        if(response.status == 400){
            $scope.warning_messages = response.warning_messages;
        }
    };

    UploaderPosterSTB.onSuccessItem = function(fileItem, response, status, headers){
        if(response.status == 400){
            $scope.warning_messages = response.warning_messages;
        }
    };



    $scope.saveIptvPackage = function()
    {
        $scope.closeAlert();

        // upload Logo URL
        UploaderLogoSTB.onBeforeUploadItem = function(item) {
            UploaderLogoSTB.progress = 0;
            $scope.fileUploadPhotoProgress = 0;
            item.formData.push({id:$scope.formData.id,form_type:1});
        };
        UploaderLogoSTB.uploadAll();

        // upload Logo URL
        UploaderLogoMobile.onBeforeUploadItem = function(item) {
            UploaderLogoMobile.progress = 0;
            $scope.fileUploadPhotoProgress = 0;
            item.formData.push({id:$scope.formData.id,form_type:1});
        };
        UploaderLogoMobile.uploadAll();

        // upload Poster Mobile
        UploaderPosterMobile.onBeforeUploadItem = function(item) {
            UploaderPosterMobile.progress = 0;
            $scope.fileUploadPhotoProgress = 0;
            item.formData.push({id:$scope.formData.id,form_type:1});
        };
        UploaderPosterMobile.uploadAll();

        // upload Poster STB
        UploaderPosterSTB.onBeforeUploadItem = function(item) {
            UploaderPosterSTB.progress = 0;
            $scope.fileUploadPhotoProgress = 0;
            item.formData.push({id:$scope.formData.id,form_type:1});
        };
        UploaderPosterSTB.uploadAll();


        $scope.formData.programs = [];
        $scope.formData.selected_item=[];
        $scope.formData.included_item=[];
        for(p in $scope.assigned_programs)
        {
            $scope.formData.programs.push($scope.assigned_programs[p].id);
        }
        var http = WebService.post($scope.uri+'/update-package',$scope.formData);
        http.then(function(response){
           var data = response.data;
            if(data.status == 400){
                $scope.warning_messages = data.warning_messages;
            }else{




                $scope.success_messages = data.success_messages;
            }
            $("html,body").animate({scrollTop:'0px'});
        });
    }


}]);