/**
 * Created by MyPC on 2016/6/20.
 */
// 选择商品弹窗
$("#button-sel").on("click",function () {
    layer.open({
        type: 1,
        //skin: 'layui-layer-molv',
        title: '选择商品',
        fix: false,
        maxmin: false,
        //shift: 4, //动画
        area: ['700px', '420px'],
        shadeClose: true, //点击遮罩关闭
        content: $(".select-produce").html()
    });
    
// 新增商品框
        $(".add-nbutton").on("click", function () {
            $(".dd-spacer").show();
            $(".item-add").show();
            $(".item-add-con , .item-add-cancel").on("click",function () {
                $(".item-add").hide();
                $(".dd-spacer").hide();
            })
            }
        )
    });




