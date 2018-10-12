var app = angular.module('plaasApp');
app.factory('WebService',function($http){
	return {
		post:function(url,data){
			return $http({
				method:"POST",
				url : SITE_URL+url,
				headers:{'X-Requested-With':'XMLHttpRequest',
						 'Content-Type': 'application/x-www-form-urlencoded'},
				data:$.param(data)
			});
		},
		get:function(url){
			return $http({
				method:"POST",
				url : SITE_URL+url,
				headers:{'X-Requested-With':'XMLHttpRequest',
						 'Content-Type': 'application/x-www-form-urlencoded'},
			});
		}
	};
});

app.controller('CreateRegion',['$scope','WebService',function($scope,WebService){

	angular.element('input[type=text]').focus();
	$scope.items = [];
	$scope.permissions = [];
	$scope.form = {id:'',name:'',childs:[]};
	$scope.item = {id:'',region_id:'',name:'',childItemName:'',childs:[],level:''};
	$scope.rootFrm = 0;
	$scope.showItem = function(i,j,k,l){
		if($scope.item != null)
			$scope.item.create_form=0;
		resetItem();
		selectItem(i,j,k,l);
		
	};

	var selectItem = function(i,j,k,l){

		if(i != null && j == null && k == null && l==null){
			$scope.item = $scope.items[i];
		    $("#L1Form input[type=text]").focus();
			$scope.item.level = 1;
		}else if(i != null && j != null && k == null && l==null){
			var L1 = $scope.items[i];
			$scope.item = L1.childs[j];
			$scope.item.level = 2;
			$("#L2Form input[type=text]").focus();
		}
		else if(i != null && j != null && k != null && l==null){
			var L1 = $scope.items[i];
			var L2 = L1.childs[j];
			var L3 = L2.childs[k];
			$scope.item = L3;
			$scope.item.level = 3;
		}
		else if(i != null && j != null && k != null && l != null){
			var L1 = $scope.items[i];
			var L2 = L1.childs[j];
			var L3 = L2.childs[k];
			var L4 = L3.childs[l];
			$scope.item = L4;
			$scope.item.level = 4;
		}
	}

	
	$scope.addItem = function(){
		
		if($scope.form.name != "")
		{
			var http = WebService.post('region/create',$scope.form);
			http.then(function(response){
				var data = response.data;

				if(data.status == 400){
					$scope.warning_messages = data.warning_messages;
				}else if(data.status == 200){
					$scope.success_messages = data.success_messages;
					loadTree();
					$scope.rootFrm = 0;
				}


			});
			
		}	
		resetItem();
	};

	$scope.addChildItem = function(i,j,k,l){
		selectItem(i,j,k,l);

		if($scope.item.childItemName != "")
		{
			i = (i != undefined)? i : 0;
			j = (j != undefined)? j : 0;
			k = (k != undefined)? k : 0;
			l = (l != undefined)? l : 0;
			var form = {id:'',name:$scope.item.childItemName,childs:[],region_id:i+'-'+j+'-'+k+'-'+l}
			var http = WebService.post('region/create',form);
			http.then(function(response){
				var data = response.data;
				if(data.status == 400){
					$scope.warning_messages = data.warning_messages;
				}
				loadTree();
				loadNotification();
			});
			
		}	
		resetItem();
	};


	$scope.updateItem = function(i,j,k,l){
		selectItem(i,j,k,l);
		if($scope.item.name != "")
		{
			i = (i != undefined)? i : 0;
			j = (j != undefined)? j : 0;
			k = (k != undefined)? k : 0;
			l = (l != undefined)? l : 0;
			
			var http = WebService.post('region/update',$scope.item);
			http.then(function(response){
				var data = response.data;
				if(data.status == 400){
					$scope.warning_messages = data.warning_messages;
				}
				loadTree();
				loadNotification();
			});
		}
		resetItem();
	};


	$scope.showChildForm = function(i,j,k,l){
		/*if($scope.item){
			$scope.item = {id:'',region_id:'',name:'',childItemName:'',childs:[],level:'',create_form:0};
			//$scope.item.=0;
		}
		$scope.rootFrm=0;*/
		selectItem(i,j,k,l);

		if($scope.permissions.create_permission == "1"){
			$scope.item.create_form = 1;
		}
	};

	$scope.showUpdateForm = function(i,j,k,l){
		resetItem();
		selectItem(i,j,k,l);
		if($scope.permissions.edit_permission == "1") {
			$scope.item.flag = 1;
		}
	}

	$scope.hideForm = function(){
		resetItem();
	};

	$scope.refresh = function(){
		resetItem();
		$scope.items = [];
		$scope.rootFrm=0;
		loadTree();
	};

	$scope.closeAlert = function(){
		$scope.warning_messages = '';
	};

	var resetItem = function(){
		$scope.item = {id:'',region_id:'',name:'',childItemName:'',childs:[]};
		$scope.form  = {id:'',name:'',childs:[]};
		if($scope.item != null)
		{
			$scope.item.flag=0;
			$scope.item.create_form=0;
			$scope.item.childItemName='';
			$scope.item.level='';
		}	
	};

	var loadTree = function(){
		var http = WebService.get('region/load_tree');
		http.then(function(response){
			var data = response.data;
			if(data.status == 200){
				$scope.items = data.regions;
				$scope.permissions = data.permissions;
			}
			//$scope.items = response.data;
		});
	}

	$scope.showRootFrm = function(){
		/*if($scope.item != null){
			$scope.item = {id:'',region_id:'',name:'',childItemName:'',childs:[],level:'',create_form:0};
			//$scope.item.create_form=0;
		}*/
		$scope.rootFrm=1;
	}

	loadTree();

}]);