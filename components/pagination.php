<?php

if ($count > 0) {
    $itemsPerPage = 12; // Number of results per page
    $totalItems = $count; // Total number of items
    $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page from the query string
    $totalPages = ceil($totalItems / $itemsPerPage); // Calculate total pages
    // Calculate the starting and ending entry numbers
    $startEntry = ($currentPage - 1) * $itemsPerPage + 1;
    $endEntry = min($startEntry + $itemsPerPage - 1, $totalItems);

    $prevLink = '';
    // Previous button (disabled if on the first page)
    if ($currentPage > 1) {
        $prevPage = $currentPage - 1;
        $prevLink = '<li class="page-item">
                    <a href="' . generatePageUrl(['page' => $prevPage]) . '" class="page-link">Previous</a>
                </li>';
    } else {
        $prevLink = '<li class="page-item disabled">
                    <a href="#" class="page-link">Previous</a>
                </li>';
    }

    $nextLink = '';
    // Next button (disabled if on the last page)
    if ($currentPage < $totalPages) {
        $nextPage = $currentPage + 1;
        $nextLink = '<li class="page-item">
                    <a href="' . generatePageUrl(['page' => $nextPage]) . '" class="page-link">Next</a>
                </li>';
    } else {
        $nextLink = '<li class="page-item disabled">
                    <a href="#" class="page-link">Next</a>
                </li>';
    }

    $pageLinks = '';
    // Page number links
    for ($i = 1; $i <= $totalPages; $i++) {
        if ($i == $currentPage) {
            $pageLinks .= '<li class="page-item active">
                    <a href="#" class="page-link">' . $i . '</a>
                </li>';
        } else {
            $pageLinks .= '<li class="page-item ">
                    <a href="' . generatePageUrl(['page' => $i]) . '" class="page-link">' . $i . '</a>
                </li>';
        }
    }

    echo '<div class="row g-0 text-center text-sm-start align-items-center mb-4">
        <div class="col-sm-6">
            <div>
                <p class="mb-sm-0 text-muted">Showing <span class="fw-semibold">' . $startEntry . '</span> to <span class="fw-semibold">' . $endEntry . '</span> of <span class="fw-semibold text-decoration-underline">' . $totalItems . '</span> entries</p>
            </div>
        </div>
        <!-- end col -->
        <div class="col-sm-6">
            <ul class="pagination pagination-separated justify-content-center justify-content-sm-end mb-sm-0">
                ' . $prevLink . $pageLinks . $nextLink . '
            </ul>
        </div><!-- end col -->
</div><!-- end row -->';
}
