<?php

function drawModal($client_name){
    return ("<script>
            /* must apply only after HTML has loaded */
            $(document).ready(function () {
                $(\"#contact_form\").on(\"submit\", function(e) {
                    var postData = $(this).serializeArray();
                    var formURL = $(this).attr(\"action\");
                    $.ajax({
                        type: \"POST\",
                        data: postData,
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
        
        <button type=\"button\" class=\"btn btn-info\" data-toggle=\"modal\" data-target=\"#contact_dialog\">Contact</button>
        
        <!-- the div that represents the modal dialog -->
        <div class=\"modal fade\" id=\"contact_dialog\" role=\"dialog\">
            <div class=\"modal-dialog\">
                <div class=\"modal-content\">
                    <div class=\"modal-header\">
                        <button type=\"button\" class=\"close\" data-dismiss=\"modal\">&times;</button>
                        <h4 class=\"modal-title\">Add Providers to ".$client_name."</h4>
                    </div>
                    <div class=\"modal-body\">
                        <form id=\"contact_form\" action=\"modal-submit.php\" method=\"POST\">
                            First name: <input type=\"text\" name=\"first_name\"><br/>
                            Last name: <input type=\"text\" name=\"last_name\"><br/>
                        </form>
                    </div>
                    <div class=\"modal-footer\">
                        <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>
                        <button type=\"button\" id=\"submitForm\" class=\"btn btn-default\">Send</button>
                    </div>
                </div>
            </div>
        </div>");
}
