<?php /* Smarty version Smarty-3.1.14, created on 2014-12-08 15:11:07
         compiled from ".\admin\Tpl\top.inc.htm" */ ?>
<?php /*%%SmartyHeaderCode:44954854f0bb8b374-29071848%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a43f14fea433618bc55dce2ec4cbceb1452a00bb' => 
    array (
      0 => '.\\admin\\Tpl\\top.inc.htm',
      1 => 1389245608,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '44954854f0bb8b374-29071848',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_54854f0bb9d036_63317731',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_54854f0bb9d036_63317731')) {function content_54854f0bb9d036_63317731($_smarty_tpl) {?>		<div id="colors-switcher" class="color">
			<a class="blue" title="Blue"></a>
			<a class="green" title="Green"></a>
			<a class="brown" title="Brown"></a>
			<a class="purple" title="Purple"></a>
			<a class="red" title="Red"></a>
			<a class="greyblue" title="GreyBlue"></a>
		</div>
		<!-- header -->
		<div id="header">
			<!-- logo -->
			<div id="logo">
				<h1><a href="/iokadmin.php" title="我行网后台管理系统"><img src="/public/image/logo.png" alt="我行网后台管理系统" /></a></h1>
			</div>
			<!-- end logo -->
			<!-- user -->
			<ul id="user">
				<li class="first"><a href="/iokadmin.php?m=Profile">我的账号（<?php echo $_SESSION['user']['account'];?>
）</a></li>
				<li><a href="/iokadmin.php?m=Profile&a=passwd">修改密码</a></li>
				<li class="highlight last"><a href="/iokadmin.php?m=Misc&a=logout">退出系统</a></li>
			</ul>
			<!-- end user -->
	
			<div id="header-inner">
				<div id="home">
					<a href="/iokadmin.php" title="Home"></a>
				</div>
				<!-- quick -->
				<ul id="quick">
				<!-- 
					<li>
						<a href="#" title="Products"><span class="icon"><img src="/public/image/agency/icons/application_double.png" alt="Products" /></span><span>Products</span></a>
						<ul>
							<li><a href="#">Manage Products</a></li>
							<li><a href="#">Add Product</a></li>
							<li>
								<a href="#" class="childs">Sales</a>
								<ul>
									<li><a href="">Today</a></li>
									<li class="last"><a href="">Yesterday</a></li>
								</ul>
							</li>
							<li class="last">
								<a href="#" class="childs">Offers</a>
								<ul>
									<li><a href="">Coupon Codes</a></li>
									<li class="last"><a href="">Rebates</a></li>
								</ul>
							</li>
						</ul>
					</li>
					-->
					<li>
						<a href="#" title="渠道招商管理"><span>渠道招商管理</span></a>
						<ul>
							<li><a href="/iokadmin.php?m=MarketImage">焦点图管理</a></li>
							<li><a href="/iokadmin.php?m=MarketNews">招商动态</a></li>
							<li><a href="/iokadmin.php?m=MarketWealth">财富中国行</a></li>
							<li><a href="/iokadmin.php?m=MarketOperation">渠道运营</a></li>
							<li><a href="/iokadmin.php?m=MarketBeeplan">蜜蜂计划</a></li>
							<li><a href="/iokadmin.php?m=MarketCase">招商案例</a></li>
							<li class="last"><a href="/iokadmin.php?m=MarketArticle">招商咨询</a></li>
						</ul>
					</li>
					<li>
						<a href="#" title="商学院管理"><span>商学院管理</span></a>
						<ul>
							<li><a href="/iokadmin.php?m=SchoolImage">焦点图管理</a></li>
							<li><a href="/iokadmin.php?m=SchoolLecturer">讲师管理</a></li>
							<li><a href="/iokadmin.php?m=SchoolVideo">课程管理</a></li>
							<li><a href="/iokadmin.php?m=SchoolNews">商学院动态</a></li>
							<li><a href="/iokadmin.php?m=SchoolAbout">关于我们</a></li>
							<li class="last"><a href="/iokadmin.php?m=SchoolLive">现场直播</a></li>
						</ul>
					</li>
					<li>
						<a href="#" title="财务管理"><span>财务管理</span></a>
						<ul>
							<li><a href="/iokadmin.php?m=Drawcash">提现审核</a></li>
							<li><a href="/iokadmin.php?m=Ordercheck">交易审核</a></li>
							<li><a href="/iokadmin.php?m=FinanceRecord">交易流水</a></li>
							<li class="last"><a href="/iokadmin.php?m=Refund">退款审核</a></li>
						</ul>
					</li>
					<li>
						<a href="#" title="广告管理"><span>广告管理</span></a>
						<ul>
							<li><a href="/iokadmin.php?m=Adplace">广告位管理</a></li>
							<li><a href="/iokadmin.php?m=Ad">广告管理</a></li>
							<li class="last"><a href="/iokadmin.php?m=Schedule">广告排期</a></li>
						</ul>
					</li>
					<li>
						<a href="#" title="资讯管理"><span>资讯管理</span></a>
						<ul>
							<li><a href="/iokadmin.php?m=MediaImage">焦点图管理</a></li>
							<li><a href="/iokadmin.php?m=Article">文章管理</a></li>
							<li><a href="/iokadmin.php?m=Video">视频管理</a></li>
							<li><a href="/iokadmin.php?m=Report">新闻报道</a></li>
							<li><a href="/iokadmin.php?m=Subject">专题管理</a></li>
							<li><a href="/iokadmin.php?m=Aboutus">关于我们</a></li>
							<li class="last"><a href="/iokadmin.php?m=Help">帮助管理</a></li>
						</ul>
					</li>
					<li>
						<a href="#" title="产品管理"><span>产品管理</span></a>
						<ul>
							<li><a href="/iokadmin.php?m=Brand">品牌管理</a></li>
							<li><a href="/iokadmin.php?m=Product">产品管理</a></li>
							<li><a href="/iokadmin.php?m=Buy">求购管理</a></li>
							<li class="last"><a href="/iokadmin.php?m=Order">订单管理</a></li>
						</ul>
					</li>
					<li>
						<a href="#" title="会员管理"><span>会员管理</span></a>
						<ul>
							<li><a href="/iokadmin.php?m=Channel">渠道商管理</a></li>
							<li><a href="/iokadmin.php?m=Servicestaff">商代商站管理</a></li>
							<li><a href="/iokadmin.php?m=Company">企业管理</a></li>
							<li><a href="/iokadmin.php?m=Person">个人管理</a></li>
							<li><a href="/iokadmin.php?m=Proof">会员资质</a></li>
							<li class="last"><a href="/iokadmin.php?m=Grade">会员等级</a></li>
						</ul>
					</li>
					<li>
						<a href="#" title="用户管理"><span>用户管理</span></a>
						<ul>
							<li><a href="/iokadmin.php?m=Department">部门管理</a></li>
							<li><a href="/iokadmin.php?m=User">用户管理</a></li>
							<li><a href="/iokadmin.php?m=Usergroup">用户组管理</a></li>
							<li class="last"><a href="/iokadmin.php?m=Role">角色管理</a></li>
						</ul>
					</li>
					<li>
						<a href="#" title="网站管理"><span>网站管理</span></a>
						<ul>
							<li><a href="/iokadmin.php?m=Syscfg">网站设置</a></li>
							<li><a href="/iokadmin.php?m=Linkedin">友情链接</a></li>
							<li><a href="/iokadmin.php?m=Partner">战略伙伴</a></li>
							<li><a href="/iokadmin.php?m=Hotsearch">热门搜索</a></li>
							<li class="last"><a href="/iokadmin.php?m=Notice">系统公告</a></li>
						</ul>
					</li>
					<li>
						<a href="#" title="系统管理"><span>系统管理</span></a>
						<ul>
							<li><a href="/iokadmin.php?m=Area">地区管理</a></li>
							<li><a href="/iokadmin.php?m=Category">类别管理</a></li>
							<li><a href="/iokadmin.php?m=Property">属性管理</a></li>
							<li><a href="/iokadmin.php?m=Banword">敏感词管理</a></li>
							<li><a href="/iokadmin.php?m=Log">日志管理</a></li>
							<li class="last"><a href="/iokadmin.php?m=Rolenode">节点管理</a></li>
						</ul>
					</li>
					<li>
						<a href="#" title="系统管理"><span>API管理</span></a>
						<ul>
							<li><a href="/iokadmin.php?m=Project">项目管理</a></li>
							<li><a href="/iokadmin.php?m=Model">模块管理</a></li>
							<li><a href="/iokadmin.php?m=Interface">接口管理</a></li>
							<li><a href="/iokadmin.php?m=Parameter">参数管理</a></li>
							
						</ul>
					</li>
				</ul>
				<!-- end quick -->
				<div class="corner tl"></div>
				<div class="corner tr"></div>
			</div>
		</div>
		<!-- end header --><?php }} ?>