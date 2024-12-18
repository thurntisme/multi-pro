$(document).on("click", ".btn-take-inventory-item", function (e) {
    e.preventDefault();
    const item_uuid = $(this).data("item-uuid");
    const item_type = $(this).data("item-type");
    const item_slug = $(this).data("item-slug");

    if (item_type.includes("-player")) {
        // Function to fetch data from the API
        function fetchData() {
            const payload = {
                item_uuid, item_slug, item_type
            };

            try {
                $.ajax({
                    url: apiUrl + '/football-manager/inventory/item',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(payload),
                    success: function (response) {
                        if (response.data) {
                            const player_data = response.data;

                            const results = {};
                            let maxSum = 0;
                            $.each(player_data.attributes, function (category, attributes) {
                                const sum = Object.values(attributes).reduce((a, b) => a + b, 0); // Calculate sum
                                if (sum > maxSum) {
                                    maxSum = sum;
                                }
                                const totalItems = Object.keys(attributes).length;
                                const maxPossible = 120 * Object.keys(attributes).length; // Calculate max possible value
                                const percentage = (sum / maxPossible) * 100; // Calculate percentage
                                results[category] = {sum, totalItems, percentage: percentage.toFixed(2)}; // Store results
                            });
                            let count = 0;
                            const extraMaxSum = maxSum % 2 === 0 ? maxSum + 30 : maxSum + 29;
                            const playerRenderInterval = setInterval(() => {
                                if (count <= extraMaxSum) {
                                    if (count === Math.round(maxSum / 4)) {
                                        $("#player-info #player-meta").text(`${player_data.age} yrd | ${player_data.height} cm | ${player_data.weight} kg`);
                                    } else if (count === Math.round(maxSum / 2)) {
                                        $("#player-info #player-nationality").text(player_data.nationality || "");
                                    } else if (count === Math.round(maxSum * 3 / 4)) {
                                        $("#player-info #player-best_position").text(
                                            player_data.best_position ? `${player_data.best_position}` : ""
                                        );
                                        $("#player-info #player-ability").text(player_data.ability ? `(${player_data.ability})` : "");
                                        $("#player-info #player-playable_positions").text(
                                            player_data.playable_positions
                                                ? player_data.playable_positions.join(", ")
                                                : ""
                                        );
                                    } else if (count === extraMaxSum) {
                                        $("#player-info #player-name").text(player_data.name || "");
                                    }

                                    if (count < maxSum) {
                                        Object.keys(results).forEach((key) => {
                                            const sum = results[key].sum;
                                            const totalItems = results[key].totalItems;
                                            if (count < sum) {
                                                const percentage = ((count / (120 * totalItems)) * 100).toFixed(2);
                                                $(`#${key}-label`).text(count);
                                                $(`#${key}-value`).attr("style", `width: ${percentage}%`);
                                                $(`#${key}-value`).attr("aria-valuenow", percentage);
                                                $(`#${key}-value`).attr("aria-valuemax", percentage);
                                            }
                                        });
                                    }
                                    count++;
                                } else {
                                    clearInterval(playerRenderInterval);
                                }
                            }, 10);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error:', error);
                    },
                });
            } catch (error) {
                console.error('Error fetching data:', error);
            }
        }

        fetchData();

        $('#inventoryItemModal').on('hidden.bs.modal', function () {
            console.log('Modal closed');
            $("#player-info #player-nationality").html("&nbsp;");
            $("#player-info #player-meta").html("&nbsp;");
            $("#player-info #player-best_position").html("&nbsp;");
            $("#player-info #player-playable_positions").text("");
            $("#player-info #player-name").html("&nbsp;");
            $("#player-info #player-ability").text("");
            ["mental", "physical", "technical", "goalkeeping"].forEach((key) => {
                $(`#${key}-label`).text(0);
                $(`#${key}-value`).attr("style", `width: ${0}%`);
                $(`#${key}-value`).attr("aria-valuenow", 0);
                $(`#${key}-value`).attr("aria-valuemax", 0);
            });
        });
    }
});

