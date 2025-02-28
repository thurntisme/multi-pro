$(document).ready(function () {
    let dice1;
    let dice2;
    let playerPosition = 0;
    let playerMoney = 1500;
    let currentPlayerInJail = false;
    let jailTurns = 0;

    function rollDice() {
        return Math.floor(Math.random() * 6) + 1;
    }

    $("#rollDice").click(function () {
        $("#rollDice").find('.spinner-border').removeClass('d-none');

        setTimeout(function () {
            dice1 = rollDice();
            dice2 = rollDice();
            const total = dice1 + dice2;
            $("#diceResult").text(`🎲 ${dice1} + 🎲 ${dice2} = ${total}`);
            movePlayer(total);
            $("#rollDice").find('.spinner-border').addClass('d-none');
        }, 1200)
    });

    function movePlayer(steps) {
        let currentPos = playerPosition;
        let targetPos = (playerPosition + steps) % $(".tile").length;

        function stepAnimation() {
            if (currentPos !== targetPos) {
                $(".tile").eq(currentPos).removeClass('active');
                currentPos = (currentPos + 1) % $(".tile").length;
                $(".player").remove(); // Xóa người chơi khỏi ô hiện tại
                $(".tile").eq(currentPos).append('<div class="player">🚶</div>').addClass('active'); // Gắn vào ô mới

                setTimeout(stepAnimation, 300); // Điều chỉnh tốc độ di chuyển (300ms mỗi bước)
            } else {
                playerPosition = targetPos; // Cập nhật vị trí người chơi
                checkTile($(".tile").eq(playerPosition)); // Kiểm tra ô sau khi dừng
            }
        }

        if (currentPlayerInJail) {
            tryToEscapeJail(stepAnimation);
        } else {
            stepAnimation();
        }
    }


    function checkTile(tile) {
        if (tile.hasClass("property") && !tile.hasClass("owned")) {
            let propertyName = tile.data("name");
            let price = tile.data("price");

            setTimeout(function () {
                Swal.fire({
                    title: 'MUA TÀI SẢN',
                    text: `Bạn có muốn mua ${propertyName} với giá ${price}$ không?`,
                    icon: 'warning',
                    showCancelButton: !0,
                    customClass: {
                        confirmButton: 'btn btn-primary w-xs me-2 mt-2',
                        cancelButton: 'btn btn-danger w-xs mt-2',
                    },
                    confirmButtonText: "Đồng Ý",
                    cancelButtonText: "Không",
                    buttonsStyling: !1,
                    showCloseButton: !0
                })
                    .then(function (t) {
                        if (t.value) {
                            tile.addClass("owned").css("background-color", "gold");
                            tile.attr("data-owner", "player1");
                            updateMoney(-price);
                        }
                    })
            }, 800);
        } else if (tile.hasClass("tax")) {
            let amount = tile.data("amount");
            setTimeout(function () {
                Swal.fire({
                    title: "THUẾ",
                    text: `Bạn phải trả ${amount}$ tiền thuế.`,
                    icon: "warning"
                }).then(function (t) {
                    if (t.value) {
                        updateMoney(-amount);
                    }
                });
            }, 800);
        } else if (tile.hasClass("chance")) {
            let chanceCards = [
                { text: "Nhận $200 từ ngân hàng!", effect: () => updateMoney(200) },
                { text: "Trả $100 tiền thuế!", effect: () => updateMoney(-100) },
                { text: "Đi tới ô Bắt đầu và nhận $200!", effect: () => moveToStart() },
                { text: "Di chuyển lên 3 ô!", effect: () => movePlayer(3) },
                { text: "Đi tù ngay lập tức!", effect: () => goToJail() }
            ];

            let randomCard = chanceCards[Math.floor(Math.random() * chanceCards.length)];

            setTimeout(function () {
                Swal.fire({
                    title: "CƠ HỘI",
                    text: randomCard.text,
                    icon: "success"
                }).then(function (t) {
                    if (t.value) {
                        randomCard.effect();
                    }
                });
            }, 800);
        } else if (tile.hasClass("owned")) {
            let owner = tile.attr("data-owner"); // Lấy chủ sở hữu ô
            let rent = parseInt(tile.attr("data-rent")); // Lấy giá thuê từ data-rent
            const currentPlayer = 'player1';
            if (owner !== currentPlayer) { // Nếu ô không phải do người chơi hiện tại sở hữu
                setTimeout(function () {
                    Swal.fire({
                        title: "THÔNG BÁO",
                        text: `Bạn đang ở trên ô của ${owner}. Bạn phải trả $${rent}!`,
                        icon: "warning"
                    }).then(function (t) {
                        if (t.value) {
                            updateMoney(-rent); // Trừ tiền người chơi hiện tại
                            updateOwnerMoney(owner, rent); // Cộng tiền cho chủ sở hữu ô
                        }
                    });
                }, 800);
            } else {
                setTimeout(function () {
                    Swal.fire({
                        title: "THÔNG BÁO",
                        text: "Đây là tài sản của bạn, bạn không cần trả tiền.",
                        icon: "info"
                    });
                }, 800);
            }
        } else if (tile.hasClass("jail")) {
            setTimeout(function () {
                currentPlayerInJail = true;
                Swal.fire({
                    title: "THÔNG BÁO",
                    text: "Bạn đang ghé thăm nhà tù.",
                    icon: "warning"
                });
            }, 800);
        }
    }

    function updateMoney(amount) {
        let playerMoney = parseInt($("#playerMoney").text());
        playerMoney += amount;
        $("#playerMoney").text(playerMoney);
    }

    function updateOwnerMoney(owner, amount) {
        let ownerMoney = parseInt($(`#${owner}-money`).text());
        ownerMoney += amount;
        $(`#${owner}-money`).text(ownerMoney);
    }

    function moveToStart() {
        movePlayerImmediately(0);
        updateMoney(200);
    }

    function goToJail() {
        let jailTile = $(".tile[data-name='Nhà Tù']").index();
        movePlayerImmediately(jailTile);
    }

    function movePlayerImmediately(position) {
        let currentPos = playerPosition;
        $(".tile").eq(currentPos).removeClass('active');
        $(".player").remove();
        $(".tile").eq(position).append('<div class="player">🚶</div>').addClass('active');
        playerPosition = position;
    }

    function tryToEscapeJail(stepAnimation) {
        if (dice1 === dice2) {
            Swal.fire({
                title: "CHÚC MỪNG",
                text: "Bạn đổ đôi! Bạn được ra tù!",
                icon: "success"
            }).then(function (t) {
                if (t.value) {
                    currentPlayerInJail = false;
                    jailTurns = 0;
                    stepAnimation();
                }
            });
        } else {
            jailTurns++;
            Swal.fire({
                title: "CHIA BUỒN",
                text: `Bạn vẫn ở trong tù (${jailTurns}/3 lượt). Bạn có thể trả $50 để ra khỏi tù!`,
                icon: "error",
                showCancelButton: !0,
                customClass: {
                    confirmButton: 'btn btn-primary w-xs me-2 mt-2',
                    cancelButton: 'btn btn-danger w-xs mt-2',
                },
                confirmButtonText: "Đồng Ý",
                cancelButtonText: "Không",
                buttonsStyling: !1,
                showCloseButton: !0,
                allowOutsideClick: false
            }).then(function (t) {
                if (t.value) {
                    payToEscape(stepAnimation);
                }
            });
        }
    }

    function payToEscape(stepAnimation) {
        if (!currentPlayerInJail) return;

        let playerMoney = parseInt($("#playerMoney").text());
        if (playerMoney >= 50) {
            playerMoney -= 50;
            $("#playerMoney").text(playerMoney);
            currentPlayerInJail = false;
            jailTurns = 0;
            stepAnimation();
        } else {
            Swal.fire({
                title: "THÔNG BÁO",
                text: "Bạn không đủ tiền để trả!",
                icon: "error"
            });
        }
    }

});
