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
app.controller('package',['$scope','WebService',function($scope,WebService){
	$scope.programs = $scope.assigned_programs = $scope.selected_item = $scope.included_item = [];
	$scope.is_active = 1;
    $scope.permissions = [];
	var programCount = 190;

	$scope.hideForm = function(){
		$scope.showFrm = 0;
	}

	$scope.showForm = function()
	{
        if($scope.permissions.create_permission=="1")
        {
            $scope.showFrm = 1;
        }else{
            $scope.warning_messages = "Sorry! You don't have permission to create package";
        }

	}

	$scope.closeAlert  = function()
	{
		$scope.warning_messages = $scope.success_messages = $scope.error_messages = '';
	};

	$scope.IncludeItems = function(){


        if($scope.selected_item.length>programCount){
            $scope.warning_messages = 'Sorry! Maximum '+programCount+' Programs could be assigned. You have selected '+$scope.selected_item.length+' programs';
            return;
        }



        for(p in $scope.programs){
            for(item in $scope.selected_item){
                if($scope.programs[p].id == $scope.selected_item[item])
                {
                        if($scope.assigned_programs.length == programCount){
                            $scope.warning_messages = 'Sorry! Maximum '+programCount+' Programs could be assigned. You have selected '+$scope.assigned_programs.length+' programs';
                            return;
                        }
                        $scope.assigned_programs.push($scope.programs[p]);
                        $scope.programs.splice(p,1);
                }
            }
        }
    };

    $scope.ExcludeItems = function(){

        for(ap in $scope.assigned_programs){
            for(item in $scope.included_item){
                if($scope.assigned_programs[ap].id == $scope.included_item[item])
                {
                    $scope.programs.push($scope.assigned_programs[ap]);
                    $scope.assigned_programs.splice(ap,1);
                }
            }
        }

    };

    var formValidation = function(formData){
        var regex = /^\d+$/;

        if(!regex.test(formData.package_price)){
            $scope.warning_messages = "Sorry! Package Price should be numeric";
            return false;
        }

        if(!regex.test(formData.package_duration)){
            $scope.warning_messages = "Sorry! Package Duration should be numeric";
            return false;
        }

        return true;
    };

    $scope.savePackage = function(){
    	$scope.closeAlert();

    	var formData = {};
    	formData.package_name = $scope.package_name;
    	formData.package_price = $scope.package_price;
    	formData.package_duration = $scope.package_duration;

    	formData.is_active = $scope.is_active;

        var isValid = formValidation(formData);
        if(!isValid){
            return isValid;
        }

    	if($scope.assigned_programs.length>programCount){
    		$scope.warning_messages = 'Sorry! Maximum '+programCount+' Programs could be assigned. You have selected '+formData.programs.length+' programs';
    		return;
    	}

        formData.programs = [];
        for(p in $scope.assigned_programs)
        {
            formData.programs.push($scope.assigned_programs[p].id);
        }
        //console.log(formData.programs.length);
        if($scope.permissions.create_permission!="1"){
            $scope.warning_messages = "Sorry! You don't have permission to create package";
            return;
        }
    	var http = WebService.post('package/save',formData);
    	http.then(function(response){
    		var data = response.data;

    		if(data.status == 400){
    			$scope.warning_messages = data.warning_messages;
    			$scope.success_messages = '';
    		}else{
    			$scope.success_messages = data.success_messages;
    			$scope.warning_messages = '';
    			loadPackage();
    			loadPrograms();
    			$scope.package_name = '';
    			$scope.packge_price = '';
    			$scope.package_duration = '';
    			$scope.is_active = 1;
    			$scope.assigned_programs = [];
    			$scope.showFrm = 0;
    		}
    	});
    };

    var loadPrograms = function(){
    	var http = WebService.get('package/ajax_load_programs');
    	http.then(function(response){
			var data = response.data;
			if(data.status == 200){
				$scope.programs = data.programs;
			}
		});
    };

	var loadPackage = function(){
		var http = WebService.get('package/ajax_load_package');
		http.then(function(response){
			var data = response.data;
			if(data.status == 200){
				$scope.items = data.packages;



			}
		});
	};

	   var generateKendoGird = function(){
        $scope.mainGridOptions = {
            dataSource: {
                transport: {
                    read: {
                        url: "package/ajax_load_package",
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
                {field: "programs", title: "Programs", width: "auto",filterable:false},
                {field: "duration", title: "Duration", width: "auto",filterable:false},
                {field: "price", title: "Price", width: "auto",filterable:false},
                {field: "assigned", title: "Assinged", width: "auto",filterable:false,template: '# if(data.assigned>0) {# <span class="label label-success">Yes</span> #} else {# <span class="label label-danger">No</span> #}#'},
                {field: "is_active", title: "Status", width: "auto",filterable:false,template: '# if(data.is_active==1) {# <span class="label label-success">Active</span> #} else {# <span class="label label-danger">Inactive</span> #}#'},
                {field: "", title: "Action", width: "auto",filterable:false,template:"<a href='"+SITE_URL+"package/view/#=data.token#' class='btn btn-default btn-xs' data-toggle='tooltip' data-placement='left' title='View'><i class='fa fa-search'></i></a>"+
                ' <a ng-if="permissions.edit_permission==\'1\'" href="'+SITE_URL+'package/edit/#=data.token#" class="btn btn-default btn-xs" data-toggle="tooltip" data-placement="left" title="Edit"><i class="fa fa-pencil"></i></a> # if ((data.assigned>0) || (data.id == 65535 || data.id == 65534)) {##} else {#<a ng-if="permissions.delete_permission==\'1\'" ng-click="delete(#=data.id#)" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="left" title="Delete"><i class="fa fa-trash"></i></a>#}#'},
            ]
        };


    };

    $scope.delete = function(i)
    {
        console.log(i);
        $scope.delete_item = i;
    }

    $scope.confirm_delete = function()
    {
        var location = SITE_URL + 'package/delete/'+$scope.delete_item;
        window.location = location;
    }

    $scope.cancel_delete = function()
    {
        $scope.delete_item = 0;
    }

    var loadPermissions = function(){
        var http = WebService.get('package/ajax_get_permissions');
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.permissions=data.permissions;
            }
        });
    };

    loadPermissions();
    generateKendoGird();

    loadPrograms();



}]);
app.controller('editPackage',['$scope','WebService','$sce',function($scope,WebService,$sce){
    $scope.programs = $scope.assigned_programs = $scope.selected_item = $scope.included_item = [];
    $scope.is_active = 1;
    var programCount = 190;

    $scope.hideForm = function(){
        $scope.showFrm = 0;
    }   

    $scope.showForm = function()
    {
        $scope.showFrm = 1;
    }

    $scope.closeAlert  = function()
    {
        $scope.warning_messages = $scope.success_messages = $scope.error_messages = '';
    };

    $scope.IncludeItems = function(){
        
        for(p in $scope.programs){
            for(item in $scope.selected_item){
                if($scope.programs[p].id == $scope.selected_item[item])
                {
                        $scope.assigned_programs.push($scope.programs[p]);    
                        $scope.programs.splice(p,1);
                }
            }
        }
    };

    $scope.ExcludeItems = function(){
        
        for(ap in $scope.assigned_programs){
            for(item in $scope.included_item){
                if($scope.assigned_programs[ap].id == $scope.included_item[item])
                {
                    $scope.programs.push($scope.assigned_programs[ap]);
                    $scope.assigned_programs.splice(ap,1);   
                }
            }
        }
        
    };

    $scope.savePackage = function(){
        $scope.closeAlert();

        var formData = {};
        formData.package_name = $scope.package_name;
        formData.package_price = $scope.package_price;
        formData.package_duration = $scope.package_duration;
        //formData.programs  = $scope.assigned_programs;
        formData.programs = [];
        for(p in $scope.assigned_programs)
        {
            formData.programs.push($scope.assigned_programs[p].id);
        }
        
        formData.is_active = $scope.is_active;
        if(formData.programs.length>programCount){
            $scope.warning_messages = 'Sorry! Maximum '+programCount+' program could be assign. You selected '+formData.programs.length+' programs';
            return;
        }
        
        var http = WebService.post('package/save/'+token,formData);
        http.then(function(response){
            var data = response.data;
            
            if(data.status == 400){
                $scope.warning_messages = $sce.trustAsHtml(data.warning_messages);
                $scope.success_messages = '';
            }else{
                $scope.success_messages = data.success_messages;
                $scope.warning_messages = $sce.trustAsHtml('');
                $("html,body").animate({scrollTop:'0px'},function(){
                    window.location = SITE_URL + 'package';
                });
                
            }
        });
    };

    var loadPrograms = function(){
        var http = WebService.get('package/ajax_load_programs');
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.programs = data.programs;
            }
        });
    };

    var loadPackage = function(){
        var http = WebService.get('package/ajax_load_package_programs/'+token);
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                var pkg = data.pkg;
                $scope.package_name = pkg.package_name;
                $scope.package_price = parseInt(pkg.price);
                $scope.package_duration = parseInt(pkg.duration);
                $scope.is_active = parseInt(pkg.is_active);
                $scope.programs = data.programs;
                
                $scope.assigned_programs = data.assigned_programs;
                
                
            }
        });
    };

       
     
    loadPackage();
    
}]);

app.controller('assignedDetails',['$scope','WebService',function($scope,WebService){

    $scope.token = token;

    var generateKendoGird = function(){
        $scope.mainGridOptions = {
            dataSource: {
                transport: {
                    read: {
                        url: SITE_URL+"package/ajax_get_assigned_package_list/"+$scope.token, 
                        dataType: "json",
                    }
                },
                schema: {
                    data: "assigned_package_list",
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
                {field: "pairing_id", title: "PairingID", width: "160px",filterable:false},
                {field: "subscriber_name", title: "Subscriber Name", width: "200px"},
                {field: "package_start_date", title: "Package Start Date", width: "auto",filterable:false},
                {field: "package_expire_date", title: "Package End Date", width: "auto",filterable:false}
                
            ]
        };

       
    };

    generateKendoGird();

}]);
