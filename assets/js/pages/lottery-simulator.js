$(document).ready(function () {
    function generateWinningNumbers() {
        let numbers = [];
        while (numbers.length < 6) {
            numbers.push(Math.floor(Math.random() * 9));
        }
        return numbers;
    }

    function getAward(matches) {
        const awards = {
            1: "$10",
            2: "$50",
            3: "$200",
            4: "$1,000",
            5: "$10,000"
        };
        return awards[matches] || "$0";
    }

    $('#btn-play').click(function () {
        $('.lottery-result').each(function (index) {
            $(this).text('0');
            $(this).removeClass('loaded');
        })
        $(this).find('.spinner-border').removeClass('d-none');
        $(this).prop('disabled', true);
        const inputNumbers = $('input[name="your_number"]').val();

        if (!/^\d{6}$/.test(inputNumbers)) {
            Swal.fire({
                icon: 'error',
                title: 'Please enter exactly 6 digits (0-9).',
                showCancelButton: false,
            })
            $(this).find('.spinner-border').addClass('d-none');
            $(this).prop('disabled', false);
            return;
        }

        let winningNumbers = generateWinningNumbers();
        let delay = 0;
        let totalElements = $('.lottery-result').length;
        let completedCount = 0;

        $('.lottery-result').each(function (index) {
            const $this = $(this);
            setTimeout(() => {
                $this.addClass('loaded');
                let interval = setInterval(() => {
                    const randomNumber = Math.floor(Math.random() * 9) + 1;
                    $this.text(randomNumber);
                }, 100);

                setTimeout(() => {
                    clearInterval(interval);
                    $this.text(winningNumbers[index]);

                    completedCount++;
                    if (completedCount === totalElements) {
                        onLotteryFinish();
                    }
                }, 4000);
            }, delay);

            delay += 6000;
        });

        function onLotteryFinish() {
            setTimeout(function () {
                Swal.fire({
                    icon: 'success',
                    title: 'Lottery Finished',
                    text: 'The winning numbers are: ' + winningNumbers.join(', '),
                    showCancelButton: false,
                })
                $('#btn-play').prop('disabled', false);
                $('#btn-play').find('.spinner-border').addClass('d-none');
            }, 1500)
        }
    });
});