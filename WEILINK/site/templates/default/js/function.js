// 字符串清除两端空格
String.prototype.trim = function () {
    return this.replace(/(^\s*)|(\s*$)/g, "");
}
// 字符串清除前空格
String.prototype.ltrim = function () {
    return this.replace(/(^\s*)/g, "");
}
// 字符串清除后空格
String.prototype.rtrim = function () {
    return this.replace(/(\s*$)/g, "");
}
// 字符串转整型数
String.prototype.toInt = function () {
    var str = this.toString();
    var str_int = parseInt(str);
    if (isNaN(str_int)) {
        str_int = 0;
    }
    return str_int;
}
// 字符串替换所有目标字符
String.prototype.replaceAll = function (search_str, replace_str) {
    var str = this.toString();
    while (str.indexOf(search_str) > -1) {
        str = str.replace(search_str, replace_str);
    }
    return str;
}
// 替换字符串中换行符
String.prototype.replaceNewLines = function (replace_str) {
    var str = this.toString();
    var search_str = "\n";
    if (str.indexOf("\r") > -1) {
        search_str = "\r" + search_str;
    }
    return str.replaceAll(search_str, replace_str);
}
String.prototype.sqlFilter = function () {
    return this.replace(/([<>%'"?\\]+)*/g, "");
}

//匹配正整数
function ispositive(number){
    var re=/^[1-9]\d*$/;
    return re.test(number);
}
//匹配负整数
function isnegtive(number){
    var re=/^-[1-9]\d*$/;
    return re.test(number);
}
//匹配整数
function isinteger(number){
    var re=/^-?[1-9]\d*$/;
    return re.test(number);
}
//匹配非负整数（正整数 + 0）
function positiveorzero(number){
    var re=/^[1-9]\d*|0$/;
    return re.test(number);
}
//匹配非正整数（负整数 + 0）
function negtiveorzero(number){
    var re=/^-[1-9]\d*|0$/;
    return re.test(number);
}
//匹配正浮点数
function positivefloat(number){
    var re=/^[1-9]\d*\.\d*|0\.\d*[1-9]\d*$/;
    return re.test(number);
}
//匹配负浮点数
function negtivefloat(number){
    var re=/^-([1-9]\d*\.\d*|0\.\d*[1-9]\d*)$/;
    return re.test(number);
}
//匹配浮点数
function isfloat(number){
    var re=/^-?([1-9]\d*\.\d*|0\.\d*[1-9]\d*|0?\.0+|0)$/;
    return re.test(number);
}
//匹配非负浮点数（正浮点数 + 0）
function positivefloatorzero(number){
    var re=/^[1-9]\d*\.\d*|0\.\d*[1-9]\d*|0?\.0+|0$/;
    return re.test(number);
}
//匹配非正浮点数（负浮点数 + 0）
function negtivefloatorzero(number){
    var re=/^(-([1-9]\d*\.\d*|0\.\d*[1-9]\d*))|0?\.0+|0$/;
    return re.test(number);
}
//验证邮箱
function isEmail(str){
    var re=/^\w+([-+.]\w+)*@\w+([-.]\\w+)*\.\w+([-.]\w+)*$/;
    return re.test(str);
}
//验证网址
function isUrl(str){
    var re=/^http:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"\"])*$/;
    return re.test(str);
}
//验证身份证
function isIdCard(str){
    var re=/^\d{15}(\d{2}[A-Za-z0-9])?$/;
    return re.test(str);
}
//只能输入英文
function isAllEng(str){
    var re=/^[A-Za-z]+$/;
    return re.test(str);
}
//只能输入英文和数字和下划线
function engIntUl(str){
    var re=/^[A-Za-z0-9_]+$/;
    return re.test(str);
}

 
/*
 *
 检测对象是否是空对象(不包含任何可读属性)。
 *
 方法既检测对象本身的属性，也检测从原型继承的属性(因此没有使hasOwnProperty)。
 */
function objectIsEmpty(obj)
{
    for (var name in obj)
    {
        return false;
    }
    return true;
}
;

// 页面是否已存在css文件
function checkCSSIsExist(url) {
    var css = document.getElementsByTagName('link');
    // 遍历查询页面中是否已经存在想要加载的css文件
    for (var i = 0; i < css.length; i++) {
        if (css[i].href.indexOf(url) > -1) {
            return true;
        }
    }
    return false;
}

// 页面是否已存在js文件
function checkJSIsExist(url) {
    var scripts = document.getElementsByTagName('script');
    // 遍历查询页面中是否已经存在想要加载的js文件
    for (var i = 0; i < scripts.length; i++) {
        if (scripts[i].src.indexOf(url) > -1) {
            return true;
        }
    }
    return false;
}

// 动态加载js文件,css文件
function includeFile(file_name, type) {
    var new_element = '';
    if (type == 'js') {
        new_element = document.createElement("script");
        new_element.setAttribute("type", "text/javascript");
        new_element.setAttribute("src", file_name);
    }
    else if (type == 'css') {
        new_element = document.createElement("link");
        new_element.setAttribute("type", "text/css");
        new_element.setAttribute("rel", "stylesheet");
        new_element.setAttribute("href", file_name);
    }

    document.getElementsByTagName("head")[0].appendChild(new_element);
}

// 自动判断加载html头文件：css或javascript
function checkAndIncludeHead(url) {
// 针对ie8使用substr不支持负数问题，修改使用substring方法
//	if (url.substr(-3) ==  'css') {
    if (url.substring(url.length - 3) == 'css') {
        if (!checkCSSIsExist(url)) {
            includeFile(url, 'css');
        }
    }
//	else if(url.substr(-2) ==  'js') {
    else if (url.substring(url.length - 2) == 'js') {
        if (!checkJSIsExist(url)) {
            includeFile(url, 'js');
        }
    }
}

// 加载指定资源头文件
function includeHead(url) {
    if (typeof (url) == 'string') {
        checkAndIncludeHead(url);
    }
    else {
        for (i in url) {
            checkAndIncludeHead(url[i]);
        }
    }
}

// 保留小数点后两位
function decimal_places(num) {
    return Math.round((Math.floor(num * 1000) / 10)) / 100;
}
function bindTableAction() {
    $('.jt_select_div').mouseenter(function () {
        var option_obj = $(this).children('.jt_select_option_div');
        option_obj.toggle();
        var option_dom = option_obj.get(0);
        var select_dom = $(this).get(0);
        var select_top = select_dom.offsetTop;
        var select_height = select_dom.offsetHeight;
        var option_height = option_dom.offsetHeight;
        var body_height = document.documentElement.clientHeight;

        if (body_height < select_top + select_height + option_height) {
            option_obj.css('top', 0 - option_height);
        }
        else {
            option_obj.css('top', select_height - 1);
        }
    }).mouseleave(function () {
        $(this).children('.jt_select_option_div').hide();
    });
}
//分行斑纹效果
function initTable() {
    $('.order-table-box tr:even').addClass('s_oushuhang');//偶数行的背景色
    $('.order-table-box tr:odd').addClass('s_jishuhang');//偶数行的背景色
    $('.order-table-box tr').hover(
            function () {
                $(this).addClass('s_hoverhang')
            },
            function () {
                $(this).removeClass('s_hoverhang')
            }
    );
}
function getIdValue(id) {
    return $.trim($('#' + id).val());
}
//点击页码
function clickpage(page) {
    initData(page);
}
//回车执行搜索
function search(evt) {
    var evt = evt ? evt : (window.event ? window.event : null);//兼容IE和FF
    if (evt.keyCode == 13) {
        initData(1)
    }
}
function close(){
    qBox.Close();
}
//关闭层
function close2(){
    var index = parent.layer.getFrameIndex(window.name);
    parent.layer.close(index); 
}
//切换标签
function changeTabs(v, obj) {
    $('#tab_id').val(v);
    $(".pro-center-box").children().hide();
    $(".pro-center-box").find('[tab-group='+$(obj).attr("tab-group")+']').show();
    $('.navTwo_menu>li>a').removeClass('choose');
    $(obj).addClass('choose');
    //切换标签时默认显示首页数据
    initData(1);
}
//查询列表分类时调用
function set_cate(obj, m) {
    var url = SITE_SITE_URL + '/index.php?act=common&op=get_category', cid = $(obj).val();
    var level = parseInt(m) + 1;
    if (!cid) {
        $(obj).nextAll('select').remove();
        return false;
    }
    $.ajax({
        url: url,
        data: {cid: cid},
        type: 'get',
        dataType: 'json',
        success: function (res) {
            if (res.status == 1) {
                $(obj).nextAll('select').remove();
                var r = '';
                var html = '<select name="" onchange="set_cate(this,' + level + ')" class="cate_fl search_input"><option value="">请选择</option>';
                for (var i in res.data) {
                    r = res.data[i];
                    html += '<option value="' + r.gc_id + '">' + r.gc_name + '</option>';
                }
                html += '</select>';
                $(obj).after(html);
            } else {
                $(obj).nextAll('select').remove();
            }
        }
    });
}

function date(unixTime, withTime) {
   var dt = unixTime>0 && new Date(unixTime*1000) || new Date(),
     y = dt.getFullYear(),
     m = dt.getMonth()+ 1,
     d = dt.getDate(),
     $return = '';
  m = m < 10 && '0'+m || m;
  d = d < 10 && '0'+d || d;

  $return = y+'-'+m+'-'+d;

  if (withTime) {
    var h = dt.getHours(), mm = dt.getMinutes();
    h = h < 10 && '0'+h || h;
    mm = mm < 10 && '0'+mm || mm;
    $return += ' '+h+':'+mm;
  }

  return $return;
}