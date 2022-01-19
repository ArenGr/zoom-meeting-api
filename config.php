<?php
/* mysql database credentials */ 
define('DB_HOST', 'localhost');
define('DB_USERNAME', '');
define('DB_PASSWORD', '');
define('DB_NAME', 'zoom');

/* zoom app credentials */
define('API_KEY', '');
define('API_SECRET', '');


/* sendgrid */
define('MAIL_API_KEY', "");
define('MAIL_FROM_ADDRESS', ''); //should match a verified Sender Identity in Sendgrid

/*Do NOT change*/
define('MAIL_URL', 'https://api.sendgrid.com/v3/mail/send');
