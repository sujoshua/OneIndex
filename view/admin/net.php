<?php view::layout('layout')?>

<?php view::begin('content');?>
<div class="mdui-container-fluid">

	<div class="mdui-typo">
	  <h1> <a href="" target="_blank">网络设置</a></h1>
	</div>
	<form action="" method="post">
		<div class="mdui-textfield">
		  <h4>开启反向代理服务器</h4>
		  <label class="mdui-textfield-label"></label>
		  <label class="mdui-switch">
			  <input type="checkbox" name="is_proxy" value="1" <?php echo empty($config['is_proxy'])?'':'checked';?>/>
			  <i class="mdui-switch-icon"></i>
		  </label>
		</div>
		<div class="mdui-textfield">
		  <h4>反向代理服务器地址</h4>
		  <input class="mdui-textfield-input" type="text" name="proxy_server" value="<?php echo $config['proxy_server'];?>"/>
		</div>
        <div class="mdui-textfield">
		  <h4>curl连接服务器传输超时时间</h4>
		  <input class="mdui-textfield-input" type="text" name="fetch_timeout" value="<?php echo $config['fetch_timeout'];?>"/>
		</div>
        <div class="mdui-textfield">
		  <h4>curl连接服务器传输前的连接超时时间</h4>
		  <input class="mdui-textfield-input" type="text" name="fetch_connect_timeout" value="<?php echo $config['fetch_connect_timeout'];?>"/>
		</div>
	   <Br>
	   <button type="submit" class="mdui-btn mdui-color-theme-accent mdui-ripple mdui-float-right">
	   	<i class="mdui-icon material-icons">&#xe161;</i> 保存
	   </button>
	   <Br>
	</form>
</div>
<?php view::end('content');?>