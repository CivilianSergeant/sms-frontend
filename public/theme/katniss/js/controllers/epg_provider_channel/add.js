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

app.controller('epgProviderChannel', ['$scope', 'WebService', function ($scope, WebService) {
        $scope.filterData = {};

        $scope.closeAlert = function () {
            $scope.success_messages = '';
            $scope.warning_messages = '';
            $scope.error_messages = '';
        };
        $scope.formData = {};

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
                $scope.warning_messages = "Sorry! You don't have permission to...";
            }

        };

        var loadPermissions = function () {
            var http = WebService.get('manage-provider-channel/ajax-get-permissions');
            http.then(function (response) {
                var data = response.data;
                if (data.status == 200) {
                    $scope.permissions = data.permissions;

                }
            });
        };

        loadPermissions();


        var generateKendoGird = function () {
            $scope.mainGridOptions = {
                dataSource: {
                    schema: {
                        data: "epg_provider_channels",
                        total: "total"
                    },
                    transport: {
                        read: {
                            url: "manage-provider-channel/ajax-get-epg-provider-channels",
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
                    {field: "provider_channel_id", title: "Channel ID", width: "auto", filterable: true},
                    {field: "provider_channel_name", title: "Channel Name", width: "100px", filterable: false},
                    {field: "lang", title: "Lang", width: "250px;", filterable: false},
                    {field: "", title: "Action", width: "auto", template: "<a href='" + SITE_URL + "manage-epg/view/#=data.id#' class='btn btn-default btn-xs' data-toggle='tooltip' data-placement='left' title='View'><i class='fa fa-search'></i></a> <a ng-if='permissions.edit_permission==\"1\"' href='" + SITE_URL + "manage-epg/edit/#=data.id#' class='btn btn-default btn-xs' data-toggle='tooltip' data-placement='left' title='Update'><i class='fa fa-pencil'></i></a> <a ng-if='permissions.delete_permission==\"1\"' ng-click='deleteItem(#=data.id#)' class='btn btn-danger btn-xs'><i class='fa fa-trash'></i></a>"},
                ]
            };
        };

        generateKendoGird();
        
        $scope.saveEPGProviderChannel = function () {

            console.log($scope.formData);
            var http = WebService.post('manage-provider-channel/save-epg-provider-channel', $scope.formData);
            http.then(function (response) {
                var data = response.data;
                if (data.status == 400) {
                    $scope.warning_messages = data.warning_messages;
                    $("html,body").animate({scrollTop: '0px'});
                } else {
                    $scope.success_messages = data.success_messages;
                    $("html,body").animate({scrollTop: '0px'});
                    $scope.hideForm();
                    generateKendoGird();
                }

            });
        };

    }]);