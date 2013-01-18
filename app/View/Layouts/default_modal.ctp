<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $this->Html->charset(); ?>
	<?php echo $this->Html->css('style'); ?>
	<?php echo $this->Html->css('main'); ?>
	<?php echo $this->Html->script("jquery"); ?>
	<?php echo $this->Html->script("script"); ?>
	<?php echo $this->Html->script("tinybox"); ?>

	<!--GoogleAnalytics用解析コード-->
	<script type="text/javascript">
	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-30867542-1']);
	  _gaq.push(['_trackPageview']);

	  (function() {
	    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();

	</script>
</head>
<body class="default_modal" style="width:600px;">
		<!-- 各ページのコンテンツ -->
		<?php echo $content_for_layout; ?>
</body>
</html>