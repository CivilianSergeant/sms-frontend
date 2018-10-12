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
app.controller('Distributor',['$scope','WebService',function($scope,WebService){

	$scope.distributor = {id:'',distributor_name:'',present_address:'',parmanent_address:'',phone1:'',phone2:'',nid_number:'', reference_name:'', reference_phone:''};
	$scope.showFrm = 0;
	$scope.permissions = [];

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
		$scope.showFrm = 1;
	};

	var formValidation = function(){
		var regex = /^\d+$/;
		if(!regex.test($scope.distributor.phone1)){
			$scope.warning_messages = 'Sorry! Phone1 number should be numeric';
			return false;
		}

		if(!regex.test($scope.distributor.phone2)){
			$scope.warning_messages = 'Sorry! Phone2 number should be numeric';
			return false;
		}
		return true;
	};

	$scope.saveDistributor = function(){
		var isValid= formValidation();
		if(!isValid){
			return isValid;
		}

		var http = WebService.post('scratch-card-distributor/save-distributor',$scope.distributor);
		http.then(function(response){
			var data = response.data;
			if(data.status == 400){
				$scope.warning_messages = data.warning_messages;
				$scope.success_messages = '';
			} else {
				$scope.distributor = {};
				$scope.saveDistributorAdd.$setUntouched();
				$scope.success_messages = data.success_messages;
				$scope.warning_messages = '';
				$scope.showFrm = 0;
				$scope.loadNotification();
				loadDistributors();
			}
			$("html,body").animate({"scrollTop":"0px"});
		});
	};


	var loadDistributors = function(){
		generateKendoGird();
	};

	var generateKendoGird = function(data){
        	$scope.mainGridOptions = {
			dataSource: {

	            schema: {
	            	data: "distributor",
	            	total: "total"
	            },

	            transport: {
	            	read: {
	            		url: "scratch-card-distributor/ajax-load-distributor", 
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
				   operators: {
					   string: {
						   startswith: "Starts with",
						   eq: "Is equal to",

					   }
				   }
	           },

	           dataBound: gridDataBound,

            columns: [
                {field: "distributor_name", title: "Name",width: "auto"},
                {field: "phone1", title: "Phone",width: "auto"},
                {field: "parmanent_address", title: "Parmanent Address",width: "auto"},
                {field: "nid_number", title: "National Id",width: "auto"},
                {field: "", filterable: false, title: "Action",width: "auto",template:"<a href='"+SITE_URL+"scratch-card-distributor/distributor-view/#=data.id#' class='btn btn-default btn-xs' data-toggle='tooltip' data-placement='left' title='View'><i class='fa fa-search'></i></a> <a ng-if='permissions.edit_permission == \"1\"' href='"+SITE_URL+"scratch-card-distributor/distributor-edit/#=data.id#' class='btn btn-default btn-xs' data-toggle='tooltip' data-placement='left' title='Update'><i class='fa fa-pencil'></i></a>"},
            ]
        };
    };

	loadDistributors();

	 var loadPermissions = function(){
	 	var http = WebService.get('scratch-card-distributor/ajax-get-permissions');
	 	http.then(function(response){
	 		var data = response.data;
	 		if(data.status == 200){
	 			$scope.permissions=data.permissions;
	 		}
	 	});
	 };

	 loadPermissions();

}]);
