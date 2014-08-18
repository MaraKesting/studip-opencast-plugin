OC = {
    formData : {},
    initAdmin : function(){
        jQuery(document).ready(function(){
            jQuery('#admin-accordion').accordion();
        });
    },
    
    initSeries : function(){
        jQuery(document).ready(function(){
            if( jQuery('#select-series').data("unconnected") !== 1 ) {
                jQuery('.series_select').attr("disabled", true);
                jQuery('.form_submit').children().attr("disabled", true);
                $('#admin-accordion').accordion({ active: 1,
                                                  autoHeight: false,
                                                  clearStyle: true });
            } else {
                jQuery('#admin-accordion').accordion({autoHeight: false,
                                                      clearStyle: true });
            }
        })
    },
    
    initIndexpage: function(){
        jQuery( document ).ready(function() {
            // Episode-List
            var height = jQuery('#episodes').height();
            jQuery('#episodes').slimScroll({
                height: height,
                 alwaysVisible: false
            });
            // Upload Dialog
            jQuery("#upload_dialog").dialog({ autoOpen: false, width: 800, dialogClass: 'ocUpload'});
            jQuery("#oc_upload_dialog").click(
                function () {
                    jQuery("#upload_dialog").dialog('open');
                    return false;
                }
            );
        });

    },
    
    initUpload : function(maxChunk){
        jQuery(document).ready(function(){
            $('#btn_accept').click(function() {
                OC.formData.submit();
                return false;
            });
            
            $('#video_upload').fileupload({
                limitMultiFileUploads: 1,
                autoupload: false,
                maxChunkSize: maxChunk,
                add: function(e, data) {
                    var file = data.files[0];
                    $('#total_file_size').attr('value', file.size);
                    $('#file_name').attr('value', file.name);
                    $('#upload_info').html('<p>Name: ' 
                                                + file.name 
                                                + '<br />Gr��e: ' 
                                                + OC.getFileSize(file.size) 
                                                + '</p>');
                    $('#upload_info').val(file.name);
                    OC.formData = data;
                    return false;
                },
                submit: function (e, data) {
                    $( "#progressbar" ).progressbar({
                        value: 0
                    }).addClass('oc_mediaupload_progressbar').show().css({'background': '#d0d7e4'});;
                    
                },
                progressall: function(e, data) {
                    var progress = parseInt(data.loaded / data.total * 100, 10);
                    jQuery( "#progressbar" ).progressbar( "value", progress);
                    jQuery("#progressbar-label").text(progress + " %");
                },
                done: function(e, data) {
                    jQuery( "#progressbar" ).progressbar('destroy');
                    jQuery("#upload_dialog").dialog("close");
                    window.open(STUDIP.ABSOLUTE_URI_STUDIP + "plugins.php/opencast/course/index/ /true", '_self');
                }
            });
            $('#recordDate').datepicker({
                dateFormat: "yy-mm-dd"
            });
        })
    },
    getFileSize: function(input) {
        if(input/1024 > 1) {
            var inp_kb = Math.round((input/1024)*100)/100
            if(inp_kb/1024 > 1) {
                var inp_mb = Math.round((inp_kb/1024)*100)/100
                if(inp_mb/1024 > 1) {
                    var inp_gb = Math.round((inp_mb/1024)*100)/100
                    return inp_gb + 'GB';
                }
                return inp_mb + 'MB';
            }
            return inp_kb + 'KB';
        }
        return input + 'Bytes'
    }
};

