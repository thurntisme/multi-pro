$(document).ready(function () {
    const icons = ['🍎', '🍌', '🍒', '🍇', '🍉', '🍍', '🥝', '🍑'];
    let cards = icons.concat(icons);
    let flippedCards = [];
    let matchedPairs = 0;
    let timeLeft = 30;
    let score = 0;
    let timer;

    function shuffle(array) {
        return array.sort(() => 0.5 - Math.random());
    }

    function startGame() {
        $('#game-board').empty();
        $('#score').text(score);
        cards = shuffle(cards);
        cards.forEach((icon, index) => {
            $('#game-board').append(`<div class='card m-0' data-icon='${icon}' data-index='${index}'></div>`);
        });
        $('.card').on('click', flipCard);
        startTimer();
    }

    function flipCard() {
        if (flippedCards.length < 2 && !$(this).hasClass('flipped')) {
            $(this).text($(this).data('icon')).addClass('flipped');
            flippedCards.push($(this));

            if (flippedCards.length === 2) {
                checkMatch();
            }
        }
    }

    function checkMatch() {
        if (flippedCards[0].data('icon') === flippedCards[1].data('icon')) {
            flippedCards = [];
            matchedPairs++;
            score += 10;
            $('#score').text(score);
            if (matchedPairs === icons.length) {
                clearInterval(timer);
                Swal.fire({
                    title: 'You Win! Your score: ' + score,
                    icon: 'success',
                    confirmButtonText: 'OK'
                })
            }
        } else {
            setTimeout(() => {
                flippedCards.forEach(card => card.text('').removeClass('flipped'));
                flippedCards = [];
            }, 1000);
        }
    }

    function startTimer() {
        clearInterval(timer);
        timer = setInterval(() => {
            timeLeft--;
            $('#timer').text(timeLeft);
            if (timeLeft === 0) {
                clearInterval(timer);
                Swal.fire({
                    title: 'Time is up! You Lose. Your score: ' + score,
                    icon: 'error',
                    confirmButtonText: 'OK'
                })
                $('.card').off('click');
            }
        }, 1000);
    }

    startGame();
});