<?php /* Smarty version Smarty-3.1.14, created on 2014-12-08 15:11:09
         compiled from ".\admin\Tpl\Channel\cadd.html" */ ?>
<?php /*%%SmartyHeaderCode:1893054854f0dedfaf6-76754132%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c887b4d24811d3752d52511aefbad942253670c1' => 
    array (
      0 => '.\\admin\\Tpl\\Channel\\cadd.html',
      1 => 1387988158,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1893054854f0dedfaf6-76754132',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'data' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_54854f0e10b007_03531613',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_54854f0e10b007_03531613')) {function content_54854f0e10b007_03531613($_smarty_tpl) {?><?php if (!is_callable('smarty_function_tip')) include 'D:\\WorkSpace\\PHP\\iok2014\\web\\ThinkPHP\\Extend\\Vendor\\Smarty\\plugins\\function.tip.php';
?><?php echo $_smarty_tpl->getSubTemplate ("header.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("top.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<!-- content -->
<div id="content">
	<?php echo $_smarty_tpl->getSubTemplate ("Channel/leftmenu.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

	<!-- content / right -->
	<div id="right">
		<div class="box">
			<!-- box / title -->
			<div class="title">
				<h5><?php if ($_smarty_tpl->tpl_vars['data']->value['id']){?>编辑代理商<?php }else{ ?>添加代理商<?php }?></h5>
			</div>
			<form id="form" action="?m=Channel&a=submit" method="post">
				<input type="hidden" name="id" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['id'];?>
">
				<div class="form">
					<div class="fields">
						<div class="field  field-first">
							<div class="label">
								<label for="label">用户名:</label>
							</div>
							<div class="input">
								<input type="text" id="account" name="account" class="small" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['account'];?>
"/>
								<div class="tip"><?php echo smarty_function_tip(array('id'=>'account','default'=>'请输入用户的登录账号，3到20位英文字母、数字及“_”“-”组合'),$_smarty_tpl);?>
</div>
							</div>
						</div>
						<div class="field">
							<div class="label">
								<label for="label">密码:</label>
							</div>
							<div class="input">
								<input type="password" id="passhash" name="passhash" class="small" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['passhash'];?>
"/>
								<div class="tip"><?php echo smarty_function_tip(array('id'=>'passhash','default'=>'请输入用户的登录密码，密码长度6-20位'),$_smarty_tpl);?>
</div>
							</div>
						</div>
						<div class="field">
							<div class="label">
								<label for="label">重复输入</label>
							</div>
							<div class="input">
								<input type="password" name="passhash2" class="small" />
							</div>
							<div class="tip"><?php echo smarty_function_tip(array('id'=>'passhash2','default'=>'请再次输入用户的登录密码'),$_smarty_tpl);?>
</div>
						</div>
						<div class="field">
							<div class="label label-radio">
								<label>性别</label>
							</div>
							<div class="radios">		
								<div class="radio">
										<input type="radio" value='male' name="gender" checked /><label for="radio-2">男</label>
										<input type="radio" value='female' name="gender" /><label for="radio-2">女</label>
								</div>
							</div>
						</div>
						<div class="field">
							<div class="label">
								<label for="input-small">代理地区:</label>
							</div>
							<?php if ($_smarty_tpl->tpl_vars['data']->value['agentareaid']){?>
							<div class="select" >
								<select id="agentareaid" name="agentareaid">
									<option value="<?php echo $_smarty_tpl->tpl_vars['data']->value['agentareaid'];?>
"><?php echo $_smarty_tpl->tpl_vars['data']->value['agentareaid'];?>
</option>
								</select>
							</div>
							<?php }else{ ?>
							<div class="select" name="agentareaid" id='agentareaid'></div>
							<script>loadarea('agentareaid')</script>
							<?php }?>
							<div class="tip" style="padding:0 0 3px 0;"><?php echo smarty_function_tip(array('id'=>'top0','default'=>'默认与注册地区一致'),$_smarty_tpl);?>
(如不一致请:
								<input type="button" onclick="javascript:$('#none').css('display','block');" value="点击"/>)
							</div>
						</div>
						<div class="field" id="none" style="display:none;">
							<div class="label">
								<label for="input-small">注册地区:</label>
							</div>
							<?php if ($_smarty_tpl->tpl_vars['data']->value['areaid']){?>
							<div class="select" >
								<select id="areaid" name="areaid">
									<option value="<?php echo $_smarty_tpl->tpl_vars['data']->value['areaid'];?>
"><?php echo $_smarty_tpl->tpl_vars['data']->value['areaid'];?>
</option>
								</select>
							</div>
							<?php }else{ ?>
							<div class="select" name="areaid" id='areaid'></div>
							<script>loadarea('areaid')</script>
							<?php }?>
						</div>
						<div class="field">
							<div class="label">
								<label for="input-small">真实姓名:</label>
							</div>
							<div class="input">
								<input type="text" id="prettyname" name="prettyname" class="small" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['prettyname'];?>
"/>
							</div>
							<div class="tip"><?php echo smarty_function_tip(array('id'=>'prettyname','default'=>'请输入真实姓名'),$_smarty_tpl);?>
</div>
						</div>
						<div class="field">
							<div class="label">
								<label for="input-small">手机号:</label>
							</div>
							<div class="input">
								<input type="text" id="mobile" name="mobile" class="small" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['mobile'];?>
"/>
							</div>
							<div class="tip"><?php echo smarty_function_tip(array('id'=>'mobile','default'=>'请输入手机号码'),$_smarty_tpl);?>
</div>
						</div>
						<div class="field">
							<div class="label">
								<label for="input-small">邮箱:</label>
							</div>
							<div class="input">
								<input type="text" id="email" name="email" class="small" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['email'];?>
"/>
							</div>
							<div class="tip"><?php echo smarty_function_tip(array('id'=>'email','default'=>'请输入电子邮箱'),$_smarty_tpl);?>
</div>
						</div>
						<div class="field">
							<div class="label">
								<label for="input-small">QQ:</label>
							</div>
							<div class="input">
								<input type="text" id="qq" name="qq" class="small" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['qq'];?>
"/>
							</div>
						</div>
						<div class="field">
							<div class="label">
								<label for="input-small">电话:</label>
							</div>
							<div class="input">
								<input type="text" id="telephone" name="telephone" class="small" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['telephone'];?>
"/>
							</div>
						</div>
						<div class="field">
							<div class="label">
								<label for="input-small">传真:</label>
							</div>
							<div class="input">
								<input type="text" id="fax" name="fax" class="small" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['fax'];?>
"/>
							</div>
						</div>
						<div class="field">
							<div class="label">
								<label for="input-small">邮编:</label>
							</div>
							<div class="input">
								<input type="text" id="postcode" name="postcode" class="small" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['postcode'];?>
"/>
							</div>
						</div>
						<!-- <div class="field">
							<div class="label">
								<label for="input-small">地址:</label>
							</div>
							<?php if ($_smarty_tpl->tpl_vars['data']->value['agentareaid']){?>
							<div class="select">
								<select id="addrareaid" name="addrareaid">
									<option value="<?php echo $_smarty_tpl->tpl_vars['data']->value['addrareaid'];?>
"><?php echo $_smarty_tpl->tpl_vars['data']->value['addrareaid'];?>
</option>
								</select> 
							</div>
							<?php }else{ ?>
							<div style="margin-top: 4px;" class="select" name="addrareaid" id='areaid0'></div>
							<script>loadarea('areaid0')</script>
							<?php }?>
							<input type="text" id="address" name="address" class="small" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['address'];?>
"/>
							<?php echo smarty_function_tip(array('id'=>'address','default'=>'请输入详细地址'),$_smarty_tpl);?>

						</div> -->
						<div class="field">
							<div class="label">
								<label for="input-small">邀请人:</label>
							</div>
							<div class="input">
								<input type="text" id="inviterid" name="inviterid" class="small" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['inviterid']['account'];?>
"/>
							</div>
							<div class="tip"><?php echo smarty_function_tip(array('id'=>'inviterid','default'=>'请输入邀约人'),$_smarty_tpl);?>
</div>
						</div>
						<div class="field">
							<div class="buttons">
								<div class="highlight">
									<input type="submit" name="submit" value="保存" />
								</div>
								<input type="reset" name="reset" value="取消" onclick="javascript:gotoUrl('/iokadmin.php?m=Channel')"/>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	<!-- end content / right -->
	</div>
</div>
<!-- end content -->
<?php echo $_smarty_tpl->getSubTemplate ("footer.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<?php }} ?>