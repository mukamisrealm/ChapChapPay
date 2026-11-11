<?php
session_start();
require_once 'db.php';

// Ensure user is logged in (accessible by member, treasurer, or admin)
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['role'] ?? 'member';
$user_name = $_SESSION['name'] ?? 'User';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make Payment | ChapChapPay</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #0b1329;
            color: #f8fafc;
            min-height: 100vh;
        }
        .pay-card {
            background: #131f37;
            border: 1px solid #1e2d4d;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }
        .form-control, .form-select {
            background-color: #0b1329;
            border: 1px solid #1e2d4d;
            color: #ffffff;
            border-radius: 12px;
            padding: 0.75rem 1rem;
        }
        .form-control:focus, .form-select:focus {
            background-color: #0b1329;
            border-color: #8172f8;
            color: #ffffff;
            box-shadow: 0 0 0 0.25rem rgba(129, 114, 248, 0.25);
        }
        .btn-mpesa {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border: none;
            color: #ffffff;
            font-weight: 600;
            border-radius: 12px;
            padding: 0.85rem;
            transition: all 0.2s ease-in-out;
        }
        .btn-mpesa:hover {
            opacity: 0.95;
            transform: translateY(-1px);
            color: #ffffff;
        }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="pay-card p-4 p-md-5">
                <div class="text-center mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center bg-success bg-opacity-10 text-success rounded-circle mb-3" style="width: 64px; height: 64px;">
                        <i class="bi bi-phone-vibrate fs-2"></i>
                    </div>
                    <h3 class="fw-bold text-white mb-1">M-Pesa Contribution</h3>
                    <p class="text-muted small">Initiate an STK Push payment directly to your account.</p>
                </div>

                <form id="mpesaForm">
                    <!-- If user is Admin or Treasurer, allow selecting target member -->
                    <?php if ($user_role === 'admin' || $user_role === 'treasurer'): ?>
                        <div class="mb-3">
                            <label class="form-label text-white-50 small fw-semibold">Paying On Behalf Of</label>
                            <select name="target_user_id" class="form-select">
                                <option value="<?php echo $user_id; ?>">Myself (<?php echo htmlspecialchars($user_name); ?>)</option>
                                <?php
                                $members_query = "SELECT id, name, phone FROM users WHERE id != ? ORDER BY name ASC";
                                $stmt = $conn->prepare($members_query);
                                $stmt->bind_param("i", $user_id);
                                $stmt->execute();
                                $res = $stmt->get_result();
                                while ($m = $res->fetch_assoc()) {
                                    echo "<option value='{$m['id']}'>" . htmlspecialchars($m['name']) . " (" . htmlspecialchars($m['phone']) . ")</option>";
                                }
                                $stmt->close();
                                ?>
                            </select>
                        </div>
                    <?php else: ?>
                        <input type="hidden" name="target_user_id" value="<?php echo $user_id; ?>">
                    <?php endif; ?>

                    <div class="mb-3">
                        <label class="form-label text-white-50 small fw-semibold">Safaricom Phone Number</label>
                        <div class="input-group">
                            <span class="input-group-text bg-dark text-white-50 border-secondary">+254</span>
                            <input type="tel" id="phone" name="phone" class="form-control" placeholder="712345678" required>
                        </div>
                        <div class="form-text text-muted">Enter active Safaricom number (e.g. 712345678).</div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-white-50 small fw-semibold">Amount (KES)</label>
                        <input type="number" id="amount" name="amount" class="form-control" placeholder="e.g. 500" min="1" step="1" required>
                    </div>

                    <button type="submit" class="btn btn-mpesa w-100 fs-6">
                        <i class="bi bi-send-check me-2"></i>Trigger STK Push
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('mpesaForm').addEventListener('submit', function(e) {
    e.preventDefault();

    Swal.fire({
        title: 'Initiating Payment...',
        text: 'Sending STK Push prompt to the device.',
        background: '#131f37',
        color: '#ffffff',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    const formData = new FormData(this);

    fetch('stk_push.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            Swal.fire({
                icon: 'success',
                title: 'STK Push Sent!',
                text: data.message,
                background: '#131f37',
                color: '#ffffff',
                confirmButtonColor: '#10b981'
            }).then(() => {
                window.location.href = '<?php echo $user_role; ?>dashboard.php';
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Transaction Failed',
                text: data.message,
                background: '#131f37',
                color: '#ffffff',
                confirmButtonColor: '#ef4444'
            });
        }
    })
    .catch(err => {
        Swal.fire({
            icon: 'error',
            title: 'Network Error',
            text: 'Failed to communicate with local server handler.',
            background: '#131f37',
            color: '#ffffff',
            confirmButtonColor: '#ef4444'
        });
    });
});
</script>

</body>
</html>