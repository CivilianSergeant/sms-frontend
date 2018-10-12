<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script type="text/javascript">
    var token = "<?php echo $token; ?>";
</script>
<div id="container" ng-controller="notification" ng-cloak>
    <div class="alert alert-success" ng-show="success_messages" ng-model="success_messages">
        <button class="close" ng-click="closeAlert()">×</button>
        {{success_messages}}
    </div>
    <div class="panel panel-default">
        <div class="row">
        <div id="result">
            <div class="col-md-12">
                <div class="panel-heading">
                    
                    <h4 class="widgettitle"> 
                        Notifications [{{$parent.countNotification}}]
                        <a class="btn btn-danger btn-xs pull-right" ng-click="deleteAll()" style="margin-left:10px;"><i class="fa fa-trash"></i> Delete All</a>
                        <a class="btn btn-primary btn-xs pull-right" ng-click="refresh()"><i class="fa fa-refresh"></i> Refresh</a>
                    </h4>
                    <span class="clearfix"></span>
                </div>
                <hr/>
            </div>
            <style type="text/css">
            .all_msg{background: #F2F2F2;border-radius: 5px;margin-bottom: 10px; padding: 5px 0 5px 15px;text-align: justify;}
            .all_msg strong{color:#191919;}
            .all_msg p{margin-right: 15px;}
            article{margin-top: 7px;}
            .panel-body a {color:#2b669a;}
            .close{margin-right: 15px;}
            </style>
            <div class="panel-body">
                <div class="col-md-12">
                    <div class="widgettitle">
                        
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="all_msg" ng-repeat="msg in notification">
                                <article>
                                    <h6>{{msg.title}}<button ng-click="delete($index)" class="close pull-right" aria-label="close">×</button></h6>

                                    <p>{{msg.description}}</p>
                                </article>

                            </div>
                        </div>
                       <!--  <div class="col-md-12">
                            <div class="all_msg">
                                <article>
                                    <h6>heading<button class="close pull-right" aria-label="close">×</button></h6>

                                    <p>From this distant vantage point, the Earth might not seem of any particular interest. But for us, it's different. Consider again that dot.

                                    Space, the final frontier. These are the voyages of the starship Enterprise. Its five year mission: to explore strange new worlds, to seek out new life and new civilizations, to boldly go where no man has gone before!</p>
                                </article>

                            </div>
                        </div> -->
                    </div>
                    <div class="col-md-12 text-center" ng-if="isLoadPreviousEnabled()">
                        <a ng-click="getPreviousMessage()" style="cursor:pointer;">{{ ($parent.countNotification - notification.length)}} New notification</a>
                    </div>
                    <div class="col-md-12 text-center" ng-if="!notification.length">
                        <p>No notification found</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo PROJECT_PATH.'public/theme/katniss/'; ?>js/readmore.js"></script>

  <script>
    $('#info').readmore({
      moreLink: '<a href="#">Usage, examples, and options</a>',
      collapsedHeight: 384,
      afterToggle: function(trigger, element, expanded) {
        if(! expanded) { // The "Close" link was clicked
          $('html, body').animate({scrollTop: element.offset().top}, {duration: 100});
        }
      }
    });

    $('article').readmore({speed: 200});
  </script>


 
