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
app.controller('EditGroupProfile',['$scope','WebService','FileUploader',function($scope,WebService,FileUploader){

    $scope.profile = {id:'',mso_name:'',email:'',password:'',re_password:''};
    $scope.tabs = {profile:1,login:0,billing_address:0,business_region:0,photo:0,identity_verify:0,business_modality:0};
    $scope.countries = $scope.divisions = $scope.districts = $scope.areas = $scope.sub_areas = $scope.roads = [];
    $scope.identity_verify_types = ['Nation ID','Passport','Utility Document'];
    $scope.token = token;
    $scope.identity = {};
    $scope.modality = {token:''};
    $scope.loader = 0;
    $scope.billing_address_id = null;
    $scope.billing_address = {id:'',name:'',subscriber_name:'',email:'',token:''};
    $scope.notStrongPassFlag = 0;
    $scope.checkRePasswordFlag = 0;
    $scope.pass_message = '';
    $scope.re_pass_message = '';
    $scope.role_type = null;
    $scope.user_type = null;
    $scope.roles = [];
    $scope.fileUploadPhotoProgress = 0;
    $scope.fileUploadIdentityProgress = 0;

    // show hide loader base on profile data loaded or not
    $scope.$watch('profile',function(val){
        
        if(val.id != ""){
            $scope.loader = 0;
            
        } else {

            $scope.loader = 1;
        }

    });

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

    $scope.closeAlert = function(){
     resetMessage();
    };

 var uploader = $scope.uploader = new FileUploader({
    headers: {'X-Requested-With':'XMLHttpRequest'},
    url: SITE_URL+'groups/upload-photo'
});

 uploader.onBeforeUploadItem = function(item) {
     uploader.progress = 0;
     $scope.fileUploadPhotoProgress = 0;
    item.formData.push({token:$scope.token,form_type:1});
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
        $scope.loadNotification();
    }else{
        uploader.progress = 0;
        $scope.fileUploadPhotoProgress = 0;
        $scope.warning_messages = response.warning_messages;
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
    item.formData.push({token:$scope.token,form_type:1});
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

$scope.setTab = function(tab){
    resetMessage();
    switch(tab){
        case 'profile':
        $scope.tabs = {profile:1,login:0,billing_address:0,business_region:0,photo:0,identity_verify:0,business_modality:0};
        break;
        case 'login':
        
        $scope.tabs = {profile:0,login:1,billing_address:0,business_region:0,photo:0,identity_verify:0,business_modality:0};
        break;
        case 'billing_address':
        
        $scope.tabs = {profile:0,login:0,billing_address:1,business_region:0,photo:0,identity_verify:0,business_modality:0};
        break;
        case 'business_region':
        
        $scope.tabs = {profile:0,login:0,billing_address:0,business_region:1,photo:0,identity_verify:0,business_modality:0};
        break;
        case 'photo':
        
        $scope.tabs = {profile:0,login:0,billing_address:0,business_region:0,photo:1,identity_verify:0,business_modality:0};
        break;
        case 'identity_verify':
        
        $scope.tabs = {profile:0,login:0,billing_address:0,business_region:0,photo:0,identity_verify:1,business_modality:0};
        break;
        case 'business_modality':
        
        $scope.tabs = {profile:0,login:0,billing_address:0,business_region:0,photo:0,identity_verify:0,business_modality:1};
        break;
    }
};

$scope.updateProfile = function(){
    resetMessage();
    $scope.profile.form_type=1;
    var http = WebService.post('groups/update-profile',$scope.profile);
    http.then(function(response){
        var data = response.data;
        if(data.status == 400){
            $scope.warning_messages = data.warning_messages;
            $scope.success_messages = '';
        } else {
            $scope.success_messages = data.success_messages;
            $scope.warning_messages = '';
            $scope.loadNotification();
            
        }
        $("html,body").animate({scrollTop:"0px"});
    });
};

$scope.updateLogin = function(){
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
        form_type:1,
        role_id:$scope.profile.role_id
    };
    var http = WebService.post('groups/update-login-info',formData);
    http.then(function(response){
        var data = response.data;
        if(data.status==400){
            $scope.warning_messages = data.warning_messages;
        } else {
            $scope.success_messages = data.success_messages;
            $scope.loadNotification();
        }
    });
};

    // reset all alert message
    var resetMessage = function()
    {
        $scope.success_messages = '';
        $scope.warning_messages = '';
        $scope.error_messages = '';
    }

    // load mso profile info
    var loadProfile = function(){
        var http = WebService.get('groups/ajax-get-profile/'+$scope.token);
        http.then(function(response){
            var data = response.data;
            $scope.profile = data.profile;
            $scope.user_type = data.user_type;
            $scope.role_type = data.role_type;
            $scope.roles = data.roles;

            /*if($scope.profile.business_region_assigned == 0 && $scope.user_type=='mso'){
                $scope.warning_messages = 'Please Save Business Region for this LCO otherwise LCO will not able to create Subscriber';
            }*/

            if($scope.profile.contact != ""){
                if($scope.profile.contact != $scope.profile.billing_contact){
                    $scope.profile.is_same_as_contact = '0';
                }else{
                    $scope.profile.is_same_as_contact = '1';
                }
            }

            $scope.billing_address = data.billing_address;


            if($scope.billing_address!=null)
            {
                $scope.billing_address_id = data.billing_address.id;
            }




            $scope.profile.password = '';
            $scope.identity.type = data.profile.identity_type;
            $scope.identity.id   = data.profile.identity_number;
            $scope.identity.identity_attachment = data.profile.identity_attachment;
            $scope.countries = data.countries;
            
            $scope.$watch('profile.region_l1_code',function(val){

                if(val != null){
                    var l1code = eval($scope.profile.region_l1_code);
                    if($scope.regions != undefined && $scope.regions[l1code] != undefined){
                        var level_two = $scope.regions[l1code];
                        $scope.business_region_l1 = $scope.profile.region_l1_code;
                        $scope.regions_level_2 = (level_two.childs !=null)? level_two.childs : null;
                    }
                    
                }
            });

            $scope.$watch('profile.region_l2_code',function(val){
                if(val != null){
                    var l2code = eval($scope.profile.region_l2_code);
                    if($scope.regions_level_2 != undefined){
                        var level_three = $scope.regions_level_2[l2code];
                        $scope.business_region_l2 = $scope.profile.region_l2_code;
                        $scope.regions_level_3 = (level_three !=null && level_three.childs !=null)? level_three.childs : null;
                    }
                }
                
                
            });

            $scope.$watch('profile.region_l3_code',function(val){
                if(val != null){
                    var l3code = eval($scope.profile.region_l3_code);
                    if($scope.regions_level_3 != undefined){
                        var level_four = $scope.regions_level_3[l3code];
                        $scope.business_region_l3 = $scope.profile.region_l3_code;
                        $scope.regions_level_4 = (level_four!=null && level_four.childs != null)? level_four.childs : null;
                    }
                    
                }
                
                
            });

            $scope.$watch('profile.region_l4_code',function(){
                $scope.business_region_l4 = $scope.profile.region_l4_code;
            });
        });
    };

    $scope.setBillingAddress = function(){
        if($scope.copy_profile){
            $scope.billing_address = {};
            $scope.billing_address.name = $scope.profile.lco_name;
            $scope.billing_address.email = $scope.profile.email;
            $scope.billing_address.address1 = $scope.profile.address1;
            $scope.billing_address.address2 = $scope.profile.address2;
            $scope.billing_address.contact = $scope.profile.contact;
            $scope.billing_address.billing_contact = $scope.profile.billing_contact;
            $scope.billing_address.country_id = $scope.profile.country_id;
            $scope.billing_address.division_id = $scope.profile.division_id;
            $scope.billing_address.district_id = $scope.profile.district_id;
            $scope.billing_address.area_id = $scope.profile.area_id;
            $scope.billing_address.sub_area_id = $scope.profile.sub_area_id;
            $scope.billing_address.road_id = $scope.profile.road_id;
            $scope.billing_address.token = $scope.token;
        } else{
           $scope.billing_address = {};
        }
       
   };

   /*$scope.saveBillingAddress = function(){

        $scope.billing_address.full_name = $scope.billing_address.name;
        $scope.billing_address.form_type = 1;
        var http = WebService.post('groups/save-billing-address',$scope.billing_address);
        http.then(function(response){
            var data = response.data;
           
            if(data.status == 400){
                $scope.warning_messages = data.warning_messages;
                $scope.success_messages = '';
            }else{
                
                $scope.warning_messages = '';
                $scope.success_messages = data.success_messages;
                if(data.billing_address_id != null)
                    $scope.billing_address_id = data.billing_address_id;
                $scope.loadNotification();
            }

            $("html,body").animate({scrollTop:'0px'});
        });
    };

    $scope.saveBusinessRegion = function(){
        resetMessage();

        var formData = {
            region_l1_code: $scope.business_region_l1,
            region_l2_code: $scope.business_region_l2,
            region_l3_code: $scope.business_region_l3,
            region_l4_code: $scope.business_region_l4,
            token: $scope.token,
            form_type:1
        };

        //console.log(formData);
        var http = WebService.post('lco/save_business_region',formData);
        http.then(function(response){
            var data = response.data;
            if(data.status == 400){
                $scope.warning_messages = data.warning_messages;
                $scope.success_messages = '';
            } else {
                $scope.success_messages = data.success_messages;
                $scope.warning_messages = '';
                $scope.loadNotification();

            }
        });
    };*/

    $scope.saveModality = function(){
         resetMessage();
         
         $scope.modality.token = $scope.profile.token;
         $scope.modality.business_modality = $scope.profile.business_modality;
         $scope.modality.form_type = 1;
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

    var loadBusinessRegion = function(){
        var http = WebService.get('lco/ajax_load_region');
        http.then(function(response){
            var data = response.data;
            $scope.regions = data;

        });
    };

    loadBusinessRegion();

    loadProfile();

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

    $scope.$watch('profile.country_id',function(val){
        if(val != null){
            loadLocations('divisions',$scope.profile);

            $scope.districts = [];
            $scope.areas = [];
            $scope.sub_areas = [];
            $scope.roads = [];
        }
    });

    $scope.$watch('profile.division_id',function(val){
        if(val != null){
            loadLocations('districts',$scope.profile);
            
            $scope.areas = [];
            $scope.sub_areas = [];
            $scope.roads = [];
        }
    });

    $scope.$watch('profile.district_id',function(val){
        if(val != null){
            loadLocations('areas',$scope.profile);
            
            $scope.sub_areas = [];
            $scope.roads = [];

        }
    });

    $scope.$watch('profile.area_id',function(val){
        if(val != null){
            loadLocations('sub_areas',$scope.profile);
            
            $scope.roads = [];
        }
    });

    $scope.$watch('profile.sub_area_id',function(val){
        if(val != null){
            loadLocations('roads',$scope.profile);
            
        }
    });

    if($scope.user_type == 'mso') {

        // billing address locations
        $scope.$watch('billing_address.country_id', function (val) {
            if (val != null) {

                loadLocations('divisions', $scope.billing_address);
                $scope.districts = [];
                $scope.areas = [];
                $scope.sub_areas = [];
                $scope.roads = [];
            }
        });

        $scope.$watch('billing_address.division_id', function (val) {
            if (val != null) {

                loadLocations('districts', $scope.billing_address);
                $scope.areas = [];
                $scope.sub_areas = [];
                $scope.roads = [];
            }
        });

        $scope.$watch('billing_address.district_id', function (val) {
            if (val != null) {
                loadLocations('areas', $scope.billing_address);
                $scope.sub_areas = [];
                $scope.roads = [];
            }
        });

        $scope.$watch('billing_address.area_id', function (val) {
            if (val != null) {
                loadLocations('sub_areas', $scope.billing_address);
                $scope.roads = [];
            }
        });

        $scope.$watch('billing_address.sub_area_id', function (val) {
            if (val != null) {
                loadLocations('roads', $scope.billing_address);

            }
        });

        $scope.changeCountry = function (type) {
            if (type == 'profile') {
                $scope.profile.division_id = undefined;
                $scope.profile.district_id = undefined;
                $scope.profile.area_id = undefined;
                $scope.profile.sub_area_id = undefined;
                $scope.profile.road_id = undefined;
            } else {
                $scope.billing_address.division_id = undefined;
                $scope.billing_address.district_id = undefined;
                $scope.billing_address.area_id = undefined;
                $scope.billing_address.sub_area_id = undefined;
                $scope.billing_address.road_id = undefined;
            }

            $scope.divisions = [];
            $scope.districts = [];
            $scope.areas = [];
            $scope.sub_areas = [];
            $scope.roads = [];
        };
    }

    $scope.changeDivision = function(type){
        if(type == 'profile') {
            /*$scope.profile.district_id = undefined;
            $scope.profile.area_id     = undefined;
            $scope.profile.sub_area_id = undefined;
            $scope.profile.road_id     = undefined;*/
        } else {
            $scope.billing_address.district_id = undefined;
            $scope.billing_address.area_id     = undefined;
            $scope.billing_address.sub_area_id = undefined;
            $scope.billing_address.road_id     = undefined;
        }
        
        $scope.districts = [];
        $scope.areas = [];
        $scope.sub_areas = [];
        $scope.roads = [];
    };

    $scope.changeDistrict = function(type){
        if(type == 'profile') {
           /* $scope.profile.area_id     = undefined;
            $scope.profile.sub_area_id = undefined;
            $scope.profile.road_id     = undefined;*/
        } else {
            $scope.billing_address.area_id     = undefined;
            $scope.billing_address.sub_area_id = undefined;
            $scope.billing_address.road_id     = undefined;
        }
        $scope.areas = [];
        $scope.sub_areas = [];
        $scope.roads = [];
    };

    $scope.changeArea = function(type){
        if(type == 'profile') {
            /*$scope.profile.sub_area_id = undefined;
            $scope.profile.road_id     = undefined;*/
        } else {
            $scope.billing_address.sub_area_id = undefined;
            $scope.billing_address.road_id     = undefined;
        }
        
        $scope.sub_areas = [];
        $scope.roads = [];
    };

    $scope.changeSubArea = function(type){
        if(type == 'profile') {
           /* $scope.profile.road_id     = undefined;*/
        } else {
            $scope.billing_address.road_id     = undefined;
        }
        $scope.roads = [];
    };

    $scope.changeBillingContact = function(){
        if($scope.profile.is_same_as_contact=='1'){
            $scope.profile.billing_contact = $scope.profile.contact;
            $scope.billing_address.billing_contact = $scope.billing_address.contact;
        } else{
            $scope.profile.billing_contact = null;
            $scope.billing_address.billing_contact = null;
        }
    };


    var validateForm = function(){
        $scope.$watch('profile.contact',function(val){
            var pattern = /[0-9]/
            if(val != null && val != "" && val != undefined)
            {
                if(!val.match(pattern)){
                    $scope.warning_messages = 'Contact Number should be numerice value';
                    $scope.profile.contact = '';
                    $("html,body").animate({scrollTop:'0px'});
                }
            }


        });

        $scope.$watch('profile.billing_contact',function(val){
            var pattern = /[0-9]/
            if(val != null && val != "" && val != undefined)
            {
                if(!val.match(pattern)){
                    $scope.warning_messages = '2nd Contact Number should be numerice value';
                    $scope.profile.billing_contact = '';
                    $("html,body").animate({scrollTop:'0px'});
                }
            }

        });

    };
    
    
    validateForm();
    
    



}]);