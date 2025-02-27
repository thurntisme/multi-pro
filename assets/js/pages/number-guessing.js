$(document).ready(function() {
    let randomNumber = Math.floor(Math.random() * 100) + 1; // Example: 64
    let attempts = 5;
    let min = 1;
    let max = 100;

    $("#checkGuess").click(function() {
        let guess = parseInt($("#userGuess").val());

        if (isNaN(guess) || guess < min || guess > max) {
            $("#message").text(`⚠️ Enter a number between ${min} and ${max}.`);
            return;
        }

        attempts--;

        if (guess === randomNumber) {
            $("#message").html(`🎉 Correct! The number was ${randomNumber}.`);
            $("#checkGuess").hide();
            $("#restart").show();
        } else {
            if (guess < randomNumber) {
                min = guess;
            } else {
                max = guess;
            }
            $("#min").text(min);
            $("#max").text(max);
            $("#message").html(`🔍 The number is between ${min} and ${max}. You have ${attempts} attempts left.`);
        }

        if (attempts === 0 && guess !== randomNumber) {
            $("#message").html(`❌ Game Over! The number was ${randomNumber}.`);
            $("#checkGuess").hide();
            $("#restart").show();
        }
    });

    $("#restart").click(function() {
        randomNumber = Math.floor(Math.random() * 100) + 1;
        attempts = 5;
        min = 1;
        max = 100;
        $("#min").text(min);
        $("#max").text(max);
        $("#message").text("");
        $("#userGuess").val("");
        $("#checkGuess").show();
        $("#restart").hide();
    });
});