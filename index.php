<?php
declare(strict_types=1);

// Load environment variables from .env file if it exists
if (file_exists(__DIR__ . '/.env')) {
    $lines = file(__DIR__ . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '#') === 0) {
            continue;
        }
        list($name, $value) = explode('=', $line, 2);
        $_ENV[trim($name)] = trim($value);
    }
}

// These are the defined authentication environment in the db service
// The MySQL service named in the docker-compose.yml.
$host = getenv('DB_HOST') ?: '188.166.208.200';

// Database user name
$user = getenv('DB_USER') ?: 'root';

// Database user password
$pass = getenv('DB_PASSWORD') ?: 'rahsia';

// Database name
$mydatabase = getenv('DB_NAME') ?: 'malaysia';

// Check the MySQL connection status
$conn = new mysqli($host, $user, $pass, $mydatabase);

// Check for connection errors
if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error);
    die("A database connection error occurred. Please try again later.");
}

// Enable mysqli exception mode
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Select query using prepared statement
$sql = 'SELECT id, nama FROM negeri ORDER BY id ASC';

try {
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        error_log("Prepare failed: " . $conn->error);
        die("A database query error occurred. Please try again later.");
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    $negeris = [];
    while ($data = $result->fetch_object()) {
        $negeris[] = $data;
    }
    
    $stmt->close();
} catch (Exception $e) {
    error_log("Query execution failed: " . $e->getMessage());
    die("A database query error occurred. Please try again later.");
}

// Close connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senarai Negeri-Negeri Di Malaysia</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Navigation */
        .navbar {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            padding: 0 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .navbar-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 70px;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: white;
        }

        .navbar-logo {
            height: 45px;
            width: auto;
            margin-right: 12px;
        }

        .navbar-title {
            font-size: 1.5rem;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .navbar-subtitle {
            font-size: 0.75rem;
            opacity: 0.85;
            margin-top: 2px;
        }

        .navbar-nav {
            display: flex;
            gap: 30px;
            list-style: none;
        }

        .navbar-nav a {
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            font-weight: 500;
            padding: 8px 16px;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .navbar-nav a:hover {
            background-color: rgba(255, 255, 255, 0.15);
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 40px 20px;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .page-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .page-header h1 {
            color: #1e3c72;
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .page-header p {
            color: #666;
            font-size: 1.1rem;
        }

        /* Table Styles */
        .table-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        }

        th {
            padding: 16px 20px;
            text-align: left;
            color: white;
            font-weight: 600;
            font-size: 1rem;
            letter-spacing: 0.5px;
        }

        th:first-child {
            width: 80px;
            text-align: center;
        }

        tbody tr {
            transition: background-color 0.2s ease;
        }

        tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }

        tbody tr:hover {
            background-color: #eef2ff;
        }

        td {
            padding: 14px 20px;
            color: #333;
            border-bottom: 1px solid #eee;
        }

        td:first-child {
            text-align: center;
            font-weight: 600;
            color: #1e3c72;
        }

        .negeri-name {
            font-weight: 500;
        }

        /* Footer */
        .footer {
            background-color: #1e3c72;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: auto;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
        }

        .footer-copyright {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .footer-company {
            font-weight: 600;
            margin-top: 5px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .navbar-container {
                flex-direction: column;
                height: auto;
                padding: 15px 0;
                gap: 15px;
            }

            .navbar-nav {
                flex-wrap: wrap;
                justify-content: center;
                gap: 10px;
            }

            .page-header h1 {
                font-size: 1.8rem;
            }

            th, td {
                padding: 12px 15px;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 480px) {
            .navbar-title {
                font-size: 1.2rem;
            }

            .page-header h1 {
                font-size: 1.5rem;
            }

            th, td {
                padding: 10px 12px;
                font-size: 0.85rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="navbar-container">
            <a href="/" class="navbar-brand">
                <img src="https://enovade.com/wp-content/uploads/2021/06/Enovade-Logo-Blue-words-at-right-side-160x52.png" alt="Enovade Logo" class="navbar-logo">
                
            </a>
            <ul class="navbar-nav">
                <li><a href="/">Home</a></li>
                <li><a href="#">About</a></li>
                <li><a href="#">Contact</a></li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <div class="page-header">
                <h1>Senarai Negeri Malaysia</h1>
                <p>Directory of all 14 states in Malaysia</p>
            </div>

            <div class="table-card">
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Negeri</th>
                            </tr>
                        </thead>
                        <tbody>
<?php foreach ($negeris as $negeri): ?>
                            <tr>
                                <td><?php echo htmlspecialchars((string)$negeri->id, ENT_QUOTES, 'UTF-8'); ?></td>
                                <td class="negeri-name"><?php echo htmlspecialchars($negeri->nama, ENT_QUOTES, 'UTF-8'); ?></td>
                            </tr>
<?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <p class="footer-copyright">&copy; 2026 Enovade Sdn. Bhd.. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
