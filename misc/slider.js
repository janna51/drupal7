var $ = jQuery;
var slider = Object({
	current:0,
	auto:true,
	slider:null,
	initialize:function(_selector){
		slider.slider = $(_selector);
		slider.slider.wrap('<div class="slider-wrapper">');
		$(".slider-wrapper").css({'position':"relative", 'overflow':'hidden', 'width':'612px', 'height':'275px'});
		slider.slider.css({"position":"absolute", "width":10000, "left":0});
		slider.slider.find(".img").css({'float':'left', 'margin-right':15});
		slider.slider.find("img").not(".slider-calendar").css({'display':'block'});
		slider.slider.find(".img").each(function(){
			$(".wellesley-nav").append('<a href=#>&bull;</a>');
		});
		
		var last = slider.slider.find(".img:last").clone();
		var first = slider.slider.find(".img:first").clone();
		
		slider.slider.prepend(last);
		slider.slider.append(first);
		
		slider.slider.find(".img").click(function(){
			slider.auto = false;
			var i = $(".slider-wrapper .img").index($(this));			
			slider.show(i - 1);
		});
		$(".wellesley-nav a").hover(function(){
			slider.auto = false;
		});
		$(".wellesley-nav a").click(function(){
			var i = $(".wellesley-nav a").index($(this));
			slider.show(i);
			return false;
		});
		$(".wellesley-nav a:eq(0)").click();
		setTimeout(slider.autonext, 6000);
	},
	start:function(){
		$(".wellesley-nav a:eq(0)").click();
		slider.autonext();
	},
	autonext:function(){
		if(slider.auto){
			slider.show(slider.current);
			setTimeout(slider.autonext, 6000);
		}
	},
	show:function(_i){
		
		
		var size = slider.slider.find(".img").size()-2;
		
		if(_i == -1)
			_i = size-1;
		
		if(_i == (size))
			_i = 0;
		
		_i++;
		slider.current = _i;
		var current = slider.slider.find(".img:eq("+_i+")");
		if(current.hasClass("current")){
			if(current.find("a").size()>0){
				window.location.href = current.find("a:first").attr("href");
			}
			return false;
		}
			
		$("div.caption").hide();
		slider.slider.find(".img").removeClass("current");
		
		
			
		$(".wellesley-nav").find("a").removeClass("active");
		$(".wellesley-nav").find("a:eq("+(_i-1)+")").addClass("active");
		var left = current.position().left;
		var width = current.width();
		slider.slider.find(".img:eq("+_i+")").addClass("current");
		
		slider.slider.stop().animate({"left":-(left-306+width/2)}, function(){
			current.find("div.caption").fadeIn(300);
		});
	}
});

slider.initialize(".wellesley-slider");