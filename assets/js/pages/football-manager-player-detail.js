$(document).on('click', ".btn-player-detail", function (e) {
    e.preventDefault();
    const playerDetailBackdrop = $("#playerDetailBackdrop");
    const playerName = $(this).data('player-name');
    const playerNationality = $(this).data('player-nationality');
    const playerAttributes = $(this).data('player-attributes');
    const playerMeta = $(this).data('player-meta');
    playerDetailBackdrop.find("#playerName").empty();
    playerDetailBackdrop.find("#playerNationality").empty();
    playerDetailBackdrop.find("#playerMeta").empty();
    playerDetailBackdrop.find("#playerAttributes").empty();
    playerDetailBackdrop.find("#playerName").text(playerName);
    playerDetailBackdrop.find("#playerNationality").text(playerNationality);
    playerDetailBackdrop.find("#playerMeta").text(playerMeta);
    let playerAttrContent = '';
    Object.keys(playerAttributes).forEach(function (key) {
        playerAttrContent += `<div class="col-4">
                            <h6 class="card-title flex-grow-1 mb-3 fs-15 text-capitalize">${key}</h6>
                            <table class="table table-borderless mb-0">
                                <tbody>`;

        Object.keys(playerAttributes[key]).forEach(function (attr) {
            playerAttrContent += `<tr>
                                            <th class="ps-0 text-capitalize text-start" scope="row">${attr.replace(/_/g, ' ')} :</th>
                                            <td class="text-muted">${playerAttributes[key][attr]}</td>
                                        </tr>`;
        })

        playerAttrContent += `</tbody>
                            </table>
                        </div>`;
    })
    const playerAttrHtml = `<div class="row">${playerAttrContent}</div>`;
    playerDetailBackdrop.find("#playerAttributes").html(playerAttrHtml);
});