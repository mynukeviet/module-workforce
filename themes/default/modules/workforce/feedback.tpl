<!-- BEGIN: main -->
<form class="form-horizontal" action="" method="post" id="frm-feedback">
    <input type="hidden" name="id" value="{ROW.id}" />
    <div style="padding: 20px">
        <div class="form-group">
            <label class="control-label"><strong></strong>{LANG.title}</label>
            <input class="form-control required" type="text" name="title" id="title" value="{ROW.title}" placeholder="{LANG.title}" />
        </div>
        <div class="form-group">
            <label class="control-label"><strong>{LANG.feedback}</strong></label> {ROW.feedback}
        </div>
        <div class="form-group text-center">
            <input type="hidden" name="submit" value="1" />
            <input class="btn btn-primary" type="submit" value="{LANG.send_feedback}" />
        </div>
    </div>
</form>
<!-- END: main -->