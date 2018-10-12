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
app.controller('LcoImport',['$scope','WebService','FileUploader',function($scope,WebService,FileUploader){
    $scope.fileUploadProgress = 0;
    $scope.regions = [];
    $scope.regions_level_2 = [];
    $scope.regions_level_3 = [];
    $scope.regions_level_4 = [];
    // File upload section
    var uploader = $scope.uploader = new FileUploader({
        headers: {'X-Requested-With':'XMLHttpRequest'},
        url: SITE_URL+'lco/import-lco',
        removeAfterUpload:true
    });
    $scope.loader = 0;
    uploader.onBeforeUploadItem = function(item) {
        uploader.progress = 0;
        $scope.fileUploadProgress = 0;
        $scope.loader = 1;
        var businessRegion = {
            'r1':$scope.business_region_l1,
            'r2':$scope.business_region_l2,
            'r3':$scope.business_region_l3,
            'r4':$scope.business_region_l4
        };
        item.formData.push(businessRegion);
    };

    uploader.onAfterAddingFile = function(fileItem) {

        uploader.progress = 0;
        $scope.fileUploadProgress = 0;

    };

    uploader.onProgressItem = function(fileItem, progress) {

        $scope.fileUploadProgress =  progress;
    };

    uploader.onSuccessItem = function(fileItem, response, status, headers) {

        if (response.status == 200){
            $scope.uploadView = false;
            $scope.success_messages = response.success_messages;
            $scope.warning_messages = '';
            $scope.loadNotification();
            $scope.loader = 0;

        }else{
            uploader.progress = 0;
            $scope.fileUploadProgress = 0;
            $scope.warning_messages = response.warning_messages;
            $scope.success_messages = '';
            $scope.uploadView = false;
        }

        //uploader.clearQueue();
        clearFileInputField('fileValue');
        $("html,body").animate({scrollTop:'0px'},500);


    };

    $scope.uploadFile = function(){
        /*if($scope.business_region_l1 == undefined){
            $scope.warning_messages = 'Please Select Region Level 1';
            return false;
        }
        if($scope.business_region_l2 == undefined){
            $scope.warning_messages = 'Please Select Region Level 2';
            return false;
        }
        if($scope.business_region_l3 == undefined){
            $scope.warning_messages = 'Please Select Region Level 3';
            return false;
        }*/

        uploader.uploadAll();
    }

    var clearFileInputField = function(tagId) {
        var fileValue = document.getElementById(tagId);

        fileValue.innerHTML = '';
        var fileObj = document.getElementById('file');
        console.log(fileObj.value="");
    };

    $scope.refresh = function(){
        window.location.reload();
    };

    $scope.closeAlert = function(){
        $scope.warning_messages = '';
        $scope.success_messages = '';
        $scope.error_messages = '';
    };

    var loadRegions = function(){
        var http = WebService.get('lco/ajax_load_region');
        http.then(function(response){
            var data = response.data;
            $scope.regions = data;

        });
    };

    loadRegions();

    $scope.setRegionLevel2 = function(){
        var level_2 = eval($scope.business_region_l1);
        if($scope.regions != undefined){
            $scope.regions_level_2 = $scope.regions[level_2].childs;
            $scope.regions_level_3 = [];
            $scope.regions_level_4 = [];
        }
    };

    $scope.setRegionLevel3 = function(){
        var level_3 = eval($scope.business_region_l2);
        $scope.regions_level_3 = $scope.regions_level_2[level_3].childs;
        $scope.regions_level_4 = [];
    };

    $scope.setRegionLevel4 = function(){
        var level_4 = eval($scope.business_region_l3);
        $scope.regions_level_4 = $scope.regions_level_3[level_4].childs;
    };


}]);