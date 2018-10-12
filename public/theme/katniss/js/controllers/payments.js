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
app.controller('CreatePayments',['$scope','WebService','FileUploader',function($scope,WebService,FileUploader){

	$scope.items = [];
	$scope.tabs = {cash:1,bank:0,pos:0,bkash:0};
	$scope.profile = {id:'',full_name:'',username:'',email:'',password:'',re_password:''};
	$scope.showFrm = 0;
	$scope.phones = [{number:''}];
	$scope.identity_verify_types = ['Nation ID','Passport','Utility Document'];
	$scope.token = null;
    $scope.identity = {};

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
    item.formData.push({token:$scope.token});
};

uploader.onSuccessItem = function(fileItem, response, status, headers) {
    if (response.status == 200){
        $scope.uploadView = false;
        $scope.profile.photo = response.image;
        $scope.success_messages = response.success_messages;
    }else{
        $scope.warning_messages = response.warning_messages;
        $scope.uploadView = false;
    }
};

var identityUploader = $scope.identityUploader = new FileUploader({
    headers: {'X-Requested-With':'XMLHttpRequest'},
    url: SITE_URL+'mso/upload_identity'
});

identityUploader.onBeforeUploadItem = function(item) {
    item.formData.push($scope.identity);
    item.formData.push({token:$scope.token});
};

identityUploader.onSuccessItem = function(fileItem, response, status, headers) {
    if (response.status == 200){
        $scope.identity.identity_attachment = response.image;
        $scope.success_messages = response.success_messages;
    }else{
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
       case 'cash':
       $scope.tabs = {cash:1,bank:0,pos:0,bkash:0};
       break;
       case 'bank':
       $scope.tabs = {cash:0,bank:1,pos:0,bkash:0};
       break;
       case 'pos':
       $scope.tabs = {cash:0,bank:0,pos:1,bkash:0};
       break;
       case 'bkash':
       $scope.tabs = {cash:0,bank:0,pos:0,bkash:1};
       break;

    }
};

$scope.showForm = function()
{
  $scope.showFrm = 1;
  $scope.success_messages = $scope.warning_messages = $scope.error_messages = '';
}

$scope.saveLogin = function(){
    resetMessage();

    var formData = {
        token:$scope.token,
        username:$scope.profile.username,
        password:$scope.profile.password,
        re_password:$scope.profile.re_password
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
     }
 }
 $("html,body").animate({scrollTop:'0px'});
});
};

$scope.saveProfile = function(){
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
    loadProfiles();
    if($scope.token != null){
     $scope.setTab('login');
 }
}
$("html,body").animate({"scrollTop":"0px"});
});
};


var loadProfiles = function(){
  var http = WebService.get('mso/ajax_load_profiles');
  http.then(function(response){
   var data = response.data;
   if(data.status == 200){
    $scope.items = data.profiles;
    generateKendoGird($scope.items);

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

}]);
app.controller('EditMSOProfile',['$scope','WebService','FileUploader',function($scope,WebService,FileUploader){

    $scope.profile = {id:'',mso_name:'',email:'',password:'',re_password:''};
    $scope.tabs = {profile:1,contract:0,photo:0,identity_verify:0};
    $scope.countries = $scope.divisions = $scope.districts = $scope.areas = $scope.sub_areas = $scope.roads = [];
    $scope.identity_verify_types = ['Nation ID','Passport','Utility Document'];
    $scope.token = token;
    $scope.identity = {};
    $scope.loader = 0;

    // show hide loader base on profile data loaded or not
    $scope.$watch('profile',function(val){

        if(val.id != ""){
            $scope.loader = 0;
            
        } else {

            $scope.loader = 1;
        }

    });

    $scope.closeAlert = function(){
       resetMessage();
   };

   var uploader = $scope.uploader = new FileUploader({
    headers: {'X-Requested-With':'XMLHttpRequest'},
    url: SITE_URL+'mso/upload_photo'
});

   uploader.onBeforeUploadItem = function(item) {
    item.formData.push({token:$scope.token});
};

uploader.onSuccessItem = function(fileItem, response, status, headers) {
    if (response.status == 200){
        $scope.uploadView = false;
        $scope.profile.photo = response.image;
        $scope.success_messages = response.success_messages;
    }else{
        $scope.uploadView = false;
    }
};

var identityUploader = $scope.identityUploader = new FileUploader({
    headers: {'X-Requested-With':'XMLHttpRequest'},
    url: SITE_URL+'mso/upload_identity'
});

identityUploader.onBeforeUploadItem = function(item) {
    item.formData.push($scope.identity);
    item.formData.push({token:$scope.token});
};

identityUploader.onSuccessItem = function(fileItem, response, status, headers) {
    if (response.status == 200){
        $scope.identity.identity_attachment = response.image;
        $scope.success_messages = response.success_messages;
    }else{
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
    var http = WebService.post('mso/update_profile',$scope.profile);
    http.then(function(response){
        var data = response.data;
        if(data.status == 400){
            $scope.warning_messages = data.warning_messages;
            $scope.success_messages = '';
        } else {
            $scope.success_messages = data.success_messages;
            $scope.warning_messages = '';
            
            
        }
        $("html,body").animate({scrollTop:"0px"});
    });
};

$scope.updateLogin = function(){

    var formData = {
        token:$scope.token,
        username:$scope.profile.username,
        password:$scope.profile.password,
        re_password:$scope.profile.re_password
    };
    var http = WebService.post('mso/update_login_info',formData);
    http.then(function(response){
        var data = response.data;
        if(data.status==400){
            $scope.warning_messages = data.warning_messages;
        } else {
            $scope.success_messages = data.success_messages;
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

        var http = WebService.post('location/ajax_get_request/'+type,formData);
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
                    url: BASE_URL+"location/ajax_get_request/divisions",
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
        url: BASE_URL+"location/ajax_get_request/districts",
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
        url: BASE_URL+"location/ajax_get_request/areas",
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
        url: BASE_URL+"location/ajax_get_request/sub_areas",
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
        url: BASE_URL+"location/ajax_get_request/roads",
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