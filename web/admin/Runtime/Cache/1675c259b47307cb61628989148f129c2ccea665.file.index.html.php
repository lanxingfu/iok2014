<?php /* Smarty version Smarty-3.1.14, created on 2014-12-08 15:12:46
         compiled from ".\admin\Tpl\Login\index.html" */ ?>
<?php /*%%SmartyHeaderCode:181954854f6ec9d298-86780221%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1675c259b47307cb61628989148f129c2ccea665' => 
    array (
      0 => '.\\admin\\Tpl\\Login\\index.html',
      1 => 1387722620,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '181954854f6ec9d298-86780221',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'account' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_54854f6ed06099_83865591',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_54854f6ed06099_83865591')) {function content_54854f6ed06099_83865591($_smarty_tpl) {?><?php if (!is_callable('smarty_function_tip')) include 'D:\\WorkSpace\\PHP\\iok2014\\web\\ThinkPHP\\Extend\\Vendor\\Smarty\\plugins\\function.tip.php';
?><?php echo $_smarty_tpl->getSubTemplate ("header.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

		<div id="login">
			<!-- login -->
			<div class="title">
				<h5>我行网后台管理系统</h5>
				<div class="corner tl"></div>
				<div class="corner tr"></div>
			</div>
			<div class="messages">
			<?php echo smarty_function_tip(array('id'=>'top'),$_smarty_tpl);?>

			</div>
			<div class="inner">
				<form id="frm" method="post">
				<div class="form">
					<!-- fields -->
					<div class="fields">
						<div class="field">
							<div class="label">
								<label for="username">帐号：</label>
							</div>
							<div class="input">
								<input type="text" id="username" name="account" size="40" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['account']->value, ENT_QUOTES, 'UTF-8', true);?>
" />
							</div>
						</div>
						<div class="field">
							<div class="label">
								<label for="password">密码：</label>
							</div>
							<div class="input">
								<input type="password" id="password" name="passwd" size="40" />
							</div>
						</div>
						<div class="field">
							<div class="label">
								<label for="captcha">验证码：</label>
							</div>
							<div class="input">
								<input type="text" name="captcha" style="width:40px;" id="captcha" maxlength="4" />
								<img src="/iokadmin.php?m=Misc&a=captcha&t=<?php echo time();?>
" title="点击更换图片" onclick="javascript:reloadcaptcha(this);" style="margin-left:10px;height:25px;" />
							</div>
						</div>
						<div class="buttons">
							<input type="hidden" name="m" value="login" />
							<input type="hidden" name="a" value="submit" />
							<input type="submit" value="登录" />
						</div>
					</div>
					<!-- end fields -->
					<!-- links -->
					<!--
					<div class="links">
						<a href="index.html">忘记密码？</a>
					</div>
					-->
					<!-- end links -->
				</div>
				</form>
			</div>
			<!-- end login -->
			<div id="colors-switcher" class="color">
				<a href="" class="blue" title="Blue"></a>
				<a href="" class="green" title="Green"></a>
				<a href="" class="brown" title="Brown"></a>
				<a href="" class="purple" title="Purple"></a>
				<a href="" class="red" title="Red"></a>
				<a href="" class="greyblue" title="GreyBlue"></a>
			</div>
		</div>
<?php echo $_smarty_tpl->getSubTemplate ("footer.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<?php }} ?>