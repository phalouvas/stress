## About

With this application you are able to run various stress tests on defined sms.to environments.

User have to define the host urls depended on the environment, along with selected user token, client_id and secret.

For each test the desired speed can be selected and user can start/stop each test.

The tests covered are the follow:

- Generate Access Token	
- Get Balance	
- Verify Number	
- Create list	
- Get Lists	
- Import contacts	
- Create contact	
- Get Contacts	
- Create Shortlink	
- Get Shortlinks	
- Hit Shortlink	
- Get messages	
- Estimate Single Message	
- Send Single Message	
- Estimate Campaign	
- Send Campaign	
- Estimate Personalized Campaign	
- Send Personalized Campaign

## Deploy process

- clone this repository
- copy file `.env.local` to `.env` and set urls and db credentials appropriately as per the webserver
- run command `composer install`
- run command `php artisan migrate`
- run command `php artisan db:seed`

User credentials
- admin@sms.to
- fGZLthkXDHxLnr.4FrgC

