const inp = document.getElementById('myInput');

function listAllPlayers() {
    let currentFocus
    const APIURL = "/searchPlayer/" + "?search=";
    let url = APIURL + $(this).val();

    fetch(url, {method: 'get'}).then(response => response.json()).then(results => {
        var a, b, i, val = this.value;
        closeAllLists();
        if (!val) {
            return false;
        }
        currentFocus = -1;
        a = document.createElement("DIV");
        a.setAttribute("id", this.id + "autocomplete-list");
        a.setAttribute("class", "autocomplete-items");
        this.parentNode.appendChild(a);
        for (i = 0; i < results.length; i++) {
            if (results[i].nickname.substr(0, val.length).toUpperCase() === val.toUpperCase()) {
                b = document.createElement("DIV");
                b.innerHTML = "<strong>" + results[i].nickname.substr(0, val.length) + "</strong>";
                b.innerHTML += results[i].nickname.substr(val.length);
                b.innerHTML += "<input type='hidden' value='" + results[i].nickname + "'>";
                b.addEventListener("click", function (e) {
                    inp.value = this.getElementsByTagName("input")[0].value;
                    closeAllLists();
                });
                a.appendChild(b);
            }
        }
    });
    inp.addEventListener("keydown", function (e) {
        var x = document.getElementById(this.id + "autocomplete-list");
        if (x) x = x.getElementsByTagName("div");
        if (e.code === 'ArrowDown') {
            currentFocus++;
            addActive(x);
        } else if (e.code === 'ArrowUp') {
            currentFocus--;
            addActive(x);
        } else if (e.code === 'Enter') {
            e.preventDefault();
            if (currentFocus > -1) {
                if (x) x[currentFocus].click();
            }
        }
    });

    function addActive(x) {
        if (!x) return false;
        removeActive(x);
        if (currentFocus >= x.length) currentFocus = 0;
        if (currentFocus < 0) currentFocus = (x.length - 1);
        x[currentFocus].classList.add("autocomplete-active");
    }

    function removeActive(x) {
        for (var i = 0; i < x.length; i++) {
            x[i].classList.remove("autocomplete-active");
        }
    }

    function closeAllLists(elmnt) {
        var x = document.getElementsByClassName("autocomplete-items");
        for (var i = 0; i < x.length; i++) {
            if (elmnt !== x[i] && elmnt !== inp) {
                x[i].parentNode.removeChild(x[i]);
            }
        }
    }

    document.addEventListener("click", function (e) {
        closeAllLists(e.target);
    });
}

function searchGame() {
    let currentFocus
    const APIRUL = 'https://rawg-video-games-database.p.rapidapi.com/games?search='
    let url = APIRUL + $(this).val()

    fetch(url, {
        method: 'GET',
        headers: {
            "x-rapidapi-host": "rawg-video-games-database.p.rapidapi.com",
            "x-rapidapi-key": "8cf274aff8msh36605e4b4e41c11p199bacjsnd954435e366f"
        },
        mode :'cors'
    }).then(response => response.json()).then(results => {
            var a, b, i, val = this.value;
            closeAllLists();
            if (!val) {
                return false;
            }
            currentFocus = -1;
            a = document.createElement("DIV");
            a.setAttribute("id", this.id + "autocomplete-list");
            a.setAttribute("class", "autocomplete-items");
            this.parentNode.appendChild(a);
            for (i = 0; i < results['results'].length; i++) {
                if (results['results'][i]['name'].substr(0, val.length).toUpperCase() === val.toUpperCase()) {
                    b = document.createElement("DIV");
                    b.innerHTML = "<strong>" + results['results'][i]['name'].substr(0, val.length) + "</strong>";
                    console.log(b)
                    b.innerHTML += results['results'][i]['name'].substr(val.length);
                    b.innerHTML += "<input type='hidden' value='" + results['results'][i]['name'] + "'>";
                    b.addEventListener("click", function (e) {
                        inp.value = this.getElementsByTagName("input")[0].value;
                        closeAllLists();
                    });
                    a.appendChild(b);
                }
            }
        });
    inp.addEventListener("keydown", function (e) {
        var x = document.getElementById(this.id + "autocomplete-list");
        if (x) x = x.getElementsByTagName("div");
        if (e.code === 'ArrowDown') {
            currentFocus++;
            addActive(x);
        } else if (e.code === 'ArrowUp') {
            currentFocus--;
            addActive(x);
        } else if (e.code === 'Enter') {
            e.preventDefault();
            if (currentFocus > -1) {
                if (x) x[currentFocus].click();
            }
        }
    });

    function addActive(x) {
        if (!x) return false;
        removeActive(x);
        if (currentFocus >= x.length) currentFocus = 0;
        if (currentFocus < 0) currentFocus = (x.length - 1);
        x[currentFocus].classList.add("autocomplete-active");
    }

    function removeActive(x) {
        for (var i = 0; i < x.length; i++) {
            x[i].classList.remove("autocomplete-active");
        }
    }

    function closeAllLists(elmnt) {
        var x = document.getElementsByClassName("autocomplete-items");
        for (var i = 0; i < x.length; i++) {
            if (elmnt !== x[i] && elmnt !== inp) {
                x[i].parentNode.removeChild(x[i]);
            }
        }
    }

    document.addEventListener("click", function (e) {
        closeAllLists(e.target);
    });
}
