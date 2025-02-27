$(document).ready(function() {
    const symbols = ['🍒', '🍋', '🔔', '⭐', '🍉'];
    $('.spin-btn').click(function() {
        $(this).find('.spinner-border').removeClass('d-none');
        $('.message').text('');
        $('.reel').each(function() {
            $(this).addClass('border-0');
        });
        let results = [];
        function spinReel(index) {
            if (index >= $('.reel').length) {
                setTimeout(() => {
                    if (results[0] === results[1] && results[1] === results[2]) {
                        $('.message').text('🎉 You Win! 🎉');
                    } else {
                        $('.message').text('❌ Try Again!');
                    }
                    $('.spin-btn').find('.spinner-border').addClass('d-none');
                }, 500);
                return;
            }
            let reel = $('.reel').eq(index);
            let interval = setInterval(() => {
                reel.text(symbols[Math.floor(Math.random() * symbols.length)]);
            }, 100);
            setTimeout(() => {
                clearInterval(interval);
                let finalSymbol = symbols[Math.floor(Math.random() * symbols.length)];
                results.push(finalSymbol);
                reel.text(finalSymbol);
                reel.removeClass('border-0');
                spinReel(index + 1);
            }, 1000);
        }
        spinReel(0);
    });
});