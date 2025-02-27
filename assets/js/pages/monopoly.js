$(document).ready(function () {
    let playerPosition = 0;
    let playerMoney = 1500;

    function rollDice() {
        return Math.floor(Math.random() * 6) + 1;
    }

    $("#rollDice").click(function () {
        let dice1 = rollDice();
        let dice2 = rollDice();
        let total = dice1 + dice2;

        $("#diceResult").text(`🎲 ${dice1} + 🎲 ${dice2} = ${total}`);
        console.log(`🎲 ${dice1} + 🎲 ${dice2} = ${total}`);
        movePlayer(total);
    });

    function movePlayer(steps) {
        playerPosition = (playerPosition + steps) % $(".tile").length;
        let newTile = $(".tile").eq(playerPosition);

        $(".player").remove();
        newTile.append('<div class="player">🚶</div>');
        console.log(newTile.text())

        // checkTile(newTile);
    }

    function checkTile(tile) {
        if (tile.hasClass("property") && !tile.hasClass("owned")) {
            let propertyName = tile.data("name");
            let price = tile.data("price");

            if (confirm(`Bạn có muốn mua ${propertyName} với giá ${price}$ không?`)) {
                tile.addClass("owned").css("background-color", "gold");
                tile.data("owner", "player1");
                updateMoney(-price);
            }
        } else if (tile.hasClass("tax")) {
            let amount = tile.data("amount");
            alert(`Bạn phải trả ${amount}$ tiền thuế.`);
            updateMoney(-amount);
        }
    }

    function updateMoney(amount) {
        playerMoney += amount;
        $("#playerMoney").text(playerMoney);
    }
});
