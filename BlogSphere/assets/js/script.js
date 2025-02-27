document.querySelector("form").addEventListener("submit", function (e) {
  // Get content from TinyMCE editor
  var content = tinymce.get("content").getContent({ format: "text" });
  if (content.trim() === "") {
    e.preventDefault();
    alert("Content is required.");
    tinymce.get("content").focus();
  }
});

function togglePassword(fieldId, e) {
  e.preventDefault(); // Prevent the default link action
  var field = document.getElementById(fieldId);
  if (field.type === "password") {
    field.type = "text";
  } else {
    field.type = "password";
  }
}
