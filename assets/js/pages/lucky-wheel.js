$(document).ready(function () {
    var canvas = document.getElementById("wheelCanvas");
    var ctx = canvas.getContext("2d");
    var segments = [];
    var colors = [
        "#FF5733", "#33FF57", "#3357FF", "#FF33A8", "#8B0000",
        "#228B22", "#00008B", "#8B008B", "#FFA500", "#FF4500",
        "#9400D3", "#006400", "#B22222", "#1E90FF", "#FF1493",
        "#4B0082", "#2E8B57", "#8B4513", "#483D8B", "#DC143C"
    ];
    var startAngle = 0;
    var spinTime = 0;
    var spinTimeTotal = 0;

    function drawWheel() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        var totalSegments = segments.length;
        var arc = (2 * Math.PI) / totalSegments; // Dynamically adjust arc size

        for (var i = 0; i < totalSegments; i++) {
            var angle = startAngle + i * arc;
            ctx.fillStyle = colors[i % colors.length]; // Use colors cyclically if more segments exist
            ctx.beginPath();
            ctx.moveTo(200, 200);
            ctx.arc(200, 200, 200, angle, angle + arc, false);
            ctx.lineTo(200, 200);
            ctx.fill();
            ctx.save();

            // Draw text
            ctx.fillStyle = "white";
            ctx.font = "bold 14px Arial";
            var textAngle = angle + arc / 2;
            var textX = 200 + Math.cos(textAngle) * 140; // Adjust position dynamically
            var textY = 200 + Math.sin(textAngle) * 140;

            ctx.translate(textX, textY);
            ctx.rotate(textAngle + Math.PI / 2); // Rotate text to align with segment
            ctx.fillText(segments[i], -ctx.measureText(segments[i]).width / 2, 0);
            ctx.restore();
        }
    }


    function rotateWheel() {
        if (segments.length < 2) {
            Swal.fire({
                title: "No items",
                text: "Please add items to the wheel",
                icon: "warning"
            });
        } else {
            var spinAngleStart = Math.random() * 10 + 10;
            spinTime = 0;
            spinTimeTotal = Math.random() * 3000 + 4000;
            rotateWheelAnimation(spinAngleStart);
        }
    }

    function rotateWheelAnimation(spinAngleStart) {
        spinTime += 30;
        if (spinTime >= spinTimeTotal) {
            stopRotateWheel();
            return;
        }
        var spinAngle = spinAngleStart - easeOut(spinTime, 0, spinAngleStart, spinTimeTotal);
        startAngle += (spinAngle * Math.PI) / 180;
        drawWheel();
        requestAnimationFrame(function () {
            rotateWheelAnimation(spinAngleStart);
        });
    }

    function stopRotateWheel() {
        var degrees = (startAngle * 180) / Math.PI % 360;
        var segmentIndex = Math.floor((segments.length * degrees) / 360);
        const won_item = segments[segments.length - 1 - segmentIndex];
        const wheel_title = $("input[name='title']").val().trim() ? $("input[name='title']").val().trim() : "Lucky Wheel";
        Swal.fire({
            title: wheel_title,
            text: won_item,
            icon: "success",
            showCancelButton: !1,
            customClass: {
                confirmButton: "btn btn-primary w-xs",
            },
            buttonsStyling: !1,
            showCloseButton: !1
        })
    }

    function easeOut(t, b, c, d) {
        var ts = (t /= d) * t;
        var tc = ts * t;
        return b + c * (tc + -3 * ts + 3 * t);
    }

    drawWheel();

    $("#spinBtn").on("click", function () {
        rotateWheel();
    });

    $("#lucky-wheel-container form").on("submit", function (e) {
        e.preventDefault();
        const keyword = $(this).find("input[name='keyword']").val();
        if (keyword.trim() === "") {
            Swal.fire({
                title: "Invalid input",
                text: "Please enter a valid keyword",
                icon: "warning"
            });
        } else {
            if (segments.includes(keyword)) {
                Swal.fire({
                    title: "Duplicate item",
                    text: "Please enter a unique keyword",
                    icon: "warning"
                });
            } else {
                $("#list").append(`<li class="list-group-item d-flex justify-content-between align-items-center">${keyword} <button class="btn btn-sm btn-danger btn-remove me-1"><i class="ri-delete-bin-5-line"></i></button></li>`);
                $(this).find("input[name='keyword']").val("");
                segments.push(keyword);
                drawWheel();
                $("#list li").each(function () {
                    $(this).find(".btn-remove").on("click", function () {
                        $(this).parent().remove();
                        segments = segments.filter(item => item !== $(this).closest("li").text().trim());
                        console.log(segments);
                        drawWheel();
                    });
                });
            }
        }
    });
});
