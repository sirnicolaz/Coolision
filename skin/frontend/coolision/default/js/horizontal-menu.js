function changeTab(tabName, foreActive, foreInactive, backActive, backInactive){
						for(var j=0; j<document.getElementById('menuContainer').getElementsByTagName('li').length; j++){
							var menuTab = document.getElementById('menuContainer').getElementsByTagName('li')[j];
							if(menuTab.id==tabName){
								document.getElementById('menuContainer').getElementsByTagName('li')[j].class='active';
								document.getElementById('menuContainer').getElementsByTagName('li')[j].getElementsByTagName('a')[0].style.color= foreActive;
								document.getElementById('menuContainer').getElementsByTagName('li')[j].style.background = backActive;
							}
							else{ 
								document.getElementById('menuContainer').getElementsByTagName('li')[j].class='inactive';
								document.getElementById('menuContainer').getElementsByTagName('li')[j].getElementsByTagName('a')[0].style.color= foreInactive;
								document.getElementById('menuContainer').getElementsByTagName('li')[j].style.background = backInactive;
	
							}
						}
						for(var k=0; k<document.getElementById('tabPanels').getElementsByTagName('div').length; k++){
							var panelClass = document.getElementById('tabPanels').getElementsByTagName('div')[k].className;
							var panelId = document.getElementById('tabPanels').getElementsByTagName('div')[k].id;
							if(panelClass=='tabInactive' || panelClass=='tabActive'){
								if(panelId == tabName+'Tab'){
									document.getElementById('tabPanels').getElementsByTagName('div')[k].style.display='block';
								}
								else{
									document.getElementById('tabPanels').getElementsByTagName('div')[k].style.display='none';
								}
							}
						}
}