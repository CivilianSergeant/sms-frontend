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
app.controller('ScratchCardBatchInfo',['$scope','WebService', function($scope,WebService){

	$scope.showFrm = 0;
	$scope.card_info_id = card_info_id;
	$scope.card_no = '';
	$scope.serial_no = '';
	$scope.showDownloadFrm = 0;
	$scope.download ={};
	$scope.queryData = {};

	$scope.showLogin = function(){
		$scope.showDownloadFrm = 1;
	};

	$scope.hideLogin = function(){
		$scope.showDownloadFrm = 0;
	}

	$scope.downloadScratchCard = function(){
		$scope.closeAlert();
		$scope.download.batch_id = $scope.card_info_id;
		$scope.download.serial_no = ($scope.queryData.serial_no != undefined)? $scope.queryData.serial_no :'n/a';
		$scope.download.search_type = ($scope.queryData.search_type != undefined)? $scope.queryData.search_type : 'n/a';
		var http = WebService.post('scratch-card-generate/ajax-download-request',$scope.download);
		http.then(function(response){
			var data = response.data;
			if(data.status == 400){
				$scope.warning_messages = data.warning_messages;
			}else{
				window.location = data.download_url;
			}
		});
	};

	$scope.closeAlert = function(){
		$scope.warning_messages = '';
		$scope.error_messages = '';
		$scope.success_messages = '';
	};

	var loadCards = function(){
		generateKendoGird();
	};

	$scope.getCardByCardNoSerialNo = function(){
		var serial_no   = ($scope.queryData.serial_no != undefined)? $scope.queryData.serial_no : '';
		var search_type = ($scope.queryData.search_type != undefined)? $scope.queryData.search_type : '';

		var kendoGridObj = $("#cardInfoGrid").data("kendoGrid");
		kendoGridObj.dataSource.transport.options.read.url = SITE_URL+"scratch-card-generate/ajax-load-all-cards?card_info_id="+$scope.card_info_id+"&serial_no="+serial_no+"&search_type="+search_type;
		kendoGridObj.dataSource.read();
		kendoGridObj.refresh();
	};

	var generateKendoGird = function(){
		$scope.mainGridOptions = {
			dataSource: {

				schema: {
					data: "all_cards",
					total: "total"
				},

				transport: {
					read: {
						url: SITE_URL+"scratch-card-generate/ajax-load-all-cards?card_info_id="+$scope.card_info_id,
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
				{field: "serial_no", title: "Serial Number",width: "auto"},
				{field: "created_at", title: "Created Date", width: "auto"},
				{field: "is_used",title: "IS USED", width:"auto",filterable:false,template:'#if(data.is_used==1){# <span class="label label-danger">Used</span> #}else{# <span class="label label-success">Not Used</span> #}#'},
				{field: "is_suspended", title: "Is Suspend", width: "auto", template: "#if(data.is_suspended==1) {# <span class='text-danger'>Suspended</span> #} else {# <span class='text-success'>Not Suspended</span> #}#"},
				{field: "is_active", title: "Status", width: "auto", template: "#if(data.is_active==1) {# <span class='text-success'>Active</span> #} else {# <span class='text-danger'>Inactive</span> #}#"},
				{field: "", title: "Action",width: "auto",template:"<a href='"+SITE_URL+"scratch-card-generate/card-view/#=data.id#' class='btn btn-default btn-xs' data-toggle='tooltip' data-placement='left' title='View'><i class='fa fa-search'></i></a> <a ng-if='permissions.edit_permission == \"1\"' href='"+SITE_URL+"scratch-card-generate/card-edit/#=data.id#' class='btn btn-default btn-xs' data-toggle='tooltip' data-placement='left' title='Update'><i class='fa fa-pencil'></i></a>"},
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
app.controller('AvailableCardList',['$scope','WebService',function($scope,WebService){
	$scope.uri = uri;
	var generateKendoGird = function(){
		$scope.mainGridOptions = {
			dataSource: {

				schema: {
					data: "cards",
					total: "total"
				},

				transport: {
					read: {
						url: SITE_URL+uri+"/ajax-get-cards/"+from+"/"+to,
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
				{field: "serial_no", title: "Serial Number",width: "auto"},
				{field: "created_at", title: "Created Date", width: "auto"},
				{field: "is_used", title: "Is Used",width:"auto"},
				{field: "is_suspended", title: "Is Suspend", width: "auto", template: "#if(data.is_suspended==1) {# <span class='text-danger'>Suspended</span> #} else {# <span class='text-success'>Not Suspended</span> #}#"},
				{field: "is_active", title: "Status", width: "auto", template: "#if(data.is_active==1) {# <span class='text-success'>Active</span> #} else {# <span class='text-danger'>Inactive</span> #}#"},
				{field: "", title: "Action",width: "auto",template:"<a href='"+SITE_URL+uri+"/card-view/#=data.id#' class='btn btn-default btn-xs' data-toggle='tooltip' data-placement='left' title='View'><i class='fa fa-search'></i></a> <a ng-if='permissions.edit_permission == \"1\"' href='"+SITE_URL+"scratch-card-generate/card-edit/#=data.id#' class='btn btn-default btn-xs' data-toggle='tooltip' data-placement='left' title='Update'><i class='fa fa-pencil'></i></a>"},
			]
		};
	};

	generateKendoGird();
}]);