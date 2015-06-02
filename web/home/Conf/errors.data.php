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
$errors['login']['allempty'] = '所有项目必填';
$errors['login']['erroraccount'] = '无此账户';
$errors['login']['errorcaptcha'] = '验证码错误';
$errors['login']['errorpasswd'] = '密码错误';
$errors['login']['inactivedaccount'] = '账户未激活';
$errors['login']['disabledaccount'] = '账户被禁用';
$errors['login']['nousergroup'] = '该账户不属于任何用户组，禁止登陆';
// register
$errors['register']['existaccount'] = '该登录账号已经被占用';
// profile
$errors['profile']['emptyoldpasswd'] = '请输入原密码';
$errors['profile']['erroroldpasswd'] = '原密码错误';
// user
$errors['user']['emptyaccount'] = '登录账号不能为空';
$errors['user']['emptyprettyname'] = '真实姓名不能为空';

?>