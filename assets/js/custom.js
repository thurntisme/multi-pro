$(document).on('click', '.btn-delete-record', function (e) {
    e.preventDefault();
    const form = $(this).closest('form');
    if (form) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: !0,
            customClass: {
                confirmButton: 'btn btn-primary w-xs me-2 mt-2',
                cancelButton: 'btn btn-danger w-xs mt-2',
            },
            confirmButtonText: 'Yes, delete it!',
            buttonsStyling: !1,
            showCloseButton: !0
        })
            .then(function (t) {
                t.value && form.submit();
            })
    }
})

$(document).on('click', '.btn-confirm-action', function (e) {
    e.preventDefault();
    const text = $(this).data('confirm-text');
    const form = $(this).closest('form');
    if (form) {
        Swal.fire({
            title: 'Are you sure?',
            text: text,
            icon: 'warning',
            showCancelButton: !0,
            customClass: {
                confirmButton: 'btn btn-primary w-xs me-2 mt-2',
                cancelButton: 'btn btn-danger w-xs mt-2',
            },
            confirmButtonText: 'Yes, confirm it!',
            buttonsStyling: !1,
            showCloseButton: !0
        })
            .then(function (t) {
                t.value && form.submit();
            })
    }
})