<?php

function drawModal($ra, $oProsDB, $pro_roles){
    $s = "<script>
            /* must apply only after HTML has loaded */
            $(document).ready(function () {
                $(\"#contact_form\").on(\"submit\", function(e) {
                    var postData = $(this).serializeArray();
                    var formURL = $(this).attr(\"action\");
                    $.ajax({
                        type: \"POST\",
                        data: postData,
                        url: formURL,
                        success: function(data, textStatus, jqXHR) {
                            $('#contact_dialog').modal('hide');
                        },
                        error: function(jqXHR, status, error) {
                            console.log(status + \": \" + error);
                        }
                    });
                    e.preventDefault();
                });
                
                $(\"#submitForm\").on('click', function() {
                    $(\"#contact_form\").submit();
                });
                $(\"#contact_dialog\").on(\"hidden.bs.modal\", function(){
                    location.reload();
                });
            });
        </script>
        
        <button type=\"button\" data-toggle=\"modal\" data-target=\"#contact_dialog\">Add Pro</button>
        
        <!-- the div that represents the modal dialog -->
        <div class=\"modal fade\" id=\"contact_dialog\" role=\"dialog\">
            <div class=\"modal-dialog\">
                <div class=\"modal-content\">
                    <div class=\"modal-header\">
                        <h4 class=\"modal-title\">Add Providers to ".$ra['client_name']."</h4>
                        <button type=\"button\" class=\"close\" data-dismiss=\"modal\">&times;</button>
                    </div>
                    <div class=\"modal-body\">
                        <form id=\"contact_form\" action=\"modal-submit.php\" method=\"POST\">
                            <input type='hidden' value='{$ra['_key']}' />";
             $otherless = array_filter($pro_roles,function($var){
                return($var != "Other");  
             });
             foreach ($pro_roles as $role){
                 if($role == "Other"){
                     $s .= "$role <select name='$role'>"
                     .SEEDCore_ArrayExpandRows($oProsDB->KFRel()->GetRecordSetRA("pro_role NOT IN (".SEEDCore_ArrayExpandSeries($otherless, ",'[[]]'",TRUE,array("sTemplateFirst"=>"'[[]]'")).")"), "<option value='[[_key]]' />[[pro_name]] ([[pro_role]])")
                     ."</select><br />";
                 }else {
                     $s .= "$role <select name='$role'>"
                     .SEEDCore_ArrayExpandRows($oProsDB->KFRel()->GetRecordSetRA("pro_role='$role'"), "<option value='[[_key]]' />[[pro_name]]")
                     ."</select><br />";
                 }
              }
              $s .= "</form>
                    </div>
                    <div class=\"modal-footer\">
                        <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>
                        <button type=\"button\" id=\"submitForm\" class=\"btn btn-default\">Send</button>
                    </div>
                </div>
            </div>
        </div>";
    return($s);
}
