	var app = angular.module('plaasApp');
	app.factory('WebService',function($http){
		return {
			get:function(url,data){
				return $http({
					method:"GET",
					url : SITE_URL+url,
					data: data,
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
	app.controller('AssignSTB',['$scope','WebService',function($scope,WebService){
		
		$scope.lco_profiles = [];
		$scope.stb_types = [];
		$scope.lco = [];
		$scope.stb_cards = [];
		$scope.stb_box_id = [];
		$scope.loader = 1;
		$scope.permissions = [];

		$scope.stb_type_id = '';
		$scope.stb_number = '';

		$scope.gridFistTime = true;

		var loadLco = function(){
			var http = WebService.get('lco-assign-stb/ajax_load_assign_stb_data');
			http.then(function(response){
				var data = response.data;
				if(data.status = 200){

					$scope.lco_profiles = data.lco_profile,
					
					$scope.stb_types = data.stb_type
				}
			});
		};

		$scope.closeAlert = function(){
			$scope.success_messages = $scope.warning_messages = '';
		};	

		

		$scope.selectAllItem = function(){
			console.log($scope.stb_cards);

			if($scope.stb_box_id.length > 0)
			{
				console.log($scope.stb_box_id.length);
				$scope.stb_box_id = [];
				console.log($scope.stb_box_id);
				$("#grid").find('input[type=checkbox]').each(function(){
					$(this).prop('checked', false);
				})
			}else{
				for(s in $scope.stb_cards){
					$scope.stb_box_id.push($scope.stb_cards[s].stb_box_id);
					$("#grid").find('input[type=checkbox]').each(function(){
						$(this).prop('checked', true);
					})
				}
				console.log($scope.stb_box_id);
			}
		};

		$scope.setItem = function(stbs){
			var index = $scope.stb_box_id.indexOf(stbs.stb_box_id);
			
			if(index == -1)
				$scope.stb_box_id.push(stbs.stb_box_id);
			else
				$scope.stb_box_id.splice(index,1);
		};

		$scope.isDisabled = function(){
			if(($scope.stb_box_id.length==0) || ($scope.lco_user_id == null))
				return true;
			else 
				return false;
		};

		var formValidation = function(){
			var regex = /^\d+$/;
			if(!regex.test($scope.stb_number)){
				$scope.warning_messages = 'Sorry! STB number should be numeric';
				return false;
			}

			return true;
		};

		$scope.searchSTB = function(){

			/*var isValid = formValidation();
			if(!isValid){
				return isValid;
			}*/

			// $scope.stb_cards = [];
			// var formData = {
			// 	stb_type_id : $scope.stb_type_id,
			// 	stb_number  : $scope.stb_number 
			// };
			// var http = WebService.post('lco/search_stb',formData);
			
			// $scope.$watch('stb_cards',function(val){
			// 	if(val.length){
			// 		$scope.loader = 1;
			// 	} else {
			// 		$scope.loader = 0;
			// 	}
			// });

			// http.then(function(response){
			// 	var data = response.data;
			// 	if(data.status == 200){
			// 		$scope.stb_cards = data.cards;
			// 		generateKendoGird($scope.stb_cards);
			// 	}
			// });
	if ($scope.stb_type_id || $scope.stb_number) {
		$scope.url = "lco-assign-stb/search_stb?stb_type_id="+$scope.stb_type_id+"&smartcard_number="+$scope.stb_number;
	}
	else if ($scope.stb_type_id && $scope.stb_number) {
		$scope.url = "lco-assign-stb/search_stb?stb_type_id="+$scope.stb_type_id+"&smartcard_number="+$scope.stb_number;
	}
	else{
		$scope.url = "lco-assign-stb/search_stb";
	}
	if($scope.gridFistTime){
		generateKendoGird();
		$scope.gridFistTime = false;
	}else{
		reloadGridWithNewData("#grid", "lco-assign-stb/search_stb?stb_type_id="+$scope.stb_type_id+"&stb_number="+$scope.stb_number);
	}
};


var generateKendoGird = function(data){
	$scope.mainGridOptions = {
		dataSource: {
			schema: {
				data: function (data) {
					$scope.stb_cards = data.cards.data;
					return data.cards.data;
				},
				total: "cards.total"
			},
			transport: {
				read: {
					url: $scope.url, 
					dataType: "json",
				},
				cache: false
			},
			pageSize: 10,
			serverPaging: true,
			serverFiltering: true
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
		{field: "", title: "",headerTemplate: "<input type='checkbox' ng-click='selectAllItem()' /> All", template:"<input type='checkbox' ng-checked='stb_box_id.indexOf(#=data.stb_box_id#) != -1' ng-click='setItem(#=JSON.stringify(data)#)' ng-value='#=data.stb_box_id#'/>"},
		{field: "external_card_number", title: "External Number",width: "auto"},
		{field: "supplier", title: "Provider",width: "auto"},
		{field: "description", title: "Description",width: "auto"},
		{field: "created_date", title: "Created Date"}
		]
	};

	        // reloadGrid("#stb-list");
	    };


	    $scope.saveLcoStb = function(){
	    	$scope.closeAlert();
	    	var formData = {
	    		lco_user_id : $scope.lco_user_id,
	    		cards: $scope.stb_box_id
	    	};
			if($scope.permissions.create_permission != '1'){
				$scope.warning_messages = "Sorry! You don't have permission to assign STB to LCO";
				return;
			}

	    	var http = WebService.post('lco-assign-stb/assign_stb_to_lco',formData);
	    	http.then(function(response){
	    		var data = response.data;
	    		
	    		if(data.status == 400){
	    			$scope.warning_messages = data.warning_messages;
	    			$scope.success_messages = '';
	    		} else {
	    			$scope.stb_box_id = [];
	    			$scope.success_messages = data.success_messages;
	    			$scope.warning_messages = '';
	    			$scope.searchSTB();
	    			$scope.loadNotification();
	    			reloadGrid("#grid");
	    		}
	    		
	    	});
	    };

		var loadPermissions = function(){
			var http = WebService.get('lco-assign-stb/ajax_get_permissions');
			http.then(function(response){
				var data = response.data;
				if(data.status == 200){
					$scope.permissions=data.permissions;
				}
			});
		};

		loadPermissions();

	    loadLco();

	}]);