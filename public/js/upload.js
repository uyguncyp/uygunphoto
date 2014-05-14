var modal_body;
$(document).ready(function(){
    // Keep the default modal body in a variable
    // to revert it back when 'New' is clicked
    modal_body = $('#modal-body').html();
    var url = $("#url").val();
    var upload_link = url+'/upload';

    $('#uploading').hide();

    $("#inputFile").change(function() {
        if (window.File && window.FileReader && window.FileList && window.Blob) {
            var file = $('#inputFile')[0].files[0];
            
            if (['image/png', 'image/jpeg', 'image/jpg'].indexOf(file.type) === -1) {
                alert("File type must be PNG or JPEG");
            }
            if (file.size > 5242880) {
                alert("File size must be less than 5 MB!");
            }
        } else {
            alert("Please upgrade your browser, because your current browser lacks some new features we need!");
        }
    });

    $('#upload').on('click', function(){
        var title = $('#inputTitle').val();

        $('#uploading').show();

        $('#inputFile').upload(upload_link, {title: title}, function(data){
            if(data.error) {
                $('#uploading').hide();
                if(data.messages.Title) {
                    $('#form_title').addClass('has-error');
                    $('#help-block-title').html(data.messages.Title);
                } else {
                    $('#form_title').addClass('has-success');
                }

                if(data.messages.Photo) {
                    $('#form_photo').addClass('has-error');
                    $('#help-block-photo').html(data.messages.Photo);
                } else {
                    $('#form_photo').addClass('has-success');
                }
            } else {
                var html = '<a href="'+url+'/photo/'+data.id+'" class="thumbnail">';
                html += '<img src="'+url+'/img/thumbnail/'+data.thumbnail_name+'" width="90" height="30"></a>';
                $('.modal-body').html(html);
            }
        }, function(prog, value){
            $('#prog').width(value+'%');
        });
    });

    $('#new_upload').on('click', function(){
        $('#modal-body').html(modal_body);
        $('#uploading').hide();
    });
});
