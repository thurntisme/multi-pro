<?php
$pageTitle = "Bookmarks";

require_once 'controllers/BookmarkController.php';
require_once 'controllers/BookmarkCategoryController.php';
$bookmarkController = new BookmarkController();
$bookmarkCategoryController = new BookmarkCategoryController();
$categoryLists = $bookmarkCategoryController->listBookmarkCategories();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['action_name'])) {
    if ($_POST['action_name'] === 'create_bookmark_category') {
      $bookmarkCategoryController->createBookmarkCategory();
    }
    if ($_POST['action_name'] === 'create_bookmark') {
      $bookmarkController->createBookmark();
    }
    if ($_POST['action_name'] === 'delete_bookmark') {
      $bookmarkController->deleteBookmark();
    }
    if ($_POST['action_name'] === 'delete_bookmark_category') {
      $bookmarkCategoryController->deleteBookmarkCategory();
    }
  }
};


$bookmarkLists = $bookmarkController->listBookmarks();
function renderBookmarkList($cate_id)
{
  global $categoryLists;
  global $bookmarkLists;
  if (count($bookmarkLists) > 0) {
    $bookmarks = $bookmarkLists;
    if ($cate_id !== "all") {
      $bookmarks = array_filter($bookmarkLists, function ($bookmark) use ($cate_id) {
        return $bookmark['category_id'] === $cate_id;
      });
    }
    $list = '<div class="row">';
    foreach ($bookmarks as $bookmark) {
      $category = array_filter($categoryLists, function ($category) use ($bookmark) {
        return $category['id'] === $bookmark['category_id'];
      });
      $bookmark['category'] = array_values($category)[0]['title'];
      $list .= '
      <div class="col-3 mb-4">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title text-gray-800 d-flex align-items-center mb-0">' . $bookmark['title'] . '
              <form method="POST" action="' . home_url("bookmarks") . '">
                  <input type="hidden" name="bookmark_id" value="' . $bookmark['id'] . '">
                  <input type="hidden" name="action_name" value="delete_bookmark">
                  <button type="submit" class="btn btn-sm"><i class="fas fa-trash text-danger"></i></button>
                </form>
            </h5>
            <small>' . $bookmark['category'] . '</small>
            <p class="card-text mt-3">' . $bookmark['content'] . '</p>
            <a href="' . $bookmark['url'] . '" class="btn btn-primary btn-sm" target="_blank" rel="noopener noreferrer">Visit<i class="fas fa-external-link-alt ml-2" style="font-size: 12px"></i></a>
          </div>
        </div>
      </div>';
    }
    return $list . "</div>";
  } else {
    return "...";
  }
}

ob_start();

if (isset($_SESSION['message'])) {
  $messageType = $_SESSION['message_type'] ?? 'info';
  echo '<div class="alert alert-' . htmlspecialchars($messageType) . ' alert-dismissible fade show mb-4" role="alert">'
    . htmlspecialchars($_SESSION['message']) .
    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>';

  unset($_SESSION['message']);
  unset($_SESSION['message_type']);
}

echo '<form method="POST" action="' . home_url("bookmarks") . '" class="form-inline mb-4">
<input type="hidden" name="action_name" value="create_bookmark_category">
<div class="form-group mr-4">
  <label for="category_title" class="col-form-label mr-4 justify-content-start" style="width: 80px">Category</label>
  <div class="flex-grow-1">
    <input type="text" class="form-control" id="category_title" name="category_title" placeholder="Category abc">
  </div>
</div>
<button type="submit" class="btn btn-success">Add new</button>
</form>';

if (count($categoryLists) > 0) {
  $categoryOptions = '';
  foreach ($categoryLists as $category) {
    $categoryOptions .= '<option value="' . htmlspecialchars($category['id']) . '">' .
      htmlspecialchars($category['title']) .
      '</option>';
  }

  echo '<form method="POST" action="' . home_url("bookmarks") . '" class="form-inline mb-4">
          <input type="hidden" name="action_name" value="create_bookmark">
          <div class="form-group mr-4">
            <label for="title" class="col-form-label mr-4 justify-content-start" style="width: 80px">Title</label>
            <div class="flex-grow-1">
              <input type="text" class="form-control" id="title" name="title" placeholder="Website">
            </div>
          </div>
          <div class="form-group mr-4">
            <label for="url" class="col-form-label mr-4">Url</label>
            <div class="flex-grow-1">
              <input type="url" class="form-control" id="url" name="url" placeholder="https://example.com">
            </div>
          </div>
          <div class="form-group mr-4">
            <label for="content" class="col-form-label mr-4">Content</label>
            <div class="flex-grow-1">
              <input type="text" class="form-control" id="content" name="content">
            </div>
          </div>
          <div class="form-group mr-4">
            <label for="category" class="col-form-label mr-4">Category</label>
            <div class="flex-grow-1">
              <select class="form-control" class="form-control" name="category_id" id="category">'
    . $categoryOptions .
    '</select>
            </div>
          </div>
          <button type="submit" class="btn btn-success">Add new</button>
        </form>';
  echo '<ul class="nav nav-pills mb-3 pt-4 border-top" id="pills-tab" role="tablist">';
  echo '<li class="nav-item" role="presentation">
          <button class="nav-link active border-0" id="pills-home-tab" data-toggle="pill" data-target="#cate-all" type="button" role="tab" aria-controls="pills-home" aria-selected="true">All</button>
        </li>';
  foreach ($categoryLists as $key => $category) {
    echo '<li class="nav-item d-flex align-items-center ml-3 position-relative" role="presentation">
              <button class="nav-link border-0" data-toggle="pill" data-target="#cate-' . $key . '" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">
              ' . $category['title'] . '
              </button>
              <form method="POST" action="' . home_url("bookmarks") . '" class="position-absolute" style="right: -8px; top: -8px">
                  <input type="hidden" name="bookmark_category_id" value="' . $category['id'] . '">
                  <input type="hidden" name="action_name" value="delete_bookmark_category">
                  <button type="submit" class="btn btn-sm p-0 rounded-circle overflow-hidden border-0 d-flex" style="width:15px;height:15px"><i class="far fa-times-circle" style="font-size: 15px; background-color: #fff;"></i></button>
                </form>
            </li>';
  }
  echo '</ul>';
  echo '<div class="tab-content" id="pills-tabContent">
          <div class="tab-pane fade show active" id="cate-all" role="tabpanel" >' . renderBookmarkList("all") . '</div>';
  foreach ($categoryLists as $key => $category) {
    echo '<div class="tab-pane fade show" id="cate-' . $key . '" role="tabpanel" >' . renderBookmarkList($category['id']) . '</div>';
  }
  echo '</div>';
}

$pageContent = ob_get_clean();
