var myJSON = {formName:""};

function toJSON(node){

        if($(node).children().length == 0) return $(node).val();

	var json = new Object();

	$(node).children("[name]").each(function(){

		name = $(this).attr('name');

		if($(node).children("[name="+name+"]").length > 1){

			if(!json[name]) json[name] = [];

			json[name].push(toJSON(this));

		}else if(($(this).children(':not(option)').length > 0)){

			json[name] = toJSON(this);

		}else{

			json[name] = $(this).val();	

		}			

	});	

	return json;

}

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

		myJSON.formName = $(this).attr('name');
		
		myJSON.keys = new Object();
		
		myJSON.keys = getSQLParams(myJSON.keys);

		e.preventDefault();

		myJSON.datas = toJSON(this);
		
		$.post('php/foRml.php',myJSON,function(data){alert(data);});

	});

});