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
app.controller('GenerateCard',['$scope','WebService', function($scope,WebService){

	$scope.card_data = {id:'', prefix:'', value:'', number_of_cards:'', active_from:''};
	$scope.showFrm = 0;
	$scope.permissions = [];

	console.log($scope.card_info_id);
	/*Form Field Validation*/
	$scope.$watch('card_data.prefix', function(prefix){
		if (prefix!= undefined && prefix.toString().length > 0) {
			if(prefix.match(/^[0-9]+$/) != null){
				if (prefix.length > 2) {
					$scope.saveCardAdd.$setUntouched();
					$scope.card_data.cardlength = '';
					$scope.warning_messages = 'Prefix Maximum 2 Digits';
				}else if (prefix.length == 2) {
					$scope.card_data.cardlength = prefix+"00000000000001";
					$scope.warning_messages = '';
				}else if (prefix.length == 1){
					$scope.saveCardAdd.$setUntouched();
					$scope.card_data.cardlength = '';
					$scope.warning_messages = 'Prefix Minimum 2 Digits';
				}
			} else{
				$scope.card_data.prefix = "";
				$scope.warning_messages = 'Prefix Number Only';
			}
		}
	});

	/*Form Field Validation*/

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
		if(!regex.test($scope.card_data.value)){
			$scope.warning_messages = 'Sorry! Value should be numeric';
			return false;
		}

		if(!regex.test($scope.card_data.number_of_cards)){
			$scope.warning_messages = 'Sorry! Number of Cards should be numeric';
			return false;
		}

		return true;
	};

	$scope.saveCards = function(){

		var isValid = formValidation();
		if(!isValid){
			return isValid;
		}

		var http = WebService.post('scratch-card-generate/save-cards',$scope.card_data);
		http.then(function(response){
			var data = response.data;
			if(data.status == 400){
				$scope.warning_messages = data.warning_messages;
				$scope.success_messages = '';
			} else {
				$scope.card_data = {};
				$scope.saveCardAdd.$setUntouched();
				$scope.success_messages = data.success_messages;
				$scope.warning_messages = '';
				$scope.showFrm = 0;
				$scope.loadNotification();
				loadCards();
			}
			$("html,body").animate({"scrollTop":"0px"});
		});
	};


	var loadCards = function(){
		generateKendoGird();
	};

	var generateKendoGird = function(data){
		$scope.mainGridOptions = {
			dataSource: {

				schema: {
					data: "cards",
					total: "total"
				},

				transport: {
					read: {
						url: SITE_URL+"scratch-card-generate/ajax-load-cards",
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

			dataBound: gridDataBound,

			columns: [
				{field: "batch_no", title: "Batch Number",width: "auto", template:"<a href='"+SITE_URL+"scratch-card-generate/scratch-card-batch-info/#=data.card_info_id#'>#=data.batch_no#</a>"},
				{field: "value", title: "Value",width: "100px"},
				{field: "number_of_cards", title: "Number Of Cards",width: "auto"},
				{field: "prefix", title: "Prefix", width: "100px"},
				{field: "distributed",title:"Distributed",width:"100px"},
				{field: "used",title:"Used",width:"100px"},
				{field: "unused",title:'Un-Used',width:"100px"},
				{field: "created_at", title: "Created At", width: "auto"},

			]
		};
	};

	loadCards();

	var loadPermissions = function(){
		var http = WebService.get('scratch-card-generate/ajax-get-permissions');
		http.then(function(response){
			var data = response.data;
			if(data.status == 200){
				$scope.permissions=data.permissions;
			}
		});
	};

	loadPermissions();

}]);
app.controller('AvailableScratchCard',['$scope','WebService',function($scope,WebService){
	$scope.token = token;
	$scope.user_type = user_type;

	var generateKendoGird = function(data){
		$scope.mainGridOptions = {
			dataSource: {

				schema: {
					data: "cards",
					total: "total"
				},

				transport: {
					read: {
						url: "scratch-card-available/ajax-load-cards",
						dataType: "json",
					}
				},
				pageSize: 10,
				serverPaging: true,
				serverFiltering: false
			},
			sortable: true,
			filterable: {
				extra: false,
				operators: {
					string: {
						startswith: "Starts with",
						eq: "Is equal to",

					}
				}
			},
			pageable: true,
			scrollable: true,
			resizable: true,

			dataBound: gridDataBound,

			/*columns: [
			 {field: "batch_no", title: "Batch Number",width: "auto"},
			 {field: "serial_no",title: "Serial No",width:"auto"},
			 {field: "value", title: "Value",width: "auto",filterable:false},
			 {field: "prefix", title: "Prefix", width: "auto",filterable:false},
			 {field: "date", title: "Date time", width: "auto",filterable:false}
			 ]*/
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
				{field: "", title:"Action",width:"auto",template:'<a href="'+SITE_URL+'scratch-card-available/detail/#=from_serial_no#/#=to_serial_no#" class="btn btn-default btn-xs">Show</a>'}

			]
		};

		if(user_type == 'Group'|| user_type == 'LCO'){
			var columns = $scope.mainGridOptions.columns;
			for(c in columns){
				if(columns[c].field == 'group_name'){
					columns.splice(c,1);
				}
				if(columns[c].field == 'lco_name'){
					columns.splice(c,1);
				}
			}
			$scope.mainGridOptions.columns = columns;
		}


	};

	generateKendoGird();
}]);