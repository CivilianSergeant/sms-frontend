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


	app.controller('CreateICProvider',['$scope','WebService',function($scope,WebService){

		$scope.items = [];
		$scope.permissions = [];
		$scope.providers = {id:'',ic_type:'',ic_supplier:'',description:'',address1:'',address2:'',country:'',state:'',city:'',zip:'',email:'',phone:''};
		$scope.showFrm = 0;

		$scope.closeAlert = function(){
			$scope.success_messages = '';
			$scope.warning_messages = '';
			$scope.error_messages = '';
		};

		$scope.hideForm = function(){
			$scope.showFrm = 0;
		}	

		$scope.showForm = function()
		{
			if($scope.permissions.create_permission == '1'){
				$scope.showFrm = 1;
			}else{
				$scope.warning_messages = "Sorry! You don't have permission to create IC Provider";
			}

		}

		$scope.$watch('providers.phone',function(val){
			if(val != null){
				var pattern = /[^0-9]/
				if(pattern.test(val)){
					$scope.warning_messages = 'Only Number allowed';
					$("html,body").animate({scrollTop:"0px"});
				}
			}
		});

		var formValidation = function(){
			var regex = /^\d+$/

			if(!regex.test($scope.providers.phone)){
				$scope.warning_messages = 'Phone number should be numeric';
				$("html,body").animate({"scrollTop":"0px"});
				return false;
			}
			return true;
		};

		$scope.saveProviders = function(){
			if($scope.permissions.create_permission != '1'){
				$scope.warning_messages = "Sorry! You don't have permission to create IC Provider";
				return;
			}

			var isValid = formValidation();
			if(!isValid){
				return isValid;
			}

			var http = WebService.post('icsmartcard-provider/create',$scope.providers);
			http.then(function(response){
				var data = response.data;
				if(data.status == 400){
					$scope.warning_messages = data.warning_messages;
					$scope.success_messages = '';
				} else {
					//add
					$scope.providers = {};
					$scope.icProviderAdd.$setUntouched();
					$scope.success_messages = data.success_messages;
					$scope.warning_messages = '';
					$scope.showFrm = 0;
					loadProfiles();
					$scope.loadNotification();
				}
				$("html,body").animate({"scrollTop":"0px"});
			});
		};


		var loadProfiles = function(){
			generateKendoGird();

	/*		var http = WebService.get("icsmartcard_provider/ajax_load_providers");
			http.then(function(response){
				var data = response.data;
				if(data.status == 200){
					$scope.items = data.profiles;
					generateKendoGird($scope.items);
				}
			});*/
	};

	var generateKendoGird = function(data){		

		$scope.mainGridOptions = {
			dataSource: {

	            schema: {
	            	data: "profiles",
	            	total: "total"
	            },

	            transport: {
	            	read: {
	            		url: "icsmartcard-provider/ajax_load_providers",
	            		dataType: "json",
	            	}
	            },
	               pageSize: 10,
	               serverPaging: true,
	               serverFiltering: false              
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
	            {field: "stb_type", title: "Provider Type",width: "auto"},
	            {field: "stb_provider", title: "Name",width: "auto"},
	            {field: "address1", title: "Address",width: "auto"},
	            {field: "country", title: "Country",width: "auto"},
	            {field: "city", title: "City",width: "auto"},
	            {field: "email", title: "Email",width: "auto"},
	            {field: "", title: "Action",width: "auto",template:"<a href='"+SITE_URL+"icsmartcard-provider/view/#=data.id#' class='btn btn-default btn-xs' data-toggle='tooltip' data-placement='left' title='View'><i class='fa fa-search'></i></a> <a ng-if='permissions.edit_permission == \"1\"' href='"+SITE_URL+"icsmartcard-provider/edit/#=data.id#' class='btn btn-default btn-xs' data-toggle='tooltip' data-placement='left' title='Update'><i class='fa fa-pencil'></i></a>"},
	            ],

	        };

	    };

		var loadPermissions = function(){
			var http = WebService.get('icsmartcard-provider/ajax_get_permissions');
			http.then(function(response){
				var data = response.data;
				if(data.status == 200){
					$scope.permissions=data.permissions;
				}
			});
		};

		loadPermissions();

	    loadProfiles();

	}]);
