-- SQL query to check the test reservation data
SELECT id, guest_name, email, phone, room_type, check_in, check_out, payment_method, status
FROM reservations
WHERE guest_name = 'Test User' AND payment_method = 'Cash';


