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
app.controller('notification',['$scope','$interval','WebService',function($scope,$interval,WebService){

	$scope.token = token;
	$scope.limit = 5;
	$scope.notification = [];
	$scope.offset = 0;

	$scope.closeAlert = function(){
		$scope.success_messages = '';
	};

	$scope.getPreviousMessage = function(){
		$scope.limit = $scope.$parent.countNotification;
		$scope.refresh();
	};


	var loadNotifications = function()
	{
		
		var http = WebService.get('notification/ajax_load_notifications?limit='+$scope.limit+'&offset='+$scope.offset);
		http.then(function(response){
			
			var data = response.data;
			if(data.status == 200)
			{
				$scope.notification = data.notification;
				
			}
				
		});
	};

	var loadNotificationInPopUp = function(){
  	
	  	var http = WebService.get('notification/ajax_load_popup_notifications');
	 	http.then(function(response){
		  	var data = response.data;
		  	if(data.status==200){
		  		$scope.$parent.notifications = data.messages;
		  		$scope.$parent.countNotification = data.count;
		  		
		  	}
	    });
    };


	$scope.delete = function(i){
			$scope.id = i;
			
			if($scope.notification != undefined || $scope.notification != null){

				var item = $scope.notification[i];
				
				var countNotification = $scope.$parent.countNotification;
				
				var http = WebService.post('notification/delete',{id:item.id});
				http.then(function(response){
					var data = response.data;
					if (data.status== 200) {
						$scope.success_messages = data.success_messages;
					}
					$scope.notification.splice(i,1);
					$scope.$parent.countNotification = (countNotification-1);
					
					loadNotificationInPopUp();

				});
			}
	};

	$scope.refresh = function(){
		$scope.closeAlert();
		loadNotifications();
		loadNotificationInPopUp();
	};

	$scope.deleteAll = function(){
		var http = WebService.post('notification/delete_all',{token:$scope.token});
		http.then(function(response){
			var data = response.data;
			if(data.status == 200){
				$scope.notification = [];
				$scope.$parent.countNotification = 0;
			}
			loadNotificationInPopUp();
		});
	};

	$scope.isLoadPreviousEnabled = function(){
		if(!$scope.notification.length){
			return false;
		}
		if($scope.$parent.countNotification > $scope.notification.length)
		{
			return true;
		}

		return false;
	};


	
	loadNotifications();

	


}]);
app.controller('notificationReport',['$scope','WebService',function($scope,WebService){

	$scope.formDate = {};

	var generateGrid = function() {


		$scope.mainGridOptions = {
			dataSource: {
				transport: {
					read: {
						url: "notification/ajax-get-report",
						dataType: "json",
					}
				},
				schema: {
					data: "reports",
					total: "total"
				},

				pageSize: 10,
				serverPaging: true,
				serverSorting: true,
				serverFiltering: true,
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
				{field: "title", title: "Title",width: "auto"},
				{field: "action_performed_by", title: "Action Performed By",width: "auto"},
				{field: "created_at", title: "Created Date",width: "auto",filterable:false},
				{field: "", title: "Action",width: "auto",template:"<a href='"+SITE_URL+"' class='btn btn-default btn-xs' data-toggle='tooltip' data-placement='left' title='View'><i class='fa fa-search'></i></a>"}
			]
		};
	};

	generateGrid();

	$scope.getReports = function()
	{
		var grid = $('#grid').data("kendoGrid");
		grid.dataSource.transport.options.read.url="notification/ajax-get-report?from_date="+$scope.formData.from_date+"&to_date="+$scope.formData.to_date,
		grid.dataSource.read();
		grid.refresh();
	};

	$scope.refresh = function(){
		window.location.reload();
	};

}]);
