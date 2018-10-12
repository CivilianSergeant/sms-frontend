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
app.controller('LcoSubscribers',['$scope','WebService',function($scope,WebService){

	$scope.filters = [{name:'None',value:'none'},
		{name:'Zero Balance',value:'zero-balance'},
		{name:'No Package',value:'no-package'},
		{name:'Active',value:'active'},
		{name:'Expired',value:'expired'}];

	var loadLco = function(){
		if(user_type == 'MSO'){
			var http = WebService.get('lco-subscribers/ajax-load-lco/1/MSO');
		}else{
			var http = WebService.get('lco-subscribers/ajax-load-lco/'+id+'/'+user_type);
		}

		http.then(function(response){
			var data = response.data;
			if(data.status == 200){
				$scope.lco_profiles = data.lco_profile
			}
		});
	};

	var loadGroups = function(){
		var http = WebService.get('lco-subscribers/ajax-load-groups');
		http.then(function(response){
			var data = response.data;
			if(data.status == 200){
				$scope.group_profiles = data.group_profiles;
			}
		});
	};

	$scope.searchLcoStaff = function()
	{
		reloadGridWithNewData("#grid", SITE_URL+"lco-subscribers/ajax_load_subscriber_profiles?lco_id="+$scope.lco_user_id+'&search='+$scope.filter);
	};

	$scope.closeAlert = function(){
		$scope.success_messages = '';
		$scope.warning_messages = '';
		$scope.error_messages = '';
	};

	$scope.downloadSubscriberList = function(){

		/*if($scope.lco_user_id == undefined || $scope.lco_user_id == 0 || $scope.lco_user_id == null){
		 $scope.warning_messages = 'Please Select LCO To Download';
		 $("html,body").animate({scrollTop:'0px'});
		 return false;
		 }*/
		window.location = SITE_URL+'lco-subscribers/download/'+$scope.filter+'/'+$scope.lco_user_id;
	};

	$scope.loadGroupLco = function()
	{
		var id = $scope.group_user_id;
		var user_type = 'MSO';
		if(id > 1){
			user_type = 'Group';
		}
		$scope.lco_user_id = null;
		var http = WebService.get('lco-subscribers/ajax-load-lco/'+id+'/'+user_type);
		http.then(function(response){
			var data = response.data;
			if(data.status == 200){
				$scope.lco_profiles = data.lco_profile

			}
		});
	}

	var generateKendoGird = function(){
		$scope.mainGridOptions = {
			dataSource: {
				transport: {
					read: {
						url: SITE_URL+"lco-subscribers/ajax_load_subscriber_profiles",
						dataType: "json",
					}
				},
				schema: {
					data: "profiles",
					total: "total"
				},

				pageSize: 10,
				serverPaging: true,
				serverSorting:true,
				serverFiltering: true,
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
				{field: "subscriber_name", title: "Name",width: "200px"},
				/*{field: "username", title: "Username",width: "auto"},
				 {field: "email", title: "E-mail",width: "auto"},*/
				{field: "parentName",title:"Parent Name",width:"200px",filterable:false},
				{field: "total_stb",title:"Total STB", headerAttributes:{"style":"text-align:center;"},attributes: {"class": "text-center"}, width:"90px",filterable:false},
				{field: "total_packages", title:"Packages",headerAttributes:{"style":"text-align:center;"},attributes: {"class": "text-center"}, width:"90px",filterable:false,template:'# if(data.total_packages <= 0){# <span>0</span> #} else {# #=data.total_packages# #}#'},
				{field:"total_payable",title:"Total Payable",headerAttributes:{"style":"text-align:center;"},attributes: {"class": "text-center"},width:"120px",filterable:false,template:'# if(data.total_payable <= 0){# <span>0</span> #} else {# #=data.total_payable# #}#'},
				{field: "balance",title:"Balance",headerAttributes:{"style":"text-align:center;"}, attributes: {"class": "text-center"},width:"auto",filterable:false,template:'# if(data.balance <= 0){# <span>0</span> #} else {# #=data.balance# #}#'},
				{field: "subscription", title:"Subscription",headerAttributes:{"style":"text-align:center;"},attributes: {"class": "text-center"}, width:"auto",filterable:false,template: '# if(data.subscription==null) {# <span class="label label-success">Active</span> #} else {# <span class="label label-danger"><a style="color:white;" href="'+SITE_URL+'lco-subscribers/expired-packages/#=data.token#">Expired</a></span> #}#'},
				{field: "status", title: "Status",width:"auto",headerAttributes:{"style":"text-align:center;"},attributes: {"class": "text-center"},filterable:false,sortable:false,template: '# if(data.user_status==1) {# <span class="label label-success">Active</span> #} else {# <span class="label label-danger">Inactive</span> #}#'},
				{field: "", title: "Action",width: "auto",template:"<a href='"+SITE_URL+"lco-subscribers/view/#=data.token#' class='btn btn-default btn-xs' data-toggle='tooltip' data-placement='left' title='View'><i class='fa fa-search'></i></a>"},
			]
		};
	};

	generateKendoGird();
	loadGroups();
	loadLco();


}]);
	