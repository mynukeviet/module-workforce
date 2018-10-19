<!-- BEGIN: main -->
<link rel="stylesheet" media="screen" href="{NV_BASE_SITEURL}{NV_FILES_DIR}/js/handsontable/handsontable.full.css">
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.css">
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2-bootstrap.min.css">
<form action="{NV_BASE_SITEURL}index.php" method="get">
    <input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}" /> <input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" /> <input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}" />
    <div class="row">
        <div class="col-xs-24 col-md-4">
            <div class="form-group">
                <select class="form-control select2" name="partid" id="partid">
                    <option value="0">---{LANG.part_all}---</option>
                    <!-- BEGIN: parent_loop -->
                    <option value="{pid}"{pselect}>{ptitle}</option>
                    <!-- END: parent_loop -->
                </select>
            </div>
        </div>
        <div class="col-xs-24 col-md-4">
            <div class="form-group">
                <select class="form-control" name="month" id="current-month" onchange="window.reload()">
                    <!-- BEGIN: month -->
                    <option value="{MONTH.index}"{MONTH.selected}>{MONTH.value}</option>
                    <!-- END: month -->
                </select>
            </div>
        </div>
    </div>
</form>
<h1 class="text-center title">{TITLE}</h1>
<div id="salary-table"></div>
<script src="{NV_BASE_SITEURL}{NV_FILES_DIR}/js/handsontable/handsontable.full.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/i18n/{NV_LANG_INTERFACE}.js"></script>
<script>
$(document).ready(function () {
    $('.select2').select2({
        theme: 'bootstrap'
    });

    var data = {DATA};
    var $container = document.getElementById('salary-table');
	var hotElement = new Handsontable($container, {
        data : data,
        rowHeaders : true,
        colHeaders : true,
        filters : true,
        columns : [ {
            data : 'fullname',
            type : 'text',
            readOnly : true
        }, {
            data : 'salary',
            type : 'text',
            readOnly : true
        }, {
            data : 'allowance',
            type : 'numeric',
            readOnly : true
        }, {
            data : 'workday',
            type : 'numeric',
            numericFormat: {
                pattern: '0.00'
            }
        }, {
            data : 'overtime',
            type : 'numeric',
            numericFormat: {
                pattern: '0.00'
            }
        }, {
            data : 'holiday',
            type : 'numeric',
            numericFormat: {
                pattern: '0.00'
            }
        }, {
            data : 'holiday_salary',
            type : 'numeric',
            readOnly : true,
            numericFormat: {
                pattern: '0,'
            }
        }, {
            data : 'advance',
            type : 'numeric'
        }, {
            data : 'bonus',
            type : 'numeric'
        }, {
            data : 'total',
            type : 'numeric',
            readOnly : true,
            numericFormat: {
                pattern: '0,'
            }
        }, {
            data : 'bhxh',
            type : 'numeric',
            readOnly : true,
            numericFormat: {
                pattern: '0,'
            }
        }, {
            data : 'deduction',
            type : 'numeric',
            numericFormat: {
                pattern: '0,'
            }
        }, {
            data : 'received',
            type : 'numeric',
            readOnly : true,
            numericFormat: {
                pattern: '0,'
            }
        } ],
        stretchH : 'all',
        colHeaders : [ 'Họ & tên', 'Lương cơ bản', 'Phụ cấp', 'Ngày công', 'Ngày làm thêm', 'Phép - lễ', 'Lương nghỉ phép, nghỉ lễ', 'Tạm ứng', 'Thưởng', 'Tổng lương', 'Trừ BHXH', 'Các khoản trừ', 'Thực nhận' ],
        renderer: function (instance, td, row, col, prop, value, cellProperties) {
            Handsontable.TextCell.renderer.apply(this, arguments);

            if (cellProperties.isModified === true) {
                td.style.background = 'yellow';
            }
        },
        afterChange: function (changes, source) {
            if (source === 'loadData' || source === 'populateFromArray' || changes[0][1] === 'total' || changes[0][1] === 'received' || changes[0][1] === 'holiday_salary') {
                return;
            }
            
            // khi thêm cột cần điều chỉnh lại chỉ số cột
            var col_total = 9; // chỉ số cột tổng lương
            var col_received = 12; // chỉ số cột thực nhận
            var col_holiday_salary = 6; // chỉ số cột lương ngày nghỉ, lễ
            
            $.each(changes, function(index){
                var rowThatHasBeenChanged = changes[index][0],
                columnThatHasBeenChanged = changes[index][1];
                var sourceRow = hotElement.getSourceDataAtRow(rowThatHasBeenChanged);
              
                $.ajax({
                    url : script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=salary-content&save_change=1&month=' + $('#current-month').val() + '&nocache=' + new Date().getTime(),
                    type : "POST",
                    data : {
                        save_change: 1,
                        data : sourceRow
                    },
                    success : function(json) {
                        hotElement.setDataAtCell(changes[index][0], col_total, json.total);
                        hotElement.setDataAtCell(changes[index][0], col_received, json.received);
                        hotElement.setDataAtCell(changes[index][0], col_holiday_salary, json.holiday_salary);
                    }
                });                
            });
		},
		/* cells: function(row, col, prop) {
		    var cellProperties = {};
		    if (row === data.length - 1) {
		        cellProperties.readOnly = true;
	        }
		    return cellProperties;
	    },
	    mergeCells: [
            {row: data.length - 1, col: 0, rowspan: 1, colspan: 9}
        ] */
    });
});
</script>
<!-- END: main -->