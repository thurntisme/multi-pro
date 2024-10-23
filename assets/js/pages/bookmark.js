jQuery(document).ready(function ($) {
  const arr = JSON.parse(bookmarkList);
  let throttleTimeout = null;
  const throttleDelay = 500; // Delay in milliseconds (500ms)

  $("#search-bookmark").on("input", function () {
    const searchTerm = $(this).val();

    // Clear the previous timeout if it's still running
    if (throttleTimeout) {
      clearTimeout(throttleTimeout);
    }

    // Set a new timeout to throttle the search
    throttleTimeout = setTimeout(function () {
      performSearch(searchTerm.trim());
    }, throttleDelay);
  });

  function performSearch(keyword) {
    if (keyword.length) {
      const filteredBookmarks = arr.filter(
        (bookmark) =>
          bookmark.name.toLowerCase().includes(keyword.toLowerCase()) ||
          bookmark.url.toLowerCase().includes(keyword.toLowerCase()) ||
          bookmark.tags.some((tag) =>
            tag.toLowerCase().includes(keyword.toLowerCase())
          )
      );
      displayBookmarks(filteredBookmarks);
    } else {
      resetSearchForm();
    }
  }

  function resetSearchForm() {
    $("#bookmark-content").removeClass("d-none");
    $("#search-results").addClass("d-none");
  }

  $("#btn-reset").on("click", function () {
    resetSearchForm();
    $("#search-bookmark").val("");
  });

  function displayBookmarks(bookmarks) {
    $("#bookmark-content").addClass("d-none");
    const searchResults = $("#search-results");
    searchResults.empty();
    searchResults.removeClass("d-none");

    if (bookmarks.length === 0) {
      searchResults.append(
        "<p>No bookmarks found that match the search criteria.</p>"
      );
      return;
    }

    bookmarks.forEach((bookmark) => {
      const bookmarkItem = $(
        `
        <div class="col-3">
            <div class="card card-body text-center">
                <div class="avatar-sm mx-auto mb-3">
                    <div class="avatar-title text-success bg-transparent rounded">
                        <img class="img-thumbnail rounded-circle avatar-xl w-100 h-100 object-fit-contain" alt="200x200" src="${
                          bookmark.logo
                        }">
                    </div>
                </div>
                <h4 class="card-title">${bookmark.name}</h4>
                <h5 class="card-text fs-14">(${bookmark.key})</h5>
                <p class="card-text text-muted">${bookmark.tags.join(", ")}</p>
                <a href="${
                  bookmark.url
                }" target="_blank" rel="noopener noreferrer" class="btn btn-success">Visit</a>
            </div>
        </div>
        `
      );
      searchResults.append(bookmarkItem);
    });
  }
});
