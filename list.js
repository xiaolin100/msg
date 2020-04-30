$("#get_list").on('click',function () {
    var start_date = $("#start").val();
    var end_date = $("#end").val();
    if(start_date == '' || end_date == ''){
        alert('请选择开始和结束日期');
        return;
    }
    $.get('product.php',{'action':'getList','orderDateStart':start_date,'orderDateEnd':end_date},function (data) {
        if(typeof data == 'string'){
            data = JSON.parse(data);
        }
        var str = '';
        if(data.productQuantity.length > 0){
            var l = data.productQuantity.length;
            var i = 0;
            for(i;i<l;i++){
                str += '<tr><td>'+data.productQuantity[i]['ProductName']+'</td><td>'+data.productQuantity[i]['Quantity']+'</td></tr>'
            }
        } else {
            str = '<tr><td colspan="2">暂无数据</td></tr>'
        }
        $("#list tbody").empty().append(str);
    });
});

