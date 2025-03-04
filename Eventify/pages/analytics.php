<?php include_once '../includes/header.php'; ?>

<?php
$data = $analyticsObj->getEventAttendanceData();
?>

<div class="card shadow-sm">
  <div class="card-header bg-dark text-light">
    <h4 class="card-title mb-0">Event Analytics</h4>
  </div>
  <div class="card-body">
    <?php if(!$data): ?>
      <div class="alert alert-info">No events available for analytics.</div>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-striped align-middle">
          <thead class="table-dark">
            <tr>
              <th>Event</th>
              <th>Capacity</th>
              <th>Registered</th>
              <th>Waitlisted</th>
              <th>Attendance Rate (%)</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($data as $d): 
                $rate = $analyticsObj->getAttendanceRate($d['event_id']);
            ?>
            <tr>
              <td class="fw-semibold"><?php echo htmlspecialchars($d['title']); ?></td>
              <td><?php echo $d['max_attendees']; ?></td>
              <td><?php echo $d['total_registered']; ?></td>
              <td><?php echo $d['total_waitlisted']; ?></td>
              <td>
                <?php 
                  echo $rate; 
                  echo "%"; 
                ?>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php include_once '../includes/footer.php'; ?>
