<?php
$postData = [];
$post_id = 1;
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
    ?>
    <div class="card" id="pdfReaderContainer">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h4 class="card-title mb-0" id="pdfTitle"><?= $postData['title'] ?? 'PDF Reader' ?></h4>
        <div class="d-flex align-items-center gap-2">
          <button type="button" class="btn btn-light" id="zoomOut"><i class="ri-zoom-out-line align-bottom me-1"></i> Zoom Out</button>
          <button type="button" class="btn btn-light" id="zoomIn"><i class="ri-zoom-in-line align-bottom me-1"></i> Zoom In</button>
        </div>
      </div>
      <div class="card-body">
        <div class="w-100 h-100 overflow-auto d-flex justify-content-center <?= empty($postData['view_url']) ? 'd-none' : '' ?>" id="pdfViewerContainer">
          <canvas id="pdfViewer"></canvas>
        </div>
        <div class="mt-3 d-flex align-items-center gap-2 opacity-0" id="pageControls">
          <span class="me-auto">Page: <span id="currentPage"><?= $postData['view_page'] ?? '1' ?></span> / <span
              id="totalPages"></span></span>
          <button type="button" class="btn btn-light" id="prevPage" disabled><i
              class="ri-arrow-left-s-line align-bottom me-1"></i> Previous Page
          </button>
          <div class="hstack gap-1">
            <input type="number" class="form-control px-2 text-center" style="width: 40px;"
              value="<?= $postData['view_page'] ?? '1' ?>" id="gotoPage">
            <button class="btn btn-link" id="goButton">Go</button>
          </div>
          <button type="button" class="btn btn-light" id="nextPage">Next Page <i
              class="ri-arrow-right-s-line align-bottom ms-1"></i>
          </button>
        </div>
        <div class="text-center p-5 <?= empty($postData['view_url']) ? '' : 'd-none' ?>" id="fileNotFound">
          <h3 class="mb-2">This file isn't available</h3>
          <p class="mb-4">We couldn't find the file you're looking for. Please try the options below:</p>
          <form method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>">
            <input type="hidden" name="action_name" value="search_file">
            <div class="mb-3 hstack justify-content-center gap-2">
              <input type="text" class="form-control flex-grow-1" placeholder="Input the file url" name="search_file">
              <button type="submit" class="btn btn-primary">Search</button>
            </div>
          </form>
          <div class="mt-4">
            <p class="small text-muted">
              If you continue to experience issues, please <a href="contact.html" class="text-decoration-underline">contact support</a>.
            </p>
          </div>
        </div>
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
  let pdfUrl = '<?= $postData['view_url'] ?>';
</script>
<script src="<?= \App\Helpers\Network::home_url('assets/js/pages/pdf-reader.js') ?>"></script>
<?php
$additionJs = ob_get_clean();

include_once LAYOUTS_DIR . 'dashboard.php';
