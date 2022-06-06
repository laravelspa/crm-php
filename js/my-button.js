document.addEventListener('DOMContentLoaded', function(){ 
    // your code goes here
    var buttonToForm = document.getElementsByClassName("buttonToForm");
    if(buttonToForm) {
        if(buttonToForm.length > 0) {
            for(var i=0; i < buttonToForm.length; i++) {
                var titlePage = document.title;
                var articleUrl = location.href;
                var redirectUrl = buttonToForm[i].dataset.url;
                buttonToForm[i].addEventListener('click', function() {
                    localStorage.setItem('titlePage', titlePage);
                    localStorage.setItem('articleUrl', articleUrl);
                    location.href = redirectUrl;
                })
            }
        }
    }
    
}, false);

