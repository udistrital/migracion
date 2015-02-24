// JavaScript Document
/*
	url: http://hector2c.wordpress.com
	autor: héctor alfredo chiguay copa
	msn: hector2c@live.com
	
	ejecutando:
		$("#tab").tab();

	html:
        <div id="tab" class="tab">
            <ul>
                <li><a href="#tab1">Internet</a></li>
                <li><a href="#tab2">Wap</a></li>
                <li><a href="#tab3">jQuery</a></li>
            </ul>
            <div id="tab1">parrafo 1</div>
            <div id="tab2">parrafo 2</div>
            <div id="tab3">parrafo 3</div>
			<div class='pie'>esto es OPCIONAL</div>
		</div>

*/

jQuery.fn.tab = function() {
	var div = jQuery(this);
	$(div).find("div:not(.pie)").hide().eq(0).show();
	$(div).find("ul li a").eq(0).addClass('selected');
	$(div).find("ul li a").click(function(){
		$(div).find("ul li a").removeClass('selected');
		$(this).addClass('selected');
		$(div).find("div:not(.pie)").hide();
		
		$( $(this).attr('href') ).show();
//		$( $(this).attr('href') ).fadeIn();

	});
}