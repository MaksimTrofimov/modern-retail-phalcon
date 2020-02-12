$(document).ready(function() {
    $.ajax({
        type:"GET",
        url:"/user/listUser",
        success:function(data){
            $.each(JSON.parse(data),function(index, value){
                var str = '<tr>\
                            <td>user_id</td>\
                            <td><a href="/user/userAddress?id=user_id">firstname</a></td>\
                            <td>lastname</td>\
                            <td>email</td>\
                            <td>user_type</td>\
                            <td>created_at</td>\
                        </tr>';
                str = str.replace("user_id", value.user_id);
                str = str.replace("user_id", value.user_id);
                str = str.replace("firstname", value.firstname);
                str = str.replace("lastname", value.lastname);
                str = str.replace("email", value.email);
                str = str.replace("user_type", value.user_type);
                str = str.replace("created_at", value.created_at);
                $("#user-list tbody").append(str);
            });
        }
    });
});

