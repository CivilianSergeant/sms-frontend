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

app.controller('CreateFeatureContent',['$scope','WebService',function($scope,WebService) {

    $scope.addFromFlag = 0;
    $scope.programs = [];
    $scope.assigned_programs = [];
    $scope.formData = {};
    $scope.formData.type="LIVE";
    $scope.types = ['LIVE','CATCHUP','VOD'];

    $scope.showForm = function(){
        $scope.addFormFlag = 1;
        loadNormalChannelPrograms();
    };

    $scope.hideForm = function(){
        $scope.addFormFlag = 0;
        $scope.formData = {};
        $scope.formData.type="LIVE";
    };

    $scope.closeAlert = function(){
        $scope.warning_messages = '';
        $scope.error_messages = '';
        $scope.success_messages = '';
    };


    var loadNormalChannelPrograms = function(){
        var http = WebService.get('feature-content/ajax-get-normal-channel-programs');
        http.then(function(response){
            var data = response.data;
            $scope.programs = data.programs;
            $scope.assigned_programs = data.featured_programs;
        });
    };

    var loadNormalContent  = function(){
        var http = WebService.get('feature-content/ajax-get-normal-'+$scope.formData.type.toLowerCase()+'-content-programs');
        http.then(function(response){
            var data = response.data;
            $scope.programs = data.programs;
            $scope.assigned_programs = data.featured_programs;
        });
    };

    $scope.loadContent = function(){
        if($scope.formData.type == 'LIVE'){
            loadNormalChannelPrograms();
        }
        if($scope.formData.type != 'LIVE'){
            loadNormalContent($scope.formData.type);
        }

    };

    $scope.IncludeItems = function(){
        $scope.closeAlert();

        if($scope.formData.selected_item.length>10){
            $scope.warning_messages = "Sorry! Cannot Make Feature Content more than 10 items at once.";
            $("html,body").animate({scrollTop:'0px'});
            return false;
        }

        for(p in $scope.programs){

            for(item in $scope.formData.selected_item){

                if($scope.programs[p].id == $scope.formData.selected_item[item])
                {
                    if($scope.assigned_programs.length==10){
                        $scope.warning_messages = 'Sorry! Maximum 10 item could be included';
                        $("html,body").animate({scrollTop:'0px'});
                        return false;
                    }
                    saveFeatureContent($scope.programs[p]);
                    $scope.assigned_programs.push($scope.programs[p]);
                    $scope.programs.splice(p,1);
                }
            }
        }
    };

    $scope.ExcludeItems = function(){
        $scope.closeAlert();

        for(ap in $scope.assigned_programs){
            for(item in $scope.formData.included_item){
                if($scope.assigned_programs[ap].id == $scope.formData.included_item[item])
                {
                    saveAsNormalContent($scope.assigned_programs[ap].id);
                    $scope.programs.push($scope.assigned_programs[ap]);
                    $scope.assigned_programs.splice(ap,1);
                }
            }
        }

    };


    var loadPermissions = function(){
        var http = WebService.get('feature-content/ajax-get-permissions');
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.permissions = data.permissions;
            }
        });
    };

    loadPermissions();

    var generateKendoGrid = function(){
        $scope.mainGridOptions = {
            dataSource: {
                transport: {
                    read: {
                        url: "feature-content/ajax-get-feature-contents",
                        dataType: "json",
                    }
                },
                schema: {
                    data: "featured_programs",
                    total: "total"
                },
                pageSize: 10,
                serverPaging: true,
                serverSorting:true,
                serverFiltering: true

            },
            sortable: true,
            pageable: true,
            scrollable: true,
            resizable: true,
            filterable: {
                extra: false,
                operators: {
                    string: {
                        startswith: "Starts with",
                        eq: "Is equal to",

                    }
                }
            },

            dataBound: gridDataBound,

            columns: [

                {field: "program_name", title: "Content Name", width: "200px",template:'# if(data.type=="LIVE") {# <a href="'+SITE_URL+'channels/view/#=data.id#">#=data.program_name#</a> #} else if(data.type=="CATCHUP"){# <a href="'+SITE_URL+'catchup-programs/view/#=data.id#">#=data.program_name#</a> #} else {# <a href="'+SITE_URL+'vod-programs/view/#=data.id#">#=data.program_name#</a> #}#'},
                {field: "type", title: "Type", width: "120px",filterable:false},
                {field: "is_active", title: "Status", width: "auto",filterable:false,template: '# if(data.is_active=="1") {# <span class="label label-success">Active</span> #} else {# <span class="label label-danger">Inactive</span> #}#'},
                {field: "", title: "Action", width: "auto",filterable:false,template:'<a ng-click="delete(#=data.id#)" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="left" title="Delete"><i class="fa fa-trash"></i></a>'},
            ]
        };
    };

    generateKendoGrid();

    $scope.delete = function(i){
        $scope.delete_flag = i;
    };

    $scope.cancel_delete = function(){
        $scope.delete_flag = '';
    };

    $scope.confirm_delete = function(){
        saveAsNormalContent($scope.delete_flag);
    };

    var saveAsNormalContent = function(id){
        var http = WebService.post('feature-content/set-as-normal-content',{id:id});
        http.then(function(response){
            var data = response.data;
            if(data.status == 400){
                $scope.warning_messages = data.warning_messages;
            }else{
                $scope.success_messages = data.success_messages;
                $scope.delete_flag = '';
            }
        });
    };

    var saveFeatureContent = function(content){

        var contents = [];
        if(content != null){
            contents.push(content.id);
        }

        var http = WebService.post('feature-content/save-feature-content',{contents:contents});
        http.then(function(response){
           var data = response.data;
            if(data.status == 400){
                $scope.warning_messages = data.warning_messages;
            }else{
                $scope.success_messages = data.success_messages;
            }
            $("html,body").animate({scrollTop:'0px'});
        });
    };
}]);