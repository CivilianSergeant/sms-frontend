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
app.controller('CreateMSOProfile',['$scope','WebService','FileUploader',function($scope,WebService,FileUploader){

	$scope.items = [];
    $scope.roles = [];
	$scope.tabs = {profile:1,login:0,contract:0,photo:0,identity_verify:0};
	$scope.profile = {id:'',full_name:'',username:'',email:'',password:'',re_password:''};
	$scope.showFrm = 0;
	$scope.phones = [{number:''}];
	$scope.identity_verify_types = ['Nation ID','Passport','Utility Document'];
	$scope.token = null;
    $scope.identity = {};
    $scope.notStrongPass = 0;
    $scope.check_re_password =0;
    $scope.fileUploadPhotoProgress = 0;
    $scope.fileUploadIdentityProgress = 0;

    $scope.addPhoneItem = function(){
      $scope.phones.push({number:''});
  };

  $scope.closeAlert = function(){
      resetMessage();
  };

  var uploader = $scope.uploader = new FileUploader({
      headers: {'X-Requested-With':'XMLHttpRequest'},
      url: SITE_URL+'mso/upload_photo'
  });

  uploader.onBeforeUploadItem = function(item) {
      uploader.progress = 0;
      $scope.fileUploadPhotoProgress = 0;
      item.formData.push({token:$scope.token,form_type:0});
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
    url: SITE_URL+'mso/upload_identity'
});

identityUploader.onBeforeUploadItem = function(item) {
    identityUploader.progress = 0;
    $scope.fileUploadIdentityProgress = 0;
    item.formData.push($scope.identity);
    item.formData.push({token:$scope.token,form_type:0});
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
        $scope.loadNotification();
    }else{
        identityUploader.progress = 0;
        $scope.fileUploadIdentityProgress = 0;
        $scope.uploadView = false;
    }
};

$scope.hideForm = function(){
  $scope.showFrm = 0;
  $scope.profile = {id:'',full_name:'',username:'',email:'',password:'',re_password:''};
  $scope.identity = {};
}

$scope.removePhoneItem = function(i){
  if (i != 0) {
   $scope.phones.splice(i,1);
}
};

var resetMessage = function()
{
    $scope.success_messages = '';
    $scope.warning_messages = '';
    $scope.error_messages = '';
}

$scope.setTab = function(tab){

    switch(tab){
       case 'profile':
            $scope.tabs = {profile:1,login:0,contract:0,photo:0,identity_verify:0};
            break;
       case 'login':
           if($scope.token != null){
               $scope.tabs = {profile:0,login:1,contract:0,photo:0,identity_verify:0};
           } else {
               $scope.warning_messages = 'You have to create profile before add login Info';
               $("html,body").animate({scrollTop:'0px'});
           }
        break;
    case 'contract':
        if($scope.token != null){
            $scope.tabs = {profile:0,login:0,contract:1,photo:0,identity_verify:0};
        } else {
            $scope.warning_messages = 'You have to create profile before add contact';
            $("html,body").animate({scrollTop:'0px'});
        }

        break;
    case 'photo':
        if($scope.token != null) {
            $scope.tabs = {profile:0,login:0,contract:0,photo:1,identity_verify:0};
        } else {
            $scope.warning_messages = 'You have to create profile before attach photo';
            $("html,body").animate({scrollTop:'0px'});
        }

        break;
    case 'identity_verify':
        if($scope.token != null) {
            $scope.tabs = {profile:0,login:0,contract:0,photo:0,identity_verify:1};
        } else {
            $scope.warning_messages = 'You have to create profile before attach Identity verification';
            $("html,body").animate({scrollTop:'0px'});
        }

        break;
    }
};
var loadPermissions = function(){
    var http = WebService.get('mso/ajax_get_permissions');
    http.then(function(response){
        var data = response.data;
        if(data.status == 200){
            $scope.permissions=data.permissions;
        }
    });
};

loadPermissions();
$scope.showForm = function()
{
  $scope.showFrm = 1;
  $scope.success_messages = $scope.warning_messages = $scope.error_messages = '';
}

$scope.saveLogin = function(){
    resetMessage();
    if($scope.notStrongPass || $scope.check_re_password){
        return;
    }

    if($scope.profile.username=='' || $scope.profile.username == undefined){
        $scope.warning_messages = 'Username cannot be blank';
        return;
    }

    var formData = {
        token:$scope.token,
        username:$scope.profile.username,
        password:$scope.profile.password,
        re_password:$scope.profile.re_password,
        form_type:0,
        role_id:$scope.profile.role_id
    };

    var http = WebService.post('mso/create_login_info',formData);
    http.then(function(response){
       var data = response.data;
       if(data.status == 400){
        $scope.warning_messages = data.warning_messages;
        $scope.success_messages = '';
    } else {
            $scope.success_messages = data.success_messages;
            $scope.warning_messages = '';
            loadProfiles();
            if($scope.token != null){
                $scope.setTab('photo');
                $scope.loadNotification();
            }
    }
    $("html,body").animate({scrollTop:'0px'});
});
};

$scope.saveProfile = function(){
    $scope.profile.form_type=0;
  var http = WebService.post('mso/create_profile',$scope.profile);
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
    $scope.loadNotification();

    loadProfiles();
    if($scope.token != null){
     $scope.setTab('login');
 }
}
$("html,body").animate({"scrollTop":"0px"});
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
    var http = WebService.get('mso/ajax_load_profiles');
    http.then(function(response){
       var data = response.data;
       if(data.status == 200){
        $scope.items = data.profiles;
        $scope.roles = data.roles;
        generateKendoGird($scope.items);
        
    }
});
};

var generateKendoGird = function(data){
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
                    url: "mso/ajax_load_profiles", 
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
            {field: "mso_name", title: "Name",width: "auto"},
            {field: "username", title: "Username",width: "auto"},
            {field: "email", title: "E-mail",width: "auto"},
            {field: "status", title: "Status",filterable:false,template: '# if(data.user_status==1) {# <span class="label label-success">Active</span> #} else {# <span class="label label-danger">Inactive</span> #}#'},
            {field: "", title: "Action",width: "auto",filterable:false,template:"<a href='"+SITE_URL+"mso/view/#=data.token#' class='btn btn-default btn-xs' data-toggle='tooltip' data-placement='left' title='View'><i class='fa fa-search'></i></a> <a ng-if='permissions.edit_permission == \"1\"' href='mso/edit/#=data.token#' class='btn btn-default btn-xs tool1' data-toggle='tooltip' data-placement='left' title='Edit'><i class='fa fa-pencil'></i></a>"},
            ]
        };
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
            
            $scope.profile.billing_contact = $scope.profile.contact;
        });

        $scope.$watch('profile.billing_contact',function(val){
            var pattern = /[0-9]/
            if(val != null)
            {
                if(!val.match(pattern)){
                    $scope.warning_messages = 'Contact Number should be numerice value';
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
    
    

    loadProfiles();

    $scope.checkPassWordStrength = function(){
        var password = ($scope.profile.password);
        //var strongRegex = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[@#])(?=.{8,})");
        var mediumRegex = new RegExp("^(?=.{8,})");
        if(!mediumRegex.test(password)){
            $scope.notStrongPass = 1;
            $scope.pass_message = 'Password should be at least 8 characters long';
        }else{
            $scope.notStrongPass = 0;
            $scope.pass_message = '';
        }
    };

    $scope.checkRePassword = function(){
        if($scope.profile.password != $scope.profile.re_password){
            $scope.check_re_password = 1;
            $scope.re_pass_message = 'Re-password not matched';
        }else{
            $scope.check_re_password = 0;
            $scope.re_pass_message = '';
        }
    }

    $scope.isSaveLoginDisabled = function(){
        if($scope.notStrongPass){
            return true;
        }
        if($scope.check_re_password){
            return true;
        }
        return false;
    }

}]);

app.controller('EditMSOProfile',['$scope','WebService','FileUploader',function($scope,WebService,FileUploader){

    $scope.roles = [];
    $scope.profile = {id:'',mso_name:'',email:'',password:'',re_password:''};
    $scope.tabs = {profile:1,contract:0,photo:0,identity_verify:0};
    $scope.countries = $scope.divisions = $scope.districts = $scope.areas = $scope.sub_areas = $scope.roads = [];
    $scope.identity_verify_types = ['Nation ID','Passport','Utility Document'];
    $scope.token = token;
    $scope.identity = {};
    $scope.loader = 0;
    $scope.notStrongPass = 0;
    $scope.check_re_password = 0;
    $scope.pass_message = '';
    $scope.re_pass_message = '';
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
            $scope.notStrongPass = 1;
            $scope.pass_message = 'Password should be at least 8 characters long';
        }else{
            $scope.notStrongPass = 0;
            $scope.pass_message = '';
        }
    };

    $scope.checkRePassword = function(){
        if($scope.profile.password != $scope.profile.re_password){
            $scope.check_re_password = 1;
            $scope.re_pass_message = 'Re-password not matched';
        }else{
            $scope.check_re_password = 0;
            $scope.re_pass_message = '';
        }
    };

    $scope.isSaveLoginDisabled = function(){
        if($scope.notStrongPass){
            return true;
        }
        if($scope.check_re_password){
            return true;
        }
        return false;
    };

    $scope.closeAlert = function(){
       resetMessage();
   };

    var uploader = $scope.uploader = new FileUploader({
        headers: {'X-Requested-With':'XMLHttpRequest'},
        url: SITE_URL+'mso/upload_photo'
    });

   uploader.onBeforeUploadItem = function(item) {
       uploader.progress = 0;
       $scope.fileUploadPhotoProgress = 0;
       item.formData.push({token:$scope.token,form_type:1});
   };

    uploader.onProgressItem = function(fileItem, progress) {

        $scope.fileUploadPhotoProgress = progress;
    };

    uploader.onAfterAddingFile = function(fileItem) {
        uploader.progress = 0;
        $scope.fileUploadPhotoProgress = 0;

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
        url: SITE_URL+'mso/upload_identity'
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

        $scope.fileUploadIdentityProgress = progress;
    };

    identityUploader.onSuccessItem = function(fileItem, response, status, headers) {
        if (response.status == 200){
            $scope.identity.identity_attachment = response.image;
            $scope.success_messages = response.success_messages;
            $scope.warning_messages = '';
            $scope.loadNotification();
        }else{
            identityUploader.progress = 0;
            $scope.fileUploadIdentityProgress =0;
            $scope.warning_messages = response.warning_messages;
            $scope.success_messages = '';
            $scope.uploadView = false;
        }
    };

$scope.setTab = function(tab){
    resetMessage();
    switch(tab){
        case 'profile':
        $scope.tabs = {profile:1,login:0,contract:0,photo:0,identity_verify:0};
        break;
        case 'login':
        
        $scope.tabs = {profile:0,login:1,contract:0,photo:0,identity_verify:0};
        break;
        case 'contract':
        
        $scope.tabs = {profile:0,login:0,contract:1,photo:0,identity_verify:0};
        break;
        case 'photo':
        
        $scope.tabs = {profile:0,login:0,contract:0,photo:1,identity_verify:0};
        break;
        case 'identity_verify':
        
        $scope.tabs = {profile:0,login:0,contract:0,photo:0,identity_verify:1};
        break;
    }
};

$scope.updateProfile = function(){
    $scope.profile.form_type=1;
    var http = WebService.post('mso/update_profile',$scope.profile);
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
    if($scope.notStrongPass || $scope.check_re_password){
        return;
    }

    if($scope.profile.username=='' || $scope.profile.username == undefined){
        $scope.warning_messages = 'Username cannot be blank';
        return;
    }

    var formData = {
        token:$scope.token,
        username:$scope.profile.username,
        password:$scope.profile.password,
        re_password:$scope.profile.re_password,
        form_type:1,
        role_id:$scope.profile.role_id
    };
    var http = WebService.post('mso/update_login_info',formData);
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
        var http = WebService.get('mso/ajax_get_profile/'+$scope.token);
        http.then(function(response){
            var data = response.data;
            $scope.profile = data.profile;
            $scope.roles = data.roles;
            $scope.profile.password = '';
            $scope.identity.type = data.profile.identity_type;
            $scope.identity.id   = data.profile.identity_number;
            $scope.identity.identity_attachment = data.profile.identity_attachment;
            $scope.countries = data.countries;
        });
    };

    loadProfile();

    // load locations
    var loadLocations = function(type){
        var formData = {};

        if(type == 'divisions'){
            formData.country_id = $scope.profile.country_id;
        }else if(type == 'districts'){
            formData.division_id = $scope.profile.division_id;
        }else if(type == 'areas'){
            formData.district_id = $scope.profile.district_id;
        }else if(type == 'sub_areas'){
            formData.area_id = $scope.profile.area_id;
        }else if(type == 'roads'){
            formData.sub_area_id = $scope.profile.sub_area_id;
        }

        var http = WebService.post('mso/location/ajax_get_request/'+type,formData);
        http.then(function(response){
            var data = response.data;
            if(type == 'divisions'){
                $scope.divisions = data;
                $("#division_id").removeAttr('disabled');
            }else if(type == 'districts'){
                $scope.districts = data;
                $("#district_id").removeAttr('disabled');
            }else if(type == 'areas'){
                $scope.areas = data;
                $("#area_id").removeAttr('disabled');
            }else if(type == 'sub_areas'){
                $scope.sub_areas = data;
                $("#sub_area_id").removeAttr('disabled');
            }else if(type == 'roads'){
                $scope.roads = data;
                $("#road_id").removeAttr('disabled');
            }
            
        });
    }

    $scope.$watch('profile.country_id',function(val){
        if(val != null){
            loadLocations('divisions');
        }
    });

    $scope.$watch('profile.division_id',function(val){
        if(val != null){
            loadLocations('districts');
        }
    });

    $scope.$watch('profile.district_id',function(val){
        if(val != null){
            loadLocations('areas');
        }
    });

    $scope.$watch('profile.area_id',function(val){
        if(val != null){
            loadLocations('sub_areas');
        }
    });

    $scope.$watch('profile.sub_area_id',function(val){
        if(val != null){
            loadLocations('roads');
        }
    });

    // validate Form
    var validateForm = function(){
        $scope.$watch('profile.contact',function(val){
            var pattern = /[0-9]/
            if(val != null)
            {
                if(!val.match(pattern)){
                    $scope.warning_messages = 'Contact Number should be numerice value';
                    $scope.profile.contact = '';
                    $("html,body").animate({scrollTop:"0px"});
                }
            }
            
            $scope.profile.billing_contact = $scope.profile.contact;
        });

        $scope.$watch('profile.billing_contact',function(val){
            var pattern = /[0-9]/
            if(val != null)
            {
                if(!val.match(pattern)){
                    $scope.warning_messages = 'Contact Number should be numerice value';
                    $scope.profile.billing_contact = '';
                    $("html,body").animate({scrollTop:"0px"});
                }
            }

        });

    };

    
    
    validateForm();
    
    



}]);

// jqery scripts for mso profile
$(function () {

		/*$('#example')
            .removeClass('display')
            .addClass('table table-striped table-bordered');*/


            $("#country_id").change(function () {
                var obj = $(this);

                $.ajax({
                    url: BASE_URL+"mso/location/ajax_get_request/divisions",
                    method: "POST",
                    data: {country_id: obj.val()},
                    beforeSend: function () {
                        $("#division_id").after('<span style="float: right;position: relative;top: -32px;right: -45px;" id="loader"><img src="' + BASE_URL + 'public/theme/katniss/img/loading_32.gif"/>');
                    },
                    success: function (e)
                    {
                        $("#loader").remove();
                        var data = $.parseJSON(e);
                        var divisionsOption = '<option value="">---Select Division---</option>';
                        $.each(data, function (i, el) {
                            divisionsOption += '<option value="' + data[i].id + '">' + data[i].division_name + '</option>';
                        });
                        $("#division_box").removeClass('hidden');
                        $("#division_id").html(divisionsOption).removeAttr('disabled');
                    }
                });
});

$("#division_id").change(function () {
    var obj = $(this);

    $.ajax({
        url: BASE_URL+"mso/location/ajax_get_request/districts",
        method: "POST",
        data: {division_id: obj.val()},
        beforeSend: function () {
            $("#district_id").after('<span style="float: right;position: relative;top: -32px;right: -45px;" id="loader"><img src="' + BASE_URL + 'public/theme/katniss/img/loading_32.gif"/>');
        },
        success: function (e)
        {
            $("#loader").remove();
            var data = $.parseJSON(e);
            var districtOption = '<option value="">---Select District---</option>';
            $.each(data, function (i, el) {
                districtOption += '<option value="' + data[i].id + '">' + data[i].district_name + '</option>';
            });
            $("#district_box").removeClass('hidden');
            $("#district_id").html(districtOption).removeAttr('disabled');
        }
    });
});

$("#district_id").change(function () {
    var obj = $(this);

    $.ajax({
        url: BASE_URL+"mso/location/ajax_get_request/areas",
        method: "POST",
        data: {district_id: obj.val()},
        beforeSend: function () {
            $("#area_id").after('<span style="float: right;position: relative;top: -32px;right: -45px;" id="loader"><img src="' + BASE_URL + 'public/theme/katniss/img/loading_32.gif"/>');
        },
        success: function (e)
        {
            $("#loader").remove();
            var data = $.parseJSON(e);
            var areaOption = '<option value="">---Select Area---</option>';
            $.each(data, function (i, el) {
                areaOption += '<option value="' + data[i].id + '">' + data[i].area_name + '</option>';
            });
            $("#area_box").removeClass('hidden');
            $("#area_id").html(areaOption).removeAttr('disabled');
        }
    });
});

$("#area_id").change(function () {
    var obj = $(this);

    $.ajax({
        url: BASE_URL+"mso/location/ajax_get_request/sub_areas",
        method: "POST",
        data: {area_id: obj.val()},
        beforeSend: function () {
            $("#sub_area_id").after('<span style="float: right;position: relative;top: -32px;right: -45px;" id="loader"><img src="' + BASE_URL + 'public/theme/katniss/img/loading_32.gif"/>');
        },
        success: function (e)
        {
            $("#loader").remove();
            var data = $.parseJSON(e);
            console.log(data.length);
            var subAreaOption = '<option value="">---Select Sub Area---</option>';
            $.each(data, function (i, el) {
                subAreaOption += '<option value="' + data[i].id + '">' + data[i].sub_area_name + '</option>';
            });
            $("#sub_area_box").removeClass('hidden');
            $("#sub_area_id").html(subAreaOption).removeAttr('disabled');
        }
    });
});


$("#sub_area_id").change(function () {
    var obj = $(this);

    $.ajax({
        url: BASE_URL+"mso/location/ajax_get_request/roads",
        method: "POST",
        data: {sub_area_id: obj.val()},
        beforeSend: function () {
            $("#sub_area_id").after('<span style="float: right;position: relative;top: -32px;right: -45px;" id="loader"><img src="' + BASE_URL + 'public/theme/katniss/img/loading_32.gif"/>');
        },
        success: function (e)
        {
            $("#loader").remove();
            var data = $.parseJSON(e);

            var roadOption = '<option value="">---Select Sub area---</option>';
            $.each(data, function (i, el) {
                roadOption += '<option value="' + data[i].id + '">' + data[i].road_name + '</option>';
            });
            
            $("#road_id").html(roadOption).removeAttr('disabled');
        }
    });
});

$("#sub_sub_area_id").change(function () {
    var obj = $(this);
    $("#road_box").removeClass('hidden');
    $("input[name=road_name]").removeClass('hidden');
});



});