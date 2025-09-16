<!DOCTYPE html> 
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Customer Records</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Bundle JS (includes Popper) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    body {
      background-color: #f5f3ff; /* soft lavender background */
      color: #2e1065; /* deep purple text */
      transition: background-color 0.3s, color 0.3s;
    }

    h2,
    h3 {
      color: #6d28d9; /* vivid violet */
    }

    .btn-pink {
      background: linear-gradient(135deg, #8b5cf6, #7c3aed); /* gradient purple */
      color: #fff;
      border: none;
      transition: transform 0.2s, background 0.3s;
    }

    .btn-pink:hover {
      background: linear-gradient(135deg, #7c3aed, #5b21b6);
      transform: scale(1.05);
      color: #fff;
    }

    .btn-warning {
      background-color: #facc15 !important; /* yellow */
      border: none;
      color: #1f1f1f;
    }

    .btn-danger {
      background-color: #ef4444 !important; /* red */
      border: none;
    }

    .modal-header {
      background-color: #ede9fe; /* light lavender */
      border-bottom: 2px solid #c4b5fd;
    }

    .modal-title {
      color: #6d28d9;
      font-weight: 600;
    }

    .table-pink {
      background-color: #a855f7; /* purple table header */
      color: #fff;
    }

    .table-hover tbody tr:hover {
      background-color: #f3e8ff; /* light purple hover */
    }

    /* Dark Mode Styles */
    body.dark-mode {
      background-color: #18122b; /* dark violet background */
      color: #e0e7ff;
    }

    body.dark-mode h2,
    body.dark-mode h3 {
      color: #c4b5fd; /* soft violet */
    }

    body.dark-mode .btn-pink {
      background: linear-gradient(135deg, #9333ea, #7e22ce);
    }

    body.dark-mode .btn-pink:hover {
      background: linear-gradient(135deg, #7e22ce, #6d28d9);
    }

    body.dark-mode .modal-header {
      background-color: #4c1d95;
      border-bottom: 2px solid #7c3aed;
    }

    body.dark-mode .modal-title {
      color: #e9d5ff;
    }

    body.dark-mode .table {
      background-color: #2a1f3d;
      color: #e0e7ff;
    }

    body.dark-mode .table th {
      background-color: #6d28d9;
      color: #fff;
    }

    body.dark-mode .table-striped tbody tr:nth-of-type(odd) {
      background-color: #3b2d5a;
    }

    body.dark-mode .table-hover tbody tr:hover {
      background-color: #4c356d;
    }

    .dark-toggle {
      position: fixed;
      top: 15px;
      right: 20px;
      border-radius: 20px;
      padding: 6px 14px;
      font-size: 14px;
    }
  </style>
</head>

<body class="container py-4">

  <!-- Dark Mode Toggle -->
  <button class="btn btn-sm btn-pink dark-toggle" onclick="toggleDarkMode()">🌙 Dark Mode</button>

  <h2 class="text-center mb-4">All Records</h2>

  <!-- Displaying Errors and Success Messages -->
  <div>
    <?php getErrors(); ?>
    <?php getMessage(); ?>
  </div>

  <!-- Add Customer Button -->
  <div class="text-end mb-3">
    <form action="<?=site_url('/');?>" method="get" class="col-sm-4 float-end d-flex">
      <?php
      $q = '';
      if(isset($_GET['q'])) {
        $q = $_GET['q'];
      }
      ?>
      <input class="form-control me-2" name="q" type="text" placeholder="Search" value="<?=html_escape($q);?>">
      <button type="submit" class="btn btn-primary" type="button">Search</button>
    </form>
    <button class="btn btn-pink" data-bs-toggle="modal" data-bs-target="#addCustomerModal">+ Add Customer</button>
  </div>

  <!-- Customer Records Table -->
  <div class="table-responsive">
    <table class="table table-bordered border-pink table-striped table-hover">
      <thead class="table-pink">
        <tr>
          <th>Customer ID</th>
          <th>First Name</th>
          <th>Last Name</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($all)): ?>
          <?php foreach ($all as $customer): ?>
            <tr>
              <td><?= htmlspecialchars($customer['customer_id']); ?></td>
              <td><?= htmlspecialchars($customer['first_name']); ?></td>
              <td><?= htmlspecialchars($customer['last_name']); ?></td>
              <td><?= htmlspecialchars($customer['email']); ?></td>
              <td><?= htmlspecialchars($customer['phone']); ?></td>
              <td>
                <button class="btn btn-sm btn-warning me-2" data-bs-toggle="modal" data-bs-target="#editModal<?= $customer['id']; ?>">Edit</button>
                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $customer['id']; ?>">Delete</button>
              </td>
            </tr>

            <!-- Edit Modal -->
            <div class="modal fade" id="editModal<?= $customer['id']; ?>" tabindex="-1" aria-labelledby="editModalLabel<?= $customer['id']; ?>" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <form action="/update-customer/<?= $customer['id']; ?>" method="POST">
                    <div class="modal-header">
                      <h5 class="modal-title" id="editModalLabel<?= $customer['id']; ?>">Edit Customer</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                      <div class="mb-2">
                        <label>Customer ID:</label>
                        <input type="text" name="customer_id" class="form-control" value="<?= htmlspecialchars($customer['customer_id']); ?>" required>
                      </div>
                      <div class="mb-2">
                        <label>First Name:</label>
                        <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($customer['first_name']); ?>" required>
                      </div>
                      <div class="mb-2">
                        <label>Last Name:</label>
                        <input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($customer['last_name']); ?>" required>
                      </div>
                      <div class="mb-2">
                        <label>Email:</label>
                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($customer['email']); ?>" required>
                      </div>
                      <div class="mb-2">
                        <label>Phone:</label>
                        <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($customer['phone']); ?>" required>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="submit" class="btn btn-success">Update</button>
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>

            <!-- Delete Modal -->
            <div class="modal fade" id="deleteModal<?= $customer['id']; ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?= $customer['id']; ?>" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <form action="/delete-customer/<?= $customer['id']; ?>" method="POST">
                    <div class="modal-header">
                      <h5 class="modal-title" id="deleteModalLabel<?= $customer['id']; ?>">Delete Customer</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                      <p>Are you sure you want to delete <strong><?= htmlspecialchars($customer['first_name']." ".$customer['last_name']); ?></strong>?</p>
                    </div>
                    <div class="modal-footer">
                      <button type="submit" class="btn btn-danger">Delete</button>
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="6" class="text-center">No customers found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
    <?php
    echo $page;
    ?>
  </div>

  <!-- Add Customer Modal -->
  <div class="modal fade" id="addCustomerModal" tabindex="-1" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="/create-customer" method="POST">
          <div class="modal-header">
            <h5 class="modal-title" id="addCustomerModalLabel">Add New Customer</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="mb-2">
              <label>Customer ID:</label>
              <input type="text" name="customer_id" class="form-control" required>
            </div>
            <div class="mb-2">
              <label>First Name:</label>
              <input type="text" name="first_name" class="form-control" required>
            </div>
            <div class="mb-2">
              <label>Last Name:</label>
              <input type="text" name="last_name" class="form-control" required>
            </div>
            <div class="mb-2">
              <label>Email:</label>
              <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-2">
              <label>Phone:</label>
              <input type="text" name="phone" class="form-control" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-pink">Add Customer</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    function toggleDarkMode() {
      document.body.classList.toggle("dark-mode");
    }
  </script>

</body>
</html>
