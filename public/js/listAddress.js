$(document).ready(function() {

    function getListAddress(id = null, page = 1) {console.log('a')
        $.ajax({
            type:"GET",
            url:"/user/listAddress",
            data:{id:id, currentPage:page},
            success:function(data){
                var adresses = JSON.parse(data);
                $("#user-list tbody").children().remove();
                renderListAddress(adresses.items);
                $("#paginate-total span").text(adresses.total_items);
                $("#paginate-total span").text(adresses.total_items);
                $("#current").text(adresses.current);
                $("#first").attr('value', adresses.first);
                $("#previous").attr('value', adresses.previous);
                $("#next").attr('value', adresses.next);
                $("#last").attr('value', adresses.last);
            }
        });
    }
    getListAddress();

    $(".mdb-select" ).change(function() {
        getListAddress($(this).val());
    });

    function renderListAddress(data) {
        $.each(data,function(index, value){
            var str = '<tr>\
                        <td>address_id</td>\
                        <td>user_id</td>\
                        <td>city</td>\
                        <td>postcode</td>\
                        <td>region</td>\
                        <td>street</td>\
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

    $('body').on('click', "nav .pagination a", function() {
        var id = $(".mdb-select" ).val();
        getListAddress(id, $(this).attr("value"));
    });

});
