<!DOCTYPE html> 
<html>
<head> 
    <meta charset="UTF-8"> 
    <title>booklovesmusic</title> 

	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.0a4.1/jquery.mobile-1.0a4.1.min.css">
	<?php echo $this->Html->css('Smartphone/sp_main'); ?>
	<?php echo $this->Html->script("jquery"); ?>
	<script src="http://code.jquery.com/mobile/1.0a4.1/jquery.mobile-1.0a4.1.min.js"></script>
    <meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1">
</head> 
<body>
	<div data-role="page" data-fullscreen="true"> 
		<!-- CONTENT -->
		<?php echo $content_for_layout; ?>

		<!-- FOOTER -->
	    <div data-role="footer" class="ui-bar" data-position="fixed" data-id="footer">
  			<div data-role="navbar">
  				<ul>
					<li><?php echo $this->Html->link( "Home", array('controller'=>'pages', 'action'=>'index'), array("data-role"=>"button", "data-icon"=>"home", "data-iconpos"=>"bottom") );?></li>
					<li><?php echo $this->Html->link( "Search", array('controller'=>'playlists', 'action'=>'index'), array("data-role"=>"button", "data-icon"=>"search", "data-iconpos"=>"bottom") );?></li>
					<li><?php echo $this->Html->link( "Add List", array('controller'=>'addlists', 'action'=>'index'), array("data-role"=>"button", "data-icon"=>"plus", "data-iconpos"=>"bottom") );?></li>
					<li><?php echo $this->Html->link( "Special", array('controller'=>'features', 'action'=>'index'), array("data-role"=>"button", "data-icon"=>"star", "data-iconpos"=>"bottom") );?></li>
					<li><?php echo $this->Html->link( "About", array('controller'=>'abouts', 'action'=>'index'), array("data-role"=>"button", "data-icon"=>"info", "data-iconpos"=>"bottom") );?></li>
				</ul>
			</div>
    </div>
	</div>
</body>
</html>


