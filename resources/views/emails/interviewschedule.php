<!DOCTYPE html>
<html>
<head>
    <title>Interview Invitation</title>
</head>
<body>
    <p>Dear {{ $mailData['applicantName'] }},</p>

    <p>We are pleased to invite you for an interview for the position of <strong>{{ $mailData['jobTitle'] }}</strong> at <strong>{{ $mailData['companyName'] }}</strong>.</p>

    <p><strong>Interview Details:</strong></p>
    <ul>
        <li><strong>Type:</strong> {{ $mailData['interviewType'] }}</li>
        <li><strong>Date & Time:</strong> {{ $mailData['scheduledDate'] }}</li>
        @if ($mailData['interviewType'] === 'In-office')
        <li><strong>Location:</strong> {{ $mailData['companyLocation'] }}</li>
        @else
        <li>A link to the online interview will be provided closer to the time.</li>
        @endif
    </ul>

    <p>If you have any questions or need to reschedule, please contact us at {{ $mailData['contactNo'] }}.</p>

    <p>Best regards,<br>The {{ $mailData['companyName'] }} Team</p>
</body>
</html>
