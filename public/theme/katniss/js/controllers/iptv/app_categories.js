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
app.controller('AppCategories',['$scope','WebService',function($scope,WebService){
        
    $scope.formData = {};
    $scope.programs = [];
    var programArr = [];
    var menuDataArr = [];
    var resetMessage = function()
    {
        $scope.success_messages = '';
        $scope.warning_messages = '';
        $scope.error_messages = '';
    };
    
    $scope.hideForm = function(){ 
        resetMessage();
        $scope.showFrm = 0;
    };
    
    $scope.showForm = function()
    {
        $scope.showFrm = 1;
        resetMessage();
    };
    
    $scope.closeAlert = function(){
        resetMessage();
    };
    
    $scope.resetForm = function()
    {
        $scope.formData = {subscriber_amount: 100, initial_balance: 50};
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
        var temp = [];
        $scope.setProgram = function(){
            $('#program-list').find('input[type=checkbox]:checked').each(function() {
                programArr.push({id: $(this).attr('data-id'), name: $(this).attr('data-name')});
                temp.push($(this).attr('data-id'));
            });
            var totalItems = $("#sortable li");
            var html = '';
            for(i in programArr){
                var itemid = (parseInt(i) + parseInt(totalItems.length));
                html+='<li style="cursor: pointer;" class="ui-state-default" id="'+itemid+'" data-id="'+ programArr[i].id +'">'+ programArr[i].name +'</li>';
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
    
    $scope.saveAppCategory = function(i){
        var totalItems = $("#sortable li");
        if(totalItems.length < 1){
            $scope.warning_messages = 'Please Add Program';
            return false;
        }
        for (var i = 0; i < totalItems.length; i++) {
            var pos = $('#' + i).index();
            var id = $('#' + i).attr('data-id');
            menuDataArr.push({id: id, index: pos+1});
        }
        var formData = {
            category_name: $scope.formData.category_name,
            order_index: $scope.formData.order_index,
            programs: menuDataArr
        }
        var http = WebService.post('app-categories/save-app-categories',formData);
        http.then(function(response){
            var data = response.data;
            if(data.status==400){
                $scope.warning_messages = data.warning_messages;
                $scope.success_messages = '';
                menuDataArr = [];
            } else {
                $scope.success_messages = data.success_messages;
                $scope.warning_messages = '';
                window.location.href = SITE_URL + "app-categories";
//                $scope.showFrm = 0;
//                generateKendoGird();
//                $scope.formData = {};
//                programArr = [];
//                menuDataArr = [];
//                temp = [];
//                $("#sortable").html('');
//                $("#program-list").html('');
            }
            $("html,body").animate({scrollTop:'0px'});
        });
    };
    
    
    var generateKendoGird = function(){
        $scope.mainGridOptions = {
            dataSource: {
                transport: {
                    read: {
                        url: "app-categories/ajax-get-app-categories", 
                        dataType: "json",
                    }
                },
                schema: {
                    data: "categories",
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
                
                {field: "category_name", title: "Category Name",width: "350px"},
                {field: "", title: "Action",width: "50px",
                template:"<a  href='"+SITE_URL+"app-categories/view/#=data.id#' class='btn btn-default btn-xs' data-toggle='tooltip' data-placement='left' title='View'><i class='fa fa-search'></i></a> <a  href='"+SITE_URL+"app-categories/edit/#=data.id#' class='btn btn-default btn-xs' data-toggle='tooltip' data-placement='left' title='Edit'><i class='fa fa-edit'></i></a>"},
            ]
        };
    };
    generateKendoGird();


}]);
