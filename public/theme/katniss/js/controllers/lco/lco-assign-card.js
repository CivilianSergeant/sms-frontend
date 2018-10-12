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
app.controller('AssignSmartCard',['$scope','WebService',function($scope,WebService){
	
	$scope.lco_profiles = [];
	$scope.stb_types = [];
	$scope.lco = [];
	$scope.smart_cards = [];
	$scope.smart_card_id = [];
	$scope.loader = 1;
	$scope.permissions = [];

	$scope.stb_type_id = '';
	$scope.smartcard_number = '';

	$scope.gridFistTime = true;



	 // CheckBox

    // $scope.selectAllItem = function (){
    //     $("#grid").find('input[type=checkbox]').each(function(){
    //         $(this).prop('checked', false);
    //     });
    // }

    var loadLco = function(){
    	var http = WebService.get('lco-assign-card/ajax_load_assign_smartcard_data');
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


		// console.log($scope.smart_card_id.length);
		

		if($scope.smart_card_id.length > 0)
		{
			$scope.smart_card_id = [];
			console.log($scope.smart_card_id);
			$("#grid").find('input[type=checkbox]').each(function(){
				$(this).prop('checked', false);
			})
		}else{
			for(s in $scope.smart_cards){
				$scope.smart_card_id.push($scope.smart_cards[s].smart_card_id);
				$("#grid").find('input[type=checkbox]').each(function(){
					$(this).prop('checked', true);
				})
			}

			console.log($scope.smart_card_id);
		}


	};

	$scope.setItem = function(smartcard){
		var index = $scope.smart_card_id.indexOf(smartcard.smart_card_id);
		
		if(index == -1)
			$scope.smart_card_id.push(smartcard.smart_card_id);
		else
			$scope.smart_card_id.splice(index,1);
	};

	$scope.isDisabled = function(){
		if(($scope.smart_card_id.length==0) || ($scope.lco_user_id == null))
			return true;
		else 
			return false;
	};

	var formValidation = function(){
		var regex = /^\d+$/;
		if(!regex.test($scope.smartcard_number)){
			$scope.warning_messages = 'Sorry! Smartcard number should be numeric';
			return false;
		}

		return true;
	};

	$scope.searchSmartCard = function(){

		/*var isValid = formValidation();
		if(!isValid){
			return isValid;
		}*/

		/*$scope.smart_cards = [];
		var formData = {
			stb_type_id : $scope.stb_type_id,
			smartcard_number  : $scope.smartcard_number 
		};
		var http = WebService.post('lco/search_smartcard',formData);
		
		$scope.$watch('smart_cards',function(val){
			if(val.length){
				$scope.loader = 1;
			} else {
				$scope.loader = 0;
			}
		});

		http.then(function(response){
			var data = response.data;
			if(data.status == 200){
				$scope.smart_cards = data.cards;
			}
		});*/
if ($scope.stb_type_id || $scope.smartcard_number) {
	$scope.url = "lco-assign-card/search_smartcard?stb_type_id="+$scope.stb_type_id+"&smartcard_number="+$scope.smartcard_number;
}
else if ($scope.stb_type_id && $scope.smartcard_number) {
	$scope.url = "lco-assign-card/search_smartcard?stb_type_id="+$scope.stb_type_id+"&smartcard_number="+$scope.smartcard_number;
}
else{
	$scope.url = "lco-assign-card/search_smartcard";
}

if($scope.gridFistTime){
	generateKendoGird();
	$scope.gridFistTime = false;
}else{
	reloadGridWithNewData("#grid", "lco-assign-card/search_smartcard?stb_type_id="+$scope.stb_type_id+"&smartcard_number="+$scope.smartcard_number);
}

};

var generateKendoGird = function(data){
	$scope.mainGridOptions = {
		dataSource: {
			schema: {
				data: function (data) {
					$scope.smart_cards = data.cards.data;
					return data.cards.data;
				},
				total: "cards.total"
			},

			transport: {
				read: {
					dataType: "json",
					url: "lco-assign-card/search_smartcard?stb_type_id="+$scope.stb_type_id+"&smartcard_number="+$scope.smartcard_number,
					success: function (data) {
						alert(data);
					},
					error: function (xhr, error) {
						console.debug(xhr); console.debug(error);
						alert('error');
					}
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
		{field: "", title: "",headerTemplate: "<input type='checkbox' ng-click='selectAllItem()' /> All", template:"<input type='checkbox' ng-checked='smart_card_id.indexOf(#=data.smart_card_id#) != -1' ng-click='setItem(#=JSON.stringify(data)#)' ng-value='#=data.smart_card_id#'/>"},
		{field: "external_card_number", title: "Card Number",width: "auto"},
		{field: "supplier", title: "Provider",width: "auto"},
		{field: "description", title: "Description",width: "auto"},
		{field: "created_date", title: "Created Date"}
		]
	};

};

$scope.saveLcoSmartCard = function(){
	$scope.closeAlert();
	var formData = {
		lco_user_id : $scope.lco_user_id,
		cards: $scope.smart_card_id
	};

	if($scope.permissions.create_permission != '1'){
		$scope.warning_messages = "Sorry! You don't have permission to assign SmartCard to LCO";
		return;
	}

	var http = WebService.post('lco-assign-card/assign_smartcard_to_lco',formData);
	http.then(function(response){
		var data = response.data;
		
		if(data.status == 400){
			$scope.warning_messages = data.warning_messages;
			$scope.success_messages = '';
		} else {
			$scope.stb_box_id = [];
			$scope.success_messages = data.success_messages;
			$scope.warning_messages = '';
			$scope.searchSmartCard();
			$scope.loadNotification();
			reloadGrid("#grid");
		}
		
	});
};


	var loadPermissions = function(){
		var http = WebService.get('lco-assign-card/ajax_get_permissions');
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