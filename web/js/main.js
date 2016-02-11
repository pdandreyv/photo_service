$(document).ready(function(){
    $('#photo_form').validate({
        rules: {
            tags: {required: true},
            photo: {required: true, accept: "jpg,jpeg,gif,png"}
        },
        messages: {
            tags: {
                required: "Please input tags"
            },
            photo: {
                required: "Please select Photo",
                accept: "Type of file must be image"
            }
        },
        submitHandler: function(form) {
            $.post($("#photo_form").attr('action'), $("#photo_form").serialize(),function(data){
                if(data) {
                    alert(data);
                }
            });
            return false;
        }
    });
});