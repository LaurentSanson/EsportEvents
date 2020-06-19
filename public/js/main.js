const input = document.getElementById('searchPlayer');
const div = document.getElementById('nicknames');
const selectNicknames = document.getElementById('selectNicknames');
let searchPlayer = document.getElementById('searchPlayer').value;

input.addEventListener("keyup", listAllPlayers);

function listAllPlayers() {
    const APIURL = "/searchPlayer/" + "?searchPlayer=";
    let errorMessage = $('#errorMessage');
    let url = APIURL + $(this).val();
    fetch(url, {method: 'get'}).then(response => response.json()).then(results => {
        $(input).find('option').remove();
        if (results.length) {
            $(errorMessage).text('').hide();
            $(div).show();
            $.each(results, function (key, value) {
                $(selectNicknames).append('<option value="' + value.nickname + '">' + value.nickname + '</option>');
            })
        } else {
            if ($(searchPlayer).val()) {
                $(errorMessage).text('There is no player with this nickname').show();
            } else {
                $(errorMessage).text('').hide();
            }
        }
    }).catch(err => {
        console.log(err);
        $(input).find('option').remove();
    });
}

// function listAllPlayers() {
//     const APIURL = document.location.href;
//     $.ajax({
//         type: "GET",
//         url: APIURL,
//         data: {searchPlayer: searchPlayer + $(this).val()},
//
//     })
//         .done(function (item) {
//             let select = document.createElement("select");
//             let option = document.createElement("option");
//             let text = document.createTextNode(item.searchPlayer);
//             span.innerText += item.searchPlayer;
//             // input.appendChild(select);
//             // select.appendChild(option);
//             // option.appendChild(text)
//         });
// };