<?php

namespace app\modules\manage\assets;

use yii\web\AssetBundle;

class LTEAsset extends AssetBundle
{
    public $basePath = '@bower/AdminLTE';
    public $baseUrl = '/lib/AdminLTE';

    public $css = [
        /*<!-- Bootstrap 3.3.5 -->*/
        'bootstrap/css/bootstrap.css',
        
        /*<!-- Font Awesome -->*/
        '//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css',
        
        /*<!-- Ionicons -->*/
        '//code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css',
                
        /* <!-- AdminLTE Skins. Choose a skin from the css/skins
        folder instead of downloading all of them to reduce the load. -->*/
        'dist/css/skins/_all-skins.min.css',
        
        /*<!-- iCheck -->*/
        'plugins/iCheck/square/blue.css',
        
        /*<!-- Morris chart -->*/
        //'plugins/morris/morris.css',
        
        /*<!-- jvectormap -->*/
        //'plugins/jvectormap/jquery-jvectormap-1.2.2.css',
        
        /*<!-- Date Picker -->*/
        'plugins/datepicker/datepicker3.css',
        
        /* select2 */
        'plugins/select2/select2.min.css',
        
        /*<!-- Daterange picker -->*/
        //'plugins/daterangepicker/daterangepicker-bs3.css',
        
        /*<!-- bootstrap wysihtml5 - text editor -->*/
        //'plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css',

        /*<!-- Theme style -->*/
        'dist/css/AdminLTE.min.css',
    ];
    public $js = [
        
        /*<!-- jQuery UI 1.11.4 -->*/
        '//code.jquery.com/ui/1.11.4/jquery-ui.min.js',
        
        /*<!-- Bootstrap 3.3.5 -->*/
        'bootstrap/js/bootstrap.min.js',

        /*<!-- Morris.js charts -->*/
        //"//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js",
        //"plugins/morris/morris.min.js",
        
        /*<!-- Sparkline -->*/
        //'plugins/sparkline/jquery.sparkline.min.js',

        /*<!-- jvectormap -->*/
        //'plugins/jvectormap/jquery-jvectormap-1.2.2.min.js',
        //'plugins/jvectormap/jquery-jvectormap-world-mill-en.js',

        /*<!-- jQuery Knob Chart -->*/
        //'plugins/knob/jquery.knob.js',
        
        /*<!-- daterangepicker -->*/
        '//cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js',
        'plugins/daterangepicker/daterangepicker.js',

        /*<!-- icheck -->*/
        'plugins/iCheck/icheck.min.js',

        /*<!-- datepicker -->*/
        'plugins/datepicker/bootstrap-datepicker.js',
        'plugins/datepicker/locales/bootstrap-datepicker.ru.js',
        
        /* select2 */
        'plugins/select2/select2.min.js',
        'plugins/select2/i18n/ru.js',

        /*<!-- Bootstrap WYSIHTML5 -->*/
        //'plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js',
        
        /*<!-- Slimscroll -->*/
        //'plugins/slimScroll/jquery.slimscroll.min.js',
        
        /*<!-- FastClick -->*/
        //'plugins/fastclick/fastclick.js',
        
        /*<!-- AdminLTE App -->*/
        'dist/js/app.min.js',
        
        /*<!-- AdminLTE dashboard demo (This is only for demo purposes) -->*/
        //'dist/js/pages/dashboard.js',
        
        /*<!-- AdminLTE for demo purposes -->*/
        //'dist/js/demo.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
    ];
}