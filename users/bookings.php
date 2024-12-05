<?php require "../includes/header.php"; ?>
<?php require "../config/config.php"; ?>

<?php
if (!isset($_SESSION['username'])) {
    echo "<script>window.location.href='" . APPURL . "'</script>";
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    if ($_SESSION['id'] != $id) {
        echo "<script>window.location.href='" . APPURL . "'</script>";
    }

    $bookings = $conn->query("SELECT * FROM bookings WHERE user_id = $id");
    $bookings->execute();

    $allBookings = $bookings->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo "<script>window.location.href='" . APPURL . "/404.php'</script>";
}
?>



<div class="container">

    <table class="table">
        <thead>
            <tr>
                <th scope="col">Email</th>
                <th scope="col">Full Name</th>
                <th scope="col">Phone Number</th>
                <th scope="col">Check In</th>
                <th scope="col">Check Out</th>
                <th scope="col">Hotel Name</th>
                <th scope="col">Room Name</th>
                <th scope="col">Reference</th>
                <th scope="col">Created At</th>





            </tr>
        </thead>
        <tbody>
            <?php foreach ($allBookings as $booking): ?>
                <tr>
                    <!-- <th scope="row">1</th> -->
                    <td><?php echo $booking['email']; ?></td>
                    <td><?php echo $booking['full_name']; ?></td>
                    <td><?php echo $booking['phone_number']; ?></td>
                    <td><?php echo $booking['check_in']; ?></td>
                    <td><?php echo $booking['check_out']; ?></td>
                    <td><?php echo $booking['hotel_name']; ?></td>
                    <td><?php echo $booking['room_name']; ?></td>
                    <td><?php echo $booking['reference']; ?></td>
                    <td><?php echo $booking['created_at']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php if (count($allBookings) == 0): ?>
        <div class="alert alert-warning" role="alert">
            No bookings found
        </div>
    <?php endif; ?>

</div>


<?php require "../includes/footer.php"; ?>