jQuery(document).ready(function ($) {
  if ($("#calendar").length) {
    var calendar = new FullCalendar.Calendar($("#calendar")[0], {
      initialView: "dayGridMonth",
      events: [],
    });
    calendar.render();
  }
  if ($("#addTaskModal").length) {
    $("#addTaskModal #btn-save").click(function () {
      $("#addTaskModal form").submit();
    });
  }
  if ($("#addNoteModal").length) {
    $("#addNoteModal #btn-save").click(function () {
      $("#addNoteModal form").submit();
    });
  }
});
