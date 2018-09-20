/**
 * @Project NUKEVIET 4.x
 * @Author TDFOSS.,LTD (contact@tdfoss.vn)
 * @Copyright (C) 2018 TDFOSS.,LTD. All rights reserved
 * @Createdate Tue, 02 Jan 2018 08:34:29 GMT
 */

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

$('#frm-feedback').submit(function(e) {
    e.preventDefault();
    
    for (instance in CKEDITOR.instances) {
        CKEDITOR.instances[instance].updateElement();
    }
    
    $.ajax({
        url : script_name + '?' + nv_name_variable + '=workforce&' + nv_fc_variable + '=feedback&nocache=' + new Date().getTime(),
        type : 'post',
        data : $(this).serialize(),
        success : function(json) {
            alert(json.msg);
            if (json.error) {
                $('#' + json.input).focus();
            } else {
                $('#sitemodal').modal('toggle');
            }
        }
    });
});

function nv_table_row_click(e, t, n) {
    var r = e.target.tagName.toLowerCase(), i = e.target.parentNode.tagName.toLowerCase(), a = e.target.parentNode.parentNode.parentNode;
    return void ("button" != r && "a" != r && "button" != i && "a" != i && "td" != i && (n ? window.open(t) : window.location.href = t))
}

function nv_workforce_feedback(id) {
    $.ajax({
        type : 'POST',
        url : script_name + '?' + nv_name_variable + '=workforce&' + nv_fc_variable + '=feedback&nocache=' + new Date().getTime(),
        data : 'id=' + id,
        success : function(html) {
            modalShow('', html);
            $('#sitemodal .modal-dialog').css('max-width', 900);
        }
    });
}