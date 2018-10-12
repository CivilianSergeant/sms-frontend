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
app.controller('conditionalLimited',['$scope','WebService',function($scope,WebService){

	$scope.from_day = new Date();
	$scope.end_day = new Date();
	$scope.end_day.setDate($scope.from_day.getDate() + eval(7));
	$scope.broadcast_type = 'LCO';
	$scope.lco = $scope.pairings = [];
	$scope.sign = sign;
	$scope.minDate = new Date();

	$scope.subscribers = [{user_id:0,subscriber_name:'All'}];

	$scope.packages = [];
	$scope.selected_item = [];
	$scope.included_item = [];
	$scope.assigned_packages = [];




	$scope.$watch('broadcast_type',function(val){
		if(val == 'SUBSCRIBER'){
			$scope.address_by = 'CARD';
		}else{
			$scope.address_by = null;
		}
	});


	var loadAllLco = function(){
		var http = WebService.get('tools-conditional-limited/ajax-get-lco');
		http.then(function(response){
			var data = response.data;
			if(data.status == 200)
			{
				$scope.lco = data.lco;
			}
		});
	};

	$scope.disabledSubscriber = function()
	{
		if($scope.broadcast_type == 'LCO')
		{
			return true;
		}
		if($scope.broadcast_type == 'BUSINESS_REGION')
		{
			return true;
		}
		return false;
	};

	$scope.disableLco = function(){
		if($scope.broadcast_type == 'BUSINESS_REGION')
		{
			return true;
		}
		return false;
	};

	$scope.disabledBroadCast = function()
	{
		if($scope.broadcast_type == 'LCO')
		{
			return true;
		}
		if($scope.broadcast_type == 'SUBSCRIBER')
		{
			return true;
		}
		return false;
	};

	$scope.loadSubscriber = function(){
		$scope.subscribers = [];
		$scope.pairings = [];
		$scope.subscriber_id = null;
		$scope.pairing_id = null;
		var http = WebService.get('tools-conditional-limited/ajax-get-subscriber-by-lco/'+$scope.lco_id);
		http.then(function(response){
			var data = response.data;
			if(data.status == 200){
				$scope.subscribers = (data.subscribers.length>1)? data.subscribers:[];
			}
		});
	};

	$scope.loadPairings = function(){
		$scope.pairings = [];
		$scope.pairing_id = null;
		var http = WebService.get('tools-conditional-limited/ajax-get-pairing/'+$scope.subscriber_id);
		http.then(function(response){
			var data = response.data;
			if(data.status == 200){
				$scope.pairings = data.pairings;
			}
		});
	};

	var loadBusinessRegions = function()
	{
		var http = WebService.get('tools-conditional-limited/ajax-get-regions');
		http.then(function(response){
			var data = response.data;
			if(data.status == 200)
			{
				$scope.regions = data.regions;
			}

		});
	};

	$scope.closeAlert = function(){
		$scope.warning_messages = $scope.success_messages = $scope.error_messages = '';
	};

	$scope.sendConditionalLimited = function(){

		var formData = {};


		var year = $scope.from_day.getFullYear();
		var month = ($scope.from_day.getMonth()<10)? '0'+($scope.from_day.getMonth()+1): ($scope.from_day.getMonth()+1);
		var date  = ($scope.from_day.getDate()<10)? '0'+$scope.from_day.getDate(): $scope.from_day.getDate();
		var start_time_min  = $scope.from_day.getHours() + ':' + $scope.from_day.getMinutes() + ':' + $scope.from_day.getSeconds();
		formData.startTime = year+'-'+month+'-'+date+' '+start_time_min;

		year = $scope.end_day.getFullYear();
		month = ($scope.end_day.getMonth()<10)? '0'+($scope.end_day.getMonth()+1): ($scope.end_day.getMonth()+1);
		date  = ($scope.end_day.getDate()<10)? '0'+$scope.end_day.getDate(): $scope.end_day.getDate();
		var end_time_min  = '23:59:59';
		formData.endTime = year+'-'+month+'-'+date+' '+ end_time_min;

		formData.broadcast_type = $scope.broadcast_type;

		var startTime = new Date(formData.startTime);
		var endTime   = new Date(formData.endTime);

		if(startTime.getTime() > endTime.getTime())
		{
			$scope.warning_messages = "Sorry! You cannot set From Datetime greater than To Datetime";
			return;
		}

		if($scope.broadcast_type == 'LCO'){

			formData.lco_id = $scope.lco_id;
			formData.subscriber_id = null;
			formData.pairing_id = null;
			formData.address_by = null;
			formData.business_region_id = null;

		}else if($scope.broadcast_type == 'SUBSCRIBER')
		{
			formData.lco_id = $scope.lco_id;
			formData.subscriber_id = $scope.subscriber_id;
			formData.pairing_id = $scope.pairing_id;
			formData.address_by = $scope.address_by;
			formData.business_region_id = null;

		}else{

			formData.lco_id = null;
			formData.subscriber_id = null;
			formData.pairing_id = null;
			formData.address_by = null;
			formData.business_region_id = $scope.business_region_id;
		}



		var productId = [];
		for(s in $scope.assigned_packages){
			productId.push($scope.assigned_packages[s].id);
		}
		formData.prodCounts  = productId.length;
		formData.productID = productId;


		var http = WebService.post('tools-conditional-limited/process',formData);
		http.then(function(response){
			var data = response.data;
			if(data.status == 400){
				$scope.warning_messages = data.warning_messages;
				$scope.success_messages = '';
			}else{
				$scope.success_messages = data.success_messages;
				$scope.warning_messages = '';
				$scope.loadNotification();
			}
			$("html,body").animate({scrollTop:'0px'});
		});

	};

	$scope.IncludeItems = function(){

		for(p in $scope.packages){
			for(item in $scope.selected_item){
				if($scope.packages[p].id == $scope.selected_item[item])
				{
					$scope.assigned_packages.push($scope.packages[p]);
					$scope.packages.splice(p,1);
				}
			}
		}
	};

	$scope.ExcludeItems = function(){

		for(ap in $scope.assigned_packages){
			for(item in $scope.included_item){
				if($scope.assigned_packages[ap].id == $scope.included_item[item])
				{
					$scope.packages.push($scope.assigned_packages[ap]);
					$scope.assigned_packages.splice(ap,1);
				}
			}
		}

	};


	var loadPackages = function() {
		var http = WebService.get('tools-conditional-limited/ajax-get-packages');
		http.then(function(response){
			var data = response.data;
			$scope.packages = data.packages;
		});
	};

	loadPackages();

	loadBusinessRegions();

	loadAllLco();

}]);