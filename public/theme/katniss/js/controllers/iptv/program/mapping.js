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
app.controller('MappingProgram',['$scope','WebService',function($scope,WebService){
    $scope.hls = [];
    $scope.operators = [];
    $scope.formData = {};
    $scope.formData.programId = programId;
    $scope.formData.hls = [];
    $scope.instances = [];
    $scope.parentId = parentId;

    $scope.addRow = function(){
        var instance = null
        for(var i in $scope.instances){

            if($scope.instances[i].id == $scope.formData.instance_id){
                instance = $scope.instances[i];
                break;
            }
        }

        var contentDir = (programDir != undefined || programDir != null)? programDir : "$directory";
        var domainUrl = instance.alias_domain_url+'/mobile_hls/'+contentDir+'/5/';
        var hls = {hls_url_mobile:domainUrl,hls_url_stb:domainUrl,hls_url_web:domainUrl};
        if($scope.formData.hls.length <= 0)
            $scope.formData.hls.push(hls);
    };

    $scope.closeAlert = function(){
        $scope.warning_messages = '';
        $scope.error_messags = '';
        $scope.success_messages = '';
    };

    var loadLco = function(){
        var http = WebService.get('channels/ajax-get-lco');
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.operators = data.lco;
            }
        });
    };

    $scope.loadStreamerInstances = function()
    {
        var http = WebService.get('channels/ajax-get-streamer-instance/'+$scope.formData.operator_id);
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.instances = data.instances;
            }
        });
    };

    $scope.deleteRow = function(i){

        var item = $scope.formData.hls[i];

        if(item != null){
          //  console.log(item,i,$scope.formData.hls);
            $scope.formData.hls.splice(i,1);
        }
    };

    $scope.saveMapping = function(){
        if($scope.formData.operator_id == null){
            $scope.warning_messages = 'Please Select Operator/LCO';
            $('html,body').animate({scrollTop:'0px'});
            return false;
        }
        if($scope.formData.instance_id == null){
            $scope.warning_messages = 'Please Select Streamer Instance';
            $('html,body').animate({scrollTop:'0px'});
            return false;
        }

        for(i in $scope.formData.hls){
            var item = $scope.formData.hls[i];
            if(item.hls_url_mobile == ''){
                $scope.warning_messages = 'Please fill-up HLS URL Mobile at ['+(i+1)+']';
                $('html,body').animate({scrollTop:'0px'});
                return false;
            }

            if(item.hls_url_stb == ''){
                $scope.warning_messages = 'Please fill-up HLS URL STB at ['+(i+1)+']';
                $('html,body').animate({scrollTop:'0px'});
                return false;
            }
        }

        var http = WebService.post('channels/save-mapping',$scope.formData);
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.success_messages = data.success_messages;
               // window.location = SITE_URL + 'channels';
            }else{
                $scope.warning_messages = data.warning_messages;
            }
            $('html,body').animate({scrollTop:'0px'});
        });

    };

    if($scope.parentId == 1){
        loadLco();
    }else{
        $scope.formData.operator_id = $scope.parentId;
        $scope.loadStreamerInstances();
    }

}]);