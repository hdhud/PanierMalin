/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// start the Stimulus application
import './bootstrap';

let allDivs = document.querySelectorAll('.derniereListeContainer');
let done = false;

allDivs.forEach(function (div) {
    div.children[0].children[1].addEventListener('click', function (e) {
        if (div.children[1].style.display == 'none') {
            allDivs.forEach(function (div2) {
                if(div != div2){
                    if(done == true){
                        div2.style.position = 'relative';
                        div.children[1].style.display = 'flex';
                        div2.style.top = div.offsetHeight+ "px"; 
                        div.children[1].style.display = 'none';
                    }
                }else{
                    done = true;
                }
            });
            setTimeout(function () {
                div.children[1].style.display = 'flex';
                allDivs.forEach(function (div2) {
                    if( div != div2){
                        div2.style.position = 'static';
                        div2.style.top = "0";
                    }
                });
                setTimeout(function () {
                    div.children[1].style.opacity = 1;
                }, 20);
            }, 500);
            div.children[0].children[1].style.background = 'white';
            div.children[0].children[1].style.color = 'black';
            done = false;
        } else if (!div.children[1].style.display) {
            allDivs.forEach(function (div2) {
                if( div != div2){
                    if(done == true){
                        div2.style.position = 'relative';
                        div.children[1].style.display = 'flex';
                        div2.style.top = div.offsetHeight+ "px"; 
                        div.children[1].style.display = 'none';
                    }
                }else{
                    done = true;
                }
            });
            setTimeout(function () {
                div.children[1].style.display = 'flex';
                allDivs.forEach(function (div2) {
                    if( div != div2){
                        div2.style.position = 'static';
                        div2.style.top = "0";
                    }
                });
                setTimeout(function () {
                    div.children[1].style.opacity = 1;
                }, 20);
            }, 500);
            div.children[0].children[1].style.background = 'white';
            div.children[0].children[1].style.color = 'black';
            done = false;
        }
        else {
            div.children[1].style.opacity = 0;
            setTimeout(function () {
                allDivs.forEach(function (div2) {
                    if( div != div2){
                        if(done == true){
                            div2.style.position = 'relative';

                            div2.style.top = "-"+(div.offsetHeight - 78)+ "px"; 
                        }
                    }else{
                        done = true;
                    }
                });
                setTimeout(function () {
                    div.children[1].style.display = 'none';
                    allDivs.forEach(function (div2) {
                        if( div != div2){
                            div2.style.position = 'static';
                            div2.style.top = "0";
                        }
                        done = false;
                    });
                }, 500);
            }, 300);
            div.children[0].children[1].style.background = 'black';
            div.children[0].children[1].style.color = 'white';
            window.scrollTo({top: 0, behavior: 'smooth'});
        }
    });
});
