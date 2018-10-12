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

app.controller('Backup',['$scope','WebService',function($scope,WebService) {
    $scope.isOk = false;
    $scope.loader = false;
    $scope.filename = '';
    $scope.formData = {};
    $scope.files = [];

    $scope.closeAlert = function(){
        $scope.success_messages = '';
        $scope.warning_messages = '';
        $scope.error_messages = '';
    };

    var loadDumpFileList = function(){
        var http = WebService.get('backup/ajax-dump-file-list');
        http.then(function(response){
            $scope.files = response.data.files;
            $scope.loader = false;
        });
    };

    var deleteFile = function() {
        var http = WebService.post('backup/delete',{file:$scope.filename});
        http.then(function(response){
            var data = response.data;
            if(data.status == 400){
                $scope.warning_messages = data.warning_messages;
            }else{
                loadDumpFileList();
                $scope.success_messages = data.success_messages;
            }
        });
    };

    $scope.closeAlert = function(){
        $scope.warning_messages = $scope.success_messages = $scope.error_messages = '';
    };

    $scope.checkPassword = function(){
        var http = WebService.post('backup/ajax-check-password',$scope.formData);
        http.then(function(response){
            var data = response.data;

            if(data.status == 400){
                $scope.warning_messages = data.warning_messages;
                $scope.isOk = false;
            }else{
                $scope.isOk = true;
                $scope.formData.password = '';
                if($scope.filename != undefined || $scope.filename != ''){
                    loadDumpFileList();
                }else{
                    loadDumpFileList();
                }

            }
        });
    };

    $scope.deleteItem = function(file){
        $scope.filename = file.filename;
        $scope.isOk = false;
    };


    $scope.dump = function(){
        var http = WebService.get('backup/dump');
        $scope.loader = true;
        http.then(function(response){
            var data = response.data;
            loadDumpFileList();
            if(data.status == 200){
                $scope.success_messages = data.success_messages;
            }else{
                $scope.warning_messages = data.warning_messages;
            }
        });
    };





}]);
app.controller('BackupTransfer',['$scope','WebService',function($scope,WebService) {
    $scope.file_name = file_name;
    $scope.accounts = [];
    $scope.transferData = {};
    $scope.loader = false;

    var loadFtpAccounts = function(){
        var http = WebService.get('backup/ajax-get-ftp-accounts');
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.accounts = data.accounts;
                if($scope.accounts.length==1){
                    $scope.accounts.filter(function(item){
                        $scope.transferData.ftp_account = item.id;
                    });
                }
            }
        });
    };

    loadFtpAccounts();

    $scope.closeAlert = function(){
        $scope.success_messages = '';
        $scope.warning_messages = '';
        $scope.error_messages = '';
    };

    $scope.transferFile = function(){
        $scope.closeAlert();
        $scope.loader = true;
        $scope.transferData.file_name = $scope.file_name;
        //console.log($scope.transferData);
        var http = WebService.post('backup/transfer',$scope.transferData);
        http.then(function(response){
            $scope.loader = false;
            var data = response.data;
            if(data.status == 400){
                $scope.warning_messages = data.warning_messages;
            }else{
                $scope.success_messages = data.success_messages;
            }
        });
    };


}]);