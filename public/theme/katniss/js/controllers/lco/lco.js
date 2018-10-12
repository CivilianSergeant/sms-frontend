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
app.controller('CreateLCOProfile',['$scope','WebService','FileUploader',function($scope,WebService,FileUploader){

    $scope.items = [];
    $scope.roles = [];
    $scope.permissions = [];
    $scope.tabs = {profile:1,login:0,billing_address:0,business_region:0,photo:0,identity_verify:0,business_modality:0};
    $scope.profile = {id:'',full_name:'',username:'',email:'',password:'',re_password:''};
    $scope.billing_address = {id:'',full_name:'',email:''};
    $scope.showFrm = 0;
    $scope.phones = [{number:''}];
    $scope.identity_verify_types = ['Nation ID','Passport','Utility Document'];
    $scope.token = null;
    $scope.billing_address_id =  null;
    $scope.identity = {};
    $scope.notStrongPass = 0;
    $scope.check_re_password =0;
    $scope.role_type = null;
    $scope.user_type = null;
    $scope.fileUploadPhotoProgress = 0;
    $scope.fileUploadIdentityProgress = 0;



    $scope.modality = {id:'',business_modality:''}
    
    var resetMessage = function()
    {
        $scope.success_messages = '';
        $scope.warning_messages = '';
        $scope.error_messages = '';
    }

    $scope.addPhoneItem = function(){
        $scope.phones.push({number:''});
    };

    $scope.closeAlert = function(){
        resetMessage();
    };

    var uploader = $scope.uploader = new FileUploader({
        headers: {'X-Requested-With':'XMLHttpRequest'},
        url: SITE_URL+'lco/upload_photo'
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
        url: SITE_URL+'lco/upload_identity'
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

    $scope.hideForm = function(){

        if($scope.role_type == "admin" && $scope.user_type != "lco"){

            if($scope.token != null && $scope.billing_address_id == null){
                $scope.warning_messages = 'Please save billilng address before close.';
                return;
            }

//            if($scope.token != null &&
//                ($scope.profile.region_l1_code == null && $scope.profile.region_l2_code == null
//                    && $scope.profile.region_l3_code == null && $scope.profile.region_l4_code == null)){
//                $scope.warning_messages = 'Please save business region before close.';
//                return;
//            }

        }

        $scope.setTab('profile');
        $scope.showFrm = 0;
        $scope.profile = {id:'',full_name:'',username:'',email:'',password:'',re_password:''};
        $scope.billing_address = {id:'',full_name:'',email:''};
        $scope.identity = {};
        $scope.success_messages = $scope.warning_messages = $scope.error_messages = '';
    }

    $scope.removePhoneItem = function(i){
        if (i != 0) {
            $scope.phones.splice(i,1);
        }
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
    }

    $scope.isSaveLoginDisabled = function(){
        if($scope.notStrongPassFlag){
            return true;
        }
        if($scope.checkRePasswordFlag){
            return true;
        }
        return false;
    }

    

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


   $scope.showForm = function()
   {
       if($scope.permissions.create_permission=='1'){
           $scope.showFrm = 1;
           $scope.success_messages = $scope.warning_messages = $scope.error_messages = '';
       }else{
           $scope.warning_messages = "Sorry! You don't have permission to create LCO Admin Profile";
       }

    }

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
        role_id:$scope.profile.role_id,
        lsp_type_id:$scope.profile.lsp_type_id
    };
    if($scope.token != null || $scope.token != undefined){
        var http = WebService.post('lco/update_login_info',formData);
    }else{
        var http = WebService.post('lco/create_login_info',formData);
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
            if($scope.token != null && ($scope.user_type=='mso')){
                $scope.setTab('billing_address');
            }
        }
        $("html,body").animate({scrollTop:'0px'});
    });
};

$scope.saveProfile = function(){
    resetMessage();
    $scope.profile.form_type = 0;
    var http = WebService.post('lco/create_profile',$scope.profile);
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
            $scope.setBillingAddress();
            $scope.loadNotification();
            if($scope.token != null){
                $scope.setTab('login');
            }
        }
        $("html,body").animate({scrollTop:'0px'});
    });
};

$scope.saveModality = function(){
    resetMessage();
    $scope.modality.token = $scope.token;
    $scope.modality.form_type = 0;
    var http = WebService.post('lco/update_modality',$scope.modality);
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


var loadProfiles = function(){
    $scope.$watch('items',function(val){
        if(val.length){
            $scope.loader = 0;
        } else {
            $scope.loader = 1;
        }
    });
    var http = WebService.get('lco/ajax_load_profiles');
    http.then(function(response){
        var data = response.data;
        if(data.status == 200){
            $scope.user_type = data.user_type;
            $scope.role_type = data.role_type;
            $scope.items = data.profiles;
            $scope.roles = data.roles;
            $scope.countries = data.countries;
            $scope.lsp_types = data.lsp_types;
            generateKendoGird($scope.items);
            


        }
    });
};

var generateKendoGird = function(data){
    $scope.mainGridOptions = {
        dataSource: {
                transport: {
                    read: {
                        url: "lco/ajax_load_profiles", 
                        dataType: "json",
                    }
                },
                schema: {
                    data: "profiles",
                    total: "total"
                },

                pageSize: 10,
                serverPaging: true,
                serverSorting:true,
                serverFiltering: true,          
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
            {field: "lco_name", title: "Name",width: "auto"},
                {field: "username", title: "Username",width: "auto"},
                {field: "email", title: "E-mail",width: "auto"},
//                {field: "business_region_assigned", title:"Business Region",width:"auto",filterable:false,template: '# if(data.business_region_assigned==1) {# <span class="label label-success">Assigned</span> #} else {# <span class="label label-danger">Not Assigned</span> #}#'},
                {field: "status", title: "Status",filterable:false,sortable:false,template: '# if(data.user_status==1) {# <span class="label label-success">Active</span> #} else {# <span class="label label-danger">Inactive</span> #}#'},
                {field: "", title: "Action",width: "auto",template:"<a href='"+SITE_URL+"lco/view/#=data.token#' class='btn btn-default btn-xs' data-toggle='tooltip' data-placement='left' title='View'><i class='fa fa-search'></i></a> <a ng-if='permissions.edit_permission==\"1\"' href='"+SITE_URL+"lco/edit/#=data.token#' class='btn btn-default btn-xs' data-toggle='tooltip' data-placement='left' title='Edit'><i class='fa fa-pencil'></i></a>"},
            ]
        };
        if($scope.role_type == "admin" && $scope.user_type == "lco"){
            $scope.mainGridOptions.columns.splice(3,1);
        }

        if($scope.user_type == 'group'){
            var action = {field: "", title: "Action",width: "auto",template:"<a href='"+SITE_URL+"lco/view/#=data.token#' class='btn btn-default btn-xs' data-toggle='tooltip' data-placement='left' title='View'><i class='fa fa-search'></i></a>"};
            $scope.mainGridOptions.columns.splice(5,1);
            $scope.mainGridOptions.columns.push(action);
        }

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



    $scope.setBillingAddress = function(){
        if($scope.copy_profile){
            $scope.billing_address.full_name = $scope.profile.full_name;
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

   $scope.saveBillingAddress = function(){
        
        var http = WebService.post('lco/save_billing_address',$scope.billing_address);
        http.then(function(response){
            var data = response.data;
            if(data.status == 400){
                $scope.warning_messages = data.warning_messages;
                $scope.success_messages = '';
            }else{
                
                $scope.warning_messages = '';
                $scope.success_messages = data.success_messages;
                $scope.billing_address_id = data.billing_address_id;
                    //$scope.setTab('photo');
                }
                $scope.loadNotification();
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
            token: $scope.token
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
    };



var validateForm = function(){
    $scope.$watch('profile.contact',function(val){
        var pattern = /[0-9]/
        if(val != null)
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
        if(val != null)
        {
            if(!val.match(pattern)){
                $scope.warning_messages = 'Billing Contact Number should be numerice value';
                $scope.profile.billing_contact = '';
                $("html,body").animate({scrollTop:'0px'});
            }
        }

    });

};

$scope.$watch('showFrm',function(val){
    if(val)
    {
        validateForm();
    }
});

    var loadBusinessRegion = function(){
        var http = WebService.get('lco/ajax_load_region');
        http.then(function(response){
            var data = response.data;
            $scope.regions = data;

        });
    };

    var loadPermissions = function(){
        var http = WebService.get('lco/ajax_get_permissions');
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.permissions=data.permissions;
            }
        });
    };

    loadPermissions();

    //loadBusinessRegion();
    loadProfiles();


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

        var http = WebService.post('lco/location/ajax_get_request/'+type,formData);
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

    // billing location
    $scope.$watch('billing_address.country_id',function(val){
        if(val != null){
            loadLocations('divisions',$scope.billing_address);
            
            $scope.districts = [];
            $scope.areas = [];
            $scope.sub_areas = [];
            $scope.roads = [];
        }
    });

    $scope.$watch('billing_address.division_id',function(val){
        if(val != null){
            loadLocations('districts',$scope.billing_address);
            
            $scope.areas = [];
            $scope.sub_areas = [];
            $scope.roads = [];
        }
    });

    $scope.$watch('billing_address.district_id',function(val){
        if(val != null){
            loadLocations('areas',$scope.billing_address);
            
            $scope.sub_areas = [];
            $scope.roads = [];

        }
    });

    $scope.$watch('billing_address.area_id',function(val){
        if(val != null){
            loadLocations('sub_areas',$scope.billing_address);
            
            $scope.roads = [];
        }
    });

    $scope.$watch('billing_address.sub_area_id',function(val){
        if(val != null){
            loadLocations('roads',$scope.billing_address);
            
        }
    });


    $scope.changeCountry = function(){
        $scope.billing_address.division_id = undefined;
        $scope.billing_address.district_id = undefined;
        $scope.billing_address.area_id     = undefined;
        $scope.billing_address.sub_area_id = undefined;
        $scope.billing_address.road_id     = undefined;
        $scope.divisions = [];
        $scope.districts = [];
        $scope.areas = [];
        $scope.sub_areas = [];
        $scope.roads = [];
    };

    $scope.changeDivision = function(){
        $scope.billing_address.district_id = undefined;
        $scope.billing_address.area_id     = undefined;
        $scope.billing_address.sub_area_id = undefined;
        $scope.billing_address.road_id     = undefined;
        $scope.districts = [];
        $scope.areas = [];
        $scope.sub_areas = [];
        $scope.roads = [];
    };

    $scope.changeDistrict = function(){
        $scope.billing_address.area_id     = undefined;
        $scope.billing_address.sub_area_id = undefined;
        $scope.billing_address.road_id     = undefined;
        $scope.areas = [];
        $scope.sub_areas = [];
        $scope.roads = [];
    };

    $scope.changeArea = function(){
        $scope.billing_address.sub_area_id = undefined;
        $scope.billing_address.road_id     = undefined;
        $scope.sub_areas = [];
        $scope.roads = [];
    };

    $scope.changeSubArea = function(){
        $scope.billing_address.road_id     = undefined;
        $scope.roads = [];
    };


}]);



