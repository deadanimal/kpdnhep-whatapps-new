// Mainly scripts
require('../../../themes/inspinia/js/bootstrap.min');
require('../../../themes/inspinia/js/plugins/metisMenu/jquery.metisMenu');
require('../../../themes/inspinia/js/plugins/slimscroll/jquery.slimscroll.min');
require('../../../themes/inspinia/js/plugins/jeditable/jquery.jeditable');

// Data Tables
//require('../../../themes/inspinia/js/plugins/dataTables/datatables.min');

// Custom and plugin javascript
require('../../../themes/inspinia/js/inspinia');
//require('../../../themes/inspinia/js/plugins/select2/select2.full.min');
//require('../../../themes/inspinia/js/plugins/pace/pace.min');


// iCheck
require('../../../themes/inspinia/js/plugins/iCheck/icheck.min');
//require('../../../themes/inspinia/js/plugins/fullcalendar/moment.min');
//require('../../../themes/inspinia/js/plugins/daterangepicker/daterangepicker');
//require('../../../themes/inspinia/js/plugins/datapicker/bootstrap-datepicker');
//require('../../../themes/ace/js/moment');

$(document).ready(function () {
//    $('.js-example-basic-single').select2();
    $('.i-checks').iCheck({
        checkboxClass: 'icheckbox_square-green',
        radioClass: 'iradio_square-green',
    });
});
