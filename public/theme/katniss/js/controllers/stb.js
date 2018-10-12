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

app.controller('CreateStb',['$scope','WebService','FileUploader',function($scope,WebService,FileUploader){

	$scope.items = [];
	$scope.stb = {id:'',internal_card_number:'',external_card_number:'',stb_card_provider:'',price:''};
	$scope.uploader = null;
	$scope.showFrm = 0;
	$scope.fileUploadProgress = 0;
	$scope.$watch('stb.external_card_number', function(exCardNum){
		if(exCardNum.length < 16)
		{
			$scope.stb.internal_card_number = '';
		}
		else if(exCardNum.length == 16)
		{
			$scope.stb.internal_card_number = exCardNum.substring(13, 15);
			$scope.warning_messages = 0;
		}
		else if(exCardNum.length > 16){
			$scope.warning_messages = 'Not More Than 16 Digits';
		}
	});

	// File upload section
	var uploader = $scope.uploader = new FileUploader({
        url: 'set-top-box/import-stb',
        method:'post'

    });

    // CALLBACKS

    uploader.onWhenAddingFileFailed = function(item /*{File|FileLikeObject}*/, filter, options) {
        // console.info('onWhenAddingFileFailed', item, filter, options);
    };
    uploader.onAfterAddingFile = function(fileItem) {
		uploader.progress = 0;
		$scope.fileUploadProgress = 0;
    };
    uploader.onAfterAddingAll = function(addedFileItems) {
        // console.info('onAfterAddingAll', addedFileItems);
    };
    uploader.onBeforeUploadItem = function(item) {
		uploader.progress = 0;
		$scope.fileUploadProgress = 0;
    	$scope.warning_messages = "Please Don't refresh your browser.";
        // console.info('onBeforeUploadItem', item);
    };
    uploader.onProgressItem = function(fileItem, progress) {
		$scope.fileUploadProgress =  progress;
        // console.info('onProgressItem', fileItem, progress);
    };
    uploader.onProgressAll = function(progress) {
    	
        // console.info('onProgressAll', progress);
    };
    uploader.onSuccessItem = function(fileItem, response, status, headers) {
    	$scope.closeAlert();
    	if(response.status == 400){

    		$scope.warning_messages = response.warning_messages;
			$scope.success_messages = '';
			uploader.progress = 0;
			$scope.fileUploadProgress = 0;
    	}

    	if(response.status == 200){

    		$scope.success_messages = response.success_messages;
			$scope.warning_messages = '';
			uploader.progress = 0;
			$scope.fileUploadProgress = 0;
    	}
		clearFileInputField('fileValue');
    	//window.open("program",'_self',false);
    	// $scope.uploadReasult(response);
    };

	var clearFileInputField = function(tagId) {
		var fileValue = document.getElementById(tagId);

		fileValue.innerHTML = '';
		var fileObj = document.getElementById('file');
		fileObj.value="";
	};

    uploader.onErrorItem = function(fileItem, response, status, headers) {
        // console.info('onErrorItem', fileItem, response, status, headers);
    };
    uploader.onCancelItem = function(fileItem, response, status, headers) {
        // console.info('onCancelItem', fileItem, response, status, headers);
    };
    uploader.onCompleteItem = function(fileItem, response, status, headers) {
        // console.info('onCompleteItem', fileItem, response, status, headers);
    };
    uploader.onCompleteAll = function(response) {
    	
        // console.info('onCompleteAll');
    };


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
		if($scope.permissions.create_permission == '1'){
			$scope.showFrm = 1;
		}else{
			$scope.warning_messages = "Sorry! You don't have permission to create Set-top Box";
		}

	};

	var formValidation = function(){
		var regex = /^\d+$/;
		if(!regex.test($scope.stb.external_card_number)){
			$scope.warning_messages = 'Sorry! External Card Number should be numeric';
			return false;
		}

		if(!regex.test($scope.stb.price)){
			$scope.warning_messages = 'Sorry! Price should be numeric';
			return false;
		}

		return true;
	};

	$scope.saveStb = function(){
		var isValid = formValidation();
		if(!isValid){
			return isValid;
		}

		if($scope.permissions.create_permission != '1'){
			$scope.warning_messages = "Sorry! You don't have permission to create Set-top Box";
			return;
		}
		var http = WebService.post('set-top-box/create',$scope.stb);
		http.then(function(response){
			var data = response.data;

			if(data.status == 400){
				$scope.warning_messages = data.warning_messages;
				$scope.success_messages = '';
			} else {
				$scope.stb = {id:'',internal_card_number:'',external_card_number:'',stb_card_provider:'',price:''};
				$scope.stbAdd.$setUntouched();
				$scope.success_messages = data.success_messages;
				$scope.warning_messages = '';
				$scope.showFrm = 0;
				
				loadStb("reload");
				$scope.loadNotification();
				// reloadGrid("#grid");

			}
			$("html,body").animate({"scrollTop":"0px"});
		});
	};


	var loadStb = function(){
		generateKendoGird();
		// var http = WebService.get('settop_box/ajax_load_stb');
		// http.then(function(response){
		// 	var data = response.data;
		// 	if(data.status == 200){
		// 		$scope.items = data.stb;
		// 		console.log("After Call Reload");
		// 		// reloadGrid("#stp-grid");
		// 	}
		// });
	};

	var generateKendoGird = function(){
		$scope.mainGridOptions = {

			dataSource: {
                schema: {
                    data: "stb.data",
                    total: "stb.total"
                },
                transport: {
                    read: {
                        url: "set-top-box/ajax_load_stb",
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
	            //{field: "internal_card_number", title: "Internal Number",width: "auto"},
	            {field: "external_card_number", title: "External Number",width: "auto"},
	            {field: "stb_provider", title: "Provider",width: "auto"},
	            {field: "price",title: 'Price'},
	            {field: "is_used",title:'Status',template:'# if(data.is_used==0) {# <span class="label label-success">Not Used</span> #} else {# <span class="label label-danger">Used</span> #}#'},
	            {field: "", title: "Action",width: "auto",template:"<a href='"+SITE_URL+"set-top-box/view/#=data.id#' class='btn btn-default btn-xs' data-toggle='tooltip' data-placement='left' title='View'><i class='fa fa-search'></i></a> <a ng-if='permissions.edit_permission==\"1\"' href='"+SITE_URL+"set-top-box/edit/#=data.id#' class='btn btn-default btn-xs' data-toggle='tooltip' data-placement='left' title='Update'><i class='fa fa-pencil'></i></a>"},
            ]
        };
    };

	var loadPermissions = function(){
		var http = WebService.get('set-top-box/ajax_get_permissions');
		http.then(function(response){
			var data = response.data;
			if(data.status == 200){
				$scope.permissions=data.permissions;
			}
		});
	};

	loadPermissions();

    loadStb();

}]);
