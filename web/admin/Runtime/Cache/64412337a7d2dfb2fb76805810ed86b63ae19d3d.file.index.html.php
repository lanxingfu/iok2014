<?php /* Smarty version Smarty-3.1.14, created on 2014-12-08 15:12:36
         compiled from ".\admin\Tpl\Index\index.html" */ ?>
<?php /*%%SmartyHeaderCode:612854854f647b8371-84175451%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '64412337a7d2dfb2fb76805810ed86b63ae19d3d' => 
    array (
      0 => '.\\admin\\Tpl\\Index\\index.html',
      1 => 1387722618,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '612854854f647b8371-84175451',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_54854f6481fdc2_49042184',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_54854f6481fdc2_49042184')) {function content_54854f6481fdc2_49042184($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("header.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("top.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

		<!-- content -->
		<div id="content">
			<?php echo $_smarty_tpl->getSubTemplate ("Index/leftmenu.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

			<!-- content / right -->
			<div id="right">
				<!-- table -->
				<div class="box">
					<!-- box / title -->
					<div class="title">
						<h5>Products</h5>
						<div class="search">
							<form action="#" method="post">
								<div class="input">
									<input type="text" id="search" name="search" />
								</div>
								<div class="button">
									<input type="submit" name="submit" value="Search" />
								</div>
							</form>
						</div>
					</div>
					<!-- end box / title -->
					<div class="table">
						<form action="" method="post">
						<table>
							<thead>
								<tr>
									<th class="left">Title</th>
									<th>Price</th>
									<th>Added</th>
									<th>Category</th>
									<th class="selected last"><input type="checkbox" class="checkall" /></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td class="title">24" LED Monitor</td>
									<td class="price">$110.00</td>
									<td class="date">April 25th, 2010 at 4:15 PM</td>
									<td class="category">Monitors</td>
									<td class="selected last"><input type="checkbox" /></td>
								</tr>
								<tr>
									<td class="title">27" LCD Flat Panel</td>
									<td class="price">$150.00</td>
									<td class="date">April 25th, 2010 at 4:15 PM</td>
									<td class="category">Monitors</td>
									<td class="selected last"><input type="checkbox" /></td>
								</tr>
								<tr>
									<td class="title">6GB 240-Pin DDR3 SDRAM DDR3 1600</td>
									<td class="price">$80.00</td>
									<td class="date">April 25th, 2010 at 4:15 PM</td>
									<td class="category">Memory</td>
									<td class="selected last"><input type="checkbox" /></td>
								</tr>
								<tr>
									<td class="title">500GB 7200 RPM 16MB Cache SATA 3.0Gb/s 3.5</td>
									<td class="price">$92.00</td>
									<td class="date">April 25th, 2010 at 4:15 PM</td>
									<td class="category">Hard Drives</td>
									<td class="selected last"><input type="checkbox" /></td>
								</tr>
								<tr>
									<td class="title">2GB 240-Pin DDR3 SDRAM DDR3 1600</td>
									<td class="price">$52.00</td>
									<td class="date">April 25th, 2010 at 4:15 PM</td>
									<td class="category">Memory</td>
									<td class="selected last"><input type="checkbox" /></td>
								</tr>
							</tbody>
						</table>
						<!-- table action -->
						<div class="action">
							<select name="action">
								<option value="" class="locked">Set status to Deleted</option>
								<option value="" class="unlocked">Set status to Published</option>
								<option value="" class="folder-open">Move to Category</option>
							</select>
							<div class="button">
								<input type="submit" name="submit" value="Apply to Selected" />
							</div>
						</div>
						<!-- end table action -->
						</form>
					</div>
				</div>
				<!-- end table -->
				<div class="box">
					<!-- box / title -->
					<div class="title">
						<h5>Product Sales</h5>
						<ul class="links">
							<li><a href="">Full Report</a></li>
						</ul>
					</div>
					<!-- end box / title -->
					<div class="sales">
						<div class="legend">
							<h6>Units Sold (April 1st to April 15th)</h6>
							<ul>
								<li class="monitors">Monitors</li>
								<li class="memory">Memory</li>
							</ul>
						</div>
						<div id="sales"></div>
					</div>
				</div>
				<!-- messages -->
				<div id="box-tabs" class="box">
					<!-- box / title -->
					<div class="title">
						<h5>Content Box</h5>
						<ul class="links">
							<li><a href="#box-messages">Messages</a></li>
							<li><a href="#box-other">Typography</a></li>
							<li><a href="#box-dialogs">Dialogs</a></li>
						</ul>
					</div>
					<!-- box / title -->
					<div id="box-messages">
						<div class="messages">
							<div id="message-error" class="message message-error">
								<div class="image">
									<img src="/public/image/agency/icons/error.png" alt="Error" height="32" />
								</div>
								<div class="text">
									<h6>Error Message</h6>
									<span>This is the error message.</span>
								</div>
								<div class="dismiss">
									<a href="#message-error"></a>
								</div>
							</div>
							<div id="message-warning" class="message message-warning">
								<div class="image">
									<img src="/public/image/agency/icons/warning.png" alt="Warning" height="32" />
								</div>
								<div class="text">
									<h6>Warning Message</h6>
									<span>This is the warning message.</span>
								</div>
								<div class="dismiss">
									<a href="#message-warning"></a>
								</div>
							</div>
							<div id="message-notice" class="message message-notice">
								<div class="image">
									<img src="/public/image/agency/icons/notice.png" alt="Notice" height="32" />
								</div>
								<div class="text">
									<h6>Notice Message</h6>
									<span>This is the notice message.</span>
								</div>
								<div class="dismiss">
									<a href="#message-notice"></a>
								</div>
							</div>
							<div id="message-success" class="message message-success">
								<div class="image">
									<img src="/public/image/agency/icons/success.png" alt="Success" height="32" />
								</div>
								<div class="text">
									<h6>Success Message</h6>
									<span>This is the success message.</span>
								</div>
								<div class="dismiss">
									<a href="#message-success"></a>
								</div>
							</div>
						</div>
					</div>
					<div id="box-other">
						<!-- paragraphs -->
						<p class="start"><img src="/public/image/agency/misc/ginger.jpg" alt="Ginger" class="right" />Lorem ipsum dolor sit amet, consectetur <a href="">adipisicing</a> elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
						<p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
						<!-- end paragraphs -->
						<!-- headings -->
						<h5>Heading</h5>
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
						<blockquote><p>Lorem ipsum dolor sit amet, consectetur <a href="">adipisicing</a> elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p></blockquote>
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
						<!-- end headings -->
					</div>
					<div id="box-dialogs">
						<p><a id="dialog-open" href="#">Open Dialog</a></p>
						<p><a id="dialog-modal-open" href="#">Open Modal Dialog</a></p>
						<p><a id="dialog-message-open" href="#">Open Modal Message Dialog</a></p>
						<p><a id="dialog-confirm-open" href="#">Open Modal Confirmation Dialog</a></p>
						<p><a id="dialog-form-open" href="#">Open Form Dialog</a></p>
					</div>
				</div>
				<!-- end messages -->
				<!-- forms -->
				<div class="box">
					<!-- box / title -->
					<div class="title">
						<h5>Forms</h5>
					</div>
					<!-- end box / title -->
					<form id="form" action="" method="post">
					<div class="form">
						<div class="fields">
							<div class="field  field-first">
								<div class="label">
									<label for="input-small">Small Input:</label>
								</div>
								<div class="input">
									<input type="text" id="input-small" name="input.small" class="small" />
									<div class="button highlight">
										<input type="submit" name="submit.highlight" value="Submit Empathized" />
									</div>
								</div>
							</div>
							<div class="field">
								<div class="label">
									<label for="input-medium">Medium Input:</label>
								</div>
								<div class="input">
									<input type="text" id="input-medium" name="input.medium" class="medium" />
								</div>
							</div>
							<div class="field">
								<div class="label">
									<label for="input-large">Large Input:</label>
								</div>
								<div class="input">
									<input type="text" id="input-large" name="input.large" class="large" />
								</div>
							</div>
							<div class="field">
								<div class="label">
									<label for="autocomplete">Auto Complete:</label>
								</div>
								<div class="input">
									<input type="text" id="autocomplete" name="input.autocomplete" value="start typing for example: java" class="small focus" />
								</div>
							</div>
							<div class="field">
								<div class="label">
									<label for="date">Date Picker:</label>
								</div>
								<div class="input">
									<input type="text" id="date" name="input.date" class="date" />
								</div>
							</div>
							<div class="field">
								<div class="label">
									<label for="input-error">Error Input:</label>
								</div>
								<div class="input">
									<input type="text" id="input-error" name="input.error" class="small error" />
									<span class="error">This is a required field.</span>
								</div>
							</div>
							<div class="field">
								<div class="label">
									<label for="input-valid">Valid Input:</label>
								</div>
								<div class="input">
									<input type="text" id="input-valid" name="input.valid" class="small valid" />
								</div>
							</div>
							<div class="field">
								<div class="label">
									<label for="select">Select:</label>
								</div>
								<div class="select">
									<select id="select" name="select">
										<option value="1">Option #1</option>
										<option value="2">Option #2</option>
										<option value="3">Option #3</option>
									</select>
								</div>
							</div>
							<div class="field">
								<div class="label label-checkbox">
									<label>Checkboxes:</label>
								</div>
								<div class="checkboxes">
									<div class="checkbox">
										<input type="checkbox" id="checkbox-1" name="checkboxex" />
										<label for="checkbox-1">Option #1</label>
									</div>
									<div class="checkbox">
										<input type="checkbox" id="checkbox-2" name="checkboxex" />
										<label for="checkbox-2">Option #2</label>
									</div>
									<div class="checkbox">
										<input type="checkbox" id="checkbox-3" name="checkboxex" />
										<label for="checkbox-3">Option #3</label>
									</div>
								</div>
							</div>
							<div class="field">
								<div class="label label-radio">
									<label>Radios:</label>
								</div>
								<div class="radios">
									<div class="radio">
										<input type="radio" id="radio-1" name="radioex" />
										<label for="radio-1">Option #1</label>
									</div>
									<div class="radio">
										<input type="radio" id="radio-2" name="radioex" />
										<label for="radio-2">Option #2</label>
									</div>
									<div class="radio">
										<input type="radio" id="radio-3" name="radioex" />
										<label for="radio-3">Option #3</label>
									</div>
								</div>
							</div>
							<div class="field">
								<div class="label">
									<label for="file">File:</label>
								</div>
								<div class="input input-file">
									<input type="file" id="file" name="file" size="40" />
								</div>
							</div>
							<div class="field">
								<div class="label label-textarea">
									<label for="textarea">Textarea:</label>
								</div>
								<div class="textarea textarea-editor">
									<textarea id="textarea" name="textarea" cols="50" rows="12" class="editor"></textarea>
								</div>
							</div>
							<div class="buttons">
								<input type="submit" name="submit" value="Submit" />
								<input type="reset" name="reset" value="Reset" />
								<div class="highlight">
									<input type="submit" name="submit.highlight" value="Submit Empathized" />
								</div>
							</div>
						</div>
					</div>
					</form>
				</div>
				<!-- end forms -->
				<!-- box / left -->
				<div id="box-left-tabs" class="box box-left box-padding">
					<!-- box / title -->
					<div class="title">
						<h5>Left Column</h5>
						<ul class="links">
							<li><a href="#box-left-forms">Tab #1</a></li>
							<li><a href="#box-left-other">Tab #2</a></li>
						</ul>
					</div>
					<!-- end box / title -->
					<div id="box-left-forms">
						<form action="" method="post">
						<div class="form">
							<div class="fields">
								<div class="field field-first">
									<div class="label">
										<label for="input">Textbox:</label>
									</div>
									<div class="input">
										<input type="text" id="input" name="input" />
									</div>
								</div>
								<div class="field">
									<div class="label label-textarea">
										<label for="textarea">Textarea:</label>
									</div>
									<div class="textarea">
										<textarea id="textarea1" name="textarea" cols="50" rows="8"></textarea>
									</div>
								</div>
								<div class="buttons">
									<input type="submit" name="submit" value="Submit" />
									<input type="reset" name="reset" value="Reset" />
								</div>
							</div>
						</div>
						</form>
					</div>
					<div id="box-left-other">
						<!-- paragraphs -->
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
						<blockquote><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p></blockquote>
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
						<!-- end paragraphs -->
					</div>
				</div>
				<!-- end box / left -->
				<!-- box / right -->
				<div class="box box-right">
					<!-- box / title -->
					<div class="title">
						<h5>Right Column</h5>
					</div>
					<!-- end box / title -->
					<!-- paragraphs -->
					<p class="start"><img src="/public/image/agency/misc/ginger.jpg" alt="Ginger" class="right" />Lorem ipsum dolor sit amet, consectetur <a href="">adipisicing</a> elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
					<p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
					<!-- end paragraphs -->
					<!-- headings -->
					<h1>Heading</h1>
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
					<blockquote><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p></blockquote>
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
					<h2>Heading</h2>
					<ol class="decimal">
						<li>List Item 1</li>
						<li>List Item 2</li>
						<li>List Item 3</li>
					</ol>
					<h3>Heading</h3>
					<ul class="disc">
						<li>List Item 1</li>
						<li>List Item 2</li>
						<li>List Item 3</li>
					</ul>
					<h4>Heading</h4>
					<ul class="square">
						<li>List Item 1</li>
						<li>List Item 2</li>
						<li>List Item 3</li>
					</ul>
					<h5>Heading</h5>
					<dl>
						<dt>Definition List Title</dt>
						<dd>This is a definition list division.</dd>
					</dl>
					<!-- end headings -->
				</div>
				<!-- end box / right -->
			</div>
			<!-- end content / right -->
		</div>
		<!-- end content -->
<?php echo $_smarty_tpl->getSubTemplate ("footer.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<?php }} ?>