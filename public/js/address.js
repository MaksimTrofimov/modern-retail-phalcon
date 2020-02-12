$(document).ready(function() {

    var url = new URL(location);
    var searchParams = new URLSearchParams(url.search.substring(1));
    var id = searchParams.get("id");
    $("#user_id").val(id);

    if ($.isNumeric(id)) {
        $.ajax({
            type:"GET",
            url:"/user/userAddress",
            data:{id:id},
            success:function(data){
                $.each(JSON.parse(data),function(index, value){
                    var str = '<tr>\
                                <td>address_id</td>\
                                <td>user_id</td>\
                                <td>city</td>\
                                <td>postcode</td>\
                                <td>region</td>\
                                <td>street</td>\
                                <td><button type="button" id="address_id" class="address-delete btn btn-danger">Delete</button></td>\
                            </tr>';
                    str = str.replace("address_id", value.address_id);
                    str = str.replace("address_id", value.address_id);
                    str = str.replace("address_id", value.address_id);
                    str = str.replace("user_id", value.user_id);
                    str = str.replace("city", value.city);
                    str = str.replace("postcode", value.postcode);
                    str = str.replace("region", value.region);
                    str = str.replace("street", value.street);
                    $("#user-list tbody").append(str);
                });
            }
        });
    } else {
        var str = '<tr><td colspan="7">You have not selected a user! <a href="/">Go to the users page.</a></td><tr>';
        $("#user-list tbody").append(str);
    }

    $(document).on('click','.address-delete', function(){
        var $row = $(this).parents('tr');
        $.ajax({
            type:"GET",
            url:"/user/userAddressDelete",
            data: {id:this.id},
            success:function(data){
                if (JSON.parse(data).status == true) {
                    $row.remove();
                }
            }
        });
    });

});
