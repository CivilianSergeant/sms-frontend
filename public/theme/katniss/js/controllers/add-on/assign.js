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
app.controller('PackageAssign',['$scope','WebService',function($scope,WebService){
    $scope.subscribers = [];
    $scope.packages = $scope.assigned_packages = [];
    $scope.add_on_packages = [];
    $scope.subscriber_token = null;

    var loadSubscribers = function(){
        var http = WebService.get('add-on-package-assign/ajax_load_subscribers');
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.subscribers = data.subscribers;
            }
        });
    };

    var loadAddOnPackages = function(){
        var http = WebService.get('add-on-package-assign/ajax_load_package');
        http.then(function(response){
           var data = response.data;
            if(data.status == 200){
                $scope.packages = data.packages;
            }
        });
    };

    $scope.isDisabledAssignPackage = function(){
        if($scope.selected_package==null){
            return 1;
        }
        return 0;
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
        $scope.warning_messages = $scope.error_messages = $scope.success_messages = '';
    };

    $scope.getPairingId = function()
    {

        $scope.subscribers.filter(function(obj){

            if($scope.subscriber_id == obj.user_id){
                $scope.subscriber_name = obj.subscriber_name;
                $scope.subscriber_token = obj.token;
            }
        });
        loadBalance();
        var http = WebService.post('add-on-package-assign/ajax_pairing_id',{subscriber_id:$scope.subscriber_id});
        http.then(function(response){
            var data = response.data;
            if(data.status == 200)
            {
                $scope.stb_card_pairs = data.stb_card_pairs;

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

    var loadBalance = function(){
        var http = WebService.post('add-on-package-assign/ajax_get_balance',{token:$scope.subscriber_token});
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.balance = data.balance;
            }


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
                subscriber: $scope.subscriber_id,
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
            $scope.assigned_packages = [];
            $scope.included_item_duration = null;
        }

       // }



    };

    $scope.confirmAddOnPackage = function(i){
        $scope.closeAlert();
        if($scope.add_on_packages[i] != undefined){
            var item = $scope.add_on_packages[i];
            var http = WebService.post('add-on-package-assign/save_assign_packages',item);
            http.then(function(response){
                var data = response.data;
                if(data.status == 200){
                    $scope.add_on_packages[i].user_package_id = data.user_package_id;
                    $scope.success_messages = data.success_messages;
                }else{
                    $scope.warning_messages = data.warning_messages;

                }
            });
        }
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

    loadSubscribers();

    loadAddOnPackages();

}]);
app.controller('Subscribers',['$scope','WebService',function($scope,WebService){
    /*var loadProfiles = function(){

        var http = WebService.get('add_on_package/ajax_load_profiles');
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){

                generateKendoGird($scope.items);

            }
        });
    };*/

    var generateKendoGird = function(items){
        $scope.mainGridOptions = {
            dataSource: {
                /*
                 static non server-side
                 type: "jsonp",
                 data:items,
                 pageSize: 10,*/
                transport: {
                    read: {
                        url: SITE_URL+"add-on-package-subscriber/ajax_load_profiles",
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

                {field: "subscriber_name", title: "Name",width: "auto"},
                /*{field: "username", title: "Username",width: "auto"},
                 {field: "email", title: "E-mail",width: "auto"},*/
                {field: "total_stb",title:"Total STB", headerAttributes:{"style":"text-align:center;"},attributes: {"class": "text-center"}, width:"auto",filterable:false},
                {field: "total_packages", title:"Packages",headerAttributes:{"style":"text-align:center;"},attributes: {"class": "text-center"}, width:"auto",filterable:false,template:'# if(data.total_packages <= 0){# <span>0</span> #} else {# #=data.total_packages# #}#'},
                /*{field:"total_payable",title:"Total Payable",headerAttributes:{"style":"text-align:center;"},attributes: {"class": "text-center"},width:"120px",filterable:false,template:'# if(data.total_payable <= 0){# <span>0</span> #} else {# #=data.total_payable# #}#'},
                {field: "balance",title:"Balance",headerAttributes:{"style":"text-align:center;"}, attributes: {"class": "text-center"},width:"auto",filterable:false,template:'# if(data.balance <= 0){# <span>0</span> #} else {# #=data.balance# #}#'},*/
                {field: "subscription", title:"Subscription",headerAttributes:{"style":"text-align:center;"},attributes: {"class": "text-center"}, width:"auto",filterable:false,template: '# if(data.subscription==null) {# <span class="label label-success">Active</span> #} else {# <span class="label label-danger"><a style="color:white;" href="'+SITE_URL+'expired-packages/#=data.token#">Expired</a></span> #}#'},
                {field: "status", title: "Status",width:"auto",headerAttributes:{"style":"text-align:center;"},attributes: {"class": "text-center"},filterable:false,sortable:false,template: '# if(data.user_status==1) {# <span class="label label-success">Active</span> #} else {# <span class="label label-danger">Inactive</span> #}#'},
                /* {field: "package_start_date",title:"Start Date",filterable:false,width:"160px"},
                {field: "package_expire_date",title:"Expire Date",filterable:false,width:"160px"},
                {field: "", title: "Action",width: "auto",
                    template:"<a href='"+SITE_URL+"foc-subscriber/view/#=data.token#' class='btn btn-default btn-xs' data-toggle='tooltip' data-placement='left' title='View'><i class='fa fa-search'></i></a>"}*/
            ]
        };
    };

    generateKendoGird();
}]);