/*
 * 单位代码JS文件
 * create date: 2016/05/06
 * author coolzbw
 */

var measureJs = {
    root: '',
    m: 'measure',
    add: {
        a: 'add',
        div: null,
        init: function () {
            this.div = '#' + measureJs.m + '_' + this.a + '_div';
        },
        before: function () {
            var company_addr = $(customerJs.edit.div).find('input[name="company_addr"]').val();
            var firstname = $(customerJs.edit.div).find('input[name="firstname"]').val();
            if (company_addr.length < 1) {
                alertMsg.error("请输入公司地址 ！");
                return false;
            } else if (firstname.length < 1) {
                alertMsg.error("请输入姓 ！");
                return false;
            } 
            return true;
        },
        submit: function () {
            var $a = $(customerJs.changePassword.div);
            var old_psd = $a.find('#oldPassword').val();
            var new_psd = $a.find('#newPassword').val();
            var con_psd = $a.find('#confirmPassword').val();
            var url = root + '/customer/updatePassword';
            $.post(url, {'old_psd': old_psd, 'password': new_psd}, function (json) {
                if (json.status == 1) {
                    alertMsg.correct(json.msg);
                    navTab.closeTab('changePassword');

                    window.location = "/index/login.html";
                } else {
                    alertMsg.error(json.msg);
                }
            }, 'json');
        },
    },
    edit: {
        a: 'edit',
        div: null,
        init: function () {
            this.div = '#' + measureJs.m + '_' + this.a + '_div';

        },
        before: function () {
            var company_addr = $(customerJs.edit.div).find('input[name="company_addr"]').val();
            var firstname = $(customerJs.edit.div).find('input[name="firstname"]').val();
            if (company_addr.length < 1) {
                alertMsg.error("请输入公司地址 ！");
                return false;
            } else if (firstname.length < 1) {
                alertMsg.error("请输入姓 ！");
                return false;
            } 
            return true;
        },
        submit: function (data) {
            if (0 == data.status) {
                alertMsg.error(data.msg);
                return;
            } else {
                alertMsg.correct("修改成功！");
                return;
            }
        },
    },
    index: {
        a: "index",
        div: null,
        init: function () {
            this.div = '#' + measureJs.m + '_' + this.a + '_div';

        },
        //绑定页签
        bindTabs: function () {
            $(this.div).find(".navTwo_menu a").bind('click', function () {
                customerJs.index.changeTabs(this.id, false)
            })
        },
        //切换页签
        changeTabs: function (id, is_back) {
            $(this.div).find('#' + id).parent().parent().find('li a').removeAttr('class');
            $(this.div).find('#' + id).addClass('choose');

            //执行查询 
            this.Query(false, is_back);
        },
        //获取各状态 的数量
        showTabsCount: function () {
            $.ajax({
                type: 'post',
                dataType: 'json',
                url: customerJs.root + '/customer/customerTabsCount',
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    alertMsg.error(errorThrown);
                },
                success: function (json) {
                    if (!json || json.length <= 0)
                        return;
                    var count_normal = json.count_normal != undefined ? '[' + json.count_normal + ']' : '';
                    var count_freeze = json.count_freeze != undefined ? '[' + json.count_freeze + ']' : '';
                    var count_del = json.count_del != undefined ? '[' + json.count_del + ']' : '';
                    var count_reg = json.count_reg != undefined ? '[' + json.count_reg + ']' : '';
                    //显示计数
                    $('#customer_tabs_normal').find('font').html(count_normal);
                    $('#customer_tabs_freeze').find('font').html(count_freeze);
                    $('#customer_tabs_del').find('font').html(count_del);
                    $('#customer_tabs_reg').find('font').html(count_reg);
                }
            })
        },
        //获取查询 条件
        getCondition: function (page) {
            var condition = $(this.div).find("#customerform").serialize();
            var status = $('div#customer_nav').find('ul li a.choose').attr('id');
            condition += '&status=' + status;
            $(this.div).find('#more_query').find('input[type!="button"],select').each(function (i) {
                condition += '&' + $(this).attr('name') + '=' + $(this).val();
            });
            //分页
            condition += page && page > 0 ? '&p=' + page : '';
            $.cookie(customerJs.index.ma + '_condition', condition);
            this.setCookie();
            return condition;
        },
        //异步查询
        Query: function (isCount, is_back) {
            //获取查询 条件
            var condition = is_back ? $.cookie(customerJs.index.ma + '_ondition') : customerJs.index.getCondition(false);
            //页面显示
            customerJs.index.ShowPage(condition, isCount);
        },
        //分页返回 数据
        pageAjaxData: function (page) {
            var condition = customerJs.index.getCondition(page);
            customerJs.index.ShowPage(condition, false);
        },
        //页面显示
        ShowPage: function (condition, isCount) {
            $.ajax({
                type: 'post',
                data: condition,
                dataType: 'json',
                url: customerJs.root + '/customer/query',
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    alertMsg.error(errorThrown);
                },
                success: function (json) {
                    //清除数据
                    $(customerJs.index.div).find('#customerListSpace').find('table:gt(0)').remove();
                    if (!json)
                        return;

                    var data = json.data ? json.data : '';
                    //生成html
                    var html = customerJs.index.initData(data, '', '');

                    //加载html
                    $(html).appendTo($(customerJs.index.div).find('#customerListSpace'));

                    //显示分页
                    var pages = json.pages ? json.pages : '';

                    $('#customerPageSpace').html(pages);
                    //重新计数
                    if (isCount)
                        customerJs.index.showTabsCount();
                    var status = $('div#customer_nav').find('ul li a.choose').attr('id');
                    if (status == "customer_tabs_del") {
                        $(customerJs.index.div).find(".view_enabled").hide();
                    } else {
                        $(customerJs.index.div).find(".view_enabled").show();
                    }
                }
            })
        },
        //全选
        selectAll: function (checkbox, type) {
            if (checkbox.checked) {
                if (type == 0) {
                    $(customerJs.index.div).find('input[type=checkbox]').not('input[name="select_total"]').attr('checked', true);
                } else {
                    $(customerJs.index.div).find('input[type=checkbox]').attr('checked', true);
                }
            } else {
                $('input[type=checkbox]').removeAttr('checked');
            }
        },
        //获取选中 
        getSelect: function () {
            var pid = '';
            var syn = '';
            $(customerJs.index.div).find('#cid:checked').each(function (i) {
                pid += syn + $(this).val();
                syn = ',';
            });
            return pid;
        },
        //缓存供应商类目
        CacheCustomerCat: function () {
            var cid = customerJs.index.getSelect();
            if (cid == '') {
                alertMsg.error('请选择有效的数据 !');
                return;
            }
            if (!confirm('确定缓存，时间有点长哦 ?')) {
                return;
            }
            $.ajax({
                type: 'post',
                data: 'cid=' + cid,
                dataType: 'json',
                url: customerJs.root + "/customer/CacheCustomerCat",
                error: function (a, b, c) {
                    alertMsg.error(c);
                },
                success: function (json) {
                    if (json.status) {
                        alertMsg.correct(json.msg);
                    } else {
                        alertMsg.error(json.msg);
                    }
                }
            });

        },
        //生成分页html
        initData: function (json, status, id) {
            if (json.length <= 0)
                return '';
            var rurl = customerJs.root;
            var purl = customerJs.root + '/public/';

            var tr = '';

            for (var i = 0; i < json.length; i++) {
                var cid = json[i].cid != undefined ? json[i].cid : 0;
                var company = json[i].company != undefined ? json[i].company : '';
                company = company.length > 40 ? company.substring(0, 40) + '...' : company;
                var customer_code = json[i].customer_code != undefined ? json[i].customer_code : '';
                var grade = json[i].grade != undefined ? json[i].grade : 0;
                var all_goods_num = json[i].all_goods_num != undefined ? json[i].all_goods_num : 0;
                var by_goods_num = json[i].by_goods_num != undefined ? json[i].by_goods_num : 0;
                var all_asn_num = json[i].all_asn_num != undefined ? json[i].all_asn_num : 0;
                var logtime = json[i].logtime != undefined ? json[i].logtime : 0;
                var status = json[i].status != undefined ? json[i].status : 0;
                var enabled = json[i].enabled != undefined ? json[i].enabled : 0;

                var td = '';
                var c = i % 2 == 0 ? 'J_ItemHead' : '';
                td += '<table width="100%" border="0" cellspacing="0" cellpadding="0" class="order-table">';
                td += '<tbody class="' + c + '">';
                td += '    <tr class="">';
                td += '		   <td width="5" align="center" scope="row"><input id="cid" class="selector" type="checkbox" value="' + cid + '" name="cIdArr"></td>';
                td += '        <td width="100">' + customer_code + '</td>';
                td += '        <td width="200">' + company + '</td>';
                td += '        <td width="60">' + all_goods_num + '</td>';
                td += '        <td width="60">' + by_goods_num + '</td>';
                td += '        <td width="60">' + all_asn_num + '</td>';
                td += '        <td width="60">' + grade + '</td>';
                td += '        <td width="60">' + logtime + '</td>';
                td += '        <td width="60" class="view_enabled">' + (enabled == "1" ? "启用" : "停用") + '</td>';
                td += '<td scope="row" align="center" width="60">';
                td += '<div style="position:relative;">';
                td += '<a href="javascript:void(0);" id="operAting" onclick="customerJs.index.operAting(this, ' + cid + ')" >操作 </a>';
                td += '<div class="operList">';
                td += '<ul>';

                //1: 正常; 2: 冻结; 3:删除;
                td += '<li><a href="javascript:void(0);" onclick="customerJs.index.viewCustomer(\'' + cid + '\');">查看 </a></li>';
                if (status == '3') {
                    td += '<li><a href="javascript:void(0);" onclick="customerJs.index.resCustomer(\'' + cid + '\');">还原</a></li>';
                } else if (status == '1') {
                    //1启用、0停用
                    if (enabled == "1") {
                        td += '<li><a href="javascript:void(0);" onclick="customerJs.index.closeCustomer(\'' + cid + '\');">停用</a></li>';
                    } else {
                        td += '<li><a href="javascript:void(0);" onclick="customerJs.index.openCustomer(\'' + cid + '\');">启用</a></li>';
                    }
                    td += '<li><a href="javascript:void(0);" onclick="customerJs.index.freezeCustomer(\'' + cid + '\');">冻结</a></li>';
                    td += '<li><a href="javascript:;" onclick="navTab.openTab(\'admineditcustomer\', \'' + customerJs.root + '/customer/adminEditCustomer/id/' + cid + '\', {title:\'修改供应商资料\'})" >修改</a></li>';
                    td += '<li><a href="javascript:void(0);" onclick="customerJs.index.delCustomer(\'' + cid + '\');">删除 </a></li>';
                } else if (status == '2') {
                    td += '<li><a href="javascript:void(0);" onclick="customerJs.index.resCustomer(\'' + cid + '\');">解冻</a></li>';
                    td += '<li><a href="javascript:;" onclick="navTab.openTab(\'admineditcustomer\', \'' + customerJs.root + '/customer/adminEditCustomer/id/' + cid + '\', {title:\'修改供应商资料\'})" >修改</a></li>';
                    td += '<li><a href="javascript:void(0);" onclick="customerJs.index.delCustomer(\'' + cid + '\');">删除 </a></li>';
                } else if (status == '4') {
                    td += '<li><a href="javascript:;" onclick="navTab.openTab(\'admineditcustomer\', \'' + customerJs.root + '/customer/adminEditCustomer/id/' + cid + '\', {title:\'审核供应商\'})" >审核</a></li>';
                    td += '<li><a href="javascript:void(0);" onclick="customerJs.index.deltrueCustomer(\'' + cid + '\');">删除 </a></li>';

                }

                td += '</ul>';
                td += '</div>';
                td += '</div>';
                td += '</td>';

                td += '    </tr>';
                td += '</tbody>';
                td += '</table>';
                tr += td;
            }
            return tr;
        },
        //查看
        viewCustomer: function (cid) {
            $.pdialog.open(customerJs.root + '/customer/customerShow/id/' + cid, 'customershow', '查看', {resizable: true, mixable: false, mask: true, height: 400});
        },
        //启用
        openCustomer: function (cid) {
            if (!confirm('确定启用 ?'))
                return;
            this.setEnabled(cid, 1);
        },
        //停用
        closeCustomer: function (cid) {
            if (!confirm('确定停用 ?'))
                return;
            this.setEnabled(cid, 0);
        },
        //还原
        resCustomer: function (cid) {
            if (!confirm('确定还原 ?'))
                return;
            this.setStatus(cid, 1);
        },
        //冻结
        freezeCustomer: function (cid) {
            if (!confirm('确定冻结 ?'))
                return;
            this.setStatus(cid, 2);
        },
        //删除
        delCustomer: function (cid) {
            if (!confirm('确定删除 ?'))
                return;
            this.setStatus(cid, 3);
        },
        //彻底删除
        deltrueCustomer: function (cid) {
            if (!confirm('确定删除 ?'))
                return;
            this.setStatus(cid, 999);
        },
        //修改状态
        setStatus: function (cid, status) {
            if (cid == '')
                return false;
            $.ajax({
                type: 'post',
                data: 'id=' + cid + '&status=' + status,
                dataType: 'json',
                url: customerJs.root + "/customer/setStatusCustomer",
                error: function (a, b, c) {
                    alertMsg.error(c);
                },
                success: function (json) {
                    if (json.status) {
                        alertMsg.correct(json.msg);
                    } else {
                        alertMsg.error(json.msg);
                    }
                }
            });
            var $tabs = $(this.div).find(".navTwo_menu a[class=choose]");
            customerJs.index.changeTabs($tabs.attr("id"), false);
            customerJs.index.showTabsCount();
        },
        //启用、停用
        setEnabled: function (cid, enabled) {
            if (cid == '')
                return false;
            $.ajax({
                type: 'post',
                data: 'id=' + cid + '&enabled=' + enabled,
                dataType: 'json',
                url: customerJs.root + "/customer/setEnabledCustomer",
                error: function (a, b, c) {
                    alertMsg.error(c);
                },
                success: function (json) {
                    if (json.status) {
                        alertMsg.correct(json.msg);
                    } else {
                        alertMsg.error(json.msg);
                    }
                }
            });
            var $tabs = $(this.div).find(".navTwo_menu a[class=choose]");
            customerJs.index.changeTabs($tabs.attr("id"), false);
            customerJs.index.showTabsCount();
        },
        //明细操作
        operAting: function (obj, id) {
            $(obj).next().show();
            $(obj).next().hover(function () {
                $(this).show();
            }, function () {
                $(this).hide();
            })
            $(obj).parent().hover(function () {
            }, function () {
                $(obj).next().hide();
            })
            $(obj).next().css("width", "30px");
        },
        //设置cookie
        setCookie: function () {
            $(this.div).find('#customerformform').find('input[type!="button"],select').each(function (i) {
                $.cookie(customerJs.index.ma + $(this).attr('name'), $(this).val());
            });
            $(this.div).find('#more_query').find('input[type!="button"],select').each(function (i) {
                $.cookie(customerJs.index.ma + $(this).attr('name'), $(this).val());
            });
        },
        //根据coodie绑定页面元素
        getCookie: function () {
            $(this.div).find('input[type!="button"],select').each(function (i) {
                $(this).val($.cookie(customerJs.index.ma + $(this).attr('name')));
            });
            $(this.div).find('#more_query').find('input[type!="button"],select').each(function (i) {
                $(this).val($.cookie(customerJs.index.ma + $(this).attr('name')));
            });
        },
    },
}
