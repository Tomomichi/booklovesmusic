<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $this->Html->charset(); ?>
	<?php echo $this->Html->css('reset'); ?>
	<?php echo $this->Html->css('960'); ?>
	<?php echo $this->Html->css('jq_carousel/style'); ?>
	<?php echo $this->Html->css('main'); ?>
	<?php echo $this->Html->css('style'); ?>

	<?php echo $this->Html->script("jquery"); ?>
	<?php echo $this->Html->script("script"); ?>
	<?php echo $this->Html->script("tinybox"); ?>
	<?php echo $this->Html->script("jquery.masonry.min"); ?>
	<?php echo $this->Html->script("jq_carousel/jq.carousel.js"); ?>

	<link rel="icon" href="<?php echo $this->Html->webroot('favicon.ico');?>" type="image/x-icon" />

	<meta property="og:title" content="読書にぴったりの音楽おすすめサービス booklovesmusic" />
	<meta property="og:type" content="activity" />
	<meta property="og:url" content="http://booklovesmusic.com" />
	<meta property="og:image" content="http://booklovesmusic.com/img/OGPlogo.jpg" />
	<meta property="og:description" content="booklovesmusic（ブック・ラブズ・ミュージック）は、読書にぴったりの音楽おすすめサービスです。" />
	<!--<meta property="og:site_name" content="booklovesmusic（ブック ラブズ ミュージック）" />-->

	<title>booklovesmusic | 読書にぴったりの音楽おすすめサービス</title>

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
<body class="default">

	<!-- FacebookSDK -->
	<div id="fb-root"></div>
	<script>
		(function(d, s, id) {
		  var js, fjs = d.getElementsByTagName(s)[0];
		  if (d.getElementById(id)) return;
		  js = d.createElement(s); js.id = id;
		  js.src = "//connect.facebook.net/ja_JP/all.js#xfbml=1&appId=272701392765793";
		  fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));
	</script>


  <!-- ページ全体のコンテナ -->
  <div class="bak">
  	<div class="container_12">
		<!-- ヘッダ -->
		<div class="grid_12 header">
			<?php print $this->Html->link( $this->Html->image("common/logo.png"), array('controller'=>'', 'action'=>'index'), array('escape' => false) ); ?>
			<div>
				<p>
					<?php print $user['Account']['name'];?>さん
					<?php
						if($user['Account']['name']=="ゲスト"){
							print $this->Html->link("[ログイン]", array('controller'=>'accounts', 'action'=>'index') );
						}else{
							print $this->Html->link("[ログアウト]", array('controller'=>'accounts', 'action'=>'logout') );
						}
					?>
				</p>
				<p>
					<?php echo $this->Html->link(
						'つかいかた',
						'http://nanapi.jp/35362/',
						array('target'=>'_blank')
					);?>
				</p>
			</div>
		</div>
		<div class="grid_12 nav">
					<?php	print $this->Html->link( "HOME", array('controller'=>'', 'action'=>'index'), array("class"=>"nav_01") );?>
					<?php	print $this->Html->link( "音楽をさがす", array('controller'=>'playlists', 'action'=>'index'), array("class"=>"nav_02") );?>
					<?php	print $this->Html->link( "PlayListをつくる", array('controller'=>'addlists', 'action'=>'index'), array("class"=>"nav_03") );?>
					<?php	print $this->Html->link( "特集コンテンツ", array('controller'=>'features', 'action'=>'haruki'), array("class"=>"nav_04") );?>
					<?php	print $this->Html->link( "どんなサービス？", array('controller'=>'abouts', 'action'=>'index'), array("class"=>"nav_05") );?>
					<?php
						if($user['Account']['name']=="ゲスト"){
							print $this->Html->link( "twitterでログイン", array('controller'=>'accounts', 'action'=>'login', 'twitter'), array("class"=>"nav_06") );
						}else{
							print $this->Html->link( "マイページ", array('controller'=>'mypages', 'action'=>'index', $user['Account']['id']), array("class"=>"nav_06") );
						}
					?>
		</div>

		<!-- 各ページのコンテンツ -->
		<div class="grid_12">
			<?php echo $this->Session->flash(); ?>
		</div>
		<?php echo $content_for_layout; ?>

		<!-- サイドバー -->
		<?php require_once("side_bar.html");?>

	</div>

	<!-- フッタ -->
	<div class="footer">
		<p>
			Copyright 2011-<?php echo date("Y");?>
			<?php	print $this->Html->link( "booklovesmusic", array('controller'=>'', 'action'=>'index') );?>
			All Rights Reserved.　|　
			Produced by <a href="https://twitter.com/#!/kame_f_no7" target="_blank">@kame_f_no7</a>
		</p>
		<div id="toTop">
			<p>Back to Top</p>
		</div>
	</div>

  </div>

	<!-- ソーシャルボタン -->

	<div id="social">
			<div><iframe src="http://www.facebook.com/plugins/like.php?href=http%3a%2f%2fbooklovesmusic%2ecom%2f&amp;layout=box_count&amp;show_faces=false&amp;width=70&amp;action=like&amp;colorscheme=light&amp;height=68" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:70px; height:65px;" allowTransparency="true"></iframe></div>
			<div>
				<a href="https://twitter.com/share?count=vertical" class="twitter-share-button" data-url="http://booklovesmusic.com" data-text="読書にぴったりの音楽おすすめサービス | booklovesmusic（ブック ラブズ ミュージック）">Tweet</a>
				<script>
					!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");
				</script>
			</div>
	</div>



</body>
</html>
