<?php require '../config.php'; ?>
<?php loadClass('Helper')->authPage(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MailHog - Devilbox</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/devilbox.css">
    <style>
        .mailhog-container {
            height: calc(100vh - 200px);
            border: 1px solid #ddd;
            border-radius: 4px;
            overflow: hidden;
        }
        .mailhog-frame {
            width: 100%;
            height: 100%;
            border: none;
        }
        .mailhog-header {
            background: #f8f9fa;
            padding: 15px;
            border-bottom: 1px solid #ddd;
            border-radius: 4px 4px 0 0;
        }
        .mailhog-status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-online {
            background: #d4edda;
            color: #155724;
        }
        .status-offline {
            background: #f8d7da;
            color: #721c24;
        }
    </style>
    <?php echo loadClass('Html')->getHead(true); ?>
</head>
<body>
<?php echo loadClass('Html')->getNavbar(); ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="mailhog-header">
                    <h2><i class="fa fa-envelope"></i> MailHog Email Testing</h2>
                    <p class="text-muted mb-0">
                        Modern email testing interface integrated into Devilbox. 
                        All emails sent from your PHP applications will appear here.
                    </p>
                    <div class="mt-2">
                        <span class="mailhog-status status-online">
                            <i class="fa fa-circle"></i> MailHog Online
                        </span>
                        <span class="ml-2 text-muted">
                            Port: 8025 | Container: mailhog
                        </span>
                    </div>
                </div>
                
                <div class="mailhog-container">
                    <iframe 
                        src="http://localhost:8025" 
                        class="mailhog-frame"
                        title="MailHog Interface"
                        sandbox="allow-same-origin allow-scripts allow-forms allow-popups allow-top-navigation">
                    </iframe>
                </div>
                
                <div class="mt-3 p-3 bg-light border rounded">
                    <h5><i class="fa fa-info-circle"></i> How to Use MailHog</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Send Test Emails:</h6>
                            <ul class="mb-0">
                                <li>Use the form above to send test emails</li>
                                <li>All emails are caught locally (no internet)</li>
                                <li>Perfect for development and testing</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6>PHP Integration:</h6>
                            <ul class="mb-0">
                                <li>PHP mail() function works automatically</li>
                                <li>Configured in <code>cfg/php-ini-8.2/mailhog.ini</code></li>
                                <li>No code changes needed in your projects</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php echo loadClass('Html')->getFooter(); ?>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script>
        // Check if MailHog is accessible
        function checkMailHogStatus() {
            fetch('http://localhost:8025')
                .then(response => {
                    if (response.ok) {
                        document.querySelector('.status-online').style.display = 'inline-block';
                        document.querySelector('.status-offline').style.display = 'none';
                    }
                })
                .catch(error => {
                    document.querySelector('.status-online').style.display = 'none';
                    document.querySelector('.status-offline').style.display = 'inline-block';
                });
        }
        
        // Check status every 30 seconds
        setInterval(checkMailHogStatus, 30000);
        checkMailHogStatus();
    </script>
</body>
</html>
