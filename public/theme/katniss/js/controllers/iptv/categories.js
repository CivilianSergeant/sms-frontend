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
app.controller('IptvCategories',['$scope','WebService',function($scope,WebService){
    $scope.uri = uri;
    $scope.categories = [];
    $scope.sub_categories = [];
    $scope.showSubcategories = [];
    $scope.programs = [];
    $scope.listData ={};
    $scope.assigned_programs = [];
    $scope.formData = {};
    $scope.toggle=false;
    $scope.programObj = {};
    $scope.categoryProgramObj = {};
    $scope.categoryObj='';
    $scope.subCategoryObj='';
    $scope.showCategoryFrmFlag = 1;
    $scope.showSubCategoryFrmFlag = 0;
    $scope.formName = 'Category';

    $scope.showCategoryFrm = function(){
        $scope.showCategoryFrmFlag = 1;
        $scope.showSubCategoryFrmFlag = 0;
        $scope.formData.categoryId = '';
        $scope.formName = 'Category';
    };

    $scope.showSubCategoryFrm = function(){
        $scope.showCategoryFrmFlag = 1;
        $scope.showSubCategoryFrmFlag = 1;
        $scope.formName = 'Sub Category';
    };


    var loadIptvCategories = function() {
        var http = WebService.get($scope.uri+'/ajax-get-categories');
        http.then(function(response){
            var data = response.data;
            $scope.categories = data.categories;
        });
    };

    loadIptvCategories();

    $scope.showCategoryInput = function(){
        $scope.toggle = true;
        $scope.formData.category_id = null;
    };

    $scope.showCategories = function(){
        $scope.toggle=false;
    };

    $scope.closeAlert = function(){
        $scope.warning_messages = '';
        $scope.error_messages = '';
        $scope.success_messages = '';
    };

    $scope.saveCategory = function(){
        $scope.closeAlert();
        if($scope.formData.category_name == null && $scope.formData.sub_category_name != null){
            $scope.warning_messages = 'Please Select Category to create sub-category';
            $("html,body").animate({scrollTop:'0px'});
            return false;
        }

        var http = WebService.post($scope.uri+'/save-category',$scope.formData);
        http.then(function(response){
           var data = response.data;
            if(data.status == 400) {
                $scope.warning_messages = data.warning_messages;
            }else{
                $scope.success_messages = data.success_messages;
                loadIptvCategories();
                
                if($scope.formData.categoryId != undefined || $scope.formData.categoryId != null){
                    reloadSubCategory($scope.formData.categoryId);
                }
                
                if(!showSubCategoryFrmFlag){
                    $scope.formData.categoryId = '';
                    $scope.formData.category_name = '';
                    $scope.showSubcategories = [];
                }
                
                
                $scope.formData.subCategoryId = '';
                $scope.formData.sub_category_name = '';
                
                


            }
        });
    };

    $scope.resetForm = function(){
        $scope.showSubcategories=[];
        $scope.formData.categoryId = '';
        $scope.formData.category_name = '';
        $scope.formData.sub_category_name = '';
        $scope.formData.subCategoryId = '';
    };

    $scope.loadSubCategories = function(i){
        $scope.categoryObj = $scope.categories[i];

        $scope.formData.id = $scope.categoryObj.id;


        var http = WebService.get($scope.uri+'/ajax-get-sub-categories/'+$scope.categoryObj.id);
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.sub_categories = data.sub_categories;
                $scope.subCategoryObj = '';
                /*$scope.subCategoryObj = '';
                for(i in $scope.assigned_programs){
                    $scope.programs.push($scope.assigned_programs[i]);
                    $scope.assigned_programs.splice(i,1);
                }*/
                loadPrograms();
                $scope.assigned_programs = [];
            }
        });
    };

    $scope.SelectCategories = function(i){
        var categoryObj = $scope.categories[i];
        $scope.formData.category_name = categoryObj.category_name;
        $scope.formData.categoryId = categoryObj.id;


        var http = WebService.get($scope.uri+'/ajax-get-sub-categories/'+categoryObj.id);
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.showSubcategories = data.sub_categories;

                /*$scope.subCategoryObj = '';
                for(i in $scope.assigned_programs){
                    $scope.programs.push($scope.assigned_programs[i]);
                    $scope.assigned_programs.splice(i,1);
                }*/
            }
        });
    };
    
    var reloadSubCategory = function(categoryId){
        var http = WebService.get($scope.uri+'/ajax-get-sub-categories/'+$scope.formData.categoryId);
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.sub_categories = data.sub_categories;
                $scope.subCategoryObj = '';
                $scope.showSubcategories = data.sub_categories;
                /*$scope.subCategoryObj = '';
                for(i in $scope.assigned_programs){
                    $scope.programs.push($scope.assigned_programs[i]);
                    $scope.assigned_programs.splice(i,1);
                }*/
                loadPrograms();
                $scope.assigned_programs = [];
            }
        });
    };



    var loadSelectedProgram = function(id){

        var http = WebService.get($scope.uri+'/ajax-get-selected-programs/'+id);
        http.then(function(response){
            var data = response.data;

            $scope.programs = [];
            for(i in data.programs){
                $scope.programs.push(data.programs[i]);
            }
            $scope.formData.subId = id;
            $scope.assigned_programs = data.assigned_programs;
            $scope.program = '';
        });
    };

    $scope.deleteCategory = function(i){
        $scope.closeAlert();
        var category = $scope.categories[i];
        $scope.categoryObj = category;
        $scope.delete_item = true;
        $scope.type = "category";
        $scope.index = i;
        $("html,body").animate({scrollTop:'0px'});

    };

    $scope.deleteSubCategory = function(i){
        $scope.closeAlert();
        $scope.subCategoryObj = $scope.showSubcategories[i];
        $scope.delete_item = true;
        $scope.type = "subcategory";
        $scope.index = i;
        $("html,body").animate({scrollTop:'0px'});
    };

    $scope.deleteProgram = function(id){
        $scope.closeAlert();
        $scope.categoryProgramObj =  $scope.assigned_programs[id];
        $scope.delete_item = true;
        $scope.type = "categoryprogram";
        $scope.index = id;
        $("html,body").animate({scrollTop:'0px'});
    };

    $scope.confirm_delete = function(){
        var http = WebService.post($scope.uri+'/ajax-check-password',{password:$("#password").val()});
        http.then(function(response){
            var data = response.data;
            if(data.status == 400){
                $scope.warning_messages = data.warning_messages;
            }else{
                if($scope.type == "category"){

                    var http = WebService.get($scope.uri+'/delete-category/'+$scope.categoryObj.id);
                    http.then(function(response){
                        var data = response.data;
                        if(data.status == 200){

                            $scope.success_messages = data.success_messages;

                            if($scope.index >= 0)
                            {
                                $scope.categories.splice($scope.index,1);
                            }
                        }else{
                            $scope.warning_messages = data.warning_messages;
                        }
                        $("html,body").animate({scrollTop:'0px'});
                    });
                    $scope.delete_item = false;


                }else if($scope.type == "subcategory"){


                    var http = WebService.get($scope.uri+'/delete-sub-category/'+$scope.subCategoryObj.id);
                    http.then(function(response){
                        var data = response.data;
                        if(data.status == 200){
                            $scope.success_messages = data.success_messages;
                            $scope.sub_categories.splice($scope.index,1);
                         
                            reloadSubCategory($scope.formData.categoryId);
                            
                            loadPrograms();
                        }else{
                            $scope.warning_messages = data.warning_messages;

                        }

                        //loadSelectedProgram($scope.subCategoryObj.id);
                    });

                    $scope.delete_item = false;

                }else if($scope.type == "categoryprogram"){

                    var http = WebService.post($scope.uri+'/delete-category-program',{
                        id:$scope.categoryProgramObj.id,
                        category_id:$scope.categoryObj.id,
                        sub_category_id:$scope.subCategoryObj.id
                    });
                    http.then(function(response){
                        var data = response.data;
                        if(data.status == 200){
                            $scope.assigned_programs.splice($scope.index,1);
                            $scope.success_messages = data.success_messages;
                            loadSelectedProgram($scope.subCategoryObj.id);
                        }
                    });

                    $scope.delete_item = false;
                }
            }
        });
    };

    $scope.cancel_delete = function(){
        $scope.delete_item = false;
    };



    $scope.loadSelectedPrograms = function(i){
        var subCategory = $scope.sub_categories[i];
        $scope.subCategoryObj = subCategory;
        $scope.formData.category_id = subCategory.category_id;

        $scope.formData.id = subCategory.id;
        loadSelectedProgram(subCategory.id);
    };

    $scope.selectSubCategory = function(i){
        var subCategory = $scope.showSubcategories[i];

        $scope.formData.categoryId = subCategory.category_id;
        $scope.formData.subCategoryId = subCategory.id;
        $scope.formData.sub_category_name = subCategory.sub_category_name;
    };


    var assignProgramCategory = function(program){
        $scope.closeAlert();
        var programs = [];
        programs.push(program.id);
        var formData = {
            category_id: $scope.categoryObj.id,
            sub_category_id : $scope.subCategoryObj.id,
            programs: programs
        };

        var http = WebService.post($scope.uri+'/assign-program-category',formData);
        http.then(function(response){
            var data = response.data;
            if(data.status == 400){
                $scope.warning_messages = data.warning_messages;
                $("html,body").animate({scrollTop:'0px'});
            }else{
                $scope.success_messages = data.success_messages;
                $scope.listData.program = '';
                //$("html,body").animate({scrollTop:'0px'});
                loadSelectedProgram(formData.sub_category_id);
                //window.location.reload();
            }


        });

    };


    $scope.addProgram = function(){

       $scope.programs.filter(function(v){
           if(v.id == $("#program").val()){
               $scope.programObj = v;
           }
       });

       if($scope.assigned_programs.length>0)
       {
           for(i in $scope.assigned_programs)
           {
               if($scope.assigned_programs[i].id == $scope.programObj.id){
                   break;
               }else{
                   console.log($scope.assigned_programs)
                   $scope.assigned_programs.push($scope.programObj);
                   var indexOf = $scope.programs.indexOf($scope.programObj);
                   $scope.programs.splice(indexOf,1);
                   assignProgramCategory($scope.programObj);
                   break;
               }
           }
       }else{
           console.log('2')
           $scope.assigned_programs.push($scope.programObj);
           var indexOf = $scope.programs.indexOf($scope.programObj);
           $scope.programs.splice(indexOf,1);
           assignProgramCategory($scope.programObj);
       }



    };



    var loadPrograms = function(){
        var http = WebService.get($scope.uri+'/ajax-get-programs');
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.programs = data.programs;
            }
        });
    };

    loadPrograms();




}]);