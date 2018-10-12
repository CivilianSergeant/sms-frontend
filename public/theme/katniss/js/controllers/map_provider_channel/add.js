var app = angular.module('plaasApp');
app.factory('WebService', function ($http) {
    return {
        get: function (url) {
            return $http({
                method: "POST",
                url: SITE_URL + url,
                headers: {'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/x-www-form-urlencoded'},
            });
        },
        post: function (url, data) {
            return $http({
                method: "POST",
                url: SITE_URL + url,
                headers: {'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/x-www-form-urlencoded'},
                data: $.param(data)
            });
        }

    };
});

app.controller('mapProviderChannel', ['$scope', 'WebService', function ($scope, WebService) {
        $scope.filterData = {};

        $scope.closeAlert = function () {
            $scope.success_messages = '';
            $scope.warning_messages = '';
            $scope.error_messages = '';
        };
        $scope.formData = {};
        $scope.mappings = [];

        $scope.hideForm = function () {
            $scope.showFrm = 0;
            $scope.formData = {};
            $scope.formData.duration = 30;
            $scope.formData.repeats = [];
        };

        $scope.showForm = function ()
        {
            if ($scope.permissions.create_permission == '1') {
                $scope.showFrm = 1;
            } else {
                $scope.warning_messages = "Sorry! You don't have permission to ...";
            }

        };

        var loadPermissions = function () {
            var http = WebService.get('map-provider-channel/ajax-get-permissions');
            http.then(function (response) {
                var data = response.data;
                if (data.status == 200) {
                    $scope.permissions = data.permissions;

                }
            });
        };

        //loadPermissions();

        $scope.loadProviderChannels = function(){
            var http = WebService.get('map-provider-channel/ajax-get-provider-channels/'+$scope.provider_id);
            http.then(function(response){
                var data = response.data;
                $scope.providerChannels = data.channels;
            });
        };
        
        
        $scope.addRow = function(){
            $scope.closeAlert();
            if(!$scope.channel){
                $scope.warning_messages = 'Please Select Channel';
                return false;
            }
            
            if(!$scope.providerChannel){
                $scope.warning_messages = 'Please Select Provider Channel';
                return false;
            }
            
            var row =   {   providerId:$scope.provider_id,
                            channelId:$scope.channel,
                            providerChannelId:$scope.providerChannel,
                            channel:$("select[ng-model='channel'] option:selected").text(),
                            providerChannel:$("select[ng-model='providerChannel'] option:selected").text()
                        };
            $scope.mappings.push(row);
        }; 
        
        $scope.removeFromRow = function(index){
            if($scope.mappings[index] != undefined){
                $scope.mappings.splice(index,1);
            }
        };
        
        $scope.saveMapping = function(){
            $scope.closeAlert();
            var http = WebService.post('map-provider-channel/save-mapping',{mappings:$scope.mappings});
            http.then(function(response){
               var data = response.data;
               if(data.status == 200){
                   $scope.success_messages = data.success_messages;
               }else{
                   $scope.warning_messages = data.warning_messages;
               }
            });
        };


    }]);