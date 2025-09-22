<?php
$pageTitle = "Book View";

require_once 'controllers/BookController.php';
$bookController = new BookController();

$modify_type = getLastSegmentFromUrl();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        $post_id = $_GET['id'];
        $postData = $bookController->viewBook($post_id);
        $tags = !empty($postData['tags']) ? explode(',', $postData['tags']) : [];
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action_name'])) {
        if ($_POST['action_name'] === 'save_current_page') {
            $bookController->saveCurrentBookPage();
        }
    }
}

ob_start();
?>

<div class="row">
    <div class="col-md-10 offset-md-1">
        <?php
        ob_start();
        if (!empty($postData['view_url'])) {
            ?>
            <form method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>">
                <input type="hidden" name="action_name" value="save_current_page">
                <input type="hidden" name="post_id" value="<?= $post_id ?>">
                <input type="hidden" name="view_page" value="<?= $postData['view_page'] ?? '1' ?>">
                <button type="submit" class="btn btn-primary w-sm"><i class="ri-save-3-line align-bottom me-1"></i> Save and
                    Back
                </button>
            </form>
            <?php
        }
        $additionBtn = ob_get_clean();
        includeFileWithVariables('components/single-button-group.php', array("slug" => "book", "post_id" => $postData['id'], 'modify_type' => $modify_type, 'additionBtn' => $additionBtn));
        ?>
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0"><?= $postData['title'] ?></h4>
            </div>
            <div class="card-body">
                <?php
                if (!empty($postData['view_url'])) { ?>
                    <div class="d-flex justify-content-center">
                        <canvas id="pdfViewer"></canvas>
                    </div>
                    <div class="mt-3 d-flex justify-content-center align-items-center gap-5">
                        <button type="button" class="btn btn-light" id="prevPage"><i
                                class="ri-arrow-left-s-line align-bottom me-1"></i> Previous Page
                        </button>
                        <span>Page: <span id="currentPage"><?= $postData['view_page'] ?? '1' ?></span> / <span
                                id="totalPages"></span></span>
                        <div class="hstack gap-1">
                            <input type="number" class="form-control px-2 text-center" style="width: 40px;"
                                value="<?= $postData['view_page'] ?? '1' ?>" id="gotoPage">
                            <button class="btn btn-link" id="goButton">Go</button>
                        </div>
                        <button type="button" class="btn btn-light" id="nextPage">Next Page <i
                                class="ri-arrow-right-s-line align-bottom ms-1"></i>
                        </button>
                    </div>
                <?php } else { ?>
                    <div class="text-center p-5">
                        <h3 class="mb-2">This book isn't available</h3>
                        <p class="mb-4">We couldn't find the book you're looking for. Please try the options below:</p>
                        <div class="mb-3 hstack justify-content-center gap-2">
                            <a class="btn btn-primary" href="<?= App\Helpers\NetworkHelper::home_url('app/book') ?>">Search
                                for Another Book</a>
                            <a class="btn btn-secondary"
                                href="<?= App\Helpers\NetworkHelper::home_url('app/book/edit?id=' . $post_id) ?>">Contact
                                Support</a>
                        </div>
                        <div class="mt-4">
                            <p class="small text-muted">You can also browse our <a href="catalog.html">catalog</a> to find
                                similar books.</p>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <!-- end card body -->
        </div>
    </div>
    <!-- end col -->
</div>

<?php
$pageContent = ob_get_clean();

ob_start(); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.15.349/pdf.min.js"></script>
<script type="text/javascript">
    const url = '<?= App\Helpers\NetworkHelper::home_url("assets/pdf/example.pdf") ?>';
    const pdfjsLib = window['pdfjs-dist/build/pdf'];
    let pdfDoc = null;
    let currentPage = <?= $postData['view_page'] ?? '1' ?>;
    let totalPages = 0;
    const scale = 1.5;
    const $canvas = $('#pdfViewer');
    const canvas = $canvas[0];
    const ctx = canvas.getContext('2d');

    // Load the PDF document
    pdfjsLib.getDocument(url).promise.then((pdf) => {
        pdfDoc = pdf;
        totalPages = pdf.numPages;
        $('#totalPages').text(totalPages);

        renderPage(currentPage);
    });

    // Render the page
    function renderPage(pageNumber) {
        pdfDoc.getPage(pageNumber).then((page) => {
            const viewport = page.getViewport({
                scale
            });
            canvas.width = viewport.width;
            canvas.height = viewport.height;

            const renderContext = {
                canvasContext: ctx,
                viewport: viewport
            };
            page.render(renderContext);
        });

        // Update page number display
        $('#currentPage').text(pageNumber);
        $('#gotoPage').val(pageNumber);
        $("[name=view_page]").val(pageNumber);
    }

    // Navigate to the previous page
    $('#prevPage').on('click', function () {
        if (currentPage <= 1) return;
        currentPage--;
        renderPage(currentPage);
    });

    // Navigate to the next page
    $('#nextPage').on('click', function () {
        if (currentPage >= totalPages) return;
        currentPage++;
        renderPage(currentPage);
    });

    // Go to a specific page
    $('#goButton').on('click', function () {
        const targetPage = parseInt($('#gotoPage').val(), 10);
        if (!isNaN(targetPage) && targetPage >= 1 && targetPage <= totalPages) {
            currentPage = targetPage;
            renderPage(currentPage);
        } else {
            alert(`Please enter a valid page number between 1 and ${totalPages}`);
        }
    });
</script>
<?php
$additionJs = ob_get_clean();
