<?php
// Count total tasks
$totalTasks = 0;
$completedTasks = 0;

// Loop through each status group to count tasks and completed tasks
foreach ($groupedTasks as $status => $tasks) {
    $totalTasks += count($tasks);
    if ($status === 'done') {
        $completedTasks += count($tasks);  // Only count tasks that are marked as 'done'
    }
}

// Calculate overall progress
$overallProgress = ($totalTasks > 0) ? ($completedTasks / $totalTasks) * 100 : 0;
?>

<div class="row">
    <div class="col col-3">
        <h5>Task Report Template</h5>
        <div class="content">
            Task Report Date: <?= $today ?><br>
            Author: <?= $author ?><br>
            Project Name: <?= $project ?><br>
            -----------------------------<br>

            <?php if (!empty($groupedTasks['todo'])): ?>
                Status: Todo<br>
                <?php foreach ($groupedTasks['todo'] as $task): ?>
                    Task Title: <?php echo htmlspecialchars($task['task_title']); ?><br>
                    ETA: <?php echo htmlspecialchars($task['task_eta']); ?><br>
                    Time Spent: <?php echo htmlspecialchars($task['task_time_spend']); ?><br>
                    -----------------------------<br>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if (!empty($groupedTasks['processing'])): ?>
                Status: In Progress<br>
                <?php foreach ($groupedTasks['processing'] as $task): ?>
                    Task Title: <?php echo htmlspecialchars($task['task_title']); ?><br>
                    ETA: <?php echo htmlspecialchars($task['task_eta']); ?><br>
                    Time Spent: <?php echo htmlspecialchars($task['task_time_spend']); ?><br>
                    -----------------------------<br>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if (!empty($groupedTasks['done'])): ?>
                Status: Done<br>
                <?php foreach ($groupedTasks['done'] as $task): ?>
                    Task Title: <?php echo htmlspecialchars($task['task_title']); ?><br>
                    ETA: <?php echo htmlspecialchars($task['task_eta']); ?><br>
                    Time Spent: <?php echo htmlspecialchars($task['task_time_spend']); ?><br>
                    -----------------------------<br>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <button class="btn btn-primary btn-copy-template mt-3">Copy</button>
    </div>
    <div class="col col-3">
        <h5>Project Progress Report Template</h5>
        <div class="content">
            Project Report Date: <?= $today ?><br>
            Author: <?= $author ?><br>
            Project Name: <?= $project ?><br>
            -----------------------------<br>

            Progress Status<br>
            Total Tasks: <?php echo $totalTasks; ?><br>
            Completed Tasks: <?php echo $completedTasks; ?><br>
            Overall Progress: <?php echo round($overallProgress, 2); ?>%<br>
            -----------------------------<br>

            Tasks<br>

            <?php if (!empty($groupedTasks['todo'])): ?>
                <?php foreach ($groupedTasks['todo'] as $task): ?>
                    Task Title: <?php echo htmlspecialchars($task['task_title']); ?><br>
                    Status: Pending<br>
                    -----------------------------<br>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if (!empty($groupedTasks['processing'])): ?>
                <?php foreach ($groupedTasks['processing'] as $task): ?>
                    Task Title: <?php echo htmlspecialchars($task['task_title']); ?><br>
                    Status: In Progress<br>
                    -----------------------------<br>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if (!empty($groupedTasks['done'])): ?>
                <?php foreach ($groupedTasks['done'] as $task): ?>
                    Task Title: <?php echo htmlspecialchars($task['task_title']); ?><br>
                    Status: Completed<br>
                    -----------------------------<br>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <button class="btn btn-primary btn-copy-template mt-3">Copy</button>
    </div>
    <div class="col col-3">
        <h5>Daily Task Report Template</h5>
        <div class="content">
            Daily Task Report Date: <?= $today ?><br>
            Author: <?= $author ?><br>
            Project Name: <?= $project ?><br>
            -----------------------------<br>

            <?php if (!empty($groupedTasks['todo'])): ?>
                <?php foreach ($groupedTasks['todo'] as $task): ?>
                    Task Title: <?php echo htmlspecialchars($task['task_title']); ?><br>
                    Status: [Pending]<br>
                    ETA: <?php echo htmlspecialchars($task['task_eta']); ?><br>
                    Time Spent: <?php echo htmlspecialchars($task['task_time_spend']); ?><br>
                    <?php
                    if (!empty($task['comments'])) {
                        echo 'Comments: ' . htmlspecialchars($task['comments']) . '<br>';
                    }
                    ?>
                    -----------------------------<br>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if (!empty($groupedTasks['processing'])): ?>
                <?php foreach ($groupedTasks['processing'] as $task): ?>
                    Task Title: <?php echo htmlspecialchars($task['task_title']); ?><br>
                    Status: [In Progress]<br>
                    ETA: <?php echo htmlspecialchars($task['task_eta']); ?><br>
                    Time Spent: <?php echo htmlspecialchars($task['task_time_spend']); ?><br>
                    <?php
                    if (!empty($task['comments'])) {
                        echo 'Comments: ' . htmlspecialchars($task['comments']) . '<br>';
                    }
                    ?>
                    -----------------------------<br>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if (!empty($groupedTasks['done'])): ?>
                <?php foreach ($groupedTasks['done'] as $task): ?>
                    Task Title: <?php echo htmlspecialchars($task['task_title']); ?><br>
                    Status: [Completed]<br>
                    ETA: <?php echo htmlspecialchars($task['task_eta']); ?><br>
                    Time Spent: <?php echo htmlspecialchars($task['task_time_spend']); ?><br>
                    <?php
                    if (!empty($task['comments'])) {
                        echo 'Comments: ' . htmlspecialchars($task['comments']) . '<br>';
                    }
                    ?>
                    -----------------------------<br>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <button class="btn btn-primary btn-copy-template mt-3">Copy</button>
    </div>
    <div class="col col-3">
        <h5>Employee Performance Report Template</h5>
        <div class="content">
            Employee Performance Report Date: <?= $today ?><br>
            Author: <?= $author ?><br>
            Project Name: <?= $project ?><br>
            -----------------------------<br>

            Performance Overview<br>
            Total Tasks Assigned: <?php echo count($groupedTasks['todo']); ?><br>
            Tasks Completed: <?php echo count($groupedTasks['processing']); ?><br>
            Tasks Pending: <?php echo count($groupedTasks['done']); ?><br>
            -----------------------------<br>

            Task Breakdown<br>

            <?php if (!empty($groupedTasks['todo'])): ?>
                <?php foreach ($groupedTasks['todo'] as $task): ?>
                    Task Title: <?php echo htmlspecialchars($task['task_title']); ?><br>
                    Status: [Pending]<br>
                    Time Spent: <?php echo htmlspecialchars($task['task_time_spend']); ?><br>
                    -----------------------------<br>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if (!empty($groupedTasks['processing'])): ?>
                <?php foreach ($groupedTasks['processing'] as $task): ?>
                    Task Title: <?php echo htmlspecialchars($task['task_title']); ?><br>
                    Status: [In Progress]<br>
                    Time Spent: <?php echo htmlspecialchars($task['task_time_spend']); ?><br>
                    -----------------------------<br>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if (!empty($groupedTasks['done'])): ?>
                <?php foreach ($groupedTasks['done'] as $task): ?>
                    Task Title: <?php echo htmlspecialchars($task['task_title']); ?><br>
                    Status: [Completed]<br>
                    Time Spent: <?php echo htmlspecialchars($task['task_time_spend']); ?><br>
                    -----------------------------<br>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <button class="btn btn-primary btn-copy-template mt-3">Copy</button>
    </div>
</div>