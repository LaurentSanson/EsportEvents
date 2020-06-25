const input = document.getElementById('add_player_to_team_nickname');
const div = document.getElementById('nicknames');
const selectNicknames = document.getElementById('selectNicknames');
let searchPlayer = document.getElementById('add_player_to_team_nickname').value;

input.addEventListener("keyup", listAllPlayers);

function listAllPlayers() {
    const APIURL = "/searchPlayer/" + "?search=";
    let errorMessage = $('#errorMessage');
    let url = APIURL + $(this).val();

    fetch(url, {method: 'get'}).then(response => response.json()).then(results => {
        $(input).find('option').remove();
        if (results.length) {
            $(errorMessage).text('').hide();
            $(div).show();
            $.each(results, function (key, value) {
                $(selectNicknames).append('<option value="' + value.nickname + '" name="selectedPlayer" id="searchPlayer">' + value.nickname + '</option>');
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