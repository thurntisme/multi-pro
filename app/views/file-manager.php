<?php
$pageTitle = "File Manager";

$folderPath = "assets/uploads/";
$files = scandir($folderPath);
$files = array_diff($files, array('.', '..'));
// Sort files by modified time (newest first)
usort($files, function ($a, $b) use ($folderPath) {
    $fileA = $folderPath . DIRECTORY_SEPARATOR . $a;
    $fileB = $folderPath . DIRECTORY_SEPARATOR . $b;

    return filemtime($fileB) - filemtime($fileA); // Compare the modified times, reverse for descending order
});
function formatFileSize($size)
{
    // Convert size to GB
    if ($size >= 1073741824) { // 1 GB = 1073741824 bytes
        return number_format($size / 1073741824, 2) . ' GB';
    } elseif ($size >= 1048576) { // 1 MB = 1048576 bytes
        return number_format($size / 1048576, 2) . ' MB';
    } elseif ($size >= 1024) { // 1 KB = 1024 bytes
        return number_format($size / 1024, 2) . ' KB';
    } else {
        return $size . ' Bytes';
    }
}
// Calculate total file size
$totalSize = 0;
foreach ($files as $file) {
    $filePath = $folderPath . DIRECTORY_SEPARATOR . $file;
    if (is_file($filePath)) {
        $totalSize += filesize($filePath);
    }
}

ob_start(); ?>
<!-- dropzone css -->
<link href="<?= App\Helpers\Network::home_url("assets/libs/dropzone/dropzone.css") ?>" rel="stylesheet" />
<?php
$additionCss = ob_get_clean();

ob_start();
?>

<div class="auth-page-content overflow-hidden p-0 pb-4">
    <div class="d-lg-flex gap-1">
        <div class="file-manager-sidebar">
            <div class="p-3 d-flex flex-column h-100">
                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold">My Drive</h5>
                    <button class="btn btn-success btn-sm createFile-modal" data-bs-toggle="modal"
                        data-bs-target="#createFileModal"><i class="ri-add-line align-bottom me-1"></i> Upload
                        File</button>
                </div>
                <div class="search-box">
                    <input type="text" class="form-control bg-light border-light" placeholder="Search here...">
                    <i class="ri-search-2-line search-icon"></i>
                </div>
                <div class="mt-3 mx-n4 px-4 file-menu-sidebar-scroll" data-simplebar>
                    <ul class="list-unstyled file-manager-menu">
                        <li>
                            <a data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="true"
                                aria-controls="collapseExample">
                                <i class="ri-folder-2-line align-bottom me-2"></i> <span class="file-list-link">My
                                    Drive</span>
                            </a>
                            <div class="collapse show" id="collapseExample">
                                <ul class="sub-menu list-unstyled">
                                    <li>
                                        <a href="#!">Assets</a>
                                    </li>
                                    <li>
                                        <a href="#!">Marketing</a>
                                    </li>
                                    <li>
                                        <a href="#!">Personal</a>
                                    </li>
                                    <li>
                                        <a href="#!">Projects</a>
                                    </li>
                                    <li>
                                        <a href="#!">Templates</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li>
                            <a href="#!"><i class="ri-file-list-2-line align-bottom me-2"></i> <span
                                    class="file-list-link">Documents</span></a>
                        </li>
                        <li>
                            <a href="#!"><i class="ri-image-2-line align-bottom me-2"></i> <span
                                    class="file-list-link">Media</span></a>
                        <li>
                            <a href="#!"><i class="ri-history-line align-bottom me-2"></i> <span
                                    class="file-list-link">Recent</span></a>
                        </li>
                        <li>
                            <a href="#!"><i class="ri-star-line align-bottom me-2"></i> <span
                                    class="file-list-link">Important</span></a>
                        </li>
                        </li>
                        <li>
                            <a href="#!"><i class="ri-delete-bin-line align-bottom me-2"></i> <span
                                    class="file-list-link">Deleted</span></a>
                        </li>
                    </ul>
                </div>

                <?php
                // Assuming $totalSize is already calculated
                $totalStorage = 10 * 1024 * 1024 * 1024; // Total storage (in bytes) - 10 GB
                $usedStorage = $totalSize; // Total size of files in bytes
                
                // Calculate the percentage of storage used
                $percentageUsed = round(($usedStorage / $totalStorage) * 100, 2);

                // Format the total used storage for display
                $formattedUsedStorage = formatFileSize($usedStorage);
                $formattedTotalStorage = formatFileSize($totalStorage);
                ?>

                <div class="mt-auto">
                    <h6 class="fs-11 text-muted text-uppercase mb-3">Storage Status</h6>
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ri-database-2-line fs-17"></i>
                        </div>
                        <div class="flex-grow-1 ms-3 overflow-hidden">
                            <div class="progress mb-2 progress-sm">
                                <div class="progress-bar bg-success" role="progressbar"
                                    style="width: <?php echo $percentageUsed; ?>%"
                                    aria-valuenow="<?php echo $percentageUsed; ?>" aria-valuemin="0"
                                    aria-valuemax="100"></div>
                            </div>
                            <span class="text-muted fs-12 d-block text-truncate">
                                <b><?php echo $formattedUsedStorage; ?></b> used of
                                <b><?php echo $formattedTotalStorage; ?></b>
                            </span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="file-manager-content w-100 p-3 py-0">
            <div class="mx-n3 pt-4 px-4 file-manager-content-scroll">
                <div class="d-flex align-items-center mb-3">
                    <h5 class="flex-grow-1 fs-16 mb-0" id="filetype-title">Recent File</h5>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle table-nowrap mb-0">
                        <thead class="table-active">
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col" class="text-center">File Item</th>
                                <th scope="col">File Size</th>
                                <th scope="col">Recent Date</th>
                                <th scope="col" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="file-list">
                            <?php foreach ($files as $file): ?>
                                <?php
                                $filePath = $folderPath . DIRECTORY_SEPARATOR . $file;
                                $fileSize = is_file($filePath) ? formatFileSize(filesize($filePath)) : '-';
                                $fileDate = is_file($filePath) ? date("Y-m-d H:i:s", filemtime($filePath)) : '-';
                                $fileType = pathinfo($file, PATHINFO_EXTENSION);
                                $fileUrl = home_url('assets/uploads/' . urlencode($file));
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars(truncateString($file, 50)); ?></td>
                                    <td class="text-center"><?php echo $fileType; ?></td>
                                    <td><?php echo $fileSize; ?></td>
                                    <td><?php echo $fileDate; ?></td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-info btn-copy-link"
                                            data-link="<?php echo $fileUrl; ?>"><i class="ri-links-fill"></i></button>
                                        <a href="<?php echo $fileUrl; ?>" download class="btn btn-sm btn-primary"><i
                                                class="ri-download-line"></i></a>
                                        <button class="btn btn-sm btn-danger btn-delete"
                                            data-file="<?php echo htmlspecialchars($file); ?>"><i
                                                class="ri-delete-bin-line"></i></button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="align-items-center mt-2 row g-3 text-center text-sm-start">
                    <div class="col-sm">
                        <div class="text-muted">Showing<span class="fw-semibold">4</span> of <span
                                class="fw-semibold">125</span> Results
                        </div>
                    </div>
                    <div class="col-sm-auto">
                        <ul
                            class="pagination pagination-separated pagination-sm justify-content-center justify-content-sm-start mb-0">
                            <li class="page-item disabled">
                                <a href="#" class="page-link">←</a>
                            </li>
                            <li class="page-item">
                                <a href="#" class="page-link">1</a>
                            </li>
                            <li class="page-item active">
                                <a href="#" class="page-link">2</a>
                            </li>
                            <li class="page-item">
                                <a href="#" class="page-link">3</a>
                            </li>
                            <li class="page-item">
                                <a href="#" class="page-link">→</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- START CREATE FILE MODAL -->
<div class="modal fade zoomIn" id="createFileModal" tabindex="-1" aria-labelledby="createFileModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header p-3 bg-success-subtle">
                <h5 class="modal-title" id="createFileModalLabel">Create File</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" id="addFileBtn-close"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form autocomplete="off" class="needs-validation createfile-form" id="createfile-form" novalidate>
                    <div class="mb-3">
                        <div class="dropzone">
                            <div class="fallback">
                                <input name="file" type="file" multiple="multiple">
                            </div>
                            <div class="dz-message needsclick">
                                <div class="mb-3">
                                    <i class="display-4 text-muted ri-upload-cloud-2-fill"></i>
                                </div>

                                <h4>Drop files here or click to upload.</h4>
                            </div>
                        </div>
                        <ul class="list-unstyled" id="dropzone-preview"></ul>
                    </div>
                    <div class="hstack gap-2 justify-content-end">
                        <button type="button" class="btn btn-ghost-success" data-bs-dismiss="modal"><i
                                class="ri-close-line align-bottom"></i> Close</button>
                        <button type="button" class="btn btn-info" id="resetForm">Reset</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
$pageContent = ob_get_clean();

ob_start(); ?>
<!-- dropzone min -->
<script src="<?= App\Helpers\Network::home_url("assets/libs/dropzone/dropzone-min.js") ?>"></script>
<script type="text/javascript">
    $(document).ready(function () {
        var previewTemplate, dropzone, dropzonePreviewNode;

        Dropzone.autoDiscover = false;

        const myDropzone = new Dropzone(".dropzone", {
            url: "/api/file-manager/upload",
            maxFilesize: 2,
            paramName: "file",
            previewsContainer: "#dropzone-preview",
            previewTemplate: `
        <li class="pt-3 list-group-item dz-preview dz-file-preview">
            <div class="d-flex justify-content-between align-items-center">
                <span class="dz-filename"><span data-dz-name></span></span>
                <span class="dz-size" data-dz-size></span>
                <button class="btn btn-danger btn-sm dz-remove" data-dz-remove>Remove</button>
            </div>
            <div class="progress mt-2">
                <div class="progress-bar" role="progressbar" data-dz-uploadprogress></div>
            </div>
        </li>
    `,
            dictDefaultMessage: `
        <div class="mb-3">
            <i class="display-4 text-muted ri-upload-cloud-2-fill"></i>
        </div>
        <h4>Drop files here or click to upload.</h4>
    `,
            success: function (file, response) {
                console.log("Successfully uploaded", response);
                $("#dropzone-preview .progress-bar").addClass("bg-success");
            },
            error: function (file, errorMessage) {
                console.error("Upload failed", errorMessage);
                $("#dropzone-preview .progress-bar").addClass("bg-danger");
            }
        });

        $(document).on('click', '#resetForm', function (e) {
            e.preventDefault();
            myDropzone.removeAllFiles(true);
        })

        $(document).on('click', '.btn-copy-link', function (e) {
            e.preventDefault();
            var link = $(this).data('link');
            var $temp = $('<textarea>');
            $('body').append($temp);
            $temp.val(link).select();
            document.execCommand('copy');
            $temp.remove();

            Swal.fire({
                icon: 'success',
                title: 'Link copied!',
                text: 'The link has been copied to the clipboard.',
                confirmButtonText: 'OK'
            });
        })

        $(document).on('click', '.btn-delete', function (e) {
            e.preventDefault();
            var fileName = $(this).data('file');

            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to delete the file '" + fileName + "'?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/api/file-manager/delete_file',
                        type: 'POST',
                        data: JSON.stringify({
                            file: fileName
                        }),
                        contentType: 'application/json',
                        success: function (response) {
                            if (response.status === 'success') {
                                location.href = '<?= App\Helpers\Network::home_url('app/file-manager') ?>';
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: 'There was an issue deleting the file.',
                                    confirmButtonText: 'OK'
                                });
                            }
                        },
                        error: function () {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'There was an error processing your request.',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });
        })
    });
</script>
<?php
$additionJs = ob_get_clean();
