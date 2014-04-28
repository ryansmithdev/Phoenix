    </div><!--content-right-->
    <div class="clearfix"></div>
</div><!--container-->
<? if ( DEBUG_MODE_ENABLED ): ?>
<div id="debug">
	<div id="debug-inner">
		
		<div id="debug-gui">
			<? dbug_gui(); ?>
		</div>
		<form method="post">
		
			<input type="hidden" name="form" value="phoenix-command"/>
			
			<input id="command" type="text" name="command" autofocus="true" value="cmd " spellcheck="false"/>
			
		</form>
		
	</div>

</div>
<? endif;?>

</body>
</html>