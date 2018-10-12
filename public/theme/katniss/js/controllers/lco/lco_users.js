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
app.controller('LcoUsers',['$scope','WebService',function($scope,WebService){


	var loadLco = function(){
		var http = WebService.get('lco-users/ajax_load_assign_stb_data');
		http.then(function(response){
			var data = response.data;
			if(data.status = 200){

				$scope.lco_profiles = data.lco_profile,
				$scope.stb_types = data.stb_type
			}
		});
	};

	$scope.searchLcoStaff = function()
	{
		reloadGridWithNewData("#grid", "lco-users/ajax_load_staff_profiles?lco_id="+$scope.lco_user_id);
	};

	var generateKendoGird = function(){
		$scope.mainGridOptions = {
			dataSource: {
                transport: {
                    read: {
                        url: "lco-users/ajax_load_staff_profiles",
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
            {field: "lco_name", title: "Name",width: "auto"},
                {field: "username", title: "Username",width: "auto"},
                {field: "email", title: "E-mail",width: "auto"},
                {field: "status", title: "Status",filterable:false,sortable:false,template: '# if(data.user_status==1) {# <span class="label label-success">Active</span> #} else {# <span class="label label-danger">Inactive</span> #}#'},
                {field: "", title: "Action",width: "auto",template:"<a href='"+SITE_URL+"lco/view/#=data.token#' class='btn btn-default btn-xs' data-toggle='tooltip' data-placement='left' title='View'><i class='fa fa-search'></i></a>"},
            ]
		};

	        
    };

    generateKendoGird();
	loadLco();

}]);
	