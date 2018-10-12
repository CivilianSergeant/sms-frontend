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

app.controller('CreateEPG',['$scope','WebService',function($scope,WebService){
    $scope.filterData = {};

    $scope.weekDays = [];

    $scope.closeAlert = function(){
        $scope.success_messages = '';
        $scope.warning_messages = '';
        $scope.error_messages = '';
    };
    $scope.formData = {};
    $scope.formData.duration = 30;
    $scope.formData.repeats = [];

    $scope.addRow = function(){
        var repeats = {repeat_date:'',repeat_start_time:'',repeat_end_time:''};
        $scope.formData.repeats.push(repeats);
    };

    $scope.hideForm = function(){
        $scope.showFrm = 0;
        $scope.formData = {};
        $scope.formData.duration = 30;
        $scope.formData.repeats = [];
    };

    $scope.showForm = function()
    {
        if($scope.permissions.create_permission == '1'){
            $scope.showFrm = 1;
        }else{
            $scope.warning_messages = "Sorry! You don't have permission to create Set-top Box";
        }

    };

    var loadPermissions = function(){
        var http = WebService.get('manage-epg/ajax-get-permissions');
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.permissions=data.permissions;

            }
        });
    };

    loadPermissions();

    var loadChannels = function(){
        var http = WebService.get('manage-epg/ajax-get-channels');
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.channels = data.channels;
            }
        });
    };

    loadChannels();

    var generateKendoGird = function(){
        $scope.mainGridOptions = {

            dataSource: {
                schema: {
                    data: "epgs",
                    total: "total"
                },
                transport: {
                    read: {
                        url: "manage-epg/ajax-get-epgs",
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
                /*{field: "channel_name", title: "Channel Name",width: "auto",filterable:true},*/
                {field: "program_name", title: "Program Name",width: "auto",filterable:true},
                {field: "epg_type", title: "EPG TYPE",width: "100px",filterable:false},
                {field: "show_date", title: "Show Date/ Week Days",width: "250px;",filterable:false,template:'#if(data.epg_type=="FIXED"){# <span>#=data.show_date#</span> #}else{# <span>#=data.week_days#</span> #}#'},
                {field: "start_time",title: 'Start Time',width:"auto",filterable:false},
                {field: "end_time",title: 'End Time',width:"auto",filterable:false},
                {field: "", title: "Action",width: "auto",template:"<a href='"+SITE_URL+"manage-epg/view/#=data.id#' class='btn btn-default btn-xs' data-toggle='tooltip' data-placement='left' title='View'><i class='fa fa-search'></i></a> <a ng-if='permissions.edit_permission==\"1\"' href='"+SITE_URL+"manage-epg/edit/#=data.id#' class='btn btn-default btn-xs' data-toggle='tooltip' data-placement='left' title='Update'><i class='fa fa-pencil'></i></a> <a ng-if='permissions.delete_permission==\"1\"' ng-click='deleteItem(#=data.id#)' class='btn btn-danger btn-xs'><i class='fa fa-trash'></i></a>"},
            ]
        };
    };

    generateKendoGird();

    $scope.setStartTime = function(){

        
        var startDate = new Date($scope.start_time);
        console.log(startDate);
//
//        var hours = start_Date[0];
//        var minutes = start_Date[1];
//
//        if(clickedMeridiem == "PM" || clickedMeridiem == "pm"){
//
//            if(hours == 12){
//                startDate.setHours(hours);
//                startDate.setMinutes(minutes);
//            }else{
//                hours = parseInt(hours) + 12;
//                startDate.setHours(hours);
//                startDate.setMinutes(minutes);
//            }
//        }
//
//        if(clickedMeridiem == "AM" || clickedMeridiem == "am"){
//
//            if(hours == 12){
//
//                hours = parseInt(hours) + 12;
//                startDate.setHours(hours);
//                startDate.setMinutes(minutes);
//            }else{
//
//                startDate.setHours(hours);
//                startDate.setMinutes(minutes);
//            }
//        }
//
//
        var minutes = startDate.getTime();
//        console.log($scope.formData.end_time);
        if(!isNaN(minutes)){
            startDate.setTime(minutes + ($scope.formData.duration*60)*1000);
            var endTime = startDate.toLocaleString().split(",");
            $scope.formData.end_time = endTime[1].trim().replace(":00",'');
        }else{
            $scope.formData.end_time = $scope.formData.start_time;
        }

        
        $scope.end_time = startDate;

    };
    
    $scope.setRepeatStartTime = function(index){
           
        var start_Date = $($("input.repeatStartTime")[index]).val();
        
        start_Date = start_Date.toString().split(' ');
        var clickedMeridiem = start_Date[1];

        start_Date = start_Date[0].split(':');
        
        var startDate = new Date();
        

        var hours = parseInt(start_Date[0]);
        var minutes = parseInt(start_Date[1]);
        
        
        
        if(clickedMeridiem == "PM" || clickedMeridiem == "pm"){

            if(hours == 12){
                startDate.setHours(hours);
                startDate.setMinutes(minutes);
            }else{
                hours = parseInt(hours) + 12;
                startDate.setHours(hours);
                startDate.setMinutes(minutes);
            }
        }

        if(clickedMeridiem == "AM" || clickedMeridiem == "am"){

            if(hours == 12){

                hours = parseInt(hours) + 12;
                startDate.setHours(hours);
                startDate.setMinutes(minutes);
            }else{

                startDate.setHours(hours);
                startDate.setMinutes(minutes);
            }
        }


        var minutes = startDate.getTime();
        
        if(!isNaN(minutes)){
            console.log(minutes);
            startDate.setTime(minutes + ($scope.formData.duration*60)*1000);
            var endTime = startDate.toLocaleString().split(",");
            if($scope.formData.repeats.length > 0)
            {
                $scope.formData.repeats[index].repeat_end_time = endTime[1].trim().replace(":00",'');
            }
        }else{
            if($scope.formData.repeats.length > 0)
            {
                $scope.formData.repeats[index].repeat_end_time = $scope.formData.repeats[index].repeat_start_time;
            }
        }


       

    };

    $scope.resetTime = function(){
        var start_Date = $("input#startTime").val();
        if(start_Date != null || start_Date != "" || start_Date != undefined){
            $scope.setStartTime();
        }
    };


    $scope.saveEPG = function(){

        $scope.formData.program_logo = $("input[name=program_logo]").next().attr('src');
        $scope.formData.program_poster = $("input[name=program_poster]").next().attr('src');
        $scope.formData.program_description = tinymce.activeEditor.getContent({format : 'raw'});
        
        $scope.formData.end_time = $("#endTime").val();

        if($scope.weekDays.length>0){
            $scope.formData.weekDays = $scope.weekDays;
        }

        console.log($scope.formData);
        var http = WebService.post('manage-epg/save-epg',$scope.formData);
        http.then(function(response){
           var data = response.data;
            if(data.status==400){
                $scope.warning_messages = data.warning_messages;
                $("html,body").animate({scrollTop:'0px'});
            }else{
                $scope.success_messages = data.success_messages;
                $("html,body").animate({scrollTop:'0px'});
                $scope.hideForm();
                generateKendoGird();
            }

        });
    };

    $scope.deleteItem = function(id){
        $scope.delete_flag = id;
    };

    $scope.confirm_delete = function(){
        var http = WebService.get('manage-epg/delete/'+$scope.delete_flag);
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                window.location.reload();
            }
        });
    };

    $scope.cancel_delete = function(){
        $scope.delete_flag = '';
    };

    $scope.deleteRepeatRow = function(i){
        if($scope.formData.repeats  != undefined){
            $scope.formData.repeats.splice(i,1);
        }
    };

    $scope.setDay = function(day){
        var index = $scope.weekDays.indexOf(day);
        if(index == -1){
            $scope.weekDays.push(day);
            $("#"+day).removeClass('btn-info').addClass('btn-success');
        }else{
            $scope.weekDays.splice(index,1);
            $("#"+day).removeClass('btn-success').addClass('btn-info');
        }
    };

    $scope.searchEpg = function(){

        var grid = $('#epg-grid').data("kendoGrid");
        grid.dataSource.transport.options.read
            .url=SITE_URL+"manage-epg/ajax-get-epgs?channel_id="+$scope.filterData.channel_id+
            "&show_date="+$scope.filterData.show_date+"&start_time="+$scope.filterData.start_time+
            "&end_time="+$scope.filterData.end_time;
        grid.dataSource.read();
        grid.refresh();

    };

}]);