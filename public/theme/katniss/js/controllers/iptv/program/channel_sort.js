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
app.controller('ChannelSort', ['$scope', 'WebService', 'FileUploader', function ($scope, WebService, FileUploader) {

        $scope.addFromFlag = 0;
        $scope.programs = [];
        $scope.permissions = {};
        $scope.imageProcessing = 0;
        $scope.verifyData = {};
        $scope.formData = {type: 'LIVE', lcn: 0, individual_price: 0, is_active: 1, is_available: 1, video_language: "Bengali/Bangla"};
        $scope.formData.service_operator_id = [];
        $scope.types = [];//[{name:'LIVE',value:'LIVE'},{name:'CATCHUP',value:'CATCHUP'},{name:'DELAY',value:'DELAY'},{name:'VOD',value:'VOD'}];
        $scope.showDurationFlag = false;

        $scope.closeAlert = function () {
            $scope.warning_messages = '';
            $scope.error_messages = '';
            $scope.success_messages = '';
        };

        var loadPermissions = function () {
            var http = WebService.get('channels/ajax-get-permissions');
            http.then(function (response) {
                var data = response.data;
                if (data.status == 200) {
                    $scope.permissions = data.permissions;
                    $scope.types = data.types;
                }
            });
        };

        loadPermissions();

        var programGrid = function () {
            var http = WebService.get('channels/ajax-get-programs');
            http.then(function (response) {
                var data = response.data;
                if (data.status == 200) {
                    $scope.programs = data.programs;
                    _programs = data.programs;
                }
            });
        };

        programGrid();

        $(document).ready(function () {
            $("#sortable li.static").each(function () {
                $(this).attr("id", "static-" + $(this).index());
            });

        });

        $(function () {
            $("#sortable").sortable({
                //items: "li:not(.ui-state-disabled)",
                //items: "li:not(.not-sortable)",
                items: ':not(.static)',
                start: function(){
                    $('.static', this).each(function(){
                        var $this = $(this);
                        $this.data('pos', $this.index());
                    });
                },
                
                change: function(){
                        $sortable = $(this);
                        $statics = $('.static', this).detach();
                        $helper = $('<li></li>').prependTo(this);
                        $statics.each(function(){
                            var $this = $(this);
                            var target = $this.data('pos');

                            $this.insertAfter($('li', $sortable).eq(target));
                        });
                        $helper.remove();
                },

                update: function (event, ui) {
                    var totalItems = $('#sortable li');
                    var dataArr = [];

                    $.each(totalItems, function (i) {
                        var indexStart = $("#index-start").val();
                        var id = $(this).attr('data-uid');
                        var i = $(this).index();
                        var index = parseInt(indexStart) + i;
                        dataArr.push({id: id, index: index})
                    });

                    $.ajax({
                        type: 'POST',
                        data: {dataArr: dataArr},
                        url: 'channel-sort/update-program-order',
                        beforeSend: function () {
                            //$('#loading-section').show(0);
                        },
                        success: function (e) {
                            // $('#loading-section').hide(0);
                           $('#sortable .static').remove();
                            programGrid();
                        }
                    });
                },
            }).disableSelection();

            $("#sort-program").click(function () {
                var totalItems = $('#sortable li');
                var dataArr = [];

                $.each(totalItems, function (i) {
                    var indexStart = $("#index-start").val();
                    var id = $(this).attr('data-uid');
                    var i = $(this).index();
                    var index = parseInt(indexStart) + i;
                    dataArr.push({id: id, index: index})
                });

                $.ajax({
                    type: 'POST',
                    data: {dataArr: dataArr},
                    url: 'channel-sort/update-program-order',
                    beforeSend: function () {
                        //$('#loading-section').show(0);
                    },
                    success: function (e) {
                        // $('#loading-section').hide(0);
                        programGrid();
                    }
                });
            });
        });
        
                    
        $scope.updateProgramStatus = function(id, status)
        {
            if(status == 0)
            {
                var newStatus = 1;
            }
            else
            {
                newStatus = 0;
            }
            
            $.ajax({
                type: 'POST',
                data: {id: id, status: newStatus},
                url: 'channel-sort/update-program-status',
                beforeSend: function () {
                    //$('#loading-section').show(0);
                },
                success: function (e) {
                    // $('#loading-section').hide(0);
                    programGrid();
                }
            });
        };
        
    }]);

