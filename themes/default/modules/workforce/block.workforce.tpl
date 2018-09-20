<!-- BEGIN: main -->
<table class="table table-striped table-bordered table-hover">
    <thead>
        <tr>
            <th>Tên nhân viên</th>
            <th>Góp ý</th>
        </tr>
    </thead>
    <tbody>
        <!-- BEGIN: workforce -->
        <tr>
            <td>{WORKFORCE_VIEW.fullname}</td>
            <td>
                <ul class=" list-inline">
                    <li><a href="" class="btn btn-primary btn-xs" data-toggle="tooltip" data-original-title="{LANG.feedback}" onclick="nv_workforce_feedback({WORKFORCE_VIEW.id}); return !1;"><em class="fa fa-comments">&nbsp;</em>{LANG.create}</a></li>
                </ul>
            </td>
        </tr>
        <!-- END: workforce -->
    </tbody>
</table>
<!-- END: main -->