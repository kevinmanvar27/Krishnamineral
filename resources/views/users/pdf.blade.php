<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Joining Letter - Krishna Minerals</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            background-color: #f1ecf8;
            padding: 40px 20px;
            margin: 0;
            color: #333;
        }

        .letter-container {
            background-color: #ffffff;
            padding: 40px 50px;
            max-width: 800px;
            margin: auto;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid #6c4bb6;
        }

        .header h1 {
            margin: 0;
            font-size: 28px;
            color: #4b2991;
            letter-spacing: 1px;
        }

        .header p {
            margin: 4px 0;
            font-size: 14px;
            color: #666;
        }

        .date {
            text-align: right;
            font-size: 14px;
            color: #555;
            margin-bottom: 30px;
        }

        .subject {
            font-size: 18px;
            font-weight: 600;
            text-decoration: underline;
            margin-bottom: 20px;
            color: #333;
        }

        .content p {
            margin-bottom: 18px;
            font-size: 16px;
        }

        .credentials {
            margin-top: 25px;
            background-color: #f8f6fc;
            border-left: 5px solid #6c4bb6;
            border-radius: 6px;
            padding: 20px 25px;
            font-size: 15px;
            color: #222;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px 30px;
        }

        .credentials p {
            margin: 4px 0;
        }

        .signature {
            margin-top: 50px;
            font-size: 16px;
        }

        .signature .name {
            font-weight: bold;
            margin-top: 40px;
        }

        .footer {
            margin-top: 60px;
            text-align: center;
            font-size: 13px;
            color: #888;
        }

        @media print {
            body {
                background-color: #fff;
                padding: 0;
            }

            .letter-container {
                box-shadow: none;
                border-radius: 0;
                margin: 0;
                padding: 40px;
            }
        }
    </style>
</head>
<body>

    <div class="letter-container">
        <div class="header">
            <h1>KRISHNA MINERALS</h1>
            <p>{{ isset($settings) && !empty($settings->address) ? $settings->address : 'Corporate Office: 123 Business Avenue, Industrial Area, City, ZIP' }}</p>
            <p>Email: hr@krishnaminerals.com | Phone: +91-9876543210</p>
        </div>

        <div class="date">
            <p>Date: {{ $created_at }}</p>
        </div>

        <p>To,</p>
        <p><strong>{{ $username }}</strong><br />
        Email: {{ $email }}</p>

        <p class="subject">Subject: Appointment Letter for the Position in {{ $department }} Department</p>

        <div class="content">
            <p>Dear {{ $username }},</p>

            <p>We are pleased to offer you the position in the <strong>{{ $department }}</strong> department at <strong>Krishna Minerals</strong>. Your monthly compensation will be <strong>₹{{ $salary }}</strong>.</p>

            <p>Your official joining date is <strong>{{ $created_at }}</strong>. Below are your login credentials to access the company systems and employee portal:</p>

            <div class="credentials">
                <p><strong>Username:</strong> {{ $username }}</p>
                <p><strong>Email:</strong> {{ $email }}</p>
                <p><strong>Password:</strong> {{ $password }}</p>
                <p><strong>Department:</strong> {{ $department }}</p>
                <p><strong>Salary:</strong> ₹{{ $salary }}</p>
                <p><strong>Joining Date:</strong> {{ $created_at }}</p>
            </div>

            <p>Please keep this information secure and do not share it with anyone. We are excited to have you on board and look forward to your valuable contributions.</p>

            <p>For any queries, please feel free to reach out to the HR department.</p>
        </div>

        <div class="signature">
            <p>Sincerely,</p>
            <p class="name">HR Manager</p>
            <p>Krishna Minerals</p>
        </div>

        <div class="footer">
            © {{ date('Y') }} Krishna Minerals. All rights reserved.
        </div>
    </div>

</body>
</html>
