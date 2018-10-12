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
app.controller('CreateICSmartcard',['$scope','WebService','FileUploader',function($scope,WebService,FileUploader){

	$scope.items = [];
	$scope.ic_smartcard = {id:'',internal_card_number:'',external_card_number:'',smart_card_provider:'',price:''};
	$scope.showFrm = 0;
	$scope.uploader = null;
	$scope.$watch('ic_smartcard.external_card_number', function(exCardNum){
		//console.log(exCardNum);
		if(exCardNum.length < 16)
		{
			$scope.ic_smartcard.internal_card_number = '';
		}
		else if(exCardNum.length == 16)
		{
			var http = WebService.post('icsmart-card/testExNum',$scope.ic_smartcard);
			http.then(function(response){
				var data = response.data;
				if(data.status == 400){
					$scope.warning_messages = data.warning_messages;
					$scope.success_messages = '';
					$scope.ic_smartcard.internal_card_number = '';
				}
				else{
					$scope.ic_smartcard.internal_card_number = data.inter_card_num;
					$scope.warning_messages = '';
				}	
			});
		}
		else if(exCardNum.length > 16)
		{
			$scope.warning_messages = 'External Cardnumber Can Not be More Than 16 Digits';
		}
	});

	// File upload section
	var uploader = $scope.uploader = new FileUploader({
        url: 'icsmart-card/import',
        method:'post'

    });

    // CALLBACKS

    uploader.onWhenAddingFileFailed = function(item /*{File|FileLikeObject}*/, filter, options) {
        // console.info('onWhenAddingFileFailed', item, filter, options);
    };
    uploader.onAfterAddingFile = function(fileItem) {
		uploader.progress = 0;
		$scope.fileUploadProgress = 0;
		// console.info('onAfterAddingFile', fileItem);
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
			$scope.uploadView = false;
    		$scope.warning_messages = response.warning_messages;
			$scope.success_messages = '';
			uploader.progress = 0;
			$scope.fileUploadProgress = 0;
    	}

    	if(response.status == 200){
			$scope.uploadView = false;
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

	$scope.removeAlert = function(){
		$scope.success_messages = 0;
		$scope.warning_messages = 0;
		$scope.error_messages = 0;
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
			$scope.warning_messages = "Sorry! You don't have permission to create IC/SmartCard";
		}

	};

	var formValidation = function(){
		var regex = /^\d+$/;
		if(!regex.test($scope.ic_smartcard.external_card_number)){
			$scope.warning_messages = 'Sorry! External Card Number should be numeric';
			return false;
		}

		if(!regex.test($scope.ic_smartcard.price)){
			$scope.warning_messages = 'Sorry! Price should be numeric';
			return false;
		}

		return true;
	};

	$scope.saveICSmartcard = function(){
		var isValid = formValidation();
		if(!isValid){
			return isValid;
		}

		if($scope.permissions.create_permission != '1'){
			$scope.warning_messages = "Sorry! You don't have permission to create IC/SmartCard";
			return;
		}

		var http = WebService.post('icsmart-card/create',$scope.ic_smartcard);
		http.then(function(response){
			var data = response.data;
			console.log(data);
			if(data.status == 400){
				$scope.warning_messages = data.warning_messages;
				$scope.success_messages = '';
			} else {
				$scope.ic_smartcard = {id:'',internal_card_number:'',external_card_number:'',smart_card_provider:'',price:''};
				$scope.ic_smartcard = {};
				$scope.icAdd.$setUntouched();
				$scope.success_messages = data.success_messages;
				$scope.warning_messages = '';
				$scope.showFrm = 0;
				$scope.loadNotification();
				loadIcSmartcard("reload");
			}
			$("html,body").animate({"scrollTop":"0px"});
		});
	};


	var loadIcSmartcard = function(){
		generateKendoGird();
		
		/*$scope.$watch('items',function(val){
			if(val.length){
				$scope.loader = 0;
			} else {
				$scope.loader = 1;
			}
		});
		var http = WebService.get("icsmart_card/ajax_load_ic_smartcards");
		http.then(function(response){
			var data = response.data;
			if(data.status == 200){
				$scope.items = data.ic_smartcard;
				generateKendoGird($scope.items);
			}
		});*/
	};

	var generateKendoGird = function(data){
		$scope.mainGridOptions = {
			dataSource: {
           /* type: "jsonp",
            data:$scope.items,
            pageSize: 5,*/

            schema: {
            	data: "ic_smartcard",
            	total: "total"
            },
            pageSize: 10,
            transport: {
            	read: {
            		url: "icsmart-card/ajax_load_ic_smartcards",
            		dataType: "json",
            	}
            },
               // pageSize: 5,
               serverPaging: true,
               serverFiltering: false              
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
            {field: "internal_card_number", title: "Internal IC",width: "110px"},
            {field: "external_card_number", title: "External Number",width: "170px"},
            {field: "stb_provider", title: "Provider",width: "400px"},
            {field: "price", title: 'Price', width: "80px"},
            {field: "is_used",title:'Status', width: "90px", template:'# if(data.is_used==0) {# <span class="label label-success">Not Used</span> #} else {# <span class="label label-danger">Used</span> #}#'},
            {field: "", title: "Action",width: "auto",template:"<a href='"+SITE_URL+"icsmart-card/view/#=data.id#' class='btn btn-default btn-xs' data-toggle='tooltip' data-placement='left' title='View'><i class='fa fa-search'></i></a> <a ng-if='permissions.edit_permission == \"1\"' href='"+SITE_URL+"icsmart-card/edit/#=data.id#' class='btn btn-default btn-xs' data-toggle='tooltip' data-placement='left' title='Update'><i class='fa fa-pencil'></i></a>"},
            ]
        };
    };

	var loadPermissions = function(){
		var http = WebService.get('icsmart-card/ajax_get_permissions');
		http.then(function(response){
			var data = response.data;
			if(data.status == 200){
				$scope.permissions=data.permissions;
			}
		});
	};

	loadPermissions();

    loadIcSmartcard();

}]);