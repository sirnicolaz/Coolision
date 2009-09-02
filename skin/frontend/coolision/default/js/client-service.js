function changeContent(menuName, selection){
	//var currentContent = document.getElementById('contentsContainer').getElementById(menuName);
	var exit=false;
	for(var j=0; j<document.getElementById('contentsContainer').getElementsByTagName('div').length && !exit; j++){
		if(document.getElementById('contentsContainer').getElementsByTagName('div')[j].id == menuName){
			for(var i=0; i<document.getElementById('contentsContainer').getElementsByTagName('div')[j].getElementsByTagName('div').length; i++){
				if((i+1)==selection){
					document.getElementById('contentsContainer').getElementsByTagName('div')[j].getElementsByTagName('div')[i].style.display='block';
				}
				else{
					document.getElementById('contentsContainer').getElementsByTagName('div')[j].getElementsByTagName('div')[i].style.display='none';
				}
			}
			exit=true;
		}
	}
}