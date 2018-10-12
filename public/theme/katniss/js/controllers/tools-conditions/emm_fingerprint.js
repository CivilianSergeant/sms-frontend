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
app.controller('emmFingerPrint',['$scope','WebService',function($scope,WebService){

	$scope.from_day = new Date();
	$scope.end_day = new Date();
	$scope.end_day.setDate($scope.from_day.getDate() + eval(7));
	$scope.broadcast_type = 'LCO';
	$scope.lco = $scope.pairings = [];
	$scope.sign = sign;
	$scope.minDate = new Date();

	$scope.subscribers = [{user_id:0,subscriber_name:'All'}];

	$scope.settings = [];
	$scope.priorities = [];
	$scope.positions = [];
	$scope.sizes = [];
	$scope.types = [];
	$scope.color_types = [];
	$scope.fonts = [];
	$scope.back_colors = [];

	$scope.position_id = '4';
	$scope.position_x = '0';
	$scope.position_y = '0';
	$scope.show_duration ='1';
	$scope.stop_duration = '0';
	$scope.size_id = '8';
	$scope.type_id = '0';
	$scope.color_type_id = '3';
	$scope.font_id = '-1';
	$scope.back_color_id = '-2139062017';
	$scope.transparency = '0';


	$scope.$watch('broadcast_type',function(val){
		if(val == 'SUBSCRIBER'){
			$scope.address_by = 'CARD';
		}else{
			$scope.address_by = null;
		}
	});


	var loadAllLco = function(){
		var http = WebService.get('tools-emm-fingerprint/ajax-get-lco');
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
		var http = WebService.get('tools-emm-fingerprint/ajax-get-subscriber-by-lco/'+$scope.lco_id);
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
		var http = WebService.get('tools-emm-fingerprint/ajax-get-pairing/'+$scope.subscriber_id);
		http.then(function(response){
			var data = response.data;
			if(data.status == 200){
				$scope.pairings = data.pairings;
			}
		});
	};

	var loadBusinessRegions = function()
	{
		var http = WebService.get('tools-emm-fingerprint/ajax-get-regions');
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

	$scope.sendEmmFingerprint = function(){

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

		formData.position_id   = $scope.position_id;
		formData.position_x    = $scope.position_x;
		formData.position_y    = $scope.position_y;
		formData.show_duration = $scope.show_duration;
		formData.stop_duration = $scope.stop_duration;
		formData.fingerprint_overt  = $scope.fingerprint_overt;
		formData.display_background = $scope.display_background;
		formData.show_card_stb      = $scope.show_card_stb;
		formData.font_size_id       = $scope.size_id;
		formData.font_type_id       = $scope.type_id;
		formData.color_type_id      = $scope.color_type_id;
		formData.font_id		    = $scope.font_id;
		formData.back_color_id      = $scope.back_color_id;

		$scope.positions.filter(function(obj){
			if(obj.name == formData.position_id){
				formData.position_id = obj.value;
				return;
			}
		});

		$scope.color_types.filter(function(obj){
			if(obj.name == formData.color_type_id){
				formData.color_type_id = obj.value;
				return;
			}
		});

		$scope.back_colors.filter(function(obj){
			if(obj.name == formData.back_color_id){
				formData.back_color_id = obj.value;
				return;
			}
		});


		var http = WebService.post('tools-emm-fingerprint/process',formData);
		http.then(function(response){
			var data = response.data;
			if(data.status == 400){
				$scope.warning_messages = data.warning_messages;
				$scope.success_messages = '';
			}else{
				$scope.success_messages = data.success_messages;
				$scope.warning_messages = '';
			}
			$("html,body").animate({scrollTop:'0px'});
		});

	};



	var loadSettingsData = function() {
		var http = WebService.get('tools-emm-fingerprint/ajax-get-settings-data');
		http.then(function(response){
			var data = response.data;
			$scope.settings   = data.settings;
			$scope.priorities = data.priorities;
			$scope.positions  = data.positions;
			$scope.sizes      = data.sizes;
			$scope.types      = data.types;
			$scope.color_types= data.color_types;
			$scope.fonts      = data.fonts;
			$scope.back_colors= data.back_colors;
		});
	};





	loadSettingsData();

	loadBusinessRegions();

	loadAllLco();

}]);