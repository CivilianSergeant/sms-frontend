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
app.controller('Packages',['$scope','WebService',function($scope,WebService){
    $scope.user_id = user_id;
    $scope.user_type = user_type;
    $scope.token = token;
    $scope.assigned_package_list = [];

    $scope.formData = {};

    $scope.$emit('loadSubscriberBalance',{user_type:$scope.user_type,user_id:$scope.user_id,token:$scope.token});

    var loadPackages = function(){
        var http = WebService.get('profile/ajax-get-packages/'+$scope.token);
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.assigned_package_list = data.assigned_package_list;
            }
        });
    };

    loadPackages();

    $scope.closeAlert = function()
    {
        $scope.warning_messages = $scope.success_messages = $scope.error_messages = '';
    };

    $scope.cancel = function()
    {
        $scope.formData = {};
    };

    $scope.migrate = function(item){
        $scope.loader=1;
        $scope.closeAlert();
        $scope.item = item;
        $scope.formData.stb_card_id = item.stb_card_id;
        $scope.formData.pairing_id = item.pairing_id;
        $scope.formData.token = $scope.token;
        $scope.formData.start_date = item.start_date;
        var http = WebService.post('profile/ajax-get-subscriber-migration-amount',$scope.formData);
        http.then(function(response){
            var data = response.data;
            $scope.message = data.message;
            $scope.loader=0;
        });
    };

    $scope.confirm = function()
    {
        $scope.closeAlert();
        var http = WebService.post('profile/unsubscribe',$scope.formData);
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.loadNotification();
                $scope.success_message = 'Package successfully unsubscribed';
                window.location = SITE_URL + 'profile/package-reassign/'+$scope.token+'/'+data.stb_card_id;
            } else {
                $scope.warning_messages = data.warning_messages;
                $scope.success_messages = '';
            }
        });
    };

}]);
app.controller('PackageReAssign',['$scope','WebService',function($scope,WebService){

    $scope.package_duration = 0;
    $scope.included_item_duration = null;
    $scope.pairing_id = null;
    $scope.stb_card_id = stb_card_id;
    $scope.token = token;
    $scope.packages = $scope.assigned_packages = [];
    $scope.user_id = user_id;
    $scope.user_type = user_type;

    $scope.$emit('loadSubscriberBalance',{user_type:$scope.user_type,user_id:$scope.user_id,token:$scope.token});

    $scope.closeAlert = function()
    {
        $scope.success_message = $scope.warning_messages = $scope.error_messages = '';
    };

    $scope.IncludeItems = function(){

        for(p in $scope.packages){
            for(item in $scope.selected_item){
                if($scope.packages[p].id == $scope.selected_item[item])
                {
                    if($scope.included_item_duration == null)
                    {

                        $scope.included_item_duration = $scope.packages[p].duration;
                        $scope.assigned_packages.push($scope.packages[p]);
                        $scope.package_price = $scope.packages[p].price;
                        $scope.package_duration = eval($scope.packages[p].duration);
                        $scope.packages.splice(p,1);

                    } else {

                        if($scope.packages[p].duration == $scope.included_item_duration)
                        {
                            $scope.assigned_packages.push($scope.packages[p]);
                            $scope.package_price = (eval($scope.package_price) + eval($scope.packages[p].price));
                            $scope.package_duration += eval($scope.packages[p].duration);
                            $scope.packages.splice(p,1);

                        }else{
                            $scope.warning_messages = 'You cannot add packages with different duration';
                            $("html,body").animate({scrollTop:'0px'});
                        }
                    }
                }
            }
        }
    };

    $scope.ExcludeItems = function(){

        for(ap in $scope.assigned_packages){
            for(item in $scope.included_item){
                if($scope.assigned_packages[ap].id == $scope.included_item[item])
                {
                    $scope.packages.push($scope.assigned_packages[ap]);
                    $scope.package_price = ($scope.package_price - $scope.assigned_packages[ap].price);
                    $scope.assigned_packages.splice(ap,1);
                }
            }
        }
        if($scope.assigned_packages.length <= 0)
        {
            $scope.included_item_duration = null;
        }
    };


    $scope.isDisabledAssignPackage = function(){

        if($scope.stb_card_id == null || $scope.assigned_packages == null
            || $scope.charge_type == null){

            return true;
        }

        return false;
    };

    var setPairingID = function()
    {

        $scope.unassigned_stb_cards.filter(function(obj){
            if($scope.stb_card_id == obj.id){
                $scope.pairing_id = obj.pairing_id;
            }
        });
    };



    $scope.reassignPackages = function(){
        $scope.closeAlert();
        setPairingID();

        if($scope.assigned_packages.length == 0){
            $scope.warning_messages = 'Please Include Packages';
            return;
        }

        var dateObj = new Date();

        var formData = {
            token: $scope.token,
            stb_card_id: $scope.stb_card_id,
            packages: $scope.assigned_packages,
            charge_type : $scope.charge_type,

        };

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

        var no_of_days = 0;
        var unit_price  = 0;
        var amountCharge = 0;

        $scope.assigned_packages.filter(function(obj){
            unit_price += Math.round(obj.price/obj.duration);
        });

        formData.start_date = dateObj.getFullYear() + '-'+ month + '-' + day + ' ' + hours + ':' + minutes + ':' + seconds;

        if($scope.charge_type == 1) // charge by amount
        {

            no_of_days = Math.round($scope.balance/unit_price);
            amountCharge = $scope.balance;
            dateObj.setDate(dateObj.getDate() + eval(no_of_days));

        } else {

            no_of_days = eval($scope.package_duration/$scope.assigned_packages.length);
            amountCharge = $scope.package_price;
            dateObj.setDate(dateObj.getDate() + eval(no_of_days));
        }


        formData.balance = $scope.balance;
        formData.amount_charge = amountCharge;
        formData.no_of_days = no_of_days;
        formData.unit_price = unit_price;
        formData.pairing_id = $scope.pairing_id;

        // expire date time
        var month = (dateObj.getMonth()+1);
        month = (month<10)? '0'+month:month;

        var day = dateObj.getDate();
        day = (day<10)? '0'+day : day;

        var hours = dateObj.getHours();
        hours = (hours<10)? '0'+hours : hours;

        var minutes = dateObj.getMinutes();
        minutes = (minutes<10)? '0'+minutes : minutes;

        var seconds = dateObj.getMinutes();
        seconds = (seconds<10)? '0'+seconds : seconds;

        hours = '23'; minutes = '59'; seconds = '59';

        formData.expire_date = dateObj.getFullYear()+'-'+month+'-'+day+ ' ' +hours+':'+minutes+':'+seconds;




        if(eval($scope.package_price) > eval($scope.balance) && $scope.charge_type==0)
        {
            $scope.warning_messages = 'You don\'t have enough balance';
            $('html,body').animate({scrollTop:'0px'});
            return;

        } else {

            if($scope.balance == 0){

                $scope.warning_messages = 'You don\'t have enough balance';
                $('html,body').animate({scrollTop:'0px'});
                return;
            }
        }

        //console.log(formData);

        var http = WebService.post('profile/save-reassign-packages',formData);
        http.then(function(response){
            var data = response.data;

            if(data.status == 400){
                $scope.warning_messages = data.warning_messages;
                $scope.success_messages = '';
                $("html,body").animate({scrollTop:'0px'});
            } else {
                $scope.success_messages = data.success_messages;
                $scope.loadNotification();
                $("html,body").animate({scrollTop:'0px'},function(){
                    window.location = SITE_URL+'subscriber-packages/'+$scope.token;
                });

            }

        });
    };

    var loadPackages = function(){

        var http = WebService.get('profile/ajax-get-packages/'+$scope.token);
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.packages  = data.packages;
                $scope.assigned_package_list = data.assigned_package_list;
            }
        });
    };



    var loadUnusedCards = function(){
        var formData = {
            token: $scope.token,
        };
        var http = WebService.post('profile/ajax-get-unused-cards',formData);
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.stbs = data.stbs;
                $scope.smart_cards = data.cards;
                $scope.stb_cards = data.stb_cards;
                $scope.unassigned_stb_cards = data.unassigned_stb_cards;

                $scope.warning_messages = '';
            }
        });
    };

    var loadProfile = function(){
        // show hide loader base on profile data loaded or not
        /* $scope.$watch('profile',function(val){

         if(val.id != ""){
         $scope.loader = 0;

         } else {

         $scope.loader = 1;
         }

         });*/

        // load balance of subscriber
        //loadBalance();
        loadPackages();
        loadUnusedCards();

        var http = WebService.get('profile/ajax_get_profile/'+$scope.token);
        http.then(function(response){
            var data = response.data;
            $scope.profile = data.profile;
            if($scope.stb_card_id != ""){
                loadUnusedCards();
                setPairingID();
            }

        });
    };

    loadProfile();

}]);

app.controller('FocPackageReAssign',['$scope','WebService',function($scope,WebService){

    $scope.package_duration = 0;
    $scope.included_item_duration = null;
    $scope.pairing_id = null;
    $scope.stbCard = null;
    $scope.stb_card_id = stb_card_id;
    $scope.token = token;
    $scope.packages = $scope.assigned_packages = [];

    $scope.closeAlert = function()
    {
        $scope.success_message = $scope.warning_messages = $scope.error_messages = '';
    };

    $scope.IncludeItems = function(){

        for(p in $scope.packages){
            for(item in $scope.selected_item){
                if($scope.packages[p].id == $scope.selected_item[item])
                {
                    if($scope.included_item_duration == null)
                    {

                        $scope.included_item_duration = $scope.packages[p].duration;
                        $scope.assigned_packages.push($scope.packages[p]);
                        $scope.package_price = $scope.packages[p].price;
                        $scope.package_duration = eval($scope.packages[p].duration);
                        $scope.packages.splice(p,1);

                    } else {

                        if($scope.packages[p].duration == $scope.included_item_duration)
                        {
                            $scope.assigned_packages.push($scope.packages[p]);
                            $scope.package_price = (eval($scope.package_price) + eval($scope.packages[p].price));
                            $scope.package_duration += eval($scope.packages[p].duration);
                            $scope.packages.splice(p,1);

                        }else{
                            $scope.warning_messages = 'You cannot add packages with different duration';
                            $("html,body").animate({scrollTop:'0px'});
                        }
                    }
                }
            }
        }
    };

    $scope.ExcludeItems = function(){

        for(ap in $scope.assigned_packages){
            for(item in $scope.included_item){
                if($scope.assigned_packages[ap].id == $scope.included_item[item])
                {
                    $scope.packages.push($scope.assigned_packages[ap]);
                    $scope.package_price = ($scope.package_price - $scope.assigned_packages[ap].price);
                    $scope.assigned_packages.splice(ap,1);
                }
            }
        }
        if($scope.assigned_packages.length <= 0)
        {
            $scope.included_item_duration = null;
        }
    };


    $scope.isDisabledAssignPackage = function(){

        /*if($scope.stb_card_id == null || $scope.assigned_packages.length == 0 ||
         $scope.expire_date_string == null){

         return true;
         }*/
        if($scope.stbCard){

            if($scope.stbCard.free_subscription_fee == 1){
                var expireDate = angular.element('input[ng-model=expire_date_string]').val();

                if($scope.stb_card_id == null || $scope.assigned_packages.length == 0 ||
                    expireDate == ''){


                    return true;
                }
            }else{

                if($scope.stb_card_id == null || $scope.assigned_packages.length == 0){

                    return true;
                }

            }
        }else{
            return true;
        }

        return false;
    };

    $scope.setPairingID = function()
    {

        $scope.unassigned_stb_cards.filter(function(obj){
            if($scope.stb_card_id == obj.id){
                $scope.stbCard = obj;
                $scope.pairing_id = obj.pairing_id;
            }
        });
    };

    var daydiff = function(first, second) {
        return Math.round((second-first)/(1000*60*60*24));
    };

    $scope.reassignPackages = function(){
        $scope.setPairingID();
        $scope.closeAlert();

        if($scope.assigned_packages.length == 0){
            $scope.warning_messages = 'Please Include Packages';
            return;
        }

        var dateObj = new Date();

        var formData = {
            token: $scope.token,
            stb_card_id: $scope.stb_card_id,
            packages: $scope.assigned_packages,
            charge_type : $scope.charge_type,

        };

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

        var no_of_days = daydiff(dateObj,$scope.expire_date_object);;
        var unit_price  = 0;
        var amountCharge = 0;


        formData.start_date = dateObj.getFullYear() + '-'+ month + '-' + day + ' ' + hours + ':' + minutes + ':' + seconds;
        // check following conditions if subscription fee is not free
        if($scope.stbCard.free_subscription_fee == 0) {


            $scope.assigned_packages.filter(function (obj) {
                unit_price += Math.round(obj.price / obj.duration);
            });

            if ($scope.charge_type == 1) // charge by amount
            {

                no_of_days = Math.round($scope.balance / unit_price);
                amountCharge = $scope.balance;
                dateObj.setDate(dateObj.getDate() + eval(no_of_days));

            } else {

                no_of_days = eval($scope.package_duration / $scope.assigned_packages.length);
                amountCharge = $scope.package_price;
                dateObj.setDate(dateObj.getDate() + eval(no_of_days));
            }
        }


        formData.balance = $scope.balance;
        formData.amount_charge = amountCharge;
        formData.no_of_days = no_of_days;
        formData.unit_price = unit_price;
        formData.pairing_id = $scope.pairing_id;

        // expire date time
        var month = (dateObj.getMonth()+1);
        month = (month<10)? '0'+month:month;

        var day = dateObj.getDate();
        day = (day<10)? '0'+day : day;

        var hours = dateObj.getHours();
        hours = (hours<10)? '0'+hours : hours;

        var minutes = dateObj.getMinutes();
        minutes = (minutes<10)? '0'+minutes : minutes;

        var seconds = dateObj.getMinutes();
        seconds = (seconds<10)? '0'+seconds : seconds;

        hours = '23'; minutes = '59'; seconds = '59';

        //formData.expire_date = dateObj.getFullYear()+'-'+month+'-'+day+ ' ' +hours+':'+minutes+':'+seconds;
        //formData.expire_date = $scope.expire_date_string + ' ' + hours + ':' + minutes + ':' + seconds;

        if($scope.stbCard.free_subscription_fee == 0){

            formData.expire_date = dateObj.getFullYear()+'-'+month+'-'+day+ ' ' +hours+':'+minutes+':'+seconds;
        }else{

            formData.expire_date = angular.element("input[ng-model=expire_date_string]").val() + ' ' + hours + ':' + minutes + ':' + seconds;
        }

        formData.free_subscription_fee = $scope.stbCard.free_subscription_fee;

        // check following conditions if subscription fee is not free
        if($scope.stbCard.free_subscription_fee == 0) {
            if (eval($scope.package_price) > eval($scope.balance) && $scope.charge_type == 0) {
                $scope.warning_messages = 'You don\'t have enough balance';
                $('html,body').animate({scrollTop: '0px'});
                return;

            } else {

                if ($scope.balance == 0) {

                    $scope.warning_messages = 'You don\'t have enough balance';
                    $('html,body').animate({scrollTop: '0px'});
                    return;
                }
            }
        }



        // check following condition if subscription fee is free
        if($scope.stbCard.free_subscription_fee == 1) {
            if (no_of_days <= 0) {
                $scope.warning_messages = 'Expire Date Must be greater than today';
                return;
            }
        }

        //console.log(formData);
        var http = WebService.post('profile/save_foc_reassign_packages',formData);
        http.then(function(response){
            var data = response.data;
            if(data.status == 400){
                $scope.warning_messages = data.warning_messages;
                $scope.success_messages = '';
            } else {
                $scope.success_messages = data.success_messages;
                $scope.warning_messages = '';
                loadBalance();
                loadPackages();
                loadUnusedCards();
                $scope.assigned_packages = [];
                $scope.included_item_duration = null;
                $scope.stb_card_id = null;
                $scope.charge_type = 0
                $("html,body").animate({scrollTop:'0px'},function(){
                    window.location = SITE_URL+'foc-subscriber';
                });
            }
        });
    };

    var loadPackages = function(){

        var http = WebService.get('profile/ajax-get-packages/'+$scope.token);
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.packages  = data.packages;
                $scope.assigned_package_list = data.assigned_package_list;
            }
        });
    };

    /*var loadBalance = function(){
        var http = WebService.post('profile/ajax_get_balance',{token:$scope.token});
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.balance = data.balance;
            }


        });
    };*/

    var loadUnusedCards = function(){
        var formData = {
            token: $scope.token,
        };
        var http = WebService.post('profile/ajax-get-unused-cards',formData);
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.stbs = data.stbs;
                $scope.smart_cards = data.cards;
                $scope.stb_cards = data.stb_cards;
                $scope.unassigned_stb_cards = data.unassigned_stb_cards;

                $scope.warning_messages = '';
            }
        });
    };

    var loadProfile = function(){
        // show hide loader base on profile data loaded or not
        /* $scope.$watch('profile',function(val){

         if(val.id != ""){
         $scope.loader = 0;

         } else {

         $scope.loader = 1;
         }

         });*/

        // load balance of subscriber
        //loadBalance();
        loadPackages();
        loadUnusedCards();

        var http = WebService.get('profile/ajax_get_profile/'+$scope.token);
        http.then(function(response){
            var data = response.data;
            $scope.profile = data.profile;
            if($scope.stb_card_id != ""){
                loadUnusedCards();
                $scope.setPairingID();
            }

        });
    };

    loadProfile();

}]);
