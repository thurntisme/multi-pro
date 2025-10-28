const pdfjsLib = window["pdfjs-dist/build/pdf"];

let pdfDoc = null,
  ctx = null,
  scale = 1,
  canvas = $("#pdfViewer"),
  pageNum = 1,
  totalPages = 1;

if (canvas.length) {
  ctx = canvas[0].getContext("2d");

  // Render page
  function renderPage(num) {
    // Validate page num
    num = validatePageNum(num);

    pdfDoc.getPage(num).then((page) => {
      const viewport = page.getViewport({ scale });
      canvas[0].height = viewport.height;
      canvas[0].width = viewport.width;

      const renderCtx = {
        canvasContext: ctx,
        viewport: viewport,
      };

      page.render(renderCtx);

      // update UI
      if (num === 1) {
        $("#prevPage").prop("disabled", true);
      } else {
        $("#prevPage").prop("disabled", false);
      }
      if (num === pdfDoc.numPages) {
        $("#nextPage").prop("disabled", true);
      } else {
        $("#nextPage").prop("disabled", false);
      }
      $("#currentPage").text(num);
      $("#gotoPage").val(num);
      $("[name=view_page]").val(num);
    });
  }

  // call ajax
  function callAjaxProcess(num) {
    renderPage(num);
    // $.ajax({
    //   url: ajaxEbookUrl,
    //   method: "POST",
    //   data: { page: num },
    //   success: function (response) {
    //     if (response.success) {
    //       const { page_num } = response.data;
    //       renderPage(page_num);
    //       ebookNote = response.data.content;
    //       $("#note-preview").html(ebookNote);
    //     }
    //   },
    //   error: function (error) {
    //     alert("Error: " + error);
    //   },
    // });
  }

  // Load PDF
  function loadPdf(pdfUrl) {
    if (!pdfUrl) {
      return;
    }
    pdfjsLib
      .getDocument(pdfUrl)
      .promise.then((pdfDoc_) => {
        pdfDoc = pdfDoc_;

        pdfDoc.getMetadata().then(({ info, metadata }) => {
          const title =
            info.Title ||
            (metadata ? metadata.get("dc:title") : null) ||
            "Untitled PDF";
          $("#pdfTitle").text(title);
        });

        totalPages = pdfDoc_.numPages;
        $("#totalPages").text(totalPages);
        $("#fileNotFound").remove();
        $("#pdfViewerContainer").removeClass("d-none");
        $("#zoomControls").removeClass("d-none");
        $("#pageControls").removeClass("d-none");
        renderPage(pageNum);
      })
      .catch((e) => {
        $("#pdfReaderContainer #pdfViewerContainer").html(
          '<div class="alert alert-danger">Network error. Please try again.</div>'
        );
      });
  }
  loadPdf(pdfUrl);

  // Next page
  $("#nextPage").on("click", () => {
    if (pageNum >= pdfDoc.numPages) return;
    pageNum++;
    callAjaxProcess(pageNum);
  });

  // Prev page
  $("#prevPage").on("click", () => {
    if (pageNum <= 1) return;
    pageNum--;
    callAjaxProcess(pageNum);
  });

  // Zoom In
  $("#zoomIn").on("click", () => {
    scale += 0.2;
    renderPage(pageNum);
  });

  // Zoom Out
  $("#zoomOut").on("click", () => {
    if (scale <= 0.6) return;
    scale -= 0.2;
    renderPage(pageNum);
  });

  // Go to a specific page
  $("#goButton").on("click", function () {
    const targetPage = parseInt($("#gotoPage").val(), 10);
    renderPage(targetPage);
  });

  function validatePageNum(num) {
    if (num < 1 || num > totalPages) {
      alert(
        `Invalid page number. Please enter a number between 1 and ${totalPages}`
      );
      return 1;
    }
    return num;
  }

  $("#fileNotFound form").on("submit", function (e) {
    e.preventDefault();
    const searchFile = $(this).find("[name=search_file]").val();
    if (!searchFile) {
      alert("Please input the file url");
      return;
    }
    loadPdf(searchFile);
  });
}

// if ($("#ebookNoteModal")) {
//   $("#ebookNoteModal").on("shown.bs.modal", function () {
//     $("#ebookNoteModal #note-preview").addClass("d-none");
//     $("#summernote").summernote({
//       height: 200,
//       placeholder: "Write something...",
//       toolbar: [
//         ["style", ["bold", "italic", "underline", "clear"]],
//         ["para", ["ul", "ol", "paragraph"]],
//         ["insert", ["link", "picture"]],
//         ["view", ["fullscreen", "codeview"]],
//       ],
//     });
//     $("#summernote").summernote("code", ebookNote);
//   });

//   $("#ebookNoteModal").on("hidden.bs.modal", function () {
//     $("#summernote").summernote("destroy");
//     $("#ebookNoteModal #note-preview").removeClass("d-none");
//     $("#btnSaveContent").prop("disabled", false);
//     $("#btnSaveContent .spinner-border").addClass("d-none");
//   });

//   $("#btnSaveContent").on("click", function () {
//     const content = $("#summernote").val();
//     $(this).prop("disabled", true);
//     $(this).find(".spinner-border").removeClass("d-none");
//     const page_num = $("#page-num").text();
//     updateEbookNote(page_num, content);
//   });

//   function updateEbookNote(num, content) {
//     $.ajax({
//       url: ajaxNoteUrl,
//       method: "POST",
//       data: { page: num, content },
//       success: function (response) {
//         if (response.success) {
//           ebookNote = response.data.content;
//           $("#note-preview").html(ebookNote);
//         }
//         $("#ebookNoteModal").modal("hide");
//       },
//       error: function (error) {
//         alert("Error: " + error);
//         $("#ebookNoteModal").modal("hide");
//       },
//     });
//   }
// }
