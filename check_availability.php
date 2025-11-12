<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hotel_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$checkin = $_POST['checkin'];
$checkout = $_POST['checkout'];
$adults = $_POST['adults'];
$children = $_POST['children'];

// Function to check room availability
function checkRoomAvailability($conn, $roomType, $checkin, $checkout) {
    $sql = "SELECT COUNT(*) as booked FROM reservations WHERE room_type = ? AND ((checkin_date <= ? AND checkout_date > ?) OR(checkin_date < ? AND checkout_date >= ?) OR (checkin_date >= ? AND checkout_date <= ?))";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $roomType, $checkin, $checkin, $checkout, $checkout, $checkin, $checkout);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    // Total rooms available for this type
    $totalRooms = 10; // This should come from your database
    
    return $totalRooms - $row['booked'];
}

// Room types data (in a real app, this would come from database)
$roomTypes = [
    [
        'type' => 'deluxe',
        'name' => 'Deluxe Room',
        'price' => 120,
        'max_guests' => 2,
        'image' => 'https://images.unsplash.com/photo-1583845112203-29329902330e?ixlib=rb-4.0.3&auto=format&fit=crop&w=1374&q=80',
        'description' => 'Spacious room with king bed and harbor view, featuring luxury amenities and comfortable workspace.',
        'features' => ['King Bed', '35 m²', 'Free WiFi', 'Pool Access']
    ],
    [
        'type' => 'executive',
        'name' => 'Executive Suite',
        'price' => 180,
        'max_guests' => 2,
        'image' => 'https://images.unsplash.com/photo-1566669437685-2c5a585aded0?ixlib=rb-4.0.3&auto=format&fit=crop&w=1374&q=80',
        'description' => 'Luxurious suite with separate living area, premium amenities, and stunning ocean views.',
        'features' => ['King Bed', '55 m²', 'Free WiFi', 'Jacuzzi']
    ],
    [
        'type' => 'family',
        'name' => 'Family Room',
        'price' => 150,
        'max_guests' => 4,
        'image' => 'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80',
        'description' => 'Spacious accommodation with two queen beds, perfect for families or groups.',
        'features' => ['2 Queen Beds', '45 m²', 'Free WiFi', 'Child Friendly']
    ],
    [
        'type' => 'presidential',
        'name' => 'Presidential Suite',
        'price' => 350,
        'max_guests' => 2,
        'image' => 'https://images.unsplash.com/photo-1566669437692-2ce328f9f1c8?ixlib=rb-4.0.3&auto=format&fit=crop&w=1374&q=80',
        'description' => 'Our most luxurious accommodation with panoramic views, butler service, and premium amenities.',
        'features' => ['King Bed', '90 m²', 'Free WiFi', 'Butler Service']
    ]
];

// Check availability for each room type
$availableRooms = [];
foreach ($roomTypes as $room) {
    $available = checkRoomAvailability($conn, $room['type'], $checkin, $checkout);
    if ($available > 0 && ($adults + $children) <= $room['max_guests']) {
        $room['available'] = $available;
        $availableRooms[] = $room;
    }
}

// Close connection
$conn->close();

// Generate HTML response
if (empty($availableRooms)) {
    echo '<div class="no-availability" style="text-align: center; padding: 30px; background: #f8f5f2; border-radius: 4px;">
            <h3 style="color: var(--primary); margin-bottom: 15px;">No Rooms Available</h3>
            <p>We\'re sorry, but there are no rooms available for your selected dates.</p>
            <p>Please try different dates or contact us for assistance.</p>
          </div>';
} else {
    echo '<h3 style="color: var(--primary); margin-bottom: 20px; text-align: center;">Available Rooms</h3>';
    echo '<p style="text-align: center; margin-bottom: 30px;">We found '.count($availableRooms).' room types available for your dates</p>';
    
    foreach ($availableRooms as $room) {
        echo '<div class="room-card" style="display: flex; border: 1px solid #eee; border-radius: 4px; margin-bottom: 20px; overflow: hidden; transition: all 0.3s ease;">
                <div class="room-image" style="width: 250px; flex-shrink: 0;">
                    <img src="'.$room['image'].'" alt="'.$room['name'].'" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <div class="room-details" style="padding: 20px; flex: 1;">
                    <h3 style="font-family: \'Playfair Display\', serif; font-size: 22px; margin-bottom: 10px; color: var(--primary);">'.$room['name'].'</h3>
                    <p style="margin-bottom: 15px;">'.$room['description'].'</p>
                    
                    <div style="display: flex; flex-wrap: wrap; gap: 15px; margin: 15px 0;">
                        <div style="display: flex; align-items: center; gap: 5px; font-size: 14px;">
                            <i class="fas fa-bed" style="color: var(--secondary);"></i>
                            <span>'.$room['features'][0].'</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 5px; font-size: 14px;">
                            <i class="fas fa-ruler-combined" style="color: var(--secondary);"></i>
                            <span>'.$room['features'][1].'</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 5px; font-size: 14px;">
                            <i class="fas fa-wifi" style="color: var(--secondary);"></i>
                            <span>'.$room['features'][2].'</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 5px; font-size: 14px;">
                            <i class="fas fa-user" style="color: var(--secondary);"></i>
                            <span>Max Guests: '.$room['max_guests'].'</span>
                        </div>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <div style="font-size: 24px; font-weight: 700; color: var(--primary);">$'.$room['price'].' <span style="font-size: 16px; font-weight: 400; color: var(--gray);">/ night</span></div>
                            <div style="font-size: 14px; color: var(--primary); margin-top: 5px;">'.$room['available'].' rooms available</div>
                        </div>
                        <form action="book_now.php" method="post" style="display: inline;">
                            <input type="hidden" name="room_type" value="'.$room['type'].'">
                            <input type="hidden" name="checkin" value="'.$checkin.'">
                            <input type="hidden" name="checkout" value="'.$checkout.'">
                            <input type="hidden" name="adults" value="'.$adults.'">
                            <input type="hidden" name="children" value="'.$children.'">
                            <button type="submit" style="background-color: var(--secondary); color: var(--dark); border: none; padding: 10px 20px; border-radius: 4px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">Book Now</button>
                        </form>
                    </div>
                </div>
              </div>';
    }
}
?>