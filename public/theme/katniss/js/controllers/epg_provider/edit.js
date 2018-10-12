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

app.controller('editEPGProvider', ['$scope', 'WebService', function ($scope, WebService) {
        $scope.providerId = id;
        $scope.closeAlert = function () {
            $scope.success_messages = '';
            $scope.warning_messages = '';
            $scope.error_messages = '';
        };
        $scope.formData = {};

        $scope.hideForm = function () {
            $scope.showFrm = 0;
            $scope.formData = {};
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
            var http = WebService.get('manage-epg-provider/ajax-get-permissions');
            http.then(function (response) {
                var data = response.data;
                if (data.status == 200) {
                    $scope.permissions = data.permissions;

                }
            });
        };

        loadPermissions();
        
        var loadEpgProvider = function(){
            var http = WebService.get('manage-epg-provider/get-provider-by-id/'+$scope.providerId);
            http.then(function(response){
                var data = response.data;
                console.log(data);
                if(data.status == 200){
                    $scope.formData = data.provider;
                }
            });
        };

        loadEpgProvider();


        $scope.editEPGProvider = function () {

            console.log($scope.formData);
            var http = WebService.post('manage-epg-provider/update-epg-provider', $scope.formData);
            http.then(function (response) {
                var data = response.data;
                if (data.status == 400) {
                    $scope.warning_messages = data.warning_messages;
                    $("html,body").animate({scrollTop: '0px'});
                } else {
                    $scope.success_messages = data.success_messages;
                    $("html,body").animate({scrollTop: '0px'});
                }

            });
        };

    }]);