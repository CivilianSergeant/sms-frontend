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
app.controller('EditAppCategories', ['$scope', 'WebService', function ($scope, WebService) {

        $scope.formData = {};
        $scope.programs = [];
        $scope.cat_data = [];
        $scope.cat_id = cat_id;

        $scope.closeAlert = function () {
            resetMessage();
        };

        $scope.closeAlert = function () {
            $scope.warning_messages = '';
            $scope.error_messages = '';
            $scope.success_messages = '';
        };

        $scope.resetForm = function ()
        {
            $scope.formData = {subscriber_amount: 100, initial_balance: 50};
        };
        var temp = [];
        var appCatData = function () {
            var http = WebService.get('app-categories/ajax-get-appcat-data?id=' + $scope.cat_id);
            http.then(function (response) {

                var data = response.data;
                if (data.status == 200) {
                    $scope.formData.category_name = data.cat_data.category_name;
                    $scope.formData.order_index = data.cat_data.order_index;
                    $scope.formData.id = data.cat_data.id;
                    var program_data = data.program_data;

                    var html = '';
                    for (i in program_data) {
                        temp.push(program_data[i].content_id);
                        html += '<li style="cursor: pointer;" class="ui-state-default" id="' + i + '" data-rowid="' + program_data[i].id + '" data-id="' + program_data[i].content_id + '"><i class="fa fa-close pull-right" onclick="programDelete(' + i + ')"></i> ' + program_data[i].program_name + '</li>';
                    }
                    $("#sortable").append(html);
                    $("#sortable").sortable();
                }
            });
        };

        appCatData();

        $scope.formData.programType = '0';
        $scope.programGrid = function () {
            $("#loading").show();
            $("#program-list").hide();
            var http = WebService.get('app-categories/get-programs?type=' + $scope.formData.programType);
            http.then(function (response) {
                var data = response.data;
                
                if (data.status == 200) {
                    $scope.programs = data.programs;
                    $("#loading").hide();
                    $("#program-list").show();
                }
            });
        };
        
        $scope.formData.searchKey = '';
        $scope.searchProgram = function () {
            if($scope.formData.programType == '0'){
                alert('Please select type')
                return false;
            }
            $("#loading").show();
            $("#program-list").hide();
            var http = WebService.get('app-categories/search-programs?search_key=' + $scope.formData.searchKey + '&type=' + $scope.formData.programType);
            http.then(function (response) {
                var data = response.data;
                
                if (data.status == 200) {
                    $scope.programs = data.programs;
                    $("#loading").hide();
                    $("#program-list").show();
                }
            });
        };

        //programGrid();

        var programArr = [];
        var menuDataArr = []; 

        $scope.setProgram = function () {
            $('#program-list').find('input[type=checkbox]:checked').each(function () {
                programArr.push({id: $(this).attr('data-id'), content_id: $(this).attr('data-id'), name: $(this).attr('data-name')});
                temp.push($(this).attr('data-id'));
            });
            var totalItems = $("#sortable li");
            var html = '';
            for (i in programArr) {
                var itemid = (parseInt(i) + parseInt(totalItems.length));
                html += '<li style="cursor: pointer;" class="ui-state-default" id="' + itemid + '" data-id="' + programArr[i].content_id + '" data-rowid="0"><i class="fa fa-close pull-right" onclick="programDelete(' + i + ')"></i> ' + programArr[i].name + '</li>';
            }
            $("#sortable").append(html);
            $("#sortable").sortable();
            $('#program-list').find('input[type=checkbox]:checked').removeAttr('checked');
            programArr = [];
        };
        
        $scope.checkIfExist = function(id){
            var i;
            for (i=0; i < temp.length; i++)
            {
                if (temp[i] == id)
                {
                    alert('Already added');
                    $("#program-list #" + id).find('input[type=checkbox]:checked').removeAttr('checked');
                }
            }
        };

        $scope.saveAppCategory = function (i) {
            var totalItems = $("#sortable li");
            if (totalItems.length < 1) {
                $scope.warning_messages = 'Please Add Program';
                return false;
            }
            $('#sortable').find('li').each(function () {
                var pos = $(this).index();
                menuDataArr.push({id: $(this).attr('data-rowid'), content_id: $(this).attr('data-id'), index: pos + 1});
            });

            var formData = {
                id: $scope.formData.id,
                category_name: $scope.formData.category_name,
                order_index: $scope.formData.order_index,
                programs: menuDataArr
            }
            var http = WebService.post('app-categories/update', formData);
            http.then(function (response) {
                var data = response.data;
                if (data.status == 400) {
                    $scope.warning_messages = data.warning_messages;
                    $scope.success_messages = '';
                    menuDataArr = [];
                } else {
                    $scope.success_messages = data.success_messages;
                    $scope.warning_messages = '';
                    window.location.href = SITE_URL + "app-categories";
                }
                $("html,body").animate({scrollTop: '0px'});
            });
        };

    }]);
