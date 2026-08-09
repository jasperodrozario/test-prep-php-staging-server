<br>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Sl</th>
            <th>Task</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($tasks as $task): ?>
            <tr>
                <td><?= htmlspecialchars($task['id']) ?></td>
                <td><a href='<?= htmlspecialchars($task['link']) ?>'><u><?= htmlspecialchars($task['title']) ?></u></a></td>
                <td>
                    <ul>
                        <?php foreach ($task['description'] as $desc): ?>
                            <li><?= htmlspecialchars($desc) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>