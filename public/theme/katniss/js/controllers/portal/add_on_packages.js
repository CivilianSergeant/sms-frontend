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
app.controller('AddOnPackages',['$scope','WebService',function($scope,WebService){

    $scope.user_id = user_id;
    $scope.user_type = user_type;
    $scope.token = token;
    $scope.subscriber_name = subscriber_name;
    $scope.stb_card_pairs = [];
    $scope.packages = [];
    $scope.add_on_packages = [];
    $scope.assigned_package_list = [];

    $scope.$emit('loadSubscriberBalance',{user_type:$scope.user_type,user_id:$scope.user_id,token:$scope.token});

    var loadAddOnPackages = function(){
        var http = WebService.get('profile/ajax-load-addon-packages/'+$scope.token);
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.packages = data.packages;
            }
        });
    };

    var loadAssignedAddOnPackages = function()
    {
        var http = WebService.get('profile/ajax-get-assigned-addon-packages/'+$scope.token);
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.assigned_package_list = data.assigned_packages;
                var arr = [];
                var i=0;
                for(a in data.assigned_packages){
                    var obj = {
                        subscriber_name : $scope.subscriber_name,
                        start_date : data.assigned_packages[a].start_date,
                        expire_date : data.assigned_packages[a].expire_date,
                        pairing_id : data.assigned_packages[a].pairing_id,
                        duration : data.assigned_packages[a].duration,
                        package_name:'',
                        price:'',
                        user_package_id:''
                    };


                    var packages = data.assigned_packages[a].packages;
                    for(p in packages){
                        obj.package_name = packages[p].package_name;
                        obj.price = packages[p].price;
                        obj.user_package_id = packages[p].user_package_id;
                    }
                    arr[i] = obj;
                    i++;
                }
                //$scope.add_on_packages = arr;
            }
        });
    };

    $scope.isDisabledAssignPackage = function(){
        if($scope.selected_package==null){
            return 1;
        }
        return 0;
    };

    var loadPairingId = function()
    {

        var http = WebService.get('profile/ajax-get-pairing-id/'+$scope.user_id);
        http.then(function(response){
            var data = response.data;
            if(data.status == 200)
            {
                var i = 0;
                var arr =[];
                for(p in data.pairings){

                    if(data.pairings[p].id != 0){
                        arr[i]={
                            id: data.pairings[p].id,
                            pairing_id:data.pairings[p].pairing_id
                        };
                        i++;
                    }

                }

                $scope.stb_card_pairs = arr;


                /*$scope.pair_row_id = [];
                 angular.forEach($scope.stb_card_pairs, function(value, key){
                 var temp = {};
                 temp['pair_id'] = value.pairing_id;
                 temp['row_id'] = value.id;
                 $scope.pair_row_id[key] = temp;

                 });*/

            }
        });
    };

    $scope.setStbCardId = function()
    {

        $scope.stb_card_pairs.filter(function(obj){
            if($scope.stb_card_id == obj.id)
            {
                $scope.pairing_id = obj.pairing_id;
            }

        });
    };

    $scope.closeAlert = function(){
        $scope.warning_messages = '';
        $scope.success_messages = '';
        $scope.error_messages = '';
    };

    $scope.addPackage = function(){
        $scope.closeAlert();



        var dateObj = new Date();

        // start date time
        var month = (dateObj.getMonth()+1);
        month = (month<10)? '0'+month:month;

        var day = dateObj.getDate();
        day = (day<10)? '0'+day:day;

        var hours = dateObj.getHours();
        hours = (hours<10)? '0'+hours : hours;

        var minutes = dateObj.getMinutes();
        minutes = (minutes<10)? '0'+minutes : minutes;

        var seconds = dateObj.getMinutes();
        seconds = (seconds<10)? '0'+seconds : seconds;
        var startDate = dateObj.getFullYear() + '-'+ month + '-' + day + ' ' + hours + ':' + minutes + ':' + seconds;

        //for(var a in $scope.assigned_packages){
        var pkg = null;
        $scope.packages.filter(function(obj){
            if($scope.selected_package == obj.id){

                pkg = obj;
            }
        });

        //var pkg = $scope.selected_package;
        dateObj.setDate(dateObj.getDate() + eval(pkg.duration));
        // start date time
        var month = (dateObj.getMonth()+1);
        month = (month<10)? '0'+month:month;

        var day = dateObj.getDate();
        day = (day<10)? '0'+day:day;

        var hours = '23';//dateObj.getHours();
        //hours = (hours<10)? '0'+hours : hours;

        var minutes = '59';//dateObj.getMinutes();
        //minutes = (minutes<10)? '0'+minutes : minutes;

        var seconds = '59';//dateObj.getMinutes();
        //seconds = (seconds<10)? '0'+seconds : seconds;
        var endDate = dateObj.getFullYear() + '-'+ month + '-' + day + ' ' + hours + ':' + minutes + ':' + seconds;
        var item = {
            user_package_id:null,
            stb_card_id:$scope.stb_card_id,
            pairing_id: $scope.pairing_id,
            subscriber: $scope.user_id,
            subscriber_name:$scope.subscriber_name,
            assigned:pkg.assigned,
            duration: pkg.duration,
            id:pkg.id,
            is_active:pkg.is_active,
            package_name:pkg.package_name,
            price: pkg.price,
            programs:pkg.programs,
            token:pkg.token,

            start_date:startDate,
            expire_date:endDate

        };

        if(parseFloat($scope.balance) < parseFloat(item.price)){
            $scope.warning_messages = "Sorry! Subscriber don't have sufficient balance to purchase Add-on Package";

        }else{
            $scope.add_on_packages.push(item);

        }
        console.log(item);
        // }



    };

    $scope.deleteItem = function(i){
        if($scope.add_on_packages[i] != undefined){
            //var item = $scope.add_on_packages[i];
            /*$scope.packages.push({
             assigned:item.assigned,
             duration: item.duration,
             id:item.id,
             is_active:item.is_active,
             package_name:item.package_name,
             price: item.price,
             programs:item.programs,
             token:item.token,
             });*/
            $scope.add_on_packages.splice(i,1);
        }
    };

    $scope.confirmAddOnPackage = function(i){
        $scope.closeAlert();
        if($scope.add_on_packages[i] != undefined){
            var item = $scope.add_on_packages[i];
            var http = WebService.post('profile/save-addon-package',item);
            http.then(function(response){
                var data = response.data;
                if(data.status == 200){
                    $scope.add_on_packages[i].user_package_id = data.user_package_id;
                    $scope.success_messages = data.success_messages;
                    $scope.warning_messages = '';
                }else{
                    $scope.warning_messages = data.warning_messages;
                    $scope.success_messages = '';
                    $("html,body").animate({scrollTop:"0px"});
                }
            });
        }
    };

    loadAddOnPackages();
    loadAssignedAddOnPackages();
    loadPairingId();

}]);
