<?php
/**
 * @name errors.data.php
 * @author wayne
 */
$errors = array();
$errors['public']['error'] = '有错误发生';
$errors['public']['noedit'] = '该记录不允许修改';
$errors['public']['norecord'] = '没有找到该记录';
$errors['public']['nopermission'] = '无此操作权限';
$errors['public']['submiterr'] = '数据提交发生未知错误，请重新操作';
$errors['public']['emptyemail'] = 'email地址不能为空';
$errors['public']['invalidemail'] = '无效的email地址';
$errors['public']['existemail'] = 'email地址已经存在';
$errors['public']['invalidtelephone'] = '不是有效的固定电话号码';
$errors['public']['invalidmobile'] = '不是有效的手机号码';
$errors['public']['emptytitle'] = '标题不能为空';
$errors['public']['emptycontent'] = '内容不能为空';
// login module
$errors['Login']['allempty'] = '所有项目必填';
$errors['Login']['erroraccount'] = '无此账户';
$errors['Login']['errorcaptcha'] = '验证码错误';
$errors['Login']['errorpasswd'] = '密码错误';
$errors['Login']['inactivedaccount'] = '账户未激活';
$errors['Login']['disabledaccount'] = '账户被禁用';
$errors['Login']['nousergroup'] = '该账户不属于任何用户组，禁止登陆';
// profile
$errors['Profile']['emptyoldpasswd'] = '请输入原密码';
$errors['Profile']['erroroldpasswd'] = '原密码错误';
$errors['Profile']['invalidpasswd'] = '无效密码';
// usergroup
$errors['Usergroup']['invalidrole'] = '无效的角色';
$errors['Usergroup']['noreservedtype'] = '至少选择一个预定类型';
$errors['Usergroup']['errormaxrequest'] = '请正确输入最大预定个数';
$errors['Usergroup']['maxrequestrange'] = '最大预定个数只能从1到100';
$errors['Usergroup']['errormaxsnapshot'] = '请正确输入最多快照数量';
$errors['Usergroup']['maxsnapshotrange'] = '最多快照数量只能从0到20';
// user
$errors['User']['emptyaccount'] = '登录账号不能为空';
$errors['User']['emptyprettyname'] = '真实姓名不能为空';

// Channel 会员---添加渠道商
$errors['Channel']['emptyaccount'] = '账号不能为空';
$errors['Channel']['existaccount'] = '该用户名已被注册';
$errors['Channel']['invalidaccount'] = '无效账户';
$errors['Channel']['emptyprettyname'] = '真实姓名不能为空';
$errors['Channel']['invalidpasswd'] = '无效密码';
$errors['Channel']['notmatch'] = '重复密码不一致';
$errors['Channel']['invalidemail'] = '邮箱格式不正确';
$errors['Channel']['existemail'] = '该邮箱已被注册';
$errors['Channel']['invalidmobile'] = '手机号码格式不正确';

// Servicestaff 会员---添加渠道商
$errors['Servicestaff']['emptyaccount'] = '账号不能为空';
$errors['Servicestaff']['existaccount'] = '该用户名已被注册';
$errors['Servicestaff']['invalidaccount'] = '无效账户';
$errors['Servicestaff']['emptyprettyname'] = '真实姓名不能为空';
$errors['Servicestaff']['invalidpasswd'] = '无效密码';
$errors['Servicestaff']['notmatch'] = '重复密码不一致';
$errors['Servicestaff']['invalidemail'] = '邮箱格式不正确';
$errors['Servicestaff']['existemail'] = '该邮箱已被注册';
$errors['Servicestaff']['invalidmobile'] = '手机号码格式不正确';

// Product 产品管理
$errors['Product']['emptyaccount'] = '不能为空';

//Market  招商管理
$errors['Market']['emptyaccount'] = '标题不能为空';
?>