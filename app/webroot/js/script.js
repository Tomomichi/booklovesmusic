var domain = location.hostname;

$(function(){

	//現在選択中のグローバルナビをハイライト
	$url = document.URL;
	$a = $url.indexOf("playlists");
	$b = $url.indexOf("addlists");
	$c = $url.indexOf("features");
	$d = $url.indexOf("abouts");
	$e = $url.indexOf("accounts");
	$f = $url.indexOf("mypages");

	if($a>0){
		$("a.nav_02").attr("id", "currentPage");
	}else if($b>0){
		$("a.nav_03").attr("id", "currentPage");
	}else if($c>0){
		$("a.nav_04").attr("id", "currentPage");
	}else if($d>0){
		$("a.nav_05").attr("id", "currentPage");
	}else if($e>0 || $f>0){
		//ハイライトなし
	}else{
		$("a.nav_01").attr("id", "currentPage");

	}
	

	//検索結果の最初の本だけ詳細を表示しとく
	$(".book_detail:first").toggle();
	$(".detail_nav:first img#show").hide();
	$(".detail_nav:first img#hide").show();	


	//検索結果のメニューパネルの操作
	$(".detail_nav").click(function(){

		//ナビボタンを押すたびに表示/非表示を切り替え。
		var id = $(this).attr("id");
		$("."+id).toggle();
		$("#"+id+" img").toggle();
		
		//他のナビボタンを開くやつに変更。
		$(".detail_nav:not(#"+id+") img#show").show();
		$(".detail_nav:not(#"+id+") img#hide").hide();

		//他の詳細パネルを閉じる。
		$("div.book_detail:not(."+id+")").hide();
		
	});


	//video選択ボックスの半透明フィルタ
	$("ul.video_box li a").hover(function(){
		$(this).css("background-color", "black").css("opacity", "0.5");
		$(this).append("<p>Click to<br>add<br>Music</p>");
	},function(){
		$(this).css("background-color", "").css("opacity", "");
		$("ul.video_box li a p").remove();
	});
	

	//お気に入りに追加。
	$("#fav_btn").click(function(){
		$("#fav_btn").attr("disabled", "disabled");
		var user_id = $("#uid_form").attr("value");
		var pl_id = $("#plid_form").attr("value");
		addFav(user_id, pl_id);
	});


	//モーダルウィンドウ内で検索
	$("#frmSearch").submit(function(){
        search($("#searchVideoVKey").val());
        return false;
	});
	
	
	//jQueryMasonry
	var $container = $('#container');
    $container.imagesLoaded( function(){
        $container.masonry({
            itemSelector : '.box'
        });
    });

	//お問い合わせ
	$("#side_submit").click(function(){
		var text = $("#side_contact").attr("value");
		if(!text){
			alert("テキストが入力されていません");
			return
		}
		submitContact(text);
		alert("送信しました！ご意見ありがとうございます！");
		$("#side_contact").attr("value", "");
	});
	
	
	//ページトップへのスクロール
	$('#toTop').click(function() {
      // スクロールの速度
      var speed = 500;// ミリ秒
      // 移動先を数値で取得
      var position = 0;
      // スムーススクロール
      $($.browser.safari ? 'body' : 'html').animate({scrollTop:position}, speed, 'swing');
      return false;
   });


    //jq.carouselの実装
    var $carousel = $('#carousel_1').carousel({indicator:true});

	$('#carousel_1_prev').click(function(ev) {
	  ev.preventDefault();
	  $carousel.carousel('prev');
	});
	$('#carousel_1_next').click(function(ev) {
	  ev.preventDefault();
	  $carousel.carousel('next');
	});

});


function search(keyword) {
    // (1) 表示領域を初期化します。
    $("#videos").text("Loading...");

    // (2) ajax通信を行います
    $.ajax({
        dataType: "jsonp",    // (3) データ形式はJSONPを指定します。
        data: {               // (4) リクエストパラメータを定義します。
            "vq": keyword,
            "max-results":"10",
            "alt":"json-in-script"
        },
        cache: true,          // (5) キャッシュを使用します。
        url: "http://gdata.youtube.com/feeds/api/videos",
        success: function (data) {  // (6) データ取得に成功した場合の処理を定義します。

            $("#videos").empty();
            $.each(data.feed.entry, function(i,item){    // (7) entryの各要素へアクセスします。
                var group = item.media$group;
                var v_id = item.id.$t.substr(42,11);
                
                $("<div/>", {class: 'search_result'})
                   // .attr("href", group.media$player[0].url).attr("target", "_blank")
                    .append("<div class='video_thumb'><img src='" + group.media$thumbnail[1].url + "'/></div>")
                    .append("<div class='video_caption'><h4><a href='"+group.media$player[0].url+"' target='_blank'>"+group.media$title.$t+"</a></h4><p>aurhor:"+item.author[0].name.$t+"<br>published:"+item.published.$t+"</p><div onclick=\"add_video(\'"+v_id+"\')\" class='add_video' id='"+v_id+"'>リストに追加</div></div>")
                    //.append("<div class='video_caption'><h4><a href='"+group.media$player[0].url+"' target='_blank'>"+group.media$title.$t+"</a></h4><p>aurhor:"+item.author[0].name.$t+"<br>published:"+item.published.$t+"</p><img onclick=\"add_video(\'"+v_id+"\')\" src='http://cahier-sauvage.boo.jp/blm/img/addList/add_btn.jpg' class='add_video' id='"+v_id+"'></div>")
                    .append("<div class='video_clear'></div>")
                    .appendTo("#videos");
            });
        }
    });
    
}


function add_video(v_code){
	
	//何番目の動画かを判定
	var body_class = $("#vsearch_main").attr("class");
	$num = body_class.slice(3);
	
	//選択した動画IDを親ウィンドウに反映(フォームのhidden値に設定)
	$v_form = parent.document.getElementById("vform_"+$num);
	$($v_form).val(v_code);
	
	$thum_url = "http://img.youtube.com/vi/" + v_code + "/1.jpg";
	$thumb = parent.document.getElementById("v_box"+ $num);
	$($thumb).append("<img src='"+$thum_url+"'>");

	//モーダルウィンドウを閉じる。
	$p = parent.document.getElementById("blackmask");
	$($p).css("display", "none");
	$tinner = parent.document.getElementById("frameless");
	$tbox = $($tinner).parent();
	$($tbox).css("display", "none");
}


//お気に入りへの追加
function addFav(user_id, pl_id) {
    $.ajax({
        dataType: "jsonp",    // (3) データ形式はJSONPを指定します。
        data: {               // (4) リクエストパラメータを定義します。
            "user_id": user_id,
            "pl_id": pl_id,
        },
        cache: true,          // (5) キャッシュを使用します。
        
        url: "http://booklovesmusic.com/favlists/addfav/",
    });
    console.log();
}


//お気に入りしてるかどうかチェック
function chkFav(user_id, pl_id) {
    $.ajax({
        dataType: "jsonp",    // (3) データ形式はJSONPを指定します。
        data: {               // (4) リクエストパラメータを定義します。
            "user_id": user_id,
            "pl_id": pl_id,
        },
        cache: true,          // (5) キャッシュを使用します。

        url: "http://booklovesmusic.com/favlists/chkfav/",
        success: function (data) {  // (6) データ取得に成功した場合の処理を定義します。
			console.log(url);
        }
    });
}


//お問い合わせ送信
function submitContact(text) {
    $.ajax({
        dataType: "jsonp",    // (3) データ形式はJSONPを指定します。
        data: {               // (4) リクエストパラメータを定義します。
            "text": text
        },
        cache: true,          // (5) キャッシュを使用します。
        url: "http://booklovesmusic.com/contacts/submit_contact/",
//        url: "http://cahier-sauvage.boo.jp/blm_dev/contacts/submit_contact/",
    });
}


