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
app.controller('IptvProgram',['$scope','WebService','FileUploader',function($scope,WebService,FileUploader){

    $scope.addFromFlag = 0;
    $scope.programs = [];
    $scope.permissions = {};
    $scope.verifyData = {};
    $scope.formData = {type:'CATCHUP',lcn:0,individual_price:0,is_active:1,is_available:1,video_language:"Bengali/Bangla"};
    $scope.formData.service_operator_id = [];
    $scope.types = [];//[{name:'LIVE',value:'LIVE'},{name:'CATCHUP',value:'CATCHUP'},{name:'DELAY',value:'DELAY'},{name:'VOD',value:'VOD'}];
    $scope.showDurationFlag = false;
    $scope.imageProcessing = 0;
    
    $("input.duration").val("00");

    $scope.showForm = function(){
        $scope.addFormFlag = 1;

    };

    $scope.hideForm = function(){
        $scope.addFormFlag = 0;
        $scope.formData = {type:'CATCHUP',lcn:0,individual_price:0,is_active:1,is_available:1,video_language:"Bengali/Bangla"};
    };

    $scope.closeAlert = function(){
        $scope.warning_messages = '';
        $scope.error_messages = '';
        $scope.success_messages = '';
    };

    $scope.$watch('formData.program_name',function(val){
        if(val != null)
        {
            $scope.formData.video_share_url = md5(val);
            val = keywords = val.toString().toLowerCase();
            $scope.formData.content_dir = val.replace(/\s/g,"_");
            $scope.formData.keywords = keywords.replace(/\s/g,", ");
        }
    });
    
    


    var loadPermissions = function(){
        var http = WebService.get('catchup-programs/ajax-get-permissions');
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.permissions = data.permissions;
                $scope.types = data.types;
            }
        });
    };

    loadPermissions();

    var generateKendoGird = function(){
        $scope.mainGridOptions = {
            dataSource: {
                transport: {
                    read: {
                        url: "catchup-programs/ajax-get-programs",
                        dataType: "json",
                    }
                },
                schema: {
                    data: "programs",
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
                {field: "id", title: "ID", width: "60px",filterable:false},
                {field: "program_name", title: "Content Name", width: "200px"},
                //{field: "description",title:"Description",width:"200px", filterable:false},

                {field: "type",title:"Type",width:"auto",filterable:false},
                {field: "is_active", title: "Status", width: "auto",filterable:false,template: '# if(data.is_active==1) {# <span class="label label-success">Active</span> #} else {# <span class="label label-danger">Inactive</span> #}#'},
                {field: "", title: "Action", width: "auto",filterable:false,template:"<a href='"+SITE_URL+"catchup-programs/view/#=data.id#' class='btn btn-default btn-xs' data-toggle='tooltip' data-placement='left' title='View'><i class='fa fa-search'></i></a>"+
                ' <a ng-if="permissions.edit_permission==\'1\'" href="'+SITE_URL+'catchup-programs/edit/#=data.id#" class="btn btn-default btn-xs" data-toggle="tooltip" data-placement="left" title="Edit"><i class="fa fa-pencil"></i></a> <a href="'+SITE_URL+'catchup-programs/mapping/#=data.id#" class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="left" title="Add Streamer Instance"><i class="fa fa-plus-circle"></i></a> # if ((data.assigned>0))  {##} else {#<a ng-if="permissions.delete_permission==\'1\'" ng-click="delete(#=data.id#)" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="left" title="Delete"><i class="fa fa-trash"></i></a>#}#'},
            ]
        };


    };

    generateKendoGird();

    $scope.delete = function(i)
    {
        $scope.delete_item = i;
    };

    $scope.confirm_delete = function()
    {
        $scope.verifyData.delete_item = $scope.delete_item;
        var http = WebService.post('catchup-programs/delete',$scope.verifyData);
        http.then(function(response){
            var data = response.data;
            if(data.status == 400){
                $scope.warning_messages = data.warning_messages;
                $("html,body").animate({scrollTop:'0px'});
            }else{
                window.location.reload();
            }
        });
    };

    $scope.cancel_delete = function()
    {
        $scope.closeAlert();
        $scope.delete_item = 0;
    };


    // show and hide adv options for image format
    $scope.showAdv = 0;

    $scope.showAdvOptions = function(){
        $scope.showAdv = 1;
    };
    $scope.hideAdvOptions = function(){
        $scope.showAdv = 0;
    };



    var UploaderImage = $scope.UploaderImage = new FileUploader({
        headers: {'X-Requested-With':'XMLHttpRequest'},
        url: SITE_URL+'catchup-programs/upload-image'
    });


    var UploaderWatermark = $scope.UploaderWatermark = new FileUploader({
        headers: {'X-Requested-With':'XMLHttpRequest'},
        url: SITE_URL+'catchup-programs/upload-water-mark'
    });

    UploaderImage.onCompleteItem = function(fileItem,response){
        if(response.status == 400){
            $scope.closeAlert();
            $scope.warning_messages = response.warning_messages;
            $("html,body").animate({scrollTop:'0px'});
            window.location = response.redirect;
        }
        $scope.imageProcessing = 0;
    };


    UploaderWatermark.onCompleteItem = function(fileItem,response) {
        if(response.status==400){
            $scope.warning_messages = response.warning_messages;
        }
        $scope.imageProcessing = 0;
    };



    $scope.saveIpTvProgram = function()
    {
        $scope.formData.description = tinymce.activeEditor.getContent({format : 'raw'});

        if($scope.formData.category_id == undefined){
            $scope.warning_messages = "Please Select Category";
            $("html,body").animate({scrollTop:'0px'});
            return false;
        }

        if($scope.formData.sub_category_id == undefined){
            $scope.warning_messages = "Please Select Sub Category";
            $("html,body").animate({scrollTop:'0px'});
            return false;
        }
        var dH = $(".duration_h").val();
            dH = (dH != "")? dH : "00";
        var dM = $(".duration_m").val();
            dM = (dM != "")? dM : "00";
        var dS = $(".duration_s").val();
            dS = (dS != "")? dS : "00";
        
        if(parseInt(dH) < 0){
            $scope.warning_messages = "Duration Hour should be positive number";
            $("html,body").animate({scrollTop:'0px'});
            return false;
        }
        if(parseInt(dM) < 0){
            $scope.warning_messages = "Duration Minute should be positive number";
            $("html,body").animate({scrollTop:'0px'});
            return false;
        }
        if(parseInt(dS) < 0){
            $scope.warning_messages = "Duration Second should be positive number";
            $("html,body").animate({scrollTop:'0px'});
            return false;
        }
        
        if($scope.formData.individual_price < 0){
            $scope.warning_messages = "individual price should be positive number";
            $("html,body").animate({scrollTop:'0px'});
            return false;
        }
        
        
        $scope.formData.duration = dH+":"+dM+":"+dS;

        var http = WebService.post('catchup-programs/save-program',$scope.formData);
        http.then(function(response){
           var data = response.data;
            if(data.status == 400){
                $scope.warning_messages = data.warning_messages;
                $("html,body").animate({scrollTop:'0px'});
            }else{


                // upload Logo URL
                UploaderImage.onBeforeUploadItem = function(item) {
                    UploaderImage.progress = 0;
                    $scope.fileUploadPhotoProgress = 0;
                    item.formData.push({id:data.id,form_type:0,
                        web_logo: $scope.formData.web_logo,
                        stb_logo: $scope.formData.stb_logo,
                        mobile_logo:$scope.formData.mobile_logo,
                        mobile_poster:$scope.formData.mobile_poster,
                        web_poster:$scope.formData.web_poster,
                        stb_poster:$scope.formData.stb_poster,
                        web_player_poster:$scope.formData.web_player_poster,
                        mobile_player_poster:$scope.formData.mobile_player_poster,
                        image_quality:$scope.formData.image_quality
                    });
                    $scope.imageProcessing = 1;
                    $("html,body").animate({scrollTop:'0px'});
                };
                UploaderImage.uploadAll();


                // upload water mark
                UploaderWatermark.onBeforeUploadItem = function(item) {
                    UploaderWatermark.progress = 0;
                    $scope.fileUploadPhotoProgress = 0;
                    item.formData.push({id:data.id,form_type:0});
                    $scope.imageProcessing = 1;
                    $("html,body").animate({scrollTop:'0px'});
                };
                UploaderWatermark.uploadAll();

                $scope.success_messages = data.success_messages;

                generateKendoGird();

                $scope.hideForm();
            }
        });
    };

    $scope.showDuration = function(){

        if($scope.formData.type == 'VOD'){
            $scope.showDurationFlag = true;
        }else{
            $scope.showDurationFlag = false;
        }
    };

    $scope.toggleSelectAllServiceOperator = function(){
        if($scope.selectAllServiceOperator){

            if(serviceOperators != undefined){
                serviceOperators = ((typeof serviceOperators) == "string")? JSON.parse(serviceOperators) : serviceOperators;
                for(s in serviceOperators){
                    console.log(serviceOperators[s]);
                    $scope.formData.service_operator_id.push(Number(serviceOperators[s].id));
                }
            }
        }else{
            $scope.formData.service_operator_id = [];
        }
    };


    $scope.isChecked = function($val){

        if($scope.formData!=null && $val != undefined){
            var index = ($scope.formData.service_operator_id != undefined)? $scope.formData.service_operator_id.indexOf(parseInt($val)) : -1;
            if(index != -1){
                return true;
            }
        }
        return false;
    };

    $scope.loadSubCategories = function(){
        var categoryId = $scope.formData.category_id;
        console.log(categoryId);
        var http = WebService.get('catchup-programs/ajax-get-sub-categories/'+categoryId);
        http.then(function(response){
            var data= response.data;
            $scope.sub_categories = data.sub_categories;
        });
    };

    $scope.setAllImageFormat = function(){
        if($scope.allImageFormat){
            $scope.formData.web_logo = 1;
            $scope.formData.stb_logo = 1;
            $scope.formData.mobile_logo = 1;
            $scope.formData.mobile_poster = 1;
            $scope.formData.web_poster = 1;
            $scope.formData.stb_poster = 1;
            $scope.formData.web_player_poster = 1;
            $scope.formData.mobile_player_poster = 1;
        }else{
            $scope.formData.web_logo = 0;
            $scope.formData.stb_logo = 0;
            $scope.formData.mobile_logo = 0;
            $scope.formData.stb_web_logo = 0;
            $scope.formData.mobile_poster = 0;
            $scope.formData.web_poster = 0;
            $scope.formData.stb_poster = 0;
            $scope.formData.web_player_poster = 0;
            $scope.formData.mobile_player_poster = 0;
        }
    }


}]);