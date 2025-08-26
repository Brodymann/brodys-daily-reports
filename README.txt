Brody's Daily Progress – HTML/PHP/MySQL App

1) Edit config.php with your DreamHost MySQL credentials.
2) Upload all files to your domain root (brodys.site/). Keep the /admin folder.
3) Create tables via phpMyAdmin (or run schema.sql).
4) Create an admin user:
   INSERT INTO admins (email, pass_hash)
   VALUES ('you@yourmail.com', PASSWORD_HASH_HERE);

   To get PASSWORD_HASH_HERE, create a temporary PHP file with:
   <?php echo password_hash('YourStrongPassword', PASSWORD_BCRYPT); ?>
   Load it in the browser, copy the hash, then delete the file.

5) Public form: https://brodys.site/
   Admin:       https://brodys.site/admin/login.php
