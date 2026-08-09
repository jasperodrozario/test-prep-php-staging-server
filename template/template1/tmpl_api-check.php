<div style="margin-top:110px;"></div>
<h1>Course Data</h1>
<pre id="result">Loading...</pre>

<script>
  const formData = new FormData();
  formData.append("action", "get-course-details");
  formData.append("course_code", "1");

  fetch("https://staging-test-prep.asvab-tutoring.com/practice-test/asvab-api.php", {
      method: "POST",
      body: formData,
    })
    .then(response => response.json())
    .then(data => {
      console.log(data); // Debugging
      document.getElementById("result").textContent = JSON.stringify(data, null, 2);
    })
    .catch(error => {
      console.error("Fetch error:", error);
      document.getElementById("result").textContent = "Error fetching data.";
    });
</script>