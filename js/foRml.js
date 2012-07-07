var myJSON = {formName:""};

function fetch(node){

	var json = new Object();

	$(node).children("[name]").each(function(){
	
		name = $(this).attr('name');

		if($(node).children("[name="+name+"]").length > 1){

			if(!json[name]) json[name] = [];

			json[name].push(fetch(this));

		}else if(($(this).children(':not(option)').length > 0)){

			json[name] = fetch(this);

		}else{

			json[name] = $(this).val();	

		}			

	});	
	
	if($(node).children().length == 0) return $(node).val();
	
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

		myJSON.datas = fetch(this);
		
		$.post('php/foRml.php',myJSON,function(data){alert(data);});

	});

});