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
app.controller('Collector',['$scope','WebService',function($scope,WebService){

	$scope.collector = {id:'',collector_name:'',present_address:'',parmanent_address:'',phone1:'',phone2:'',nid_number:'', reference_name:'', reference_phone:''};
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
	}

	var formValidation = function(){
		var regex = /^\d+$/;
		$scope.warning_messages = '';

		if(!regex.test($scope.collector.phone1)){
			$scope.warning_messages = 'Sorry! Phone 1 should be number';
			$("html,body").animate({"scrollTop":"0px"});
			return false;
		}

		if($scope.collector.phone2 != "" && !regex.test($scope.collector.phone2)){
			$scope.warning_messages = 'Sorry! Phone 2 should be number';
			$("html,body").animate({"scrollTop":"0px"});
			return false;
		}

		if($scope.collector.phone1.length!=11){
			$scope.warning_messages = 'Sorry! Phone 1 should have 11 digits';
			$("html,body").animate({"scrollTop":"0px"});
			return false;
		}

		return true;

	};

	$scope.saveCollector = function(){


		var isValid = formValidation();
		if(!isValid){
			return isValid;
		}

		var http = WebService.post('collector/save-collector',$scope.collector);
		http.then(function(response){
			var data = response.data;
			if(data.status == 400){
				$scope.warning_messages = data.warning_messages;
				$scope.success_messages = '';
			} else {
				$scope.collector = {};
				$scope.saveCollectorAdd.$setUntouched();
				$scope.success_messages = data.success_messages;
				$scope.warning_messages = '';
				$scope.showFrm = 0;
				$scope.loadNotification();
				loadCollectors();
			}
			$("html,body").animate({"scrollTop":"0px"});
		});
	};


	var loadCollectors = function(){
		generateKendoGird();
	};

	var generateKendoGird = function(data){
        	$scope.mainGridOptions = {
			dataSource: {

	            schema: {
	            	data: "collectors",
	            	total: "total"
	            },

	            transport: {
	            	read: {
	            		url: "collector/ajax_load_collector", 
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
                {field: "name", title: "Name",width: "auto"},
                {field: "phone1", title: "Phone",width: "auto"},
                {field: "parmanent_address", title: "Parmanent Address",width: "auto"},
                {field: "nid_number", title: "National Id",width: "auto"},
                {field: "", filterable: false, title: "Action",width: "auto",template:"<a href='"+SITE_URL+"collector/view/#=data.id#' class='btn btn-default btn-xs' data-toggle='tooltip' data-placement='left' title='View'><i class='fa fa-search'></i></a> <a ng-if='permissions.edit_permission == \"1\"' href='"+SITE_URL+"collector/edit/#=data.id#' class='btn btn-default btn-xs' data-toggle='tooltip' data-placement='left' title='Update'><i class='fa fa-pencil'></i></a>"},
            ]
        };
    };

	loadCollectors();

	var loadPermissions = function(){
		var http = WebService.get('collector/ajax_get_permissions');
		http.then(function(response){
			var data = response.data;
			if(data.status == 200){
				$scope.permissions=data.permissions;
			}
		});
	};




	loadPermissions();

}]);
app.controller('CollectorEdit',['$scope','WebService',function($scope,WebService){
	$scope.collector = null;
	$scope.collector_id = collector_id;

	var loadCollector = function(){
		var http = WebService.get('collector/ajax_get_collector/'+$scope.collector_id);
		http.then(function(response){
			var data = response.data;
			if(data.status == 200){
				$scope.collector = data.collector;

			}
		});
	};

	var formValidation = function(){
		var regex = /^\d+$/;
		$scope.warning_messages = '';

		if(!regex.test($scope.collector.phone1)){
			$scope.warning_messages = 'Sorry! Phone 1 should be number';
			$("html,body").animate({"scrollTop":"0px"});
			return false;
		}

		if($scope.collector.phone2 != "" && !regex.test($scope.collector.phone2)){
			$scope.warning_messages = 'Sorry! Phone 2 should be number';
			$("html,body").animate({"scrollTop":"0px"});
			return false;
		}

		if($scope.collector.phone1.length!=11){
			$scope.warning_messages = 'Sorry! Phone 1 should have 11 digits';
			$("html,body").animate({"scrollTop":"0px"});
			return false;
		}

		return true;
	};


	$scope.updateCollector = function(){
		var isValid = formValidation();
		if(!isValid){
			return isValid;
		}
		var http = WebService.post('collector/update-collector',$scope.collector);
		http.then(function(response){
			var data = response.data;
			if(data.status == 400){

				$scope.warning_messages = data.warning_messages;
				$scope.success_messages = '';
			}else{
				$scope.success_messages = data.success_messages;
				$scope.warning_messages = '';
				window.location = SITE_URL+'collector';
			}
		});
	};

	loadCollector();
}]);


