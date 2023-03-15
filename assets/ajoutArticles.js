var buttonAdd = document.getElementById('btnAjout');

buttonAdd.addEventListener('click', function(){
    
    var li = document.createElement('li');
    var prix = document.createElement('p');
    li.innerHTML += ' <button onclick="this.parentNode.remove()">-</button>';
    newIngredient = document.getElementById('inpPlats').value;

    var options = document.getElementById('platsLi').options;
    var found = false;
    for (var i = 0; i < options.length; i++) {
        if (options[i].value === newIngredient) {
            found = true;
            prix = options[i].getAttribute('prix');
            break;
        }
    }

    if (found) {
        li.appendChild(document.createTextNode(newIngredient))
        var input = document.createElement('input');
        var checkbox = document.createElement('input');
        var total = document.createElement('p');

        input.type = "number";

        input.addEventListener('change', function(){
            total.innerHTML = "Total : " + (input.value * prix).toFixed(3);
            var totalAc = parseFloat(document.getElementById("totalNum").innerHTML);
            document.getElementById("totalNum").innerHTML = parseFloat(totalAc + input.value * prix);
        });

        checkbox.type = "checkbox";
        input.innerHTML = "5";

        li.appendChild(document.createTextNode(", " + prix + "€ | Quantité :"));
        li.appendChild(input);
        li.appendChild(checkbox);
        li.appendChild(total);
        document.getElementById('allIng').appendChild(li);
    }
});