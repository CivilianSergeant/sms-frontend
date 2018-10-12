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
app.controller('parkingReport',['$scope','WebService',function($scope,WebService){

    $scope.stb_cards = [];
    $scope.ic_cards  = [];
    $scope.subscribers = [];

    var loadCards = function(){
        var http = WebService.get('parking-report/ajax-get-data');
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.stb_cards = data.stb_cards;
                $scope.ic_cards  = data.ic_cards;
                $scope.subscribers = data.subscribers;
            }
        });
    };

    loadCards();

    $scope.searchResult = function()
    {
        var stb = $scope.formData.stb_id;
        stb = (stb == null)? '' : stb;

        var smart_card = $scope.formData.card_id;
        smart_card = (smart_card == null)? '' : smart_card;

        var subscriber_id = $scope.formData.subscriber_id;
        subscriber_id = (subscriber_id == null)? '' : subscriber_id;

        var from_date = $scope.formData.from_date;
        from_date = (from_date == undefined)? '' : from_date;

        var to_date = $scope.formData.to_date;
        to_date = (to_date == undefined)? '' : to_date;

        var grid = $('#grid').data("kendoGrid");
        grid.dataSource.transport.options.read.url="parking-report/ajax-get-parking-report?subscriber_id="+subscriber_id+"&stb="+stb+"&smart_card="+smart_card+"&from_date="+from_date+"&to_date="+to_date,
            grid.dataSource.read();
        grid.refresh();

    };

    var generateKendoGird = function(){
        $scope.mainGridOptions = {
            dataSource: {
                transport: {
                    read: {
                        url: "parking-report/ajax-get-parking-report",
                        dataType: "json",
                    }
                },
                schema: {
                    data: "reports",
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


                {field: "subscriber_name", title: "Subscriber",filterable:false,width: "auto"},
                {field: "stb_id", title: "STB",filterable:false,width: "auto"},
                {field: "card_id", title: "IC",filterable:false,width: "auto"},
                {field: "parking_date", title: "Parking Date",filterable:false,width: "auto"},
                /*{field: "", title: "Action",width: "80px",template:"<a ng-click='stop(#=data.id#)' class='btn btn-danger btn-xs' data-toggle='tooltip' data-placement='left' title='Stop'><i class='fa fa-trash'></i></a>"},*/
            ]
        };
    };

    generateKendoGird();

}]);
app.controller('reassignFromParking',['$scope','WebService',function($scope,WebService){
    $scope.stb_cards = [];
    $scope.ic_cards  = [];
    $scope.subscribers = [];

    var loadCards = function(){
        var http = WebService.get('parking-report/ajax-get-data');
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.stb_cards = data.stb_cards;
                $scope.ic_cards  = data.ic_cards;
                $scope.subscribers = data.subscribers;
            }
        });
    };

    loadCards();

    $scope.searchResult = function()
    {
        var stb = $scope.formData.stb_id;
        stb = (stb == null)? '' : stb;

        var smart_card = $scope.formData.card_id;
        smart_card = (smart_card == null)? '' : smart_card;

        var subscriber_id = $scope.formData.subscriber_id;
        subscriber_id = (subscriber_id == null)? '' : subscriber_id;

        var from_date = $scope.formData.from_date;
        from_date = (from_date == undefined)? '' : from_date;

        var to_date = $scope.formData.to_date;
        to_date = (to_date == undefined)? '' : to_date;

        var grid = $('#grid').data("kendoGrid");
        grid.dataSource.transport.options.read.url="reassign-pairing-report/ajax-get-reassign-report?subscriber_id="+subscriber_id+"&stb="+stb+"&smart_card="+smart_card+"&from_date="+from_date+"&to_date="+to_date,
            grid.dataSource.read();
        grid.refresh();

    };

    var generateKendoGird = function(){
        $scope.mainGridOptions = {
            dataSource: {
                transport: {
                    read: {
                        url: "reassign-pairing-report/ajax-get-reassign-report",
                        dataType: "json",
                    }
                },
                schema: {
                    data: "reports",
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


                {field: "old_subscriber_name", title: "Old Subscriber",filterable:false,width: "auto"},
                {field: "new_subscriber_name", title: "New Subscriber",filterable:false,width: "auto"},
                {field: "stb_id", title: "STB",filterable:false,width: "auto"},
                {field: "card_id", title: "IC",filterable:false,width: "auto"},
                {field: "parking_date", title: "Parking Date",filterable:false,width: "auto"},
                {field: "updated_at", title: "Parking Reassign Date",filterable:false,width: "auto"},
                /*{field: "", title: "Action",width: "80px",template:"<a ng-click='stop(#=data.id#)' class='btn btn-danger btn-xs' data-toggle='tooltip' data-placement='left' title='Stop'><i class='fa fa-trash'></i></a>"},*/
            ]
        };
    };

    generateKendoGird();
}]);
app.controller('ownershipTransferReport',['$scope','WebService',function($scope,WebService){
    $scope.stb_cards = [];
    $scope.ic_cards  = [];
    $scope.subscribers = [];

    var loadCards = function(){
        var http = WebService.get('ownership-transfer-report/ajax-get-data');
        http.then(function(response){
            var data = response.data;
            if(data.status == 200){
                $scope.stb_cards = data.stb_cards;
                $scope.ic_cards  = data.ic_cards;
                $scope.subscribers = data.subscribers;
            }
        });
    };

    loadCards();

    $scope.searchResult = function()
    {
        var stb = $scope.formData.stb_id;
        stb = (stb == null)? '' : stb;

        var smart_card = $scope.formData.card_id;
        smart_card = (smart_card == null)? '' : smart_card;

        var subscriber_id = $scope.formData.subscriber_id;
        subscriber_id = (subscriber_id == null)? '' : subscriber_id;

        var from_date = $scope.formData.from_date;
        from_date = (from_date == undefined)? '' : from_date;

        var to_date = $scope.formData.to_date;
        to_date = (to_date == undefined)? '' : to_date;

        var grid = $('#grid').data("kendoGrid");
        grid.dataSource.transport.options.read.url="ownership-transfer-report/ajax-get-transfer-report?subscriber_id="+subscriber_id+"&stb="+stb+"&smart_card="+smart_card+"&from_date="+from_date+"&to_date="+to_date,
            grid.dataSource.read();
        grid.refresh();

    };

    var generateKendoGird = function(){
        $scope.mainGridOptions = {
            dataSource: {
                transport: {
                    read: {
                        url: "ownership-transfer-report/ajax-get-transfer-report",
                        dataType: "json",
                    }
                },
                schema: {
                    data: "reports",
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
                {field: "old_subscriber_name", title: "Old Subscriber",filterable:false,width: "auto"},
                {field: "new_subscriber_name", title: "New Subscriber",filterable:false,width: "auto"},
                {field: "stb_id", title: "STB",filterable:false,width: "auto"},
                {field: "card_id", title: "IC",filterable:false,width: "auto"},
                {field: "created_at", title: "Transfer Date",filterable:false,width: "auto"},
                /*{field: "", title: "Action",width: "80px",template:"<a ng-click='stop(#=data.id#)' class='btn btn-danger btn-xs' data-toggle='tooltip' data-placement='left' title='Stop'><i class='fa fa-trash'></i></a>"},*/
            ]
        };
    };

    generateKendoGird();
}]);