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
app.controller('CreateSubscriberProfile',['$scope','WebService','FileUploader','$timeout',function($scope,WebService,FileUploader,$timeout){

    $scope.items = [];

    $scope.profile = {id:'',full_name:'',username:'',email:'',password:'',re_password:''};
    $scope.billing_address = {id:'',full_name:'',email:''};
    $scope.showFrm = 0;
    $scope.phones = [{number:''}];
    $scope.identity_verify_types = ['Nation ID','Passport','Utility Document'];
    $scope.token = null;
    $scope.identity = {};
    $scope.packages = $scope.assigned_packages = [];
    $scope.countries = $scope.divisions = $scope.districts = $scope.areas = $scope.sub_areas = $scope.roads = [];
    $scope.package_price = 0;
    $scope.loader = 0;

    $scope.tabs = {profile:1,login:0,documents:0,package_assign:0,recharge:0};
    
    $scope.package_duration = 0;
    $scope.included_item_duration = null;
    $scope.pairing_id = null;
    $scope.stb_card_id = null;
    $scope.charge_type = 0;
    $scope.billing_address_id =  null;
    $scope.notStrongPassFlag = 0;
    $scope.checkRePasswordFlag = 0;
    $scope.pass_message = '';
    $scope.re_pass_message = '';
    $scope.profile.region_l1_code = null;
    $scope.profile.region_l2_code = null;
    $scope.profile.region_l3_code = null;
    $scope.profile.region_l4_code = null;
    $scope.stb_box_id = null;
    $scope.smart_card_id = null;

    $scope.fileUploadPhotoProgress = 0;
    $scope.fileUploadIdentityProgress = 0;
    $scope.fileUploadSubscriptionProgress = 0;

    $scope.filters = [{name:'None',value:'none'},
        {name:'Zero Balance',value:'zero-balance'},
        {name:'No Package',value:'no-package'},
        {name:'Active',value:'active'},
        {name:'Expired',value:'expired'}];

    /*$scope.$watch('included_item',function(val){
        
        $scope.package_price=0
        for(v in val){   
            for(ap in $scope.assigned_packages){
               var item = $scope.assigned_packages[ap]
               if(val[v] == item.id){
                    $scope.package_price += eval(item.price);
               }
            }
        }
    });*/
        
        
    var resetMessage = function()
    {
        $scope.success_message = '';
        $scope.warning_messages = '';
        $scope.error_messages = '';
    };

    $timeout( function(){ $scope.loader=0; }, 2000);

    $scope.addPhoneItem = function(){
        $scope.phones.push({number:''});
    };

    $scope.filterData = {};

    $scope.filterSubscriberList = function() {
        var grid = $('#subscriber-grid').data("kendoGrid");
        grid.dataSource.transport.options.read.url=SITE_URL+"subscriber/ajax_load_grid_data?type="+$scope.filterData.filter;
        grid.dataSource.read();
        // grid.refresh();
    };

    $scope.downloadSubscriberList = function(){


        window.location = SITE_URL+'subscriber/download/'+$scope.filterData.filter;
    };

    $scope.checkPassWordStrength = function(){
        var password = ($scope.profile.password);
        //var strongRegex = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[@#])(?=.{8,})");
        var mediumRegex = new RegExp("^(?=.{8,})");
        if(!mediumRegex.test(password)){
            $scope.notStrongPassFlag = 1;
            $scope.pass_message = 'Password should be at least 8 characters long';
        }else{
            $scope.notStrongPassFlag = 0;
            $scope.pass_message = '';
        }
    };

    $scope.checkRePassword = function(){
        if($scope.profile.password != $scope.profile.re_password){
            $scope.checkRePasswordFlag = 1;
            $scope.re_pass_message = 'Re-password not matched';
        }else{
            $scope.checkRePasswordFlag = 0;
            $scope.re_pass_message = '';
        }
    };

    $scope.isSaveLoginDisabled = function(){
        if($scope.notStrongPassFlag){
            return true;
        }
        if($scope.checkRePasswordFlag){
            return true;
        }
        return false;
    };

    $scope.closeAlert = function(){
        resetMessage();
    };

    var uploader = $scope.uploader = new FileUploader({
        headers: {'X-Requested-With':'XMLHttpRequest'},
        url: SITE_URL+'subscriber/upload_photo'
    });

    uploader.onBeforeUploadItem = function(item) {
        uploader.progress = 0;
        $scope.fileUploadPhotoProgress = 0;
        item.formData.push({token:$scope.token,form_type:0});
    };

    uploader.onAfterAddingFile = function(fileItem) {
        uploader.progress = 0;
        $scope.fileUploadPhotoProgress = 0;

    };
    uploader.onProgressItem = function(fileItem, progress) {
        $scope.fileUploadPhotoProgress =  progress;
    };

    uploader.onSuccessItem = function(fileItem, response, status, headers) {
        if (response.status == 200){
            $scope.uploadView = false;
            $scope.profile.photo = response.image;
            $scope.success_message = response.success_messages;
            $scope.warning_messages = '';
            $scope.loadNotification();
        }else{
            uploader.progress = 0;
            $scope.fileUploadPhotoProgress = 0;
            $scope.warning_messages = response.warning_messages;
            $scope.success_message = '';
            $scope.uploadView = false;
        }
    };

    var identityUploader = $scope.identityUploader = new FileUploader({
        headers: {'X-Requested-With':'XMLHttpRequest'},
        url: SITE_URL+'subscriber/upload_identity'
    });

    identityUploader.onBeforeUploadItem = function(item) {
        //item.formData.push($scope.identity);
        identityUploader.progress = 0;
        $scope.fileUploadIdentityProgress = 0;
        item.formData.push({token:$scope.token,form_type:0});
    };

    identityUploader.onAfterAddingFile = function(fileItem) {
        identityUploader.progress = 0;
        $scope.fileUploadIdentityProgress = 0;

    };

    identityUploader.onProgressItem = function(fileItem, progress) {
        $scope.fileUploadIdentityProgress =  progress;
    };

    identityUploader.onSuccessItem = function(fileItem, response, status, headers) {
        if (response.status == 200){
            $scope.identity.identity_attachment = response.image;
            $scope.success_message = response.success_messages;
            $scope.warning_messages = '';
            $scope.loadNotification();
        }else{
            identityUploader.progress = 0;
            $scope.fileUploadIdentityProgress = 0;
            $scope.warning_messages = response.warning_messages;
            $scope.success_message = '';
            $scope.uploadView = false;
        }
    };

    var subuscriptionUploader = $scope.subuscriptionUploader = new FileUploader({
        headers: {'X-Requested-With':'XMLHttpRequest'},
        url: SITE_URL+'subscriber/upload_subscription_copy'
    });

    subuscriptionUploader.onBeforeUploadItem = function(item) {
        subuscriptionUploader.progress = 0;
        $scope.fileUploadSubscriptionProgress = 0;
        item.formData.push({token:$scope.token,form_type:0});
    };

    subuscriptionUploader.onAfterAddingFile = function(fileItem) {
        subuscriptionUploader.progress = 0;
        $scope.fileUploadSubscriptionProgress = 0;

    };

    subuscriptionUploader.onProgressItem = function(fileItem, progress) {
        $scope.fileUploadSubscriptionProgress =  progress;
    };

    subuscriptionUploader.onSuccessItem = function(fileItem, response, status, headers) {
        if (response.status == 200){
            $scope.uploadView = false;
            $scope.profile.subscription_copy = response.image;
            $scope.success_message = response.success_messages;
            $scope.warning_messages = ''
            $scope.loadNotification();
        }else{
            subuscriptionUploader.progress = 0;
            $scope.fileUploadSubscriptionProgress = 0;
            $scope.warning_messages = response.warning_messages;
            $scope.success_message = '';
            $scope.uploadView = false;
        }
    };

    $scope.hideForm = function(){
        
        resetMessage();
        
        if($scope.token != null && $scope.billing_address_id == null){
            $scope.warning_messages = 'Please save billilng address before close.';
            return;
        }
        window.location.reload();
        $scope.showFrm = 0;
        $scope.profile = {id:'',full_name:'',username:'',email:'',password:'',re_password:''};
        $scope.billing_address = {id:'',full_name:'',email:''};
        $scope.identity = {};
    }

    $scope.removePhoneItem = function(i){
        if (i != 0) {
            $scope.phones.splice(i,1);
        }
    };  

    $scope.setTab = function(tab){
        //resetMessage();
        switch(tab){
            case 'profile':
                $scope.tabs = {profile:1,login:0,billing_address:0,documents:0,business_region:0,stb_card:0,package_assign:0,recharge:0};
                break;
            case 'login':
                loadBalance();
                if($scope.token != null){
                    $scope.tabs = {profile:0,login:1,billing_address:0,documents:0,business_region:0,stb_card:0,package_assign:0,recharge:0};
                } else {
                    $scope.warning_messages = 'You have to create profile before add login Info';
                    $("html,body").animate({scrollTop:'0px'});
                }
                break;

            case 'documents':

                if($scope.token != null){
                    $scope.tabs = {profile:0,login:0,billing_address:0,documents:1,business_region:0,stb_card:0,package_assign:0,recharge:0};
                } else {
                    
                    $scope.warning_messages = 'You have to create profile before attach photo';
                    $("html,body").animate({scrollTop:'0px'});
                }
                break;
            case 'package_assign':
                if($scope.token != null) {
                    loadUnusedCards();
                    loadBalance();
                    loadPackages();
                    //loadAssignedPackages();
                    $scope.tabs = {profile:0,login:0,billing_address:0,documents:0,business_region:0,stb_card:0,package_assign:1,recharge:0};
                } else {
                     $scope.warning_messages = 'You have to create profile before assign package';
                    $("html,body").animate({scrollTop:'0px'});
                }
                break;
        }
    };

     var loadPackages = function(){
       
        var http = WebService.get('subscriber/ajax_get_packages/'+$scope.token);
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.packages  = data.packages;
                $scope.assigned_package_list = data.assigned_package_list;
            }
        });
    };

    var loadBalance = function(){
        var http = WebService.post('subscriber/ajax_get_balance',{token:$scope.token});
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                 $scope.balance = data.balance;
            } 
               
            
        });
    };

    var loadAssignedPackages = function(){
        var http = WebService.post('subscriber/ajax_get_assigned_packages',{token:$scope.token});
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                 //$scope.assigned_packages = data.assigned_packages;
            } 
               
            
        });
    };

     var loadUnusedCards = function(){
        var formData = {
            token: $scope.token,
        };
        var http = WebService.post('subscriber/ajax_get_devices',formData);
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.stbs = data.stbs;
                $scope.smart_cards = data.cards;
                $scope.stb_cards = data.stb_cards;
                $scope.unassigned_stb_cards = data.unassigned_stb_cards;
                /*if($scope.stbs.length==0)
                {
                    $scope.warning_messages += 'You don\'t have any unused Set-Top Box ';
                }

                if($scope.smart_cards.length == 0)
                {
                    if($scope.warning_messages != null)
                    {  
                        $scope.warning_messages += 'and unused Smart Card';
                    } else {
                        $scope.warning_messages += 'You don\'t have any unused Smart Card';
                    }
                }*/
            }
        });
    };

    $scope.addStbCard = function(){
        $scope.stb_box_id = angular.element('select[ng-model=stb_box_id]').val();
        //$scope.smart_card_id = angular.element('select[ng-model=smart_card_id]').val();
        var stbCard = {
            //pairing_id:'',
            device_number:'',
            //stb_type:'',
            //smart_card_provider:'',
            //smart_card_type:'',
            //smart_card_number:'',
            //stb_provider_id:'',
            //smart_card_provider_id:'',
            //smart_card_id:'',
            stb_box_id:''
        };

        $scope.stbs.filter(function(obj){


            if(obj.stb_number == $scope.stb_box_id)
            {
                //stbCard.stb_provider    = obj.stb_provider;
                stbCard.device_number      = obj.stb_number;
                //stbCard.stb_type        = obj.stb_type;
                //stbCard.stb_provider_id = obj.stb_provider_id;
                stbCard.stb_box_id      = obj.stb_box_id;
            }
        });

        /*$scope.smart_cards.filter(function(obj){

            if(obj.smart_card_number == $scope.smart_card_id)
            {
                stbCard.smart_card_provider    = obj.smart_card_provider;
                stbCard.smart_card_number      = obj.smart_card_number;
                stbCard.smart_card_type        = obj.smart_card_type;
                stbCard.smart_card_provider_id = obj.smart_card_provider_id;
                stbCard.smart_card_id          = obj.smart_card_id;
            }
        });*/

        $scope.stb_cards.push(stbCard);
        //console.log($scope.stb_cards);
        $scope.stb_box_id = $scope.smart_card_id = '';
    };

    $scope.deleteStbSmartCard = function(i){
        $scope.stb_cards.splice(i,1);
    };

    $scope.confirmStbSmartCard = function(i){
        var formData = $scope.stb_cards[i];
        formData.token  = $scope.token;
        formData.form_type = 0;
        var http = WebService.post('subscriber/assign-stb-smartcard',formData);
        http.then(function(response){
            var data = response.data;
            if(data.status==400){
                $scope.warning_messages = data.warning_messages;
                $scope.success_message = '';
            } else {
                $scope.success_message = data.success_message;
                $scope.warning_messages = '';
                $scope.stbs = data.stbs;
                $scope.smart_cards = data.cards;
                $scope.stb_cards = data.stb_cards;
                $scope.assigned_packages = [];
                loadUnusedCards();
                $scope.loadNotification();
                $scope.setTab('package_assign');
                //window.location = SITE_URL + 'subscriber/edit/'+$scope.token+'#package_assign';
            }
        });
    }; 

    $scope.showForm = function()
    {
        /*if($scope.lco_profile.business_region_assigned == 0){
            $scope.warning_messages = 'Please Ask your MSO to assign business region to your profile so you can create Subscriber';
            return;
        }*/

        $scope.showFrm = 1;
        $scope.success_messages = $scope.warning_messages = $scope.error_messages = '';
    };

    $scope.saveLogin = function(){
        resetMessage();

        if($scope.profile.username=='' || $scope.profile.username == undefined){
            $scope.warning_messages = 'Username cannot be blank';
            return;
        }

        var formData = {
            token:$scope.token,
            username:$scope.profile.username,
            password:$scope.profile.password,
            re_password:$scope.profile.re_password,
            is_remote_access_enabled:$scope.profile.is_remote_access_enabled,
            form_type:0
        };

        if($scope.token != null || $scope.token != ""){
            var http = WebService.post('subscriber/update_login_info',formData);
        }else{
            var http = WebService.post('subscriber/create_login_info',formData);
        }

        http.then(function(response){
            var data = response.data;
            if(data.status == 400){
                $scope.warning_messages = data.warning_messages;
                $scope.success_message = '';
            } else {

                $scope.success_message = data.success_message;
                $scope.warning_messages = '';
                loadProfiles();
                if($scope.token != null){
                    $scope.setTab('billing_address');
                }
                $scope.loadNotification();
            }
            $("html,body").animate({scrollTop:'0px'});
        });
    };

    $scope.saveProfile = function(){
        resetMessage();
        
        $scope.profile.form_type=0;
        var http = WebService.post('subscriber/create_profile',$scope.profile);
        http.then(function(response){
            var data = response.data;
            if(data.status == 400){
                $scope.warning_messages = data.warning_messages;
                $scope.success_message = '';
            } else {

                $scope.success_message = data.success_message;

                if($scope.profile.username == "")
                    $scope.profile.username = $scope.profile.email;

                $scope.billing_address.is_same_as_profile = 1;
                

                $scope.warning_messages = '';
                $scope.token = data.token;
                
                loadProfiles();
                $scope.setBillingAddress();
                //loadBusinessRegion();
                if($scope.token != null){
                    $scope.setTab('login');
                }
                $scope.loadNotification();
            }
            $("html,body").animate({scrollTop:'0px'});
        });
    };

    $scope.IncludeItems = function(){
        $scope.selected_item =[];
        var selectedItems = angular.element("select#select-from").children(':selected');
        $.each(selectedItems,function(i,el){
            var value =$(this).val();

            $scope.selected_item.push(value);
        });





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

                        /*if($scope.packages[p].duration == $scope.included_item_duration)
                        {*/
                            $scope.assigned_packages.push($scope.packages[p]);
                            $scope.package_price = (eval($scope.package_price) + eval($scope.packages[p].price));
                            $scope.package_duration += eval($scope.packages[p].duration);
                            $scope.packages.splice(p,1);

                        /*}else{
                            $scope.warning_messages = 'You cannot add packages with different duration';
                            $("html,body").animate({scrollTop:'0px'});
                        }*/
                    }   
                }
            }
        }
    };

    $scope.ExcludeItems = function(){

        $scope.included_item =[];
        var selectedItems = angular.element("select#select-to").children(':selected');
        $.each(selectedItems,function(i,el){
            var value =$(this).val();

            $scope.included_item.push(value);
        });
        
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
        $scope.stb_card_id = angular.element('select[ng-model=stb_card_id]').val();

        if($scope.stb_card_id == '' || $scope.assigned_packages == null){

            return true;
        }

        return false;
    };
    
    var setPairingID = function()
    {
        
        $scope.unassigned_stb_cards.filter(function(obj){
            if($scope.stb_card_id == obj.id){
                $scope.pairing_id = obj.device_number;
            }
        });
    };

    $scope.assignPackages = function(){

        resetMessage();

        $scope.stb_card_id = angular.element('select[ng-model=stb_card_id]').val();
        $scope.charge_type = angular.element('input[ng-model=charge_type]:checked').val();
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

        if(eval($scope.package_price) !=0) {
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

        formData.form_type=0;

        //console.log(formData);

        var http = WebService.post('subscriber/save_assign_packages',formData);
        http.then(function(response){
            var data = response.data;
            if(data.status == 400){
                $scope.warning_messages = data.warning_messages;
                $scope.success_message = '';
            } else {
                $scope.success_message = data.success_message;
                $scope.warning_messages = '';
                loadBalance();
                loadPackages();
                loadUnusedCards();
                $scope.assigned_packages = [];
                $scope.included_item_duration = null;
                $scope.stb_card_id = null;
                $scope.charge_type = null;
                $scope.loadNotification();
            } 
            $("html,body").animate({scrollTop:'0px'});   
        });
    };

    // assign package
    /*$scope.assignPackage = function(){
        resetMessage();
        if($scope.assigned_packages==null){
            $scope.warning_messages = 'Please Include Packages';
            return;
        }
        var formData = {
            token: $scope.token,
            stb_card_id: $scope.stb_card_id,
            package_id: $scope.selected_package
        };
        
        var http = WebService.post('subscriber/has_package_assigned',formData);
        http.then(function(response){

            var data = response.data;
            var dateObj = new Date();
            var item = {};
            if(data.status == 200){
                $scope.subscriber_package = data.package;
                
                    $scope.assigned_packages.filter(function(obj){
                        if(obj.stb_card_id == $scope.stb_card_id){
                            item = obj;
                        }
                    });
                    
                    

                    $scope.packages.filter(function(obj){
                        if($scope.selected_package == obj.id){
                                if(eval(obj.price) > eval($scope.balance) && $scope.charge_type==0)
                                {
                                    $scope.warning_messages = 'You don\'t have enough balance';
                                    $('html,body').animate({scrollTop:'0px'});
                                    return;
                                } 
                                item.package_id = obj.id
                                item.package_name = obj.package_name;
                                item.price = obj.price;
                                item.duration = obj.duration;
                                item.no_of_program = obj.no_of_program;
                                item.charge_type = $scope.charge_type;
                                
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

                                item.start_date = dateObj.getFullYear()+'-'+month+'-'+day + ' ' +hours+':'+minutes+':'+seconds;
                                
                                if($scope.charge_type==1){

                                    var unit_price = Math.round(obj.price/obj.duration);
                                    var addDays = Math.round($scope.balance/unit_price);
                                    dateObj.setDate(dateObj.getDate()+eval(addDays));
                                    item.no_of_days = addDays;
                                } else {
                                    item.no_of_days = obj.duration;
                                    dateObj.setDate(dateObj.getDate()+eval(obj.duration));
                                  
                                
                                }

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

                                item.expire_date = dateObj.getFullYear()+'-'+month+'-'+day+ ' ' +hours+':'+minutes+':'+seconds;
                                

                                
                                
                        }
                    });
                
            }
        });

    };*/

    /*// delte package item
    $scope.deletePackageItem = function(i){
        var item = $scope.assigned_packages[i];
        item.package_name = '';
        item.price = '';
        item.duration = '';
        item.no_of_program = '';
        item.charge_type = '';
        item.start_date = '';
        item.expire_date = '';

    };*/

    // confirm package Item
    /*$scope.confirmPackageItem = function(i){
        var item = $scope.assigned_packages[i];
        item.token = $scope.token;
        item.balance = $scope.balance;
        item.form_type=0;
        var http = WebService.post('subscriber/save_assign_packages',item);
        http.then(function(response){
            var data = response.data;
            
            if(data.status == 400){
                $scope.warning_messages = data.warning_messages;
                $scope.success_messages = '';
            } else {
                $scope.success_messages = data.success_messages;
                $scope.warning_messages = '';
                loadBalance();
                loadUnusedCards();
                loadAssignedPackages();
                $scope.stb_card_id = '';
                $scope.smart_card_id = '';
                $scope.selected_package = null;
                $scope.charge_type = null;
            }

            
        });
    };*/

    /*$scope.unsubscribePackageItem = function(i){
        var item = $scope.assigned_packages[i];
        item.token = $scope.token;
        var http = WebService.post('subscriber/unsubscribe_package',item);
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.success_messages = data.success_messages;
                $scope.assigned_packages = [];
                loadBalance();
                loadProfile();
            }
        });
        
    };*/

    var loadBusinessRegion = function(){
        var http = WebService.get('subscriber/ajax_load_region');
        http.then(function(response){
            var data = response.data;
            $scope.regions = data;

        });
    };

    var loadLcoProfile = function(){
        var http = WebService.get('subscriber/ajax_load_lco_profile');
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.lco_profile = data.lco_profile;

                if(($scope.lco_profile != undefined) && ($scope.lco_profile.country_id !=undefined))
                    $scope.profile.country_id  = $scope.lco_profile.country_id;

                if(($scope.lco_profile != undefined) && ($scope.lco_profile.division_id !=undefined))
                    $scope.profile.division_id = $scope.lco_profile.division_id;

                if(($scope.lco_profile != undefined) && ($scope.lco_profile.district_id !=undefined))
                    $scope.profile.district_id = $scope.lco_profile.district_id;
                
                if(($scope.lco_profile != undefined) && ($scope.lco_profile.region_l1_code != undefined))
                {
                    $scope.business_region_l1 = $scope.profile.region_l1_code = $scope.lco_profile.region_l1_code;
                    $scope.setRegionLevel2();
                } 

                if(($scope.lco_profile != undefined) && ($scope.lco_profile.region_l2_code != undefined))
                {
                    $scope.business_region_l2 = $scope.profile.region_l2_code = $scope.lco_profile.region_l2_code;
                    $scope.setRegionLevel3();
                }

                if(($scope.lco_profile != undefined) && ($scope.lco_profile.region_l3_code != undefined))
                {
                    $scope.business_region_l3 = $scope.profile.region_l3_code = $scope.lco_profile.region_l3_code;
                    $scope.setRegionLevel4();
                } 

                if(($scope.lco_profile != undefined) && ($scope.lco_profile.region_l4_code != undefined))
                {
                    if($scope.profile.region_l4_code == null || $scope.profile.region_l4_code == 0){
                        $scope.business_region_l4 = $scope.lco_profile.region_l4_code;
                        $scope.profile.region_l4_code = $scope.lco_profile.region_l4_code;
                    }
                }
            }
        });
    };

    var loadProfiles = function(){
        $scope.$watch('items',function(val){

            if(val !=undefined){
                if(val.length){
                    $scope.loader = 0;
                } else {
                    $scope.loader = 1;
                }
            }
        });

        var http = WebService.get('subscriber/ajax_load_profiles');
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.items = data.profiles;
                $scope.countries = data.countries;
                $scope.lco_profile = data.lco_profile;

                if(($scope.lco_profile != undefined) && ($scope.lco_profile.country_id !=undefined))
                    $scope.profile.country_id  = $scope.lco_profile.country_id;

                if(($scope.lco_profile != undefined) && ($scope.lco_profile.division_id !=undefined))
                    $scope.profile.division_id = $scope.lco_profile.division_id;

                if(($scope.lco_profile != undefined) && ($scope.lco_profile.district_id !=undefined))
                    $scope.profile.district_id = $scope.lco_profile.district_id;
                
                if(($scope.lco_profile != undefined) && ($scope.lco_profile.region_l1_code != undefined))
                {
                    $scope.business_region_l1 = $scope.profile.region_l1_code = $scope.lco_profile.region_l1_code;
                    $scope.setRegionLevel2();
                } 

                if(($scope.lco_profile != undefined) && ($scope.lco_profile.region_l2_code != undefined))
                {
                    $scope.business_region_l2 = $scope.profile.region_l2_code = $scope.lco_profile.region_l2_code;
                    $scope.setRegionLevel3();
                }

                if(($scope.lco_profile != undefined) && ($scope.lco_profile.region_l3_code != undefined))
                {
                    $scope.business_region_l3 = $scope.profile.region_l3_code = $scope.lco_profile.region_l3_code;
                    $scope.setRegionLevel4();
                } 

                if(($scope.lco_profile != undefined) && ($scope.lco_profile.region_l4_code != undefined))
                {
                    $scope.business_region_l4 = $scope.profile.region_l4_code = $scope.lco_profile.region_l4_code;

                }   
                
                $scope.packages = data.packages;
            
            }
        });
    };

    var generateKendoGird = function(){
        $scope.mainGridOptions = {
            dataSource: {
               /* 
                static non server-side
                type: "jsonp",
                data:items,
                pageSize: 10,*/
                transport: {
                    read: {
                        url: "subscriber/ajax_load_grid_data", 
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
                serverFiltering: true
            },
            filterable: {
               extra: false,
               operators: {
                    string: {
                        startswith: "Starts with",
                        eq: "Is equal to",
                       
                    }
                }
            },
            sortable: true,
            pageable: true,
            scrollable: true,
            resizable: true,
            
            dataBound: gridDataBound,

            columns: [
                
                {field: "subscriber_name", title: "Name",width: "250px"},
                /*{field: "username", title: "Username",width: "auto"},
                {field: "email", title: "E-mail",width: "auto"},
                {field: "total_stb",title:"Total Device", headerAttributes:{"style":"text-align:center;"},attributes: {"class": "text-center"}, width:"120px",filterable:false},*/
                {field: "contact",title:"Phone Number", headerAttributes:{"style":"text-align:center;"},attributes: {"class": "text-center"}, width:"150px",filterable:true},
                {field: "total_packages", title:"Packages",headerAttributes:{"style":"text-align:center;"},attributes: {"class": "text-center"}, width:"90px",filterable:false,template:'# if(data.total_packages <= 0){# <span>0</span> #} else {# #=data.total_packages# #}#'},
                {field:"total_payable",title:"Total Payable",headerAttributes:{"style":"text-align:center;"},attributes: {"class": "text-center"},width:"120px",filterable:false,template:'# if(data.total_payable <= 0){# <span>0</span> #} else {# #=data.total_payable# #}#'},
                {field: "balance",title:"Balance",headerAttributes:{"style":"text-align:center;"}, attributes: {"class": "text-center"},width:"auto",filterable:false,template:'# if(data.balance <= 0){# <span>0</span> #} else {# #=data.balance# #}#'},
                {field: "subscription", title:"Subscription",headerAttributes:{"style":"text-align:center;"},attributes: {"class": "text-center"}, width:"auto",filterable:false,template: '# if(data.subscription==null) {# <span class="label label-success">Active</span> #} else {# <span class="label label-danger"><a style="color:white;" href="'+SITE_URL+'expired-packages/#=data.token#">Expired</a></span> #}#'},
                {field: "status", title: "Status",width:"auto",headerAttributes:{"style":"text-align:center;"},attributes: {"class": "text-center"},filterable:false,sortable:false,template: '# if(data.user_status==1) {# <span class="label label-success">Active</span> #} else {# <span class="label label-danger">Inactive</span> #}#'},
                {field: "", title: "Action",width: "115px",
                template:"<a  href='"+SITE_URL+"subscriber/view/#=data.token#' class='btn btn-default btn-xs' data-toggle='tooltip' data-placement='left' title='View'><i class='fa fa-search'></i></a> <a ng-if='permissions.edit_permission==\"1\"' href='"+SITE_URL+"subscriber/edit/#=data.token#' class='btn btn-default btn-xs' data-toggle='tooltip' data-placement='left' title='Edit'><i class='fa fa-pencil'></i></a>"+'# if(parseInt(data.balance)>=parseInt(data.total_payable) && data.subscription>0) {# <span style="cursor: pointer;" class="label label-success" ng-click="subscriberRenew(#=data.user_id#)">Renew</span> #}#'},
            ]
        };
    };

    generateKendoGird();

    $scope.changeBillingContact = function(){
        if($scope.profile.is_same_as_contact){

            $scope.profile.billing_contact = $scope.profile.contact;
            
        } else{

            $scope.profile.billing_contact = null;
            
        }
    };

    $scope.changeBillingAddressContact = function(){
        if($scope.billing_address.is_same_as_contact){
            $scope.billing_address.billing_contact = $scope.billing_address.contact;
        } else {
            $scope.billing_address.billing_contact = null;
        }
    }

    var validateForm = function(){
        $scope.$watch('profile.contact',function(val){
            var pattern = /[0-9]/
            if(val != null)
            {
                if(!val.match(pattern)){
                    $scope.warning_messages = 'Contact Number should be numerice value';
                    $scope.profile.contact = '';
                    $("html,body").animate({scrollTop:'0px'});
                }
            }
            
              
        });

        $scope.$watch('profile.billing_contact',function(val){
            var pattern = /[0-9]/
            if(val != null)
            {
                if(!val.match(pattern)){
                    $scope.warning_messages = 'Billing Contact Number should be numerice value';
                    $scope.profile.billing_contact = '';
                    $("html,body").animate({scrollTop:'0px'});
                }
            }

        });

    };

    $scope.$watch('showFrm',function(val){
        if(val)
        {
            validateForm();
        }
    });
        
    // Set Billing Address
    $scope.setBillingAddress = function(){

        if($scope.billing_address.is_same_as_profile){

            $scope.billing_address.id              = $scope.profile.id;
            $scope.billing_address.subscriber_name = $scope.profile.full_name;
            $scope.billing_address.email           = $scope.profile.email;
            $scope.billing_address.address1        = $scope.profile.address1;
            $scope.billing_address.address2        = $scope.profile.address2;
            $scope.billing_address.contact         = $scope.profile.contact;
            $scope.billing_address.billing_contact = $scope.profile.billing_contact;
            $scope.billing_address.country_id      = $scope.profile.country_id;
            $scope.billing_address.division_id     = $scope.profile.division_id;
            $scope.billing_address.district_id     = $scope.profile.district_id;
            $scope.billing_address.area_id         = $scope.profile.area_id;
            $scope.billing_address.sub_area_id     = $scope.profile.sub_area_id;
            $scope.billing_address.road_id         = $scope.profile.road_id;
            $scope.billing_address.token           = $scope.token;

            $scope.billing_address.is_same_as_contact = $scope.profile.is_same_as_contact;

        } else {
            $scope.billing_address = {id:'',subscriber_name:'',email:''};
        }
    };
    
    $scope.saveBillingAddress = function(){
        resetMessage();
        $scope.billing_address.form_type=0;
        var http = WebService.post('subscriber/save_billing_address',$scope.billing_address);
        http.then(function(response){
            var data = response.data;
            if(data.status == 400){
                $scope.warning_messages = data.warning_messages;
                $scope.success_message = '';
            }else{
                $scope.warning_messages = '';
                $scope.success_message = data.success_message;

                $scope.setTab('stb_card');
                $scope.billing_address_id = data.billing_address_id;
                $scope.loadNotification();
            }

            $("html,body").animate({scrollTop:'0px'});
        });
    };

    $scope.saveBusinessRegion = function(){
        resetMessage();
        $scope.business_region_l4 = angular.element('#business_region_l4').val();
        var formData = {
            region_l1_code: $scope.business_region_l1,
            region_l2_code: $scope.business_region_l2,
            region_l3_code: $scope.business_region_l3,
            region_l4_code: $scope.business_region_l4,
            token: $scope.token,
            form_type:0
        };


        var http = WebService.post('subscriber/save_business_region',formData);
        http.then(function(response){
            var data = response.data;
            if(data.status == 400){
                $scope.warning_messages = data.warning_messages;
                $scope.success_message = '';

            } else {
                if($scope.business_region_l4 != null)
                {
                    $scope.profile.region_l4_code = $scope.business_region_l4;
                }
                $scope.success_message = data.success_message;
                $scope.warning_messages = '';
                $scope.loadNotification();
                $scope.setTab('stb_card');

            }

        });
    };

    var loadPermissions = function(){
        var http = WebService.get('subscriber/ajax_get_permissions');
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.permissions=data.permissions;
            }
        });
    };

    loadPermissions();

    //loadBusinessRegion();
    loadPackages();
    loadProfiles();

    // load locations
    var loadLocations = function(type,model){
        var formData = {};

        if(type == 'divisions'){
            formData.country_id = model.country_id;
        }else if(type == 'districts'){
            formData.division_id = model.division_id;
        }else if(type == 'areas'){
            formData.district_id = model.district_id;
        }else if(type == 'sub_areas'){
            formData.area_id = model.area_id;
        }else if(type == 'roads'){
            formData.sub_area_id = model.sub_area_id;
        }

        var http = WebService.post('subscriber/location/ajax_get_request/'+type,formData);
        http.then(function(response){
            var data = response.data;
            if(type == 'divisions'){
                $scope.divisions = data;
                //$("#division_id").removeAttr('disabled');
            }else if(type == 'districts'){
                $scope.districts = data;
                //$("#district_id").removeAttr('disabled');
            }else if(type == 'areas'){
                $scope.areas = data;
                //$("#area_id").removeAttr('disabled');
            }else if(type == 'sub_areas'){
                $scope.sub_areas = data;
                //$("#sub_area_id").removeAttr('disabled');
            }else if(type == 'roads'){
                $scope.roads = data;
                //$("#road_id").removeAttr('disabled');
            }
            
        });
    };

    $scope.setRegionLevel2 = function(){
        var level_2 = eval($scope.business_region_l1);
        if($scope.regions != undefined){
            $scope.regions_level_2 = ($scope.regions[level_2])? $scope.regions[level_2].childs : [];
            $scope.regions_level_3 = [];
            $scope.regions_level_4 = [];
        }
    };

    $scope.setRegionLevel3 = function(){
        var level_3 = eval($scope.business_region_l2);
        if($scope.regions != undefined){
        $scope.regions_level_3 = ($scope.regions_level_2[level_3])? $scope.regions_level_2[level_3].childs : [];
        $scope.regions_level_4 = [];
        }
    };

    $scope.setRegionLevel4 = function(){
        var level_4 = eval($scope.business_region_l3);
        if($scope.regions != undefined){
            $scope.regions_level_4 = ($scope.regions_level_3[level_4])? $scope.regions_level_3[level_4].childs : [];
        }
    };

    $scope.setRegionLevel5 = function(){
        $scope.$watch('business_region_l4',function(obj){
            console.log(obj);
        });

    }

    $scope.$watch('profile.country_id',function(val){
        if(val != null){
            
            loadLocations('divisions',$scope.profile);
            $scope.districts = [];
            $scope.areas = [];
            $scope.sub_areas = [];
            $scope.roads = [];
        }
    });

    $scope.$watch('profile.division_id',function(val){
        if(val != null){
            
            loadLocations('districts',$scope.profile);
            $scope.areas = [];
            $scope.sub_areas = [];
            $scope.roads = [];
        }
    });

    $scope.$watch('profile.district_id',function(val){
        if(val != null){
            
            loadLocations('areas',$scope.profile);
            $scope.sub_areas = [];
            $scope.roads = [];
        }
    });

    $scope.$watch('profile.area_id',function(val){
        if(val != null){
            loadLocations('sub_areas',$scope.profile);
            $scope.profile.road_id = undefined;
            $scope.roads = [];
        }
    });

    $scope.$watch('profile.sub_area_id',function(val){
        if(val != null){
            
            loadLocations('roads',$scope.profile);
        }
    });

    // billing location
    $scope.$watch('billing_address.country_id',function(val){
        if(val != null){
            loadLocations('divisions',$scope.billing_address);
            /*$scope.billing_address.division_id = undefined;
            $scope.billing_address.district_id = undefined;
            $scope.billing_address.area_id = undefined;
            $scope.billing_address.sub_area_id = undefined;
            $scope.billing_address.road_id = undefined;*/
            $scope.districts = [];
            $scope.areas = [];
            $scope.sub_areas = [];
            $scope.roads = [];
        }
    });

    $scope.$watch('billing_address.division_id',function(val){
        if(val != null){
            loadLocations('districts',$scope.billing_address);
            /*$scope.billing_address.district_id = undefined;
            $scope.billing_address.area_id = undefined;
            $scope.billing_address.sub_area_id = undefined;
            $scope.billing_address.road_id = undefined;*/
            $scope.areas = [];
            $scope.sub_areas = [];
            $scope.roads = [];
        }
    });

    $scope.$watch('billing_address.district_id',function(val){
        if(val != null){
            loadLocations('areas',$scope.billing_address);
            /*$scope.billing_address.area_id = undefined;
            $scope.billing_address.sub_area_id = undefined;
            $scope.billing_address.road_id = undefined;*/
            $scope.sub_areas = [];
            $scope.roads = [];

        }
    });

    $scope.$watch('billing_address.area_id',function(val){
        if(val != null){
            loadLocations('sub_areas',$scope.billing_address);
            //$scope.billing_address.sub_area_id = undefined;
            $scope.roads = [];
        }
    });

    $scope.$watch('billing_address.sub_area_id',function(val){
        if(val != null){
            loadLocations('roads',$scope.billing_address);
            //$scope.billing_address.road_id = undefined;
        }
    });
    
    
    $scope.subscriberRenew = function(user_id)
    {
        var formData = {user_id: user_id};
        var http = WebService.post('subscriber/subscriber-renew', formData);
        http.then(function(response){
            var data = response.data;
            if(data.status==400){
                $scope.warning_messages = data.warning_messages;
                $scope.success_message = '';
            } else {
                $scope.success_message = data.success_messages;
                $scope.warning_messages = '';
                loadProfiles();
            }

            $("html,body").animate({scrollTop:'0px'});
        });
    };
    
        
    $scope.bulkRenew = function()
    {
        var http = WebService.post('subscriber/bulk-renew', {});
        http.then(function(response){
            var data = response.data;
            if(data.status==400){
                $scope.warning_messages = data.warning_messages;
                $scope.success_message = '';
            } else {
                $scope.success_message = data.success_messages;
                $scope.warning_messages = '';
                $scope.formData = '';
                loadProfiles();
            }
            $("html,body").animate({scrollTop:'0px'});
        });
    };

}]);

