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

### Build container
```
docker-compose build
```

### Start/stop container
```
docker-compose up -d   # Start
docker-compose stop    # Stop
```

### Check logs
```
docker-compose logs --follow --tail=50 smsto-stress
```

In case you did any changes on code, migrations, etc you need to force rebuild the container:
```
docker-compose build --no-cache
```

### Access it on:
```
http://localhost:8080
```

### User credentials

- admin@sms.to
- fGZLthkXDHxLnr.4FrgC

