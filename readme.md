#Zoom Meeting API

##Setup
Find included _zoom.sql_ file for database structure. Import into your Mysql database.


##Configuration
**file**: _config.php_

#### Database
Use _config.php_ to set your mysql database credentials (host, user, password, database_name)

#### Zoom account
The API owner should have a zoom account. Follow the link to create a zoom APP and get _API_KEY_ and _API_SECRET_.
Zoom app link: https://marketplace.zoom.us/docs/guides/build/jwt-app

When you have zoom credentials, update _config.php_ with.


#### Sendgrid account
You need to have a sendgrid account (free plan is ok) to be able to send emails.
Follow the link to create pne and generate _API_KEY_ and update _config.php_ file *MAIL_API_KEY* and *MAIL_FROM_ADDRESS*

_The from address should match a verified Sender Identity in Sendgrid._

Sendgrid link: https://sendgrid.com/


##Request example

- content type: application/json
- request method: POST
- request body example  
````
  {
      "title":"first meeting",
      "duration":"45", (default 30 mins)
      "invite_emails": ["testemail@gmail.com", "test@tt.tt"]
      "password": "VnLtM7" (optional)
  }
````

##Response example
- content type: application/json
- response example  
````
  {
      {
          "error": false,
          "msg": "success",
          "data": {
              "join_url": "https://us05web.zoom.us/j/86304095779?pwd=cUU2Nk1L...",
              "start_url": "https://us05web.zoom.us/s/86304095779?zak=eyJ6bV9za20iOiJ6bV9vMm0iL...",
              "password": "VnLtM7",
              "emails_sent": 2
          }
      }
  }
````