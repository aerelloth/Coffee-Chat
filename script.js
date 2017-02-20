//fonction à utiliser lorsque le DOM est chargé :
document.addEventListener('DOMContentLoaded', function() 
{
	//alert('DOM chargé !');

    //  Sélection et suppression du lien permettant d'actualiser le chat
    var refreshLink = document.querySelector('.fa');
    refreshLink.parentNode.removeChild(refreshLink);

	setInterval(chatRefresh, 5000);    
    //rafraîchissement auto toutes les 5 secondes

    //envoi du formulaire
    var form = document.querySelector('form') ;
    form.addEventListener('submit', function(event)
    {
        event.preventDefault() ;    //annule le comportement par défaut
        var formData = new FormData(form) ; //récupère les données du formulaire
        var xhr = new XMLHttpRequest(); 
        xhr.open('POST', 'add-message.php');
        xhr.send(formData);
        xhr.addEventListener('readystatechange', function() 
        { 
            if (xhr.readyState === xhr.DONE) 
            { // Si tout s'est bien passé
                var textarea = document.querySelector('textarea');
                textarea.value = '';
                chatRefresh();
            }
        });
    }
) ;
});
/*function test()
{
	alert('Timer OK');
}*/
function chatRefresh() 
{
	var xhr = new XMLHttpRequest();	
	xhr.open('GET', 'get-messages-on-json.php');
	xhr.send();
	xhr.addEventListener('readystatechange', function() 
	{ 
        if (xhr.readyState === xhr.DONE) 
        { // Si tout s'est bien passé
    		
    		//suppression des anciens messages

    		var chat = document.querySelector('#chat');
    		while (chat.firstChild) {
				chat.removeChild(chat.firstChild);
			}
			
			//ajout des nouveaux messages

			var messages = JSON.parse(xhr.responseText);
    		//console.log(messages);
    		messages.forEach(function (message)
    		{
    			var p = document.createElement('p');
    			var textNode = document.createTextNode(message.content);
    			p.appendChild(textNode);
    			//console.log(p);

    			var h2 = document.createElement('h2');
    			var textNode = document.createTextNode(message.username);
    			h2.appendChild(textNode);

    			var h3 = document.createElement('h3');
    			var textNode = document.createTextNode(message.publishingDate);
    			h3.appendChild(textNode);

    			var id = document.createElement('div');
    			id.classList.add('id');
    			id.appendChild(h2);
    			id.appendChild(h3);

    			var message = document.createElement('div');
    			message.classList.add('message');
    			message.appendChild(id);
    			message.appendChild(p);

    			console.log(message);
    			chat.appendChild(message);
    		});
        }
    });
}