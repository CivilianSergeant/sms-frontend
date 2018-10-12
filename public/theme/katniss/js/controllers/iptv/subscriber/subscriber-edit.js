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
app.controller('EditSubscriberProfile',['$scope','WebService','FileUploader',function($scope,WebService,FileUploader){

    $scope.profile = {id:'',subscriber_name:'',email:'',password:'',re_password:''};
    
    $scope.countries = $scope.divisions = $scope.districts = $scope.areas = $scope.sub_areas = $scope.roads = [];
    $scope.identity_verify_types = ['Nation ID','Passport','Utility Document'];
    $scope.token = token;
    $scope.identity = {};
    $scope.region = [];
    $scope.packages = [];
    $scope.selected_item = '';
    $scope.loader = 0;
    $scope.package_price = 0;
    $scope.stb_cards = [];
    $scope.assigned_packages = [];
    $scope.current_package_id = '';

    $scope.tabs = {profile:1,login:0,billing_address:0,documents:0,business_region:0,stb_card:0,package_assign:0,recharge:0,invoice:0,tools:0};

    $scope.billing_address = {id:'',name:'',subscriber_name:'',email:'',token:''};
    
    $scope.package_duration = 0;
    $scope.included_item_duration = null;
    $scope.pairing_id = null;
    $scope.billing_address_id = null;
    $scope.notStrongPassFlag = 0;
    $scope.checkRePasswordFlag = 0;
    $scope.pass_message = '';
    $scope.re_pass_message = '';
    $scope.success_message = '';

    $scope.fileUploadPhotoProgress = 0;
    $scope.fileUploadIdentityProgress = 0;
    $scope.fileUploadSubscriptionProgress = 0;

    /*$scope.$watch('assigned_packages',function(val){
        
        $scope.package_price=0
        for(v in val){   
            for(ap in $scope.assigned_packages){
               var item = $scope.assigned_packages[ap];
               if(val[v] == item.id){
                    $scope.package_price += eval(item.price);
               }
            }
        }
    });*/


    Object.size = function(obj) {
        var size = 0, key;
        for (key in obj) {
            if (obj.hasOwnProperty(key)) size++;
        }
        return size;
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
    $scope.closeAlertItem = function(i){
        if($scope.success_messages[i] != undefined){
            $scope.success_messages.splice(i,1);
        }
    };

    var uploader = $scope.uploader = new FileUploader({
        headers: {'X-Requested-With':'XMLHttpRequest'},
        url: SITE_URL+'iptv-subscribers/upload-photo'
    });

    uploader.onBeforeUploadItem = function(item) {
        uploader.progress = 0;
        $scope.fileUploadPhotoProgress = 0;
        item.formData.push({token:$scope.token,form_type:1});
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
            $scope.success_message = response.success_message;
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
        url: SITE_URL+'iptv-subscribers/upload-identity'
    });

    identityUploader.onBeforeUploadItem = function(item) {
        //item.formData.push($scope.identity);
        identityUploader.progress = 0;
        $scope.fileUploadIdentityProgress = 0;
        item.formData.push({token:$scope.token,form_type:1});
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
            $scope.success_message = response.success_message;
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
        url: SITE_URL+'iptv-subscribers/upload-subscription-copy'
    });

    subuscriptionUploader.onBeforeUploadItem = function(item) {
        subuscriptionUploader.progress = 0;
        $scope.fileUploadSubscriptionProgress = 0;
        item.formData.push({token:$scope.token,form_type:1});
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
            $scope.success_message = response.success_message;
            $scope.warning_messages = '';
            $scope.loadNotification();
        }else{
            subuscriptionUploader.progress = 0;
            $scope.fileUploadSubscriptionProgress = 0;
            $scope.warning_messages = response.warning_messages;
            $scope.success_message = '';
            $scope.uploadView = false;
        }
    };



    $scope.setTab = function(tab){
        resetMessage();
        switch(tab){
            case 'profile':
                $scope.tabs = {profile:1,login:0,billing_address:0,documents:0,business_region:0,stb_card:0,package_assign:0,recharge:0,invoice:0,tools:0};
                break;
            case 'login':
                
                $scope.tabs = {profile:0,login:1,billing_address:0,documents:0,business_region:0,stb_card:0,package_assign:0,recharge:0,invoice:0,tools:0};
                break;
            case 'billing_address':
                
                $scope.tabs = {profile:0,login:0,billing_address:1,documents:0,business_region:0,stb_card:0,package_assign:0,recharge:0,invoice:0,tools:0};
                break;
            case 'documents':
                
                $scope.tabs = {profile:0,login:0,billing_address:0,documents:1,business_region:0,stb_card:0,package_assign:0,recharge:0,invoice:0,invoice:0,tools:0};
                break;
            case 'business_region':
                loadProfile();
                $scope.tabs = {profile:0,login:0,billing_address:0,documents:0,business_region:1,stb_card:0,package_assign:0,recharge:0,invoice:0,tools:0};
                break;
            case 'stb_card':
                loadUnusedCards();
                $scope.tabs = {profile:0,login:0,billing_address:0,documents:0,business_region:0,stb_card:1,package_assign:0,recharge:0,invoice:0,tools:0};
                break;
            case 'package_assign':
                loadUnusedCards();
                loadBalance();
                loadPackages();
                $scope.tabs = {profile:0,login:0,billing_address:0,documents:0,business_region:0,stb_card:0,package_assign:1,recharge:0,invoice:0,tools:0};
                break;
            case 'invoice':
                $scope.tabs = {profile:0,login:0,billing_address:0,documents:0,business_region:0,stb_card:0,package_assign:0,recharge:0,invoice:1,tools:0};
                break;
            case 'tools':
                $scope.tabs = {profile:0,login:0,billing_address:0,documents:0,business_region:0,stb_card:0,package_assign:0,recharge:0,invoice:0,tools:1};
                break;
            
        }
    };
    

    $scope.addStbCard = function(){
        var stbCard = {
            pairing_id:'',
            stb_number:'',
            stb_type:'',
            smart_card_provider:'',
            smart_card_type:'',
            smart_card_number:'',
            stb_provider_id:'',
            smart_card_provider_id:'',
            smart_card_id:'',
            stb_box_id:''
        };

        $scope.stbs.filter(function(obj){
            if(obj.stb_number == $scope.stb_box_id)
            {
                stbCard.stb_provider    = obj.stb_provider;
                stbCard.stb_number      = obj.stb_number;
                stbCard.stb_type        = obj.stb_type;
                stbCard.stb_provider_id = obj.stb_provider_id;
                stbCard.stb_box_id      = obj.stb_box_id;
            }
        });

        $scope.smart_cards.filter(function(obj){
            if(obj.smart_card_number == $scope.smart_card_id)
            {
                stbCard.smart_card_provider    = obj.smart_card_provider;
                stbCard.smart_card_number      = obj.smart_card_number;
                stbCard.smart_card_type        = obj.smart_card_type;
                stbCard.smart_card_provider_id = obj.smart_card_provider_id;
                stbCard.smart_card_id          = obj.smart_card_id;
            }
        });

        $scope.stb_cards.push(stbCard);
        $scope.stb_box_id = $scope.smart_card_id = '';
    };

    $scope.deleteStbSmartCard = function(i){
        $scope.stb_cards.splice(i,1);
    };

    $scope.confirmStbSmartCard = function(i){
        var formData = $scope.stb_cards[i];
        formData.token  = $scope.token;
        formData.form_type=1;
        var http = WebService.post('subscriber/assign-stb-smartcard',formData);
        http.then(function(response){
            var data = response.data;
            if(data.status==400){
                $scope.warning_messages = data.warning_messages;
                $scope.success_messages = [];
            } else {
                $scope.success_messages = data.success_messages;
                $scope.warning_messages = '';
                $scope.stbs = data.stbs;
                $scope.smart_cards = data.cards;
                $scope.stb_cards = data.stb_cards;
                loadUnusedCards();
                $scope.loadNotification();

            }
            $("html,body").animate({scrollTop:'0px'});
        });
    }; 

    $scope.updateProfile = function(){
        resetMessage();
        $scope.profile.form_type=1;
        $scope.profile.photo = null;
        $scope.profile.identity_attachment = null;
        $scope.profile.subscription_copy = null;
        var http = WebService.post('iptv-subscribers/update-profile',$scope.profile);
        http.then(function(response){
            var data = response.data;
            if(data.status == 400){
                $scope.warning_messages = data.warning_messages;
                $scope.success_message = '';
            } else {
                $scope.success_message = data.success_message;
                console.log($scope.success_message);
                $scope.warning_messages = '';
                $scope.loadNotification();
                
            }
            $("html,body").animate({scrollTop:"0px"});
        });
    };

    $scope.updateLogin = function(){
        resetMessage();

        if($scope.profile.username=='' || $scope.profile.username == undefined){
            $scope.warning_messages = 'Username cannot be blank';
            return;
        }

        var formData = {
            token:$scope.token,
            username:$scope.profile.username,
            password:$scope.profile.password,
            is_remote_access_enabled:$scope.profile.is_remote_access_enabled,
            re_password:$scope.profile.re_password,
            form_type:1
        };
        var http = WebService.post('subscriber/update_login_info',formData);
        http.then(function(response){
            var data = response.data;
            if(data.status==400){
                $scope.warning_messages = data.warning_messages;
                $scope.success_message = '';
            } else {
                $scope.success_message = data.success_message;
                $scope.warning_messages = '';
                $scope.loadNotification();
            }
        });
    };

    $scope.setBillingAddress = function(){

        if($scope.billing_address.is_same_as_profile){
            
            /*$scope.billing_address.id              = $scope.profile.profile_id;*/
            $scope.billing_address.name = $scope.profile.subscriber_name;
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
            $scope.billing_address.token           = $scope.profile.token;

        } else {
            $scope.billing_address = {id:'',subscriber_name:'',email:''};
        }
    };
    
    $scope.saveBillingAddress = function(){
        $scope.billing_address.subscriber_name = $scope.billing_address.name;
        $scope.billing_address.form_type = 1;
        $scope.billing_address.token = $scope.profile.token;
        var http = WebService.post('iptv-subscribers/save-billing-address',$scope.billing_address);
        http.then(function(response){
            var data = response.data;
            if(data.status == 400){
                $scope.warning_messages = data.warning_messages;
                $scope.success_message = '';
            }else{
                $scope.warning_messages = '';
                $scope.success_message = data.success_message;
                $scope.loadNotification();
                if(data.billing_address_id != null)
                    $scope.billing_address_id = data.billing_address_id;
            }

            $("html,body").animate({scrollTop:'0px'});
        });
    };

    $scope.setRegion = function(l1,l2,l3,l4){
        
        if(l1 != null && l2 == null & l3 == null && l4 == null)
        {
            /*$scope.region_l2.status=true;
            $scope.region_l3.status=true;
            $scope.region_l4.status=true;*/

            var id = l1.region_id;
            var indexOf = $scope.region.indexOf(id);
            if(indexOf != -1)
            {
                $scope.region.splice(indexOf,1);
                $scope.region = [];
            } else {
                $scope.region.push(id);
                var l2 = $scope.regions[l1.id].childs;
                for(l in l2){
                    var l2Index = $scope.region.indexOf(l2[l].region_id);

                    if(l2Index == -1)
                    {
                            $scope.region.push(l2[l].region_id);
                    }

                    var l2Childs = (l2[l].childs != null)? l2[l].childs : null;
                    if(l2Childs != null){
                        var l3 = l2Childs;
                        for(l_3 in l3){
                            var l3Index = $scope.region.indexOf(l3[l_3].region_id);
                            if(l3Index == -1){
                                $scope.region.push(l3[l_3].region_id);
                            }
                        }
                    }

                }
            }
        }

        if(l1 != null && l2 != null & l3 == null && l4 == null)
        {
            var id = l2.region_id;
            
            var indexOf = $scope.region.indexOf(id);
            if(indexOf != -1)
                $scope.region.splice(indexOf,1);
            else
            {
                $scope.region.push(id);
            }    
        }
        

        if(l1 != null && l2 != null & l3 != null && l4 == null)
        {
            var id = l3.region_id; //l1.id+'-'+l2.id+'-'+l3.id+'-0';
            var indexOf = $scope.region.indexOf(id);
            if(indexOf != -1)
                $scope.region.splice(indexOf,1);
            else
                $scope.region.push(id);
        }

        if(l1 != null && l2 != null & l3 != null && l4 != null)
        {
            var id = l4.region_id; // l1.id+'-'+l2.id+'-'+l3.id+'-'+l4.id;
            var indexOf = $scope.region.indexOf(id);
            if(indexOf != -1)
                $scope.region.splice(indexOf,1);
            else
                $scope.region.push(id);
        }
        
    };

    $scope.saveBusinessRegion = function(){
        resetMessage();

        var formData = {
            region_l1_code: $scope.business_region_l1,
            region_l2_code: $scope.business_region_l2,
            region_l3_code: $scope.business_region_l3,
            region_l4_code: $scope.business_region_l4,
            token: $scope.token,
            form_type:1
        };

        var http = WebService.post('subscriber/save_business_region',formData);
        http.then(function(response){
            var data = response.data;
            if(data.status == 400){
                $scope.warning_messages = data.warning_messages;
                $scope.success_message = '';
            } else {
                $scope.success_message = data.success_message;
                $scope.warning_messages = '';
                $scope.loadNotification();
                loadProfile();

            }
            $("html,body").animate({scrollTop:'0px'});
        });
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

    $scope.assignPackages = function(){
        setPairingID();
        resetMessage();
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

        
        formData.form_type = 1;


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
    $scope.assignPackage = function(){

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

    };

    // delte package item 
    $scope.deletePackageItem = function(i){
        var item = $scope.assigned_packages[i];
        item.package_name = '';
        item.price = '';
        item.duration = '';
        item.no_of_program = '';
        item.charge_type = '';
        item.start_date = '';
        item.expire_date = '';

    };

    // confirm package Item
    $scope.confirmPackageItem = function(i){
        var item = $scope.assigned_packages[i];
        item.token = $scope.token;
        item.balance = $scope.balance;
        var http = WebService.post('subscriber/save_assign_packages',item);
        http.then(function(response){
            var data = response.data;
            console.log(data);
            if(data.status == 400){
                $scope.warning_messages = data.warning_messages;
                $scope.success_message = '';
            } else {
                $scope.success_message = data.success_message;
                $scope.warning_messages = '';
                loadBalance();
                loadUnusedCards();
                loadProfile();
                $scope.stb_card_id = null;
                $scope.selected_package = null;
                $scope.charge_type = null;
            }

            
        });
    };

    /*$scope.unsubscribePackageItem = function(i){
        var item = $scope.assigned_packages[i];
        item.token = $scope.token;
        var http = WebService.post('subscriber/unsubscribe_package',item);
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.success_message = data.success_message;
                $scope.assigned_packages = [];
                loadBalance();
                loadProfile();
            }
        });
        
    };*/

    // reset all alert message
    var resetMessage = function()
    {
        $scope.success_message = '';
        $scope.warning_messages = '';
        $scope.error_messages = '';
    }

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

    var loadUnusedCards = function(){
        var formData = {
            token: $scope.token,
        };
        var http = WebService.post('subscriber/ajax_get_unused_cards',formData);
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.stbs = data.stbs;
                $scope.smart_cards = data.cards;
                $scope.stb_cards = data.stb_cards;
                $scope.unassigned_stb_cards = data.unassigned_stb_cards;

                $scope.warning_messages = '';
                
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

    var loadBusinessRegion = function(){
        var http = WebService.get('subscriber/ajax_load_region');
        http.then(function(response){
            var data = response.data;
            $scope.regions = data;

        });
    };

    // load mso profile info
    var loadProfile = function(){
        // show hide loader base on profile data loaded or not
        $scope.$watch('profile',function(val){
            
            if(val.id != ""){
                $scope.loader = 0;
                
            } else {

                $scope.loader = 1;
            }

        });

        // load balance of subscriber
        loadBalance();
        
        var http = WebService.get('subscriber/ajax_get_profile/'+$scope.token);
        http.then(function(response){
            var data = response.data;
            $scope.profile = data.profile;

            $scope.billing_address = data.billing_address;

            if($scope.billing_address != null)
            {
                $scope.billing_address_id = data.billing_address.billing_address_id;
                $scope.billing_address.is_same_as_profile = eval($scope.billing_address.is_same_as_profile);
                $scope.billing_address.is_same_as_contact = eval($scope.billing_address.is_same_as_contact);
            }
            $scope.profile.password = '';
            $scope.profile.is_same_as_contact = eval($scope.profile.is_same_as_contact);
            $scope.profile.is_remote_access_enabled = eval($scope.profile.is_remote_access_enabled);
            $scope.identity.identity_attachment = data.profile.identity_attachment;
            $scope.countries = data.countries;
            $scope.lco_profile = data.lco_profile;
            loadUnusedCards();
            /*$scope.packages  = data.packages;
            $scope.assigned_packages = data.assigned_packages;
            $scope.current_package_id = data.current_package_id;
*/
            $scope.$watch('profile.region_l1_code',function(val){
                if(val != null){
                    var l1code = eval($scope.profile.region_l1_code);
                    if($scope.regions != undefined && $scope.regions[l1code] != undefined){
                        var level_two = $scope.regions[l1code];
                        $scope.business_region_l1 = $scope.profile.region_l1_code;
                        $scope.regions_level_2 = (level_two.childs !=null)? level_two.childs : null;
                    }
                    
                }
            });

            $scope.$watch('profile.region_l2_code',function(val){
                if(val != null){
                    var l2code = eval($scope.profile.region_l2_code);
                    if($scope.regions_level_2 != undefined){
                        var level_three = $scope.regions_level_2[l2code];
                        $scope.business_region_l2 = $scope.profile.region_l2_code;
                        $scope.regions_level_3 = (level_three !=null && level_three.childs !=null)? level_three.childs : null;
                    }
                }
                
                
            });

            $scope.$watch('profile.region_l3_code',function(val){
                if(val != null){
                    var l3code = eval($scope.profile.region_l3_code);
                    if($scope.regions_level_3 != undefined){
                        var level_four = $scope.regions_level_3[l3code];
                        $scope.business_region_l3 = $scope.profile.region_l3_code;
                        $scope.regions_level_4 = (level_four!=null && level_four.childs != null)? level_four.childs : null;
                    }
                    
                }
                
                
            });

            $scope.$watch('profile.region_l4_code',function(){
                $scope.business_region_l4 = $scope.profile.region_l4_code;
            });




        });
    };

    
    loadBusinessRegion();


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
                $("#division_id").removeAttr('disabled');
            }else if(type == 'districts'){
                $scope.districts = data;
                $("#district_id").removeAttr('disabled');
            }else if(type == 'areas'){
                $scope.areas = data;
                $("#area_id").removeAttr('disabled');
            }else if(type == 'sub_areas'){
                $scope.sub_areas = data;
                $("#sub_area_id").removeAttr('disabled');
            }else if(type == 'roads'){
                $scope.roads = data;
                $("#road_id").removeAttr('disabled');
            }
            
        });
    };

    $scope.setRegionLevel2 = function(){
        var level_2 = eval($scope.business_region_l1);
        $scope.regions_level_2 = $scope.regions[level_2].childs;
        $scope.regions_level_3 = [];
        $scope.regions_level_4 = [];
    };

    $scope.setRegionLevel3 = function(){
        var level_3 = eval($scope.business_region_l2);
        $scope.regions_level_3 = $scope.regions_level_2[level_3].childs;
        $scope.regions_level_4 = [];
    };

    $scope.setRegionLevel4 = function(){
        var level_4 = eval($scope.business_region_l3);
        $scope.regions_level_4 = $scope.regions_level_3[level_4].childs;
    };


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
            //$scope.profile.road_id = "";
            $scope.sub_areas = [];
            $scope.roads = [];
        }
    });

    $scope.$watch('profile.sub_area_id',function(val){
        if(val != null){
            
            loadLocations('roads',$scope.profile);
        }
    });

    // billing address locations
    $scope.$watch('billing_address.country_id',function(val){
        if(val != null){
            
            loadLocations('divisions',$scope.billing_address);
            $scope.districts = [];
            $scope.areas = [];
            $scope.sub_areas = [];
            $scope.roads = [];
        }
    });

    $scope.$watch('billing_address.division_id',function(val){
        if(val != null){
            
            loadLocations('districts',$scope.billing_address);
            $scope.areas = [];
            $scope.sub_areas = [];
            $scope.roads = [];
        }
    });

    $scope.$watch('billing_address.district_id',function(val){
        if(val != null){
            loadLocations('areas',$scope.billing_address);
            $scope.sub_areas = [];
            $scope.roads = [];
        }
    });

    $scope.$watch('billing_address.area_id',function(val){
        if(val != null){
            loadLocations('sub_areas',$scope.billing_address);
            $scope.roads = [];
        }
    });

    $scope.$watch('billing_address.sub_area_id',function(val){
        if(val != null){
            loadLocations('roads',$scope.billing_address);

        }
    });

    loadProfile();

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

    // validate Form
    var validateForm = function(){

        $scope.$watch('profile.contact',function(val){
            var pattern = /[0-9]/
            //console.log(val);
           // if($scope.profile.contact != undefined){
                if(val != null)
                {
                    if(!val.match(pattern)){
                        $scope.warning_messages = 'Contact Number should be numerice value';
                        $scope.profile.contact = '';
                        $("html,body").animate({scrollTop:"0px"});
                    }
                }
          //  }

        });

        $scope.$watch('profile.billing_contact',function(val){
            var pattern = /[0-9]/
            if($scope.billing_address != undefined){
                if(val != null)
                {
                    if(!val.match(pattern)){
                        $scope.warning_messages = 'Contact Number should be numerice value';
                        $scope.profile.billing_contact = '';
                        $("html,body").animate({scrollTop:"0px"});
                    }
                }
            }
        });

    };



    validateForm();
    
    var setOnLoadTab = function()
    {

        var tab = window.location.hash;
        tabName = (tab.substr(1,tab.length));
        switch(tabName){
            case 'package_assign':
                $scope.$watch('profile',function(obj){

                if(obj.id != null){
                    if(obj.region_l1_code && obj.region_l2_code &&
                        obj.region_l3_code && obj.region_l4_code){

                        $scope.setTab(tabName);
                    }
                }

                });
                break;
        }

        
    }    

    setOnLoadTab();

    /******** Tool Tab functions ****/

    $scope.sendAuthorizationRequest = function(){
        if($scope.tools_stb_card_id == '' || $scope.tools_stb_card_id == null || $scope.tools_stb_card_id == undefined){
            $scope.warning_messages = 'Please Select Pairing ID';
            return;
        }

        var formData = {
            token: $scope.token,
            pairing_id: $scope.tools_stb_card_id
        }

        var http = WebService.post('subscriber/send-authorization-request',formData);
        http.then(function(response){
            var data = response.data;
            if(data.status == 400){
                $scope.warning_messages = data.warning_messages;
            }else{
                $scope.success_message = data.success_message;
            }
            $("html,body").animate({scrollTop:'0px'});
        });
    };



    $scope.sendPairRequest = function(){
        if($scope.tools_stb_card_id == '' || $scope.tools_stb_card_id == null || $scope.tools_stb_card_id == undefined){
            $scope.warning_messages = 'Please Select Pairing ID';
            return;
        }

        var formData = {
            token: $scope.token,
            pairing_id: $scope.tools_stb_card_id
        }

        var http = WebService.post('subscriber/send-pair-request',formData);
        http.then(function(response){
            var data = response.data;
            if(data.status == 400){
                $scope.warning_messages = data.warning_messages;
            }else{
                $scope.success_message = data.success_message;
            }
            $("html,body").animate({scrollTop:'0px'});
        });
    };

    $scope.sendUnPairRequest = function(){
        if($scope.tools_stb_card_id == '' || $scope.tools_stb_card_id == null || $scope.tools_stb_card_id == undefined){
            $scope.warning_messages = 'Please Select Pairing ID';
            return;
        }

        var formData = {
            token: $scope.token,
            pairing_id: $scope.tools_stb_card_id
        }

        var http = WebService.post('subscriber/send-unpair-request',formData);
        http.then(function(response){
            var data = response.data;
            if(data.status == 400){
                $scope.warning_messages = data.warning_messages;
            }else{
                $scope.success_message = data.success_message;


            }
            $("html,body").animate({scrollTop:'0px'});
        });
    };
    
}]);
