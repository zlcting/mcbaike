<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<title>宅宠百科</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link type="text/css" rel="stylesheet" href="/baike/Public/css/all.css" />
<script type="text/javascript" src="/baike/Public/js/jquery-1.4.4.js"></script>
</head>

<body>
<div class="header">
  <div class="logo">
    <div class="bk-zc fr"><img src="/baike/Public/images/icon.png" class="fl"><a href="#">论坛进入</a><span><a href="#">注册</a>| <a href="#">登录</a></span><img src="/baike/Public/images/search.png"></div>
    <img src="/baike/Public/images/logo.png"> </div>
  <div class="wrap"> 
    <!--banner s-->
    <div class="v_out v_out_p">
      <div class="v_show">
        <div class="v_cont">
          <ul>
            <li index="0"><img src="/baike/Public/images/banner1.jpg">
              <div class="bj-tit">观赏鱼-A</div>
            </li>
            <li index="1"><img src="/baike/Public/images/banner1.jpg">
              <div class="bj-tit">观赏鱼-A</div>
            </li>
            <li index="2"><img src="/baike/Public/images/banner1.jpg">
              <div class="bj-tit">观赏鱼-A</div>
            </li>
            <li index="3"><img src="/baike/Public/images/banner1.jpg">
              <div class="bj-tit">观赏鱼-A</div>
            </li>
            <li index="4"><img src="/baike/Public/images/banner1.jpg">
              <div class="bj-tit">观赏鱼-A</div>
              ]</li>
            <li index="5"><img src="/baike/Public/images/banner1.jpg">
              <div class="bj-tit">观赏鱼-A</div>
            </li>
            <li index="6"><img src="/baike/Public/images/banner1.jpg">
              <div class="bj-tit">观赏鱼-A</div>
            </li>
          </ul>
        </div>
      </div>
      <ul class="circle">
        <li class="circle-cur"><img src="/baike/Public/images/banner1_l.jpg"></li>
        <li><img src="/baike/Public/images/banner2_l.jpg"></li>
        <li><img src="/baike/Public/images/banner3_l.jpg"></li>
        <li><img src="/baike/Public/images/banner4_l.jpg"></li>
        <li><img src="/baike/Public/images/banner5_l.jpg"></li>
        <li><img src="/baike/Public/images/banner6_l.jpg"></li>
        <li style="margin-right:0"><img src="/baike/Public/images/banner7_l.jpg"></li>
      </ul>
    </div>
    <script type="text/javascript">
  
		 $(function(){

				/*======next======*/
				$(".next a").click(function(){ nextscroll() });

				function nextscroll(){

						var vcon = $(".v_cont ");
						var offset = ($(".v_cont li").width())*-1;
						
						vcon.stop().animate({left:offset},"slow",function(){

							 var firstItem = $(".v_cont ul li").first();
							 vcon.find("ul").append(firstItem);
							 $(this).css("left","0px");

							 circle()
							
						});  
					
				};


				function circle(){
					  
						var currentItem = $(".v_cont ul li").first();
						var currentIndex = currentItem.attr("index");

						$(".circle li").removeClass("circle-cur");
						$(".circle li").eq(currentIndex).addClass("circle-cur");
						
				}

				//setInterval(nextscroll,2000)
				 
				/*======prev======*/
				$(".prev a").click(function(){

						var vcon = $(".v_cont ");
						var offset = ($(".v_cont li").width()*-1);

						var lastItem = $(".v_cont ul li").last();
						vcon.find("ul").prepend(lastItem);
						vcon.css("left",offset);
						vcon.animate({left:"0px"},"slow",function(){
							 circle()
						})

				 });

			   /*======btn====circle======*/
				 var animateEnd = 1;

				$(".circle li").click(function(){

					   if(animateEnd==0){return;}

					   $(this).addClass("circle-cur").siblings().removeClass("circle-cur");
					
						var nextindex = $(this).index();
						var currentindex = $(".v_cont li").first().attr("index");
						var curr = $(".v_cont li").first().clone();
						
						if(nextindex > currentindex){

								for (var i = 0; i < nextindex - currentindex; i++) {
									 
									 var firstItem = $(".v_cont li").first();
									 $(".v_cont ul").append(firstItem); 
										
								}

								$(".v_cont ul").prepend(curr);

								var offset = ($(".v_cont li").width())*-1;

								if(animateEnd==1){

									animateEnd=0;	
									$(".v_cont").stop().animate({left:offset},"slow",function(){

											$(".v_cont ul li").first().remove();
											$(".v_cont").css("left","0px");
											animateEnd=1;

									}); 

								} 

						}else{

								var curt = $(".v_cont li").last().clone();

								for (var i = 0; i < currentindex - nextindex; i++) {
									 var lastItem = $(".v_cont li").last();
									 $(".v_cont ul").prepend(lastItem);
								}

								$(".v_cont ul").append(curt);

								var offset = ($(".v_cont li").width())*-1;

								$(".v_cont").css("left",offset);
										

								  if(animateEnd==1){

										animateEnd=0;	
										$(".v_cont").stop().animate({left:"0px"},"slow",function(){
											
											$(".v_cont ul li").last().remove();
											animateEnd=1;
										  
										}); 

									} 
							
							}

				});

		 })
  
</script> 
    <!--banner e--> 
    <!--zcbk s-->
    <div class="zcbk w1136">
      <div class="nav20"></div>
      <div class="nav40"></div>
      <img src="/baike/Public/images/sy-zcbk.png">
      <div class="nav20"></div>
      <div class="nav40"></div>
      <ul class="zc_zs">
        <li><img src="/baike/Public/images/sy-bk1.jpg">
          <div class="nav20"></div>
          <a href="#">观赏鱼</a></li>
        <li><img src="/baike/Public/images/sy-bk2.jpg">
          <div class="nav20"></div>
          <a href="#">宠物龟</a></li>
        <li><img src="/baike/Public/images/sy-bk3.jpg">
          <div class="nav20"></div>
          <a href="#">水草</a></li>
        <li style="margin-right:0"><img src="/baike/Public/images/sy-bk4.jpg">
          <div class="nav20"></div>
          <a href="#">鱼缸设备</a></li>
      </ul>
    </div>
    <!--zcbk e--> 
  </div>
  <!--footer s-->
  <div class="footer">
    <div class="w1136">
      <div class="f-dh">
      	<div class="fdh1">&nbsp;&nbsp;&nbsp;<img src="/baike/Public/images/logo.png"><br><div class="nav20"></div>宠物百科 专注为爱好者服务</div>
        <div class="fdh2">产品<a href="#">宅宠社区</a><a href="#">宅宠商城</a><a href="#">鱼友交易</a></div>
        <div class="fdh2">联系<a href="#">关于我们</a><a href="#">商业合作</a><a href="#">联系我们</a></div>
        <div class="fdh2">其他<a href="#">隐私版权</a><a href="#">免责申明</a><a href="#">网站地图</a><a href="#">触屏版</a></div>
        <div class="fdh3"><li><img src="/baike/Public/images/2wm_1.jpg"><div class="nav10"></div>鱼邻APP</li><li><img src="/baike/Public/images/2wm_2.jpg"><div class="nav10"></div>宅宠百科微信公众号</li><div class="nav10"></div><a href="#">宅宠百科官方群：xxxxxxxxxxx</a></div>
      </div>
      <div class="nav40"></div>
      <p>Copyright ©2017-2024 zhaichong.cc.All Right Reserved. 宅宠@版权所有<br>
宅宠百科（粤ICP备xxxxxxxx 号－x）       粤公网安备 XXXXXXXXXXXX号  增值电信业务经营许可证编号：粤 XX-XXXXXXXX</p>
    </div>
  </div>
  
  <!--footer e--> 
</div>
</body>
</html>