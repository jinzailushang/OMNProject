<div class="successfully">	
		<h4 class="emhead">
			<div class="<?php if($output['tips_type'] == 'succ'){ ?> success-img <?php }else{?> error-img <?php }?>"><?php echo $output['position']; ?></div> 
			<!--<p>若不选择将自动跳转<a href="<?php echo $output['url'] ?>">返回上一页</a></p>-->
		</h4>
</div>
<script type="text/javascript"> window.setTimeout("javascript:location.href='<?php echo $output['url'] ?>'", <?php echo $output['time'] ?>);</script>

