<?php
$pageTitle = "Contact";
$submitted = $_SERVER["REQUEST_METHOD"] === "POST";
include "includes/header.php";
?>

<section class="page-header">
    <h1>Contact</h1>
    <p>Send a simple message about the Indigenous Plants Showcase project.</p>
</section>

<section class="section narrow">
    <?php if ($submitted): ?>
        <div class="notice success">
            <h2>Thank you for contacting us!</h2>
            <p>Your message has been received for this demo project.</p>
        </div>
    <?php endif; ?>

    <form class="contact-form" method="post" action="contact.php">
        <label>
            Name
            <input type="text" name="name" required>
        </label>
        <label>
            Email
            <input type="email" name="email" required>
        </label>
        <label>
            Message
            <textarea name="message" rows="6" required></textarea>
        </label>
        <button class="button" type="submit">Submit</button>
    </form>
</section>

<?php include "includes/footer.php"; ?>
