$(document).ready(function(){
    var globalthat;
    var globalparentname;
	$('a.modal-del ').on('click' , function(){
		var that1 = $(this);
                globalthat = that1;
                globalparentname = that1.parent().parent();
		var srch = screen.height;
		var srcw = screen.width;
		var form = $('div.delete');
		Ph = ((srch/2)-(form.outerHeight()/2))-70;
		Pw = (srcw/2)-(form.outerWidth()/2);
		$('div#background').fadeIn('500');	
			form.delay(500).animate({
				'top' : Ph ,
				'left' : Pw
			} , 0).css('position' , 'fixed').fadeIn(250);
		// $('form#delete').bind('submit' , function(e){
                

	});
        $('a.modal-add ').on('click' , function(){
		var that = $(this);
                globalthat = that;
                globalparentname = that.parent().parent();
		var srch = screen.height;
		var srcw = screen.width;
		var form = $('div.add');
		Ph = ((srch/2)-(form.outerHeight()/2))-70;
		Pw = (srcw/2)-(form.outerWidth()/2);
		$('div#background').fadeIn('500');	
			form.delay(500).animate({
				'top' : Ph ,
				'left' : Pw
			} , 0).css('position' , 'fixed').fadeIn(250);
		// $('form#delete').bind('submit' , function(e){
                

	});
        $(".yes").click(function (){
		 	//e.preventDefault();
                        var jsondata = {
                            'dir': globalthat.data('dir'),
                            'pass': globalthat.data('pass'),
                            'rowid' : globalthat.data('rowid')
                        };
                        console.log(jsondata);
                        $.post('log.php' , jsondata , function(response){
                            console.log(response);
                            globalparentname.remove();
                            });
                            closepopup();
                        });
                        $(".yes-add").click(function (){
		 	//e.preventDefault();
                       
                        var ID = $('input.add').val();
                        var jsondata = {
                            'dir': globalthat.data('dir'),
                            'pass': globalthat.data('pass'),
                            'rowid' : globalthat.data('rowid'),
                            'id' : ID
                        };
                       // console.log(jsondata);
                        $.post('log.php' , jsondata , function(response){
                           var image = $('<img></img>');
                           var img = $('<img id="dynamic" width="50">');
                           var src;//Equivalent: $(document.createElement('img'))
                           $.each(response, function(i, object) {
                            src = object.image;
                            });
                            console.log(response);
                            console.log(src);
                            img.attr('src', src);
                            
                           ////.attr("src",response.image);
//                           ,{
//                                src: response.image
//                            });
                           // console.log(image);
			   var chokh = globalparentname.children("td").eq(1);
                           img.appendTo(chokh);
                            //console.log(response);
                            });
                            closepopup();
                        });
                        $("div#background , a.close , button.no" ).on('click' , function(){
                            closepopup();
                        });
                        function closepopup(){
                        $('div.custom').fadeOut('400' , function(){
                            $('div#background').fadeOut(500);
                            });
                        };
                        
//		 	
		 });
	



	


