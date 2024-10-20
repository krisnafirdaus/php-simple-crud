<?php
session_start();
// Initialize the array if it doesn't exist
if (!isset($_SESSION['items'])) {
    $_SESSION['items'] = [];
}
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id = isset($_POST['id']) ? intval($_POST['id']) : null;
    $item = $_POST['item'] ?? '';
    switch ($action) {
        case 'create':
            $_SESSION['items'][] = $item;
            break;
        case 'update':
            if (isset($_SESSION['items'][$id - 1])) {
                $_SESSION['items'][$id - 1] = $item;
            }
            break;
        case 'delete':
            if (isset($_SESSION['items'][$id - 1])) {
                array_splice($_SESSION['items'], $id - 1, 1);
            }
            break;
    }
}

// Echo items
echo "<pre>";
print_r($_SESSION['items']);
echo "</pre>";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple CRUD System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        input[type="text"] {
            width: 100%;
            padding: 5px;
        }
        button {
            padding: 5px 10px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h1>Simple CRUD System</h1>
    
    <!-- Create Form -->
    <h2>Add New Item</h2>
    <form method="post">
        <input type="hidden" name="action" value="create">
        <input type="text" name="item" required>
        <button type="submit">Add Item</button>
    </form>
    
    <!-- Read Table -->
    <h2>Item List</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Item</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($_SESSION['items'] as $index => $item): ?>
            <tr>
                <td><?php echo $index + 1; ?></td>
                <td><?php echo htmlspecialchars($item); ?></td>
                <td>
                    <button onclick="editItem(<?php echo $index + 1; ?>, '<?php echo htmlspecialchars($item, ENT_QUOTES); ?>')">Edit</button>
                    <form method="post" style="display: inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?php echo $index + 1; ?>">
                        <button type="submit">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <!-- Update Form (hidden by default) -->
    <div id="updateForm" style="display: none;">
        <h2>Edit Item</h2>
        <form method="post">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" id="updateId">
            <input type="text" name="item" id="updateItem" required>
            <button type="submit">Update Item</button>
            <button type="button" onclick="cancelEdit()">Cancel</button>
        </form>
    </div>
    
    <script>
        function editItem(id, item) {
            document.getElementById('updateForm').style.display = 'block';
            document.getElementById('updateId').value = id;
            document.getElementById('updateItem').value = item;
        }
        function cancelEdit() {
            document.getElementById('updateForm').style.display = 'none';
        }
    </script>
</body>
</html>