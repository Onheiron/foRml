var myJSON = {formName:""};

(function( $ ) {

  $.fn.toJSON = function() {
  
    if(!this.children().length) return this.val();

	var json = new Object();

	this.children('[name]').each(function(){

		name = $(this).attr('name');

		if($(this).siblings("[name="+name+"]").length){

			if(!json[name]) json[name] = [];

			json[name].push($(this).toJSON());

		}else if($(this).children('[name]').length){

			json[name] = $(this).toJSON();

		}else{

			json[name] = $(this).val();	

		}			

	});	

	return json;

  };
})( jQuery );

function getSQLParams(json){

	json['count'] = new Array();
		
	json['value'] = new Array();
	
	json['key'] = $("[data-base=primary]").val();

	$("[data-base]").each(function(){
		
		if($(this).attr('data-base') == 'value'){
		
			json['value'].push($(this).attr('name')+":"+$(this).val());
		
		}else if($(this).attr('data-base') == 'count'){
		
			count = $("[name=" + $(this).attr('name') + "]").length;
			
			json['count'].push($(this).attr('name')+":"+count);
		
		}
	
	});
	
	return json;

}

$(document).ready(function(){

	$("form[data-detect]").submit(function(e){

		e.preventDefault();

		$(this).validate();

		myJSON.formName = $(this).attr('name');
		
		myJSON.keys = new Object();
		
		myJSON.keys = getSQLParams(myJSON.keys);

		myJSON.datas = $(this).toJSON();
		
		$.post('php/foRml.php',myJSON,function(data){alert(data);});

	});

});