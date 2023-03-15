var buttonAdd = document.getElementById('btnAjout');

buttonAdd.addEventListener('click', function(){
    
    var li = document.createElement('li');
    newIngredient = document.getElementById('inpPlats').value;

    var options = document.getElementById('platsLi').options;
    var found = false;
    for (var i = 0; i < options.length; i++) {
        if (options[i].value === newIngredient) {
            found = true;
            break;
        }
    }

    if (found) {
        li.textContent = newIngredient;
        document.getElementById('allIng').appendChild(li);
        document.getElementById('allIng').appendChild(document.createElement('br'));
    }
});