<?php
// poll_results.php
include 'includes/header.php';
include 'db.php';

$poll_id = isset($_GET['poll_id']) ? intval($_GET['poll_id']) : 0;

// Fetch poll details
$pollResult = $conn->query("SELECT * FROM polls WHERE id = $poll_id");
if($pollResult->num_rows == 0){
    echo "Poll not found.";
    exit;
}
$poll = $pollResult->fetch_assoc();
?>
<main>
    <div class="vw-container">
        <h2 class="vw-heading"><?php echo htmlspecialchars($poll['title']); ?> - Results</h2>
        <canvas id="resultsChart" width="400" height="200"></canvas>
        <br>
        <a class="vw-btn" href="export_csv.php?poll_id=<?php echo $poll_id; ?>">Export CSV</a>
        |
        <a class="vw-btn" href="export_pdf.php?poll_id=<?php echo $poll_id; ?>">Export PDF</a>
    </div>
</main>
<script>
document.addEventListener('DOMContentLoaded', function(){
    var ctx = document.getElementById('resultsChart').getContext('2d');
    var chart;
    function fetchResults(){
        fetch('get_results.php?poll_id=<?php echo $poll_id; ?>')
        .then(response => response.json())
        .then(data => {
            var labels = [];
            var votes = [];
            data.forEach(function(item){
                labels.push(item.option);
                votes.push(item.votes);
            });
            if(chart){
                chart.data.labels = labels;
                chart.data.datasets[0].data = votes;
                chart.update();
            } else {
                chart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Results',
                            data: votes,
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: { beginAtZero: true }
                        }
                    }
                });
            }
        });
    }
    fetchResults();
    setInterval(fetchResults, 5000); // update every 5 seconds
});
</script>
<?php include 'includes/footer.php'; ?>
