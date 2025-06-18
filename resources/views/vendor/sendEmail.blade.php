<body>
    <h2>Hai, {{ $booking->user->name }}.</h2>
    <p>Thank you for trusting our services.</p>
    <p>Detail Booking:</p>
    <ul>
        <li>Service : {{ $booking->service->description }}</li>
        <li>Date Booking : {{ $booking->date_booking }}</li>
    </ul>
    <p>Regards,<br>Service Anything</p>
</body>
