/**
 * @Project NUKEVIET 4.x
 * @Author TDFOSS.,LTD (contact@tdfoss.vn)
 * @Copyright (C) 2018 TDFOSS.,LTD. All rights reserved
 * @Createdate Tue, 02 Jan 2018 08:34:29 GMT
 */

$('#current-month, #partid').change(function() {
    window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=salary-content&month=' + $('#current-month').val() + '&partid=' + $('#partid').val();
});

function nv_list_action(action, url_action, del_confirm_no_post) {
    var listall = [];
    $('input.post:checked').each(function() {
        listall.push($(this).val());
    });
    if (listall.length < 1) {
        alert(del_confirm_no_post);
        return false;
    }
    if (action == 'delete_list_id') {
        if (confirm(nv_is_del_confirm[0])) {
            $.ajax({
                type : 'POST',
                url : url_action,
                data : 'delete_list=1&listall=' + listall,
                success : function(data) {
                    var r_split = data.split('_');
                    if (r_split[0] == 'OK') {
                        window.location.href = window.location.href;
                    } else {
                        alert(nv_is_del_confirm[2]);
                    }
                }
            });
        }
    }
    return false;
}

// $('#form-workforce').submit(function(e) {
// e.preventDefault();
// $.ajax({
// url : script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=content&nocache=' + new Date().getTime(),
// type : 'post',
// data : $(this).serialize(),
// success : function(json) {
// if (json.error) {
// alert(json.msg);
// } else {
// alert('Thêm dữ liệu thành công !');
// location.reload();
// }
//           
// }
//    
// });
// });

function nv_table_row_click(e, t, n) {
    var r = e.target.tagName.toLowerCase(), i = e.target.parentNode.tagName.toLowerCase(), a = e.target.parentNode.parentNode.parentNode;
    return void ("button" != r && "a" != r && "button" != i && "a" != i && "td" != i && (n ? window.open(t) : window.location.href = t))
}

function nv_chang_status(vid) {
    var nv_timer = nv_settimeout_disable('change_status_' + vid, 1000);
    var new_status = $('#change_status_' + vid).val();
    $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=detail&nocache=' + new Date().getTime(), 'change_status=1&id=' + vid + '&new_status=' + new_status, function(res) {
        var r_split = res.split("_");
        if (r_split[0] != 'OK') {
            alert(nv_is_change_act_confirm[2]);
            clearTimeout(nv_timer);
        }
        return;
    });
    return;
}