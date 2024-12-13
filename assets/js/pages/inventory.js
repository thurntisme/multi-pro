$(document).on("click", ".btn-take-inventory-item", function (e) {
    e.preventDefault();
    const item_uuid = $(this).data("item-uuid");
    console.log(item_uuid);

    // Function to fetch data from the API
    function fetchData() {
        const payload = {
            item_uuid
        };

        try {
            $.ajax({
                url: apiUrl + '/football-manager/inventory/item',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(payload),
                success: function (response) {
                    console.log(response);
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
});