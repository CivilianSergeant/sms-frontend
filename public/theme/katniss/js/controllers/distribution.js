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
app.controller('Distribution',['$scope','WebService',function($scope,WebService){

	$scope.user_id = user_id;
	$scope.user_type = user_type;
	$scope.lco_profiles = [];
	$scope.group_profiles = [];
	$scope.showFrm = false;
	$scope.distribution_data = {id:'', lco_user_id:'', distributor_id:'', batch:'', serial_from:'', serial_to:''};
	$scope.showFrm = 0;
	$scope.permissions = [];
	$scope.batch_numbers = [];
	$scope.serial_numbers = [];


	$scope.checkCardSerial = function(){
		//console.log($scope.distribution_data.serial_to);
		$scope.warning_messages = '';
		if($scope.distribution_data.serial_to < $scope.distribution_data.serial_from)
		{
			$scope.warning_messages = 'Please Maintain Serial Number Sequence!';
			//$scope.distribution_data.serial_from = '';
			//$scope.distribution_data.serial_to = '';
		}
		else{
			$scope.warning_messages = '';
		}

	};

	$scope.closeAlert = function(){
		$scope.success_messages = '';
		$scope.warning_messages = '';
		$scope.error_messages = '';
	};

	$scope.hideForm = function(){
		$scope.showFrm = 0;
	};

	$scope.showForm = function()
	{
		$scope.showFrm = 1;
	};



	var loadLco = function(id,user_type){
		var http = WebService.get('scratch-card-distribution/ajax-load-lco/'+id+'/'+user_type);
		http.then(function(response){
			var data = response.data;
			if(data.status == 200){
				$scope.lco_profiles = data.lco_profile					
			}
		});
	};

	$scope.loadGroups = function(){
		var http = WebService.get('scratch-card-distribution/ajax-load-groups');
		http.then(function(response){
			var data = response.data;
			if(data.status == 200){
				$scope.group_profiles = data.group_profiles;
			}
		});
	};

	$scope.loadDistributors = function(lcoId){
		$scope.distribution_data.distributor_id = '';
		/*if(user_type == 'LCO'){
			var http = WebService.get('scratch-card-distribution/ajax-load-distributors-by-lco/'+$scope.user_id);
		}else{
			var http = WebService.get('scratch-card-distribution/ajax-load-distributors-by-lco/'+$scope.distribution_data.lco_user_id);
		}*/
		var id  = (lcoId)? lcoId : $scope.distribution_data.lco_id;
			console.log(id);
			var http = WebService.get('scratch-card-distribution/ajax-load-distributors-by-lco/'+id);
			http.then(function(response){
				var data = response.data;
				if(data.status == 200){
					$scope.distributor_profiles = data.distributors;
				}
			});


	};

	$scope.loadBatchNumbers = function(Id){
		/*if(user_type == 'LCO') {
			var http = WebService.get('scratch-card-distribution/ajax-load-batch-numbers/' + $scope.user_id);
		}else{
			var http = WebService.get('scratch-card-distribution/ajax-load-batch-numbers/' + $scope.distribution_data.lco_user_id);
		}*/
		var Url = 'scratch-card-distribution/ajax-load-batch-numbers/' + Id;


		var http = WebService.get(Url);
		http.then(function(response){
			var data = response.data;
			if(data.status == 200){
				$scope.batch_numbers = data.all_batch_numbers;
			}
		});
	};

	$scope.loadSerialNumbers = function(){
		if(user_type == 'MSO'){
			var ID = ($scope.distribution_data.group_id) ? $scope.distribution_data.group_id : $scope.user_id;


			var http = WebService.get('scratch-card-distribution/ajax-load-serial-no/'+$scope.distribution_data.batch+'/'+$scope.user_id);
		}else if(user_type == "Group") {
			var http = WebService.get('scratch-card-distribution/ajax-load-serial-no/'+$scope.distribution_data.batch+'/'+$scope.user_id);
		}else if(user_type == "LCO"){
			var http = WebService.get('scratch-card-distribution/ajax-load-serial-no/'+$scope.distribution_data.batch+'/'+$scope.user_id);
		}

		http.then(function(response){
			var data = response.data;
			if(data.status == 200){
				$scope.serial_numbers = data.serial_numbers;
			}
		});
	};

	var formValidation = function(){
		var regex = /^\d+$/;

		if($scope.user_type == "MSO" && $scope.distribution_data.group_id == 1){
			if($scope.distribution_data.distributor_id == null ||
					$scope.distribution_data.distributor_id == undefined ||
					$scope.distribution_data.distributor_id == 0){
				$scope.warning_messages = 'Please Select Distributor for MSO';
				return false;
			}
		}

		if(!regex.test($scope.distribution_data.batch)){
			$scope.warning_messages = 'Sorry! batch number should be numeric';
			return false;
		}

		if(!regex.test($scope.distribution_data.serial_from)){
			$scope.warning_messages = 'Sorry! Serial From number should be numeric';
			return false;
		}

		if(!regex.test($scope.distribution_data.serial_to)){
			$scope.warning_messages = 'Sorry! Serial To number should be numeric';
			return false;
		}

		return true;
	};

	$scope.saveDistributionData = function(){
		var isValid = formValidation();
		if(!isValid){
			return isValid;
		}
		//console.log($scope.distribution_data.distributor_id);

		var http = WebService.post('scratch-card-distribution/distribution-card', $scope.distribution_data);
		http.then(function(response){
			var data = response.data;
			if(data.status == 400){
				$scope.warning_messages = data.warning_messages;
				$scope.success_messages = '';
			} else {
				$scope.distribution_data = {};
				//$scope.saveDistribution.$setUntouched();
				$scope.success_messages = data.success_messages;
				$scope.warning_messages = '';
				$scope.showFrm = 0;
				$scope.loadNotification();
			}
			$("html,body").animate({"scrollTop":"0px"});
		});

	};

	 var loadPermissions = function(){
	 	var http = WebService.get('scratch-card-distribution/ajax-get-permissions');
	 	http.then(function(response){
	 		var data = response.data;
	 		if(data.status == 200){
	 			$scope.permissions=data.permissions;
	 		}
	 	});
	 };

	loadPermissions();


	$scope.loadData = function(){
		var groupId = ($scope.distribution_data.group_id)? $scope.distribution_data.group_id : 1;
		if($scope.user_type == "MSO"){
			if(groupId>1){

				loadLco(groupId,'Group');
				//$scope.loadDistributors(groupId);
				//$scope.loadBatchNumbers(groupId);
			}else{
				$scope.loadGroups();
				loadLco(groupId,'MSO');

				//$scope.loadDistributors(groupId);
				$scope.loadBatchNumbers(groupId);
			}

		}

	};

	$scope.loadData();



	if(user_type == 'LCO'){
		var id = user_id;
		$scope.loadDistributors(id);
		$scope.loadBatchNumbers(id);
	}

	if(user_type == "Group"){
		var id = user_id;
		loadLco(id,'Group');
		$scope.loadBatchNumbers(id,'Group');
	}

	$scope.showForm = function() {
		$scope.showFrm = true;
	};

	$scope.hideForm = function(){
		$scope.showFrm = false;
	};

	$scope.setToSerialNo = function(){
		if($scope.sameAsFrom){
			$scope.distribution_data.serial_to = '';
			$scope.sameAsFrom = 0;
		}else{
			$scope.distribution_data.serial_to = $scope.distribution_data.serial_from;
			$scope.sameAsFrom = 1;
		}

	};



	var generateKendoGird = function(data){
		$scope.mainGridOptions = {
			dataSource: {

				schema: {
					data: "distributed_list",
					total: "total"
				},

				transport: {
					read: {
						url: "scratch-card-distribution/ajax-get-distributed-list",
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
				{field: "created_at",   title: "Date Time",width: "200px",filterable:false},
				{field: "group_name",   title: "To Group",width: "auto",filterable:false, template:'#if(group_name != null){# #=group_name#  #}else{# <span class="text-center">---<span>  #}#'},
				{field: "lco_name",     title: "To LCO",width: "auto",filterable:false, template:'#if(lco_name != null){# #=lco_name#  #}else{# <span class="text-center">---<span>  #}#'},
				{field: "from_serial_no", title: "FROM",width:"auto",filterable:false},
				{field: "to_serial_no", title: "TO",width:"auto",filterable:false},
				{field: "total",  title: "Total",width:"auto",filterable:false},
				{field: "value", title: "Amount",width:"auto",filterable:false},
				{field: "used", title: "Used",width:"auto",filterable:false},
				{field: "unused", title:"Un-used",width:"auto",filterable:false},
				{field: "", title:"Action",width:"auto",template:'<a href="'+SITE_URL+'scratch-card-distribution/detail/#=from_serial_no#/#=to_serial_no#" class="btn btn-default btn-xs">Show</a>'}

			]
		};

		if(user_type == 'Group'){
			var columns = $scope.mainGridOptions.columns;
			for(c in columns){
				if(columns[c].field == 'group_name'){
					columns.splice(c,1);
				}
			}
			$scope.mainGridOptions.columns = columns;
		}
	};
	generateKendoGird();
}]);
