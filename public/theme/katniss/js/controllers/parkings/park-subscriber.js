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
app.controller('parkSubscriber',['$scope','WebService',function($scope,WebService){
    $scope.subscribers = [];
    $scope.pairings = [];
    $scope.parkings = [];

    $scope.closeAlert = function(){
        $scope.subscriber_messages = '';
        $scope.warning_messages = '';
        $scope.error_messages = '';
    };

    // load subscriber
    var loadSubscribers = function(){
        var http = WebService.get('park-subscriber/ajax-get-subscribers');
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.subscribers = data.subscribers;
            }
        });
    };

    $scope.loadPairings = function(){

        $scope.formData.pairing_id = null;
        if($scope.formData.subscriber_id !=null) {
            var http = WebService.get('park-subscriber/ajax-get-pairing-id/' + $scope.formData.subscriber_id);
            http.then(function (response) {
                var data = response.data;
                if (data.status == 200) {
                    $scope.pairings = data.pairings;
                }
            });
        }
    };

    $scope.addToList = function(){

        var formData = {
            subscriber_id : $scope.formData.subscriber_id,
            stb_card_id   : $scope.formData.pairing_id,

        };
        $scope.subscribers.filter(function(obj){
            if(obj.user_id == $scope.formData.subscriber_id){
                formData.subscriber_name = obj.subscriber_name;
            }
        });
        $scope.pairings.filter(function(obj){
            if(obj.id == $scope.formData.pairing_id){
                formData.pairing_id = obj.pairing_id;
            }
        });
        formData.id = '';
        var newDateObj = new Date();
        var year = newDateObj.getFullYear();
        var month = parseInt(newDateObj.getUTCMonth() + 1);
        month = (month<10)? '0'+month : month;

        var day = newDateObj.getDate();
        day = (day<10)? ('0'+day) : day;

        formData.parking_date = year+'-'+month+'-'+day;

        $scope.parkings.push(formData);
        $scope.formData.pairing_id = null;
    };

    $scope.confirmPark = function(i){
        $scope.closeAlert();
        var item = ($scope.parkings.length>0)? $scope.parkings[i] : null;
        if(item != null){
            var http = WebService.post('park-subscriber/park',item);
            http.then(function(response){
                var data = response.data;
                if(data.status == 200){
                    item.id = data.id;
                    $scope.pairings = data.pairings;
                    $scope.success_messages = data.success_messages;
                }else{
                    $scope.warnings = data.warnings;
                }
            });
        }
    };

    $scope.cancelPark = function(i){
        $scope.closeAlert();
        var item = ($scope.parkings.length>0)? $scope.parkings[i] : null;
        if(item != null){
            $scope.parkings.splice(i,1);
        }
    }


    // calling load Subscriber on load of the page
    loadSubscribers();
}]);
app.controller('assignFromParking',['$scope','WebService',function($scope,WebService){
    $scope.subscribers = [];
    $scope.parks = [];
    $scope.parkings = [];
    // load parks
    var loadParks = function(){
        var http = WebService.get('assign-from-parking/ajax-get-parks');
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.parks = data.parks;
            }
        });
    };

    // load subscriber
    var loadSubscribers = function(){
        var http = WebService.get('assign-from-parking/ajax-get-subscribers');
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.subscribers = data.subscribers;
            }
        });
    };

    $scope.addToList = function(){

        var formData = {};

        $scope.parks.filter(function(obj){
            if(obj.id == $scope.formData.pairing_id){
                formData = obj;
            }
        });

        $scope.subscribers.filter(function(obj){
            if(obj.user_id == $scope.formData.subscriber_id){
                formData.subscriber_name = obj.subscriber_name;
            }
        });

        formData.subscriber_id = $scope.formData.subscriber_id;


        //formData.id = '';
        var newDateObj = new Date();
        var year = newDateObj.getFullYear();
        var month = parseInt(newDateObj.getUTCMonth() + 1);
        month = (month<10)? '0'+month : month;

        var day = newDateObj.getDate();
        day = (day<10)? ('0'+day) : day;

        formData.parking_date = year+'-'+month+'-'+day;

        if(formData.pairing_id == null){
            $scope.warning_messages = 'Please Select Pairing ID';
            return;
        }
        if(formData.subscriber_id == null){
            $scope.warning_messages = 'Please Select Subscriber';
            return;
        }


        $scope.parkings.push(formData);
        $scope.closeAlert();
    };

    $scope.reassign = function(i){
        var item = ($scope.parkings.length>0)? $scope.parkings[i] : null;
        if(item != null){
            var http = WebService.post('assign-from-parking/reassign',item);
            http.then(function(response){
                var data = response.data;
                if(data.status == 200){
                    $scope.success_messages = data.success_messages;
                    $scope.parks = data.parks;
                    item.log_id = data.log_id;
                }else{
                    $scope.warning_messages = data.warning_messages;
                }
            });
        }
    };

    $scope.cancel = function(i){
        $scope.formData.pairing_id = null;
        $scope.formData.subscriber_id = null;
        var item = ($scope.parkings.length>0)? $scope.parkings[i] : null;
        if(item != null){
            $scope.parkings.splice(i,1);
        }
    };

    $scope.closeAlert = function(){
        $scope.success_messages = '';
        $scope.warning_messages = '';
        $scope.error_messages = '';
    };

    // calling parks, subscribers method during to on-load of page
    loadParks();
    loadSubscribers();
}]);
app.controller('ownershipTransfer',['$scope','WebService',function($scope,WebService){
    $scope.subscribers = [];
    $scope.pairings = [];
    $scope.selected_item = [];
    $scope.included_item = [];
    $scope.assigned_pairings = [];
    $scope.formData = {};

    // close alert box
    $scope.closeAlert = function(){
        $scope.success_messages = '';
        $scope.warning_messages = '';
        $scope.error_messages = '';
    };

    // load subscriber
    var loadSubscribers = function(){
        var http = WebService.get('ownership-transfer/ajax-get-subscribers');
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.subscribers = data.subscribers;
            }
        });
    };

    $scope.loadPairings = function(){
        $scope.formData.pairing_id = null;
        if($scope.formData.old_subscriber_id !=null) {
            var http = WebService.get('ownership-transfer/ajax-get-pairing-id/' + $scope.formData.old_subscriber_id);
            http.then(function (response) {
                var data = response.data;
                if (data.status == 200) {
                    $scope.pairings = data.pairings;
                }
            });
        }
    };
    $scope.IncludeItems = function(){


        /*if($scope.selected_item.length>programCount){
            $scope.warning_messages = 'Sorry! Maximum '+programCount+' Programs could be assigned. You have selected '+$scope.selected_item.length+' programs';
            return;
        }*/



        for(p in $scope.pairings){
            for(item in $scope.selected_item){
                if($scope.pairings[p].id == $scope.selected_item[item])
                {
                    /*if($scope.assigned_pairings.length == programCount){
                        $scope.warning_messages = 'Sorry! Maximum '+programCount+' Programs could be assigned. You have selected '+$scope.assigned_programs.length+' programs';
                        return;
                    }*/
                    $scope.assigned_pairings.push($scope.pairings[p]);
                    $scope.pairings.splice(p,1);
                }
            }
        }

    };

    $scope.ExcludeItems = function(){

        for(ap in $scope.assigned_pairings){
            for(item in $scope.included_item){
                if($scope.assigned_pairings[ap].id == $scope.included_item[item])
                {
                    $scope.pairings.push($scope.assigned_pairings[ap]);
                    $scope.assigned_pairings.splice(ap,1);
                }
            }
        }

    };

    $scope.transfer = function(){
        $scope.closeAlert();
        if($scope.assigned_pairings.length<0){
            $scope.warning_messages = 'Please Include Pairing ID from Parking List';
            return;
        }

        if($scope.formData.new_subscriber_id == null){
            $scope.warning_messages = 'Please select new subscriber ';
            return;s
        }
        var pairings = [];
        for(ap in $scope.assigned_pairings){
            pairings.push($scope.assigned_pairings[ap].id);
        }
        $scope.formData.pairings=pairings;

        var http = WebService.post('ownership-transfer/transfer',$scope.formData);
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.success_messages = data.success_messages;
                $scope.assigned_pairings = [];
            }else{
                $scope.warning_messages = data.warning_messages;
            }
        });
    };

    loadSubscribers();
}]);