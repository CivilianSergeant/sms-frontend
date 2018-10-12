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
app.controller('Permission',['$scope','WebService',function($scope,WebService){
    $scope.roles = $scope.menu_routes = [];
    $scope.role = {};
    $scope.permission_editable = null;
    $scope.role_id = 0;
    $scope.menu_routes = [];
    $scope.parent_routes = [];

    $scope.user_type = null;
    $scope.role_type = null;
    //console.log(userRole);


    $scope.role_id = userRole;
    $scope.loading = 0;

    var loadRoles = function(){
        var http = WebService.get('permissions/ajax-get-roles');
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.roles = data.roles;
            }
        });
    };

    $scope.getMenuRoutes = function(){
        if($scope.role_id == "" || $scope.role_id == undefined || $scope.role_id == 0){
            $scope.menu_routes = [];
            return;
        }
      if($scope.role_id != null || $scope.role_id != 0){
          $scope.menu_routes = [];
          $scope.loading = 1;
          var http = WebService.post('permissions/ajax-get-menu-routes',{role_id:$scope.role_id});
          http.then(function(response){
              var data = response.data;
              if(data.status==200){
                $scope.loading = 0;
                $scope.menu_routes = data.menu_routes;
                $scope.role = data.role;
                $scope.permission_editable = data.permission_editable;
                $scope.user_type = data.user_type;
                $scope.role_type = data.role_type;
                $scope.parent_routes = data.parent_routes;

              }
          });
      }

    };

    $scope.closeAlert = function(){
        $scope.success_messages = $scope.warning_messages = $scope.error_messages = '';
    };

    $scope.isDisabled = function(){
        if($scope.role.role_type =="admin" && $scope.role.user_type=="MSO"){
            return true;
        }
        if($scope.permission_editable){
            return true;
        }
        return false;
    };

    $scope.togglePermission = function(i,j){
        if($scope.role.role_type =="admin" && $scope.role.user_type=="MSO"){
          return;
        }
        if($scope.permission_editable){
            return;
        }
        if($scope.menu_routes[i] != undefined){

          var item = null;
          if(i!=undefined && j!=undefined){
              item = $scope.menu_routes[i].submenus[j];
              /*if($scope.user_type == 'lco') {
                  var parentRoute = $scope.parent_routes[i].submenus[j];
                  item.create_permission = (parentRoute.create_permission == 1) ? item.permission : 0;
                  item.edit_permission = (parentRoute.edit_permission == 1) ? item.permission : 0;
                  item.delete_permission = (parentRoute.delete_permission == 1) ? item.permission : 0;
              }else{
                  item.create_permission = item.permission;
                  item.edit_permission   = item.permission;
                  item.delete_permission = item.permission;
              }*/

              item.create_permission = item.permission;
              item.edit_permission   = item.permission;
              item.delete_permission = item.permission;

              var formData = {
                  main_menu_id: item.id,
                  permission:(item.permission)?item.permission :0,
                  create_permission:(item.create_permission)? item.create_permission : 0,
                  edit_permission:(item.edit_permission)? item.edit_permission : 0,
                  delete_permission:(item.delete_permission)? item.delete_permission : 0,
                  role_id:$scope.role_id,

              };

              var http = WebService.post('permissions/toggle',formData);
              http.then(function(response){
                  var data = response.data;
                  if(data.status == 200){

                  }
              });

          }else{
              item = $scope.menu_routes[i];
              item.create_permission = item.permission;
              item.edit_permission   = item.permission;
              item.delete_permission = item.permission;

              if(item.permission == "0")
              {
                  toggleSubmenu(i,"0");
              }else{
                  toggleSubmenu(i,"1");
              }



              var formData = {
                  main_menu_id: item.main_menu_id,
                  sub_menus: item.submenus,
                  permission:(item.permission)? item.permission : 0,
                  create_permission:(item.create_permission)? item.create_permission: 0,
                  edit_permission:(item.edit_permission)? item.edit_permission: 0,
                  delete_permission:(item.delete_permission)? item.delete_permission: 0,
                  role_id:$scope.role_id
              };

              var http = WebService.post('permissions/toggle',formData);
              http.then(function(response){
                  var data = response.data;
                  if(data.status == 200){
                    $scope.success_messages = 'Permission Changed';
                  }
              });

          }

        }
    };

    var toggleSubmenu = function(item,permission){
        var submenus = $scope.menu_routes[item].submenus;

        if(submenus.length){
            for(var i in submenus) {

                    $scope.menu_routes[item].submenus[i].permission = permission;
                    $scope.menu_routes[item].submenus[i].create_permission = permission;
                    $scope.menu_routes[item].submenus[i].edit_permission = permission;
                    $scope.menu_routes[item].submenus[i].delete_permission = permission;

            }
        }
    }


    $scope.toggleCreatePermission = function(i,j){
        if($scope.role.role_type =="admin" && $scope.role.user_type=="MSO"){
            return;
        }
        if($scope.menu_routes[i] != undefined){

            var item = null;
            if(i!=undefined && j!=undefined){
                item = $scope.menu_routes[i].submenus[j];
                /*if($scope.user_type=='lco'){
                    var parentRoute = $scope.parent_routes[i].submenus[j];
                    item.create_permission = (parentRoute.create_permission==1)? item.create_permission : 0;
                }*/

                var formData = {
                    main_menu_id: item.id,
                    create_permission:(item.create_permission)?item.create_permission :0,
                    role_id:$scope.role_id,

                };
                var http = WebService.post('permissions/toggle_create',formData);
                http.then(function(response){
                    var data = response.data;
                    if(data.status == 200){
                        $scope.success_messages = 'Permission Changed';
                    }
                });

            }else{
                item = $scope.menu_routes[i];

                if(item.create_permission == "0")
                {
                    toggleCreateSubmenu(item,"0");
                }else{
                    toggleCreateSubmenu(item,"1");
                }



                var formData = {
                    main_menu_id: item.main_menu_id,
                    sub_menus: item.submenus,
                    create_permission:(item.create_permission)? item.create_permission : 0,
                    role_id:$scope.role_id
                };

                var http = WebService.post('permissions/toggle_create',formData);
                http.then(function(response){
                    var data = response.data;
                    if(data.status == 200){
                        $scope.success_messages = 'Permission Changed';
                    }
                });

            }




        }
    };

    var toggleCreateSubmenu = function(item,permission){
        var submenus = item.submenus;


        if(submenus.length){
            for(var i in submenus) {
                    submenus[i].create_permission = permission;

            }
        }
    }

    // Edit Permission
    $scope.toggleEditPermission = function(i,j){
        if($scope.role.role_type =="admin" && $scope.role.user_type=="MSO"){
            return;
        }
        if($scope.menu_routes[i] != undefined){

            var item = null;
            if(i!=undefined && j!=undefined){
                item = $scope.menu_routes[i].submenus[j];


                var formData = {
                    main_menu_id: item.id,
                    edit_permission:(item.edit_permission)?item.edit_permission :0,
                    role_id:$scope.role_id,

                };
                var http = WebService.post('permissions/toggle_edit',formData);
                http.then(function(response){
                    var data = response.data;
                    if(data.status == 200){
                        $scope.success_messages = 'Permission Changed';
                    }
                });

            }else{
                item = $scope.menu_routes[i];

                if(item.edit_permission == "0")
                {
                    toggleEditSubmenu(item,"0");
                }else{
                    toggleEditSubmenu(item,"1");
                }

                var formData = {
                    main_menu_id: item.main_menu_id,
                    sub_menus: item.submenus,
                    edit_permission:(item.edit_permission)? item.edit_permission : 0,
                    role_id:$scope.role_id
                };

                var http = WebService.post('permissions/toggle_edit',formData);
                http.then(function(response){
                    var data = response.data;
                    if(data.status == 200){
                        $scope.success_messages = 'Permission Changed';
                    }
                });

            }




        }
    };

    var toggleEditSubmenu = function(item,permission){
        var submenus = item.submenus;

        if(submenus.length){
            for(var i in submenus) {
                    submenus[i].edit_permission = permission;

            }
        }
    }

    // Delete Permission
    $scope.toggleDeletePermission = function(i,j){
        if($scope.role.role_type =="admin" && $scope.role.user_type=="MSO"){
            return;
        }
        if($scope.menu_routes[i] != undefined){

            var item = null;
            if(i!=undefined && j!=undefined){
                item = $scope.menu_routes[i].submenus[j];

                var formData = {
                    main_menu_id: item.id,
                    delete_permission:(item.delete_permission)? item.delete_permission :0,
                    role_id:$scope.role_id,

                };
                var http = WebService.post('permissions/toggle_delete',formData);
                http.then(function(response){
                    var data = response.data;
                    if(data.status == 200){
                        $scope.success_messages = 'Permission Changed';
                    }
                });

            }else{
                item = $scope.menu_routes[i];

                if(item.delete_permission == "0")
                {
                    toggleDeleteSubmenu(item,"0");
                }else{
                    toggleDeleteSubmenu(item,"1");
                }

                var formData = {
                    main_menu_id: item.main_menu_id,
                    sub_menus: item.submenus,
                    delete_permission:(item.delete_permission)? item.delete_permission : 0,
                    role_id:$scope.role_id
                };

                var http = WebService.post('permissions/toggle_delete',formData);
                http.then(function(response){
                    var data = response.data;
                    if(data.status == 200){
                        $scope.success_messages = 'Permission Changed';
                    }
                });

            }




        }
    };

    var toggleDeleteSubmenu = function(item,permission){
        var submenus = item.submenus;

        if(submenus.length){
            for(var i in submenus) {
                    submenus[i].delete_permission = permission;

            }
        }
    }

    loadRoles();

    if($scope.role_id != null && $scope.role_id != 0){

        $scope.getMenuRoutes();
    }
}]);