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

app.controller('EditTranscoder', ['$scope', 'WebService', function ($scope, WebService) {

        $scope.transcoderId = transcoderId;

        $scope.closeAlert = function () {
            $scope.success_messages = '';
            $scope.warning_messages = '';
            $scope.error_messages = '';
        };
        $scope.formData = {};

        $scope.hideForm = function () {
            $scope.showFrm = 0;
        };

        $scope.showForm = function ()
        {
            if ($scope.permissions.edit_permission == '1') {
                $scope.showFrm = 1;
            } else {
                $scope.warning_messages = "Sorry! You don't have permission to edit transcoder";
            }

        };

        var loadPermissions = function () {
            var http = WebService.get('manage-epg/ajax-get-permissions');
            http.then(function (response) {
                var data = response.data;
                if (data.status == 200) {
                    $scope.permissions = data.permissions;
                }
            });
        };

        loadPermissions();

        var loadVendors = function () {
            var http = WebService.get('vendor/ajax-get-vendors');
            http.then(function (response) {
                var data = response.data;
                if (data.status == 200) {
                    $scope.vendors = data.vendors;
                }
            });
        };

        loadVendors();

        var loadTranscoder = function () {
            var http = WebService.get('transcoder/ajax-get-transcoder-by-id/' + $scope.transcoderId);
            http.then(function (response) {
                var data = response.data;

                if (data.status == 200) {
                    $scope.formData = data.transcoder;
                }
            });


        };
        loadTranscoder();

        $scope.transcoderUpdate = function () {
            var http = WebService.post('transcoder/update-transcoder', $scope.formData);
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