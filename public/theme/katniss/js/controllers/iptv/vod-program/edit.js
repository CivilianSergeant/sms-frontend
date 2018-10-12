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
app.directive('stringToNumber', function() {
    return {
        require: 'ngModel',
        link: function(scope, element, attrs, ngModel) {
            ngModel.$parsers.push(function(value) {
                return '' + value;
            });
            ngModel.$formatters.push(function(value) {
                return parseFloat(value, 10);
            });
        }
    };
});
app.controller('IptvProgram',['$scope','WebService','FileUploader',function($scope,WebService,FileUploader){

    $scope.types = null;
    $scope.formData = null;
    $scope.service_operators = null;
    $scope.imageProcessing = 0;
    $scope.shared_url = null;
    
    $scope.lsps = JSON.parse(lsps);

    var loadSubCategories = function(){
        var categoryId = $scope.formData.category_id;

        var http = WebService.get('vod-programs/ajax-get-sub-categories/'+categoryId);
        http.then(function(response){
            var data= response.data;
            $scope.sub_categories = data.sub_categories;
        });
    };

    $scope.closeAlert = function(){
        $scope.warning_messages = '';
        $scope.error_messages = '';
        $scope.success_messages = '';
    };

    // show and hide adv options for image format
    $scope.showAdv = 0;

    $scope.showAdvOptions = function(){
        $scope.showAdv = 1;
    };
    $scope.hideAdvOptions = function(){
        $scope.showAdv = 0;
    };

    var loadProgram  = function(){
        var http = WebService.get('vod-programs/ajax-get-program/'+programId);
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.types = data.types;
                $scope.formData = data.program;
                
                if(data.program.parent_id == 1){
                    $scope.formData.parent_id = 'MSO';
                }
                
                for(lsp in $scope.lsps){
                    if($scope.lsps[lsp].user_id == data.program.parent_id){
                        $scope.formData.parent_id = $scope.lsps[lsp].username;
                        
                    }
                }
                if($scope.formData.category_id != null){
                    loadSubCategories();
                }
                
                if($scope.formData.duration != ""){
                    
                    var dH = parseInt($scope.formData.duration.substring(0,2));
                    var dM = parseInt($scope.formData.duration.substring(3,5));
                    var dS = parseInt($scope.formData.duration.substring(6,8));
                    
                    $(".duration_h").val((dH<10)? '0'+dH : dH);
                    $(".duration_m").val((dM<10)? '0'+dM : dM);
                    $(".duration_s").val((dS<10)? '0'+dS : dS);
                }

                $scope.service_operators = data.service_operators;
                //$scope.formData.duration = parseInt($scope.formData.duration);
                $scope.shared_url = data.settings.default_share_url;

                if($scope.formData.service_operator_id != null && $scope.formData.service_operator_id != undefined){
                    for(var v in $scope.formData.service_operator_id){
                        $scope.formData.service_operator_id[v] = (Number($scope.formData.service_operator_id[v]));
                    }
                }

                if($scope.service_operators.length == $scope.formData.service_operator_id.length){
                    $scope.selectAllServiceOperator = 1;
                }

                if($scope.formData.description != undefined)
                {
                    var description = $scope.formData.description;
                    if(tinymce!=undefined & tinymce.activeEditor != undefined){
                        tinymce.activeEditor.setContent(description);
                    }
                    
                } 
            }
        });
    };

    loadProgram();

    var UploaderImage = $scope.UploaderImage = new FileUploader({
        headers: {'X-Requested-With':'XMLHttpRequest'},
        url: SITE_URL+'vod-programs/upload-image'
    });

    UploaderImage.onCompleteItem = function(fileItem,response){

        if(response.status == 400){
            $scope.warning_messages = response.warning_messages;
        }
        $scope.imageProcessing = 0;
        window.location.reload();
    };



    var UploaderWatermark = $scope.UploaderWatermark = new FileUploader({
        headers: {'X-Requested-With':'XMLHttpRequest'},
        url: SITE_URL+'vod-programs/upload-water-mark'
    });

    UploaderWatermark.onCompleteItem = function(fileItem,response) {
        if(response.status==400){
            $scope.warning_messages = response.warning_messages;
        }else{
            if(response.status == 200){
                /*$("#waterMark").attr('src',BASE_URL+response.image);*/
                $scope.imageProcessing = 0;
                window.location.reload();
            }
        }
    };

    $scope.isChecked = function($val){

        if($scope.formData!=null){
            var index = $scope.formData.service_operator_id.indexOf(parseInt($val));
            if(index != -1){
                return true;
            }
        }
        return false;
    };

    $scope.updateIpTvProgram = function(){
        $scope.closeAlert();
        $scope.formData.description = tinymce.activeEditor.getContent({format : 'raw'});
        
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

        $scope.formData.type = 'VOD';
        var http = WebService.post('vod-programs/update-program',$scope.formData);
        http.then(function(response){
            var data = response.data;
            if(data.status == 400){
                $scope.warning_messages = data.warning_messages;
            }else{

                $scope.success_messages = data.success_messages;

                UploaderImage.onBeforeUploadItem = function(item) {
                    UploaderImage.progress = 0;
                    $scope.fileUploadPhotoProgress = 0;
                    item.formData.push({id:$scope.formData.id,form_type:1,
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



                UploaderWatermark.onBeforeUploadItem = function(item) {
                    UploaderWatermark.progress = 0;
                    $scope.fileUploadPhotoProgress = 0;
                    item.formData.push({id:$scope.formData.id,form_type:1});
                    $scope.imageProcessing = 1;
                    $("html,body").animate({scrollTop:'0px'});
                };
                UploaderWatermark.uploadAll();

                $("html,body").animate({scrollTop:'0px'},500,'swing',function(){
                    //window.location = SITE_URL+'vod';
                });


            }
        });
    };

    $scope.toggleSelectAllServiceOperator = function(){
        if($scope.selectAllServiceOperator){

            if($scope.service_operators != undefined){
                var serviceOperators = ((typeof $scope.service_operators) == "string")? JSON.parse($scope.service_operators) : $scope.service_operators;
                for(s in serviceOperators){
                    $scope.formData.service_operator_id.push(Number(serviceOperators[s].id));
                }
            }

        }else{
            $scope.formData.service_operator_id = [];
        }
    };

    $scope.loadSubCategories = function(){
        var categoryId = $scope.formData.category_id;
        console.log(categoryId);
        var http = WebService.get('channels/ajax-get-sub-categories/'+categoryId);
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
            $scope.formData.mobile_poster = 0;
            $scope.formData.web_poster = 0;
            $scope.formData.stb_poster = 0;
            $scope.formData.web_player_poster = 0;
            $scope.formData.mobile_player_poster = 0;
        }
    }

}]);