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
app.controller('IptvProgram',['$scope','WebService',function($scope,WebService){

    $scope.types = null;
    $scope.formData = null;
    $scope.service_operators = null;
    $scope.delete_hls_item = null;
    $scope.shared_url = null;

    var loadSubCategories = function(){
        var categoryId = $scope.formData.category_id;

        var http = WebService.get('catchup-programs/ajax-get-sub-categories/'+categoryId);
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

    var loadProgram  = function(){
        var http = WebService.get('catchup-programs/ajax-get-program/'+programId);
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.types = data.types;
                $scope.formData = data.program;
                $scope.shared_url = data.settings.default_share_url;

                if($scope.formData.category_id != null){
                    loadSubCategories();
                }

                $scope.service_operators = data.service_operators;
                //$scope.formData.duration = parseInt($scope.formData.duration);

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

    $scope.isChecked = function($val){

        if($scope.formData!=null){
            var index = $scope.formData.service_operator_id.indexOf(parseInt($val));
            if(index != -1){
                return true;
            }
        }
        return false;
    };

    $scope.deleteItem = function(id){
        $scope.delete_hls_item = id;
    };

    $scope.cancelDelete = function(){
        $scope.delete_hls_item = null;
    };

    $scope.confirmDelete = function(){
        $scope.closeAlert();
        var http = WebService.post('catchup-programs/remove-mapping',{id:$scope.delete_hls_item,password:$scope.formData.password});
        http.then(function(response){
            var data = response.data;
            if(data.status==200){
                window.location.reload();
            }else {
                $("html,body").animate({scrollTop:'0px'});
                $scope.warning_messages = data.warning_messages;

            }
        });
    };


}]);