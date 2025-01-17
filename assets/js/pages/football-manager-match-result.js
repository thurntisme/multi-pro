$(document).on("click", "#gift-box .btn-open-gift", function () {
    const url = new URL(window.location.href);
    const item_idx = $(this).data("item-idx");
    const payload = { match_uuid: url.searchParams.get('uuid'), item_idx };
    $.ajax({
        url: apiUrl + "/football-manager/match/gift",
        method: "POST",
        contentType: "application/json",
        data: JSON.stringify({
            ...payload,
        }),
        beforeSend: function(){
            $("#gift-box .gift-item").each(function(){
                $(this).find(".text").addClass("d-none");
                $(this).find(".spinner-border").removeClass("d-none");
            });
        },
        success: function (response) {
            console.log(response);
            if (response.status === "success") {
                $("#gift-box .gift-item").each(function(idx, el){
                    if (idx !== response.data.item_idx) {
                        $(el).html(`
                            <div class="text-center">
                            <div class="text-muted fs-12 lh-1">${response.data.list[idx].ability}</div>
                                <div class="text-dark fs-16 fw-bold lh-1 mt-2">${response.data.list[idx].name}</div>
                                <div class="text-dark fs-12 lh-1 mt-2">${response.data.list[idx].best_position} | ${response.data.list[idx].playable_positions.join(", ")}</div>
                            </div>
                        `)
                    }
                });
                setTimeout(function() {
                    $(`#gift-box .gift-item:eq(${response.data.item_idx})`).html(`
                        <div class="text-center">
                        <div class="text-muted fs-12 lh-1">${response.data.list[response.data.item_idx].ability}</div>
                            <div class="text-dark fs-16 fw-bold lh-1 mt-2">${response.data.list[response.data.item_idx].name}</div>
                            <div class="text-dark fs-12 lh-1 mt-2">${response.data.list[response.data.item_idx].best_position} | ${response.data.list[response.data.item_idx].playable_positions.join(", ")}</div>
                        </div>
                    `);
                }, 2000)
            } else {
                $("#gift-box .gift-item").each(function(idx, el){
                    $(el).html(`
                        <div class="text-center">
                            <div class="text-dark fs-16 fw-bold lh-1 mt-2">Fail</div>
                        </div>
                    `)
                });
            }
        },
        error: function (xhr, status, error) {
            console.error("Error:", error);
            $("#gift-box .gift-item").each(function(idx, el){
                $(el).html(`
                    <div class="text-center">
                        <div class="text-dark fs-16 fw-bold lh-1 mt-2">Fail</div>
                    </div>
                `)
            });
        },
    });
});
