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
app.controller('Billing',['$scope','WebService',function($scope,WebService){
	
	$scope.cash = {id:'',money_receipt:'',subscriber_id:'',pairing_id:'',stb_card_id:'',receive_date:'', amount:'', discount:'', vat_amount:'', total_amount:'', collector_id:''};
	$scope.cash.amount = $scope.cash.discount = $scope.cash.vat_amount = 0;
	$scope.stb_card_pairs = [];
	$scope.subscriber_id = subscriber_id;
	$scope.closeAlert = function(){
		$scope.success_messages = '';
		$scope.warning_messages = '';
		$scope.error_messages = '';
	};

	$scope.hideForm = function(){
		$scope.showFrm = 0;
	};

	$scope.showForm = function()
	{
		$scope.showFrm = 1;
	};

	$scope.setStbCardId = function()
	{

		$scope.stb_card_pairs.filter(function(obj){
			if($scope.cash.stb_card_id == obj.id)
			{
				$scope.cash.pairing_id = obj.pairing_id;
			}
			
		});
	};

	$scope.setSubscriber = function()
	{
		$scope.subscriber.filter(function(obj){
			if(obj.id==$scope.subscriber_id)
			{
				$scope.cash.subscriber_id = obj.id;
				$scope.getPairingId();
			}
		});
	};

	$scope.getPairingId = function()
	{
		var http = WebService.post('cash/pairing_id',{subscriber_id:$scope.cash.subscriber_id});
		http.then(function(response){
			var data = response.data;
			if(data.status == 200)
			{
				$scope.stb_card_pairs = data.stb_card_pairs;

				$scope.pair_row_id = [];
				angular.forEach($scope.stb_card_pairs, function(value, key){
					var temp = {};
					temp['pair_id'] = value.pairing_id;
					temp['row_id'] = value.id;
					$scope.pair_row_id[key] = temp;
					
				});

			}
		});
	};

	var loadSubscriberLists = function()
	{
		var http = WebService.get('cash/ajax_load_subscribers');
		http.then(function(response){
			
			var data = response.data;
			if(data.status == 200)
			{
				$scope.all_subscribers = data.all_subscribers;
				// console.log($scope.all_subscribers[0].subscriber_name);
				$scope.subscriber = [];
				angular.forEach($scope.all_subscribers, function(value, key){
					var temp = {};
					temp['name'] = value.subscriber_name;
					temp['id'] = value.user_id;
					$scope.subscriber[key] = temp;
					
				});
				$scope.setSubscriber();
			}
		});
	};

	loadSubscriberLists();

	var formValidation = function(){
		var regex = /^\d+$/;
		if(!regex.test($scope.cash.money_receipt)){
			$scope.warning_messages = 'Sorry! Money Receipt Number should be numeric';
			return false;
		}

		return true;
	};

	$scope.saveCashReceive = function(){
		var isValid = formValidation();
		if(!isValid){
			return isValid;
		}

		//console.log($scope.cash);
		var http = WebService.post('cash/save_cash_receive',$scope.cash);
		http.then(function(response){
			var data = response.data;
			//console.log(data);
			if(data.status == 400){
				$scope.warning_messages = data.warning_messages;
				$scope.success_messages = '';
				$("html,body").animate({scrollTop:"0px"});
			} else {
				$scope.success_messages = data.success_messages;
				$scope.warning_messages = '';
				$scope.loadNotification();
				//$scope.cash = {};
				//console.log(data);
				$("html,body").animate({scrollTop:"0px"}, function(){
					window.location = data.reditect_to;
				});			
			}
			
		});
	};

	$scope.$watch('[cash.amount, cash.discount, cash.vat_amount]', function(amount) {
    
		var amount = $scope.cash.amount;
		var discount = $scope.cash.discount;
		var vat_amount = $scope.cash.vat_amount;
		var amountLessDiscount = (eval(amount)-eval(discount));
		var vat_cal = (eval(amountLessDiscount) * eval(vat_amount)) / 100;
        $scope.cash.total_amount = (eval(vat_cal) + eval(amountLessDiscount));
        
   });

	 $scope.isDisabled = function(){

	 	if ($scope.cash.money_receipt == 0)
	 		return true;
	 	if (isNaN($scope.cash.subscriber_id))
	 		return true;
	 	if ($scope.cash.stb_card_id == 0)
	 		return true;
	 	if ($scope.cash.receive_date == 0)
	 		return true;
	 	if ($scope.cash.amount == 0)
	 		return true;
	 	// if ($scope.cash.discount == 0)
	 	// 	return true;
	 	// if ($scope.cash.vat_amount == 0)
	 	// 	return true;
	 	if ($scope.cash.collector_id == 0)
	 		return true;

	 	return false;

	 };

}]);



app.controller('Charge',['$scope','WebService',function($scope,WebService){
	$scope.token = token;
	$scope.pair_id = pair_id;
	$scope.formData = {};
	$scope.assigned_packages = [];
	$scope.formData.charge_type = 2;

	var loadAssignedPackages = function(){
		var formData = {token: $scope.token, pair_id: (($scope.pair_id != null)? $scope.pair_id : null)};
		var http = WebService.post('cash/ajax_get_assigned_packages',formData);
		http.then(function(response){
			var data = response.data;
			if(data.status == 200){
				$scope.assigned_packages = data.assigned_packages;
			} 


		});
	};


	var loadBalance = function(){
        var http = WebService.post('cash/ajax-get-subscriber-balance',{token:$scope.token});
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                 $scope.balance = data.balance;
            } 
               
            
        });
    };

    $scope.charge = function(index)
    {
    	 
    	var item = $scope.assigned_packages[index];
    	$scope.formData =item;

    	
    };

    $scope.closeAlert = function()
    {
    	$scope.warning_messages = $scope.success_messages = $scope.error_messages = '';
    };

    $scope.cancel = function()
    {
    	$scope.formData = {};
    	$scope.closeAlert();
    };

    $scope.confirm = function()
    {
    	
    	/*if($scope.formData.charge_type == 1){
    		$scope.warning_messages = "Sorry! System under construction for Charge By Amount";
    		return;
    	}*/

    	/*if(eval($scope.balance) < eval($scope.formData.total_price))
    	{
    		$scope.warning_messages = "Sorry! Subscriber don't have sufficient balance to Charge Fee";
    		return;
    	}*/

		if($scope.formData.free_subscription_fee==0){
			if(eval($scope.balance) < eval($scope.formData.total_price) && $scope.formData.charge_type == 0){
				$scope.warning_messages = "Sorry! Subscriber don't have sufficient balance to Charge Fee";
				return;

			}else{

				if($scope.balance <= 0){
					$scope.warning_messages = "Sorry! Subscriber don't have sufficient balance to Charge Fee";
					return;
				}
			}

		}

    	//console.log($scope.formData,$scope.balance);
    	$scope.formData.token = $scope.token;
    	var http = WebService.post('subscriber/charge',$scope.formData);
    	http.then(function(response){
    		var data = response.data;
    		if(data.status == 200){
    			
    			$scope.success_messages = data.success_messages;
    			$scope.loadNotification();
    			$("html,body").animate({scrollTop:"0px"}, function(){
					window.location.reload();
				});
    		}else{
				$scope.warning_messages = data.warning_messages;
				$scope.success_messages = '';
			}
    	});

    };

    loadBalance();
	loadAssignedPackages();
	
}]);


