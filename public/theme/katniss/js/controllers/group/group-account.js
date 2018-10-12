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
app.controller('CreateGroupProfile',['$scope','WebService','FileUploader',function($scope,WebService,FileUploader){

    $scope.tabs = {profile:1,login:0,contract:0,photo:0,identity_verify:0};
    $scope.profile = {id:'',full_name:'',username:'',email:'',password:'',re_password:''};
    $scope.showFrm = 0;
    $scope.identity_verify_types = ['Nation ID','Passport','Utility Document'];
    $scope.token = null;
    $scope.identity = {};
    $scope.notStrongPass = 0;
    $scope.check_re_password =0;
    $scope.fileUploadPhotoProgress = 0;
    $scope.fileUploadIdentityProgress = 0;
    $scope.roles = [];
    $scope.permissions = [];
    $scope.countries = [];

    $scope.showForm = function()
    {
        $scope.showFrm = 1;
        $scope.success_messages = $scope.warning_messages = $scope.error_messages = '';
    };

    $scope.hideForm = function(){
        /*if($scope.role_type == "admin" && $scope.user_type != "lco"){

            if($scope.token != null && $scope.billing_address_id == null){
                $scope.warning_messages = 'Please save billilng address before close.';
                return;
            }

            if($scope.token != null &&
                ($scope.profile.region_l1_code == null && $scope.profile.region_l2_code == null
                && $scope.profile.region_l3_code == null && $scope.profile.region_l4_code == null)){
                $scope.warning_messages = 'Please save business region before close.';
                return;
            }

        }*/

        $scope.setTab('profile');
        $scope.showFrm = 0;
        $scope.profile = {id:'',full_name:'',username:'',email:'',password:'',re_password:''};
        $scope.billing_address = {id:'',full_name:'',email:''};
        $scope.identity = {};
        $scope.closeAlert();
    };

    var resetMessage = function()
    {
        $scope.success_messages = '';
        $scope.warning_messages = '';
        $scope.error_messages = '';
    }

    $scope.closeAlert = function(){
        resetMessage();
    };

    $scope.checkPassWordStrength = function(){
        var password = ($scope.profile.password);
        //var strongRegex = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[@#])(?=.{8,})");
        var mediumRegex = new RegExp("^(?=.{8,})");
        if(!mediumRegex.test(password)){
            $scope.notStrongPassFlag = 1;
            $scope.pass_message = 'Password should be at least 8 characters long';
        }else{
            $scope.notStrongPassFlag = 0;
            $scope.pass_message = '';
        }
    };

    $scope.checkRePassword = function(){
        if($scope.profile.password != $scope.profile.re_password){
            $scope.checkRePasswordFlag = 1;
            $scope.re_pass_message = 'Re-password not matched';
        }else{
            $scope.checkRePasswordFlag = 0;
            $scope.re_pass_message = '';
        }
    };

    $scope.isSaveLoginDisabled = function(){
        if($scope.notStrongPassFlag){
            return true;
        }
        if($scope.checkRePasswordFlag){
            return true;
        }
        return false;
    };

    $scope.setTab = function(tab){

        switch(tab){
            case 'profile':
                $scope.tabs = {profile:1,login:0,billing_address:0,business_region:0,photo:0,identity_verify:0,business_modality:0};
                break;
            case 'login':

                if($scope.token != null){
                    $scope.tabs = {profile:0,login:1,billing_address:0,business_region:0,photo:0,identity_verify:0,business_modality:0};

                } else {
                    $scope.warning_messages = 'You have to create profile before add login Info';
                    $("html,body").animate({scrollTop:'0px'});
                }
                break;
            case 'billing_address':
                if($scope.token != null){
                    $scope.tabs = {profile:0,login:0,billing_address:1,business_region:0,photo:0,identity_verify:0,business_modality:0};
                } else {
                    $scope.warning_messages = 'You have to create profile before add billing address';
                    $("html,body").animate({scrollTop:'0px'});
                }

                break;
            case 'business_region':
                if($scope.token != null){
                    $scope.tabs = {profile:0,login:0,billing_address:0,business_region:1,photo:0,identity_verify:0,business_modality:0};
                } else {
                    $scope.warning_messages = 'You have to create profile before add business region';
                    $("html,body").animate({scrollTop:'0px'});
                }
                break;
            case 'photo':
                if($scope.token != null) {
                    $scope.tabs = {profile:0,login:0,billing_address:0,business_region:0,photo:1,identity_verify:0,business_modality:0};
                } else {
                    $scope.warning_messages = 'You have to create profile before attach photo';
                    $("html,body").animate({scrollTop:'0px'});
                }

                break;
            case 'identity_verify':
                if($scope.token != null) {
                    $scope.tabs = {profile:0,login:0,billing_address:0,business_region:0,photo:0,identity_verify:1,business_modality:0};
                } else {
                    $scope.warning_messages = 'You have to create profile before attach Identity verification';
                    $("html,body").animate({scrollTop:'0px'});
                }

                break;
            case 'business_modality':
                if($scope.token != null) {
                    $scope.tabs = {profile:0,login:0,billing_address:0,business_region:0,photo:0,identity_verify:0,business_modality:1};
                } else {
                    $scope.warning_messages = 'You have to create profile before add business modality';
                    $("html,body").animate({scrollTop:'0px'});
                }
        }
    };

    $scope.saveProfile = function(){
        resetMessage();
        $scope.profile.form_type = 0;
        var http = WebService.post('groups/create-profile',$scope.profile);
        http.then(function(response){
            var data = response.data;
            if(data.status == 400){
                $scope.warning_messages = data.warning_messages;
                $scope.success_messages = '';
            } else {
                $scope.success_messages = data.success_messages;
                $scope.warning_messages = '';
                $scope.token = data.token;

                if($scope.profile.username == "")
                    $scope.profile.username = $scope.profile.email;

                $scope.copy_profile = 1;

                loadProfiles();
                //$scope.setBillingAddress();
                $scope.loadNotification();
                if($scope.token != null){
                    $scope.setTab('login');
                }
            }
            $("html,body").animate({scrollTop:'0px'});
        });
    };

    $scope.saveLogin = function(){
        resetMessage();

        if($scope.profile.username=='' || $scope.profile.username == undefined){
            $scope.warning_messages = 'Username cannot be blank';
            return;
        }

        // if mso user then assign lco admin role
        if($scope.user_type == 'mso'){
            $scope.profile.role_id = 3;
        }

        var formData = {
            token:$scope.token,
            username:$scope.profile.username,
            password:$scope.profile.password,
            re_password:$scope.profile.re_password,
            is_remote_access_enabled:$scope.profile.is_remote_access_enabled,
            form_type:0,
            role_id:$scope.profile.role_id
        };
        if($scope.token != null || $scope.token != undefined){
            var http = WebService.post('groups/update-login-info',formData);
        }else{
            var http = WebService.post('groups/create-login-info',formData);
        }
        http.then(function(response){
            var data = response.data;
            if(data.status == 400){
                $scope.warning_messages = data.warning_messages;
                $scope.success_messages = '';
            } else {
                $scope.success_messages = data.success_messages;
                $scope.warning_messages = '';
                $scope.loadNotification();
                loadProfiles();
                if($scope.token != null){
                    $scope.setTab('photo');
                }
            }
            $("html,body").animate({scrollTop:'0px'});
        });
    };

    // Photo upload related functions
    var uploader = $scope.uploader = new FileUploader({
        headers: {'X-Requested-With':'XMLHttpRequest'},
        url: SITE_URL+'groups/upload-photo'
    });

    uploader.onBeforeUploadItem = function(item) {
        uploader.progress = 0;
        $scope.fileUploadPhotoProgress = 0;
        item.formData.push({token:$scope.token});
    };

    uploader.onAfterAddingFile = function(fileItem) {
        uploader.progress = 0;
        $scope.fileUploadPhotoProgress = 0;

    };

    uploader.onProgressItem = function(fileItem, progress) {

        $scope.fileUploadPhotoProgress =  progress;
    };

    uploader.onSuccessItem = function(fileItem, response, status, headers) {
        if (response.status == 200){
            $scope.uploadView = false;
            $scope.profile.photo = response.image;
            $scope.success_messages = response.success_messages;
            $scope.warning_messages = '';
            $scope.loadNotification();
        }else{
            uploader.progress = 0;
            $scope.fileUploadPhotoProgress = 0;
            $scope.warning_messages = response.warning_messages;
            $scope.success_messages = '';
            $scope.uploadView = false;
        }
    };

    var identityUploader = $scope.identityUploader = new FileUploader({
        headers: {'X-Requested-With':'XMLHttpRequest'},
        url: SITE_URL+'groups/upload-identity'
    });

    identityUploader.onBeforeUploadItem = function(item) {
        identityUploader.progress = 0;
        $scope.fileUploadIdentityProgress = 0;
        item.formData.push($scope.identity);
        item.formData.push({token:$scope.token});
    };

    identityUploader.onAfterAddingFile = function(fileItem) {
        identityUploader.progress = 0;
        $scope.fileUploadIdentityProgress = 0;

    };

    identityUploader.onProgressItem = function(fileItem, progress) {
        $scope.fileUploadIdentityProgress =  progress;
    };

    identityUploader.onSuccessItem = function(fileItem, response, status, headers) {
        if (response.status == 200){
            $scope.identity.identity_attachment = response.image;
            $scope.success_messages = response.success_messages;
            $scope.warning_messages = '';
            $scope.loadNotification();
        }else{
            identityUploader.progress = 0;
            $scope.fileUploadIdentityProgress = 0;
            $scope.warning_messages = response.warning_messages;
            $scope.success_messages = '';
            $scope.uploadView = false;
        }
    };

    $scope.saveModality = function(){
        resetMessage();
        $scope.modality.token = $scope.token;
        $scope.modality.form_type = 0;
        var http = WebService.post('groups/update-modality',$scope.modality);
        http.then(function(response){
            var data = response.data;
            if (data.status == 400) {
                $scope.warning_messages = data.warning_messages;
                $scope.success_messages = '';
            } else {
                $scope.success_messages = data.success_messages;
                $scope.warning_messages = '';
                $scope.loadNotification();
            }
            $("html,body").animate({scrollTop:'0px'});
        });
    };

    // load locations
    var loadLocations = function(type,model){
        var formData = {};

        if(type == 'divisions'){
            formData.country_id = model.country_id;
        }else if(type == 'districts'){
            formData.division_id = model.division_id;
        }else if(type == 'areas'){
            formData.district_id = model.district_id;
        }else if(type == 'sub_areas'){
            formData.area_id = model.area_id;
        }else if(type == 'roads'){
            formData.sub_area_id = model.sub_area_id;
        }

        var http = WebService.post('groups/ajax-get-location-request/'+type,formData);
        http.then(function(response){
            var data = response.data;
            if(type == 'divisions'){
                $scope.divisions = data;
                //$("#division_id").removeAttr('disabled');
            }else if(type == 'districts'){
                $scope.districts = data;
                //$("#district_id").removeAttr('disabled');
            }else if(type == 'areas'){
                $scope.areas = data;
                //$("#area_id").removeAttr('disabled');
            }else if(type == 'sub_areas'){
                $scope.sub_areas = data;
                //$("#sub_area_id").removeAttr('disabled');
            }else if(type == 'roads'){
                $scope.roads = data;
                //$("#road_id").removeAttr('disabled');
            }

        });
    };

    $scope.$watch('profile.country_id',function(val){
        if(val != null){
            loadLocations('divisions',$scope.profile);
            $scope.profile.division_id = undefined;
            $scope.profile.district_id = undefined;
            $scope.profile.area_id = undefined;
            $scope.profile.sub_area_id = undefined;
            $scope.profile.road_id = undefined;
            $scope.districts = [];
            $scope.areas = [];
            $scope.sub_areas = [];
            $scope.roads = [];
        }
    });

    $scope.$watch('profile.division_id',function(val){
        if(val != null){
            loadLocations('districts',$scope.profile);
            $scope.profile.district_id = undefined;
            $scope.profile.area_id = undefined;
            $scope.profile.sub_area_id = undefined;
            $scope.profile.road_id = undefined;
            $scope.areas = [];
            $scope.sub_areas = [];
            $scope.roads = [];
        }
    });

    $scope.$watch('profile.district_id',function(val){
        if(val != null){
            loadLocations('areas',$scope.profile);
            $scope.profile.area_id = undefined;
            $scope.profile.sub_area_id = undefined;
            $scope.profile.road_id = undefined;
            $scope.sub_areas = [];
            $scope.roads = [];

        }
    });

    $scope.$watch('profile.area_id',function(val){
        if(val != null){
            loadLocations('sub_areas',$scope.profile);
            $scope.profile.sub_area_id = undefined;
            $scope.roads = [];
        }
    });

    $scope.$watch('profile.sub_area_id',function(val){
        if(val != null){
            loadLocations('roads',$scope.profile);
            $scope.profile.road_id = undefined;
        }
    });

    var loadPermissions = function(){
        var http = WebService.get('groups/ajax-get-permissions');
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.permissions=data.permissions;
            }
        });
    };

    $scope.changeBillingContact = function(){
        if($scope.billing_contact_auto){
            $scope.profile.billing_contact = $scope.profile.contact;
            $scope.billing_address.billing_contact = $scope.billing_address.contact;
        } else{
            $scope.profile.billing_contact = null;
            $scope.billing_address.billing_contact = null;
        }
    };

    var generateKendoGird = function(){
        $scope.mainGridOptions = {
            dataSource: {
                /* type: "jsonp",
                 data:$scope.items,
                 pageSize: 5,*/

                schema: {
                    data: "profiles",
                    total: "total"
                },
                pageSize: 10,
                transport: {
                    read: {
                        url: "groups/ajax-load-profiles",
                        dataType: "json",
                    }
                },

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
                {field: "group_name", title: "Name",width: "auto"},
                {field: "username", title: "Username",width: "auto"},
                {field: "email", title: "E-mail",width: "auto"},
                {field: "status", title: "Status",filterable:false,template: '# if(data.user_status==1) {# <span class="label label-success">Active</span> #} else {# <span class="label label-danger">Inactive</span> #}#'},
                {field: "", title: "Action",width: "auto",filterable:false,template:"<a href='"+SITE_URL+"groups/view/#=data.token#' class='btn btn-default btn-xs' data-toggle='tooltip' data-placement='left' title='View'><i class='fa fa-search'></i></a> <a ng-if='permissions.edit_permission == \"1\"' href='groups/edit/#=data.token#' class='btn btn-default btn-xs tool1' data-toggle='tooltip' data-placement='left' title='Edit'><i class='fa fa-pencil'></i></a>"},
            ]
        };
    };

    var loadProfiles = function(){
        $scope.$watch('items',function(val){
            if(val != undefined && val.length){
                $scope.loader = 0;
            } else {
                $scope.loader = 1;
            }
        });
        var http = WebService.get('groups/ajax-load-profiles');
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.user_type = data.user_type;
                $scope.role_type = data.role_type;
                $scope.items = data.profiles;
                $scope.roles = data.roles;
                $scope.countries = data.countries;
                generateKendoGird();



            }
        });
    };

    loadPermissions();
    loadProfiles();

}]);
