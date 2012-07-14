var myJSO = {formName:""};

(function( $ ) {

	$.fn.toJSO = function() {

		if(!this.children('[name]').length) return this.val();

		var jso = new Object();

		this.children('[name]').each(function(){

			var name = $(this).attr('name');
			var type = $(this).attr('type');

			if($(this).siblings("[name="+name+"]").length){

				if( type == 'checkbox' && !$(this).prop('checked')) return true;
				if( type == 'radio' && !$(this).prop('checked')) return true;

				if(!jso[name]) jso[name] = [];

				jso[name].push($(this).toJSO());

			}else{

				jso[name] = $(this).toJSO();

			}		

		});	

		return jso;
	};

	$.fn.grabSQL = function(){

		json = new Object();

		json['count'] = new Array();
		
		json['value'] = new Array();
	
		json['key'] = this.children("[data-base=primary]").val();

		this.children("[data-base]").each(function(){
		
			if($(this).attr('data-base') == 'value'){
		
				json['value'].push($(this).attr('name')+":"+$(this).val());
		
			}else if($(this).attr('data-base') == 'count'){
		
				count = $("[name=" + $(this).attr('name') + "]").length;
			
				json['count'].push($(this).attr('name')+":"+count);
		
			}
	
		});
	
		return json;

	};


})( jQuery );

$(document).ready(function(){

	$("form[data-detect]").submit(function(e){

		e.preventDefault();

		myJSO.formName = $(this).attr('name');

		myJSO.keys = $(this).grabSQL();

		myJSO.datas = $(this).toJSO();
		
		$.post('php/foRml.php',myJSO,function(data){alert(data);});

	});

});