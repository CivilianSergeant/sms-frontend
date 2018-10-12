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

    $scope.epgId = epgId;

    $scope.closeAlert = function(){
        $scope.success_messages = '';
        $scope.warning_messages = '';
        $scope.error_messages = '';
    };
    $scope.formData = {};
    $scope.formData.duration = 30;
    $scope.formData.repeats = [];
    $scope.duration_in_text = '';
    $scope.weekDays = [];

    $scope.addRow = function(){
        var repeats = {repeat_date:'',repeat_time:''};
        $scope.formData.repeats.push(repeats);
    };

    $scope.hideForm = function(){
        $scope.showFrm = 0;
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

    var loadEpg = function(){
        var http = WebService.get('manage-epg/ajax-get-epg/'+$scope.epgId);
        http.then(function(response){
            var data = response.data;
            if(data.status==200){
                $scope.formData = data.epg;
                var pattern = /\:/
                var duration = data.epg.duration;
                if(pattern.test(duration)){
                    var timeArr = duration.split(':');
                    var hours = timeArr[0];
                    var minutes = timeArr[1];
                    minutes = (parseInt(hours)*60)+parseInt(minutes);
                    $scope.duration_in_text = duration;
                    $scope.formData.duration = minutes;
                }
                
                if($scope.formData.program_description != undefined){
                    tinymce.activeEditor.setContent($scope.formData.program_description);
                }

                if($scope.formData.week_days != null)
                    $scope.weekDays = $scope.formData.week_days.toString().split(',');

                var logo = new Image();
                var poster = new Image();

                logo.src = BASE_URL+$scope.formData.program_logo;
                poster.src= BASE_URL+$scope.formData.program_poster;
                if($scope.formData.program_logo != null)
                    $("input[name=program_logo]").next().attr('src',logo.src).removeClass('hidden');
                if($scope.formData.program_poster != null)
                    $("input[name=program_poster]").next().attr('src',poster.src).removeClass('hidden');
            }
        });


    };

    loadEpg();

    /*$scope.setStartTime = function(){
        var startDate = $scope.start_time;

        var minutes = startDate.getMinutes();
        startDate.setMinutes(minutes+$scope.formData.duration);

        var endTime = startDate.toLocaleString().split(",");
        $scope.end_time = startDate;
        $scope.formData.end_time = endTime[1].trim().replace(":00",'');

    };*/

    $scope.setStartTime = function(){

//        var start_Date = $("input#startTime").val();
//        start_Date = start_Date.toString().split(' ');
//        var clickedMeridiem = start_Date[1];
//
//        start_Date = start_Date[0].split(':');
//        var startDate = new Date($scope.start_time);
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
//            if(hours == 12){
//
//                hours = parseInt(hours) + 12;
//                startDate.setHours(hours);
//                startDate.setMinutes(minutes);
//            }else{
//                startDate.setHours(hours);
//                startDate.setMinutes(minutes);
//            }
//        }


        var startDate = new Date($scope.start_time);
        console.log(startDate);

        var minutes = startDate.getTime();
        
        if(!isNaN(minutes)){
            startDate.setTime(minutes + ($scope.formData.duration*60)*1000);
            var endTime = startDate.toLocaleString().split(",");
            
            $scope.formData.end_time = endTime[1].trim().replace(":00",'');
        }else{
            $scope.formData.end_time = $scope.formData.start_time;
        }


        $scope.end_time = startDate;


    };

    $scope.resetTime = function(){
        var start_Date = $("input#startTime").val();
        if(start_Date != null || start_Date != "" || start_Date != undefined){
            $scope.setStartTime();
        }
    };


    $scope.saveEPG = function(){

        $scope.formData.program_id = $("#channelList").val();
        $scope.formData.program_logo = $("input[name=program_logo]").next().attr('src');
        $scope.formData.program_poster = $("input[name=program_poster]").next().attr('src');
        $scope.formData.program_description = tinymce.activeEditor.getContent({format : 'raw'});
        
        $scope.formData.end_time = $("#endTime").val();
        
        if($scope.weekDays.length>0){
            $scope.formData.weekDays = $scope.weekDays;
        }

        var http = WebService.post('manage-epg/update-epg',$scope.formData);
        http.then(function(response){
           var data = response.data;
            if(data.status==400){
                $scope.warning_messages = data.warning_messages;
                $("html,body").animate({scrollTop:'0px'});
            }else{
                $scope.success_messages = data.success_messages;
                $("html,body").animate({scrollTop:'0px'});
                //window.location = SITE_URL+'manage-epg';
            }

        });
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

}]);