var myJSON = {formName:""};

function fetch(node,json){

	if($(node).children().length == 0){
	
		return $(node).val();
	
	}else{

		$(node).children().each(function(){
	
			if($(this).attr('name')){
	
				if($(node).children("[name="+$(this).attr('name')+"]").length > 1){
	
					if(!json[$(this).attr('name')]) json[$(this).attr('name')] = new Array();	
	
					newJSON = new Object();
					
					newJSON = fetch(this,newJSON);
	
					json[$(this).attr('name')].push(newJSON);
	
				}else if(($(this).children(':not(option)').length > 0)){
					
					json[$(this).attr('name')] = new Object();
	
					json[$(this).attr('name')] = fetch(this,json[$(this).attr('name')]);
	
				}else{
		
					json[$(this).attr('name')] = $(this).val();	
	
				}
	
			}			
	
		});	
		
	}
	
	return json;

}

function getSQLParams(json){

	json['count'] = new Array();
		
	json['value'] = new Array();
	
	json['key'] = $("[data-base=primary]").val();

	$("[data-base]").each(function(){
		
		if($(this).attr('data-base') == 'value'){
		
			json['value'].push($(this).val());
		
		}else if($(this).attr('data-base') == 'count'){
		
			count = $("[name=" + $(this).attr('name') + "]").length;
			
			json['count'].push(count);
		
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

		myJSON.datas = new Object();

		myJSON.datas = fetch(this,myJSON.datas);
		
		$.post('php/script.php',myJSON,function(data){alert(data);});

	});

});