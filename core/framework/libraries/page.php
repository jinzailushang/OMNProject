<?php

/**
 * 分页类
 * 
 *
 * @package    library
 *
 * */
defined('InOmniWL') or exit('Access Invalid!');

class Page {

    /**
     * url参数中页码参数名
     */
    private $page_name = 'curpage';

    /**
     * 信息总数
     */
    private $total_num = 1;

    /**
     * 页码链接
     */
    private $page_url = '';

    /**
     * 每页信息数量
     */
    private $each_num = 10;

    /**
     * 当前页码
     */
    private $now_page = 1;

    /**
     * 设置页码总数
     */
    private $total_page = 1;

    /**
     * 输出样式
     * 4、5为商城伪静态专用样式
     */
    private $style = 2;

    /**
     * ajax 分页 预留，目前先不使用
     * 0为不使用，1为使用，默认为0
     */
    private $ajax = 0;

    /**
     * 首页
     */
    private $pre_home = '';

    /**
     * 末页
     */
    private $pre_last = '';

    /**
     * 上一页
     */
    private $pre_page = '';

    /**
     * 下一页
     */
    private $next_page = '';

    /**
     * 页码 样式左边界符
     */
    private $left_html = '<li>';

    /**
     * 页码 样式右边界符
     */
    private $right_html = '</li>';

    /**
     * 选中样式左边界符
     */
    private $left_current_html = '<li>';

    /**
     * 选中样式右边界符
     */
    private $right_current_html = '</li>';

    /**
     * 省略号样式左边界符
     */
    private $left_ellipsis_html = '<li>';

    /**
     * 省略号样式右边界符
     */
    private $right_ellipsis_html = '</li>';

    /**
     * 在页码链接a内部的样式 <a>(样式名)页码(样式名)</a>
     */
    private $left_inside_a_html = '';

    /**
     * 在页码链接a内部的样式 <a>(样式名)页码(样式名)</a>
     */
    private $right_inside_a_html = '';

    /**
     * 构造函数
     * 
     *  数据库使用到的方法：
     * 	$this->setTotalNum($total_num);
     *  $this->getLimitStart();
     *  $this->getLimitEnd();
     * 
     * @param 
     * @return 
     */
    public function __construct() {
        Language::read('core_lang_index');
        $lang = Language::getLangContent();
        $this->pre_home = $lang['first_page'];
        $this->pre_last = $lang['last_page'];
        $this->pre_page = $lang['pre_page'];
        $this->next_page = $lang['next_page'];
        /**
         * 设置当前页码
         */
        $this->setNowPage($_GET[$this->page_name]);
        /**
         * 设置当前页面的页码url
         * 商城伪静态分页不需要使用这个方法
         */
        if (!in_array($this->style, array(4, 5))) {
            $this->setPageUrl();
        }
    }

    /**
     * 取得属性
     *
     * @param string $key 属性键值
     * @return string 字符串类型的返回结果
     */
    public function get($key) {
        return $this->$key;
    }

    /**
     * 设置属性
     *
     * @param string $key 属性键值
     * @param string $value 属性值
     * @return bool 布尔类型的返回结果
     */
    public function set($key, $value) {
        return $this->$key = $value;
    }

    /**
     * 设置url页码参数名
     *
     * @param string $page_name url中传递页码的参数名
     * @return bool 布尔类型的返沪结果
     */
    public function setPageName($page_name) {
        $this->page_name = $page_name;
        return true;
    }

    /**
     * 设置当前页码
     *
     * @param int $page 当前页数
     * @return bool 布尔类型的返回结果
     */
    public function setNowPage($page) {
        $this->now_page = intval($page) > 0 ? intval($page) : 1;
        return true;
    }

    /**
     * 设置每页数量
     *
     * @param int $num 每页显示的信息数
     * @return bool 布尔类型的返回结果
     */
    public function setEachNum($num) {
        $this->each_num = intval($num) > 0 ? intval($num) : 10;
        return true;
    }

    /**
     * 设置输出样式
     *
     * @param int $style 样式名
     * @return bool 布尔类型的返回结果
     */
    public function setStyle($style) {
        $this->style = ($style == 'admin' ? 2 : $style);
        return true;
    }

    /**
     * 设置信息总数
     *
     * @param int $total_num 信息总数
     * @return bool 布尔类型的返回结果
     */
    public function setTotalNum($total_num) {
        $this->total_num = $total_num;
        return true;
    }

    /**
     * 取当前页码
     *
     * @param 
     * @return int 整型类型的返回结果
     */
    public function getNowPage() {
        return $this->now_page;
    }

    /**
     * 取页码总数
     *
     * @param 
     * @return int 整型类型的返回结果
     */
    public function getTotalPage() {
        if ($this->total_page == 1) {
            $this->setTotalPage();
        }
        return $this->total_page;
    }

    /**
     * 取信息总数
     *
     * @param 
     * @return int 整型类型的返回结果
     */
    public function getTotalNum() {
        return $this->total_num;
    }

    /**
     * 取每页信息数量
     *
     * @param 
     * @return int 整型类型的返回结果
     */
    public function getEachNum() {
        return $this->each_num;
    }

    /**
     * 取数据库select开始值
     *
     * @param 
     * @return int 整型类型的返回结果
     */
    public function getLimitStart() {
        if ($this->getNowPage() == 1) {
            $tmp = 0;
        } else {
            $this->setTotalPage();
            $this->now_page = $this->now_page > $this->total_page ? $this->total_page : $this->now_page;
            $tmp = ($this->getNowPage() - 1) * $this->getEachNum();
        }
        return $tmp;
    }

    /**
     * 取数据库select结束值
     *
     * @param 
     * @return int 整型类型的返回结果
     */
    public function getLimitEnd() {
        $tmp = $this->getNowPage() * $this->getEachNum();
        if ($tmp > $this->getTotalNum()) {
            $tmp = $this->getTotalNum();
        }
        return $tmp;
    }

    /**
     * 设置页码总数
     *
     * @param int $id 记录ID
     * @return array $rs_row 返回数组形式的查询结果
     */
    public function setTotalPage() {
        $this->total_page = ceil($this->getTotalNum() / $this->getEachNum());
    }

    /**
     * 输出html
     *
     * @param style=9 表示js效果，func为js函数
     * @return string 字符串类型的返回结果
     */
    public function show($style = null, $func = '') {
        /**
         * 设置总数
         */
        $this->setTotalPage();
        if (!is_null($style)) {
            $this->style = $style;
        }
        $html_page = '';
        $this->left_current_html = '<li><span class="currentpage">';
        $this->right_current_html = '</span></li>';
        $this->left_inside_a_html = '<span>';
        $this->right_inside_a_html = '</span>';
        switch ($this->style) {
            case '1':
                $html_page .= '<ul>';
                if ($this->getNowPage() == 1) {
                    $html_page .= '<li>' . $this->left_inside_a_html . $this->pre_page . $this->right_inside_a_html . '</li>';
                } else {
                    $html_page .= '<li><a class="demo" href="' . $this->page_url . ($this->getNowPage() - 1) . '">' . $this->left_inside_a_html . $this->pre_page . $this->right_inside_a_html . '</a></li>';
                }
                if ($this->getNowPage() == $this->getTotalPage() || $this->getTotalPage() == 0) {
                    $html_page .= '<li>' . $this->left_inside_a_html . $this->next_page . $this->right_inside_a_html . '</li>';
                } else {
                    $html_page .= '<li><a class="demo" href="' . $this->page_url . ($this->getNowPage() + 1) . '">' . $this->left_inside_a_html . $this->next_page . $this->right_inside_a_html . '</a></li>';
                }
                $html_page .= '</ul>';
                break;
            case '2':
                $html_page .= '<ul>';
                if ($this->getNowPage() == 1) {
                    $html_page .= '<li>' . $this->left_inside_a_html . $this->pre_home . $this->right_inside_a_html . '</li>';
                    $html_page .= '<li>' . $this->left_inside_a_html . $this->pre_page . $this->right_inside_a_html . '</li>';
                } else {
                    $html_page .= '<li><a class="demo" href="' . $this->page_url . '1">' . $this->left_inside_a_html . $this->pre_home . $this->right_inside_a_html . '</a></li>';
                    $html_page .= '<li><a class="demo" href="' . $this->page_url . ($this->getNowPage() - 1) . '">' . $this->left_inside_a_html . $this->pre_page . $this->right_inside_a_html . '</a></li>';
                }
                $html_page .= $this->getNowBar();
                if ($this->getNowPage() == $this->getTotalPage() || $this->getTotalPage() == 0) {
                    $html_page .= '<li>' . $this->left_inside_a_html . $this->next_page . $this->right_inside_a_html . '</li>';
                    $html_page .= '<li>' . $this->left_inside_a_html . $this->pre_last . $this->right_inside_a_html . '</li>';
                } else {
                    $html_page .= '<li><a class="demo" href="' . $this->page_url . ($this->getNowPage() + 1) . '">' . $this->left_inside_a_html . $this->next_page . $this->right_inside_a_html . '</a></li>';
                    $html_page .= '<li><a class="demo" href="' . $this->page_url . $this->getTotalPage() . '">' . $this->left_inside_a_html . $this->pre_last . $this->right_inside_a_html . '</a></li>';
                }
                $html_page .= '</ul>';
                break;
            case '3':
                $html_page .= '<ul>';
                if ($this->getNowPage() == 1) {
                    $html_page .= '<li>' . $this->left_inside_a_html . $this->pre_page . $this->right_inside_a_html . '</li>';
                } else {
                    $html_page .= '<li><a class="demo" href="' . $this->page_url . ($this->getNowPage() - 1) . '">' . $this->left_inside_a_html . $this->pre_page . $this->right_inside_a_html . '</a></li>';
                }
                $html_page .= $this->getNowBar();
                if ($this->getNowPage() == $this->getTotalPage() || $this->getTotalPage() == 0) {
                    $html_page .= '<li>' . $this->left_inside_a_html . $this->next_page . $this->right_inside_a_html . '</li>';
                    $html_page .= '<li>' . $this->left_inside_a_html . $this->pre_last . $this->right_inside_a_html . '</li>';
                } else {
                    $html_page .= '<li><a class="demo" href="' . $this->page_url . ($this->getNowPage() + 1) . '">' . $this->left_inside_a_html . $this->next_page . $this->right_inside_a_html . '</a></li>';
                }
                $html_page .= '</ul>';
                break;
            case '4':
                $html_page .= '<ul>';
                if ($this->getNowPage() == 1) {
                    $html_page .= '<li>' . $this->left_inside_a_html . $this->pre_page . $this->right_inside_a_html . '</li>';
                } else {
                    $html_page .= '<li><a class="demo" href="' . $this->setShopPseudoStaticPageUrl($this->getNowPage() - 1) . '">' . $this->left_inside_a_html . $this->pre_page . $this->right_inside_a_html . '</a></li>';
                }
                if ($this->getNowPage() == $this->getTotalPage() || $this->getTotalPage() == 0) {
                    $html_page .= '<li>' . $this->left_inside_a_html . $this->next_page . $this->right_inside_a_html . '</li>';
                } else {
                    $html_page .= '<li><a class="demo" href="' . $this->setShopPseudoStaticPageUrl($this->getNowPage() + 1) . '">' . $this->left_inside_a_html . $this->next_page . $this->right_inside_a_html . '</a></li>';
                }
                $html_page .= '</ul>';
                break;
            case '5':
                $html_page .= '<ul>';
                if ($this->getNowPage() == 1) {
                    $html_page .= '<li>' . $this->left_inside_a_html . $this->pre_home . $this->right_inside_a_html . '</li>';
                    $html_page .= '<li>' . $this->left_inside_a_html . $this->pre_page . $this->right_inside_a_html . '</li>';
                } else {
                    $html_page .= '<li><a class="demo" href="' . $this->setShopPseudoStaticPageUrl('1') . '">' . $this->left_inside_a_html . $this->pre_home . $this->right_inside_a_html . '</a></li>';
                    $html_page .= '<li><a class="demo" href="' . $this->setShopPseudoStaticPageUrl($this->getNowPage() - 1) . '">' . $this->left_inside_a_html . $this->pre_page . $this->right_inside_a_html . '</a></li>';
                }
                $html_page .= $this->getNowBar();
                if ($this->getNowPage() == $this->getTotalPage() || $this->getTotalPage() == 0) {
                    $html_page .= '<li>' . $this->left_inside_a_html . $this->next_page . $this->right_inside_a_html . '</li>';
                    $html_page .= '<li>' . $this->left_inside_a_html . $this->pre_last . $this->right_inside_a_html . '</li>';
                } else {
                    $html_page .= '<li><a class="demo" href="' . $this->setShopPseudoStaticPageUrl($this->getNowPage() + 1) . '">' . $this->left_inside_a_html . $this->next_page . $this->right_inside_a_html . '</a></li>';
                    $html_page .= '<li><a class="demo" href="' . $this->setShopPseudoStaticPageUrl($this->getTotalPage()) . '">' . $this->left_inside_a_html . $this->pre_last . $this->right_inside_a_html . '</a></li>';
                }
                $html_page .= '</ul>';
                break;
            case '9':
                $html_page .= '<ul class="ajax_page">';
                if ($this->getNowPage() == 1) {
                    $html_page .= '<li>' . $this->left_inside_a_html . $this->pre_home . $this->right_inside_a_html . '</li>';
                    $html_page .= '<li>' . $this->left_inside_a_html . $this->pre_page . $this->right_inside_a_html . '</li>';
                } else {
                    $html_page .= '<li><a class="demo" href="javascript:;" onclick="' . $func . '(1)">' . $this->left_inside_a_html . $this->pre_home . $this->right_inside_a_html . '</a></li>';
                    $next_page = $this->getNowPage() - 1;
                    $html_page .= '<li><a class="demo" href="javascript:;" onclick="' . $func . '(' . $next_page . ')">' . $this->left_inside_a_html . $this->pre_page . $this->right_inside_a_html . '</a></li>';
                }
                $html_page .= $this->getNowBar($func);
                if ($this->getNowPage() == $this->getTotalPage() || $this->getTotalPage() == 0) {
                    $html_page .= '<li>' . $this->left_inside_a_html . $this->next_page . $this->right_inside_a_html . '</li>';
                    $html_page .= '<li>' . $this->left_inside_a_html . $this->pre_last . $this->right_inside_a_html . '</li>';
                } else {
                    $next_page = $this->getNowPage() + 1;
                    $html_page .= '<li><a class="demo" href="javascript:;" onclick="' . $func . '(' . $next_page . ')">' . $this->left_inside_a_html . $this->next_page . $this->right_inside_a_html . '</a></li>';
                    $html_page .= '<li><a class="demo" href="javascript:;" onclick="' . $func . '(' . $this->getTotalPage() . ')">' . $this->left_inside_a_html . $this->pre_last . $this->right_inside_a_html . '</a></li>';
                }
                $html_page .= '</ul>';
                break;
            case '10':
                $this->left_current_html = '<span class="current">';
                $this->right_current_html = '</span>';
                $this->left_inside_a_html = '';
                $this->right_inside_a_html = '';
                $html_page .= '<div class="bigPage">';
                $html_page .= '<span id="pageNav">总共:'.$this->getTotalPage().'&nbsp;页';
                if ($this->getNowPage() == 1) {
                    $html_page .= '' . $this->left_inside_a_html . $this->pre_home . $this->right_inside_a_html . '';
                    $html_page .= '' . $this->left_inside_a_html . $this->pre_page . $this->right_inside_a_html . '';
                } else {
//                    $html_page .= '<a class="Prev" href="' . $this->page_url . '1">' . $this->left_inside_a_html . $this->pre_home . $this->right_inside_a_html . '</a>';
                    $html_page .= '<a class="Prev" href="' . $this->page_url . ($this->getNowPage() - 1) . '">' . $this->left_inside_a_html . $this->pre_page . $this->right_inside_a_html . '</a>';
                }
                $html_page .= $this->getNowBar();
                if ($this->getNowPage() == $this->getTotalPage() || $this->getTotalPage() == 0) {
                    $html_page .= '' . $this->left_inside_a_html . $this->next_page . $this->right_inside_a_html . '';
                    $html_page .= '' . $this->left_inside_a_html . $this->pre_last . $this->right_inside_a_html . '';
                } else {
                    $html_page .= '<a class="Next" href="' . $this->page_url . ($this->getNowPage() + 1) . '">' . $this->left_inside_a_html . $this->next_page . $this->right_inside_a_html . '</a>';
//                    $html_page .= '<a class="Next" href="' . $this->page_url . $this->getTotalPage() . '">' . $this->left_inside_a_html . $this->pre_last . $this->right_inside_a_html . '</a>';
                }

                $html_page .= '</span>';
                $html_page .= '<span class="pl30">跳转到:</span>';
                $html_page .= '<input name="page" data-url="' . $this->page_url .'" max-page="'.$this->getTotalPage().'" class="j_FSInput w30" type="text" onkeydown="return jumpPage(this, event);" value="" />';
                $html_page .= '<a class="pageBtn" data-url="' . $this->page_url .'" max-page="'.$this->getTotalPage().'" href="javascript:void(0);" onclick="go(this); return false;">GO</a>';
                $html_page .= '</div>';
                break;
            case '11':
                $this->left_current_html = '';
                $this->right_current_html = '';
                $this->left_inside_a_html = '';
                $this->right_inside_a_html = '';
                $html_page .= '<div id="bottom_pager" class="search-page">';
                if ($this->getNowPage() == 1) {
                    $html_page .= '' . $this->left_inside_a_html . $this->pre_home . $this->right_inside_a_html . '';
                    $html_page .= '' . $this->left_inside_a_html . $this->pre_page . $this->right_inside_a_html . '';
                } else {

                    $html_page .= '<a id="prePage" class="page-prev" href="' . $this->page_url . ($this->getNowPage() - 1) . '"><b></b></a>';
                }
                $html_page .= $this->getNowBar();
                if ($this->getNowPage() == $this->getTotalPage() || $this->getTotalPage() == 0) {
                    $html_page .= '' . $this->left_inside_a_html . $this->next_page . $this->right_inside_a_html . '';
                    $html_page .= '' . $this->left_inside_a_html . $this->pre_last . $this->right_inside_a_html . '';
                } else {
                    $html_page .= '<a id="nextPage" class="page-next" href="' . $this->page_url . ($this->getNowPage() + 1) . '"><b></b></a>';
                }

                $html_page .= '</div>';
                
                break;
            default:
                break;
        }
        /**
         * 转码
         */
        /**
          if (strtoupper(CHARSET) == 'GBK' && !empty($html_page)){
          $html_page = iconv('UTF-8','GBK',$html_page);
          }
         */

        return $html_page;
    }

    /**
     * 页码条内容
     * 样式为： 前面2个页码 ... 中间7个页码 ...
     *
     * @param func不为空为js效果
     * @return string 字符串类型的返回结果
     */
    private function getNowBar($func = '') {
        /**
         * 显示效果
         * 中间显示7个，左右两个，不足则不显示省略号
         */
        /**
         * 判断当前页是否大于7
         */
        if ($this->getNowPage() >= 7) {
            /**
             * 前面增加省略号，并且计算开始页码
             */
            $begin = $this->getNowPage() - 2;
        } else {
            /**
             * 小于7，前面没有省略号
             */
            $begin = 1;
        }
        /**
         * 计算结束页码
         */
        if ($this->getNowPage() + 5 < $this->getTotalPage()) {
            /**
             * 增加省略号
             */
            $end = $this->getNowPage() + 5;
        } else {
            $end = $this->getTotalPage();
        }
        if(in_array($this->style, array(11))){
            $this->left_ellipsis_html = '';
            $this->right_ellipsis_html = '';
        }
        /**
         * 整理整个页码样式
         */
        $result = '';
        if (empty($func)) {
            if ($begin > 1) {
                $result .= $this->setPageHtml(1, 1) . $this->setPageHtml(2, 2);
                if (!in_array($this->style, array(10))) {
                    $result .= $this->left_ellipsis_html . '<span>...</span>' . $this->right_ellipsis_html;
                }
            }
            /**
             * 中间部分内容
             */
            for ($i = $begin; $i <= $end; $i++) {
                $result .= $this->setPageHtml($i, $i);
            }
            if ($end < $this->getTotalPage() && !in_array($this->style, array(10))) {
                $result .= $this->left_ellipsis_html . '<span>...</span>' . $this->right_ellipsis_html;
            }
        } else {
            if ($begin > 1) {
                $result .= $this->setPageHtmlJs(1, 1, $func) . $this->setPageHtmlJs(2, 2, $func);
                if (!in_array($this->style, array(10))) {
                    $result .= $this->left_ellipsis_html . '<span>...</span>' . $this->right_ellipsis_html;
                }
            }
            /**
             * 中间部分内容
             */
            for ($i = $begin; $i <= $end; $i++) {
                $result .= $this->setPageHtmlJs($i, $i, $func);
            }
            if ($end < $this->getTotalPage() && !in_array($this->style, array(10))) {
                $result .= $this->left_ellipsis_html . '<span>...</span>' . $this->right_ellipsis_html;
            }
        }
        return $result;
    }

    /**
     * 设置单个页码周围html代码
     *
     * @param string $page_name 页码显示内容
     * @param string $page 页码数
     * @return string 字符串类型的返回结果
     */
    private function setPageHtml($page_name, $page) {
        /**
         * 判断是否是当前页
         */
        if ($this->getNowPage() == $page) {
            if (in_array($this->style, array(11))) {
                $result = '<a class="cur" href="">' . $page . '</a>';
            } else {
                $result = $this->left_current_html . $page . $this->right_current_html;
            }

        } else {
            if (in_array($this->style, array(10))) {     // 商城伪静态使用
                $result = "<a href='" . $this->page_url . $page . "'>" . $this->left_inside_a_html . $page_name . $this->right_inside_a_html . "</a>";
            } else if (in_array($this->style, array(11))) {     // 商城伪静态使用
                $result = "<a href='" . $this->page_url . $page . "'>" . $this->left_inside_a_html . $page_name . $this->right_inside_a_html . "</a>";
            } else if (in_array($this->style, array(4, 5))) {     // 商城伪静态使用
                $result = $this->left_html . "<a class='demo' href='" . $this->setShopPseudoStaticPageUrl($page) . "'>" . $this->left_inside_a_html . $page_name . $this->right_inside_a_html . "</a>" . $this->right_html;
            } else {                                      // 普通分页使用
                $result = $this->left_html . "<a class='demo' href='" . $this->page_url . $page . "'>" . $this->left_inside_a_html . $page_name . $this->right_inside_a_html . "</a>" . $this->right_html;
            }
        }
        return $result;
    }

    /**
     * 设置单个页码周围html代码--js效果
     *
     * @param string $page_name 页码显示内容
     * @param string $page 页码数
     * @return string 字符串类型的返回结果
     */
    private function setPageHtmlJs($page_name, $page, $func) {
        /**
         * 判断是否是当前页
         */
        if ($this->getNowPage() == $page) {
            if (in_array($this->style, array(11))) {
                $result = '<a class="cur" href="">' . $page . '</a>';
            } else {
                $result = $this->left_current_html . $page . $this->right_current_html;
            }
            
        } else {
            if (in_array($this->style, array(4, 5))) {     // 商城伪静态使用
                $result = $this->left_html . "<a class='demo' href='javascript:;' onclick='" . $func . "(" . $page . ")'>" . $this->left_inside_a_html . $page_name . $this->right_inside_a_html . "</a>" . $this->right_html;
            } else {                                      // 普通分页使用
                $result = $this->left_html . "<a class='demo' href='javascript:;' onclick='" . $func . "(" . $page . ")'>" . $this->left_inside_a_html . $page_name . $this->right_inside_a_html . "</a>" . $this->right_html;
            }
        }
        return $result;
    }

    /**
     * 取url地址
     *
     * @param 
     * @return string 字符串类型的返回结果
     */
    private function setPageUrl() {
        $uri = request_uri();
        $_SERVER['REQUEST_URI'] = $uri;

        /**
         * 不存在QUERY_STRING时
         */
        if (empty($_SERVER['QUERY_STRING'])) {
            $this->page_url = $_SERVER['REQUEST_URI'] . "?" . $this->page_name . "=";
        } else {
            if (stristr($_SERVER['QUERY_STRING'], $this->page_name . '=')) {
                /**
                 * 地址存在页面参数
                 */
                $this->page_url = str_replace($this->page_name . '=' . $this->now_page, '', $_SERVER['REQUEST_URI']);
                $last = $this->page_url[strlen($this->page_url) - 1];
                if ($last == '?' || $last == '&') {
                    $this->page_url .= $this->page_name . "=";
                } else {
                    $this->page_url .= '&' . $this->page_name . "=";
                }
            } else {
                $this->page_url = $_SERVER['REQUEST_URI'] . '&' . $this->page_name . '=';
            }
        }
        return true;
    }

    /**
     * 取url地址
     *
     * @param int $page
     * @return string 字符串类型的返回结果
     */
    private function setShopPseudoStaticPageUrl($page) {
        $param = $_GET;
        $act = $param['act'] == '' ? 'index' : $param['act'];
        unset($param['act']);
        $op = $param['op'] == '' ? 'index' : $param['op'];
        unset($param['op']);
        $param[$this->page_name] = $page;
        return urlShop($act, $op, $param);
    }

}
